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


	// FEEDS
feeds.init                              = function()
{
	var f = this;
	f.errors = {};
	//f.errors = {};
	

	return f;
};

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
	c.UPDATE_ID        = null;
	c.errors           = {};
	ce                 = c.errors;
	ce.removeCategory  = 'There was an error deleting the category.';
	ce.updateCategory  = 'You must enter a category name.';
	ce.addCategory     = 'You must enter a category name.';
	ce.addContact1     = 'You must enter an office category, an office name and a company name.';

	c.objects          = {};
	co                 = c.objects;
	co.addOfficeFields = $(':text#officeName','#addOfficeShell')
						 .add(':text#companyName','#addOfficeShell')
						 .add(':text#address1','#addOfficeShell')
						 .add(':text#address2','#addOfficeShell')
						 .add(':text#address3','#addOfficeShell')
						 .add(':text#city','#addOfficeShell')
						 .add(':text#zip','#addOfficeShell')
						 .add(':text#country','#addOfficeShell')
						 .add(':text#contact1FirstName','#addOfficeShell')
						 .add(':text#contact1LastName','#addOfficeShell')
						 .add(':text#contact2FirstName','#addOfficeShell')
						 .add(':text#contact2LastName','#addOfficeShell')
						 .add(':text#contact3FirstName','#addOfficeShell')
						 .add(':text#contact3LastName','#addOfficeShell')
						 .add(':text#phone','#addOfficeShell')
						 .add(':text#email','#addOfficeShell')
						 .add(':text#websiteURL','#addOfficeShell');

	co.editOfficeFields = $(':text#officeName','#editOfficeShell')
						 .add(':text#companyName','#editOfficeShell')
						 .add(':text#address1','#editOfficeShell')
						 .add(':text#address2','#editOfficeShell')
						 .add(':text#address3','#editOfficeShell')
						 .add(':text#city','#editOfficeShell')
						 .add(':text#zip','#editOfficeShell')
						 .add(':text#country','#editOfficeShell')
						 .add(':text#contact1FirstName','#editOfficeShell')
						 .add(':text#contact1LastName','#editOfficeShell')
						 .add(':text#contact2FirstName','#editOfficeShell')
						 .add(':text#contact2LastName','#editOfficeShell')
						 .add(':text#contact3FirstName','#editOfficeShell')
						 .add(':text#contact3LastName','#editOfficeShell')
						 .add(':text#phone','#editOfficeShell')
						 .add(':text#email','#editOfficeShell')
						 .add(':text#websiteURL','#editOfficeShell');

	c.addOfficeCategoryTableListeners()
	.addOfficeTableListeners()
	.addSubmitOfficeCategoryListener()
	.addSubmitOfficeListener();

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
				$('#officeCategoryId','#addOfficeShell').add('#officeCategoryId','#editOffice').html(data.data2);
				$('#officeTable','#editOfficeShell').html(data.data3);
				c.addOfficeCategoryTableListeners().addOfficeTableListeners().resetEditOfficeFields();
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
				$('#officeCategoryId','#addOfficeShell').add('#officeCategoryId','#editOffice').html(data.data2);
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

// =================
// = REMOVE OFFICE =
// =================
	$('.removeOfficeBtn','#editOfficeShell').click(function()
	{
		var companyName = $(this).parent().parent().find('td.companyName').text(),
			params = {};
		params.id  = $(this).attr('officeId');

		if(!confirm('Are you sure you want to remove ' + companyName + '?'))
		{
			return;
		}

		$.get(AJAX_FILE,{callback:'removeOffice',params:params},function(data)
		{
			if(data.success)
			{
				$('#officeTable','#editOfficeShell').html(data.data);
				c.addOfficeTableListeners();
			}else
			{
				alert(data.message);
			}
		},'json');
	});

// =================
// = SELECT OFFICE =
// =================
	$('tr','#editOfficeShell').click(function()
	{
		var params = {};
		params.id  = c.UPDATE_ID = $(this).attr('id');

		$.get(AJAX_FILE,{callback:'getOffice',params:params},function(data)
		{
			if(data.success)
			{
				$('select#officeCategoryId','#editOfficeShell').val(data.officeCategoryID);
				$(':text#officeName','#editOfficeShell').val(data.officeLocale);
				$(':text#companyName','#editOfficeShell').val(data.companyName);
				$(':text#address1','#editOfficeShell').val(data.address1);
				$(':text#address2','#editOfficeShell').val(data.address2);
				$(':text#address3','#editOfficeShell').val(data.address3);
				$(':text#city','#editOfficeShell').val(data.city);
				$('select#state','#editOfficeShell').val(data.stateID);
				$(':text#zip','#editOfficeShell').val(data.zip);
				$(':text#country','#editOfficeShell').val(data.country);
				$(':text#contact1FirstName','#editOfficeShell').val(data.contact1FirstName);
				$(':text#contact1LastName','#editOfficeShell').val(data.contact1LastName);
				$(':text#contact2FirstName','#editOfficeShell').val(data.contact2FirstName);
				$(':text#contact2LastName','#editOfficeShell').val(data.contact2LastName);
				$(':text#contact3FirstName','#editOfficeShell').val(data.contact3FirstName);
				$(':text#contact3LastName','#editOfficeShell').val(data.contact3LastName);
				$(':text#phone','#editOfficeShell').val(data.phone);
				$(':text#email','#editOfficeShell').val(data.email);
				$(':text#websiteURL','#editOfficeShell').val(data.websiteURL);
			}else
			{
				alert(data.message);
			}
		},'json');
	});

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
				$('#officeCategoryId','#addOfficeShell').add('#officeCategoryId','#editOffice').html(data.data2);
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
		params.officeCategoryID  = $('select#officeCategoryId',       '#addOfficeShell').val();
		params.officeLocale      = $.trim($(':text#officeName',       '#addOfficeShell').val());
		params.companyName       = $.trim($(':text#companyName',      '#addOfficeShell').val());
		params.address1          = $.trim($(':text#address1',         '#addOfficeShell').val());
		params.address2          = $.trim($(':text#address2',         '#addOfficeShell').val());
		params.address3          = $.trim($(':text#address3',         '#addOfficeShell').val());
		params.city              = $.trim($(':text#city',             '#addOfficeShell').val());
		params.stateID           = $('select#state',                  '#addOfficeShell').val();
		params.zip               = $.trim($(':text#zip',              '#addOfficeShell').val());
		params.country           = $.trim($(':text#country',          '#addOfficeShell').val());
		params.contact1FirstName = $.trim($(':text#contact1FirstName','#addOfficeShell').val());
		params.contact1LastName  = $.trim($(':text#contact1LastName', '#addOfficeShell').val());
		params.contact2FirstName = $.trim($(':text#contact2FirstName','#addOfficeShell').val());
		params.contact2LastName  = $.trim($(':text#contact2LastName', '#addOfficeShell').val());
		params.contact3FirstName = $.trim($(':text#contact3FirstName','#addOfficeShell').val());
		params.contact3LastName  = $.trim($(':text#contact3LastName', '#addOfficeShell').val());
		params.phone             = $.trim($(':text#phone',            '#addOfficeShell').val());
		params.email             = $.trim($(':text#email',            '#addOfficeShell').val());
		params.websiteURL        = $.trim($(':text#websiteURL',       '#addOfficeShell').val());

		if(params.officeLocale === '' || params.companyName === '' || params.officeCategoryID === '')
		{
			alert(c.errors.addContact1);
			return;
		}

		$.get(AJAX_FILE,{callback:'addOffice',params:params},function(data)
		{
			if(data.success)
			{
				$('#officeTable','#editOfficeShell').html(data.data);
				$('#officeCategoryId','#addOfficeShell').val(0);
				$('#state','#addOfficeShell').val(0);
				c.resetAddOfficeFields().addOfficeTableListeners();
			}else
			{
				alert(data.message);
			}
		},'json');
	});

	// EDIT OFFICE
	$(':button#updateOfficeBtn').click(function()
	{
		if(c.UPDATE_ID)
		{
			var params               = {};
			params.id                = c.UPDATE_ID;
			params.officeCategoryID  = $('select#officeCategoryId',       '#editOfficeShell').val();
			params.officeLocale      = $.trim($(':text#officeName',       '#editOfficeShell').val());
			params.companyName       = $.trim($(':text#companyName',      '#editOfficeShell').val());
			params.address1          = $.trim($(':text#address1',         '#editOfficeShell').val());
			params.address2          = $.trim($(':text#address2',         '#editOfficeShell').val());
			params.address3          = $.trim($(':text#address3',         '#editOfficeShell').val());
			params.city              = $.trim($(':text#city',             '#editOfficeShell').val());
			params.stateID           = $('select#state',                  '#editOfficeShell').val();
			params.zip               = $.trim($(':text#zip',              '#editOfficeShell').val());
			params.country           = $.trim($(':text#country',          '#editOfficeShell').val());
			params.contact1FirstName = $.trim($(':text#contact1FirstName','#editOfficeShell').val());
			params.contact1LastName  = $.trim($(':text#contact1LastName', '#editOfficeShell').val());
			params.contact2FirstName = $.trim($(':text#contact2FirstName','#editOfficeShell').val());
			params.contact2LastName  = $.trim($(':text#contact2LastName', '#editOfficeShell').val());
			params.contact3FirstName = $.trim($(':text#contact3FirstName','#editOfficeShell').val());
			params.contact3LastName  = $.trim($(':text#contact3LastName', '#editOfficeShell').val());
			params.phone             = $.trim($(':text#phone',            '#editOfficeShell').val());
			params.email             = $.trim($(':text#email',            '#editOfficeShell').val());
			params.websiteURL        = $.trim($(':text#websiteURL',       '#editOfficeShell').val());

			if(params.officeLocale === '' || params.companyName === '' || params.officeCategoryID === '')
			{
				alert(c.errors.addContact1);
				return;
			}

			$.get(AJAX_FILE,{callback:'updateOffice',params:params},function(data)
			{
				if(data.success)
				{
					$('#officeTable','#editOfficeShell').html(data.data);
					c.addOfficeTableListeners().resetEditOfficeFields();
				}else
				{
					alert(data.message);
				}
			},'json');
		}else
		{
			alert('Please retry selecting an office.');
		}
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

contact.resetEditOfficeFields           = function()
{
	var c = this,
		co = c.objects;
	co.editOfficeFields.val('');
	$('select#officeCategoryId','#editOfficeShell').val(0);
	$('select#state','#editOfficeShell').val(0);
	c.UPDATE_ID = null;

	return c;
};








//end