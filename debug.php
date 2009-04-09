<?php
//if ($_REQUEST['debug'] && !$_REQUEST['ajax'])
if (!$_REQUEST['ajax'])
{
?>
<style type="text/css">
#debug { position: absolute; top: 0; left: 0; z-index: 1000; background: white; font-size: 11px; }
#debug h1 { font-size: 2px; }
#debug .debug { display: none; }
#debug:hover .debug { display: block; }
#debug ul li.max { color: red; }
</style>
<div id="debug">
<h1>debug</h1>
<div class="debug">
<?
    $max_time_query = max(Storage_Db::$tlog_queries);
    echo("Page generated in ".($end_time-$start_time)." sec.<br />\n");
    echo("Select queries executed ".(Storage_Db::$num_queries)." in ".(Storage_Db::$time_queries)." sec.<br />\n");
    echo("<ul>");
    foreach (Storage_Db::$log_queries as $i => $query)
    {
        echo("<li".(Storage_Db::$tlog_queries[$i] == $max_time_query? " class=\"max\"": "").">".(Storage_Db::$tlog_queries[$i]).": $query</li>");
    }
	echo("</ul>");
	echo("Profiling statistics:");
	echo("<table>");
	echo("<tr><th>Function</th><th>times run</th><th>total time</th><th>max time</th><th>avg time</th><th>last time</th></tr>");
	$stat = Profile::getStat();
	foreach ($stat as $func => $stat)
	{
		printf("<tr><td>%s</td><td>%d</td><td>%0.10f</td><td>%0.10f</td><td>%0.10f</td><td>%0.10f</td></tr>", $func, $stat['ntimes'], $stat['total'], $stat['max'], $stat['avg'], $stat['last']);
	}
	echo("</table>");
?>
</div></div>
<?
}
?>
