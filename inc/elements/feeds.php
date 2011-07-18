<h1>Feeds</h1>

<?php
$directors = GL::getDirectors();
$mediaCategories = GL::getMediaCategories();

if(!$directors && !$mediaCategories)
{
	die('There was an error retrieving data for this page.');
}?>


<!-- ============ -->
<!-- = ADD FEED = -->
<!-- ============ -->

<div id="addFeedShell">
	<h3>Add Feed</h3>

	<select name="directors" id="directors">
		<option value="0" selected="selected">Select a director</option>
	<?php
	foreach($directors as $d)
	{?>
		<option value="<?= $d['id'] ?>"><?= $d['firstName'] . ' ' . $d['lastName']?></option>
	<?}?>
	</select>

	<select name="mediaCategories" id="mediaCategories">
		<option value="0" selected="selected">Select a category</option>
	<?php
	foreach($mediaCategories as $mc)
	{?>
		<option value="<?= $mc['id'] ?>"><?= $mc['name'] ?></option>
	<?}?>
	</select>

	<input type="text" name="feedUrl" id="feedUrl" value="URL"/>

	<div id="addBtnShell"><button id="addFeedBtn"></button></div>
</div>


<!-- ============= -->
<!-- = EDIT FEED = -->
<!-- ============= -->

<div id="editFeedShell">
	<h3>Edit Feed</h3>

	<table id="feedList" border="0" cellspacing="0" cellpadding="2">
	</table>
</div>
<script type="text/javascript" charset="utf-8">
	feeds.init();
</script>