var cut_topic_id;

function ask_question(question, $obj, href, ajaxaction)
{
	var $form = $('#question');
	var pos = $obj.offset();
	pos.top += $obj.height();
	$form.data("ajaxaction", ajaxaction).attr("action", href).css(pos).find("ol li:first-child").text(question).end().show();
}

function run_form(e)
{
	var data = new Object();
	var $form = $(e.target);
	var ok_handler = $form.data("ajaxaction");
	$form.hide().find(":input[name]").each(function(){ data[this.name] = this.value; });
	data.ajax = true;
	$.getJSON($form.attr("action"), data, function (result) {
		if (result.state != "failed" && ok_handler)
			ok_handler(result);
		else
			error_form(result.error, $form);
	});
	return false;
}

function error_form(errmsg, $obj)
{
	var $form = $("#error-form");
	var pos = $obj.offset();
	pos.top += $obj.height();
	$form.css(pos).find("p").html(errmsg).end().fadeIn("fast", function(){setTimeout("$('#error-form').fadeOut('slow')", 5000)});
}

function rename_topic(result)
{
	var $item = $("#topics li#t"+result.id).find("a:first-child");
	$item.attr("title", result.name).text(result.title);
}

function remove_topic(result)
{
	var $item = $("#topics li#t"+result.id);
	$item.fadeOut("normal", function(){$(this).remove()});
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
			error_form(result.error, $item);
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
		if (cut_topic_id) $("#t"+cut_topic_id).removeClass("cut");
		cut_topic_id = itemid;
		$item.addClass("cut");
		$(".context-menu li.paste").removeClass("disabled");
		break;
	case "paste":
		if (cut_topic_id)
		{
			$.getJSON(sitePrefix+"/topic/move/"+cut_topic_id+"/"+itemid, { ajax: true }, function (result) {
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
					if (result.errors)
					{
						result.error += "<ul>";
						for (errmsg in result.errors)
						{
							result.error += "<li>"+result.errors[errmsg]+"</li>";
						}
						result.error += "</ul>";
					}
					error_form(result.error, $cutitem.find("a:first-child"));
				}
			});

			$("#t"+cut_topic_id).removeClass("cut");
			cut_topic_id = null;
			$(".context-menu li.paste").addClass("disabled");
		}
		break;
	case "rename":
		var pos = $item.offset();
		pos.top += $href.height();
		$("#rename-topic")
			.attr("action", sitePrefix+"/topic/rename/"+itemid)
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

var context_menu;
function enable_context_menu(selector, menu, func)
{
	var actfunc = function(){
		var $me     = $(this);
		var $caller = $me.prev();
		var $menu   = $caller.data('menu');
		var invis   = !$me.data('menuvisible');

		if (context_menu)
		{
			context_menu.hide();
			context_menu.data('caller').next().data('menuvisible', false);
			context_menu = null;
		}

		if (invis)
		{
			var pos = $me.offset();
			pos.top += $me.height();
			$menu.data('caller', $caller).css(pos).show();
			context_menu = $menu;
			$me.data('menuvisible', true);
		}
		else
		{
			$me.data('menuvisible', false);
		}
		return false;
	};
	var menufunc = function(){
		var $me = $(this).parents("ol");
		var pos = $me.offset();
		var func = $me.data('callback');
		var $item = $me.data('caller');
		var act  = $(this).attr('href').substring(1);

		context_menu.hide();
		context_menu.data('caller').next().data('menuvisible', false);
		context_menu = null;

		func(act, $item.get(0), pos);
		return false;
	};

	var $activator = $("<a href=\"#\" class=\"menu-activator\">&nbsp;<"+"/a>");
	var $menu = $(menu).data('callback', func);
	$menu.find("li > a").each(function(){$(this).click(menufunc)}).end();

	$(selector).data("menu", $menu).after($activator);
	return $(selector).not("a.menu-activator").each(function(){$(this).next().click(actfunc)}).end();
}

$("#topics ul li#troot ul").sortable({ items: 'li', handle: 'a', update: reorder_topic });
enable_context_menu("#topics ul li#troot ul li a", '#topics-menu', handle_topic_context_menu);
enable_context_menu("#topics ul li#troot > a", '#root-topic-menu', handle_topic_context_menu);
$("#rename-topic").data("ajaxaction", rename_topic);
