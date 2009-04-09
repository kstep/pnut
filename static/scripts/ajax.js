function lightbox(state)
{
	var $lightbox = $("#lightbox");
    return state? $lightbox.show(): $lightbox.hide();
}

function scaleDown($obj, imgw, imgh)
{
	var coeff = 1;
	var $body = $("body");
	var width  = $body.width();
	var height = $body.height();
	var visw = 0.8*width;
	var vish = 0.8*height;

	if (imgw > visw)
		coeff = visw / imgw;
	else if (imgh > vish)
		coeff = vish / imgh;

	var imgw = coeff*imgw;
	var imgh = coeff*imgh;
	var x = (width / 2) - (imgw / 2);
	var y = (height / 2) - (imgh / 2);

	$obj.css({ "top": y+"px", "left": x+"px", "width": imgw+"px", "height": imgh+"px" });
	return $obj;
}

function imageView(e)
{
	lightbox(true);
    var id = e.target.parentNode.id.substring(3);
	var closebutton = sitePrefix + "/static/images/close.gif";

    $.getJSON(sitePrefix+"/image/meta/"+id, { "ajax": true }, function (result) {
		if (result.image)
		{
			var $view = $("<div>")
				.attr({ "class": "imageView" })
				.css({ "background-image": 'url('+sitePrefix+"/view/"+result.id+')' });

			$('<a>')
				.attr({ "href": "#","class": "closeButton" })
				.click(function () { $(this.parentNode).remove(); lightbox(false); })
				.appendTo($view);

			scaleDown($view, result.image[0], result.image[1]).prependTo("body").show();
		}
		else
		{
			lightbox(false);
		}
    });

    return false;
}

function videoView(e)
{
	lightbox(true);
	var id = e.target.parentNode.id.substring(5);
	var flowplayerurl = sitePrefix + "/static/flash/flowplayer/";
	var closebutton = sitePrefix + "/static/images/close.gif";

	var $view = $("<div>")
		.attr({ "class": "videoView" })
		.flowplayer(flowplayerurl+"core.swf", {
			clip: { url: sitePrefix+"/view/"+id, autoPlay: true, autoBuffering: true },
			plugins: {
				closebutton: {
					url: flowplayerurl+"content.swf",
					top: 2,
					right: 2,
					width: 16,
					height: 16,
					backgroundImage: 'url('+closebutton+')',
					onClick: function () { $("div.videoView").remove(); lightbox(false); }
				},
				controls: {
					url: flowplayerurl+"controls.swf"
				}
			}
		});

		scaleDown($view, 640, 480).prependTo("body").show();
	return false;
}

$(document).ready(function () {
    $("ul.gallery li[id^='img'] a").click(imageView);
    $("ul.gallery li[id^='video'] a").click(videoView);
});
