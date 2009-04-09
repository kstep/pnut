<?php
abstract class Model_Ordered extends Model_Db implements Model_Validatable
{
	protected $_order_by_field = 'sortorder';
    protected $_list_class_name = null;

	protected function moveTo(Model_Ordered $target, $aftertarget = true)
	{
		if ($aftertarget)
		{
			$above = $target;
			$below = $target->getNext();
			if (!$below)
			{
				$below = clone($target);
				$below->order++;
			}
			else
			{
				if ($below->getId() == $this->getId()) return;
			}
		}
		else
		{
			$above = $target->getPrev();
			$below = $target;
			if (!$above)
			{
				$above = clone($target);
				$above->order--;
			}
			else
			{
				if ($above->getId() == $this->getId()) return;
			}
		}

		$delta = $below->order - $above->order;

		$this->order = $above->order + 1;
		$this->_db->update($this->_table, array( $this->_order_by_field => $this->order ), array( $this->_pk => $this->getId() ));
		if ($delta < 2)
		{
			$update = $above->getNextId(0);
			$update = array_diff($update, array( $this->getId() ));
			if ($update)
			{
				$delta = 2 - $delta;
				$this->_db->update($this->_table, "{$this->_order_by_field} = {$this->_order_by_field} + $delta", array( $this->_pk => $update ));
			}
		}
	}

	public function isAfter(Model_Ordered $target)
	{
		return $target->order < $this->order or ($target->order == $this->order and $target->getId() < $this->getId());
	}

	public function isBefore(Model_Ordered $target)
	{
		return !$this->isAfter($target);
	}

	public function insertAfter(Model_Ordered $target)
	{
		$this->moveTo($target, true);
	}

	public function insertBefore(Model_Ordered $target)
	{
		$this->moveTo($target, false);
	}

	abstract protected function getNextResult($limit = 1, $fields = '*');
	abstract protected function getPrevResult($limit = 1, $fields = '*');
	abstract public function regenerate($parent = 0, $state = 0);

	public function getNext($limit = 1)
	{
		$result = new $this->_list_class_name ($this->_db, $this->getNextResult($limit));
		if ($limit == 1)
		{
			$result->rewind();
			return $result->current();
		}
		else
		{
			return $result;
		}
	}

	public function getPrev($limit = 1)
	{
		$result = new $this->_list_class_name ($this->_db, $this->getPrevResult($limit));
		if ($limit == 1)
		{
			$result->rewind();
			return $result->current();
		}
		else
		{
			return $result;
		}
	}

	public function getNextId($limit = 1)
	{
		$result = $this->getNextResult($limit, $this->_pk);

		if ($limit == 1)
		{
			$result = $result->fetchFirst();
			return $result[$this->_pk];
		}
		else
		{
			$output = array();
			foreach ($result as $item) $output[] = $item[$this->_pk];
			return $output;
		}
	}

	public function getPrevId($limit = 1)
	{
		$result = $this->getPrevResult($limit, $this->_pk);

		if ($limit == 1)
		{
			$result = $result->fetchFirst();
			return $result[$this->_pk];
		}
		else
		{
			$output = array();
			foreach ($result as $item) $output[] = $item[$this->_pk];
			return $output;
		}
	}
}
?>
