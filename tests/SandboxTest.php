<?php

namespace Attogram\SharedMedia\Sandbox;

use PHPUnit\Framework\TestCase;

/**
 */
class SandboxTest extends TestCase
{
    const VERSION = '1.1.0';

    /**
     */
    public function testConstruct()
    {
        $this->assertTrue(
            class_exists('\Attogram\SharedMedia\Sandbox\Sandbox'),
            'class \Attogram\SharedMedia\Sandbox\Sandbox not found'
        );
        $this->assertTrue(
            defined('\Attogram\SharedMedia\Sandbox\Sandbox::VERSION'),
            'constant \Attogram\SharedMedia\Sandbox\Sandbox::VERSION not found'
        );
        $sandbox = new \Attogram\SharedMedia\Sandbox\Sandbox();
        $this->assertTrue(is_object($sandbox), 'instantiation of Sandbox failed');
    }
}
