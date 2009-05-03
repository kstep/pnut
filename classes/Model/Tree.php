<?php
abstract class Model_Tree extends Model_Ordered implements IteratorAggregate
{
    protected $_parent_field = 'parent_id';

    protected $_path = "";

    public function __construct(Storage_Db $db, $path = null)
    {
        if ($path && is_string($path) && !is_numeric($path))
        {
            $condition = $tables = array();
            $apath = explode("/", trim($path, "/"));
            $pathlen = count($apath) - 1;
            // t0.parent_id = 0 and t1.parent_id = t2.id and ... and tN.parent_id = 0 and t0.name = $apath[0] and ... and tN.name = $apath[N]
            foreach ($apath as $index => $name)
            {
                $tables[] = "{$this->_table} as t$index";
                $condition[] = ($index > 0? "t{$index}.{$this->_parent_field} = t".($index-1).".id": "t0.{$this->_parent_field} = 0") . " AND t{$index}.name = '".addslashes($name)."'";
            }
            $data = $db->select($tables, "t{$pathlen}.*", implode(" AND ", $condition), 1)->fetchFirst();
            parent::__construct($db);
            if ($data)
            {
                $this->parseData($data);
            }
        }
        else
        {
            parent::__construct($db, $path);
        }
    }

	public function isChildOf(Model_Tree $model)
	{
		return $this->parent == $model->getId();
	}
	public function isParentOf(Model_Tree $model)
	{
		return $this->getId() == $model->parent;
	}

	public function isDescendantOf(Model_Tree $model)
	{
		if ($this->isChildOf($model)) return true;
		for ($item = $this->getParent(); $item; $item = $item->getParent())
		{
			if ($item->isChildOf($model))
				return true;
		}
		return false;
	}
	public function isAncestorOf(Model_Tree $model)
	{
		return $model->isDescendantOf($this);
	}

    /**
     * get children, null should be returned for leaf objects.
     * @return array of Model_Tree
     */
    public function getChildren()
	{
		return new $this->_list_class_name($this->_db, array( $this->_parent_field => $this->getId() ));
	}

    /**
     * get parent, null should be returned for root objects.
     * @return Model_Tree
     */
    public function getParent()
	{
		$class = get_class($this);
		return new $class($this->_db, array( $this->_parent_field => $this->parent ));
	}


	public function getSiblings()
	{
		return new $this->_list_class_name($this->_db, array( $this->_parent_field => $this->parent ));
	}

    /**
     * get list of all parents.
     * @param bool if true current object will be in the list
     * @return array of Model_Tree
     */
    public function getAncestors($inclusive = false)
    {
        $result = array();
        for ($parent = $inclusive? $this: $this->getParent(); $parent; $parent = $parent->getParent())
        {
            $result[] = $parent;
        }
        return $result;
    }

    /**
     * get list of all descendants.
     * @return array of Model_Tree
     */
    public function getDescendants()
    {
        $result = array();
        $children = $this->getChildren();
        foreach ($children as $child)
        {
            $result[] = $child;
            $result = array_merge($result, $child->getDescendants());
        }
        return $result;
    }

    /**
     * get path to current object as string.
     * @param string path separator
     * @param bool if true current object will be in the list
     * @return string
     */
    public function getPath($path_separator = "/", $inclusive = true)
    {
        if (!$this->_path)
        {
            if (!$this->isLoaded()) return "";

			if ($this->parent)
			{
				$this->_path = "";
				for ($parent = $this; $parent; $parent = $parent->getParent())
				{
					$this->_path = "$parent$path_separator{$this->_path}";
				}
				$this->_path = trim($this->_path, '/');
			}
			else
			{
				$this->_path = (string)$this;
			}
        }
        return $inclusive? $this->_path: substr($this->_path, 0, strrpos($this->_path, '/'));
    }

    /**
     * convinience method to get list of all children's ids.
     * @return array of integers
     */
    public function getDescendantsId()
    {
        $result = array();
        for ($children = $this->getChildren(); $children; $children = $children->getChildren())
        {
            foreach ($children as $child)
            {
                $result[] = $child->getId();
            }
        }
        return $result;
    }

    /**
     * IteratorAggregate interface imlementation.
     * @return Iterator
     */
    public function getIterator()
    {
        return $this->getDescendants();
    }

	protected function getNextResult($limit = 1, $fields = '*')
	{
		return $this->_db->select($this->_table, $fields, "{$this->_parent_field} = {$this->parent} and {$this->_order_by_field} > {$this->order}", $limit, 0, $this->_order_by_field);
	}

	protected function getPrevResult($limit = 1, $fields = '*')
	{
		return $this->_db->select($this->_table, $fields, "{$this->_parent_field} = {$this->parent} and {$this->_order_by_field} < {$this->order}", $limit, 0, $this->_order_by_field);
	}

	public function validate(array $attributes = array())
	{
		$errors = array();

        if ($this->parent)
        {
            if ($this->parent == $this->_id)
                $errors['parent'] = _('Item can\'t be parent of itself');
			else
			{
				$subtopics = $this->getDescendantsId();
				if (in_array($this->parent, $subtopics))
					$errors['parent'] = _('Item\'s child can\'t be parent of a child');
			}
        }

		return $errors;
	}
}
?>
