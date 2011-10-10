<?php
#LOCAL DIRECTORIES
define('DS',DIRECTORY_SEPARATOR);
define('FS','/');
define('PD','..' . DS);
define('ROOT', $_SERVER['DOCUMENT_ROOT'] . DS);
define('LOCAL_STORAGE_DIR',ROOT . PD . 'storage' . FS);
define('FILE_DIR',LOCAL_STORAGE_DIR . 'files' . FS);
define('INC_DIR',ROOT . PD . 'inc' . DS);
define('CLASS_DIR',ROOT . PD . 'inc' . DS . 'classes' . DS);
define('ELEMENT_DIR',ROOT . PD . 'inc' . DS . 'elements' . DS);
define('FLOURISH_DIR',CLASS_DIR . 'flourish' . DS);

#WEB DIRECTORIES
define('WEB_STORAGE_DIR','storage' . FS);
define('NOTABLE_DIR',WEB_STORAGE_DIR . 'notable' . FS);
define('ABOUT_DIR',WEB_STORAGE_DIR . 'about' . FS);
define('ABOUT_IMG_NAME','gl_about.jpg');
define('CSS_DIR',FS . 'css' . FS);
define('IMG_DIR',FS . 'img' . FS);
define('JS_DIR',FS . 'js' . FS);
define('SCRIPT_DIR',FS . 'scripts' . FS);
define('SWF_DIR',FS . 'swf' . FS);

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
define('USER_LOGIN_ID','userLoginId');
define('USER_TYPE_ID','userTypeId');
define('USERNAME','username');
define('USER_TYPE_PRIVILEGES','userTypePrivileges');
define('USER_PRIVILEGES','userPrivileges');
define('MIN_USERNAME_LENGTH',6);
define('MIN_PASSWORD_LENGTH',6);

#PRIVILEGES
define('PRIVILEGE_FILE',INC_DIR . 'privileges.php');
define('USER_SELF_ID',3);
define('ADD_USER_ID',0);
define('EDIT_USER_TYPE_ID',1);
define('EDIT_USER_PASSWORD_ID',2);
define('REMOVE_USER_ID',3);

#MISC
define('NL',"\n");
define('EOL',PHP_EOL);
define('EDIT_NOTABLE_IMG_WIDTH',100);
define('NOTABLE_IMG_WIDTH',188);
define('NOTABLE_IMG_HEIGHT',100);
define('ABOUT_IMG_WIDTH',960);
define('ABOUT_IMG_HEIGHT',214);

#REGEX
define('USERNAME_RULE','/^[A-z0-9_]{' . MIN_USERNAME_LENGTH . ',}$/');
?>