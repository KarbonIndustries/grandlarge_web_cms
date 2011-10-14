<?php
#LOCAL DIRECTORIES
define('DS',DIRECTORY_SEPARATOR);
define('FS','/');
define('PD','..' . DS);
define('ROOT', $_SERVER['DOCUMENT_ROOT'] . DS);
define('MEDIA_STORAGE_DIR',ROOT . PD . 'media_storage' . FS);
define('INC_DIR',ROOT . PD . 'inc' . DS);
define('CLASS_DIR',ROOT . PD . 'inc' . DS . 'classes' . DS);
define('ELEMENT_DIR',ROOT . PD . 'inc' . DS . 'elements' . DS);
define('FLOURISH_DIR',CLASS_DIR . 'flourish' . DS);
define('SESSION_DIR',ROOT . PD . 'sessions' . DS);

#WEB DIRECTORIES
define('WEB_STORAGE_DIR',DS . 'storage' . FS);
define('NOTABLE_DIR',WEB_STORAGE_DIR . 'notable' . FS);
define('ABOUT_DIR',WEB_STORAGE_DIR . 'about' . FS);
define('ABOUT_IMG_NAME','gl_about.jpg');
define('CSS_DIR',FS . 'css' . FS);
define('IMG_DIR',FS . 'img' . FS);
define('JS_DIR',FS . 'js' . FS);
define('JQUERY_DIR',JS_DIR . 'jquery' . FS);
define('SCRIPT_DIR',FS . 'scripts' . FS);
define('SWF_DIR',FS . 'swf' . FS);
define('JQUERY_VERSION','1.6.2');
define('JQUERY_FILE',JQUERY_DIR . 'jquery_' . JQUERY_VERSION . '.min.js');
define('JQUERY_UI_FILE',JQUERY_DIR . 'jquery_ui_1.8.16.custom.min.js');

#CREDITS
define('SHORT_COMPANY_NAME','Grand Large');
define('LONG_COMPANY_NAME',SHORT_COMPANY_NAME . ' Inc.');
define('COMPANY_BASE_URL','http://www.grandlargeinc.com/');
define('COMPANY_PRETTY_URL','grandlargeinc.com');
define('ARCHITECT','Karbon Interaktiv Inc.');
define('ARCHITECT_URL','http://www.karbonnyc.com/');
define('ARCHITECT_EMAIL','shammel@karbonnyc.com');

#DEFAULTS
define('LOGIN_PAGE','/');

#ABBREVIATIONS
define('PHONE_ABBR','T:');
define('WEB_ABBR','W:');
define('EMAIL_ABBR','E:');

#SESSION
define('SESSION_LENGTH','1 week');
define('USER_LOGIN_ID','userLoginId');
define('USER_TYPE_ID','userTypeId');
define('USERNAME','username');
define('USER_TYPE_PRIVILEGES','userTypePrivileges');
define('USER_PRIVILEGES','userPrivileges');
define('MIN_USERNAME_LENGTH',6);
define('MAX_USERNAME_LENGTH',40);
define('MIN_PASSWORD_LENGTH',6);
define('MAX_PASSWORD_LENGTH',20);

#PRIVILEGES
define('PRIVILEGE_FILE',INC_DIR . 'privileges.php');
define('USER_SELF_ID',3);
define('ADD_USER_ID',0);
define('EDIT_USER_TYPE_ID',1);
define('EDIT_USER_PASSWORD_ID',2);
define('REMOVE_USER_ID',3);
define('VIEW_FILE_ID',4);
define('DOWNLOAD_FILE_ID',5);
define('REMOVE_FILE_ID',6);

#MISC
define('NL',"\n");
define('EOL',PHP_EOL);
define('EDIT_NOTABLE_IMG_WIDTH',100);
define('NOTABLE_IMG_WIDTH',188);
define('NOTABLE_IMG_HEIGHT',100);
define('ABOUT_IMG_WIDTH',960);
define('ABOUT_IMG_HEIGHT',214);

#REGEX
define('USERNAME_RULE' ,'/^[a-z0-9_]{' . MIN_USERNAME_LENGTH . ',' . MAX_USERNAME_LENGTH . '}$/i');
define('USER_DIRECTORY_RULE','/^[a-z0-9_]{' . MIN_USERNAME_LENGTH . ',' . MAX_USERNAME_LENGTH . '}\/$/i');
define('USER_FILE_RULE','/^[^\/]+$/i');

#ENCRYPTION
define('SHA1','sha1');
define('MD5','md5');
define('FILENAME_HASH_METHOD',MD5);

#ERRORS
define('FILE_NOT_FOUND',"The file you requested either can't be found or doesn't exist!");
define('NO_FILES_FOUND','There are no files for this user!');
define('NO_FILES_FOUND_FOR_YOU','You have no files!');
define('UNKNOWN_REQUEST',"Unrecognized request!");
define('INVALID_USER','Invalid user!');
define('NO_VIEW_FILE_PERMISSION',"You do not have permission to view this user's files!");
define('NO_DOWNLOAD_FILE_PERMISSION',"You do not have permission to download this user's files!");
define('NO_REMOVE_FILE_PERMISSION',"You do not have permission to remove this user's files!");

?>