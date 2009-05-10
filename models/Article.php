<?php
/**
 * @package Models
 * @subpackage Content
 * Article model
 */
class Model_Article extends Model_Timestamped implements Model_Rightful, Model_Tagged, Model_Trashable
{
    protected $_fields = array(
        'id'             => Model::TYPE_INTEGER,
        'topic_id'       => Model::TYPE_INTEGER,
        'author_id'      => Model::TYPE_INTEGER,
        //'owner_id'       => Model::TYPE_INTEGER,
        //'group_id'       => Model::TYPE_INTEGER,
        //'rights'         => Model::TYPE_INTEGER,
        'title'          => Model::TYPE_STRING,
		'name'           => Model::TYPE_STRING,
        'abstract'       => Model::TYPE_STRING,
        'content'        => Model::TYPE_STRING,
        'created_at'     => Model::TYPE_TIMESTAMP,
        'modified_at'    => Model::TYPE_TIMESTAMP,
        'published_at'   => Model::TYPE_TIMESTAMP,
        'archived_at'    => Model::TYPE_TIMESTAMP,
        'views'          => Model::TYPE_INTEGER,
        'items_per_page' => Model::TYPE_INTEGER,
        'flags'          => Model::TYPE_SET,
        'type'           => Model::TYPE_ENUM,
        'sortorder'      => Model::TYPE_INTEGER,
		'link'           => Model::TYPE_STRING,
		'language'       => Model::TYPE_STRING,
		'original_id'    => Model::TYPE_INTEGER,
    );

    protected $_attributes = array(
        //'id'           => 'id',
        'topic'          => 'topic_id',
        'author'         => 'author_id',
        //'owner'          => 'owner_id',
        //'group'          => 'group_id',
        //'rights'         => 'rights',
        'title'          => 'title',
        'abstract'       => 'abstract',
        'content'        => 'content',
        'created'        => 'created_at',
        'modified'       => 'modified_at',
        'published'      => 'published_at',
        'archived'       => 'archived_at',
        'views'          => 'views',
        'flags'          => 'flags',
        'type'           => 'type',
        'items_per_page' => 'items_per_page',
		'order'          => 'sortorder',
		'link'           => 'link',
		'language'       => 'language',
		'original'       => 'original_id',
		'name'           => 'name',
    );

    protected $_table = 'articles';
	protected $_list_class_name = 'Model_List_Article';

	protected $_views = array(
			'visible' => 'visible_articles',
			'nonremoved' => 'nonremoved_articles',
		);

	private $_rights = null;

	public function __construct(Storage_Db $db, $id = null, $lang = null)
	{
		if ($lang !== null) $id = array( 'original_id' => $id, 'language' => $lang );
		parent::__construct($db, $id);
	}

    public function getAuthor() { return new Model_User($this->_db, $this->author); }
    public function getTopic() { return new Model_Topic($this->_db, $this->topic); }

    public function getAttachments($page = false)
    {
        return $this->getId()? new Model_List_Attachment($this->_db, array( 'article_id' => $this->getId() ), $this->items_per_page, $page): array();
    }

    public function getComments($page = false)
    {
        return $this->getId()? new Model_List_Comment($this->_db, array( 'article_id' => $this->getId() ), $this->items_per_page, $page): array();
    }

    public function attach(Model_Attachment $attachment)
    {
        $attachment->article = $this->getId();
        $attachment->save();
        return $this;
    }

	public function getOriginal()
	{
		return $this->original? new Model_Article($this->_db, $this->original): null;
	}

	public function getTranslation($language)
	{
		return new Model_Article($this->_db, array( 'language' => $language, 'original_id' => $this->original? $this->original: $this->getId() ));
	}

	public function getTranslations()
	{
		return new Model_List_Article($this->_db, array( 'original_id' => $this->original? $this->original: $this->getId() ));
	}

    public function dettach(Model_Attachment $attachment = null)
    {
        if ($attachment === null)
        {
            $attachments = $this->getAttachments();
            foreach ($attachments as $item)
            {
                $item->delete();
            }
        } else {
            if ($attachment->article == $this->getId())
                $attachment->delete();
        }
    }

    public function __toString()
    {
        return $this->title;
    }

    public function remove()
    {
		parent::remove();
		$this->dettach();
    }

    public function isVisible()
    {
        return !array_intersect($this->flags, array('hidden', 'removed'))
            and time() >= $this->published;
    }

	public function isArchived()
	{
		return in_array('archived', $this->flags) or time() < $this->archived;
	}

	public function isRemoved()
	{
		return in_array('removed', $this->flags);
	}

    public function validate(array $attributes = array())
    {
        //$errors = parent::validate($attributes);
		$errors = array();

        //foreach (array("title", "abstract", "content") as $name)
        foreach (array('title', 'content') as $name)
            if (empty($this->_values[$name]) or strlen($this->_values[$name]) == 0)
                $errors[$name] = _(ucfirst($name).' can\'t be empty');

        if (!$this->published)
            $errors['published'] = _('Publish date is incorrect');
        if (!$this->archived)
            $errors['archived'] = _('Archive date is incorrect');

        if (!$this->author or !$this->getAuthor()->getId())
            $errors['author'] = _('Author is not found');
        
        if (!$this->topic or !$this->getTopic()->getId())
            $errors['topic'] = _('Topic is not found');

        if (!in_array($this->type, array('article', 'gallery')))
            $errors['type'] = _('Type must be either article or gallery');

		if (strlen($this->language) and !preg_match('/^[a-z]{2}_[A-Z]{2}$/', $this->language))
			$errors['language'] = _('Language code is incorrect');

		if ($this->original and !$this->getOriginal()->getId())
			$errors['original'] = _('Original article is not found');

        if (strlen($this->name) and preg_match('/[^0-9A-Za-z_-]/', $this->name))
            $errors['name'] = _('Name must contain symbols from set: A-Z, a-z, 0-9, "-", "_"');

        return $errors;
    }

	protected function getNextResult($limit = 1, $fields = '*')
	{
		$myId = $this->getId();
		return $this->_db->select($this->_view, $fields, "topic_id = {$this->topic} and ({$this->_order_by_field} > {$this->order} or ({$this->_order_by_field} = {$this->order} and {$this->_pk} > {$myId}))", $limit, 0, array( $this->_order_by_field, $this->_pk ));
	}

	protected function getPrevResult($limit = 1, $fields = '*')
	{
		$myId = $this->getId();
		return $this->_db->select($this->_view, $fields, "topic_id = {$this->topic} and ({$this->_order_by_field} < {$this->order} or ({$this->_order_by_field} = {$this->order} and {$this->_pk} < {$myId}))", $limit, 0, array( $this->_order_by_field, $this->_pk ));
	}

	public function regenerate($parent = 0, $state = 0)
	{
		if (!$parent)
		{
			$topics = new Model_List_Topic($this->_db);
			foreach ($topics as $topic)
			{
				$this->regenerate($topic->getId());
			}
		}
		else
		{
			$minId = $this->_db->select($this->_view, "min(id) as minid", array( 'topic_id' => $parent ))->fetchFirst();
			$this->_db->update($this->_table, "{$this->_order_by_field} = {$this->_pk} - {$minId['minid']}", array( 'topic_id' => $parent ), array( $this->_order_by_field, $this->_pk ));
		}
	}

	public function getRights()
	{
		if (!$this->_rights) $this->_rights = new Model_Rights($this);
		return $this->_rights;
	}

	public function isInTopic(Model_Topic $topic)
	{
		return $this->topic == $topic->getId();
	}

	public function getTags()
	{
		return Model_List_Tag::getModelTags($this->_db, $this);
	}

	public function addTags($tag)
	{
		if (!$tag instanceof Model_Tag)
		{
			if (is_array($tag) or $tag instanceof Model_List_Tag)
			{
				foreach ($tag as $item)
				{
					$this->addTags($item);
				}
				return true;
			}
			else
			{
				$tag = new Model_Tag($this->_db, $tag);
			}
		}

		return $tag->tagModel($this);
	}

	public function dropTags($tag = null)
	{
		$isnull = $tag === null;
		if (!$tag instanceof Model_Tag)
		{
			if (is_array($tag) or $tag instanceof Model_List_Tag)
			{
				foreach ($tag as $item)
				{
					$this->removeTag($item);
				}
				return true;
			}
			else
			{
				$tag = new Model_Tag($this->_db, $tag);
			}
		}

		return $tag->untagModel($this, $isnull);
	}

}
?>
