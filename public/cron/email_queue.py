#!/usr/bin/python                       #created by Reyad

import smtplib
import json
#import mysql.connector


import pymysql
#import MySQLdb
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
from datetime import datetime
from email.mime.application import MIMEApplication

hostname = '103.219.147.21'
username = 'ocpl'
password = 'Ocpl@2017'
database = 'dev-bida'
#myConnection = mysql.connector.connect( host=hostname, user=username, passwd=password, db=database )

myConnection = pymysql.connect( host=hostname, user=username, passwd=password, db=database )
#myConnection = MySQLdb.connect(host=hostname, user=username, passwd=password, db=database)



def email_queue(conn):

    cur = conn.cursor()
    config = "SELECT caption,details FROM configuration WHERE caption= 'MAIL_CONFIGURATION'"
    cur.execute(config)
    row = cur.fetchone()

    if row:
        config_data = json.loads(row[1])
        MAIL_USERNAME = config_data["MAIL_USERNAME"]
        MAIL_PASSWORD = config_data["MAIL_PASSWORD"]
        MAIL_HOST = config_data["MAIL_HOST"]
        MAIL_PORT = config_data["MAIL_PORT"]
    else:
        # Default configuration
        MAIL_USERNAME = 'beza.ocpl@gmail.com'
        MAIL_PASSWORD = 'beza123*#'
        MAIL_HOST = 'smtp.gmail.com'
        MAIL_PORT = 465

    # for details in cur.fetchall()
    query = "SELECT id, email_to,email_cc,email_content,no_of_try,attachment,email_subject " \
            "FROM email_queue WHERE email_status=0 AND email_to!='' ORDER BY id DESC LIMIT 5"
    result = cur.execute(query)
    count = 0
    if result > 0:
        for id, email_to, email_cc, email_content, no_of_try, attachment, email_subject in cur.fetchall():
            print(id, email_to)
            # exit();
            sent_from = MAIL_USERNAME
            to = [email_to]
            html = email_content
            msg = MIMEMultipart('alternative')
            # msg['Subject'] = email_subject
            # cc = email_cc
            # msg['cc'] = email_cc
            msg["Subject"] = email_subject
            msg["From"] = MAIL_USERNAME
            msg["To"] = email_to
            msg["Cc"] = email_cc
            # print(msg["To"].split(","))
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
                server = smtplib.SMTP_SSL(host=MAIL_HOST, port=MAIL_PORT)
                server.login(MAIL_USERNAME, MAIL_PASSWORD)
                # text = msg.as_string()
                server.sendmail(str(msg["From"]), [msg["To"]] + cc, msg.as_string())
                # server.sendmail(msg["From"], msg["To"].split(",") + msg["Cc"].split(","), msg.as_string())

                # server.sendmail(sent_from, [to, cc], text)
                server.quit()
                # server = smtplib.SMTP(MAIL_HOST, MAIL_PORT)
                # server.ehlo()
                # server.login(MAIL_USERNAME, MAIL_PASSWORD)
                # server.sendmail(sent_from, [to, cc], msg.as_string())
                # server.close()
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
            conn.commit()
            print(mail_messages)

    if count == 0:
        print("No Email in queue to send! " + datetime.now().strftime('%Y-%m-%d %H:%M:%S'))

print("Using MySQLdb…")
email_queue(myConnection)
myConnection.close()
