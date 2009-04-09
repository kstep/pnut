<?php
/**
 * @package Models
 * @subpackage Poll
 * Variant of answer
 */
class Model_Variant extends Model_Db
{
    protected $_attributes = array(
        'question'    => 'question_id',
        'title'       => 'title',
        'description' => 'description',
        'order'       => 'sortorder',
        'flags'       => 'flags',
    );

    protected $_fields = array(
        'id'          => Model::TYPE_INTEGER,
        'question_id' => Model::TYPE_INTEGER,
        'title'       => Model::TYPE_STRING,
        'description' => Model::TYPE_STRING,
        'sortorder'   => Model::TYPE_INTEGER,
        'flags'       => Model::TYPE_SET,
    );

    protected $_table = "variants";

    private $_question;

    public function getQuestion()
    {
        if (!$this->_question) $this->_question = new Model_Question($this->_db, $this->question);
        return $this->_question;
    }
}
?>
