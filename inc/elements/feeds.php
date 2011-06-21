<h1>Feeds</h1>

<div id="addFeedShell">
<h3>Add Feed</h3>

<?php
if(($directors = GL::getDirectors()) && ($mediaCategories = GL::getMediaCategories()))
{?>
	<select name="directors" id="directors">
	<?foreach($directors as $d)
	{?>
		<option value="<?= $d['id'] ?>"><?= $d['firstName'] . ' ' . $d['lastName']?></option>
	<?}?>
	</select>

	<select name="mediaCategories" id="mediaCategories">
	<?foreach($mediaCategories as $mc)
	{?>
		<option value="<?= $mc['id'] ?>"><?= $mc['name'] ?></option>
	<?}?>
	</select>

	<input type="text" name="feedUrl" id="feedUrl" value="Feed URL"/>

	<div id="addBtnShell"><button id="addFeedBtn"></button></div>
<?}?>
</div>

<div id="editFeedShell">
<h3>Edit Feed</h3>

<?php
if($feeds = GL::getFeeds())
{?>
	<table id="feedList" border="0" cellspacing="0" cellpadding="2">
	<?$catId = null;
	foreach($feeds as $f)
	{
		if($f['mediaCategoryID'] !== $catId)
		{?>
			<thead>
				<tr>
					<th colspan="4"><?= $f['categoryName'] ?></th>
				</tr>
			</thead>
			<?$catId = $f['mediaCategoryID'];
			GL::resetAlt();
		}?>
			<tbody>
				<tr <?= GL::altStr('class="altRow"') ?> rowId="<?= $f['id'] ?>">
					<td class="positionCol"><input class="feedCategoryPosition" type="text" value="<?= $f['categoryPosition'] ?>"/></td>
					<td class="nameCol"><?= $f['directorName'] ?></td>
					<td class="updateCol"><button class="updateFeedBtn" name="" id="" feedId="<?= $f['id'] ?>">Update</button></td>
					<td class="removeCol"><button class="removeFeedBtn" name="" id="" feedId="<?= $f['id'] ?>">Remove</button></td>
				</tr>
			</tbody>
	<?}?>
	</table>
<?}?>
</div>
<script type="text/javascript" charset="utf-8">
	feeds.init();
</script>