<?php

namespace Tests;

use Pdecorate\Pdecorate;

class TestCase extends \PHPUnit\Framework\TestCase
{
    public function assertStaticPropertyEqual($property, $expected)
    {
        $class = new \ReflectionClass(Pdecorate::class);
        $properties = $class->getStaticProperties();
        $this->assertEquals($expected, $properties[$property]);
    }
}