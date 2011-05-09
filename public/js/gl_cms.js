// CONFIG
var SCRIPT_DIR = '/../scripts/',
	AJAX_FILE = SCRIPT_DIR + 'ajax.php';

// OBJECTS
var directors = {},
	feeds     = {},
	notable   = {},
	about     = {},
	contact   = {},
	users     = {},
	files     = {};

// DIRECTORS
directors.init                          = function()
{
	var d                = this;
	d.errors             = {};
	d.errors.add         = 'You must specify a first name.';
	d.errors.get         = 'There was an error retrieving the director you requested.';
	d.errors.update1     = 'You cannot update a director without changing their info.';
	d.errors.update2     = 'You must specify a first name.';
	d.resetDirector();
	d.addDirectorFields  = $('#addDirectorShell #firstName,#addDirectorShell #lastName,#addDirectorShell #bio,#addDirectorShell #description,#addDirectorShell #website');
	d.editDirectorFields = $('#editDirectorShell #firstName,#editDirectorShell #lastName,#editDirectorShell #bio,#editDirectorShell #description,#editDirectorShell #website');
	d.addTableListeners().addSubmitListeners();
	return d;
};

directors.addTableListeners             = function()
{
	var d = this;

	// GET DIRECTOR
	$('.directorName').click(function()
	{
		var params = {};
		params.id  = $(this).parent().attr('directorId');
		
		$.get(AJAX_FILE,{callback:'getDirector',params:params},function(data)
		{
			if(data.success)
			{
				d.currentDirector.id          = data.id;
				d.currentDirector.firstName   = data.firstName;
				d.currentDirector.lastName    = data.lastName;
				d.currentDirector.bio         = data.bio;
				d.currentDirector.description = data.description;
				d.currentDirector.website     = data.websiteURL;
				$('#editDirectorShell #firstName').val(data.firstName);
				$('#editDirectorShell #lastName').val(data.lastName);
				$('#editDirectorShell #bio').val(data.bio);
				$('#editDirectorShell #description').val(data.description);
				$('#editDirectorShell #website').val(data.websiteURL);
			}else
			{
				alert(d.errors.get);
			}
		},'json');
	});
	
	// REMOVE DIRECTOR
	$('.removeDirectorBtn').click(function()
	{
		var directorName = $(this).parent().prev().text(),
			params       = {};
		params.id        = $(this).parent().parent().attr('directorId');
		
		// confirm delete
		if(!confirm('Are you sure you want to remove ' + directorName + '?'))
		{
			return;
		}

		$.get(AJAX_FILE,{callback:'removeDirector',params:params},function(data)
		{
			if(data.success)
			{
				$('#directorTable').html(data.data);
				d.addTableListeners().resetDirector();
				d.editDirectorFields.val('');
			}else
			{
				alert(data.message);
			}
		},'json');
	});

	return d;
};

directors.addSubmitListeners            = function()
{
	var d = this;

	// ADD DIRECTOR
	$('#addDirectorShell #addDirectorBtn').click(function()
	{
		params             = {};
		params.firstName   = $.trim($('#addDirectorShell #firstName').val());
		params.lastName    = $.trim($('#addDirectorShell #lastName').val());
		params.bio         = $.trim($('#addDirectorShell #bio').val());
		params.description = $.trim($('#addDirectorShell #description').val());
		params.website     = $.trim($('#addDirectorShell #website').val());

		// confirm first name contains value
		if(params.firstName == '')
		{
			alert(d.errors.add);
			return;
		}

		$.get(AJAX_FILE,{callback:'addDirector',params:params},function(data)
		{
			if(data.success)
			{
				$('#directorTable').html(data.data);
				d.addTableListeners().resetDirector().clearAddDirectorFields().clearEditDirectorFields();
			}else
			{
				alert(data.message);
			}
		},'json');
	});

	// UPDATE DIRECTOR
	$('#updateBtnShell #updateDirectorBtn').click(function()
	{
		var params         = {};
		params.firstName   = $.trim($('#editDirectorShell #firstName').val());
		params.lastName    = $.trim($('#editDirectorShell #lastName').val());
		params.bio         = $.trim($('#editDirectorShell #bio').val());
		params.description = $.trim($('#editDirectorShell #description').val());
		params.website     = $.trim($('#editDirectorShell #website').val());

		// confirm first name contains value
		if(params.firstName == '')
		{
			alert(d.errors.update2);
			return;
		}

		// confirm id is set and info has been changed
		if(d.currentDirector.id && (
		d.currentDirector.firstName   != params.firstName   || 
		d.currentDirector.lastName    != params.lastName    || 
		d.currentDirector.bio         != params.bio         || 
		d.currentDirector.description != params.description || 
		d.currentDirector.website     != params.website
		))
		{
			params.id = d.currentDirector.id;
			$.get(AJAX_FILE,{callback:'updateDirector',params:params},function(data)
			{
				if(data.success)
				{
					$('#directorTable').html(data.data);
					d.addTableListeners().resetDirector().clearEditDirectorFields();
				}else
				{
					alert(data.message);
				}
			},'json');
		}else
		{
			alert(d.errors.update1);
		}
	});

	return d;
};

directors.resetDirector                 = function()
{
	var d = this;

	d.currentDirector             = {};
	d.currentDirector.id          = null;
	d.currentDirector.firstName   = null;
	d.currentDirector.lastName    = null;
	d.currentDirector.bio         = null;
	d.currentDirector.description = null;
	d.currentDirector.website     = null;

	return d;
};

directors.clearAddDirectorFields        = function()
{
	var d = this;
	d.addDirectorFields.val('');
	return d;
};

directors.clearEditDirectorFields       = function()
{
	var d = this;
	d.editDirectorFields.val('');
	return d;
};

// CONTACT
contact.init                            = function()
{
	var c              = this;

	c.errors           = {};
	ce                 = c.errors;
	ce.removeCategory  = 'There was an error deleting the category.';
	ce.updateCategory  = 'You must enter a category name.';
	ce.addCategory     = 'You must enter a category name.';
	ce.addContact1     = 'You must enter an office category, an office name and a company name.';

	c.objects          = {};
	co                 = c.objects;
	co.addOfficeFields = $(':text#officeName,:text#companyName,:text#address1,:text#address2,:text#address3,:text#city,:text#zip,:text#country,:text#contact1FirstName,:text#contact1LastName,:text#contact2FirstName,:text#contact2LastName,:text#contact3FirstName,:text#contact3LastName,','#addOfficeShell,:text#phone,:text#email,:text#websiteURL');

	c.addOfficeCategoryTableListeners().addOfficeTableListeners().addSubmitOfficeCategoryListener().addSubmitOfficeListener();

	return c;
};

contact.addOfficeCategoryTableListeners = function()
{
	var c = this;

	// REMOVE CATEGORY
	$("#officeCategoryTable :button.removeOfficeCategoryBtn").click(function()
	{
		var name = $(":input[name=name" + $(this).attr('id') + "]").val(),
			params = {};
		params.id  = $(this).attr('id');

		// confirm delete
		if(!confirm('WARNING:\nAll offices in this category will be removed!\n\nAre you sure you want to remove this category?'))
		{
			return;
		}

		$.get(AJAX_FILE,{callback:'removeOfficeCategory',params:params},function(data)
		{
			if(data.success)
			{
				$('#officeCategoryTable').html(data.data);
				$('#officeCategoryId','#addOfficeShell').html(data.data2);
				$('#officeTable','#editOfficeShell').html(data.data3);
				c.addOfficeCategoryTableListeners().addOfficeTableListeners();
			}else
			{
				alert(data.message);
			}
		},'json');
	});

	// UPDATE CATEGORY
	$("#officeCategoryTable :button.updateOfficeCategoryBtn").click(function()
	{
		var params  = {};
		params.id   = $(this).attr('id');
		params.name = $.trim($(":input[name=name" + $(this).attr('id') + "]").val());

		if(params.name == '')
		{
			alert(c.errors.updateCategory);
			return;
		}

		$.get(AJAX_FILE,{callback:'updateOfficeCategory',params:params},function(data)
		{
			if(data.success)
			{
				$('#officeCategoryTable').html(data.data);
				$('#officeCategoryId','#addOfficeShell').html(data.data2);
				c.addOfficeCategoryTableListeners();
			}else
			{
				alert(data.message);
			}
		},'json');
	});

	return c;
};

contact.addOfficeTableListeners         = function()
{
	var c = this;
	
	return c;
};

contact.addSubmitOfficeCategoryListener = function()
{
	var c = this;

	// ADD CATEGORY
	$(":button#addOfficeCategoryBtn").click(function()
	{
		var input   = $("#addBtnShell :text#categoryName"),
			params  = {};
		params.name = $.trim(input.val());

		// confirm name contains value
		if(params.name == '')
		{
			alert(c.errors.addCategory);
			return;
		}

		$.get(AJAX_FILE,{callback:'addOfficeCategory',params:params},function(data)
		{
			if(data.success)
			{
				$('#officeCategoryTable','#officeCategoryShell').html(data.data);
				$('#officeCategoryId','#addOfficeShell').html(data.data2);
				c.addOfficeCategoryTableListeners();
				input.val('');
			}else
			{
				alert(data.message);
			}
		},'json');
	});

	return c;
};

contact.addSubmitOfficeListener         = function()
{
	var c = this;

	// ADD OFFICE
	$(':button#addOfficeBtn').click(function()
	{
		var params               = {};
		params.officeCategoryID  = $('#addOfficeShell select#officeCategoryId').val();
		params.officeLocale      = $.trim($('#addOfficeShell :text#officeName').val());
		params.companyName       = $.trim($('#addOfficeShell :text#companyName').val());
		params.address1          = $.trim($('#addOfficeShell :text#address1').val());
		params.address2          = $.trim($('#addOfficeShell :text#address2').val());
		params.address3          = $.trim($('#addOfficeShell :text#address3').val());
		params.city              = $.trim($('#addOfficeShell :text#city').val());
		params.stateID           = $('#addOfficeShell select#state').val();
		params.zip               = $.trim($('#addOfficeShell :text#zip').val());
		params.country           = $.trim($('#addOfficeShell :text#country').val());
		params.contact1FirstName = $.trim($('#addOfficeShell :text#contact1FirstName').val());
		params.contact1LastName  = $.trim($('#addOfficeShell :text#contact1LastName').val());
		params.contact2FirstName = $.trim($('#addOfficeShell :text#contact2FirstName').val());
		params.contact2LastName  = $.trim($('#addOfficeShell :text#contact2LastName').val());
		params.contact3FirstName = $.trim($('#addOfficeShell :text#contact3FirstName').val());
		params.contact3LastName  = $.trim($('#addOfficeShell :text#contact3LastName').val());
		params.phone             = $.trim($('#addOfficeShell :text#phone').val());
		params.email             = $.trim($('#addOfficeShell :text#email').val());
		params.websiteURL        = $.trim($('#addOfficeShell :text#websiteURL').val());

		if(params.officeLocale == '' || params.companyName == '' || params.officeCategoryID == '')
		{
			alert(c.errors.addContact1);
			return;
		}

		$.get(AJAX_FILE,{callback:'addOffice',params:params},function(data)
		{
			if(data.success)
			{
				$('#officeTable','#editOfficeShell').html(data.data);
				c.resetAddOfficeFields();
			}else
			{
				alert(data.message);
			}
		},'json');
	});

	return c;
};

contact.resetAddOfficeFields            = function()
{
	var c = this,
		co = c.objects;
	co.addOfficeFields.val('');

	return c;
};












//end