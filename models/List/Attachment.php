<?php
/**
 * @package Models
 * @subpackage Content
 * List of attachments
 */
class Model_List_Attachment extends Model_List_Paged
{
    protected $_table = 'attachments';
    protected $_model_class_name = 'Model_Attachment';
}
?>
