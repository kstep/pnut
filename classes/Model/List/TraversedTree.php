<?php
class Model_List_TraversedTree extends Model_List_Ordered implements TreeIterator
{
    private $_stack = array();
    private $_stepInOut = 0;
    private $_stepFirst = true;
	private $_path = array();
	private $_basePath = '';

    protected $_order_by_fields = "lside,id";

    public function __construct(Storage_Db $db, $filter = "", $limit = 0, $offset = 0, $order = "", $group = "", $having = "")
    {
        parent::__construct($db, $filter, $limit, $offset, $order, $group, $having);
		$this->rewind();
		if ($this->_current_model)
			$this->_basePath = $this->_current_model->getPath('/', false);
    }

    public function getStep()
    {
        return $this->_stepInOut;
    }

    public function getLevel()
    {
        return count($this->_stack);
    }

    public function isFirst()
    {
        return $this->_stepFirst;
    }

    public function rewind()
    {
        parent::rewind();
        $this->_stepInOut = 0;
        $this->_stepFirst = true;
        $this->_stack = array( $this->_current_model->rside );
		$this->_path = array( (string)$this->_current_model );
    }

    public function next()
    {
		Profile::start(__METHOD__);
        parent::next();
        $this->_stepInOut = 0;
        $this->_stepFirst = false;
        while (count($this->_stack) > 0 && $this->_stack[0] < $this->_current_model->rside)
        {
            array_shift($this->_stack);
            $this->_stepInOut--;
        }
		if ($this->_stack[0] != $this->_current_model->rside)
		{
			array_splice($this->_path, count($this->_path) + $this->_stepInOut++, count($this->_path), (string)$this->_current_model);
			array_unshift($this->_stack, $this->_current_model->rside);
		}
		Profile::stop(__METHOD__);
    }

	public function getPath($pathsep = '/')
	{
		return $this->_basePath.$pathsep.implode($pathsep, $this->_path);
	}

}
?>
