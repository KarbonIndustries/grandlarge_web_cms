<?php

class GL
{
	private static $link			= NULL;

	# SESSION FUNCTIONS
	public static function startSession()
	{
		session_start();
	}

	public static function isLoggedIn()
	{
		return isset($_SESSION['username']);
	}

	public static function confirmLoggedIn()
	{
		if(!self::isLoggedIn())
		{
			redirectTo('index.php');
		}
	}

	public static function logout()
	{
		
	}

	# GENERAL FUNCTIONS
	public static function redirectTo($url = NULL)
	{
		if(isset($url))
		{
			header("Location: {$url}");
			exit;
		}
	}

	# GENERAL DB FUNCTIONS
	public static function openConnection()
	{
		self::$link = isset(self::$link) ? self::$link : mysql_connect(DB_HOST,DB_USER,DB_PASS);
		if(!self::$link){die('Database Connection Error: ' . mysql_error());}
		$db = mysql_select_db(DB_NAME,self::$link);
		if(!$db){die('Database Selection Error: ' . mysql_error());}
		return self::$link;
	}

	public static function closeDb($db)
	{
		mysql_close(self::$link);
	}

	public static function mysqlClean($value)
	{
		$value = trim($value);
		$magicQuotesOn = get_magic_quotes_gpc();
		$validPHPVersion = function_exists("mysql_real_escape_string"); // php >= v4.3.0
		if($validPHPVersion)
		{
			if($magicQuotesOn)
			{
				$value = stripslashes($value);
			}
			$value = mysql_real_escape_string($value);
		}else
		{
			if(!$magicQuotesOn)
			{
				$value = addslashes($value);
			}
		}
		return $value;
	}

	public static function queryDb($query)
	{
		$result = mysql_query($query);
		self::confirmQuery($result);
		return $result;
	}

	private static function confirmQuery($result)
	{
		if(!$result)
		{
			die('error ' . mysql_error());
		}
	}

	public static function getDirectors()
	{
		$query = "";
		$query .= "SELECT `directors`.`id`,`directors`.`firstName`,`directors`.`lastName` ";
		$query .= "FROM `directors` ";
		$query .= "WHERE `directors`.`active` = 1 ";
		$query .= "ORDER BY `directors`.`firstName`";
		$result = self::queryDb($query,self::$link);
		while($row = mysql_fetch_assoc($result))
		{
			$directors[] = $row;
		}
		return $directors;
	}

}

?>