# Recruitment-System
**Introduction**<br/>
Recruitment System is for evaluating candidate for interview process.
This system is for HR or interviewer for increasing the efficiency & decreasing the time spend on interview process.
System having Job Description, Recruitment Request, Candidate Information, Interview Scheduling & Alerts.

# Slim Framework
Slim is a PHP micro framework that helps you quickly write simple yet powerful web applications and APIs.

**Usage**<br/>
Using <a href="https://www.apachefriends.org/index.html">XAMPP</a> or <a href="http://www.wampserver.com/en/">WMPP</a> for WINDOWS & For Linux <a href="http://howtoubuntu.org/how-to-install-lamp-on-ubuntu">LAMP</a> :
- Copy extracted files to the web server's root directory [most probably, `C:\xampp\htdocs` in XAMPP or `C:\wamp\www` in WAMP] for WINDOWS & For Linux [most probably, `/var/www/html`].
- Create 'Attachment' Folder in that file(extracted file).
- Start APACHE and MySQL servers.
- Open your browser and used http://localhost/phpmyadmin/ this link.
- Create a blank database named 'recruitment'
- Import `script/recruitment.sql` to this database.
- Change the host, user, password in `data/db/connection.php` according to your phpmyadmin credentials.
- Finally, access the project files throgh url `localhost/<local_dirctory_path>` in your browser.
Thank You!!
