// CONFIG
var SCRIPT_DIR = '/../scripts/';
var AJAX_FILE = SCRIPT_DIR + 'ajax.php';

// VARIABLES

// OBJECTS
var directors = {};
var feeds     = {};
var notable   = {};
var about     = {};
var contact   = {};
var users     = {};
var files     = {};

// OBJECT METHODS
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
}

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
		var params       = {};
		params.id        = $(this).parent().parent().attr('directorId');
		var directorName = $(this).parent().prev().text();
		
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
}

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
}

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
}

directors.clearAddDirectorFields        = function()
{
	var d = this;
	d.addDirectorFields.val('');
	return d;
}

directors.clearEditDirectorFields       = function()
{
	var d = this;
	d.editDirectorFields.val('');
	return d;
}


contact.init                            = function()
{
	var c    = this;

	c.errors = {};
	c.errors.removeCategory = 'There was an error deleting the category.';
	c.errors.updateCategory = 'You must enter category name.';
	c.errors.addCategory    = 'You must enter category name.';
	c.addOfficeCategoryTableListeners().addOfficeTableListeners().addSubmitOfficeCategoryListener().addSubmitOfficeListener();

	return c;
}

contact.addOfficeCategoryTableListeners = function()
{
	var c = this;

	// REMOVE CATEGORY
	$("#officeCategoryTable :button.removeOfficeCategoryBtn").click(function()
	{
		var name = $(":input[name=name" + $(this).attr('id') + "]").val();
		var params = {};
		params.id  = $(this).attr('id');

		// confirm delete
		if(!confirm('Are you sure you want to remove this category?'))
		{
			return;
		}

		$.get(AJAX_FILE,{callback:'removeOfficeCategory',params:params},function(data)
		{
			if(data.success)
			{
				$('#officeCategoryTable').html(data.data);
				c.addOfficeCategoryTableListeners();
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
				c.addOfficeCategoryTableListeners();
			}else
			{
				alert(data.message);
			}
		},'json');
	});

	return c;
}

contact.addOfficeTableListeners         = function()
{
	var c = this;
	
	return c;
}

contact.addSubmitOfficeCategoryListener = function()
{
	var c = this;

	// ADD CATEGORY
	$(":button#addOfficeCategoryBtn").click(function()
	{
		var input   = $("#addBtnShell :text#categoryName");
		var params  = {};
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
				$('#officeCategoryTable').html(data.data);
				c.addOfficeCategoryTableListeners();
				input.val('');
			}else
			{
				alert(data.message);
			}
		},'json');
	});

	return c;
}

contact.addSubmitOfficeListener         = function()
{
	var c = this;
	
	return c;
}
//end
