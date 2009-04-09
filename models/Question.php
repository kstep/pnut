<?php
/**
 * @package Models
 * @subpackage Poll
 * Question of a poll
 */
class Model_Question extends Model_Db
{
    protected $_attributes = array(
        'poll'        => 'poll_id',
        'title'       => 'title',
        'description' => 'description',
        'minvariants' => 'min_variants',
        'maxvariants' => 'max_variants',
        'order'       => 'sortorder',
    );

    protected $_fields = array(
        'id'           => Model::TYPE_INTEGER,
        'poll_id'      => Model::TYPE_INTEGER,
        'title'        => Model::TYPE_STRING,
        'description'  => Model::TYPE_STRING,
        'min_variants' => Model::TYPE_INTEGER,
        'max_variants' => Model::TYPE_INTEGER,
        'sortorder'    => Model::TYPE_INTEGER,
    );

    protected $_table = "questions";

    public function getPoll()
    {
        return new Model_Poll($this->_db, $this->poll);
    }
}
?>
