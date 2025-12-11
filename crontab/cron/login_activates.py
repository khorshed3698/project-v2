#!/usr/bin/python                   #created by Reyad
import pymysql
import smtplib
import json

from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
from datetime import datetime
from email.mime.application import MIMEApplication

hostname = '103.219.147.21'
username = 'ocpl'
password = 'Ocpl@2017'
database = 'dev-bida'

myConnection = pymysql.connect(host=hostname, user=username, passwd=password, db=database)


def email_queue(conn):

    cur = conn.cursor()
    query = "select users.id,users.user_email, max(login_dt) as login_dt, desk_id from users left join user_logs " \
            "on users.id = user_logs.user_id where user_type = '4x404' and login_dt >= ( CURRENT_DATE - 1 ) " \
            "AND login_dt < CURRENT_DATE group by user_email"
    result = cur.execute(query)
    if result > 0:
        for id, user_email, login_dt, desk_id in cur.fetchall():
            print(login_dt)
            query2 = "SELECT id, tracking_no, desk_id,updated_at FROM process_list WHERE desk_id in("+desk_id+") " \
                     "AND process_list.updated_at >= '"+str(login_dt)+"' "
                     # "AND process_list.updated_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)"
            # print(query2)
            last24hours_application = cur.execute(query2)

            query3 = "select * from process_list where desk_id in ("+desk_id+")"
            total_application = cur.execute(query3)

            print(id, user_email, login_dt, "desk_id: "+str(desk_id), "last24hours_application:"+str(last24hours_application), "total_application:"+str(total_application))
            insert_email_queue(id, user_email, login_dt, last24hours_application, total_application, conn)
            print("data insert successfully")


def insert_email_queue(user_id, user_email, login_dt, last24hours_application, total_application, conn):
    cur = conn.cursor()
    query3 = "select value from configuration where caption= 'PROJECT_BASE_URL' "
    cur.execute(query3)
    base_url = cur.fetchone()

    convert_date_time = login_dt.strftime('%d-%B-%Y %I:%M %p')

    query = "INSERT INTO email_queue(user_id,email_content,email_to,email_subject,created_at,updated_at) " \
            "VALUES(%s,%s,%s,%s,%s,%s)"
    email_content = "We would like to inform you that your last login date:time is <b>" + str(convert_date_time)+" at "+str(base_url[0])+"</b> "
    email_content += "<br/>In the mean time,<br/>Total pending application in your desk:"+str(total_application)
    email_content += "<br/>Number of new applications in your desk after your last login time is:"+str(last24hours_application)
    email_content += "<br/><br/><b>This is a system generated email. Please don't reply</b>"
    email_to = user_email
    subject = "Login Information"
    now = datetime.now()
    formatted_date = now.strftime('%Y-%m-%d %H:%M:%S')
    created_at = formatted_date
    updated_at = formatted_date
    html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'\
           '<html xmlns="http://www.w3.org/1999/xhtml">'\
           '<head>'\
           '<title>    Application Update Information for Co-Branded Card'\
           '</title>'\
           '<link href="https://fonts.googleapis.com/css?family=Vollkorn" rel="stylesheet" type="text/css">'\
           '<style type="text/css">'\
           '*{font-family: Vollkorn;}</style>' \
           '</head>'\
           '<body>'\
           '<table width="80%" style="background-color:#D2E0E8;margin:0 auto; height:50px; border-radius: 4px;">'\
           '<thead> <tr>'\
           '<td style="padding: 10px; border-bottom: 1px solid rgba(0, 102, 255, 0.21);">'\
           '<img style="margin-left: auto; margin-right: auto; display: block;" src="http://localhost:8000/assets/images/basis_log_new.jpg" width="80px" alt="OSS Framework"/>'\
           '<h4 style="text-align:center">'\
           '</h4></td></tr></thead>'\
           '<tbody><tr><td style="margin-top: 20px; padding: 15px;">'\
           'Dear Sir,<br/>Hope you are doing fine.<br/><br/>'\
           '<span style="color:#000;text-align:justify;">' \
           ''+email_content+'' \
           '</span><br/><br/><br/>Thanks<br/><b>OSS Framework</b>'\
           '<br/><br/></td></tr>'\
           '<tr style="margin-top: 15px;">'\
           '<td style="padding: 1px; border-top: 1px solid rgba(0, 102, 255, 0.21);">'\
           '<h5 style="text-align:center">All right reserved by OSS Framework 2018.</h5>'\
           '</td></tr> </tbody></table></body></html>'\

    args = (user_id, html, email_to, subject, created_at, updated_at)
    cur.execute(query, args)
    conn.commit()


print("Using MySQLdb…")
email_queue(myConnection)
myConnection.close()
