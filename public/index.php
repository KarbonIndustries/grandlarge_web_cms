<?php
require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'init.php');

if(GL::isLoggedIn())
{
	$page = fSession::get(USER_TYPE_ID) == 1 ? 'files' : 'directors';
	GL::redirectTo('/home.php?p=' . $page);
}

$submitted  = $_SERVER['REQUEST_METHOD'] == 'POST';
$SELF       = $_SERVER['PHP_SELF'];
$loginError = '';

if($submitted)
{
	if($user = GL::login($_POST['username'],$_POST['password']))
	{
		$page = fSession::get(USER_TYPE_ID) == 1 ? 'files' : 'directors';
		GL::redirectTo('/home.php?p=' . $page);
	}

	$loginError = <<<ERR
<tr>
	<th id="loginErrorHead">
		Incorrect username/password
	</th>
</tr>
ERR;
}
require_once(ELEMENT_DIR . 'html_head.php');
?>


<div id="loginShell">
	<table id="loginTable" cellpadding="0" cellspacing="0" border="0">
		<thead>
			<?= $loginError ?>
			<tr><th id="loginTitle"><?= COMPANY_PRETTY_URL ?> CMS Login</th></tr>
		</thead>
		<tbody>
			<tr>
				<td>
					<form action="<?= $SELF ?>" method="post" accept-charset="utf-8">
						<div id="usernameShell">
							<label for="username">Username</label>
							<input type="text" name="username" value="" id="username" tabindex="1"></div>
						<div id="passwordShell">
							<label for="password">Password</label>
							<input type="password" name="password" value="" id="password" tabindex="2">
						</div>
						<div id="submitShell">
							<input type="submit" value="Login" tabindex="3">
						</div>
					</form>
				</td>
			</tr>
		</tbody>
	</table>
</div>

<script type="text/javascript" charset="utf-8">
	$(function()
	{
		$('div#loginShell').find('div#usernameShell').find('input#username').focus();
	});
</script>

<?php
require_once(ELEMENT_DIR . 'html_foot.php');
GL::closeDb();
?>