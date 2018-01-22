<?php

namespace Tests;

use Pdecorate\Pdecorate;

class PdecorateClassTest extends TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Decorator function is not callable
     */
    public function testAddNumberDecorator()
    {
        Pdecorate::add('1', 1);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Decorator function is not callable
     */
    public function testAddStringDecorator()
    {
        Pdecorate::add('1', '1');
    }

    public function testAddValidDecorator()
    {
        Pdecorate::add('valid', 'strtoupper');
        $this->assertStaticPropertyEqual('fns', ['valid' => 'strtoupper']);
    }

    public function testHasDecorator()
    {
        Pdecorate::add('valid', 'strtoupper');
        $this->assertStaticPropertyEqual('fns', ['valid' => 'strtoupper']);
        $this->assertTrue(Pdecorate::has('valid'));
        $this->assertFalse(Pdecorate::has('not_valid'));
    }

    public function testRemoveDecorator()
    {
        Pdecorate::add('valid', 'strtoupper');
        $this->assertStaticPropertyEqual('fns', ['valid' => 'strtoupper']);
        Pdecorate::remove('valid');
        $this->assertStaticPropertyEqual('fns', []);
    }

    public function testCreateDecoration()
    {
        Pdecorate::add('up', 'strtoupper');
        $decoration = new Pdecorate('up', function () {
            return 'test';
        });
        $this->assertEquals('TEST', $decoration());
        $this->assertEquals('TEST', (string)$decoration);
    }

    public function testComplexDecoration()
    {
        Pdecorate::add('up', 'strtoupper');
        Pdecorate::add('split', 'str_split');
        Pdecorate::add('wrap', function ($carry) {
            return array_map(function ($i) {
                return "_$i";
            }, $carry);
        });
        $decoration = new Pdecorate('up', 'split', 'wrap', function () {
            return 'test';
        });
        $this->assertEquals(['_T', '_E', '_S', '_T'], $decoration());
    }

    public function testDecorationWithArguments()
    {
        function sum($a, $b) {
            return $a + $b;
        }
        Pdecorate::add('html', function ($carry) {
            return "<i>{$carry}</i>";
        });
        $decoration = new Pdecorate('html', 'Tests\sum');
        $this->assertEquals('<i>5</i>', $decoration(2, 3));
    }
}