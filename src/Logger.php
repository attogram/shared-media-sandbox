<?php

namespace Attogram\SharedMedia\Sandbox;

use Psr\Log\AbstractLogger;

/**
 * Simple PSR3 Logger
 */
class Logger extends AbstractLogger
{
    const VERSION = '1.1.5';

    /** @var string - Log Level */
    private $level;

    /** @var int - Level Key */
    private $levelKey;

    /** @var array - Log Levels */
    private $levels = [
        0 => 'EMERGENCY',
        1 => 'ALERT',
        2 => 'CRITICAL',
        3 => 'ERROR',
        4 => 'WARNING',
        5 => 'NOTICE',
        6 => 'INFO',
        7 => 'DEBUG'
    ];

    /**
     * Logger constructor.
     * @param string|null $level
     */
    public function __construct($level = null)
    {
        $this->setLevel($level);
    }

    /**
     * @param string|null $level
     */
    public function setLevel($level = null)
    {
        if (!$level || !is_string($level) || !in_array(strtoupper($level), $this->levels)) {
            $this->level = null;
            return;
        }
        $this->level = strtoupper($level);
        $this->levelKey = array_search($this->level, $this->levels);
    }

    /**
     * @param $level
     * @return bool
     */
    private function isLevel($level)
    {
        $currentLevelKey = array_search(strtoupper($level), $this->levels);
        if ($currentLevelKey <= $this->levelKey) {
            return true;
        }
        return false;
    }

    /**
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     */
    public function log($level, $message, array $context = [])
    {
        if (!$this->isLevel($level)) {
            return;
        }
        $level = strtoupper($level);
        $out = '<div class="log log' . $level . '">'."$level: $message";
        if (!empty($context)) {
            $out .= ' ' . htmlentities(json_encode($context));
        }
        $out .= '</div>';
        print $out;
    }

    /**
     * @return array
     */
    public function getLevels()
    {
        return $this->levels;
    }
}
