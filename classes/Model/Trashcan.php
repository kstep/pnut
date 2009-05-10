<?php
class Model_Trashcan
{
	private $_db;

	public function __construct(Storage $db)
	{
		$this->_db = $db;
	}

	public function getList($object)
	{
		$list = Model_List::create($this->_db, $object);
	   	$list->find("FIND_IN_SET('removed', flags) > 0");
		return $list;
	}

	private function _update_model(Model_Trashable $model, $expression)
	{
		$this->_db->update($model->getTable(), $expression, array( $model->getPk() => $model->getId() ));
	}

	private function _update_list($objname, $idlist, $expression)
	{
		$model = Model::create($this->_db, $objname);
		$this->_db->update($model->getTable(), $expression, array( $model->getPk() => $idlist ));
	}

	public function cleanup($object, $idlist = null)
	{
		if ($object instanceof Model_Trashable && $object->isRemoved())
		{
			$object->remove();
		}
		elseif (is_string($object) && $idlist !== null)
		{
			$list = Model_List::create($this->_db, $object);
			$list->remove($idlist);
		}
	}

	public function restore($object, $idlist = null)
	{
		if ($object instanceof Model_Trashable && !$object->isRemoved())
		{
			$object->flags = array_diff($object->flags, array( 'removed' ));
			$this->_update_model($object, "flags = REPLACE(flags, 'removed', '')");
		}
		elseif (is_string($object) && $idlist !== null)
		{
			$this->_update_list($object, $idlist, "flags = REPLACE(flags, 'removed', '')");
		}
	}

	public function remove($object, $idlist = null)
	{
		if ($object instanceof Model_Trashable && !$object->isRemoved())
		{
			$object->flags[] = 'removed';
			$this->_update_model($object, "flags = CONCAT_WS(',', flags, 'removed')");
		}
		elseif (is_string($object) && $idlist !== null)
		{
			$this->_update_list($object, $idlist, "flags = CONCAT_WS(',', flags, 'removed')");
		}
	}

	static public function put(Model_Trashable $model)
	{
		if (!$model->isRemoved())
		{
			$model->flags[] = 'removed';
			$model->getStorage()->update($model->getTable(), "flags = CONCAT_WS(',', flags, 'removed')", array( $model->getPk() => $model->getId() ));
		}
	}
}
?>
