function moveIntoGroup(e, ui)
{
    var id = ui.draggable.attr('id');
    var targid = this.parentNode.id.substring(1);
    var objname = id.substring(0, 1) == "u"? "user": "group";
    id = id.substring(1);

    $.getJSON(sitePrefix+"/"+objname+"/move/"+id, { ajax: true, to: targid }, function(result) {

    if (result.state == "moved")
    {
        if (result.objname == "group")
        {
            var orig = $("li#g"+result.id);
            if (result.parent_id == 0)
            {
                orig.hide("fast", function() { $(this).insertBefore("#groups ul li#gnew").show("fast") });
            } else {
                var ul;
                var targ = $("li#g"+result.parent_id);
                ul = targ.children("ul");
                if (!ul.length) ul = $("<ul>").appendTo(targ);
                orig.hide("fast", function() { $(this).appendTo(ul).show("fast") });
            }
        }
		else
		{
			$("li#u"+result.id).hide("normal");
		}
    }
    });
}

$(function () {
    $("#groups span.actions a.remove").click(beginRemoveNavObj);
    $("#groups span.actions a.edit").click(beginRenameNavObj);
    $("#groups li#gnew a.add").click(beginCreateNavObj);

	$("#groups ul li").not("li#gnew").draggable({
		helper: "clone",
		scope: "groups"
	});
	$("#groups ul li span.name").droppable({
		hoverClass: 'drop',
		drop: moveIntoGroup,
		tolerance: 'pointer',
		scope: "groups"
	});
    $("#users ul.itemsList li").not("li#unew").draggable({ helper: 'clone', scope: 'groups' });
});
