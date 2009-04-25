<?php
/**
 * @package Models
 * @subpackage User
 * User model
 */
class Model_User extends Model_Actor implements Model_Validatable
{

    protected $_attributes = array(
        //'id'       => 'id',
        'username' => 'username',
        'email'    => 'email',
        'created'  => 'created_at',
        'online'   => 'online_at',
        'login'    => 'login',
        'password' => 'password',
        'group'    => 'group_id',
        'rating'   => 'rating',
        'role'     => 'role',
        'flags'    => 'flags',
		'gender'   => 'gender',
    );

    protected $_fields = array(
        'id'         => Model::TYPE_INTEGER,
        'username'   => Model::TYPE_STRING,
        'email'      => Model::TYPE_STRING,
        'created_at' => Model::TYPE_TIMESTAMP,
        'online_at'  => Model::TYPE_TIMESTAMP,
        'login'      => Model::TYPE_STRING,
        'password'   => Model::TYPE_STRING,
        'group_id'   => Model::TYPE_INTEGER,
        'rating'     => Model::TYPE_FLOAT,
        'role'       => Model::TYPE_ENUM,
        'gender'     => Model::TYPE_ENUM,
        'flags'      => Model::TYPE_SET,
    );

    protected $_table = "users";

	public function __construct(Storage_Db $db, $id = null, $password = null)
	{
		if ($password !== null)
		{
			$password = md5($password);
			if (is_array($id))
				$id['password'] = $password;
			else
				$id = array('login' => $id, 'password' => $password);
		}
		parent::__construct($db, $id);
	}

    public function getGroup()
    {
        return new Model_Group($this->_db, $this->group);
    }

    public function __toString()
    {
        return (string)$this->username;
    }

	/*public function __set($name, $value)
	{
		if ($name == 'password')
			$this->_values['password'] = md5($password);
		else
			parent::__set($name, $value);
	}*/

	public function setPassword($password)
	{
		$this->password = md5($password);
	}

	public function checkPassword($password)
	{
		return $this->password == md5($password);
	}

	public function setData(array $data)
	{
		if (isset($data['password']))
		{
			if (!empty($data['password']))
				$this->setPassword($data['password']);
			unset($data['password']);
		}
		parent::setData($data);
	}

	public function validate(array $attributes = array())
	{
		$errors = array();
		if (!strlen($this->login))
			$errors['login'] = _('Login must not be empty');
		elseif (preg_match('/[^A-Za-z0-9_-]/', $this->login))
			$errors['login'] = _('Login must contain symbols from set: A-Z, a-z, 0-9, "-", "_"');

		if (!$this->getId() && !strlen($this->password))
			$errors['password'] = _('Password must not be empty');

		if (!strlen($this->username))
			$errors['username'] = _('Username must not be empty');

        if (strlen($this->_values['email']) && !preg_match("/^([-0-9a-zA-Z_.]+)@([-a-zA-Z0-9.]+\.[a-z]{2,5}|([0-9]{1,3}\.){3}[0-9]{1,3})$/", $this->_values['email']))
            $errors['email'] = _('Incorrect email address');

		if (!in_array($this->role, array('guest', 'user', 'manager', 'admin', 'superuser')))
			$errors['role'] = _('Role must be either "guest", "user", "manager", "admin" or "superuser"');

		if (!in_array($this->gender, array('male', 'female')))
			$errors['gender'] = _('Gender must be either "male" or "female"');

		//if (strlen($this->rating) && !is_float($this->rating))
			//$errors['rating'] = _('Rating must be a floating point number');

		if (!$this->group || !$this->getGroup()->getId())
			$errors['group'] = _('Group is not found');

		return $errors;
	}

    public function save()
    {
        if (!$this->getId())
            $this->created = $this->modified;
        parent::save();
    }
}
?>
