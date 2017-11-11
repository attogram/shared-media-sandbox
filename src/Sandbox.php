<?php

namespace Attogram\SharedMedia\Sandbox;

class Sandbox extends Base
{
    const VERSION = '1.1.1';

    const DEFAULT_LIMIT = 10;

    public $class;
    public $method;
    public $arg;
    public $endpoint;
    public $limit;
    public $format;
    public $logger;
    public $pageids;
    public $titles;
    public $action = [];

    public function __construct($htmlTitle = 'Sandbox')
    {
        parent:: __construct($htmlTitle);
        $this->endpoint = Tools::getGet('endpoint');
        $this->class = Tools::getGet('class');
        $this->method = Tools::getGet('method');
        $this->arg = Tools::getGet('arg');
        $this->pageids = Tools::getGet('pageids');
        $this->titles = Tools::getGet('titles');
        $this->limit = Tools::getGet('limit');
        if (!$this->limit) {
            $this->limit = self::DEFAULT_LIMIT;
        }
        $this->format = Tools::getGet('format');
        if (!$this->format) {
            $this->format = 'html';
        }
    }

    public function play()
    {
        print $this->getHeader()
            .$this->menu()
            .$this->form();
        if (Tools::hasGet('play')) {
            print $this->getResponse();
        }
        print $this->getFooter();
    }

    public function menu()
    {
        $lastClass = null;
        $menu = '';
        foreach ($this->methods as list($class, $method)) {
            if ($lastClass != $class) {
                $menu .= '</div><div class="menubox">'.$class.'::';
            }
            $menu .= '<div class="menu">'
                .'<a href="?class='.$class.'&amp;method='.$method.'">'.$method.'</a>'
                .'</div>';
            $lastClass = $class;
        }
        $menu = substr($menu, 6); // remove unmatched first </div>
        return $menu.'</div>';
    }

    public function form()
    {
        if (!$this->class || !$this->method) {
            return;
        }
        $form = '<form><input type="hidden" name="play" value="1" />endpoint:'.$this->endpointSelect()
            .'<input type="hidden" name="method" value="'.$this->method.'" />'
            .'&nbsp; <nobr>limit:<input name="limit" value="'.$this->limit.'" type="text" size="5" /></nobr>'
            .'&nbsp; <nobr>format:<select name="format">'
            .'<option value="html"'.Tools::isSelected('html', $this->format).'>HTML</option>'
            .'<option value="raw"'.Tools::isSelected('raw', $this->format).'>Raw</option>'
            .'</select></nobr>&nbsp; <nobr>logLevel:'
            .$this->getLogLevelSelect()
            .'</nobr><input type="hidden" name="class" value="'.$this->class.'" />';
        if (isset($this->action[3]) && $this->action[3]) { // Requires Identifier
            $form .= '<br />Identifier: Titles:<input name="titles" value="'.$this->titles.'" type="text" size="30" />'
            . ' OR: Pageids:<input name="pageids" value="'.$this->pageids.'" type="text" size="30" />';
        }
        if (isset($this->action[2]) && $this->action[2]) { // Requires argument
            $form .= '<br />'.$this->action[2] .': <input name="arg" type="text" size="42" value="'.$this->arg.'" />';
        }
        return $form.'<br /><input type="submit" value="        '.$this->class.'::'.$this->method.'        "/></form>';
    }

    public function endpointSelect()
    {
        $select = '<select name="endpoint">';
        foreach ($this->sources as $source) {
            $selected = '';
            if (isset($this->endpoint) && $this->endpoint == $source) {
                $selected = ' selected ';
            }
            $select .= '<option value="'.$source.'"'.$selected.'>'.$source.'</option>';
        }
        $select .= '</select>';
        return $select;
    }

    public function getResponse()
    {
        $action = $this->getMethodInfo();               // get status of Class::method
        if (!$action) {
            return 'SANDBOX ERROR: Class::method not allowed';
        }
        if ($action[2] && !$this->arg) {
            return 'SANDBOX ERROR: Missing Arg: '.$action[2];
        }
        $class = $this->getClass();                     // get the requested class
        if (!is_callable([$class, $this->method])) {
            return 'SANDBOX ERROR: Class::method not found';
        }
        $this->logger->debug('SANDBOX: class: '.get_class($class));
        foreach ($this->preCall as list($method, $arg)) {
            $this->logger->debug("SANDBOX: preCall: class.$method(this.$arg)", [$this->{$arg}]);
            $class->{$method}($this->{$arg});
        }
        $results = $class->{$this->method}($this->arg); // get results as an array of arrays
        $this->logger->debug('SANDBOX: results:', [$results]);
        return $this->getResponseFormat($results, $class);
    }

    protected function getResponseFormat($results, $class)
    {
        $this->logger->debug('SANDBOX: getResponseFormat:', [$this->format]);
        switch ($this->format) {
            case 'raw':                                 // format for result: as PHP Array
                $response = '<pre>'.var_dump($results, true).'</pre>';
                break;
            case 'html':                                // format for result: as HTML string
            default:
                $response = $class->format($results);
                break;
        }
        return $response;
    }

    public function getClass()
    {
        $classNames = array_unique(array_column($this->methods, '0'));
        if (!in_array($this->class, $classNames)) {
            $this->logger->critical('getClass: Class Denied:', [$this->class]);
            return false;
        }
        if (!class_exists($this->class)) {
            $this->logger->critical('getClass: Class Not Found:', [$this->class]);
            return false;
        }
        return new $this->class($this->logger);
    }

    public function getMethodInfo()
    {
        foreach ($this->methods as $key => $val) {
            if ($val[0] == $this->class && $val[1] == $this->method) {
                return $this->methods[$key];
            }
        }
    }

    public function setMethods(array $methods)
    {
        $this->methods = $methods;
        $this->action = $this->getMethodInfo();
    }
}
