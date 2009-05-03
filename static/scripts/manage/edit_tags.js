$('#taglist input#tags').remove();
$('#taglist ul.tag-list').removeClass('hidden');

function remove_tag(e)
{
	remove_element($(e.target.parentNode));
	return false;
}

function add_tag(e, leave)
{
	var $me = $(e.target.parentNode);
	var tagname = $(e.target).val();
	if (!leave) $(e.target).prev().show().end().remove();

	if (tagname == '') return;

	var $newtag = $('<a href="#">').attr('class', 'remove tag').text(tagname).click(remove_tag);
	var $input = $('<input type="hidden" />').attr('name', 'tag[]').val(tagname);
	var $li = $('<li>').append($newtag).append($input);
	$me.before($li);
}

function new_tag(e)
{
	var $li = $(e.target.parentNode);
	var $tag = $('<input type="text" />').attr({ size: 8 });
	$(e.target).hide();
	$li.append($tag);
	$tag.blur(add_tag).keydown(tag_key_handler).focus();
	return false;
}

function tag_key_handler(e)
{
	var $me = $(e.target);
	switch (e.keyCode)
	{
	case 13:
		add_tag(e, true);
	case 27:
		$me.val('');
		return false;
	default:
		return true;
	}
}

$('.tag-list a.new').click(new_tag);
$('.tag-list a.remove').click(remove_tag);

