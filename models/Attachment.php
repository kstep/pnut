<?php
/**
 * @package Models
 * @subpackage Content
 * Attachment model
 */
class Model_Attachment extends Model_ArticleObject implements Model_Rightful
{
    protected $_fields = array(
        'id'          => Model::TYPE_INTEGER,
        'filename'    => Model::TYPE_STRING,
        'title'       => Model::TYPE_STRING,
        'mimetype'    => Model::TYPE_STRING,
        'author_id'   => Model::TYPE_INTEGER,
        //'owner_id'    => Model::TYPE_INTEGER,
        //'group_id'    => Model::TYPE_INTEGER,
        //'rights'      => Model::TYPE_INTEGER,
        'article_id'  => Model::TYPE_INTEGER,
        'created_at'  => Model::TYPE_TIMESTAMP,
        'modified_at' => Model::TYPE_TIMESTAMP,
        'filesize'    => Model::TYPE_INTEGER,
        'md5hash'     => Model::TYPE_STRING,
        'sortorder'   => Model::TYPE_INTEGER,
    );

    protected $_attributes = array(
        //'id'       => 'id',
        'filename' => 'filename',
        'title'    => 'title',
        'mimetype' => 'mimetype',
        'author'   => 'author_id',
        //'owner'    => 'owner_id',
        //'group'    => 'group_id',
        //'rights'   => 'rights',
        'article'  => 'article_id',
        'created'  => 'created_at',
        'modified' => 'modified_at',
        'filesize' => 'filesize',
        'md5hash'  => 'md5hash',
		'order'    => 'sortorder',
    );

	protected static $_suffixes = array(
		'image/jpeg'     => '.jpg',
		'image/png'      => '.png',
		'image/gif'      => '.gif',
		'text/plain'     => '.txt',
		'text/html'      => '.html',
		'video/x-flv'    => '.flv',
		'video/x-ms-asf' => '.asf',
	);

    protected $_table = 'attachments';
	protected $_list_class_name = 'Model_List_Attachment';

	private $_rights = null;

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

    public function getArticle()
    {
        return new Model_Article($this->_db, $this->article);
    }

    public function getAuthor()
    {
        return new Model_User($this->_db, $this->author);
    }

    public function attachTo(Model_Article $article)
    {
        $this->article = $article->getId();
    }

    /**
     * puts given file into attachments dir and fill in objects
     * with this file's meta-data.
     * @param mixed string or array either path to file to attach,
     * or $_FILES element with uploaded file description to process
     * @return bool true if upload was successful
     */
    public function uploadFile($filename, $replace = true)
    {
        if (!is_array($filename))
        {
            $filename = array(
                'tmp_name' => $filename,
                'name'     => $filename,
            );
        }

        if ($filename['error'] || !file_exists($filename['tmp_name'])) return false;

        $size = filesize($filename['tmp_name']);
        if ($filename['size'] && $filename['size'] != $size) return false;

        //$type = mime_content_type($filename['tmp_name']);
        //if ($filename['type'] && $filename['type'] != $type) return false;
        $type = $filename['type'];

        $md5hash = md5_file($filename['tmp_name']);

		if ($this->isLoaded())
		{
			$ufile = $this->getFilepath();
			if (file_exists($ufile))
			{
				if ($replace)
					unlink($ufile);
				else
					return false;
			}
		}

		if (empty($this->title))    $this->title    = $filename['name'];
        if (empty($this->filename)) $this->filename = $filename['name'];
        if (empty($this->mimetype)) $this->mimetype = $type;
        $this->md5hash  = $md5hash;
        $this->filesize = $size;
        $ufile = $this->getFilepath();

        if (!move_uploaded_file($filename['tmp_name'], $ufile)) return false;

        return true;
    }

    public function getFilepath()
    {
        return ATTACHMENTS_PATH.'/'.$this->md5hash.self::$_suffixes[$this->mimetype];
    }

    public function isImage()
    {
        return "image" == substr($this->mimetype, 0, 5);
    }
    public function isVideo()
    {
        return "video" == substr($this->mimetype, 0, 5);
    }

    /**
     * check if file is intact in filesystem.
     * return bool true if file exists and matches meta-data in storage,
     * null if file is absent, false if file exists but corrupted.
     */
    public function checkFile()
    {
        if (!$this->md5hash) return null;
        $ufile = $this->getFilepath();
        if (!file_exists($ufile)) return null;
        
        $size = filesize($ufile);
        if ($size != $this->filesize) return false;
        //$type = mime_content_type($ufile);
        //if ($type != $this->mimetype) return false;
        $md5hash = md5_file($ufile);
        if ($md5hash != $this->md5hash) return false;

        return true;
    }

    public function remove()
    {
        $filepath = $this->getFilepath();
        parent::remove();
        if (file_exists($filepath))
            unlink($filepath);
    }

	public function validate(array $attributes = array())
	{
		//$errors = parent::validate($attributes);
		$errors = array();

		if (!preg_match("#[a-z-]+/[a-z+-]+#", $this->_values['mimetype']))
			$errors['mimetype'] = _('Incorrect MIME-type format');
		if (empty($this->_values['filename']))
			$errors['filename'] = _('Filename can\'t be empty');
		return $errors;
	}

	public function getRights()
	{
		if (!$this->_rights) $this->_rights = new Model_Rights($this);
		return $this->_rights;
	}

}
?>
