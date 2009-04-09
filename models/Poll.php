<?php
/**
 * @package Models
 * @subpackage Poll
 * Poll model
 * @todo implement Model_Ordered abstract methods
 */
class Model_Poll extends Model_Timestamped implements Model_Rightful
{
    protected $_attributes = array(
        'author'      => 'author_id',
        //'owner'       => 'owner_id',
        //'group'       => 'group_id',
        //'rights'      => 'rights',
        'title'       => 'title',
        'description' => 'description',
        'created'     => 'created_at',
        'answers'     => 'answers',
        'closed'      => 'closed_at',
        'modified'    => 'modified_at',
        'maxanswers'  => 'max_answers',
        'flags'       => 'flags',
    );

    protected $_fields = array(
        'id'          => Model::TYPE_INTEGER,
        'author_id'   => Model::TYPE_INTEGER,
        //'owner_id'    => Model::TYPE_INTEGER,
        //'group_id'    => Model::TYPE_INTEGER,
        //'rights'      => Model::TYPE_INTEGER,
        'title'       => Model::TYPE_STRING,
        'description' => Model::TYPE_STRING,
        'created_at'  => Model::TYPE_TIMESTAMP,
        'answers'     => Model::TYPE_INTEGER,
        'closed_at'   => Model::TYPE_TIMESTAMP,
        'modified_at' => Model::TYPE_TIMESTAMP,
        'max_answers' => Model::TYPE_INTEGER,
        'flags'       => Model::TYPE_SET,
    );

    protected $_table = 'polls';
	protected $_list_class_name = 'Model_List_Poll';

	private $_rights = null;

	/**
	 * get user, who created this poll
	 * @return Model_User
	 */
    public function getAuthor()
    {
        return new Model_User($this->_db, $this->author);
    }

	public function getRights()
	{
		if (!$this->_rights) $this->_rights = new Model_Rights($this);
		return $this->_rights;
	}
}
?>
