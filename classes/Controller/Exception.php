<?php
class Controller_Exception extends Exception
{
    /**
     * @var View stores view to be render()ed to inform user about
     * Controller's error (e.g. redirect or access denied error)
     * @author kstep
     */
    private $_view;

    public function __construct($message = "", $code = 0, View $view = null)
    {
        if ($view instanceof View_Exceptionable)
            $view->setError($code, $message);

        $this->_view = $view;
        parent::__construct($message, $code);
    }

    /**
     * returns view (@see $_view property)
     * @return View
     * @author kstep
     */
    final public function getView()
    {
        return $this->_view;
    }
}
?>
