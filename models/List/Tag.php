<?php
class Model_List_Tag extends Model_List_Db
{
    protected $_table = 'tags';
    protected $_model_class_name = 'Model_Tag';

	public static function getModelTags(Storage_Db $db, Model_Tagged $model)
	{
		$table = $model->getTable();
		$objid = $model->getId();
		$result = $db->select(array('tag_relations', 'tags'), "tags.*", "tag_relations.obj_type = '{$table}' and tag_relations.obj_id = '{$objid}' and tag_relations.tag_id = tags.id");
		return new Model_List_Tag($db, $result);
	}

	public static function getTagsCloud(Storage_Db $db, $objtype = null, $limit = 0, $offset = 0, $order = "")
	{
		$result = $db->select('tags LEFT JOIN tag_relations ON (tags.id = tag_relations.tag_id)', "tags.*, COUNT(tag_relations.tag_id) AS total", $objtype === null? null: array( 'obj_type' => $objtype ), $limit, $offset, $order, "tags.id");
		return new Model_List_Tag($db, $result);
	}
}
?>
