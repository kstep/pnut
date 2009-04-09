<?php
/**
 * @package Models
 * @subpackage User
 * List of user groups
 */
class Model_List_Group extends Model_List_TraversedTree
{
    protected $_table = 'groups';
    protected $_model_class_name = 'Model_Group';
}
?>
