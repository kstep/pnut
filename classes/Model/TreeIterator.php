<?php
require_once(CLASSES_PATH.'/TreeIterator.php');
class Model_TreeIterator implements TreeIterator
{
    private $_stack          = array();
    private $_init_object    = null;
    private $_stepInOut      = 0;

    public function __construct(Iterator $init_object)
    {
        $this->_init_object    = $init_object;
        $this->rewind();
    }

    public function rewind()
    {
        $this->_stack = array($this->_init_object);
        $this->_init_object->rewind();
    }

    public function getStep()
    {
        return $this->_stepInOut;
    }

    public function getLevel()
    {
        return count($this->_stack);
    }

    public function next()
    {
        $this->_stepInOut = 0;
        $children = $this->current()->getChildren();
        if (count($children))
        {
            array_unshift($this->_stack, $children);
            $children->rewind();
            $this->_stepInOut++;
        }
        else
        {
            for (;;)
            {
                $this->_stack[0]->next();
                if ($this->_stack[0]->valid())
                {
                    break;
                }
                else
                {
                    array_shift($this->_stack);
                    if (count($this->_stack))
                        $this->_stepInOut--;
                    else
                        break;
                }
            }
        }
    }

    public function valid()
    {
        return count($this->_stack) && $this->_stack[0]->valid();
    }

    public function current()
    {
        return $this->_stack[0]->current();
    }

    public function key()
    {
        return $this->_stack[0]->key();
    }

}
?>
