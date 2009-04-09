function removeArticle(e)
{
    $.getJSON(e.target.href, { ajax: true }, function (result) {
        if (result.state == "removed")
        {
            $("li#a"+result.id).hide("normal");
        }
    });
    e.stopPropagation();
    return false;
}

function reorderArticle(e, ui)
{
    var id = ui.draggable.attr('id').substring(1);
    var targid = this.parentNode.id.substring(1);

	$.getJSON(sitePrefix + "/admin/article/reorder/" + id, { ajax: true, to: targid }, function (result) {
		if (result.state == 'reordered')
		{
			var orig = $("li#a"+result.id);
			var targ = $("li#a"+result.target_id);
			if (orig && targ)
			{
				orig.insertAfter(targ);
			}
		}
	});
}

$(function () {
    $("#articles span.actions a.remove").click(function (e) {
        confirmAction(this, "Удалить?", removeArticle);
        return false;
    });
    $("#articles ul.itemsList li").not("li#anew").draggable({ helper: 'clone', scope: 'topics' });
	$("#articles ul.itemsList li a.gallery, #articles ul.itemsList li a.article").droppable({
		hoverClass: "drop",
		drop: reorderArticle,
		tolerance: 'pointer',
		scope: "topics"
	});
});
