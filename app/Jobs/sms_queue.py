#!/usr/bin/python
import time
import pymysql
import requests
import os
import cron_job_audit
from pathlib import Path  # python3 only
from datetime import datetime
from dotenv import load_dotenv

load_dotenv()
hostname = os.getenv("DB_HOST")
username = os.getenv("DB_USERNAME")
password = os.getenv("DB_PASSWORD")
database = os.getenv("DB_DATABASE")

myConnection = pymysql.connect(host=hostname, user=username, passwd=password, db=database)


def getToken():
    cur = myConnection.cursor()
    api_token_query = "Select * from configuration where caption ='email_sms_api_token'"
    api_token_query_exec = cur.execute(api_token_query)
    api_token_result = cur.fetchone()

    if api_token_query_exec == 1:
        api_token = api_token_result[2]
        token_expire_time = api_token_result[4]
        current_time_in_seconds = round(time.time())
        if api_token and int(token_expire_time) > current_time_in_seconds:
            data = {
                'responseCode': 1,
                'data': api_token
            }
            return data

    try:
        sms_api_url_for_token = os.getenv('SMS_API_URL_FOR_TOKEN',
                                          'https://idp.oss.net.bd/auth/realms/dev/protocol/openid'
                                          '-connect/token')
        sms_client_id = os.getenv('SMS_CLIENT_ID', 'bida-client')
        sms_client_secret = os.getenv('SMS_CLIENT_SECRET', '453e84e7-3b5c-4268-ad08-4f7e64bf7615')
        sms_grant_type = os.getenv('SMS_GRANT_TYPE', 'client_credentials')
        token_request_data = {
            "client_id": sms_client_id,
            "client_secret": sms_client_secret,
            "grant_type": sms_grant_type
        }

        token_response = requests.post(sms_api_url_for_token, data=token_request_data)
        token_response_json = token_response.json()
        access_token = token_response_json['access_token']
        data = {
            'responseCode': 1,
            'data': access_token
        }

        # Update token into Database
        token_expire_time = (round(time.time()) + token_response_json['expires_in']) - 60
        token_record_query = "select exists(select * from configuration where caption='email_sms_api_token') as token_exists"
        token_record_query_exec = cur.execute(token_record_query)
        token_record_result = cur.fetchone()

        if token_record_result[0] == 1:
            token_update_query = "UPDATE configuration SET value='" + str(access_token) + "',value2='" + str(
                token_expire_time) + "' WHERE caption='email_sms_api_token'"
        else:
            token_update_query = "INSERT INTO configuration (caption, value, value2) VALUES ('email_sms_api_token', '" + str(
                access_token) + "', '" + str(token_expire_time) + "')"

        cur.execute(token_update_query)
        myConnection.commit()
    except Exception as e:
        data = {
            'responseCode': 0,
            'msg': str(e),
            'data': ''
        }

    return data


def sms_queue(conn):
    current_execute_file_path = os.path.basename(__file__)
    get_time_index = current_execute_file_path.split('=')
    if 1 < len(get_time_index):
        time_index = int(get_time_index[1])
    else:
        time_index = 0

    hour = 48
    limit = 20
    offset = 0

    if time_index > 15:
        exit()
    elif time_index > 0:
        offset = (time_index * limit) + limit

    cur = conn.cursor()
    pending_sms_query = "SELECT id, sms_content, sms_to,sms_status,sent_on,sms_no_of_try " \
                        " FROM email_queue WHERE sms_to!=''" \
                        " AND sms_no_of_try<3 " \
                        " AND (sms_status = 0 OR (sms_status=-1 AND ADDDATE(sent_on, INTERVAL 180 SECOND) < NOW()))" \
                        " AND `created_at` >= DATE_SUB(NOW(), INTERVAL " + str(hour) + " HOUR)" \
                                                                                       " ORDER BY id DESC  limit " + str(
        offset) + ", " + str(limit) + " "
    pending_sms_list = cur.execute(pending_sms_query)

    count_sent_sms = 0

    if pending_sms_list > 0:

        access_token = ''
        for id, sms_content, sms_to, sms_status, sent_on, sms_no_of_try in cur.fetchall():
            try:
                sent_on = str(sent_on)
                mobile_no = sms_to.replace('+88', '')
                count_sent_sms += 1

                update_current_record = "UPDATE email_queue SET sms_status=%s, sent_on=NOW(), " \
                                        " sms_no_of_try=%s, cron_id=%s" \
                                        " WHERE id=%s AND sms_status=%s" \
                                        " AND (sent_on IS NULL OR sent_on=%s )"
                data_set_for_update_query = ('-1', sms_no_of_try + 1, time_index, id, sms_status, sent_on)
                update_current_record_status = cur.execute(update_current_record, data_set_for_update_query)
                if update_current_record_status is None:
                    print('Something went wrong during record update before SMS sending to ' + sms_to)
                    continue

                if access_token == '':
                    token_response = getToken()
                    if token_response['responseCode'] == 0:
                        print(token_response['msg'])
                        continue
                    access_token = token_response['data']

                sms_api_url = os.getenv('SMS_API_URL_FOR_SEND',
                                        'https://api-k8s.oss.net.bd/api/broker-service/sms/send_sms')
                headers = {"Authorization": "Bearer " + access_token}
                params = {"msg": sms_content, "destination": mobile_no}
                response = requests.post(sms_api_url, json=params, headers=headers)
                sms_response_for_db = response.text
                all_response = response.json()

                if all_response['status'] == 200:
                    sms_response_id = all_response['data']['id']
                    status_update_query = "UPDATE email_queue set sms_status =%s, sms_response_id =%s, sms_response=%s, sent_on = %s  WHERE id =%s "
                    dataSet_of_status_update_query = (
                        1, sms_response_id, str(sms_response_for_db), datetime.now().strftime('%Y-%m-%d %H:%M:%S'),
                        id)
                    mail_messages = "SMS has been sent to - " + sms_to
                elif all_response['status'] == 400:
                    status_update_query = "UPDATE email_queue set sms_status=%s, sms_response=%s WHERE id =%s "
                    dataSet_of_status_update_query = ('-9', str(sms_response_for_db), id)
                else:
                    status_update_query = "UPDATE email_queue set sms_status =%s , sms_response= %s WHERE id =%s"
                    dataSet_of_status_update_query = (0, str(sms_response_for_db), id)
                cur.execute(status_update_query, dataSet_of_status_update_query)
                conn.commit()
            except Exception as exception:
                mail_messages = 'Something went wrong...' + str(exception)

            print(mail_messages)

    if count_sent_sms == 0:
        print("No SMS in queue to send! " + datetime.now().strftime('%Y-%m-%d %H:%M:%S'))

    # Store CronJob Audit information.
    comment = "SMS Sending"
    file_name = str(os.path.basename(__file__))
    full_path = str(Path(__file__).absolute())
    cron_job_audit.cronAuditSave(time_index, count_sent_sms, comment, file_name, full_path, conn)
    # End of Store CronJob Audit information.


sms_queue(myConnection)
myConnection.close()
