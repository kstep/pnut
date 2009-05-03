function rename_user(result)
{
	var $item = $('#a'+result.id);
	if (result.state != 'failed')
	{
		$item.find("td.username > a:first-child").text(result.username).end().find("td.login").text(result.login);
	}
	else
	{
		error_form(result, $item);
	}
}

function remove_user(result)
{
	remove_element($("table.item-list tr#u"+result.id));
}

$('#articles table.item-list tbody')
	.selectable({ filter: 'tr', cancel: 'tr#unew' });

enable_context_menu('table.item-list tbody tr td:nth-child(2) a', '#users-menu', function(action, item, pos){

var $href = $(item);
var $item = $(item).parent().parent();
var itemid = parseInt($item.attr("id").substring(1));
if (isNaN(itemid)) itemid = 0;

switch (action)
{
case 'rename':
	var pos = $item.offset();
	pos.top += $href.height();
	$("#rename-user")
		.attr("action", sitePrefix+"/group/rename/"+itemid)
		.data("ajaxaction", rename_article)
		.css(pos)
		.find("#uusername").val($href.text()).end()
		.find("#tname").val($item.find("td.name").text()).end()
		.show()
		.find("#ttitle").focus();
break;
case 'cut':
	$('table.item-list tbody tr.cut').removeClass('cut');
	$('table.item-list tbody tr.ui-selected').addClass('cut');
	$('.context-menu li.paste').removeClass('disabled');
	$item.addClass('cut');
break;
case 'remove':
	ask_question("Точно удалить эту статью?", $href, sitePrefix+"/article/remove/"+itemid, remove_article);
break;
}
});

