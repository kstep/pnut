<?php
class Model_Tag extends Model_Tree implements Countable
{

    protected $_table = 'tags';

    protected $_attributes = array(
        'name'   => 'name',
        'parent' => 'parent_id',
    );

    protected $_fields = array(
        'name'      => Model::TYPE_STRING,
        'parent_id' => Model::TYPE_INTEGER,
    );

	private $_total = null;

    protected $_list_class_name = 'Model_List_Tag';

	private function getModelsList($objname, $page = 0)
	{
		$result = $this->_db->select(array('tag_relations', $objname), "{$objname}.*", "tag_relations.obj_type = '$objname' and tag_relations.obj_id = {$objname}.id and tag_relations.tag_id = ".$this->getId());
		$class = "Model_List_".ucfirst(strtolower($objname));
		return new $class ($this->_db, $result);
	}

	public function getArticles($page = 0)
	{
		return $this->getModelsList('article', $page);
	}

	public function getTopics($page = 0)
	{
		return $this->getModelsList('topic', $page);
	}

	public function __toString()
	{
		return (string)$this->name;
	}

	public function parseData(array $values)
	{
		if (isset($values['total'])) $this->_total = (int)$values['total'];
		return parent::parseData($values);
	}

	public function count()
	{
		if ($this->_total === null)
		{
			$result = $this->_db->select('tag_relations', 'COUNT(*) AS total', array( 'tag_id' => $this->getId() ))->fetchFirst();
			$this->_total = (int)$result['total'];
		}
		return $this->_total;
	}
}
?>
