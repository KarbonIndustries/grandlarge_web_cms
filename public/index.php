<?php
require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'init.php');


if(GL::isLoggedIn())
{
	$pages = GL::getPagesForUserType(fSession::get(USER_TYPE_ID));
	GL::redirectTo('/home.php?p=' . $pages[0]);
}

$submitted  = $_SERVER['REQUEST_METHOD'] == 'POST';
$SELF       = $_SERVER['PHP_SELF'];
$loginError = '';

if($submitted)
{
	if($user = GL::login($_POST['username'],$_POST['password']))
	{
		$pages = GL::getPagesForUserType(fSession::get(USER_TYPE_ID));
		GL::redirectTo('/home.php?p=' . $pages[0]);
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

<?php
define('PAGE_SCRIPT',
<<<SCRIPT
$(function()
{
	$('div#loginShell').find('div#usernameShell').find('input#username').focus();
});
SCRIPT
);

require_once(ELEMENT_DIR . 'html_foot.php');
GL::closeDb();
?>