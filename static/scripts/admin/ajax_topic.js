// @todo add errors notification

function moveIntoTopic(e, ui)
{
    var id = ui.draggable.attr('id');
    var targid = this.parentNode.id.substring(1);
    var objname = id.substring(0, 1) == "a"? "article": "topic";
	var action = e.shiftKey? "reorder": "move";
    id = id.substring(1);

    if (objname == "topic" && id == targid)
        return;

    $.getJSON(sitePrefix+"/"+objname+"/"+action+"/"+id, { ajax: true, to: targid }, function(result) {

    if (result.state == "moved")
    {
        if (result.objname == "topic")
        {
            var orig = $("li#t"+result.id);
            if (result.parent_id == 0)
            {
                orig.hide("fast", function() { $(this).insertBefore("#topics ul li#tnew").show("fast") });
            } else {
                var ul;
                var targ = $("li#t"+result.parent_id);
                ul = targ.children("ul");
                if (!ul.length) ul = $("<ul>").appendTo(targ);
                orig.hide("fast", function() { $(this).appendTo(ul).show("fast") });
            }
        }
        else if (result.objname == "article")
        {
            $("li#a"+result.id).hide("normal");
        }
    }
	else if (result.state == "reordered")
	{
		var obj = result.objname.substring(0,1);
		var orig = $("li#"+obj+result.id);
		var targ = $("li#"+obj+result.target_id);
		if (orig && targ)
		{
			if (result.parent_id == result.target_id)
				orig.prependTo(targ.find("ul").get(0));
			else
			{
				if (result.after) orig.insertAfter(targ);
				else orig.insertBefore(targ);
			}
		}
	}

    });
}

function beginMoveTopic(e)
{
    $(this).removeClass("drop");
    $(e.dragProxy).remove();
    if (this.parentNode.id == "tnew" && e.dragTarget.id.substring(0,1) == "a")
    {
        return false;
    }
    else if (this.parentNode.id != e.dragTarget.id)
    {
        var targId = e.dragTarget.id;
        var targObj = targId.substring(0,1);

        $.getJSON(sitePrefix+"/" + (targObj == "a"? "article": "topic") + "/move/" + targId.substring(1), { to: this.parentNode.id.substring(1), ajax: true }, moveIntoTopic);
    }
}

$(function () {
    $("#topics span.actions a.remove").click(beginRemoveNavObj);
    $("#topics span.actions a.edit").click(beginRenameNavObj);
    $("#topics li#tnew a.add").click(beginCreateNavObj);

	$("#topics ul li").not("li#tnew").draggable({
		helper: "clone",
		scope: "topics"
	});
	$("#topics ul li span.name").droppable({
		hoverClass: 'drop',
		drop: moveIntoTopic,
		tolerance: 'pointer',
		scope: "topics"
	});
});
