Flutrack.org
============

Installation Guide
============

Firstly, the db.php file must be executed via a browser, in order for the table fields to be created in the data base. An Apache Distribution containing MySQL and PHP 
should exist (proposed as the simplest solution but not unique).

Secondly, and while no error has occurred, call http://127.0.0.1/application_folder/db.php?secretinstall=your_db.php_secret_password.

Now the backend.php file must be executed. To properly achieve that, the asterisks in the auth.php file should be replaced with the keys included in the 
Twitter API (https://dev.twitter.com/docs/auth/application-only-auth). Each potential user should create an application in Twitter API, to get the corresponding 
keys.


The results.json file is an illustrative example of a visualisation file for the front-end section, as exported (or extracted) from the data base. It can be used
through index.html to visualise tweets. The map in index.html will not display something as long as the images representing tweets do not exist. A folder 
containing 2 images of choice should be created (http://mapicons.nicolasmollet.com/).

The gennaw.php, gennhtriajson.php files are used to export the json files from the data base and do not have any primary use. It should be noted that tweets may 
be exported from the data base in multiple ways (e.g. xml).

Accessing styles.css file, the values of the map dimension may change, and within map.js file the map may be parameterized. This is where the markers names should
be changed and tweets will be visualised, depending on the images to be used. It is also possible for the map parameters to be customized and characteristics to 
be enabled/disabled.

ATTENTION:

On the page where you created your twitter account to get the credentials, you should add as website/application-url, the url of the localhost, 
i.e. http://127.0.0.1 . This is the only way for your application to be able to connect to the Twitter server. 

For the application to work, the following must be activated and supported by the localhost server:
[PHP Modules] 
apc, 
bcmath, 
bz2, 
calendar, 
Core, 
ctype, 
curl, 
date, 
dba, 
dom, 
ereg, 
exif, 
fileinfo, 
filter, 
ftp, 
gd, 
gettext, 
hash, 
iconv, 
json, 
libxml, 
mbstring, 
mhash, 
mysql, 
mysqli, 
mysqlnd, 
openssl, 
pcntl, 
pcre, 
PDO, 
pdo_mysql, 
Phar, 
posix, 
readline, 
Reflection, 
session, 
shmop, 
SimpleXML, 
soap, 
sockets, 
SPL, 
standard, 
suhosin, 
sysvmsg, 
sysvsem, 
sysvshm, 
tokenizer, 
wddx, 
xml, 
xmlreader, 
xmlwriter, 
zip, 
zlib

For any questions or suggestions, please contact: flutrack.org@gmail.com

Για οποιαδήποτε ερώτηση, προβληματισμό ή ιδέα επικοινωνήστε στο: sckarolos@yahoo.com
