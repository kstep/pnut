<?php
/**
 * @package Models
 * @subpackage Poll
 * Answer to poll's question
 */
class Model_Answer extends Model_Db
{
    protected $_attributes = array(
        'variant'  => 'variant_id',
        'question' => 'question_id',
        'answered' => 'answered_at',
        'user'     => 'user_id',
        'custom'   => 'custom_text',
    );

    protected $_fields = array(
        'id'          => Model::TYPE_INTEGER,
        'variant_id'  => Model::TYPE_INTEGER,
        'question_id' => Model::TYPE_INTEGER,
        'answered_at' => Model::TYPE_TIMESTAMP,
        'user_id'     => Model::TYPE_INTEGER,
        'custom_text' => Model::TYPE_STRING,
    );

    protected $_table = "answers";

    public function getVariant() { return new Model_Variant($this->_db, $this->variant); }
    public function getQuestion() { return new Model_Question($this->_db, $this->question); }
    public function getUser() { return new Model_User($this->_db, $this->user); }
}
?>
