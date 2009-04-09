<?php
class Filter_Pager implements Iterator, ArrayAccess, Countable
{
    private $_num_items = 0;
    private $_num_pages = 0;
    private $_current_page   = 0;
    private $_visible_range  = 5;
    private $_items_per_page = 10;

    private $_page   = 1;
    private $_offset = 0;

    private $_min_visible_page = 1;
    private $_max_visible_page = 10;

    public function __construct($num_items = 0, $current_page = 1, $items_per_page = 20, $visible_range = 5)
    {
        $this->_num_items      = (int)($num_items instanceof Countable? count($num_items): $num_items);
        $this->_current_page   = (int)$current_page;
        $this->_visible_range  = (int)$visible_range;
        $this->_items_per_page = (int)$items_per_page;

        $this->init();
    }

    private function init()
    {
        if (!$this->_current_page) $this->_current_page = 1;
        if (!$this->_items_per_page) $this->_items_per_page = 20;

        $this->_num_pages = ceil($this->_num_items / $this->_items_per_page);
		if ($this->_num_pages == 0) $this->_num_pages = 1;

        if ($this->_current_page < 1 or $this->_current_page > $this->_num_pages)
            throw new Filter_Exception("Current page is out of borders.");

        $this->_min_visible_page = ($this->_current_page <= $this->_visible_range)? 1: $this->_current_page - $this->_visible_range;
        $this->_max_visible_page = ($this->_current_page >= ($this->_num_pages - $this->_visible_range))? $this->_num_pages: $this->_current_page + $this->_visible_range;
        $this->rewind();
    }

    public function rewind()
    {
        $this->_page   = $this->_min_visible_page;
        $this->_offset = ($this->_page - 1) * $this->_items_per_page;
    }

    public function next()
    {
        $this->_page++;
        $this->_offset += $this->_items_per_page;
    }

    public function valid()
    {
        return $this->_page >= $this->_min_visible_page and $this->_page <= $this->_max_visible_page;
    }

    public function current()
    {
        return $this->_offset;
    }

    public function key()
    {
        return $this->_page;
    }

    public function count()
    {
        return $this->_num_pages;
    }

    public function isCurrentPage()
    {
        return $this->_current_page == $this->_page;
    }

    public function setPage($page = 0)
    {
        $this->_current_page = (int)$page;
        $this->init();
        return $this;
    }
    public function setCount($count)
    {
        $this->_num_items = (int)$count;
        $this->init();
        return $this;
    }
    public function setVisibleRange($range)
    {
        $this->_visible_range = (int)$range;
        $this->init();
        return $this;
    }

    public function minVisiblePage()
    {
        return $this->_min_visible_page;
    }

    public function maxVisiblePage()
    {
        return $this->_max_visible_page;
    }

    public function offsetGet($offset)
    {
        return $offset * $this->_items_per_page;
    }

    public function offsetExists($offset)
    {
        return $offset > 0 and $offset <= $this->_num_pages;
    }

    public function offsetSet($offset, $value)
    {
        return;
    }

    public function offsetUnset($offset)
    {
        return;
    }
}
?>
