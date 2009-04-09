<?php
/**
 * @package Models
 * @subpackage Poll
 * List of questions
 */
class Model_List_Question extends Model_List_Db
{
    protected $_table = "questions";
    protected $_model_class_name = "Model_Question";
}
?>
