#!/usr/bin/python                   #created by Reyad
import os
from pathlib import Path  # python3 only
from datetime import datetime


def cronAuditSave(time_index, number, comment, file_name, full_path, conn):
    try:
        cur = conn.cursor()
        query3 = "INSERT INTO cron_job_audit(file_name, full_address, record_index, no_of_record, comments, cron_run_time)" \
                 " VALUES(%s,%s,%s,%s,%s,%s) " \
                 " ON DUPLICATE KEY UPDATE " \
                 " file_name =%s, full_address=%s, record_index=%s, no_of_record=%s, comments=%s, cron_run_time=%s"
        args = (str(file_name), str(full_path), str(time_index), str(number), str(comment), str(datetime.now().strftime('%Y-%m-%d %H:%M:%S')),
                str(file_name), str(full_path), str(time_index), str(number), str(comment),  str(datetime.now().strftime('%Y-%m-%d %H:%M:%S')))
        cur.execute(query3, args)
        conn.commit()
        return True
    except ValueError:
        return "something want wrong! " + datetime.now().strftime('%Y-%m-%d %H:%M:%S')
