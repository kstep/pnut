<?php
class Authorizator_Rules extends Authorizator
{
    protected $_rules = array();
    static protected $_roles = array(
        'superuser' => 0,
        'admin'     => 1,
        'manager'   => 2,
        'user'      => 3,
        'guest'     => 4,
    );

    public function __construct(Storage $store, array $rules = array())
    {
        $this->_rules = $rules;
        parent::__construct($store);
    }

    public function canPerform(Controller_Restricted $controller, $action = null)
    {
        $realm = $controller->getRealm();
        $rule  = $this->_rules[$realm];
        if ($rule)
        {
            $class = str_replace("Controller_", "", get_class($controller));
            $rule = $rule[$class];
            if ($rule)
            {
                if ($action !== null && $rule[$action]) $rule = $rule[$action];
                return (!$rule['allow'] || $this->matchRule($rule['allow']))
                    && !($rule['deny'] && $this->matchRule($rule['deny']));
            }
            else
            {
                return $this->canAccess($realm);
            }
        }
        else
        {
            return false;
        }
    }

    public function canAccess($realm)
    {
        $rule = $this->_rules[$realm];
        if ($rule)
        {
            return (!$rule['allow'] || $this->matchRule($rule['allow']))
                && !($rule['deny'] && $this->matchRule($rule['deny']));
        }
        else
        {
            return false;
        }
    }

    protected function matchRule(array $rule)
    {
		return !isset($rule['nobody']) && (
			(isset($rule['everyone']) || isset($rule['authorized']) && $this->_authorized_user)
            || ($rule['user'] && $rule['user'] === $this->_authorized_user->login)
            || ($rule['role'] && $this->compareRole($this->_authorized_user->role, $rule['role']))
			|| ($rule['group'] && ($rule['group'] === $this->_authorized_user->getGroup()->name))
			|| ($rule['subgroup'] && $this->_authorized_user->getGroup()->isAncestorOf(new Model_Group($this->_authorized_user->getStorage(), array( 'name' => $rule['subgroup'] ))))
		);
    } 

    protected function compareRole($role1, $role2)
    {
        return self::$_roles[$role2] >= self::$_roles[$role1];
    }
}
?>
