<?php
require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'init.php');
GL::confirmLoggedIn();
$SELF    = $_SERVER['PHP_SELF'];
$PAGES   = GL::getPagesForUserType(fSession::get(USER_TYPE_ID));
$reqPage = isset($_GET['page']) ? strtolower($_GET['page']) : NULL;
$selPage = in_array($reqPage,$PAGES,true) ? $reqPage : $PAGES[0];
GL::checkForUserFileAction($fileError,$userFiles);
require_once(ELEMENT_DIR . 'html_head.php');
?>

<div id="navShell">
	<div>
		<h1>Admin</h1>
	</div>
	<ul id="nav">
		<?php
		while($pageName = array_shift($PAGES))
		{?>
			<li><a class="<?= $selPage == $pageName ? 'selected' : '' ?>" href="/<?= $pageName ?>"><?= ucwords($pageName) ?></a></li>
		<?}
		?>
			<li><a href="<?= COMPANY_BASE_URL ?>" target="_blank">Visit Site</a></li>
			<li><a href="/logout" title="Logged in as <?= fSession::get(USERNAME) ?>">Logout</a></li>
	</ul>
</div>

<div id="contentShell">
<?php
require_once(ELEMENT_DIR . $selPage . '.php');
?>
</div>

<?php
require_once(ELEMENT_DIR . 'html_foot.php');
GL::closeDb();
?>