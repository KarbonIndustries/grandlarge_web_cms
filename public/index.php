<?php
require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'init.php');
require_once(ELEMENT_DIR . 'html_head.php');
$pages = array('directors','feeds','notable','about','contact','users','files');
$reqPage = isset($_GET['p']) ? strtolower($_GET['p']) : NULL;
$selPage = in_array($reqPage,$pages,true) ? $reqPage : DEFAULT_PAGE;

?>

<div id="navShell">
	<div>
		<h1>Admin</h1>
	</div>
	<ul id="nav">
		<li><a class="<?= $selPage == $pages[0] ? 'selected' : '' ?>" href="/?p=directors">Directors</a></li>
		<li><a class="<?= $selPage == $pages[1] ? 'selected' : '' ?>" href="/?p=feeds">Feeds</a></li>
		<li><a class="<?= $selPage == $pages[2] ? 'selected' : '' ?>" href="/?p=notable">Notable</a></li>
		<li><a class="<?= $selPage == $pages[3] ? 'selected' : '' ?>" href="/?p=about">About</a></li>
		<li><a class="<?= $selPage == $pages[4] ? 'selected' : '' ?>" href="/?p=contact">Contact</a></li>
		<li><a class="<?= $selPage == $pages[5] ? 'selected' : '' ?>" href="/?p=users">Users</a></li>
		<li><a class="<?= $selPage == $pages[6] ? 'selected' : '' ?>" href="/?p=files">Files</a></li>
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
?>