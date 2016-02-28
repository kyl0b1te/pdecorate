<?php

use Codeception\Util\Stub;
use Decoration\Decoration;
use Decoration\Decorator;

class TestDecorator implements Decorator
{

    private $increment;

    public function __construct($increment)
    {
        $this->increment = (int)$increment;
    }

    public function decorator()
    {
        return function ($result, $arguments) {
            return (int)$result + $this->increment + array_sum($arguments);
        };
    }

}

class DecorationTest extends \Codeception\TestCase\Test
{

   /**
    * @var \CodeGuy
    */
    protected $codeGuy;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testDecoration()
    {
        $decoration = $this->getDecoration();
        $decorators = $this->getProperty($decoration, 'decorators');

        $this->assertCount(4, $decorators);

        $decorator = array_shift($decorators);
        $this->assertEquals(4, call_user_func($decorator));

        $accumulator = 4;
        foreach ($decorators as $decorator) {
            $this->assertEquals(
                $accumulator,
                call_user_func_array($decorator, [1, []])
            );
            $accumulator--;
        }
    }

    public function testStringifyMethod()
    {
        $this->assertEquals(10, (string)$this->getDecoration());
    }

    public function testInvokeMethod()
    {
        $decoration = $this->getDecoration();

        $this->assertEquals(10, $decoration());

        $this->assertEquals(13, $decoration(1));

        $this->assertEquals(16, $decoration(1, 1));
    }

    public function testDecorateMethod()
    {
        $decoration = $this->getDecoration();
        $this->assertEquals(10, $this->getMethod($decoration, 'decorate'));
    }

    private function getDecoration()
    {
        return new Decoration(
            new TestDecorator(1),
            new TestDecorator(2),
            new TestDecorator(3),
            function () {
                return 4;
            }
        );
    }

    private function getMethod($object, $name)
    {
        $method = (new ReflectionObject($object))->getMethod($name);
        $method->setAccessible(true);

        return $method->invoke($object);
    }

    private function getProperty($object, $name)
    {
        $property = (new ReflectionObject($object))->getProperty($name);
        $property->setAccessible(true);

        return $property->getValue($object);
    }

}