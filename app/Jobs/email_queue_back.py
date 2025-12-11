#!/usr/bin/python                       #created by Reyad

import smtplib
import json
# import datetime
# import mysql.connector

import pymysql
# import MySQLdb
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
from datetime import datetime
# import datetime
from email.mime.application import MIMEApplication

hostname = '103.219.147.21'
username = 'ocpl'
password = 'Ocpl@2017'
database = 'dev-bida'

# myConnection = mysql.connector.connect( host=hostname, user=username, passwd=password, db=database )
myConnection = pymysql.connect(host=hostname, user=username, passwd=password, db=database)


def email_queue(conn):
    cur = conn.cursor()
    maximum_total_mail = 5000

    # start email configuration setup
    sql = "SELECT id,from_email,server_details, sent_total_mail, last_updated_date  " \
          "FROM email_configuration WHERE is_active=1 AND sent_total_mail >=" + str(maximum_total_mail) + ""
    cur.execute(sql)
    for id, from_email, server_details, sent_total_mail, last_updated_date in cur.fetchall():
        application_updated_date = last_updated_date
        current_date = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
        datetimeFormat = '%Y-%m-%d %H:%M:%S'
        date1 = str(application_updated_date)
        diff = datetime.strptime(current_date, datetimeFormat) \
               - datetime.strptime(date1, datetimeFormat)

        # checking one day 24 hours after clear total mail
        if diff.days >= 1:
            query3 = "INSERT INTO email_configuration_history(from_email,sent_total_mail,server_details," \
                     "created_at,updated_at) VALUES(%s,%s,%s,%s,%s)"
            args = (from_email, sent_total_mail, server_details, current_date, current_date)
            cur.execute(query3, args)

            query4 = "UPDATE email_configuration SET sent_total_mail = %s, updated_at = %s, last_updated_date = %s" \
                     " where id= %s"
            data2 = (0, str(current_date), str(current_date), id)
            cur.execute(query4, data2)
            conn.commit()
        # end of email configuration setup

    config = "SELECT server_details,sent_total_mail, id " \
             "FROM email_configuration WHERE is_active=1 AND sent_total_mail <="+str(maximum_total_mail)+" "
    cur.execute(config)
    row = cur.fetchone()

    if row:
        config_data = json.loads(row[0])
        MAIL_USERNAME = config_data["MAIL_USERNAME"]
        MAIL_PASSWORD = config_data["MAIL_PASSWORD"]
        MAIL_HOST = config_data["MAIL_HOST"]
        MAIL_PORT = config_data["MAIL_PORT"]
    else:
        print('Today Email Quata has been full. Please check email configuration table!')
        exit()

    #     # Default configuration
    #     MAIL_USERNAME = 'ossbida@bidaquickserv.org'
    #     MAIL_PASSWORD = 'mKFxxgf3'
    #     MAIL_HOST = 'smtp.bidaquickserv.org'
    #     # MAIL_HOST = 'smtp.gmail.com'
    #     MAIL_PORT = 587
    #     #MAIL_PORT = 465 #ssl

    # for details in cur.fetchall()
    query = "SELECT id, email_to,email_cc,email_content,no_of_try,attachment,email_subject " \
            "FROM email_queue WHERE email_status=0 AND email_to!='' ORDER BY id DESC LIMIT 5"
    result = cur.execute(query)
    count = 0
    if result > 0:
        for id, email_to, email_cc, email_content, no_of_try, attachment, email_subject in cur.fetchall():
            print("from: " + MAIL_USERNAME)
            print('to', email_to)
            # exit();
            html = email_content
            msg = MIMEMultipart('alternative')
            # msg['Subject'] = email_subject
            # cc = email_cc
            # msg['cc'] = email_cc
            msg["Subject"] = email_subject
            msg["From"] = MAIL_USERNAME
            msg["To"] = email_to
            msg["Cc"] = email_cc
            if msg["Cc"] is not None:
                cc = msg["Cc"].split(",")
            else:
                cc = ['']

            if msg["To"]:
                msg["To"].split(",")

            # part1 = MIMEText(text, 'plain')
            part2 = MIMEText(html, 'html')
            msg.attach(part2)

            # Attach pdf file to the email
            if attachment:
                attachment_file = MIMEApplication(open(attachment, "rb").read())
                attachment_file.add_header('Content-Disposition', 'attachment', filename=attachment)
                msg.attach(attachment_file)

            try:
                if MAIL_HOST == 'smtp.gmail.com':
                    server = smtplib.SMTP_SSL(host=MAIL_HOST, port=MAIL_PORT)
                else:
                    server = smtplib.SMTP(MAIL_HOST, MAIL_PORT)
                server.login(MAIL_USERNAME, MAIL_PASSWORD)
                server.sendmail(str(msg["From"]), [msg["To"]] + cc, msg.as_string())
                server.quit()
                # server.ehlo()
                status = 1
                mail_messages = "Email  has been sent on " + datetime.now().strftime('%Y-%m-%d %H:%M:%S')
                count += 1

            except ValueError:
                no_of_try = no_of_try + 1
                if no_of_try > 10:
                    status = -9
                else:
                    status = 0
                mail_messages = 'Something went wrong...' + ValueError

            query1 = "UPDATE email_queue SET email_status = %s where id= %s"
            data = (status, id)
            cur.execute(query1, data)

            query2 = "UPDATE email_configuration SET sent_total_mail =%s,updated_at =%s where id=%s "
            data5 = (str(row[1]+1), datetime.now().strftime("%Y-%m-%d %H:%M:%S"), row[2])
            cur.execute(query2, data5)

            conn.commit()
            print(mail_messages)

    if count == 0:
        print("No Email in queue to send! " + datetime.now().strftime('%Y-%m-%d %H:%M:%S'))


print("Using MySQLdb…")
email_queue(myConnection)
myConnection.close()
