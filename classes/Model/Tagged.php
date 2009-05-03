<?php
interface Model_Tagged
{
	function getTags();
	function addTags($tag);
	function dropTags($tag = null);
}
?>
