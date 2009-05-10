<?php
/**
 * @package Models
 * @subpackage Content
 * Comment model
 */
class Model_Comment extends Model_ArticleObject implements Model_Trashable
{
    protected $_fields = array(
        'id'          => Model::TYPE_INTEGER,
        'article_id'  => Model::TYPE_INTEGER,
        'author_id'   => Model::TYPE_INTEGER,
        'title'       => Model::TYPE_STRING,
        'content'     => Model::TYPE_STRING,
        'created_at'  => Model::TYPE_TIMESTAMP,
        'modified_at' => Model::TYPE_TIMESTAMP,
        'flags'       => Model::TYPE_SET,
        'username'    => Model::TYPE_STRING,
        'email'       => Model::TYPE_STRING,
    );

    protected $_attributes = array(
        //'id'        => 'id',
        'author'    => 'author_id',
        'article'   => 'article_id',
        'title'     => 'title',
        'content'   => 'content',
        'created'   => 'created_at',
        'modified'  => 'modified_at',
        'flags'     => 'flags',
        'username'  => 'username',
        'email'     => 'email',
    );

    protected $_table = 'comments';
	protected $_list_class_name = 'Model_List_Comment';
	protected $_order_by_field = 'created_at';

	public function isAfter(Model_Comment $target) { return $this->created > $target->created; }

	protected function getNextResult($limit = 1, $fields = '*')
	{
		return $this->_db->select($this->_table, $fields, "article_id = {$this->article} AND UNIX_TIMESTAMP({$this->_order_by_field}) > {$this->created}", $limit);
	}
	protected function getPrevResult($limit = 1, $fields = '*')
	{
		return $this->_db->select($this->_table, $fields, "article_id = {$this->article} AND UNIX_TIMESTAMP({$this->_order_by_field}) < {$this->created}", $limit);
	}

	protected function moveTo(Model_Comment $target, $aftertarget = true)
	{
		// impossible
	}

    public function getAuthor() { return new Model_User($this->_db, $this->author); }
    public function getArticle() { return new Model_Article($this->_db, $this->article); }

	public function isRemoved()
	{
		return in_array('removed', $this->flags);
	}

	public function isVisible()
	{
        return !count($this->flags);
	}

    public function validate(array $attributes = array())
    {
        //$errors = parent::validate($errors, $attributes);
		$errors = array();

        if (!strlen($this->content))
            $errors['content'] = _('Content must not be empty');

        if (strlen($this->email) && !preg_match("/^([-0-9a-zA-Z_.]+)@([-a-zA-Z0-9.]+\.[a-z]{2,5}|([0-9]{1,3}\.){3}[0-9]{1,3})$/", $this->email))
            $errors['email'] = _('Incorrect email address');

        return $errors;
    }

    public function __toString()
    {
        return $this->title;
    }
}
?>
