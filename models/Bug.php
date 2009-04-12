<?php
class Model_Bug extends Model_Db
{
	protected $_attributes = array(
		'id'          => 'id',
		'title'       => 'title',
		'description' => 'description',
		'severity'    => 'severity',
		'priority'    => 'priority',
		'state'       => 'state',
		'ostype'      => 'os_type',
		'type'        => 'type',
		'created'     => 'created_at',
		'modified'    => 'modified_at',
		'modificator' => 'modified_by',
		'creator'     => 'created_by',
		'duedate'     => 'due_to',
		'version'     => 'version',
		'dueversion'  => 'due_to_version',
		'assigned'    => 'assigned_to',
		'progress'    => 'progress',
		'category'    => 'category_id',
		'rating'      => 'rating',
		'duplicate'   => 'duplicate_of',
	);

	protected $_fields = array(
		'id'             => Model::TYPE_INTEGER,
		'title'          => Model::TYPE_STRING,
		'description'    => Model::TYPE_STRING,
		'severity'       => Model::TYPE_ENUM,
		'priority'       => Model::TYPE_ENUM,
		'state'          => Model::TYPE_ENUM,
		'os_type'        => Model::TYPE_SET,
		'type'           => Model::TYPE_ENUM,
		'created_at'     => Model::TYPE_TIMESTAMP,
		'modified_at'    => Model::TYPE_TIMESTAMP,
		'modified_by'    => Model::TYPE_INTEGER,
		'created_by'     => Model::TYPE_INTEGER,
		'due_to'         => Model::TYPE_TIMESTAMP,
		'version'        => Model::TYPE_STRING,
		'due_to_version' => Model::TYPE_STRING,
		'assigned_to'    => Model::TYPE_INTEGER,
		'progress'       => Model::TYPE_INTEGER,
		'category_id'    => Model::TYPE_INTEGER,
		'rating'         => Model::TYPE_FLOAT,
		'duplicate_of'   => Model::TYPE_INTEGER,
	);

	protected $_table = 'bugs';

	public function getDuplicate() { return $this->duplicate? new Model_Bug($this->_db, $this->duplicate): null; }
	public function getCreator() { return new Model_User($this->_db, $this->creator); }
	public function getModificator() { return new Model_User($this->_db, $this->modificator); }
	//public function getCategory() { return new Model_Category($this->_db, $this->category); }
}
?>
