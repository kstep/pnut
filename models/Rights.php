<?php
class Model_Rights extends Model_Db
{
	protected $_attributes = array(
		'otype'  => 'object_type',
		'oid'    => 'object_id',
		'owner'  => 'owner_id',
		'group'  => 'group_id',
		'rights' => 'rights',
	);

	protected $_fields = array(
		'object_type' => Model::TYPE_ENUM,
		'object_id'   => Model::TYPE_INTEGER,
		'owner_id'    => Model::TYPE_INTEGER,
		'group_id'    => Model::TYPE_INTEGER,
		'rights'      => Model::TYPE_INTEGER,
	);

	protected $_table = 'rights';

	protected $_pk = array( "object_type", "object_id" );

	public function __construct(Model_Db $model)
	{
		$otype = strtolower(substr(get_class($model), 6));
		$oid = (int)$model->getId();
		if ($otype and $oid)
			parent::__construct($model->getStorage(), array( 'object_type' => $otype, 'object_id' => $oid ));
		if (!$this->isLoaded())
		{
			$this->otype = $otype;
			$this->oid   = $oid;
		}
	}

	public function getOwner() { return new Model_User($this->_db, $this->owner); }
	public function getGroup() { return new Model_Group($this->_db, $this->group); }

    public function getRights()
    {
        $result = array();
        $rights = $this->rights;
        if ($rights & (Model_Actor::CAN_VIEW << Model_Actor::OTHER_RIGHTS)) array_push($result, "or");
        if ($rights & (Model_Actor::CAN_EDIT << Model_Actor::OTHER_RIGHTS)) array_push($result, "ow");
        if ($rights & (Model_Actor::CAN_DELETE << Model_Actor::OTHER_RIGHTS)) array_push($result, "ox");

        if ($rights & (Model_Actor::CAN_VIEW << Model_Actor::GROUP_RIGHTS)) array_push($result, "gr");
        if ($rights & (Model_Actor::CAN_EDIT << Model_Actor::GROUP_RIGHTS)) array_push($result, "gw");
        if ($rights & (Model_Actor::CAN_DELETE << Model_Actor::GROUP_RIGHTS)) array_push($result, "gx");

        if ($rights & (Model_Actor::CAN_VIEW << Model_Actor::OWNER_RIGHTS)) array_push($result, "ur");
        if ($rights & (Model_Actor::CAN_EDIT << Model_Actor::OWNER_RIGHTS)) array_push($result, "uw");
        if ($rights & (Model_Actor::CAN_DELETE << Model_Actor::OWNER_RIGHTS)) array_push($result, "ux");

        return $result;
    }

	public function __toString()
	{
		return implode("", $this->getRights());
	}

    public function setRights(array $value, $owner = null, $group = null)
    {
		if ($owner) $this->owner = $owner instanceof Model_User? $owner->getId(): (int)$owner;
		if ($group) $this->group = $group instanceof Model_Group? $group->getId(): (int)$group;

        $val = 0;
        foreach ($value as $item)
        {
            $mask = 0;

            switch (substr($item, 1, 1))
            {
            case 'r':
            case 'R':
                $mask = Model_Actor::CAN_VIEW;
                break;
            case 'w':
            case 'W':
                $mask = Model_Actor::CAN_EDIT;
                break;
            case 'x':
            case 'X':
                $mask = Model_Actor::CAN_DELETE;
                break;
            }

            switch (substr($item, 0, 1))
            {
            case 'u':
            case 'U':
                $mask <<= 6;
                break;
            case 'g':
            case 'G':
                $mask <<= 3;
                break;
            case 'o':
            case 'O':
                break;
            default:
                $mask = 0;
            }

            $val |= $mask;
        }
        $this->rights = $val;
		return $this;
    }

	public function validate(array $attributes = array())
	{
		$errors = array();
		if (!$this->owner or !$this->getOwner()->isLoaded())
			$errors["owner"] = _('Owner is not found');

		if (!$this->group or !$this->getGroup()->isLoaded())
			$errors["group"] = _('Group is not found');

		return $errors;
	}
}
?>
