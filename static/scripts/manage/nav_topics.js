function rename_topic(result)
{
	var $item = $("#topics li#t"+result.id).find("a:first-child");
	$item.attr("title", result.name).text(result.title);
}

function remove_topic(result)
{
	remove_element($('#topics li#t'+result.id));
}

function reorder_topic(e, ui)
{
	var $item = $(ui.item);
	var $targ = $(ui.item).prev();
	var place = "after";
	if (!$targ.length)
	{
		$targ = $(ui.item).next();
		place = "before";
	}

	var targid = $targ.attr('id').substring(1);
	var itemid = $item.attr('id').substring(1);
	$(e.target).sortable('cancel');


	$.getJSON(sitePrefix+"/topic/reorder/"+itemid+"/"+place+"/"+targid, { ajax: true }, function (result) {
		var $item = $("#t"+result.id);
		var $targ = $("#t"+result.target_id);

		if (result.state != "failed")
		{
			switch (result.place)
			{
			case 'before':
				$item.insertBefore($targ);
				break;
			case 'after':
				$item.insertAfter($targ);
				break;
			}
		}
		else
		{
			error_form(result, $item);
		}
	});
}

function handle_topic_context_menu(action, item, pos)
{
	var $href = $(item);
	var $item = $(item).parent();
	var itemid = parseInt($item.attr("id").substring(1));
	if (isNaN(itemid)) itemid = 0;

	switch (action)
	{
	case "edit":
		window.location = sitePrefix+"/topic/edit/"+itemid;
		break;
	case "create":
		window.location = sitePrefix+"/topic/new/"+itemid;
		break;
	case "cut":
		$('#topics ul li.cut').removeClass("cut");
		$item.addClass("cut");
		$(".context-menu li.paste").removeClass("disabled");
		break;
	case "paste":
		$('#topics ul li.cut').each(function () {
			$.getJSON(sitePrefix+"/topic/move/"+this.id.substring(1)+"/"+itemid, { ajax: true }, function (result) {
				var $cutitem = $("#t"+result.id);
				if (result.state == 'moved')
				{
					var $item = $("#t"+((result.parent_id == 0)? "root": result.parent_id));
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
		$('#articles .item-list tr.cut').each(function () {
			$.getJSON(sitePrefix+"/article/move/"+this.id.substring(1)+"/"+itemid, { ajax: true }, function (result) {
				var $cutitem = $("#a"+result.id);
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
		$("#rename-topic")
			.attr("action", sitePrefix+"/topic/rename/"+itemid)
			.data("ajaxaction", rename_topic)
			.css(pos)
			.find("#ttitle").val($href.text()).end()
			.find("#tname").val($href.attr("title")).end()
			.show()
			.find("#ttitle").focus();
		break;
	case "remove":
		ask_question("Точно удалить этот раздел?", $href, sitePrefix+"/topic/remove/"+itemid, remove_topic);
		break;
	case "write":
		window.location = sitePrefix+"/article/new/"+itemid;
		break;
	}
}

$("#topics ul li#troot ul").sortable({ items: 'li', handle: 'a', update: reorder_topic });
enable_context_menu("#topics ul li#troot ul li a", '#topics-menu', handle_topic_context_menu);
enable_context_menu("#topics ul li#troot > a", '#root-topic-menu', handle_topic_context_menu);
