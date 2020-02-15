<?php

namespace Attogram\SharedMedia\Sandbox;

/**
 * Class Base
 * @package Attogram\SharedMedia\Sandbox
 */
class Base
{
    const VERSION = '1.1.7';

    /** @var array - [ [Class, Method, ArgName, RequiresIdentifiers], ...] */
    protected $methods = [];

    /** @var array - [ [name => Endpoint URL], ...] */
    protected $sources = [];

    /** @var array - [ ['foo', 'bar'], ...] = $class->foo($this->bar), ... */
    protected $preCall = [];

    /** @var Logger - PSR-3 Logger instance */
    protected $logger;

    /** @var int - PSR-3 Log Level */
    protected $logLevel;

    /** @var string - HTML Page <title> */
    protected $htmlTitle;

    /**
     * Base constructor.
     * @param string $htmlTitle
     */
    public function __construct($htmlTitle = 'Sandbox')
    {
        $this->htmlTitle = $htmlTitle;
        $this->logLevel = Tools::getGet('logLevel');
        if (!$this->logLevel) {
            $this->logLevel = 'NOTICE';
        }
        $this->logger = new Logger($this->logLevel);
    }

    /**
     *
     */
    public function play()
    {
        print $this->getHeader();
        print $this->getFooter();
    }

    /**
     * @param array $methods
     */
    public function setMethods(array $methods)
    {
        $this->methods = $methods;
    }

    /**
     * @param array $sources
     */
    public function setSources(array $sources)
    {
        $this->sources = $sources;
    }

    /**
     * @param array $preCall
     */
    public function setPreCall(array $preCall)
    {
        $this->preCall = $preCall;
    }

    /**
     * @return string
     */
    protected function getHeader()
    {
        return '<!DOCTYPE html><html><head>'
        . '<meta charset="UTF-8">'
        . '<meta name="viewport" content="initial-scale=1" />'
        . '<meta http-equiv="X-UA-Compatible" content="IE=edge" />'
        . '<style type="text/css">' . $this->getCSS() . '</style>'
        . '<title>' . $this->htmlTitle . ' / sandbox</title>'
        . '</head><body>'
        . '<h1><a href="?">' . $this->htmlTitle . '</a></h1>'
        . '<h3><a href="./">about</a></h3>'
        . '<br />';
    }

    /**
     * @return string
     */
    protected function getFooter()
    {
        return '<footer><br />' . $this->getActiveClasses() . '</footer></body></html>';
    }

    /**
     * @return string
     */
    protected function getActiveClasses()
    {
        $classes = '<textarea rows="32" cols="100" style="width:100%;">';
        $match = '#^Attogram\\\\SharedMedia\\\\#';
        foreach (preg_grep($match, get_declared_classes()) as $class) {
            $classes .= $class;
            if (defined("$class::VERSION")) {
                $classes .= "\tv" . $class::VERSION;
            }
            $refClass = new \ReflectionClass($class);
            $methods = $refClass->getMethods(\ReflectionMethod::IS_PUBLIC);
            foreach ($methods as $method) {
                $classes .= "\n\t" . $method->name;
            }
            $classes .= "\n";
        }
        return trim($classes).'</textarea>';
    }

    /**
     * @return string
     */
    protected function getCSS()
    {
        $cssFile = __DIR__ . '/../public/sandbox.css';
        if (!is_readable($cssFile)) {
            $this->logger->error('SANDOBX: getHeader: CSS File Not Readable:', [$cssFile]);
            return '';
        }
        $css = file_get_contents($cssFile);
        if (isset($css)) {
            return $css;
        }
        return '';
    }

    /**
     * @return string
     */
    protected function getLogLevelSelect()
    {
        $select = '<select name="logLevel">';
        foreach ($this->logger->getLevels() as $level) {
            $select .= '<option value="' . $level . '"' . Tools::isSelected($level, $this->logLevel) . '>'
                    . strtolower($level) . '</option>';
        }
        return $select . '</select>';
    }
}
