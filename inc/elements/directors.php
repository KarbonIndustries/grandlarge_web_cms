<h1>Directors</h1>

<div id="addDirectorShell">
	<h3>Add Director</h3>
	<div><label for="firstName">First Name</label><input type="text" name="firstName" id="firstName" /></div>
	<div><label for="lastName">Last Name</label><input type="text" name="lastName" id="lastName" /></div>
	<div><label for="bio">Bio</label><textarea name="bio" id="bio" rows="6"></textarea></div>
	<div><label for="description">Description</label><input type="text" name="description" id="description" /></div>
	<div><label for="website">Website</label><input type="text" name="website" id="website" /></div>
	<div id="addBtnShell"><button id="addDirectorBtn"></button></div>
</div>

<div>
	<h3>Edit Director</h3>

	<table id="directorTable" cellspacing="0" cellpadding="0" border="0">
	<?php
	$directors = GL::getDirectors();

	foreach($directors as $d)
	{?>
		<tr directorId="<?= $d['id'] ?>" class="<?= GL::altStr('altRow') ?>">
			<td class="directorName"><?= $d['firstName'] . ' ' . $d['lastName'] ?></td>
			<td class="removeBtn"><button class="removeDirectorBtn">Remove</button></td>
		</tr>
	<?}

	GL::resetAlt();
	?>
	</table>

	<div id="editDirectorShell">
		<div><label for="firstName">First Name</label><input type="text" name="firstName" id="firstName" /></div>
		<div><label for="lastName">Last Name</label><input type="text" name="lastName" id="lastName" /></div>
		<div><label for="bio">Bio</label><textarea name="bio" id="bio" rows="6"></textarea></div>
		<div><label for="description">Description</label><input type="text" name="description" id="description" /></div>
		<div><label for="website">Website</label><input type="text" name="website" id="website" /></div>
		<div id="updateBtnShell"><button id="updateDirectorBtn"></button></div>
	</div>
</div>

<div style="margin-bottom:100px"></div>

<?php
define('PAGE_SCRIPT',
<<<SCRIPT
directors.init();
SCRIPT
);
?>