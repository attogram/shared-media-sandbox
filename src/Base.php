<?php

namespace Attogram\SharedMedia\Sandbox;

class Base
{
    const VERSION = '1.1.2';

    /**
     * [ [Class, Method, ArgName, RequiresIdentifiers], ...]
     */
    protected $methods = [];

    /**
     * [ [name => Endpoint URL], ...]
     */
    protected $sources = [];

    /**
     * [ ['foo', 'bar'], ...] = $class->foo($this->bar), ...
     */
    protected $preCall = [];

    /**
     * PSR-3 Logger instance
     */
    protected $logger;

    /**
     * PSR-3 Log Level
     */
    protected $logLevel;

    /**
     * HTML Page <title>
     */
    protected $htmlTitle;


    public function __construct($htmlTitle = 'Sandbox')
    {
        $this->htmlTitle = $htmlTitle;
        $this->logLevel = Tools::getGet('logLevel');
        if (!$this->logLevel) {
            $this->logLevel = 'NOTICE';
        }
        $this->logger = new Logger($this->logLevel);
    }

    public function play()
    {
        print $this->getHeader();
        print $this->getFooter();
    }

    public function setMethods(array $methods)
    {
        $this->methods = $methods;
    }

    public function setSources(array $sources)
    {
        $this->sources = $sources;
    }

    public function setPreCall(array $preCall)
    {
        $this->preCall = $preCall;
    }

    protected function getHeader()
    {
        $head = '<!DOCTYPE html><html><head>'
        .'<meta charset="UTF-8">'
        .'<meta name="viewport" content="initial-scale=1" />'
        .'<meta http-equiv="X-UA-Compatible" content="IE=edge" />'
        .'<style type="text/css">'.$this->getCSS().'</style>'
        .'<title>'.$this->htmlTitle.' / sandbox</title>'
        .'</head><body>'
        .'<h1><a href="">'.$this->htmlTitle.'</a></h1>'
        .'<h3><a href="./">about</a></h3>'
        .'<br />';
        return $head;
    }

    protected function getFooter()
    {
        $foot = '<hr /></body></html>';
        return $foot;
    }

    protected function getCSS()
    {
        $cssFile = __DIR__.'/../public/sandbox.css';
        if (!is_readable($cssFile)) {
            $this->logger->error('SANDOBX: getHeader: CSS File Not Readable:', [$cssFile]);
            return;
        }
        $css = file_get_contents($cssFile);
        if (isset($css)) {
            return $css;
        }
    }

    protected function getLogLevelSelect()
    {
        $select = '<select name="logLevel">'
        .'<option value="DEBUG"'.Tools::isSelected('DEBUG', $this->logLevel).'>debug</option>'
        .'<option value="INFO"'.Tools::isSelected('INFO', $this->logLevel).'>info</option>'
        .'<option value="NOTICE"'.Tools::isSelected('NOTICE', $this->logLevel).'>notice</option>'
        .'<option value="WARNING"'.Tools::isSelected('WARNING', $this->logLevel).'>warning</option>'
        .'<option value="ERROR"'.Tools::isSelected('ERROR', $this->logLevel).'>error</option>'
        .'<option value="CRITICAL"'.Tools::isSelected('CRITICAL', $this->logLevel).'>critical</option>'
        .'<option value="ALERT"'.Tools::isSelected('ALERT', $this->logLevel).'>alert</option>'
        .'<option value="EMERGENCY'.Tools::isSelected('EMERGENCY', $this->logLevel).'">emergency</option>'
        .'</select>';
        return $select;
    }
}
