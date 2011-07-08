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

		$link = self::openConnection();

		foreach($info as $key => $val)
		{
			$info[$key] = self::mysqlClean($val);
		}

		$info['firstName'] = ucfirst($info['firstName']);
		$info['lastName']  = ucfirst($info['lastName']);
		$info['website']   = strtolower($info['website']);

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
		if(!is_array($info))
		{
			return $returnJSON ? json_encode(array('success' => false,'message' => 'Invalid info.')) : false;
		}

		$link = self::openConnection();

		foreach($info as $key => $val)
		{
			$info[$key] = self::mysqlClean(ucwords($val));
		}
		
		$info['name'] = ucwords(strtolower($info['name']));

		$query   = "";
		$query  .= "INSERT IGNORE INTO `officeCategories` (`name`) ";
		$query  .= "VALUES('{$info['name']}')";
		self::queryDb($query);
		if(mysql_affected_rows())
		{
			$categories = self::getOfficeCategories();
			$data       = '';
			foreach($categories as $c)
			{
				$data .= '<tr officeCategoryId="' . $c['id'] . '" class="' . self::altStr('altRow') . '">';
					$data .= '<td class="officeCategoryName"><input type="text" name="name' . $c['id'] . '" id="' . $c['id'] . '" value="' . $c['name'] . '" /></td>';
					$data .= '<td class="removeBtn"><button class="removeOfficeCategoryBtn" name="' . $c['name'] . '" id="' . $c['id'] . '">Remove</button></td>';
					$data .= '<td class="updateBtn"><button class="updateOfficeCategoryBtn" name="' . $c['name'] . '" id="' . $c['id'] . '">Update</button></td>';
				$data .= '</tr>';
			}
			self::resetAlt();
			$data2 = '<option value="" selected="selected">Select an office category</option>';
			foreach($categories as $c)
			{
				$data2 .= '<option value="' . $c['id'] . '">' . $c['name'] . '</option>';
			}
			
			$result['success'] = true;
			$result['message'] = $info['name'] . ' was successfully added.';
			$result['data']    = $data;
			$result['data2']   = $data2;
			return $returnJSON ? json_encode($result) : $result;
		}
		return $returnJSON ? json_encode(array('success' => false,'message' => 'A category named ' . $info['name'] . ' already exists.')) : false;
	}
	
	public static function addOffice($info,$returnJSON = true)
	{
		if(!is_array($info))
		{
			$result['success'] = false;
			$result['message'] = 'Invalid info';
			return $returnJSON ? json_encode($result) : false;
		}

		$link = self::openConnection();

		foreach($info as $key => $val)
		{
			$info[$key] = self::mysqlClean($val);
		}

		$info['officeLocale']      = ucwords(strtolower($info['officeLocale']));
		$info['companyName']       = ucwords(strtolower($info['companyName']));
		$info['address1']          = ucwords(strtolower($info['address1']));
		$info['address2']          = ucwords(strtolower($info['address2']));
		$info['address3']          = ucwords(strtolower($info['address3']));
		$info['city']              = ucwords(strtolower($info['city']));
		$info['country']           = ucwords($info['country']);
		$info['contact1FirstName'] = ucfirst($info['contact1FirstName']);
		$info['contact1LastName']  = ucfirst($info['contact1LastName']);
		$info['contact2FirstName'] = ucfirst($info['contact2FirstName']);
		$info['contact2LastName']  = ucfirst($info['contact2LastName']);
		$info['contact3FirstName'] = ucfirst($info['contact3FirstName']);
		$info['contact3LastName']  = ucfirst($info['contact3LastName']);
		$info['email']             = strtolower($info['email']);
		$info['websiteURL']        = strtolower($info['websiteURL']);

		$query  =<<<Q
INSERT INTO `contacts` (`officeCategoryID`,
			`officeLocale`,
			`companyName`,
			`address1`,
			`address2`,
			`address3`,
			`city`,
			`stateID`,
			`zip`,
			`country`,
			`contact1FirstName`,
			`contact1LastName`,
			`contact2FirstName`,
			`contact2LastName`,
			`contact3FirstName`,
			`contact3LastName`,
			`phone`,
			`email`,
			`websiteURL`)
VALUES ('{$info['officeCategoryID']}',
		'{$info['officeLocale']}',
		'{$info['companyName']}',
		'{$info['address1']}',
		'{$info['address2']}',
		'{$info['address3']}',
		'{$info['city']}',
		'{$info['stateID']}',
		'{$info['zip']}',
		'{$info['country']}',
		'{$info['contact1FirstName']}',
		'{$info['contact1LastName']}',
		'{$info['contact2FirstName']}',
		'{$info['contact2LastName']}',
		'{$info['contact3FirstName']}',
		'{$info['contact3LastName']}',
		'{$info['phone']}',
		'{$info['email']}',
		'{$info['websiteURL']}')
Q;

		self::queryDb($query);
		if(mysql_affected_rows())
		{
			$result['success'] = true;
			$result['message'] = $info['companyName'] . ' was successfully added.';
			$data = '';
			if($contacts = self::getOffices())
			{
				foreach($contacts as $c)
				{
					$data .= '<tr id="' . $c['id'] . '" class="' . self::altStr('altRow') . '">';
						$data .= '<td  officeId="' . $c['id'] . '" class="companyName">' . $c['companyName'] . '</td>';
						$data .= '<td  officeId="' . $c['id'] . '" class="officeName">' . $c['officeLocale'] . '</td>';
						$data .= '<td  class="removeBtn"><button officeId="' . $c['id'] . '" class="removeOfficeBtn">Remove</button></td>';
					$data .= '</tr>';
				}
				self::resetAlt();
			}else
			{
				$data .= '<tr><td>No offices available</td></tr>';
			}
			$result['data'] = $data;
			return $returnJSON ? json_encode($result) : true;
		}
		$result['success'] = false;
		$result['message'] = 'There was an error adding ' . $info['companyName'] . '.';
		return $returnJSON ? json_encode($result) : false;
	}

	#====================================================================================
	# SELECT METHODS
	#====================================================================================
	public static function getDirectors()
	{
		$link = self::openConnection();
		$query =<<<Q
SELECT `directors`.`id`,`directors`.`firstName`,`directors`.`lastName`
FROM `directors`
WHERE `directors`.`active` = 1
ORDER BY `directors`.`firstName` ASC
Q;
		$result = self::queryDb($query);
		if(mysql_num_rows($result))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$directors[] = $row;
			}
			return $directors;
		}
		return false;
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
		if(mysql_num_rows($result) == 1)
		{
			$result            = mysql_fetch_assoc($result);
			$result['success'] = true;
			$result['message'] = '';
			return $returnJSON ? json_encode($result) : $result;
		}
		$result            = array();
		$result['success'] = false;
		$result['message'] = "The director you requested doesn't exist.";
		return $returnJSON ? json_encode($result) : $result;
	}

	public static function getOfficeCategories()
	{
		$link = self::openConnection();
		$query = "";
		$query .= "SELECT * ";
		$query .= "FROM `officeCategories` ";
		$query .= "ORDER BY `officeCategories`.`name` ASC";
		$result = self::queryDb($query);
		if(mysql_num_rows($result))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$categories[] = $row;
			}
			return $categories;
		}
		return false;
	}

	public static function getOffices()
	{
		$link = self::openConnection();
		$query = "SELECT * FROM `contacts` ";
		$query .= "ORDER BY `contacts`.`companyName` ASC";
		$result = self::queryDb($query);
		if(mysql_num_rows($result))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$offices[] = $row;
			}
			return $offices;
		}
		return false;
	}

	public static function getOffice($info,$returnJSON = true)
	{
		$link = self::openConnection();
		$id = is_array($info) ? $info['id'] : $info;
		$query = "";
		$query .= "SELECT * ";
		$query .= "FROM `contacts` ";
		$query .= "WHERE `contacts`.`id` = {$id} ";
		$query .= "LIMIT 1";
		$result = self::queryDb($query);
		if(mysql_num_rows($result) == 1)
		{
			$result            = mysql_fetch_assoc($result);
			$result['success'] = true;
			$result['message'] = '';
			return $returnJSON ? json_encode($result) : $result;
		}
		$result            = array();
		$result['success'] = false;
		$result['message'] = "The office you requested doesn't exist.";
		return $returnJSON ? json_encode($result) : $result;
	}

	public static function getStates()
	{
		$link    = self::openConnection();
		$query   =<<<Q
SELECT * FROM `states`
ORDER BY `states`.`abbreviation` ASC
Q;
		$result  = self::queryDb($query);
		if(mysql_num_rows($result))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$row['abbreviation'] = $row['abbreviation'] == '' ? 'N/A' : $row['abbreviation'];
				$states[] = $row;
			}
			return $states;
		}
		return false;
	}

	public static function getFeeds($returnJSON = true)
	{
		$link = self::openConnection();
		$query =<<<Q
SELECT `mediaFeeds`.*,TRIM(CONCAT(`directors`.`firstName`,' ',`directors`.`lastName`)) AS `directorName`,`navigation`.`name` AS `categoryName`
FROM `mediaFeeds`,`directors`,`mediaCategories`,`navigation`
WHERE `mediaFeeds`.`directorID` = `directors`.`id`
AND `mediaFeeds`.`mediaCategoryID` = `mediaCategories`.`id`
AND `mediaCategories`.`id` = `navigation`.`id`
ORDER BY `mediaFeeds`.`mediaCategoryID` ASC,`mediaFeeds`.`categoryPosition` ASC
Q;
		$result = self::queryDb($query);
		if(mysql_num_rows($result))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$feeds[] = $row;
			}

			if($returnJSON)
			{
				$data['success'] = true;
				$data['data'] = '';
				$catId = null;
				foreach($feeds as $f)
				{
					if($f['mediaCategoryID'] !== $catId)
					{
						$data['data'] .= <<<HTML_DATA
<thead>
	<tr>
		<th colspan="4">{$f['categoryName']}</th>
	</tr>
</thead>
HTML_DATA;
						$catId = $f['mediaCategoryID'];
						self::resetAlt();
					}
					$altStr = self::altStr('class="altRow"');
					$data['data'] .= <<<HTML_DATA
<tbody>
	<tr {$altStr} rowId="{$f['id']}">
		<td class="positionCol"><input class="feedCategoryPosition" type="text" value="{$f['categoryPosition']}"/></td>
		<td class="nameCol">{$f['directorName']}</td>
		<td class="updateCol"><button class="updateFeedBtn" name="" id="" feedId="{$f['id']}">Update</button></td>
		<td class="removeCol"><button class="removeFeedBtn" name="" id="" feedId="{$f['id']}">Remove</button></td>
	</tr>
</tbody>
HTML_DATA;
				}
				self::resetAlt();
				return json_encode($data);
			}
			return $feeds;
		}
		$jsonData = array('success' => false,'message' => 'There are no feeds.','htmlMessage' => '<h1>There are no feeds.</h1>');
		return $returnJSON ? json_encode($jsonData) : false;
	}

	public static function getMediaCategories()
	{
		$link = self::openConnection();
		$query =<<<Q
SELECT `navigation`.*
FROM `navigation`,`mediaCategories`
WHERE `navigation`.`id` = `mediaCategories`.`navId`
ORDER BY `navigation`.`id` ASC
Q;
		$result = self::queryDb($query);
		if(mysql_num_rows($result))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$cats[] = $row;
			}
			return $cats;
		}
		return false;
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

		$link = self::openConnection();

		foreach($info as $key => $val)
		{
			$info[$key] = self::mysqlClean($val);
		}

		$info['firstName'] = ucfirst($info['firstName']);
		$info['lastName']  = ucfirst($info['lastName']);
		$info['website']   = strtolower($info['website']);

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
		if(!is_array($info))
		{
			return $returnJSON ? json_encode(array('success' => false,'message' => 'Invalid info.')) : false;
		}

		$link = self::openConnection();

		foreach($info as $key => $val)
		{
			$info[$key] = self::mysqlClean(ucwords($val));
		}
		
		$query   = "";
		$query  .= "UPDATE IGNORE `officeCategories` ";
		$query  .= "SET `officeCategories`.`name` = '{$info['name']}' ";
		$query  .= "WHERE `officeCategories`.`id` = {$info['id']} ";
		$query  .= "LIMIT 1";
		self::queryDb($query);
		if(mysql_affected_rows() == 1)
		{
			$categories = self::getOfficeCategories();
			$data       = '';
			foreach($categories as $c)
			{
				$data .= '<tr officeCategoryId="' . $c['id'] . '" class="' . self::altStr('altRow') . '">';
					$data .= '<td class="officeCategoryName"><input type="text" name="name' . $c['id'] . '" id="' . $c['id'] . '" value="' . $c['name'] . '" /></td>';
					$data .= '<td class="removeBtn"><button class="removeOfficeCategoryBtn" name="' . $c['name'] . '" id="' . $c['id'] . '">Remove</button></td>';
					$data .= '<td class="updateBtn"><button class="updateOfficeCategoryBtn" name="' . $c['name'] . '" id="' . $c['id'] . '">Update</button></td>';
				$data .= '</tr>';
			}
			self::resetAlt();
			$data2 = '<option value="" selected="selected">Select an office category</option>';
			foreach($categories as $c)
			{
				$data2 .= '<option value="' . $c['id'] . '">' . $c['name'] . '</option>';
			}
			$result['success'] = true;
			$result['message'] = 'Category was successfully updated.';
			$result['data']    = $data;
			$result['data2']   = $data2;
			return $returnJSON ? json_encode($result) : $result;
		}
		return $returnJSON ? json_encode(array('success' => false,'message' => 'A category named ' . $info['name'] . ' already exists.')) : false;
	}

	public static function updateOffice($info,$returnJSON = true)
	{
		if(!is_array($info))
		{
			$result['success'] = false;
			$result['message'] = 'Invalid info';
			return $returnJSON ? json_encode($result) : false;
		}

		$link = self::openConnection();

		foreach($info as $key => $val)
		{
			$info[$key] = self::mysqlClean($val);
		}

		$info['officeLocale']      = ucwords(strtolower($info['officeLocale']));
#		$info['companyName']       = ucwords(strtolower($info['companyName']));
		$info['companyName']       = ucwords($info['companyName']);
		$info['address1']          = ucwords(strtolower($info['address1']));
		$info['address2']          = ucwords(strtolower($info['address2']));
		$info['address3']          = ucwords(strtolower($info['address3']));
		$info['city']              = ucwords(strtolower($info['city']));
		$info['country']           = ucwords($info['country']);
		$info['contact1FirstName'] = ucfirst($info['contact1FirstName']);
		$info['contact1LastName']  = ucfirst($info['contact1LastName']);
		$info['contact2FirstName'] = ucfirst($info['contact2FirstName']);
		$info['contact2LastName']  = ucfirst($info['contact2LastName']);
		$info['contact3FirstName'] = ucfirst($info['contact3FirstName']);
		$info['contact3LastName']  = ucfirst($info['contact3LastName']);
		$info['email']             = strtolower($info['email']);
		$info['websiteURL']        = strtolower($info['websiteURL']);

		$query  =<<<Q
UPDATE `contacts`
SET `officeCategoryID`    = '{$info['officeCategoryID']}',
	`officeLocale`        = '{$info['officeLocale']}',
	`companyName`         = '{$info['companyName']}',
	`address1`            = '{$info['address1']}',
	`address2`            = '{$info['address2']}',
	`address3`            = '{$info['address3']}',
	`city`                = '{$info['city']}',
	`stateID`             = '{$info['stateID']}',
	`zip`                 = '{$info['zip']}',
	`country`             = '{$info['country']}',
	`contact1FirstName`   = '{$info['contact1FirstName']}',
	`contact1LastName`    = '{$info['contact1LastName']}',
	`contact2FirstName`   = '{$info['contact2FirstName']}',
	`contact2LastName`    = '{$info['contact2LastName']}',
	`contact3FirstName`   = '{$info['contact3FirstName']}',
	`contact3LastName`    = '{$info['contact3LastName']}',
	`phone`               = '{$info['phone']}',
	`email`               = '{$info['email']}',
	`websiteURL`          = '{$info['websiteURL']}'
WHERE `contacts`.`id`     = {$info['id']}
LIMIT 1
Q;

		self::queryDb($query);
		if(mysql_affected_rows() === 1 || mysql_affected_rows() === 0)
		{
			$result['success'] = true;
			$result['message'] = $info['companyName'] . ' was successfully updated.';
			$data = '';
			if($contacts = self::getOffices())
			{
				foreach($contacts as $c)
				{
					$data .= '<tr id="' . $c['id'] . '" class="' . self::altStr('altRow') . '">';
						$data .= '<td  officeId="' . $c['id'] . '" class="companyName">' . $c['companyName'] . '</td>';
						$data .= '<td  officeId="' . $c['id'] . '" class="officeName">' . $c['officeLocale'] . '</td>';
						$data .= '<td  class="removeBtn"><button officeId="' . $c['id'] . '" class="removeOfficeBtn">Remove</button></td>';
					$data .= '</tr>';
				}
				self::resetAlt();
			}else
			{
				$data .= '<tr><td>There are no offices available.</td></tr>';
			}
			$result['data'] = $data;
			return $returnJSON ? json_encode($result) : true;
		}
		$result['success'] = false;
		$result['message'] = 'Unable to update ' . $info['companyName'] . '.';
		return $returnJSON ? json_encode($result) : false;
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
		return $returnJSON ? json_encode(array('success' => false,'message' => 'There was an error removing the director.')) : false;
	}

	public static function removeOfficeCategory($info,$returnJSON = true)
	{
		if(!is_array($info))
		{
			return $returnJSON ? json_encode(array('success' => false,'message' => 'Invalid info.')) : false;
		}

		$link = self::openConnection();

		$query   = "";
		$query  .= "DELETE FROM `officeCategories` ";
		$query  .= "WHERE `officeCategories`.`id` = {$info['id']} ";
		$query  .= "LIMIT 1";
		self::queryDb($query);
		if(mysql_affected_rows() == 1)
		{
			$categories = self::getOfficeCategories();
			$data       = '';
			foreach($categories as $c)
			{
				$data .= '<tr officeCategoryId="' . $c['id'] . '" class="' . self::altStr('altRow') . '">';
					$data .= '<td class="officeCategoryName"><input type="text" name="name' . $c['id'] . '" id="' . $c['id'] . '" value="' . $c['name'] . '" /></td>';
					$data .= '<td class="removeBtn"><button class="removeOfficeCategoryBtn" name="' . $c['name'] . '" id="' . $c['id'] . '">Remove</button></td>';
					$data .= '<td class="updateBtn"><button class="updateOfficeCategoryBtn" name="' . $c['name'] . '" id="' . $c['id'] . '">Update</button></td>';
				$data .= '</tr>';
			}
			self::resetAlt();
			$data2 = '<option value="" selected="selected">Select an office category</option>';
			foreach($categories as $c)
			{
				$data2 .= '<option value="' . $c['id'] . '">' . $c['name'] . '</option>';
			}
			$data3 = '';
			$contacts = self::getOffices();
			foreach($contacts as $c)
			{
				$data3 .= '<tr id="' . $c['id'] . '" class="' . self::altStr('altRow') . '">';
					$data3 .= '<td class="companyName">' . $c['companyName'] . '</td>';
					$data3 .= '<td class="officeName">' . $c['officeLocale'] . '</td>';
					$data3 .= '<td class="removeBtn"><button class="removeOfficeBtn">Remove</button></td>';
				$data3 .= '</tr>';
			}
			self::resetAlt();
			$result['success'] = true;
			$result['message'] = 'Category successfully removed.';
			$result['data']    = $data;
			$result['data2']   = $data2;
			$result['data3']   = $data3;
			return $returnJSON ? json_encode($result) : $result;
		}
		return $returnJSON ? json_encode(array('success' => false,'message' => 'There was an error removing the category.')) : false;
	}

	public static function removeOffice($info,$returnJSON = true)
	{
		if(!is_array($info))
		{
			$result['success'] = false;
			$result['message'] = 'Invalid info';
			return $returnJSON ? json_encode($result) : false;
		}

		$link = self::openConnection();

		$query =<<<Q
DELETE FROM `contacts`
WHERE `contacts`.`id` = {$info['id']}
LIMIT 1;
Q;
		self::queryDb($query);

		if(mysql_affected_rows())
		{
			$result['success'] = true;
			$result['message'] = 'Successfully removed office';
			$data = '';
			if($contacts = self::getOffices())
			{
				foreach($contacts as $c)
				{
					$data .= '<tr id="' . $c['id'] . '" class="' . self::altStr('altRow') . '">';
						$data .= '<td  officeId="' . $c['id'] . '" class="companyName">' . $c['companyName'] . '</td>';
						$data .= '<td  officeId="' . $c['id'] . '" class="officeName">' . $c['officeLocale'] . '</td>';
						$data .= '<td  class="removeBtn"><button officeId="' . $c['id'] . '" class="removeOfficeBtn">Remove</button></td>';
					$data .= '</tr>';
				}
				self::resetAlt();
			}else
			{
				$data .= '<tr><td>No offices available</td></tr>';
			}
			$result['data'] = $data;
			return $returnJSON ? json_encode($result) : true;
		}
		$result['success'] = false;
		$result['message'] = 'There was an error removing the office.';
		return $returnJSON ? json_encode($result) : false;
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
