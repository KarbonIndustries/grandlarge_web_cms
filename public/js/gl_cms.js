/* ========== */
/* = CONFIG = */
/* ========== */
var SCRIPT_DIR = '/../scripts/',
	AJAX_FILE = SCRIPT_DIR + 'ajax.php';

/* =========== */
/* = OBJECTS = */
/* =========== */
var directors = {},
	feeds     = {},
	notable   = {},
	about     = {},
	contact   = {},
	users     = {},
	files     = {};

/* ========= */
/* = USERS = */
/* ========= */
users.init                              = function()
{
	var u = this;
	u.addAddUserListener().get();
	u.d = $('#passwordDialog');
	u.e = u.d.find('#passwordDialogError');
	u.d.dialog(
	{
		autoOpen:false,
		title:"Change Password",
		width:350,
		modal:true,
		draggable:false,
		resizable:false,
		buttons:
		{
			"Cancel":function()
			{
				$(this).dialog("close");
			},
			"Update":function()
			{
				var params                 = {};
					params.password        = $(this).find('input#dialogPassword').val(),
					params.confirmPassword = $(this).find('input#dialogConfirmPassword').val(),
					params.userTypeId      = u.changePasswordInfo.userTypeId;
					params.userId          = u.changePasswordInfo.userId;

				if(params.password === params.confirmPassword)
				{
					$.get(AJAX_FILE,{callback:'updatePassword',params:params},function(data)
					{
						if(data.success)
						{
							u.d.dialog("close");
						}else
						{
							u.showError(data.msg);
						}
					},'json');
				}else
				{
					u.showError('Passwords do not match. Please try again.');
				}
			}
		},
		open:function(event,ui)
		{
			u.e.html('&nbsp;');
			u.hideError();
			$(this).find('input').val('');
		},
		close:function(event,ui)
		{
			u.e.html('&nbsp;');
			u.hideError();
			$(this).find('input').val('');
		}
	});
};

users.get                               = function()
{
	var u = this,
	ne    = u.errors,
	no    = u.objects;

	$.get(AJAX_FILE,{callback:'getUsers'},function(data)
	{
		if(data.success)
		{
			$('#userListShell').html(data.html);
			u.addEditUserListeners();
		}
	},'json');

	return u;
};

users.addAddUserListener                = function()
{
	var u = this;

	$('#addUserShell').find('#addUserFieldsShell').find('#addButtonShell').find('button#addUser').click(function()
	{
		var params                 = {},
			usernameField          = $('div#addUserShell').find('div#usernameShell').find('input#username'),
			passwordField          = $('div#addUserShell').find('div#passwordShell').find('input#password'),
			confirmPasswordField   = $('div#addUserShell').find('div#confirmPasswordShell').find('input#confirmPassword'),
			userTypeField          = $('div#addUserShell').find('div#userTypeShell').find('select#userType');
			params.username        = usernameField.val(),
			params.password        = passwordField.val(),
			params.confirmPassword = confirmPasswordField.val(),
			params.userTypeId      = parseInt(userTypeField.val(),10),
			params.userId          = 0;

		if(params.password === params.confirmPassword)
		{
			$.get(AJAX_FILE,{callback:'addUser',params:params},function(data)
			{
				if(data.success)
				{
					u.get();
					usernameField.val(''),
					passwordField.val(''),
					confirmPasswordField.val(''),
					userTypeField.val(1);
				}else
				{
					alert(data.msg);
				}
			},'json');
		}else
		{
			alert('Passwords do not match. Please try again.');
		}
	});

	return u;
}

users.addEditUserListeners              = function()
{
	var u         = this,
	userListTable = $('table#userListTable');

	// CHANGE USER TYPE
	userListTable.find('select[type=userType]').change(function()
	{
		var params                 = {};
			params.userTypeId      = parseInt($(this).val(),10),
			params.userId          = parseInt($(this).attr('userId'),10);
		
		$.get(AJAX_FILE,{callback:'updateUserType',params:params},function(data)
		{
			if(data.success)
			{
				u.get();
			}else
			{
				alert(data.msg);
			}
		},'json');
	});

	// CHANGE USER PASSWORD
	userListTable.find('button[type=changePassword]').click(function()
	{
		var userTypeId      = parseInt($(this).attr('userTypeId'),10),
			userId          = parseInt($(this).attr('userId'),10);
			u.changePasswordInfo = {userId:userId,userTypeId:userTypeId};

		u.d.dialog('open');
	});

	// REMOVE USER
	userListTable.find('button[type=removeUser]').click(function()
	{
		var params                 = {};
			params.userTypeId      = parseInt($(this).attr('userTypeId'),10),
			params.userId          = parseInt($(this).attr('userId'),10);

		if(confirm('Are you sure you want to remove this user?'))
		{
			$.get(AJAX_FILE,{callback:'removeUser',params:params},function(data)
			{
				if(data.success)
				{
					if(data.removedSelf)
					{
						window.location.reload();
					}else
					{
						u.get();
					}
				}else
				{
					alert(data.msg);
				}
			},'json');
		}
	});

	return u;
};

users.showError                         = function(errorMsg)
{
	var u = this;
	u.e.html(errorMsg);
	u.e.removeClass('invisible');
	return u;
};

users.hideError                         = function()
{
	var u = this;
	u.e.addClass('invisible')
	return u;
};

/* =========== */
/* = NOBABLE = */
/* =========== */
notable.init                            = function()
{
	var n               = this,
	ne                  = n.errors = {},
	no                  = n.objects = {};
	no.addNotableShell  = $('#addNotableShell');
	no.editNotableShell = $('#editNotableShell');
	n.get();
	

	return n;
};

notable.get                             = function()
{
	var n = this,
	ne    = n.errors,
	no    = n.objects;

	
	$.get(AJAX_FILE,{callback:'getNotables'},function(data)
	{
		no.editNotableShell.find('#notableList').html(data.data);
		no.updateBtns = no.editNotableShell.find('#notableList').find('td.updateCell').find('button');
		no.removeBtns = no.editNotableShell.find('#notableList').find('td.removeCell').find('button');
		n.addTableListeners();
	},'json');

	return n;
};

notable.addTableListeners               = function()
{
	var n = this,
	ne    = n.errors,
	no    = n.objects;

	no.updateBtns.click(function()
	{
		var i = $(this),
		title = i.parent().parent().find('td.titleCell').find('input[type=text]').val(),
		url   = i.parent().parent().find('td.urlCell').find('input[type=text]').val(),
		id    = parseInt(i.attr('rowId'),10);
		n.update(title,url,id);
	});

	no.removeBtns.click(function()
	{
		var i    = $(this),
		root     = i.parent().parent();
		title    = root.find('td.titleCell').find('input[type=text]').val(),
		id       = parseInt(i.attr('rowId'),10),
		fileName = root.find('td.imgCell').find('img').attr('src');
		if(confirm('Are you sure you want to remove ' + title + '?'))
		{
			n.remove(id,fileName);
		}
	});

	return n;
};

notable.update                          = function(title,url,id)
{
	var n        = this,
	ne           = n.errors,
	no           = n.objects,
	params       = {};
	params.title = title,
	params.url   = url,
	params.id    = id;

	$.get(AJAX_FILE,{callback:'updateNotable',params:params},function(data)
	{
		alert(data.msg);
	},'json');

	return n;
};

notable.remove                          = function(id,fileName)
{
	var n           = this,
	ne              = n.errors,
	no              = n.objects
	params          = {},
	params.id       = id,
	params.fileName = fileName;

	$.get(AJAX_FILE,{callback:'removeNotable',params:params},function(data)
	{
		if(data.success)
		{
			n.get();
		}else
		{
			alert(data.msg);
		}
	},'json');

	return n;
};

/* ========= */
/* = FEEDS = */
/* ========= */
feeds.init                              = function()
{
	var f            = this,
	fe               = f.errors  = {},
	fo               = f.objects = {};
	fo.addFeedShell  = $('#addFeedShell');
	fo.editFeedShell = $('#editFeedShell');
	fe.ERROR_1       = 'Please select a valid director.';
	fe.ERROR_2       = 'Please select a valid category.';
	fe.ERROR_3       = 'Please enter a valid feed address.';
	fe.ERROR_4       = 'Please enter a valid category position.';
	fe.ERROR_5       = 'The category position must be different than the original category position.';
	f.addNewFeedListener().get();

	return f;
};

feeds.get                               = function()
{
	var f = this,
	fe    = f.errors,
	fo    = f.objects;

	$.get(AJAX_FILE,{callback:'getFeeds'},function(data)
	{
		if(data.success)
		{
			fo.editFeedShell.find('#feedList').html(data.data);
			f.addTableListeners();
		}else
		{
			alert(data.message);
		}
	},'json');

	return f;
};

feeds.addNewFeedListener                = function()
{
	var f = this,
	fe    = f.errors,
	fo    = f.objects,
	directorId,
	categoryId,
	url;

	fo.addFeedShell.find('#addBtnShell').find('#addFeedBtn').click(function()
	{
		directorId = parseInt(fo.addFeedShell.find('select#directors').val(),10);
		categoryId = parseInt(fo.addFeedShell.find('select#mediaCategories').val(),10);
		url        = String(fo.addFeedShell.find('[type="text"]#feedUrl').val());
		if(!directorId)
		{
			alert(fe.ERROR_1);
			return;
		}
		if(!categoryId)
		{
			alert(fe.ERROR_2);
			return;
		}
		if(!$.trim(url))
		{
			alert(fe.ERROR_3);
			return;
		}

		f.addFeed(directorId,categoryId,url);
	});

	return f;
};

feeds.addTableListeners                 = function()
{
	var f         = this,
	fe            = f.errors,
	fo            = f.objects,
	feedId,
	catPos;
	fo.updateBtns = fo.editFeedShell.find('#feedList').find('button.updateFeedBtn');
	fo.removeBtns = fo.editFeedShell.find('#feedList').find('button.removeFeedBtn');

	fo.updateBtns.click(function()
	{
		newCatPos = parseInt($(this).parent().parent().find('input.feedCategoryPosition').val(),10);
		oldCatPos = parseInt($(this).attr('catPos'),10);
		feedId    = parseInt($(this).attr('feedId'),10);
		f.updateFeed(feedId,newCatPos,oldCatPos);
	});

	fo.removeBtns.click(function()
	{
		feedId = parseInt($(this).attr('feedId'),10);
		if(confirm('Are you sure you want to remove this feed?'))
		{
			f.removeFeed(feedId);
		}
	});

	return f;
};

feeds.addFeed                           = function(directorId,categoryId,url)
{
	var f             = this,
	params            = {};
	params.directorId = parseInt(directorId,10);
	params.categoryId = parseInt(categoryId,10);
	params.url        = String(url);

	$.get(AJAX_FILE,{callback:'addFeed',params:params},function(data)
	{
		if(data.success)
		{
			f.clearAddFeedInputs();
			f.get();
		}else
		{
			alert(data.message);
		}
	},'json');

	return f;
};

feeds.updateFeed                        = function(feedId,newCatPos,oldCatPos)
{
	var f         = this,
	fe            = f.errors,
	fo            = f.objects,
	params        = {};
	params.feedId = parseInt(feedId,10);
	params.catPos = parseInt(newCatPos,10);

	if(newCatPos === oldCatPos)
	{
		alert(fe.ERROR_5);
	}else if(isNaN(newCatPos) || newCatPos < 1)
	{
		alert(fe.ERROR_4);
	}else
	{
		$.get(AJAX_FILE,{callback:'updateFeed',params:params},function(data)
		{
			if(data.success)
			{
				f.get();
			}else
			{
				alert(data.message);
			}
		},'json');
	}

	return f;
};

feeds.removeFeed                        = function(id)
{
	var f = this,
	feedId = parseInt(id,10);

	$.get(AJAX_FILE,{callback:'removeFeed',params:feedId},function(data)
	{
		if(data.success)
		{
			f.get();
		}else
		{
			alert(data.message);
		}
	},'json');

	return f;
};

feeds.clearAddFeedInputs                = function()
{
	var f = this,
	fo    = f.objects;
	fo.addFeedShell.find('select#directors').val(0);
	fo.addFeedShell.find('select#mediaCategories').val(0);
	fo.addFeedShell.find('[type="text"]#feedUrl').val('');
};

/* ============= */
/* = DIRECTORS = */
/* ============= */
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

/* =========== */
/* = CONTACT = */
/* =========== */
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








// end