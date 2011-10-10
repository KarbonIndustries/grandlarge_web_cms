<?php

class GL
{
	private static $link            = NULL;
	private static $alt             = false;
	private static $PAGES1          = array('files');
	private static $PAGES2          = array('directors','feeds','notable','about','contact','users','files');
	private static $PAGES3          = array('directors','feeds','notable','about','contact','users','files');
	private static $USER_TYPES      = array('1' => 'Client','2' => 'Admin','3' => 'Architect');

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

	public static function addFeed($info,$returnJSON = true)
	{
		if(!is_array($info) ||
		!array_key_exists('directorId',$info) ||
		!array_key_exists('categoryId',$info) ||
		!array_key_exists('url',$info) ||
		!filter_var($info['url'],FILTER_VALIDATE_URL))
		{
			$result['success'] = false;
			$result['message'] = 'The information provided is invalid.';
			return $returnJSON ? json_encode($result) : false;
		}

		$link = self::openConnection();
		$query = <<<Q
INSERT INTO `mediaFeeds` (`mediaCategoryId`,`categoryPosition`,`directorId`,`feedURL`)
VALUES(
{$info['categoryId']},
(SELECT `result`.`maxCategoryPosition`
FROM (
	SELECT MAX(`mediaFeeds`.`categoryPosition`) + 1 AS `maxCategoryPosition`
	FROM `mediaFeeds`
	WHERE `mediaFeeds`.`mediaCategoryID` = {$info['categoryId']}
	) AS `result`
),
{$info['directorId']},
'{$info['url']}'
)
Q;

		self::queryDb($query);
		if(mysql_affected_rows())
		{
			$result['success'] = true;
			$result['message'] = 'Successfully added feed.';
			return $returnJSON ? json_encode($result) : true;
		}

		$result['success'] = false;
		$result['message'] = 'There was an error adding the feed.';
		return $returnJSON ? json_encode($result) : false;
	}

	public static function addNotable($title,$image,$url)
	{
		$link  = self::openConnection();
		$title = self::mysqlClean($title);
		$image = self::mysqlClean($image);
		$url   = self::mysqlClean($url);
		if(!$title || !$image || !$url)
		{
			die('Form is incomplete');
		}
		$query = <<<Q
INSERT INTO `news` (`title`,`image`,`url`)
VALUES('{$title}','{$image}','{$url}')
Q;
		self::queryDb($query);
		return mysql_affected_rows() ? true : false;
	}

	public static function addUser($info,$returnJSON = true)
	{
		if(!isset(
			$info['userId'],
			$info['userTypeId'],
			$info['username'],
			$info['password'],
			$info['confirmPassword']))
		{
			$json['success'] = false;
			$json['msg']   = 'Information provided is incomplete.';
			return $returnJSON ? json_encode($json) : fasle;
		}

		$info['username'] = trim($info['username']);
		$info['password'] = trim($info['password']);
		$info['actionId'] = ADD_USER_ID;

		if($info['password'] !== $info['confirmPassword'])
		{
			$json['success'] = false;
			$json['msg']     = 'Passwords do not match. Please try again.';
			return $returnJSON ? json_encode($json) : false;
		}

		if(!self::userCanPerformAction($info))
		{
			$json['success'] = false;
			$json['msg']     = 'You do not have permission to add ' . $info['username'];
			return $returnJSON ? json_encode($json) : false;
		}

		if(!self::isValidUsername($info['username']))
		{
			$json['success'] = false;
			$json['msg']     = 'Username is insufficient. Please try again.';
			return $returnJSON ? json_encode($json) : false;
		}

		if(!(strlen($info['password']) >= MIN_PASSWORD_LENGTH))
		{
			$json['success'] = false;
			$json['msg']     = 'Password is too short. Please try again.';
			return $returnJSON ? json_encode($json) : false;
		}

		if(!self::usernameIsAvailable($info['username']))
		{
			$json['success'] = false;
			$json['msg']     = $info['username'] . ' is already taken. Please try again';
			return $returnJSON ? json_encode($json) : false;
		}

		$link = self::openConnection();
		$query = <<<Q
INSERT INTO `users` (`userTypeId`,`username`,`password`)
VALUES({$info['userTypeId']},'{$info['username']}',SHA1('{$info['password']}'))
Q;
		self::queryDb($query);
		if(!mysql_affected_rows() === 1)
		{
			$json['success'] = false;
			$json['msg']     = 'There was an error adding ' . $info['username'] . '. Please try again.';
			return $returnJSON ? json_encode($json) : false;
		}

		$json['success'] = true;
		$json['msg']     = $info['username'] . ' was successfully added.';
		return $returnJSON ? json_encode($json) : true;
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
		$link  = self::openConnection();
		$query = <<<Q
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
				$data['data']    = '';
				$catId           = null;
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
					$altStr         = self::altStr('class="altRow"');
					$data['data']  .= <<<HTML_DATA
<tbody>
	<tr {$altStr} rowId="{$f['id']}">
		<td class="positionCol"><input class="feedCategoryPosition" type="text" value="{$f['categoryPosition']}"/></td>
		<td class="nameCol">{$f['directorName']}</td>
		<td class="updateCol"><button class="updateFeedBtn" name="" id="" feedId="{$f['id']}" catPos="{$f['categoryPosition']}">Update</button></td>
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

		$data['success']     = false;
		$data['mesasge']     = 'There are no feeds.';
		$data['htmlMessage'] = '<h1>' . $data['message'] . '</h1>';
		return $returnJSON ? json_encode($data) : false;
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

	public static function getNotables($returnJSON = true)
	{
		$link = self::openConnection();
		$query = <<<Q
SELECT * FROM `news`
ORDER BY `news`.`timeAdded` DESC
Q;

		$result = self::queryDb($query);
		if(mysql_num_rows($result))
		{
			while($row = mysql_fetch_assoc($result))
			{
				$notables[] = $row;
			}

			if($returnJSON)
			{
				$data['success']     = true;
				$data['message']     = 'Successfully retrieved notables.';
				$data['htmlMessage'] = '<h1>' . $data['message'] . '<h1>';
				$data['data']        = '';
				foreach($notables as $n)
				{
					$IMG_URL        = NOTABLE_DIR . $n['image'];
					$IMG_WIDTH      = EDIT_NOTABLE_IMG_WIDTH;
					$altStr         = self::altStr('class="altRow"');
					$data['data']  .= <<<HTML_DATA
<tr {$altStr}>
	<td class="imgCell"><img src="{$IMG_URL}" width="{$IMG_WIDTH}"/></td>
	<td class="titleCell"><input type="text" value="{$n['title']}"/></td>
	<td class="urlCell"><input type="text" value="{$n['url']}"/></td>
	<td class="updateCell"><button rowId="{$n['id']}">Update</button></td>
	<td class="removeCell"><button rowId="{$n['id']}">Remove</button></td>
</tr>
HTML_DATA;
				}
				self::resetAlt();
				return json_encode($data);
			}

			return $notables;
		}

		$data['success']     = false;
		$data['message']     = 'There are no notables.';
		$data['htmlMessage'] = '<h1>' . $data['message'] . '</h1>';
		return $returnJSON ? json_encode($data) : false;
	}

	public static function getAbout($returnJSON = true)
	{
		$link = self::openConnection();
		$query = <<<Q
SELECT * FROM `about`
LIMIT 1
Q;

		$result = self::queryDb($query);
		if(mysql_num_rows($result))
		{
			return mysql_fetch_assoc($result);
		}

		return false;
	}

	public static function getUsers($returnJSON = true)
	{
		$username   = fSession::get(USERNAME);
		$userTypeId = $userTypeIdCounter = fSession::get(USER_TYPE_ID);
		$query = <<<Q
SELECT * FROM `users`
WHERE `users`.`userTypeId` <= {$userTypeId}
ORDER BY `users`.`userTypeId` DESC, `users`.`username` ASC
Q;
		$link = self::openConnection();
		$result = self::queryDb($query);

		if(mysql_num_rows($result))
		{
			$data['success'] = true;
			$data['html']    = '<table id="userListTable" cellpadding="0" cellspacing="0" border="0">';
			while($row = mysql_fetch_assoc($result))
			{
				$users[] = $row;
				$select = '<select type="userType" userId="' . $row['id'] . '">';
				$val = 0;
				while(++$val <= $userTypeId)
				{
					$select .= '<option value="' . $val . '" ' . ($row['userTypeId'] == $val ? 'selected' : '') . ' >' . self::$USER_TYPES["$val"] . '</option>';
				}
				$select .= '</select>';

				if($returnJSON)
				{
					$data['html'] .= '<tr class="' . self::altStr('altRow') . '"><td>' . $row['username'] . '</td><td>' . $select . '</td><td><button type="changePassword" userId="' . $row['id'] . '" userTypeId="' . $row['userTypeId'] . '">change password</button></td><td><button type="removeUser" userId="' . $row['id'] . '" userTypeId="' . $row['userTypeId'] . '">remove</button></td></tr>';
				}
			}
			$data['html'] .= '</table>';
			self::resetAlt();
			return $returnJSON ? json_encode($data) : $users;
		}

		if(mysql_num_rows($result) == 0 && !mysql_errno($link))
		{
			$data['success'] = true;
			$data['html']    = '<p>There are no users.</p>';
			return $returnJSON ? json_encode($data) : false;
		}
	}

	public static function getUser($id,$returnJSON = true)
	{
	}

	private static function usernameIsAvailable($username,$returnJSON = false)
	{
		$link = self::openConnection();
		$query = <<<Q
SELECT * FROM `users`
WHERE `users`.`username` = '{$username}'
Q;
		$result = self::queryDb($query);
		if(mysql_num_rows($result) == 0 && !mysql_errno($link))
		{
			return true;
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

	public static function updateFeed($info,$returnJSON = true)
	{
		if(!is_array($info) ||
		!array_key_exists('feedId',$info) ||
		!array_key_exists('catPos',$info))
		{
			$result['success'] = false;
			$result['message'] = 'Please enter a valid category position.';
			return $returnJSON ? json_encode($result) : false;
		}

		$link = self::openConnection();
		$query = <<<Q
UPDATE `mediaFeeds`
SET `categoryPosition` = {$info['catPos']}
WHERE `mediaFeeds`.`id` = {$info['feedId']}
LIMIT 1
Q;

		self::queryDb($query);
		if(mysql_affected_rows())
		{
			$result['success'] = true;
			$result['message'] = 'Successfully updated feed.';
			return $returnJSON ? json_encode($result) : true;
		}

		$result['success'] = false;
		$result['message'] = 'There was an error updating the feed.';
		return $returnJSON ? json_encode($result) : false;

	}

	public static function updateNotable($info,$returnJSON = true)
	{
		if(is_array($info) && array_key_exists('id',$info) && array_key_exists('title',$info) && array_key_exists('url',$info) && filter_var($info['url'],FILTER_VALIDATE_URL))
		{
			$info['title'] = trim($info['title']);
			$info['url']   = trim($info['url']);
			$link          = self::openConnection();
			$query         = <<<Q
UPDATE IGNORE `news`
SET `news`.`title` = '{$info['title']}',
	`news`.`url` = '{$info['url']}'
WHERE id = {$info['id']}
Q;
			self::queryDb($query);
			if(!mysql_errno($link))
			{
				$data['success'] = true;
				$data['msg']     = stripslashes($info['title']) . ' successfully updated.';
				$data['htmlMsg'] = '<h1>' . $data['msg'] . '</h1>';
				return $returnJSON ? json_encode($data) : true;
			}
		}

		$errorData = array();
		$errorData['success'] = false;
		$errorData['msg'] = 'There was an error updating the specified item. Please make sure you provided a valid title and URL.';
		$errorData['htmlMsg'] = '<h1>' . $errorData['msg'] . '</h1>';

		return $returnJSON ? json_encode($errorData) : false;
	}

	public static function updateAbout($info,$returnJSON = true)
	{
		$link = self::openConnection();
		$query = <<<Q
UPDATE IGNORE `about`
SET `about`.`image` = '{$info['image']}',
	`about`.`col1`  = '{$info['col1']}',
	`about`.`col2`  = '{$info['col2']}'
LIMIT 1
Q;

		self::queryDb($query);
		if(!mysql_errno($link))
		{
			return true;
		}
		return false;
	}

	public static function updateUserType($info,$returnJSON = true)
	{
		if(!isset(
			$info['userId'],
			$info['userTypeId']))
		{
			$json['success'] = false;
			$json['msg']   = 'Information provided is incomplete.';
			return $returnJSON ? json_encode($json) : fasle;
		}

		$info['actionId'] = EDIT_USER_TYPE_ID;

		if(!self::userCanPerformAction($info))
		{
			$json['success'] = false;
			$json['msg']     = 'You do not have permission to update the user type for this user.';
			return $returnJSON ? json_encode($json) : false;
		}

		$link = self::openConnection();
		$query = <<<Q
UPDATE `users`
SET `users`.`userTypeId` = {$info['userTypeId']}
WHERE `users`.`id` = {$info['userId']}
LIMIT 1
Q;
		self::queryDb($query);
		if(!mysql_affected_rows() === 1 || mysql_errno($link))
		{
			$json['success'] = false;
			$json['msg']     = 'There was an error updating the user type for this user. Please try again.';
			return $returnJSON ? json_encode($json) : false;
		}

		$json['success'] = true;
		$json['msg']     = 'User type was successfully updated.';
		return $returnJSON ? json_encode($json) : true;
	}

	public static function updatePassword($info,$returnJSON = true)
	{
		if(!isset(
			$info['userId'],
			$info['userTypeId'],
			$info['password'],
			$info['confirmPassword']))
		{
			$json['success'] = false;
			$json['msg']   = 'Information provided is incomplete.';
			return $returnJSON ? json_encode($json) : fasle;
		}

		$info['actionId'] = EDIT_USER_PASSWORD_ID;

		if(!self::userCanPerformAction($info))
		{
			$json['success'] = false;
			$json['msg']     = 'You do not have permission to update the password for this user.';
			return $returnJSON ? json_encode($json) : false;
		}

		$link = self::openConnection();
		$query = <<<Q
UPDATE IGNORE `users`
SET `users`.`password` = SHA1('{$info['userTypeId']}')
WHERE `users`.`id` = {$info['userId']}
LIMIT 1
Q;
		self::queryDb($query);
		if(!mysql_affected_rows() === 1 || mysql_errno($link))
		{
			$json['success'] = false;
			$json['msg']     = 'There was an error updating the password for this user. Please try again.';
			return $returnJSON ? json_encode($json) : false;
		}

		$json['success'] = true;
		$json['msg']     = 'Password was successfully updated.';
		return $returnJSON ? json_encode($json) : true;
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

	public static function removeFeed($feedId,$returnJSON = true)
	{
		(int) $feedId;
		$link = self::openConnection();

		$query = <<<Q
DELETE FROM `mediaFeeds`
WHERE `mediaFeeds`.`id` = {$feedId}
LIMIT 1
Q;

		self::queryDb($query);

		if(mysql_affected_rows())
		{
			$result['success'] = true;
			$result['message'] = 'Feed was successfully removed.';
			return $returnJSON ? json_encode($result) : true;
		}

		$result['success'] = false;
		$result['message'] = 'There was an error removing the feed.';
		return $returnJSON ? json_encode($result) : false;
	}

	public static function removeNotable($info,$returnJSON = true)
	{
		$errorData = array();
		$errorData['success'] = false;
		$errorData['msg'] = 'There was an error removing the specified item.';
		$errorData['htmlMsg'] = '<h1>' . $errorData['msg'] . '</h1>';

		if($info['id'] && $info['fileName'])
		{
			$filePath = ROOT . $info['fileName'];
			$file = new fImage($filePath);
			$file->delete();
			if(file_exists($filePath))
			{
				return $returnJSON ? json_encode($errorData) : false;
			}
			$link          = self::openConnection();
			$query         = <<<Q
DELETE FROM `news`
WHERE id = {$info['id']}
Q;
			self::queryDb($query);
			if(mysql_affected_rows($link))
			{
				$data['success'] = true;
				$data['msg']     = stripslashes('Item successfully removed.');
				$data['htmlMsg'] = '<h1>' . $data['msg'] . '</h1>';
				return $returnJSON ? json_encode($data) : true;
			}
		}

		return $returnJSON ? json_encode($errorData) : false;
	}

	public static function removeUser($info,$returnJSON = true)
	{
		if(!isset(
			$info['userId'],
			$info['userTypeId']))
		{
			$json['success'] = false;
			$json['msg']     = 'Information provided is incomplete.';
			return $returnJSON ? json_encode($json) : fasle;
		}

		$info['actionId'] = REMOVE_USER_ID;

		if(!self::userCanPerformAction($info))
		{
			$json['success'] = false;
			$json['msg']     = 'You do not have permission to remove this user.';
			return $returnJSON ? json_encode($json) : false;
		}

		$link = self::openConnection();
		$query = <<<Q
DELETE FROM `users`
WHERE `users`.`id` = {$info['userId']}
LIMIT 1
Q;
		self::queryDb($query);
		if(!mysql_affected_rows() === 1 || mysql_errno($link))
		{
			$json['success'] = false;
			$json['msg']     = 'There was an error removing the user. Please try again.';
			return $returnJSON ? json_encode($json) : false;
		}

		$userLoginId = fSession::get(USER_LOGIN_ID);
		$self        = (int) $info['userId'] === $userLoginId;

		$json['success']     = true;
		$json['msg']         = 'User was successfully removed.';
		$json['removedSelf'] = $self;
		$self ? fSession::destroy() : void;
		return $returnJSON ? json_encode($json) : true;
	}

	#====================================================================================
	# GENERAL METHODS
	#====================================================================================
	public static function redirectTo($url = null)
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

	private static function isValidUsername($username,$returnJSON = false)
	{
		return (bool) preg_match(USERNAME_RULE,(string) $username);
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

	private static function mysqlClean($value)
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
	# PRIVILEGE METHODS
	#====================================================================================
	
	public static function getUserTypePrivileges()
	{
		return fSession::get(USER_TYPE_PRIVILEGES,false);
	}

	public static function userCanPerformAction($info,$returnJSON = false)
	{
		if(isset($info['userId'],$info['userTypeId'],$info['actionId']))
		{
			$userPrivileges  = fSession::get(USER_PRIVILEGES);
			$userLoginId     = fSession::get(USER_LOGIN_ID);
			$userTypeId      = fSession::get(USER_TYPE_ID);
			$self            = (int) $info['userId'] === $userLoginId;
			$userGroup       = $self ? USER_SELF_ID : ((int) $info['userTypeId']) - 1;
			return (bool) $userPrivileges["$userTypeId"][$userGroup][$info['actionId']];
		}
		return false;
	}

	public static function getPagesForUserType($id)
	{
		$pageSet = 'PAGES' . $id;
		return self::$$pageSet;
	}

	public static function userTypeIsArchitect($userTypeId)
	{
		return (int) $userTypeId === 3;
	}

	public static function usernameIsArchitect($username)
	{
		return $username == ARCHITECT_EMAIL;
	}

	#====================================================================================
	# SESSION METHODS
	#====================================================================================
	public static function login($u,$p)
	{
		$u = trim($u);
		$p = sha1(trim($p));

		$link = self::openConnection();
		$query = <<<Q
SELECT * FROM `users`
WHERE `users`.`username` = '{$u}'
AND `users`.`password` = '{$p}'
LIMIT 1
Q;

		$result = self::queryDb($query);
		if(mysql_num_rows($result) == 1)
		{
			$row = mysql_fetch_assoc($result);
			fSession::set(USER_LOGIN_ID,(int) $row['id']);
			fSession::set(USER_TYPE_ID,(int) $row['userTypeId']);
			fSession::set(USERNAME,$row['username']);
			fSession::set(USER_TYPE_PRIVILEGES,array_slice(self::$USER_TYPES,0,$row['userTypeId'],true));
			fSession::set(USER_PRIVILEGES,unserialize(require(PRIVILEGE_FILE)));
			return (int) $row['id'];
		}
		return false;
	}

	public static function startSession()
	{
		fSession::open();
	}

	public static function isLoggedIn()
	{
		return fSession::get(USER_LOGIN_ID,false) ? true : false;
	}

	public static function confirmLoggedIn($url = null)
	{
		if(!$username = fSession::get(USERNAME,false))
		{
			self::logout($url);
		}
	}

	public static function logout($url = null)
	{
		fSession::destroy();
		self::redirectTo($url ? $url : LOGIN_PAGE);
	}

}

?>