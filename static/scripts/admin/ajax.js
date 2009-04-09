function beginRemoveObject(e)
{
    confirmAction(e.target, "Удалить?", removeObject);
    return false;
}

function removeObject(e)
{
    $.getJSON(e.target.href, { ajax: true }, function (result) {
        if (result.state == "removed")
        {
            $("li#"+result.objname.substring(0, 1)+result.id).hide("normal");
        }
    });
    return false;
}

function confirmAction(obj, msg, yescb, nocb)
{
    var span = $("<span>").attr("class", "confirm");
    $("<var>").text(msg).appendTo(span);

    $("<a>").attr({ "href": obj.href, "class": "yes", "title": "Да" }).click(function (e) {
        if (yescb) yescb(e);
        $(e.target.parentNode).prev().show().end().remove();
        return false;
    }).text(" ").appendTo(span);

    $("<a>").attr({ "href": "#no", "class": "no", "title": "Нет" }).click(function (e) {
        if (nocb) nocb(e);
        $(e.target.parentNode).prev().show().end().remove();
        return false;
    }).text(" ").appendTo(span);

    $(obj.parentNode).hide();
    span.insertAfter(obj.parentNode).css("display", "inline");
}

$(function() {
    $("h1 span.fold").click(function(e) { $(this.parentNode).toggleClass('folded').next("ul").toggle("normal"); });
    $("form ol li fieldset legend").click(function(e) { $(this).next().toggle("normal") });
	$("form ol li fieldset > ol").add("form ol li fieldset > table").hide();

	var $errors = $("form .error input, form .error textarea, form .error select");
	if ($errors.length > 0)
	{
		$errors.parents("ol, table").show().end().get(0).focus();
	}
});
