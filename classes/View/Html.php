<?php
class View_Html extends View_Http
{
	protected $_templates_path = VIEWS_PATH;

    /**
     * @var array DOCTYPEs definitions for convinience in format:
     * "doctype name" => array("strict doctype definition", "less strict doctype definition", ...)
     * @author kstep
     */
    protected $_doctypes = array(
        "xhtml" => array(
            '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">',
            '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
        ),
        "html" => array(
            '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">',
            '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">',
        ),
        "xhtml11" => array(
            '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">',
        ),
    );

	public function setTemplate($filename)
	{
        if (strpos($filename, '.') === false)
            $filename .= '.phtml';
		parent::setTemplate($filename);
	}

    /*** HTML helpers ***/
    /**
     * put doctype header for specific document type
     * @param string document type (html, xhtml...)
     * @param int level of strictiness of doctype definition
     * @return void
     * @author kstep
     */
    public function doctype($type, $level = 0)
    {
        return $this->_doctypes[$type][$level]."\n";
    }

    private function parseHtmlAttrs($attrs)
    {
        if (is_array($attrs))
        {
            $result = '';
            foreach ($attrs as $name => $value)
                $result .= ' ' . htmlentities($name) . '="' . htmlentities($value) . '"';
            return $result;
        } else {
            return ' ' . (string)$attrs;
        }
    }

    /**
     * builds <option>'s list for <select> tag
     * @param array value => title pairs
     * @param string default value
     * @return string
     * @author kstep
     */
    public function options($options, $default = null)
    {
        $result = "";
        if ($default instanceof Model) $default = $default->getId();
        foreach ($options as $value => $title)
        {
            if (is_array($title))
            {
                $result .= "<optgroup label=\"$value\">" . $this->options($title, $default) . "</optgroup>";
            } else {
                if ($title instanceof Model) $value = $title->getId();
                if ($options instanceof TreeIterator) $title = str_repeat("&nbsp;&nbsp;&nbsp;", $options->getLevel()) . $title;
                $isdef = (is_array($default)? in_array($value, $default): $default == $value)? " selected=\"selected\"": "";
                $result .= "<option value=\"$value\"$isdef>$title</option>";
            }
        }
        return $result;
    }

    /**
     * builds <select> tag
     * @param string <select>'s name
     * @param array <option>'s definitions
     * @param string default value
     * @param mixed string or array of <select>'s optional HTML attributes
     * @return void
     * @author kstep
     */
    public function select($name, $options, $default = null, $label = "", $nulloption = false, $attrs = "")
    {
        $attrs = $this->parseHtmlAttrs($attrs);
        if (is_array($default))
        {
            $attrs .= ' multiple="multiple"';
            $name  .= '[]';
        }
        $result = "";
        if ($label)
            $result = "<label for=\"$name\">$label</label> ";
        $result .= "<select name=\"$name\" id=\"$name\"$attrs>" .($nulloption === false? '': "<option value=\"\">$nulloption</option>"). $this->options($options, $default) . "</select>";
        return $result;
    }

    /**
     * build list of radios or checkboxes input
     * @param string common name of group
     * @param array option's definitions: value => label pairs
     * @param bool if true it's a radios list, checkboxs list otherwise
     * @param mixed string or array of value(s) set by default
     * @return void
     * @author kstep
     */
    public function checklist($name, $options, $is_radio = false, $default = null)
    {
        $result = "";
        $id     = $name;
        if ($is_radio)
        {
            $type = "radio";
        }
        else
        {
            $type = "checkbox";
            $name .= "[]";
        }
        if (!is_array($default)) $default = array( $default );
        foreach ($options as $value => $label)
        {
            $isdef = in_array($value, $default)? " checked=\"checked\"": "";
            $result .= "<input type=\"$type\" name=\"$name\" id=\"{$id}_{$value}\" value=\"$value\"$isdef /> <label for=\"{$id}_{$value}\">$label</label>";
            $id++;
        }
		return $result;
        //return '<ol>'.$result.'</ol>';
    }

    /**
     * builds HTML table from array
     * @param array of arrays: table data, one array per row
     * @param array of headers
     * @param mixed string or array of additional table attributes 
     * @param mixed same as $attrs but applied to add table rows
     * @return void
     * @author kstep
     */
    public function table($data, $header = null, $attrs = "", $odd_rows = "")
    {
        $attrs    = $this->parseHtmlAttrs($attrs);
        $odd_rows = $this->parseHtmlAttrs($odd_rows);

        $result = "<table$attrs>";
        if ($header)
        {
            $result .= "<thead>";
            foreach ($header as $title)
            {
                $result .= "<th>" . htmlentities($title) . "</th>";
            }
            $result .= "</thead>";
        }
        $result .= "<tbody>";
        $num = true;
        foreach ($data as $row)
        {
            $result .= ($num = !$num)? "<tr>": "<tr$odd_rows>";
            foreach ($row as $cell)
            {
                $result .= "<td>" . htmlentities($cell) . "</td>";
            }
            $result .= "</tr>";
        }
        $result .= "</tbody></table>";
        return $result;
    }

    /**
     * builds HTML list from array
     * @param array each element is a list item
     * @param string href template
     * @param bool ordered or not, default is false
     * @param mixed string or array of additional list attributes
     */
    public function alist($data, $hreftmpl = "", $current = null, $attrs = "", $curr_attrs = "", $ordered = false)
    {
        $attrs      = $this->parseHtmlAttrs($attrs);
        $curr_attrs = $this->parseHtmlAttrs($curr_attrs);
        $type       = $ordered? "ol": "ul";

        $result = "";
        foreach ($data as $item)
        {
            $html = $hreftmpl? '<a href="' . str_replace('#id#', $item->getId(), $hreftmpl) . '">' . (string)$item . '</a>': (string)$item;
            $result .= $current == $item->getId()? "<li$curr_attrs>$html</li>": "<li>$html</li>";
            if (is_array($item) or $item instanceof Iterator or $item instanceof IteratorAggregate)
                $result .= $this->alist($item, $hreftmpl, $current, $attrs, $curr_attrs, $ordered) . "</li>";
            $result .= "</li>";
        }
        return $result? "<$type$attrs>$result</$type>": "";
    }

    /**
     * builds general container (<div> or something)
     * @param string data to put into container
     * @param string container tag
     * @param mixed string or array of additional HTML attributes
     * @return string
     * @author kstep
     */
    public function div($data, $type = "div", $attrs = "")
    {
        $attrs = $this->parseHtmlAttrs($attrs);
        return "<$type$attrs>$data</$type>";
    }

    /**
     * builds text input
     * @param string input name
     * @param string default value
     * @param string optional label to the left of input
     * @param bool if true, it's password input
     * @param mixed array or string of additional HTML attributes
     * @return void
     * @author kstep
     */
    public function input($name, $default = "", $label = "", $rows = 1, $cols = 0, $attrs = "")
    {
        $attrs = $this->parseHtmlAttrs($attrs);
		$cols = (int)$cols;
		$default = htmlspecialchars($default);

        if ($rows > 1)
            $result = "<textarea name=\"$name\" id=\"$name\" rows=\"$rows\" cols=\"$cols\"$attrs>$default</textarea>";
        else
            $result = "<input type=\"".($rows < 0? "password": "text")."\" name=\"$name\" id=\"$name\"".($cols > 0? " size=\"$cols\"": '')." value=\"$default\"$attrs />";

        if ($label)
            $result = "<label for=\"$name\">$label</label> $result";
        return $result;
    }

    public function hidden($name, $value)
    {
        return "<input type=\"hidden\" name=\"$name\" value=\"$value\" />";
    }

    /**
     * builds standalone checkbox input
     * @param string input name
     * @param bool if true it's checked by default
     * @param string optional lable to the right of checkbox
     * @param mixed array or string of additional HTML attributes
     * @return void
     * @author kstep
     */
    public function checkbox($name, $checked = false, $label = "", $value = "1", $attrs = "")
    {
        $attrs = $this->parseHtmlAttrs($attrs);
        $ischecked = $checked? " checked=\"checked\"": "";
        $result = "<input type=\"checkbox\" name=\"$name\" value=\"$value\" id=\"$name\"$ischecked$attrs />";
        if ($label)
            $result .= "<label for=\"$name\">$label</label>";
        return $result;
    }

    /**
     * builds a set of controls to select a date
     * @param string input base name, will be appended with "_day", "_mon" & "_year"
     * suffixes for day, month & year selection controls.
     * @param integer unix timestamp 
     * @param string label
     * @param string what parts to include: d/m/y = day/month/year, h/s = hour:min/(with seconds)
     * @return void
     * @author kstep
     */
    public function selectDate($name, $default = null, $label = null, $include = null)
    {
        if ($include === null) $include = "dmy";
        $time = $default === null? localtime(): localtime($default);
        $hour = $time[2];
        $min  = $time[1];
        $sec  = $time[0];
        $day  = $time[3];
        $mon  = $time[4] + 1;
        $year = $time[5] + 1900;

        $result = "";
        if (substr($include, "d") !== false) $result .= $this->input($name . "_day", $day, null, 1, 2);
        if (substr($include, "m") !== false) $result .= $this->select($name . "_mon", array("---", "января", "февраля", "марта", "апреля", "мая", "июня", "июля", "августа", "сентября", "октября", "ноября", "декабря"), $mon);
        if (substr($include, "y") !== false) $result .= $this->input($name . "_year", $year, null, 1, 4);
        if (substr($include, "h") !== false)
        {
            $result .= ", " . $this->input($name . "_hour", $hour, null, 1, 2)
                    .":" . $this->input($name . "_min", $min, null, 1, 2);
            if (substr($include, "s") !== false)
                $result .= ":" . $this->input($name . "_sec", $sec, null, 1, 2);
        }

        if ($label)
            $result = "<label for=\"{$name}_day\">$label</label> $result";
        return $result;
    }

    /*** /HTML helpers ***/

    /**
     * defines pages' charset, must be called at the begginning of template
     * page, before any real output to stdout, because it's using HTTP
     * Content-Type header to define charset.
     * @param string charset name
     * @return void
     * @author kstep
     */
    public function charset($charset)
    {
        if (!headers_sent())
            header("Content-Type: text/html; charset=$charset");
        /*else
            echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=$charset\" />";*/
    }

    /**
     * conviniece method to construct XHTML correctly <link /> tag to include CSS stylesheet.
     * @param string CSS file base name
     * @return string
     */
    public function style($css, $media = "")
    {
		if ($media) $media = ' media="'.(is_array($media)? implode(', ', $media): $media).'"';
		else $media = '';
        return "<link type=\"text/css\" rel=\"stylesheet\"$media href=\"{$this->_prefix}/static/styles/$css.css\" />";
    }

    /**
     * conviniece method to construct XHTML correctly <script></script> tag include JavaScript file.
     * @param string JavaScript file base name
     * @return string
     */
    public function script($script)
    {
        return "<script type=\"text/javascript\" src=\"{$this->_prefix}/static/scripts/$script.js\"></script>";
    }

    public function tinymce(array $settings = null)
    {
        if ($settings === null) $settings = array( "mode" => "textareas", "theme" => "advanced", "language" => "ru" );
        return '<script type="text/javascript">tinyMCE.init('.json_encode($settings).');</script>';
    }

    public function scaffold(Model $model, $action = "", $variants = array(), $attrs = null)
    {
        $attrs  = $this->parseHtmlAttrs($attrs);
        $result = "<form method=\"post\" action=\"$action\"$attrs><ol>";
        $fields = $model->getAttributes(true);

        $result .= $this->hidden("id", $model->getId());
        foreach ($fields as $name => $type)
        {
            if ($variants[$name] === false) {
                $result .= $this->hidden($name, $model->$name);
                continue;
            }
            $label = ucfirst(strtolower(str_replace("_", " ", preg_replace("/([A-Z])/", " $1", $name))));
            switch ($type)
            {
                case Model::TYPE_INTEGER:
                case Model::TYPE_SET:
                case Model::TYPE_ENUM:
                    if (is_array($variants[$name]) || $variants[$name] instanceof Iterator || $variants[$name] instanceof IteratorAggregate)
                    {
                        $result .= "<li>" . $this->select($name, $variants[$name], $model->$name, $label) . "</li>";
                    }
                    else
                    {
                        $result .= "<li>" . $this->input($name, $model->$name, $label, $variants[$name]) . "</li>";
                    }
                    break;
                case Model::TYPE_FLOAT:
                    $result .= "<li>" . $this->input($name, $model->$name, $label, $variants[$name]) . "</li>";
                    break;
                case Model::TYPE_STRING:
                    $result .= "<li>" . $this->input($name, $model->$name, $label, $variants[$name], 80) . "</li>";
                    break;
                case Model::TYPE_BOOLEAN:
                    $result .= "<li>" . $this->checkbox($name, $model->$name, $label) . "</li>";
                    break;
                case Model::TYPE_TIMESTAMP:
                    $date = localtime($model->$name);
                    $result .= "<li>" . $this->selectDate($name, $model->$name, $label, "dmyhs") . "</li>";
                    break;
            }
        }
        $result .= "<li class=\"reset\"><input type=\"reset\" value=\"Reset\" /></li>";
        $result .= "<li class=\"submit\"><input type=\"submit\" value=\"Save\" /></li>";
        $result .= "</ol></form>";
        return $result;
    }

    public function meta($name, $value = null, $hteq = false)
    {
        $attr = $hteq? "http-equiv": "name";
        if (is_array($name))
        {
            $result = "";
            foreach ($name as $key => $val)
            {
                $result .= "<meta $attr=\"$key\" content=\"$val\" />";
            }
        }
        else
        {
            $result = "<meta $attr=\"$name\" content=\"$value\" />";
        }
        return $result;
    }
	public function htmeta($name, $value = null)
	{
		return $this->meta($name, $value, true);
	}
}
?>
