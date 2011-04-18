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

// ERROR MESSAGES

// OBJECT METHODS
directors.init                    = function()
{
	var d                 = this;
	d.errors              = {};
	d.errors.addError     = 'You must specify a first name.';
	d.errors.getError     = 'There was an error retrieving info for the director you requested.';
	d.errors.updateError1 = 'You cannot update a director without changing their info.';
	d.errors.updateError2 = 'You must specify a first name.';
	d.resetDirector();
	d.addDirectorFields   = $('#addDirectorShell #firstName,#addDirectorShell #lastName,#addDirectorShell #bio,#addDirectorShell #description,#addDirectorShell #website');
	d.editDirectorFields  = $('#editDirectorShell #firstName,#editDirectorShell #lastName,#editDirectorShell #bio,#editDirectorShell #description,#editDirectorShell #website');
	d.addTableListeners().addSubmitListeners();
	return d;
}

directors.addTableListeners       = function()
{
	var d = this;

	// GET DIRECTOR
	$('.directorName').click(function()
	{
		var params = {};
		params.id = $(this).parent().attr('directorId');
		
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
				alert(d.errors.getError);
			}
		},'json');
	});
	
	// REMOVE DIRECTOR
	$('.removeDirectorBtn').click(function()
	{
		var params = {};
		params.id = $(this).parent().parent().attr('directorId');
		var directorName = $(this).parent().prev().text();
		
		// confirm delete
		if(!confirm('Are you sure you want to remove ' + directorName))
		{
			return;
		}

		$.get(AJAX_FILE,{callback:'removeDirector',params:params},function(data)
		{
			if(data.success)
			{
				$('#directorTable').html(data.data);
				d.addTableListeners();
				d.resetDirector();
				$('#editDirectorShell #firstName,#editDirectorShell #lastName,#editDirectorShell #bio,#editDirectorShell #description,#editDirectorShell #website').val('');
			}else
			{
				alert(data.message);
			}
		},'json');
	});

	return d;
}

directors.addSubmitListeners      = function()
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

		// check that first name contains value
		if(params.firstName == '')
		{
			alert(d.errors.addError);
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

		// check that first name contains value
		if(params.firstName == '')
		{
			alert(d.errors.updateError2);
			return;
		}

		// check that id is set and info has been changed
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
			alert(d.errors.updateError1);
		}
	});

	return d;
}

directors.resetDirector           = function()
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

directors.clearAddDirectorFields  = function()
{
	var d = this;
	d.addDirectorFields.val('');
	return d;
}

directors.clearEditDirectorFields = function()
{
	var d = this;
	d.editDirectorFields.val('');
	return d;
}


contact.init                      = function()
{
	
}
//end
