<?php

namespace Attogram\SharedMedia\Sandbox;

class Sandbox
{
    const VERSION = '0.0.6';

    const DEFAULT_LIMIT = 10;

    public $methods = [];
    public $sources = [];
    public $sandboxTitle = 'shared-media-sandbox';
    public $versions = [];

    public $self;
    public $class;
    public $method;
    public $arg;
    public $endpoint;
    public $limit;
    public $logLevel;
    public $format;
    public $logger;
    public $pageids;
    public $titles;


    public function setMethods(array $methods)
    {
        $this->methods = $methods;
    }

    public function setSources(array $sources)
    {
        $this->sources = $sources;
    }

    public function setTitle(string $title)
    {
        $this->sandboxTitle = $title;
    }

    public function setVersions(array $versions)
    {
        $this->versions = $versions;
    }

    public function play()
    {
        $this->sandboxInit();
        print $this->getHeader()
            .'<br />'
            .$this->menu()
            .$this->form();
        if (Tools::hasGet('play')) {
            print $this->getResponse();
        }
        print $this->getFooter();
    }

    public function sandboxInit()
    {
        $this->self = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : null;
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
        $this->logLevel = Tools::getGet('logLevel');
        if (!$this->logLevel) {
            $this->logLevel = 'NOTICE';
        }
        $this->format = Tools::getGet('format');
        if (!$this->format) {
            $this->format = 'html';
        }
        $this->logger = new Logger($this->logLevel);
    }

    public function getHeader()
    {
        $cssFile = __DIR__.'/../public/sandbox.css';
        if (is_readable($cssFile)) {
            $css = file_get_contents($cssFile);
        }
        if (!isset($css)) {
            $css = '';
            $this->logger->error('getHeader: CSS File Not Readable:', [$cssFile]);
        }
        return '<!DOCTYPE html><html><head>'
        .'<meta charset="UTF-8">'
        .'<meta name="viewport" content="initial-scale=1" />'
        .'<meta http-equiv="X-UA-Compatible" content="IE=edge" />'
        .'<style type="text/css">'.$css.'</style>'
        .'<title>'.$this->sandboxTitle.' / sandbox</title>'
        .'</head><body><h1><a href="./">'.$this->sandboxTitle.'</a></h1>'
        .'<h2><a href="'.$this->self.'">Sandbox</a></h2>';
    }

    public function getFooter()
    {
        $padding = Tools::getLongestStringLengthInArray($this->versions);
        $foot = '<footer><hr />'
        .'<a href="./">'.$this->sandboxTitle
        .'</a> : <a href="'.$this->self.'">sandbox</a><pre>';
        foreach ($this->versions as $version) {
            $foot .= str_pad($version, $padding, ' ').' v'.$version::VERSION.'<br />';
        }
        $foot .= '</pre></footer></body></html>';
        return $foot;
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
                .'<a href="'.$this->self.'?class='.$class.'&amp;method='.$method.'">'.$method.'</a>'
                .'</div>';
            $lastClass = $class;
        }
        $menu = substr($menu, 6); // remove unmatched first </div>
        return $menu.'</div>';
    }

    public function getMethodInfo()
    {
        foreach ($this->methods as $key => $val) {
            if ($val[0] == $this->class && $val[1] == $this->method) {
                return $this->methods[$key];
            }
        }
    }

    public function form()
    {
        $action = $this->getMethodInfo();
        if (!$action) {
            return;
        }
        $form = '<form>'
            .'<input type="hidden" name="play" value="1" />'
            .'<input type="hidden" name="class" value="'.$this->class.'" />'
            .'<input type="hidden" name="method" value="'.$this->method.'" />'
            .'endpoint:'.$this->endpointSelect()
            .'&nbsp; <nobr>limit:<input name="limit" value="'.$this->limit.'" type="text" size="5" /></nobr>'
            .'&nbsp; <nobr>format:'
            .'<select name="format">'
            .'<option value="html"'.Tools::isSelected('html', $this->format).'>HTML</option>'
            .'<option value="raw"'.Tools::isSelected('raw', $this->format).'>Raw</option>'
            .'</select>'
            .'</nobr>'
            .'&nbsp; <nobr>logLevel:'
            .'<select name="logLevel">'
            .'<option value="DEBUG"'.Tools::isSelected('DEBUG', $this->logLevel).'>debug</option>'
            .'<option value="INFO"'.Tools::isSelected('INFO', $this->logLevel).'>info</option>'
            .'<option value="NOTICE"'.Tools::isSelected('NOTICE', $this->logLevel).'>notice</option>'
            .'<option value="WARNING"'.Tools::isSelected('WARNING', $this->logLevel).'>warning</option>'
            .'<option value="ERROR"'.Tools::isSelected('ERROR', $this->logLevel).'>error</option>'
            .'<option value="CRITICAL"'.Tools::isSelected('CRITICAL', $this->logLevel).'>critical</option>'
            .'<option value="ALERT"'.Tools::isSelected('ALERT', $this->logLevel).'>alert</option>'
            .'<option value="EMERGENCY'.Tools::isSelected('EMERGENCY', $this->logLevel).'">emergency</option>'
            .'</select>'
            .'</nobr>';
        if ($action[3]) { // Requires Identifier
            $form .= '<br />Identifier: '
            . 'Titles:<input name="titles" value="'.$this->titles.'" type="text" size="30" />'
            . ' OR: '
            . 'Pageids:<input name="pageids" value="'.$this->pageids.'" type="text" size="30" />';
        }
        if ($action[2]) { // Requires argument
            $form .= '<br />'.$action[2] .': <input name="arg" type="text" size="42" value="'.$this->arg.'" />';
        }
        $form .= '<br /><input type="submit" value="         '
            .$this->class.'::'.$this->method.'         "/></form>';
        return $form;
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
        $class->setPageid($this->pageids);              // Set the pageid identifier
        $class->setTitle($this->titles);                // Set the title identifier
        $class->setEndpoint($this->endpoint);           // Set the API endpoint
        $class->setLimit($this->limit);                 // Set the # of responses to get
        $results = $class->{$this->method}($this->arg); // get results as an array or arrays
        switch ($this->format) {
            case 'raw':
                $response = '<pre>'.var_dump($results, true).'</pre>'; // format for result: as PHP Array
            case 'html':
            default:
                $response = $class->format($results); // format for result: as HTML
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
}
