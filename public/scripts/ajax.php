<?php
require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'init.php');

if(isset($_GET['callback']) && is_callable(array('GL',$_GET['callback'])))
{
	if(isset($_GET['params']))
	{
		echo call_user_func(array('GL',$_GET['callback']),$_GET['params']);	
	}else
	{
		echo call_user_func(array('GL',$_GET['callback']));	
	}
}else
{
	echo json_encode(array('success' => false,'message' => 'Method does not exist'));
}

?>