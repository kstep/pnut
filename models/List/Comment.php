<?php
/**
 * @package Models
 * @subpackage Content
 * List of comments
 */
class Model_List_Comment extends Model_List_Paged
{
    protected $_table = 'comments';
    protected $_model_class_name = 'Model_Comment';
    protected $_order_by_fields = 'created_at,id';
	protected $_views = array('nonremoved' => 'nonremoved_comments');
}
?>
