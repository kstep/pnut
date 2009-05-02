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
	var ok_handler = $form.data("ajaxaction");
	$form.hide().find(":input[name]").each(function(){ data[this.name] = this.value; });
	data.ajax = true;
	$.getJSON($form.attr("action"), data, function (result) {
		if (result.state != "failed" && ok_handler)
			ok_handler(result);
		else
			error_form(result, $form);
	});
	return false;
}

function error_form(result, $obj)
{
	if (!result.error) return;
	var $form = $("#error-form");
	var pos = $obj.offset();
	pos.top += $obj.height();

	var errmsg = result.error;
	if (result.errors)
	{
		result.error += "<ul>";
		for (errmsg in result.errors)
		{
			result.error += "<li>"+result.errors[errmsg]+"</li>";
		}
		result.error += "</ul>";
	}

	$form.css(pos).find("p").html(errmsg).end().fadeIn("fast", function(){setTimeout("$('#error-form').fadeOut('slow')", 5000)});
}

