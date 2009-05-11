var table_cols = $('#articles table.item-list thead tr').get(0).cells.length;
var sort_headers = Object;
sort_headers[table_cols - 1] = { sorter: false };

$('#articles table.item-list')
	.tablesorter({ cssAsc: 'sort-asc', cssDesc: 'sort-desc', cssHeader: 'sort-me', headers: sort_headers })
	.find('tbody')
	.sortable({ items: 'tr', cancel: 'tr#anew', handle: 'a' })
	.selectable({ filter: 'tr', cancel: 'tr#anew, a' });

function rename_article(result)
{
	var $item = $('tr#a'+result.id);
	if (result.state != 'failed')
	{
		$item.find("td.title > a:first-child").text(result.title).end().find("td.name").text(result.name);
	}
	else
	{
		error_form(result, $item);
	}
}

function remove_article(result)
{
	remove_element($("table.item-list tr#a"+result.id));
}

enable_context_menu('table.item-list tbody tr td.title a', '#articles-menu', function(action, item, pos){

var $href = $(item);
var $item = $(item).parent().parent();
var itemid = parseInt($item.attr("id").substring(1));
if (isNaN(itemid)) itemid = 0;

switch (action)
{
case 'rename':
	var pos = $item.offset();
	pos.top += $href.height();
	$("#rename-topic")
		.attr("action", sitePrefix+"/article/rename/"+itemid)
		.data("ajaxaction", rename_article)
		.css(pos)
		.find("#ttitle").val($href.text()).end()
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

$('.item-list tbody tr td.controls a.remove').click(function(){ask_question("Точно удалить эту статью?", $(this), $(this).attr('href'), remove_article); return false;});
$('#topic-controls a.remove').click(function(){ask_question("Точно удалить этот раздел?", $(this), $(this).attr('href'), function(){window.location = sitePrefix+"/topic/"}); return false;});
