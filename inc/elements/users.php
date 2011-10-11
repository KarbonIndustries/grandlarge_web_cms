<h1>Users</h1>

<div id="addUserShell">
	<h3>Add User <span class="addUserBlurb">(<?= MIN_USERNAME_LENGTH . ' - ' . MAX_USERNAME_LENGTH ?> character username, <?= MIN_PASSWORD_LENGTH . ' - ' . MAX_PASSWORD_LENGTH ?> character password)</span></h3>
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

<!-- =================== -->
<!-- = PASSWORD DIALOG = -->
<!-- =================== -->
<div id="passwordDialog">
	<div id="passwordDialogError" class="invisible"></div>

	<div id="dialogPasswordShell">
		<label for="dialogPassword">Password</label>
		<input type="password" name="dialogPassword" value="" id="dialogPassword">
	</div>
	<div id="dialogConfirmPasswordShell">
		<label for="dialogConfirmPassword">Confirm Password</label>
		<input type="password" name="dialogConfirmPassword" value="" id="dialogConfirmPassword">
	</div>
</div>

<?php
define('PAGE_SCRIPT',
<<<SCRIPT
users.init();
SCRIPT
);
?>