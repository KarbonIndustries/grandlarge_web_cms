<h1>Files</h1>

<div id="fileShell">
<?php
if($fileError)
{
	if($fileError === NO_FILES_FOUND && $_GET['username'] === fSession::get(USERNAME))
	{?>
		<h4 class="fileErrorMsg"><?= NO_FILES_FOUND_FOR_YOU ?></h4>
		
	<?}else
	{?>
		<h4 class="fileErrorMsg"><?= $fileError ?></h4>
	<?}
	if($fileError === NO_FILES_FOUND)
	{?>
		<h3><a class="fileBackLink" href="/files">Back</a></h3>
	<?}
}
elseif($userFiles && isset($_GET['username']))
{
	if(fSession::get(USER_TYPE_ID) > 1)
	{?>
		<h3><a class="fileBackLink" href="/files">Back</a></h3>
	<?}
	$username = $_GET['username'];
	foreach($userFiles as $file)
	{?>
		<div class="fileRow <?= GL::altStr('altRow') ?>"><a href="/files/download/<?= $username . '/' . GL::encryptStr($file->getName(),FILENAME_HASH_METHOD) ?>"><?= $file->getName() . ' <span class="fileSize">(' . fFilesystem::formatFileSize($file->getSize()) . ')</span>' ?></a></div>
	<?}
	if(fSession::get(USER_TYPE_ID) > 1)
	{?>
		<h3><a class="fileBackLink" href="/files">Back</a></h3>
	<?}
}else
{
	$userTypeId = fSession::get(USER_TYPE_ID);
	$users = GL::getUsersWithMaxUserType($userTypeId);
	$userDirs;
	switch($userTypeId)
	{
		case 1:
		$userFiles = GL::getUserFiles(fSession::get(USERNAME));
		break;
		case 2:
		$userDirs = GL::getUserDirectories($users);
		break;
		case 3:
		$userDirs = GL::getUserDirectories();
		break;
		default:
		break;
	}

	if($userFiles)
	{?>
		<?
		foreach($userFiles as $file)
		{?>
			<div class="fileRow <?= GL::altStr('altRow') ?>"><a class="fileLink" href="/files/download/<?= fSession::get(USERNAME) . '/' . GL::encryptStr($file->getName(),FILENAME_HASH_METHOD) ?>"><?= $file->getName() . ' <span class="fileSize">(' . fFilesystem::formatFileSize($file->getSize()) . ')</span>' ?></a></div>
		<?}
	}elseif(isset($userDirs))
	{?>
		<h3 class="usersTitle">Users</h3>
		<?
		foreach($userDirs as $dir)
		{
			$fileCount = count($dir->scan());
		?>
			<div class="fileRow <?= GL::altStr('altRow') ?>"><a href="/files/view/<?= $dir->getName() ?>"><?= $dir->getName() . ' <span class="fileSize">(' . $fileCount . ($fileCount == 1 ? ' file => ' : ' files ') . fFilesystem::formatFileSize($dir->getSize()) . ')</span>' ?></a></div>
		<?}
	}else
	{?>
		<h4 class="fileErrorMsg"><?= NO_FILES_FOUND_FOR_YOU ?></h4>
	<?}
}
GL::resetAlt();
?>
</div>