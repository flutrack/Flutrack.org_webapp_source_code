Flutrack.org
============

Installation Guide
============

Firstly, the db.php file must be executed via a browser, in order for the table fields to be created in the database. An Apache Distribution containing MySQL and PHP 
should exist (proposed as the simplest solution but not unique).

Secondly, and while no error has occurred, call http://127.0.0.1/application_folder/db.php?secretinstall=php_var_correct_secret_word .

Now the backend.php file must be executed. To properly achieve that, the asterisks in the oauth.php file should be replaced with the keys included in the 
Twitter API (https://dev.twitter.com/docs/auth/application-only-auth). Each potential user should create an application in Twitter API, to get the corresponding keys.

Please note (real world usage): 
1) The backend.php is designed to be run frequently eg: twice a day. This can be achived via a cronjob depending on your web hosting enviroment.
2) The backend.php can be renamed to something random as well to avoid abuse. Or edited accordingly to allow execution only from localhost/trusted IP/etc.

The results.json file is an illustrative example of a visualisation file for the front-end section, as exported (or extracted) from the data base. It can be used
through index.html to visualise tweets. The map in index.html will not display something as long as the images representing tweets do not exist. A folder 
containing 2 images of choice should be created (http://mapicons.nicolasmollet.com/).

The gen.php, genjson.php files are used to export the json files from the data base and do not have any primary use. It should be noted that tweets may 
be exported from the data base in multiple ways (e.g. xml).

Accessing styles.css file, the values of the map dimension may change, and within map.js file the map may be parameterized. This is where the markers names should
be changed and tweets will be visualised, depending on the images to be used. It is also possible for the map parameters to be customized and characteristics to 
be enabled/disabled.

ATTENTION:

On the page where you created your twitter account to get the credentials, you should add as website/application-url, the url of the localhost, 
i.e. http://127.0.0.1 . This is the only way for your application to be able to connect to the Twitter server. 

The application has been tested with the following php modules enabled on server (not all of them are needed). In any case PHP should report missing dependencies:
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

