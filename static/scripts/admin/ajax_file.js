
function addUploadFile(e)
{
    $(e.target.parentNode).prev("li").clone(true).insertBefore(e.target.parentNode).find("input[type='file']").val("");
    return false;
}

function removeUploadFile(e)
{
    var li = e.target.parentNode.parentNode;
    if ($(li.parentNode).find("input[type='file']").length > 1) $(li).remove();
    return false;
}

$(function () {
    $("#attachments li[id^='f'] span.actions a.remove, #comments li[id^='c'] span.actions a.remove").click(beginRemoveObject);
    $("#attachments li > a.add").click(addUploadFile);
    $("#attachments li a.add5").click(function (e) {
        for (var i = 0; i < 5; i++)
        {
            addUploadFile(e);
        }
        return false;
    });
    $("#attachments li input + span.actions a.remove").click(removeUploadFile);
	$("#attachments ol").sortable({ items: "li[id^='f']" });
});
