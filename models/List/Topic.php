<?php
/**
 * @package Models
 * @subpackage Content
 * List of topics
 */
class Model_List_Topic extends Model_List_TraversedTree// implements Model_HasVisible
{
    protected $_table = 'topics';
    protected $_model_class_name = 'Model_Topic';
	protected $_views = array('visible' => 'visible_topics', 'nonremoved' => 'nonremoved_topics');
}
?>
