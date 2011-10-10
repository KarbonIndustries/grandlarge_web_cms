<h1>Users</h1>
<?php
#var_dump(fSession::get(USER_PRIVILEGES));
$a = array('userId' => 2,'userTypeId' => 2,'actionId' => 0);
#var_dump(GL::userCanPerformAction($a));
#var_dump(fSession::get(USER_PRIVILEGES));
#echo fSession::get(USER_LOGIN_ID);
?>
<div id="addUserShell">
	<h3>Add User <span class="addUserBlurb">(<?= MIN_USERNAME_LENGTH ?> character minimum username, <?= MIN_PASSWORD_LENGTH ?> character minimun password)</span></h3>
	<div id="addUserFieldsShell">
		<div id="usernameShell">
			<label for="username">Username</label>
			<input type="text" name="username" value="" id="username">
		</div>
		<div id="passwordShell">
			<label for="password">Password</label>
			<input type="password" name="password" value="" id="password">
		</div>
		<div id="confirmPasswordShell">
			<label for="confirmPassword">Confirm Password</label>
			<input type="password" name="confirmPassword" value="" id="confirmPassword">
		</div>
		<div id="userTypeShell">
			<label for="userType">User Type</label>
			<select name="userType" id="userType">
				<?php
				$userTypePrivileges = GL::getUserTypePrivileges();
				foreach($userTypePrivileges as $key => $val)
				{?>
					<option value="<?= $key ?>"><?= ucwords($val) ?></option>
				<?}
				?>
			</select>
		</div>
		<div id="addButtonShell">
			<button id="addUser">Add</button>
		</div>
	</div>
</div>

<div id="editUserShell">
	<h3>Edit User</h3>
	<div id="userListShell"></div>
</div>

<script type="text/javascript" charset="utf-8">
	users.init();
</script>