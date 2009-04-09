<?php
abstract class Model_Actor extends Model_Db
{
    const CAN_VIEW = 4;
    const CAN_EDIT = 2;
    const CAN_DELETE = 1;

	const OWNER_RIGHTS = 6;
	const GROUP_RIGHTS = 3;
	const OTHER_RIGHTS = 0;

    private function canDo(Model_Rightful $target, $mask)
    {
		if ($this->role == 'superuser') return true;

		$rights = $target->getRights();
        if ($this->getId() == $rights->owner)
        {
            return (bool)($this->rights & ($mask << self::OWNER_RIGHTS));
        }
        elseif ($this->group == $rights->group || $this->getGroup()->isAncestorOf($rights->getGroup()))
        {
            return (bool)($this->rights & ($mask << self::GROUP_RIGHTS));
        }
        else
        {
            return (bool)($this->rights & ($mask << self::OTHER_RIGHTS));
        }
    }

    public function canView(Model $target)
    {
        return $this->canDo($target, self::CAN_VIEW);
    }
    public function canEdit(Model $target)
    {
        return $this->canDo($target, self::CAN_EDIT);
    }
    public function canDelete(Model $target)
    {
        return $this->canDo($target, self::CAN_DELETE);
    }

}
?>
