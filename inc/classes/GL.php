<?php

class GL
{
	private static $link			= NULL;
	private static $alt				= false;
	private static $pages			= array('directors','feeds','notable','about','contact','users','files');


	# SELECT METHODS
	public static function getDirectors()
	{
		$link = self::openConnection();
		$query = "";
		$query .= "SELECT `directors`.`id`,`directors`.`firstName`,`directors`.`lastName` ";
		$query .= "FROM `directors` ";
		$query .= "WHERE `directors`.`active` = 1 ";
		$query .= "ORDER BY `directors`.`firstName`";
		$result = self::queryDb($query);
		while($row = mysql_fetch_assoc($result))
		{
			$directors[] = $row;
		}
		return $directors;
	}

	public static function getDirector($id)
	{
		$link = self::openConnection();
		$query = "";
		$query .= "SELECT * ";
		$query .= "FROM `directors` ";
		$query .= "WHERE `directors`.`id` = {$id} AND `directors`.`active` = 1 ";
		$query .= "LIMIT 1";
		$result = self::queryDb($query);
		if(mysql_num_rows($result))
		{
			return mysql_fetch_assoc($result);
		}
	}


	# GENERAL METHODS
	public static function redirectTo($url = NULL)
	{
		if(isset($url))
		{
			header("Location: {$url}");
			exit;
		}
	}

	public static function altStr($str,$altStr = '')
	{
		$returnStr = self::$alt ? $str : $altStr;
		self::$alt = !self::$alt;
		return $returnStr;
	}
	
	public static function resetAlt()
	{
		self::$alt = false;
	}

	public static function getPages()
	{
		return self::$pages;
	}


	# GENERAL DB METHODS
	public static function openConnection()
	{
		self::$link = isset(self::$link) ? self::$link : mysql_connect(DB_HOST,DB_USER,DB_PASS);
		if(!self::$link){die('Database Connection Error: ' . mysql_error());}
		$db = mysql_select_db(DB_NAME,self::$link);
		if(!$db){die('Database Selection Error: ' . mysql_error());}
		return self::$link;
	}

	public static function closeDb()
	{
		if(self::$link)
		{
			mysql_close(self::$link);
			self::$link = NULL;
		}
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


	# SESSION METHODS
	public static function startSession()
	{
		session_start();
	}

	public static function isLoggedIn()
	{
		return isset($_SESSION['user']);
	}

	public static function confirmLoggedIn()
	{
		if(!self::isLoggedIn())
		{
			redirectTo(LOGIN_PAGE);
		}
	}

	public static function logout()
	{
		
	}

}

?>