<?php

class GL
{
	private static $link			= NULL;
	private static $alt				= false;
	private static $pages			= array('directors','feeds','notable','about','contact','users','files');


	#====================================================================================
	# INSERT METHODS
	#====================================================================================
	public static function addDirector($info,$returnJSON = true)
	{
		if(!is_array($info))
		{
			return $returnJSON ? json_encode(array('success' => false,'message' => 'Invalid info.')) : false;
		}

		foreach($info as $row)
		{
			$row = self::mysqlClean($row);
		}

		$info['firstName'] = ucfirst($info['firstName']);
		$info['lastName']  = ucfirst($info['lastName']);
		$info['website']   = strtolower($info['website']);

		$link = self::openConnection();
		$query = "";
		$query .= "INSERT INTO `directors` ";
		$query .= "VALUES(NULL,DEFAULT,'{$info['firstName']}','{$info['lastName']}','{$info['bio']}','{$info['website']}','{$info['description']}')";
		self::queryDb($query);
		if(mysql_affected_rows() == 1)
		{
			$result['success'] = true;
			$result['message'] = "Successfully added {$info['firstName']} {$info['lastName']}";
			$directors = self::getDirectors();
			$data = '';
			foreach($directors as $d)
			{
				$data .= '<tr directorId="' . $d['id'] . '" class="' . self::altStr('altRow') . '">' . "\n";
				$data .= "\t" . '<td class="directorName">' . $d['firstName'] . ' ' . $d['lastName'] . '</td>' . "\n";
				$data .= "\t" . '<td class="removeBtn"><button class="removeDirectorBtn">Remove</button></td>' . "\n";
				$data .= '</tr>' . "\n";
			}
			self::resetAlt();
			$result['data'] = $data;
			return $returnJSON ? json_encode($result) : $result;
		}
		return $returnJSON ? json_encode(array('success' => false,'message' => 'Invalid Info.')) : false;
	}

	public static function addOfficeCategory($info,$returnJSON = true)
	{
		
	}
	
	public static function addOffice($info,$returnJSON = true)
	{
		
	}

	#====================================================================================
	# SELECT METHODS
	#====================================================================================
	public static function getDirectors()
	{
		$link = self::openConnection();
		$query = "";
		$query .= "SELECT `directors`.`id`,`directors`.`firstName`,`directors`.`lastName` ";
		$query .= "FROM `directors` ";
		$query .= "WHERE `directors`.`active` = 1 ";
		$query .= "ORDER BY `directors`.`firstName` ASC";
		$result = self::queryDb($query);
		while($row = mysql_fetch_assoc($result))
		{
			$directors[] = $row;
		}
		return $directors;
	}

	public static function getDirector($info,$returnJSON = true)
	{
		$link = self::openConnection();
		$id = is_array($info) ? $info['id'] : $info;
		$query = "";
		$query .= "SELECT * ";
		$query .= "FROM `directors` ";
		$query .= "WHERE `directors`.`id` = {$id} AND `directors`.`active` = 1 ";
		$query .= "LIMIT 1";
		$result = self::queryDb($query);
		if(mysql_num_rows($result))
		{
			$result = mysql_fetch_assoc($result);
			$result['success'] = true;
			$result['message'] = '';
			return $returnJSON ? json_encode($result) : $result;
		}
	}

	public static function getOfficeCategories()
	{
		$link = self::openConnection();
		$query = "";
		$query .= "SELECT * ";
		$query .= "FROM `officeCategories` ";
		$query .= "ORDER BY `officeCategories`.`name` ASC";
		$result = self::queryDb($query);
		while($row = mysql_fetch_assoc($result))
		{
			$offices[] = $row;
		}
		return $offices;
	}

	public static function getOffices($returnJSON = true)
	{
		
	}

	public static function getOffice($info,$returnJSON = true)
	{
		
	}

	#====================================================================================
	# UPDATE METHODS
	#====================================================================================
	public static function updateDirector($info,$returnJSON = true)
	{
		if(!is_array($info))
		{
			return $returnJSON ? json_encode(array('success' => false,'message' => 'Invalid info.')) : false;
		}

		foreach($info as $row)
		{
			$row = self::mysqlClean($row);
		}

		$info['firstName'] = ucfirst($info['firstName']);
		$info['lastName']  = ucfirst($info['lastName']);
		$info['website']   = strtolower($info['website']);

		$link = self::openConnection();
		$query = "";
		$query .= "UPDATE `directors` ";
		$query .= "SET ";
		$query .= "`directors`.`firstName`   = '{$info['firstName']}', ";
		$query .= "`directors`.`lastName`    = '{$info['lastName']}', ";
		$query .= "`directors`.`bio`         = '{$info['bio']}', ";
		$query .= "`directors`.`websiteURL`  = '{$info['website']}', ";
		$query .= "`directors`.`description` = '{$info['description']}' ";
		$query .= "WHERE `directors`.`id` = {$info['id']} AND `directors`.`active` = 1 ";
		$query .= "LIMIT 1";
		self::queryDb($query);
		if(mysql_affected_rows() == 1)
		{
			$result['success'] = true;
			$result['message'] = "Successfully updated {$info['firstName']} {$info['lastName']}";
			$directors = self::getDirectors();
			$data = '';
			foreach($directors as $d)
			{
				$data .= '<tr directorId="' . $d['id'] . '" class="' . self::altStr('altRow') . '">' . "\n";
				$data .= "\t" . '<td class="directorName">' . $d['firstName'] . ' ' . $d['lastName'] . '</td>' . "\n";
				$data .= "\t" . '<td class="removeBtn"><button class="removeDirectorBtn">Remove</button></td>' . "\n";
				$data .= '</tr>' . "\n";
			}
			self::resetAlt();
			$result['data'] = $data;
			return $returnJSON ? json_encode($result) : $result;
		}
		return $returnJSON ? json_encode(array('success' => false,'message' => 'Invalid Info.')) : false;
	}

	public static function updateOfficeCategory($info,$returnJSON = true)
	{
		
	}

	public static function updateOffice($info,$returnJSON = true)
	{
		
	}

	#====================================================================================
	# DELETE METHODS
	#====================================================================================
	public static function removeDirector($info,$returnJSON = true)
	{
		if(!is_array($info))
		{
			return $returnJSON ? json_encode(array('success' => false,'message' => 'Invalid info.')) : false;
		}

		$link = self::openConnection();
		$query = "";
		$query .= "DELETE FROM `directors` ";
		$query .= "WHERE `directors`.`id` = {$info['id']} AND `directors`.`active` = 1 ";
		$query .= "LIMIT 1";
		self::queryDb($query);
		if(mysql_affected_rows() == 1)
		{
			$result['success'] = true;
			$result['message'] = "Director successfully removed.";
			$directors = self::getDirectors();
			$data = '';
			foreach($directors as $d)
			{
				$data .= '<tr directorId="' . $d['id'] . '" class="' . self::altStr('altRow') . '">' . "\n";
				$data .= "\t" . '<td class="directorName">' . $d['firstName'] . ' ' . $d['lastName'] . '</td>' . "\n";
				$data .= "\t" . '<td class="removeBtn"><button class="removeDirectorBtn">Remove</button></td>' . "\n";
				$data .= '</tr>' . "\n";
			}
			self::resetAlt();
			$result['data'] = $data;
			return $returnJSON ? json_encode($result) : $result;
		}
		return $returnJSON ? json_encode(array('success' => false,'message' => 'Invalid Info.')) : false;
	}

	public static function removeOfficeCategory($info,$returnJSON = true)
	{
		
	}

	public static function removeOffice($info,$returnJSON = true)
	{
		
	}

	#====================================================================================
	# GENERAL METHODS
	#====================================================================================
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

	#====================================================================================
	# GENERAL DB METHODS
	#====================================================================================
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

	#====================================================================================
	# SESSION METHODS
	#====================================================================================
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
