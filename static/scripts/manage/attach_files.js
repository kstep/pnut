function remove_file_input()
{
	remove_element($(this.parentNode));
	return false;
}
function add_file_input($place)
{
	var $li = $('<li>').attr('class', 'controls');
	var $input = $('<input type="file" />').attr('name', 'attach[]');
	var $rmlink = $('<a href="#">').attr({ title: 'Убрать', class: 'remove article' }).html('&nbsp;').click(remove_file_input);
	$place.before($li.append($input).append($rmlink).fadeIn('normal'));
	return false;
}

$('#attachfiles ol a.new').click(function(){add_file_input($(this.parentNode)); return false;});
$('#attachfiles ol a.add5').click(function(){for (var i = 1; i < 5; i++) add_file_input($(this.parentNode)); return false;});
$('#attachfiles ol a.remove').click(remove_file_input);

