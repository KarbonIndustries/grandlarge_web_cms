<?php
require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'init.php');
require_once(ELEMENT_DIR . 'html_head.php');
$pages = GL::getPages();
$reqPage = isset($_GET['p']) ? strtolower($_GET['p']) : NULL;
$selPage = in_array($reqPage,$pages,true) ? $reqPage : DEFAULT_PAGE;

?>

<div id="navShell">
	<div>
		<h1>Admin</h1>
	</div>
	<ul id="nav">
		<li><a class="<?= $selPage == array_shift($pages) ? 'selected' : '' ?>" href="/?p=directors">Directors</a></li>
		<li><a class="<?= $selPage == array_shift($pages) ? 'selected' : '' ?>" href="/?p=feeds">Feeds</a></li>
		<li><a class="<?= $selPage == array_shift($pages) ? 'selected' : '' ?>" href="/?p=notable">Notable</a></li>
		<li><a class="<?= $selPage == array_shift($pages) ? 'selected' : '' ?>" href="/?p=about">About</a></li>
		<li><a class="<?= $selPage == array_shift($pages) ? 'selected' : '' ?>" href="/?p=contact">Contact</a></li>
		<li><a class="<?= $selPage == array_shift($pages) ? 'selected' : '' ?>" href="/?p=users">Users</a></li>
		<li><a class="<?= $selPage == array_shift($pages) ? 'selected' : '' ?>" href="/?p=files">Files</a></li>
		<li><a href="/logout.php">Logout</a></li>
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
GL::closeDb();
?>