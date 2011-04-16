var directors = {}
directors.addListeners = function()
{
	$('.directorName').click(function()
	{
		var id = $(this).parent().attr('directorId');
		//alert($(this).parent().attr('directorId'));
		$.get('/../scripts/get.php',{'callback':'getDirector','params[]':[id,id]},function(data)
		{
			alert(data);
		});
	});
	$('.removeDirectorBtn').click(function()
	{
		alert('you clicked remove');
	});
}