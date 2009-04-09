<?php
/**
 * @package Core
 * @subpackage Interface
 * Interface to object which can iterate through tree structure
 */
interface TreeIterator extends Iterator
{
    /**
     * get step offset: it should return value > 0 if we step into
     * this number of levels deeper on this iteration, < 0 if we step
     * out to this number of levels higher, and 0 if we stay on the same
     * level.
     * @return integer
     */
    public function getStep();

    /**
     * get depth level on current iteration.
     * @return integer
     */
    public function getLevel();

    /**
     * check if we are at the beggining of the tree.
     * this method must return true if, and only if, we at at the first
     * element of the tree.
     * @return boolean
     */
    public function isFirst();

	public function getPath($pathsep = '/');
}
?>
