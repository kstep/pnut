function remove_element($elem)
{
	$elem.fadeOut('normal', function(){$(this).remove()});
}

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
	var pos = $form.offset();
	var ok_handler = $form.data("ajaxaction");
	$form.hide().find(":input[name]").each(function(){ data[this.name] = this.value; });
	data.ajax = true;
	$.getJSON($form.attr("action"), data, function (result) {
		if (result.state != "failed" && ok_handler)
			ok_handler(result);
		else
			error_form(result, pos);
		$form.hide();
	});
	return false;
}

function error_form(result, $obj)
{
	if (!result.error) return;
	var $form = $("#error-form");
	var pos;
	if ($obj.offset)
	{
		pos = $obj.offset();
		pos.top += $obj.height();
	}
	else
	{
		pos = $obj;
	}


	var errmsg = result.error;
	if (result.errors)
	{
		errmsg += "<ul>";
		for (errm in result.errors)
		{
			errmsg += "<li>"+result.errors[errm]+"</li>";
		}
		errmsg += "</ul>";
	}

	$form.css(pos).find("p").html(errmsg).end().fadeIn("fast", function(){setTimeout("$('#error-form').fadeOut('slow')", 5000)});
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
