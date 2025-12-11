import json
import os
#import urllib.parse
import urllib

try:
    from urllib.parse import urlparse
except ImportError:
    from urlparse import urlparse
import cron_job_audit
import requests
import pymysql
from pathlib import Path
from dotenv import load_dotenv

load_dotenv()

hostname = os.getenv("DB_HOST")
username = os.getenv("DB_USERNAME")
password = os.getenv("DB_PASSWORD")
database = os.getenv("DB_DATABASE")

db_connection = pymysql.connect(host=hostname, user=username, passwd=password, db=database)

def sent_pdf_request(db_connection):
    db_cursor = db_connection.cursor()
    get_new_request_query = "SELECT id, url_requests, pdf_server_url, no_of_try_job_sending FROM pdf_print_requests_queue WHERE job_sending_status=0 and prepared_json=1 ORDER BY id DESC LIMIT 10"
    total_new_request = db_cursor.execute(get_new_request_query)

    time_index = 0
    operation_count = 0
    if total_new_request > 0:

        for id, url_requests, pdf_server_url, no_of_try_job_sending in db_cursor.fetchall():
            url_requests = url_requests.replace("\"{", "{")
            encoded_data = url_requests.replace("}\"", "}")

            request_data = urllib.parse.quote(encoded_data)

            update_sending_req = "UPDATE pdf_print_requests_queue SET job_sending_request ='" + str(request_data) +"' WHERE id= %s" % (id)
            db_cursor.execute(update_sending_req)

            url = pdf_server_url + 'api/new-job?requestData=' + request_data
            payload = ""
            headers = {
                'cache-control': "no-cache"
            }

            response = requests.request("POST", url, data=payload, headers=headers, verify=False)
            decodedResponse = json.loads(response.text)

            update_sending_res = "UPDATE pdf_print_requests_queue SET job_sending_response ='" + str(response) +"' WHERE id= %s" % (id)
            db_cursor.execute(update_sending_res)

            no_of_try = no_of_try_job_sending + 1

            if decodedResponse['response']:

                if decodedResponse['response']['status'] == '-1' or decodedResponse['response']['status'] == '1':
                    status = 1
                elif no_of_try > 20:
                    status = -9
                else:
                    status = 0

                update_pdf_queue_table = "UPDATE pdf_print_requests_queue SET job_sending_status= %s, no_of_try_job_sending= %s WHERE id= %s" % (status, no_of_try, id)
                db_cursor.execute(update_pdf_queue_table)
                db_connection.commit()

                operation_count += 1
        print("No of Pdf print request is : " + str(operation_count))

    file_name = str(os.path.basename(__file__))
    full_path = str(Path(__file__).absolute())
    comment = "sending";
    cron_job_audit.cronAuditSave(time_index, operation_count, comment, file_name, full_path, db_connection)

sent_pdf_request(db_connection)


def receive_pdf_response(db_connection):
    db_cursor = db_connection.cursor()
    get_response_query = "SELECT id, app_id, pdf_server_url, reg_key, pdf_type, table_name, field_name, no_of_try_job_receving FROM pdf_print_requests_queue WHERE job_receiving_status = 0 and job_sending_status=1 ORDER BY id DESC LIMIT 10"
    total_new_response = db_cursor.execute(get_response_query)

    time_index = 0
    operation_count = 0
    if total_new_response > 0:

        for id, app_id, pdf_server_url, reg_key, pdf_type, table_name, field_name, no_of_try_job_receving in db_cursor.fetchall():
            pdf_request_data = {'data': {
                "reg_key": reg_key,
                "pdf_type": pdf_type,
                "ref_id": str(app_id),
            }}
            pdf_request_data['data']['param'] = {
                "app_id": str(app_id)
            }

            request_data = json.dumps(pdf_request_data)

            update_receiving_req = "UPDATE pdf_print_requests_queue SET job_receiving_request ='" + str(request_data) + "' WHERE id= %s" % (id)
            db_cursor.execute(update_receiving_req)

            url = pdf_server_url + 'api/job-status?requestData=' + request_data
            payload = ""
            headers = {
                'cache-control': "no-cache"
            }

            response = requests.request("POST", url, data=payload, headers=headers, verify=False)
            decodedResponse = json.loads(response.text)

            update_receiving_res = "UPDATE pdf_print_requests_queue SET job_receiving_response ='" + str(response) + "' WHERE id= %s" % (id)
            db_cursor.execute(update_receiving_res)

            attachmentUrl = ''
            doc_id = ''
            no_of_try = no_of_try_job_receving + 1

            if decodedResponse['response']:

                if decodedResponse['response']['status'] == '-1' or decodedResponse['response']['status'] == '1':
                    attachmentUrl = decodedResponse['response']['download_link']
                    doc_id = decodedResponse['response']['doc_id']

                    sql3 = "UPDATE " + str(table_name) + " SET " + str(field_name) + " = '" + str(attachmentUrl) + "' WHERE id = %s" % (app_id)
                    db_cursor.execute(sql3)
                    status = 1;
                elif no_of_try > 25:
                    status = -9
                else:
                    status = 0

                sql2 = "UPDATE pdf_print_requests_queue SET certificate_link = '" + str(attachmentUrl) + "', doc_id = '" + str(doc_id) + "', job_receiving_status = %s, no_of_try_job_receving= %s WHERE id = %s" % (status, no_of_try, id);
                db_cursor.execute(sql2)
                db_connection.commit()

                operation_count += 1
        if operation_count == 0:
            print("<br/> No PDF in queue to send!")

    file_name = str(os.path.basename(__file__))
    full_path = str(Path(__file__).absolute())
    comment = "receiving";
    cron_job_audit.cronAuditSave(time_index, operation_count, comment, file_name, full_path, db_connection)

receive_pdf_response(db_connection)
