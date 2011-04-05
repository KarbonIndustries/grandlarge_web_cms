<?php
#LOCAL DIRECTORIES
define('DS',DIRECTORY_SEPARATOR);
define('FS','/');
define('PD','..' . DS);
define('ROOT', $_SERVER['DOCUMENT_ROOT'] . DS);
define('INC_DIR',ROOT . PD . 'inc' . DS);
define('CLASS_DIR',ROOT . PD . 'inc' . DS . 'classes' . DS);
define('ELEMENT_DIR',ROOT . PD . 'inc' . DS . 'elements' . DS);
define('FLOURISH_DIR',CLASS_DIR . 'flourish' . DS);

#WEB DIRECTORIES
define('STORAGE_DIR',FS . 'storage' . FS);
define('FILE_DIR',STORAGE_DIR . 'files' . FS);
define('NOTABLE_DIR',STORAGE_DIR . 'notable' . FS);
define('ABOUT_DIR',STORAGE_DIR . 'about' . FS);
define('CSS_DIR',FS . 'css' . FS);
define('IMG_DIR',FS . 'img' . FS);
define('JS_DIR',FS . 'js' . FS);
define('SWF_DIR',FS . 'swf' . FS);

#CREDITS
define('SHORT_COMPANY_NAME','Grand Large');
define('LONG_COMPANY_NAME',SHORT_COMPANY_NAME . ' Inc.');
define('COMPANY_BASE_URL','http://www.grandlargeinc.com/');
define('ARCHITECT','Karbon Interaktiv Inc.');
define('ARCHITECT_URL','http://www.karbonnyc.com/');

#DEFAULTS
define('DEFAULT_PAGE','directors');
define('LOGIN_PAGE','login.php');

#ABBREVIATIONS
define('PHONE_ABBR','T:');
define('WEB_ABBR','W:');
define('EMAIL_ABBR','E:');
?>