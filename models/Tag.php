<?php
class Model_Tag extends Model_Tree
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

    protected $_list_class_name = 'Model_List_Tag';

	private function getObjectsList($objname, $page = 0)
	{
		$result = $this->_db->select(array('tag_relations', "{$objname}s"), "{$objname}s.*", "tag_relations.obj_type = '$objname' and tag_relations.obj_id = articles.id and tag_relations.tag_id = ".$this->getId());
		$class = "Model_List_".ucfirst(strtolower($objname));
		return new $class ($this->_db, $result);
	}

	public function getArticles($page = 0)
	{
		return $this->getObjectsList('article', $page);
	}

	public function getTopics($page = 0)
	{
		return $this->getObjectsList('topic', $page);
	}

	public function __toString()
	{
		return (string)$this->name;
	}
}
?>
