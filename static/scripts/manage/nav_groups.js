function rename_group(result)
{
	var $item = $("#groups li#g"+result.id).find("a:first-child");
	$item.attr("title", result.name).text(result.title);
}

function remove_group(result)
{
	remove_element($('#groups li#g'+result.id));
}

function handle_group_context_menu(action, item, pos)
{
	var $href = $(item);
	var $item = $(item).parent();
	var itemid = parseInt($item.attr("id").substring(1));
	if (isNaN(itemid)) itemid = 0;

	switch (action)
	{
	case "edit":
		window.location = sitePrefix+"/group/edit/"+itemid;
		break;
	case "create":
		window.location = sitePrefix+"/group/new/"+itemid;
		break;
	case "cut":
		$('#groups ul li.cut').removeClass("cut");
		$item.addClass("cut");
		$(".context-menu li.paste").removeClass("disabled");
		break;
	case "paste":
		$('#groups ul li.cut').each(function () {
			$.getJSON(sitePrefix+"/group/move/"+this.id.substring(1)+"/"+itemid, { ajax: true }, function (result) {
				var $cutitem = $("#g"+result.id);
				if (result.state == 'moved')
				{
					var $item = $("#g"+((result.parent_id == 0)? "root": result.parent_id));
					var $ul = $item.find("ul:first");
					if ($ul.length == 0)
						$ul = $("<ul>").appendTo($item);
					$ul.append($cutitem);
				}
				else
				{
					error_form(result, $cutitem.find("a:first-child"));
				}
			});
		}).removeClass('cut');
		$('#users .item-list tr.cut').each(function () {
			$.getJSON(sitePrefix+"/user/move/"+this.id.substring(1)+"/"+itemid, { ajax: true }, function (result) {
				var $cutitem = $("#u"+result.id);
				if (result.state == 'moved')
				{
					remove_element($cutitem);
				}
				else
				{
					error_form(result, $cutitem);
				}
			});
		}).removeClass('cut');
		$(".context-menu li.paste").addClass("disabled");
		break;
	case "rename":
		var pos = $item.offset();
		pos.top += $href.height();
		$("#rename-group")
			.attr("action", sitePrefix+"/group/rename/"+itemid)
			.data("ajaxaction", rename_group)
			.css(pos)
			.find("#gtitle").val($href.text()).end()
			.find("#gname").val($href.attr("title")).end()
			.show()
			.find("#gtitle").focus();
		break;
	case "remove":
		ask_question("Точно удалить эту группу?", $href, sitePrefix+"/group/remove/"+itemid, remove_group);
		break;
	case "write":
		window.location = sitePrefix+"/group/new/"+itemid;
		break;
	}
}

enable_context_menu("#groups ul li#groot ul li a", '#groups-menu', handle_group_context_menu);
enable_context_menu("#groups ul li#groot > a", '#root-group-menu', handle_group_context_menu);
