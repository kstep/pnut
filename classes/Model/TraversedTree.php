<?php
abstract class Model_TraversedTree extends Model_Tree
{

    protected $_left_side  = 'lside';
    protected $_right_side = 'rside';    

	protected $_order_by_field = 'lside';

    /**
     * get children, null should be returned for lead objects.
     * @return array of Model_Tree
     */
    public function getDescendants()
    {
        if (!$this->isLoaded() or $this->getNumDescendants() == 0) return array();
		$id = $this->getId();
        return new $this->_list_class_name ($this->_db, "{$this->_left_side} > {$this->lside} AND {$this->_left_side} < {$this->rside}", 0, 0, $this->_left_side);
    }

    public function getDescendantsId()
    {
        if (!$this->isLoaded() or $this->getNumDescendants() == 0) return array();
		$id = $this->getId();
        $query = $this->_db->select($this->_table, $this->_pk, $depth? "{$this->_left_side} > {$this->lside} AND {$this->_left_side} < {$this->rside}": "{$this->_parent_field} = {$id}");
        $result = array();
        foreach ($query as $row)
        {
            $result[] = $row[$this->_pk];
        }
        return $result;
    }

	public function isDescendantOf(Model_TraversedTree $model)
	{
		return $this->lside > $model->lside and $this->lside < $model->rside;
	}

    /**
     * get number of children in this subtree.
     * @return integer
     */
    public function getNumDescendants()
    {
        return max($this->{$this->_right_side} - $this->{$this->_left_side} - 1, 0);
    }

    /**
     * get list of all parents.
     * @param bool if true current object will be in the list
     * @return array of Model_Tree
     */
    public function getAncestors($inclusive = false)
    {
        if (!$this->_id) return array();
        $inclusive = $inclusive? "=": "";
        return new $this->_list_class_name ($this->_db, "{$this->_left_side} <$inclusive {$this->lside} AND {$this->_right_side} >$inclusive {$this->rside}", 0, 0, $this->_left_side);
    }

    public function getRoot()
    {
        $list = new $this->_list_class_name ($this->_db, "{$this->_left_side} < {$this->lside} AND {$this->_right_side} > {$this->rside}", 1, 0, $this->_left_side);
        $list->rewind();
        return $list->current();
    }

	/**
	 * reorder nodes in a such way, that this concrete node (and its subtree)
	 * is placed immediately after target model.
	 * @param Model_TraversedTree $target model, must have the same class as $this
	 * @return void
	 */
	protected function moveTo(Model_TraversedTree $target, $aftertarget = true)
	{

		$overtarget = ($this->isAfter($target) xor (bool)$aftertarget);

		if ($target === null)
		{
			$target = $this->getSiblings()->current();
			$overtarget = !$overtarget;
		}
		else
		{
			if (get_class($this) !== get_class($target))
			{
				throw new Model_Exception("Model classes must be the same.");
			}
			elseif ($this->parent != $target->parent)
			{
				throw new Model_Exception("Models must be siblings.");
			}
		}

		if ($overtarget)
		{
			$lside = $target->lside - 1;
			$rside = $target->rside + 1;
		}
		else
		{
			$lside = $target->rside;
			$rside = $target->lside;
		}

		$lside = min($this->rside, $lside);
		$rside = max($this->lside, $rside);
		$Do  = $this->rside - $this->lside + 1;
		$Dto = $rside - $lside - 1;

		if ($target->lside < $this->lside)
			$Dto = -$Dto;
		else
			$Do = -$Do;

		if ($Dto != 0)
		{
			$subtree = $this->getDescendantsId();
			$subtree[] = $this->getId();

			// first we free some space for new $this position
			$this->_db->update($this->_table, "{$this->_left_side} = {$this->_left_side} + {$Do}, {$this->_right_side} = {$this->_right_side} + {$Do}", "{$this->_left_side} > $lside AND {$this->_right_side} < $rside");

			// then we move $this to its new position after $target
			$this->_db->update($this->_table, "{$this->_left_side} = {$this->_left_side} + {$Dto}, {$this->_right_side} = {$this->_right_side} + {$Dto}", array( $this->_pk => $subtree ));

			// and now we update self & target objects to avoid additional requests.
			$this->lside += $Dto;
			$this->rside += $Dto;
			if ($overtarget)
			{
				$target->lside += $Do;
				$target->rside += $Do;
			}
		}
	}

	public function isAfter(Model_TraversedTree $target)
	{
		return $this->lside > $target->lside;
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
				$query = $this->_db->select($this->_table, "name", "{$this->_left_side} <= {$this->lside} AND {$this->_right_side} >= {$this->rside}", 0, 0, "{$this->_left_side} DESC");
				$this->_path = '';
				foreach ($query as $item)
				{
					$this->_path = "{$item['name']}$path_separator{$this->_path}";
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

	protected function getNextResult($limit = 1, $fields = '*')
	{
		return $this->_db->select($this->_table, $fields, "parent_id = {$this->parent} and {$this->_order_by_field} > {$this->lside}", $limit, 0, $this->_order_by_field);
	}

	protected function getPrevResult($limit = 1, $fields = '*')
	{
		return $this->_db->select($this->_table, $fields, "parent_id = {$this->parent} and {$this->_order_by_field} < {$this->lside}", $limit, 0, $this->_order_by_field);
	}

    /**
     * regenerate left & right values.
     * @param integer parent of current pass
     * @param integer left side value of current pass
     * @return integer
     */
    public function regenerate($parent = 0, $lside = 0)
    {
        $children = $this->_db->select($this->_table, array( $this->_pk, $this->_parent_field ), array( $this->_parent_field => $parent ), 0, 0, array( $this->_order_by_field, $this->_pk ));
        foreach ($children as $child)
        {
            $this->_db->update($this->_table, array( $this->_left_side => $lside, $this->_right_side => ($lside = $this->regenerate($child[$this->_pk], $lside + 1)) ), array( $this->_pk => $child[$this->_pk] ));
            $lside++;
        }
        return $lside;
    }

    public function save()
    {
		// @todo avoid regenerate if possible
		// if (creating) { add_space_for_new_node; calculate_sides_for_new_node; }
		// elseif (parent_changed) { do_some_magic_to_rearrange_nodes_sides; }
        parent::save();
        $this->regenerate();
    }
}
?>
