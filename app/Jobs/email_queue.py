#!/usr/bin/python
# -*- coding: utf-8 -*-

import requests
import cron_job_audit
import pymysql
from datetime import datetime
import os
from pathlib import Path  # python3 only
from dotenv import load_dotenv
import time

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


def email_queue(conn):
    cur = conn.cursor()
    pending_email_query = "SELECT id, app_id, caption, email_to, email_cc, email_content, email_no_of_try, attachment, email_subject, attachment_certificate_name FROM email_queue WHERE email_status=0 AND email_to !='' ORDER BY id DESC LIMIT 15"
    pending_email_list = cur.execute(pending_email_query)

    count_sent_mail = 0
    if pending_email_list > 0:
        access_token = ''
        for id, app_id, caption, email_to, email_cc, email_content, email_no_of_try, attachment, email_subject, attachment_certificate_name in cur.fetchall():

            email_to = email_to.replace("'", "")
            email_cc = email_cc.replace("'", "")
            email_content = email_content
            count_sent_mail = count_sent_mail + 1

            # Check that is it the mail with approval certificate,
            # if is it then need to check the certificate is available or not
            if attachment_certificate_name:
                attachment_content_split = attachment_certificate_name.split('.')
                # cer_exp[0] = TABLE NAME, cer_exp[1] = FILED NAME
                if attachment_content_split[0] is not None:
                    certificate_link_query = "SELECT " + str(attachment_content_split[1]) + " FROM " + str(
                        attachment_content_split[0]) + " where id= " + str(
                        app_id) + " AND " + str(attachment_content_split[1]) + "!=''"
                    certificate_link = cur.execute(certificate_link_query)

                    if certificate_link == 0:
                        print(" for this email (" + email_cc + ") certificate not generated. please try again!")
                        continue
                    else:
                        certificate_link = cur.fetchone()
                        email_content = email_content.replace('{$attachment}', certificate_link[0])

            if access_token == '':
                token_response = getToken()
                if token_response['responseCode'] == 0:
                    print(token_response['msg'])
                    continue
                access_token = token_response['data']

            # Increment the number of try to email sending for current record
            email_no_of_try = email_no_of_try + 1

            try:
                sms_api_url_for_token = os.getenv('EMAIL_API_URL_FOR_SEND',
                                                  'https://api-k8s.oss.net.bd/api/broker-service/email/send_email')
                base_email_for_api = os.getenv('EMAIL_FROM_FOR_EMAIL_API', 'oss@bida.gov.bd')
                if email_subject:
                    email_from_for_email_api = email_subject + ' <' + base_email_for_api + '>'
                else:
                    email_from_for_email_api = base_email_for_api

                payload = {'sender': email_from_for_email_api,
                           'receipant': email_to,
                           'subject': email_subject,
                           'bodyText': '',
                           'bodyHtml': email_content,
                           'cc': email_cc,
                           }
                # files = [
                #     ('file', open('/C:/Users/Samad/Downloads/SponsorsDirectors.php', 'rb'))
                # ]
                headers = {}
                sms_api_url_for_token = sms_api_url_for_token + "?access_token=" + access_token
                # response = requests.request("POST", sms_api_url_for_token, headers=headers, data=payload, files=files)
                email_response = requests.request("POST", sms_api_url_for_token, headers=headers, data=payload)
                email_response_for_db = email_response.text
                email_response_json = email_response.json()

                email_status = 0
                email_response_id = 0
                if email_no_of_try > 10:
                    # data is invalid, abort sending
                    email_status = -9

                if email_response_json['status'] and email_response_json['status'] == 200:
                    email_status = 1
                    email_response_id = email_response_json['data']['id']
                    print("Successfully sent Email to - " + email_to)
                else:
                    print("Could not send Email to - " + email_to)
            except Exception as exception:
                email_response_for_db = str(exception)
                print('Something went wrong...' + email_response_for_db)
                if email_no_of_try > 10:
                    # data is invalid, abort sending
                    email_status = -9
                else:
                    email_status = 0

            status_update_query = "UPDATE email_queue SET email_status='" + str(
                email_status) + "',email_response_id='" + str(email_response_id) + "',email_response='" + str(
                email_response_for_db) + "', email_no_of_try='" + str(email_no_of_try) + "' WHERE id=" + str(id)
            cur.execute(status_update_query)
            myConnection.commit()

    if count_sent_mail == 0:
        print("No Email in queue to send! " + datetime.now().strftime('%Y-%m-%d %H:%M:%S'))

    # Store CronJob Audit information.
    comment = "Email Sending"
    time_index = 0
    file_name = str(os.path.basename(__file__))
    full_path = str(Path(__file__).absolute())
    cron_job_audit.cronAuditSave(time_index, count_sent_mail, comment, file_name, full_path, conn)
    # End of Store CronJob Audit information.


# execute the email queue function
email_queue(myConnection)
myConnection.close()
