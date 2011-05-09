<h1>Contact</h1>

<!-- ===================== -->
<!-- = OFFICE CATEGORIES = -->
<!-- ===================== -->
<div id="officeCategoryShell">
	<h3>Add/Edit Office Categories</h3>

	<table id="officeCategoryTable" cellspacing="0" cellpadding="0" border="0">
	<?php
	if($categories = GL::getOfficeCategories())
	{
		foreach($categories as $c)
		{?>
			<tr officeCategoryId="<?= $c['id'] ?>" class="<?= GL::altStr('altRow') ?>">
				<td class="officeCategoryName"><input type="text" name="name<?= $c['id'] ?>" id="<?= $c['id'] ?>" value="<?= $c['name'] ?>" /></td>
				<td class="removeBtn"><button class="removeOfficeCategoryBtn" name="<?= $c['name'] ?>" id="<?= $c['id'] ?>">Remove</button></td>
				<td class="updateBtn"><button class="updateOfficeCategoryBtn" name="<?= $c['name'] ?>" id="<?= $c['id'] ?>">Update</button></td>
			</tr>
		<?}
		GL::resetAlt();
	}else
	{?>
		
	<?}?>
	</table>
	<div id="addBtnShell"><label for="category"><input type="text" name="categoryName" id="categoryName" /></label><button id="addOfficeCategoryBtn">Add</button></div>
</div>

<!-- =============== -->
<!-- = ADD OFFICES = -->
<!-- =============== -->
<div id="officeShell">
	<h3>Add Office</h3>

	<div id="addOfficeShell">
		<div><label for="officeCategoryId">Office Category</label>
			<select name="officeCategoryId" id="officeCategoryId">
				<option value="" selected="selected">Select an office category</option>
				<?php
				if($categories = GL::getOfficeCategories())
				{
					foreach($categories as $c)
					{?>
						<option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
					<?}
				}else
				{?>
					<option value="">No categories available</option>
				<?}?>
			</select>
		</div>
		<div><label for="officeName">Office</label><input type="text" name="officeName" id="officeName" /></div>
		<div><label for="companyName">Company</label><input type="text" name="companyName" id="companyName" /></div>
		<div><label for="address1">Address 1</label><input type="text" name="address1" id="address1" /></div>
		<div><label for="address2">Address 2</label><input type="text" name="address2" id="address2" /></div>
		<div><label for="address3">Address 3</label><input type="text" name="address3" id="address3" /></div>
		<div><label for="city">City/Town</label><input type="text" name="city" id="city" /></div>
		<div><label for="state">State/Province</label>
			<select name="state" id="state">
				<option value="51" selected="selected">Select a state</option>
				<?php
				if($states = GL::getStates())
				{
					foreach($states as $s)
					{?>
						<option value="<?= $s['id'] ?>"><?= $s['abbreviation'] ?></option>
					<?}
				}else
				{?>
					<option value="">No states available</option>
				<?}?>
			</select>
		</div>
		<div><label for="zip">Zip Code</label><input type="text" name="zip" id="zip" /></div>
		<div><label for="country">Country</label><input type="text" name="country" id="country" /></div>
		<div class="contactDiv"><div class="labelDiv"><label for="contact1FirstName">Contact 1 First Name</label><input type="text" name="contact1FirstName" id="contact1FirstName" /></div><div class="labelDiv"><label for="contact1LastName">Contact 1 Last Name</label><input type="text" name="contact1LastName" id="contact1LastName" /></div></div>
		<div class="contactDiv"><div class="labelDiv"><label for="contact2FirstName">Contact 2 First Name</label><input type="text" name="contact2FirstName" id="contact2FirstName" /></div><div class="labelDiv"><label for="contact2LastName">Contact 2 Last Name</label><input type="text" name="contact2LastName" id="contact2LastName" /></div></div>
		<div class="contactDiv"><div class="labelDiv"><label for="contact3FirstName">Contact 3 First Name</label><input type="text" name="contact3FirstName" id="contact3FirstName" /></div><div class="labelDiv"><label for="contact3LastName">Contact 3 Last Name</label><input type="text" name="contact3LastName" id="contact3LastName" /></div></div>
		<div><label for="phone">Phone</label><input type="text" name="phone" id="phone" /></div>
		<div><label for="email">Email</label><input type="text" name="email" id="email" /></div>
		<div><label for="websiteURL">Website</label><input type="text" name="websiteURL" id="websiteURL" /></div>
		<div id="addBtnShell"><button id="addOfficeBtn"></button></div>
	</div>

<!-- ================ -->
<!-- = EDIT OFFICES = -->
<!-- ================ -->
	<h3>Edit Office</h3>
	<div id="editOfficeShell">
		<table id="officeTable" border="0" cellspacing="0" cellpadding="0">
		<?php
		if($contacts = GL::getOffices())
		{
			foreach($contacts as $c)
			{?>
				<tr id="<?= $c['id'] ?>" class="<?= GL::altStr('altRow') ?>">
					<td  officeId="<?= $c['id'] ?>" class="companyName"><?= $c['companyName'] ?></td>
					<td  officeId="<?= $c['id'] ?>" class="officeName"><?= $c['officeLocale'] ?></td>
					<td  class="removeBtn"><button officeId="<?= $c['id'] ?>" class="removeOfficeBtn">Remove</button></td>
				</tr>
			<?}
			GL::resetAlt();
		}else
		{?>
			<tr><td>No offices available</td></tr>
		<?}?>
		</table>

<!-- =============== -->
<!-- = EDIT OFFICE = -->
<!-- =============== -->
		<div id="editOffice">
			<div><label for="officeCategoryId">Office Category</label>
				<select name="officeCategoryId" id="officeCategoryId">
					<?php
					if(isset($categories))
					{?>
						<option value="" selected="selected">Select an office category</option>
						<?
						foreach($categories as $c)
						{?>
							<option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
						<?}
					}else
					{?>
						<option value="">No categories available</option>
					<?}?>
				</select>
			</div>
			<div><label for="officeName">Office</label><input type="text" name="officeName" id="officeName" /></div>
			<div><label for="companyName">Company</label><input type="text" name="companyName" id="companyName" /></div>
			<div><label for="address1">Address 1</label><input type="text" name="address1" id="address1" /></div>
			<div><label for="address2">Address 2</label><input type="text" name="address2" id="address2" /></div>
			<div><label for="address3">Address 3</label><input type="text" name="address3" id="address3" /></div>
			<div><label for="city">City/Town</label><input type="text" name="city" id="city" /></div>
			<div><label for="state">State/Province</label>
				<select name="state" id="state">
					<?php
					if(isset($states))
					{?>
						<option value="51" selected="selected">Select a state</option>
						<?
						foreach($states as $s)
						{?>
							<option value="<?= $s['id'] ?>"><?= $s['abbreviation'] ?></option>
						<?}
					}else
					{?>
						<option value="">No states available</option>
					<?}?>
				</select>
			</div>
			<div><label for="zip">Zip Code</label><input type="text" name="zip" id="zip" /></div>
			<div><label for="country">Country</label><input type="text" name="country" id="country" /></div>
			<div class="contactDiv"><div class="labelDiv"><label for="contact1FirstName">Contact 1 First Name</label><input type="text" name="contact1FirstName" id="contact1FirstName" /></div><div class="labelDiv"><label for="contact1LastName">Contact 1 Last Name</label><input type="text" name="contact1LastName" id="contact1LastName" /></div></div>
			<div class="contactDiv"><div class="labelDiv"><label for="contact2FirstName">Contact 2 First Name</label><input type="text" name="contact2FirstName" id="contact2FirstName" /></div><div class="labelDiv"><label for="contact2LastName">Contact 2 Last Name</label><input type="text" name="contact2LastName" id="contact2LastName" /></div></div>
			<div class="contactDiv"><div class="labelDiv"><label for="contact3FirstName">Contact 3 First Name</label><input type="text" name="contact3FirstName" id="contact3FirstName" /></div><div class="labelDiv"><label for="contact3LastName">Contact 3 Last Name</label><input type="text" name="contact3LastName" id="contact3LastName" /></div></div>
			<div><label for="phone">Phone</label><input type="text" name="phone" id="phone" /></div>
			<div><label for="email">Email</label><input type="text" name="email" id="email" /></div>
			<div><label for="websiteURL">Website</label><input type="text" name="websiteURL" id="websiteURL" /></div>
			<div id="updateOfficeBtnShell"><button id="updateOfficeBtn"></button></div>
		</div>
	</div>
</div>
<script type="text/javascript" charset="utf-8">
	contact.init();
</script>