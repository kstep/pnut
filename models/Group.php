<?php
/**
 * @package Models
 * @subpackage User
 * User group model
 */
class Model_Group extends Model_TraversedTree
{

    protected $_attributes = array(
        //'id'     => 'id',
        'title'  => 'title',
        'name'   => 'name',
        'role'   => 'role',
        'parent' => 'parent_id',
        'lside'  => 'lside',
        'rside'  => 'rside',
    );

    protected $_fields = array(
        'id'        => Model::TYPE_INTEGER,
        'name'      => Model::TYPE_STRING,
        'title'     => Model::TYPE_STRING,
        'role'      => Model::TYPE_ENUM,
        'parent_id' => Model::TYPE_INTEGER,
        'lside'     => Model::TYPE_INTEGER,
        'rside'     => Model::TYPE_INTEGER,
    );

    protected $_table = 'groups';

    public function __construct(Storage_Db $db, $id = null)
    {
        parent::__construct($db, $id);
    }

    public function getUsers()
    {
        return new Model_List_User($this->_db, array( "group_id" => $this->getId() ));
    }

    public function __toString()
    {
        return $this->title;
    }

	public function validate(array $attributes = array())
	{
		//$errors = array();
		$errors = parent::validate($attributes);

		if (!strlen($this->name))
			$errors['name'] = _('Name can\'t be empty');
		else if (preg_match('/[^0-9A-Za-z_-]/', $this->name))
            $errors['name'] = _('Name must contain symbols from set: A-Z, a-z, 0-9, "-", "_"');

		if (!strlen($this->title))
			$errors['title'] = _('Title can\'t be empty');

		if (!in_array($this->role, array('guest', 'user', 'manager', 'admin', 'superuser')))
			$errors['role'] = _('Role must be either "guest", "user", "manager", "admin" or "superuser"');

		return $errors;
	}
}
?>
