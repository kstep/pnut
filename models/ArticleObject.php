<?php
// @todo
abstract class Model_ArticleObject extends Model_Timestamped
{
	protected function getNextResult($limit = 1, $fields = '*')
	{
		$myId = $this->getId();
		return $this->_db->select($this->_table, $fields, "article_id = {$this->article} AND ({$this->_order_by_field} > {$this->order} OR ({$this->_order_by_field} = {$this->_order} AND {$this->_pk} > {$myId}))", $limit);
	}
	protected function getPrevResult($limit = 1, $fields = '*')
	{
		$myId = $this->getId();
		return $this->_db->select($this->_table, $fields, "article_id = {$this->article} AND ({$this->_order_by_field} < {$this->order} OR ({$this->_order_by_field} = {$this->_order} AND {$this->_pk} < {$myId}))", $limit);
	}

	public function regenerate($parent = 0, $state = 0)
	{
		if (!$parent)
		{
			$articles = new Model_List_Article($this->_db);
			foreach ($articles as $articles)
			{
				$this->regenerate($article->getId());
			}
		}
		else
		{
			$minId = $this->_db->select($this->_table, "min(id) as minid", array( 'article_id' => $parent ))->fetchFirst();
			$this->_db->update($this->_table, "{$this->_order_by_field} = {$this->_pk} - {$minId['minid']}", array( 'article_id' => $parent ), array( $this->_order_by_field, $this->_pk ));
		}
	}
}
