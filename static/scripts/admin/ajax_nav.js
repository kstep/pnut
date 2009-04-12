function restoreNavObj(e)
{
    var obj = e.target.parentNode.parentNode;
    $(obj).find("span.edit").hide().end()
        .find("span.name").show();
}

function beginRemoveNavObj(e)
{
    confirmAction(e.target, "Удалить?", removeObject, restoreNavObj);
    return false;
}

function beginRenameNavObj(e) {
    var id = e.target.parentNode.parentNode.id;
    $("li#"+id+" > span.name").hide();

    confirmAction(e.target, "", renameNavObj, restoreNavObj);
    $("li#"+id+" > span.edit").show();

    return false;
}

function beginCreateNavObj(e) {
	$(e.target.parentNode.parentNode).find("span.name").hide().end().find("span.edit").show();
    confirmAction(e.target, "", createNavObj, restoreNavObj);

    return false;
}

function createNavObj(e)
{
    restoreNavObj(e);
	var li = e.target.parentNode.parentNode;
    var title = $(li).find("span.edit input").val();
    var name  = $(li).find("span.edit small input").val();
    $.getJSON(e.target.href, { ajax: true, title: title, name: name }, function (result) {
        if (result.state == "created")
        {
			var objname = result.objname.substring(0, 1);
            var li = $("li#"+objname+"new");
            var newid = objname + result.id;
            var newli = li.clone()
                .hide()
                .attr("id", newid)
                .find("span.name").html("<a href=\""+sitePrefix+"/"+result.objname+"/"+result.id+"\">"+result.title+"</a> <small>("+result.name+")</small>").show().end()
                .find("span.edit").hide().end()
                .find("span.edit input").val(result.title).end()
                .find("span.edit small input").val(result.name).end()
                .find("span.actions a.remove").attr("href", sitePrefix+"/"+result.objname+"/remove/"+result.id).css("display", "inline").click(beginRemoveNavObj).end()
                .find("span.actions a.edit").attr("href", sitePrefix+"/"+result.objname+"/edit/"+result.id).css("display", "inline").click(beginRenameNavObj).end();

			if (result.objname == 'topic')
			{
				newli.draggable({ helper: 'clone', scope: 'topics' })
					.find("span.name").droppable({ scope: 'topics', hoverClass: 'drop', drop: moveIntoTopic, tolerance: 'pointer' }).end();
			}
			else if (result.objname == 'group')
			{
				newli.find("span.name").droppable({ scope: 'groups', hoverClass: 'drop', drop: moveIntoGroup, tolerance: 'pointer' }).end();
			}
			newli.insertBefore(li).show("normal");
        }
    })
    return false;
}

function renameNavObj(e)
{
    restoreNavObj(e);
    var id = e.target.parentNode.parentNode.id;
    var title = $("li#"+id+" > span.edit input").val();
    var name  = $("li#"+id+" > span.edit small input").val();
    $.getJSON(e.target.href, { ajax: true, title: title, name: name }, function (result) {
		var objname = result.objname.substring(0, 1);
        if (result.state == "renamed")
        {
            $("li#"+objname+result.id+" > span.name a").text(result.title);
            $("li#"+objname+result.id+" > span.name small").text("("+result.name+")");
            $("li#"+objname+result.id+" > span.edit input").val(result.title);
            $("li#"+objname+result.id+" > span.edit small input").val(result.name);
        }
    });
    return false;
}

