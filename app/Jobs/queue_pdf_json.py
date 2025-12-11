# -*- coding: utf-8 -*-

import json
import os
from pathlib import Path
import pymysql
import cron_job_audit
import re

from dotenv import load_dotenv
load_dotenv()

hostname = os.getenv("DB_HOST")
username = os.getenv("DB_USERNAME")
password = os.getenv("DB_PASSWORD")
database = os.getenv("DB_DATABASE")
port = os.getenv("DB_PORT")
# hostname = '192.168.152.221'
# username = 'ocpl'
# password = 'ocpl@321'
# database = 'hitech-v2'
db_connection = pymysql.connect(host=hostname, user=username, passwd=password, db=database, port=int(port))
db_cursor = db_connection.cursor()

def create_pdf_json(db_connection):


    get_pdf_req_query = "SELECT pprq.id, pprq.app_id, psi.sql, psi.pdf_server_url, psi.reg_key, psi.pdf_type, " \
                        "pprq.prepared_json, pprq.process_type_id FROM pdf_print_requests_queue pprq " \
                        "LEFT JOIN pdf_service_info psi on pprq.certificate_name=psi.certificate_name " \
                        "where pprq.prepared_json=0 limit 10"
    total_pdf_request = db_cursor.execute(get_pdf_req_query)
    operation_count = 0

    if total_pdf_request > 0:

        '''
        increase the length of group_concat_max_len variable in mysql.
        but this query does not execute in python, need to do R&D.
        '''
        # set_session_query = "SET SESSION group_concat_max_len = 1200000"
        # db_cursor.execute(set_session_query)

        # Iterate each row of pdf_print_request_queue to generate pdf data
        for id, app_id, sql, pdf_server_url, reg_key, pdf_type, prepared_json, process_type_id in db_cursor.fetchall():

            pdf_request_id = id
            application_id = app_id
            process_type_id = process_type_id

            # Query for application's data from predefined SQL from pdf_service_info table
            pdf_data_query = sql.replace('{app_id}', str(application_id))
            execute_pdf_data_query = db_cursor.execute(pdf_data_query)

            if execute_pdf_data_query:
                # get application's data
                pdf_data = db_cursor.fetchone()

                # get application's column names
                pdf_data_columns = [i[0] for i in db_cursor.description]

                # Rearrange application's data with pair of column-value
                modified_app_data = get_app_data_with_column(pdf_data, pdf_data_columns, process_type_id, application_id)

                # Prepare json data format from application's data
                request_json_data = get_json_data(application_id, modified_app_data, reg_key, pdf_type)

                # Set prepared_json parameter, if request_json_data is pure json then set 1 else -1
                prepared_json = 1
                if not is_json(request_json_data):
                    prepared_json = -1

                # Get final data after replacing special character
                final_request_data = replace_special_characters(request_json_data)

                # increment total operation
                operation_count += 1
            else:
                print("something wrong your sql query!")
                final_request_data = ''
                prepared_json = -9

            # Update final json data in pdf request
            json_data_update_query = "UPDATE pdf_print_requests_queue SET prepared_json=" + str(
                prepared_json) + ", job_sending_status=0, no_of_try_job_sending=0, job_receiving_status=0, no_of_try_job_receving=0, certificate_link='', url_requests='" + final_request_data + "' WHERE id=" + str(
                pdf_request_id)
            db_cursor.execute(json_data_update_query)
            print(final_request_data)
        # End of Iterate each row of pdf_print_request_queue to generate pdf data

    else:
        print("Not found any row")

    # Store cron job audit
    comment = ""
    time_index = 0
    file_name = str(os.path.basename(__file__))
    full_path = str(Path(__file__).absolute())
    cron_job_audit.cronAuditSave(time_index, operation_count, comment, file_name, full_path, db_connection)
    # End store cron job audit


def is_json(json_data):
    try:
        json_object = json.loads(json_data)
    except ValueError as e:
        return False
    return True


def get_app_data_with_column(pdf_data, pdf_data_columns, process_type_id, application_id):
    modified_app_data = {}
    data_key = 0
    for key in pdf_data_columns:
        modified_app_data[key] = pdf_data[data_key]
        data_key += 1

    qrcode_query = "SELECT id, signature_type FROM pdf_signature_qrcode " \
                   "WHERE process_type_id='"+process_type_id+"' AND app_id='"+application_id+"'"
    qrcode_query_execute = db_cursor.execute(qrcode_query)
    if qrcode_query_execute > 0:
        for id, signature_type in db_cursor.fetchall():
            if signature_type == 'final':
                modified_app_data['a_urlimg'] = str(hostname) + "/cron/signature_api/rest/signature?signature_id=" + str(id)
            elif signature_type == 'first'
                modified_app_data['b_urlimg'] = str(hostname) + "/cron/signature_api/rest/signature?signature_id=" + str(id)
    return modified_app_data


def get_json_data(application_id, modified_app_data, reg_key, pdf_type):
    pdf_request_data = {'data': {
        "json": modified_app_data,
        "reg_key": reg_key,
        "pdf_type": pdf_type,
        "ref_id": application_id,
    }}
    pdf_request_data['data']['param'] = {
        "app_id": application_id
    }
    return json.dumps(pdf_request_data)


def replace_special_characters(request_json_data):
    character_replace = request_json_data.replace("\\r\\n", " ")
    character_replace1 = character_replace.replace("\\n", " ")
    character_replace2 = character_replace1.replace("\\r", " ")
    character_replace3 = character_replace2.replace("\\", "")
    character_replace4 = character_replace3.replace("\"[", "[")
    character_replace5 = character_replace4.replace("]\"", "]")
    #character_replace6 = character_replace5.replace("'", "â€™")
    character_replace6 = character_replace5.replace("'", "’")
    final_request_data = character_replace6
    return final_request_data


'''
Run pdf_json function to generate pdf data
'''
create_pdf_json(db_connection)
