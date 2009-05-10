<?php
/**
 * @package Models
 * @subpackage Content
 * List of articles
 */
class Model_List_Article extends Model_List_Paged// implements Model_HasVisible
{
    protected $_table = 'articles';
    protected $_model_class_name = 'Model_Article';
	protected $_views = array('visible' => 'visible_articles', 'nonremoved' => 'nonremoved_articles');
}
?>
