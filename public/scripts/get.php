<?php
require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'init.php');

if(isset($_GET['callback']) && is_callable(array('GL',$_GET['callback'])))
{
	echo isset($_GET['params[]']) ? 'params[] true' : 'params[] false';
	echo isset($_GET['params']) ? 'params true' : 'params false';
	//echo call_user_func(array('GL',$_GET['callback']),isset($_GET['params[]']) ? implode(',',$_GET['params[]']) : NULL);
}

?>