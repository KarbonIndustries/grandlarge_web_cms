var directors = {}
directors.addListeners = function()
{
	$('.directorName').click(function()
	{
		alert($(this).parent().attr('directorId'));
	});
}