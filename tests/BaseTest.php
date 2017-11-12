<?php

namespace Attogram\SharedMedia\Sandbox;

use PHPUnit\Framework\TestCase;

/**
 */
class BaseTest extends TestCase
{
    const VERSION = '1.1.2';

    /**
     */
    public function testConstruct()
    {
        $this->assertTrue(
            class_exists('\Attogram\SharedMedia\Sandbox\Base'),
            'class \Attogram\SharedMedia\Sandbox\Base not found'
        );
        $this->assertTrue(
            defined('\Attogram\SharedMedia\Sandbox\Base::VERSION'),
            'constant \Attogram\SharedMedia\Sandbox\Base::VERSION not found'
        );
        $base = new \Attogram\SharedMedia\Sandbox\Base();
        $this->assertTrue(is_object($base), 'instantiation of Base failed');
    }
}
