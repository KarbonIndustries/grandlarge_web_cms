<h1>Contact</h1>

<div id="officeCategoryShell">
	<h3>Add/Edit Office Categories</h3>
	<table id="officeCategoryTable" cellspacing="0" cellpadding="0" border="0">
	<?php
	$categories = GL::getOfficeCategories();
	
	foreach($categories as $c)
	{?>
		<tr officeCategoryId="<?= $c['id'] ?>" class="<?= GL::altStr('altRow') ?>">
			<td class="officeCategoryName"><input type="text" name="name<?= $c['id'] ?>" id="<?= $c['id'] ?>" value="<?= $c['name'] ?>" /></td>
			<td class="removeBtn"><button class="removeOfficeCategoryBtn" name="<?= $c['name'] ?>" id="<?= $c['id'] ?>">Remove</button></td>
			<td class="updateBtn"><button class="updateOfficeCategoryBtn" name="<?= $c['name'] ?>" id="<?= $c['id'] ?>">Update</button></td>
		</tr>
	<?}

	GL::resetAlt();
	?>
	</table>
	<div id="addBtnShell"><label for="category"><input type="text" name="categoryName" id="categoryName" /></label><button id="addOfficeCategoryBtn">Add</button></div>
</div>

<div>
	<h3>Add/Edit Office</h3>


	<div id="editDirectorShell">
		<div><label for="firstName">First Name</label><input type="text" name="firstName" id="firstName" /></div>
		<div><label for="lastName">Last Name</label><input type="text" name="lastName" id="lastName" /></div>
		<div><label for="bio">Bio</label><textarea name="bio" id="bio" rows="6"></textarea></div>
		<div><label for="description">Description</label><input type="text" name="description" id="description" /></div>
		<div><label for="website">Web Site</label><input type="text" name="website" id="website" /></div>
		<div id="updateBtnShell"><button id="updateDirectorBtn"></button></div>
	</div>
</div>
<script type="text/javascript" charset="utf-8">
	contact.init();
</script>