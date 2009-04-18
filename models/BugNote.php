<?php

class Model_BugNote extends Model_Db
{

    protected $_table = 'bugnotes';
    protected $_pk = 'id';

    protected $_attributes = array(
        'bug'             => 'bug_id',
        'title'           => 'title',
        'content'         => 'content',
        'created'         => 'created_at',
        'author'          => 'author_id',
        'new_state'       => 'new_state',
        'new_assignee'    => 'new_assignee_id',
        'new_resolution'  => 'new_resolution',
        'new_progress'    => 'new_progress',
        'vote'            => 'vote',
        'new_due_version' => 'new_due_version',
        'new_due_date'    => 'new_due_date',
        'new_severity'    => 'new_severity',
        'new_priority'    => 'new_priority',
        'new_duplicate'   => 'new_duplicate_id',
        'time_spent'      => 'time_spent',
    );

    protected $_fields = array(
        'bug_id'           => Model::TYPE_INTEGER,
        'title'            => Model::TYPE_STRING,
        'content'          => Model::TYPE_STRING,
        'created_at'       => Model::TYPE_TIMESTAMP,
        'author_id'        => Model::TYPE_INTEGER,
        'new_state'        => Model::TYPE_ENUM,
        'new_assignee_id'  => Model::TYPE_INTEGER,
        'new_resolution'   => Model::TYPE_ENUM,
        'new_progress'     => Model::TYPE_INTEGER,
        'vote'             => Model::TYPE_FLOAT,
        'new_due_version'  => Model::TYPE_STRING,
        'new_due_date'     => Model::TYPE_TIMESTAMP,
        'new_severity'     => Model::TYPE_ENUM,
        'new_priority'     => Model::TYPE_ENUM,
        'new_duplicate_id' => Model::TYPE_INTEGER,
        'time_spent'       => Model::TYPE_INTEGER,
    );

    public function getBug()
    {
        return $this->bug? new Model_Bug($this->_db, $this->bug): null;
    }

    public function getAuthor()
    {
        return $this->author? new Model_User($this->_db, $this->author): null;
    }

    public function getNewAssignee()
    {
        return $this->new_assignee? new Model_User($this->_db, $this->new_assignee): null;
    }

    public function getNewDuplicate()
    {
        return $this->new_duplicate? new Model_Bug($this->_db, $this->new_duplicate): null;
    }

}

?>
