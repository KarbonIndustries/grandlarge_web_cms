<?php
#error_reporting(0);
require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'config.php');
require_once(INC_DIR . 'db.php');
#require_once(INC_DIR . 'functions.php');

function __autoload($class_name)
{
		$files[] = INC_DIR . $class_name . '.php';
		$files[] = CLASS_DIR . $class_name . '.php';
		$files[] = FLOURISH_DIR . $class_name . '.php';

		while($file = array_shift($files))
		{
			if(file_exists($file))
			{
				include($file);
				return;
			}
		}
		throw new Exception('The class ' . $class_name . ' could not be loaded');
}

fSession::setPath(SESSION_DIR);
fSession::setLength(SESSION_LENGTH);
?>