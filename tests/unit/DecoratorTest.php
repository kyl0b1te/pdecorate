<?php

use \PHPDecorator\Decorator;

class DecoratorTest extends \Codeception\TestCase\Test
{

    const SOURCE_CLASS = 'PHPDecorator\Decorator';

    /**
     * @var \UnitTester
     */
    protected $tester;

    private $decorator;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testAddDecorator()
    {
        Decorator::add('fail', 1);
        Decorator::add('test', function () {});
        $this->assertEquals(
            array('test' => function () {}),
            $this->getPrivateProperty('decorators')->getValue()
        );
    }

    public function testRemoveDecorator()
    {
        Decorator::add('test', function () {});
        $this->assertEquals(
            array('test' => function () {}),
            $this->getPrivateProperty('decorators')->getValue()
        );
        Decorator::remove('test');
        $this->assertEquals(
            array(),
            $this->getPrivateProperty('decorators')->getValue()
        );
    }

    public function testHasDecorator()
    {
        Decorator::add('test', function () {});
        $this->assertTrue(Decorator::has('test'));
        $this->assertFalse(Decorator::has('fail'));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testCreateDecoration()
    {
        Decorator::add('test', function () {});
        Decorator::add('test2', function () {});

        $decoration = new Decorator('test', 'test2', function () {});
        $decorators = $this->getPrivateProperty('decoration');
        $this->assertNotEmpty($decorators->getValue($decoration));

        new Decorator('fail', function () {});
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testWithMethod()
    {
        Decorator::add('test', function () { return $this->parameter; });
        $decoration = new Decorator(
            'test',
            function () { return $this->parameter; }
        );
        $context = new StdClass();
        $context->parameter = 1;
        $this->assertEquals(
            $context->parameter,
            $decoration->with($context)->call()
        );
        $decoration->with(1);
    }

    public function isWithMethod()
    {
        Decorator::add('test', function () {  });
        $decoration = new Decorator('test', function () {  });
        $decoration->with(new StdClass());

        $this->assertTrue($decoration->isWith('stdClass'));
    }

    public function testDecorationCallMethod()
    {
        $this->assertTrue($this->getDecorator()->call() == 6);
    }

    public function testDecorationInvokeMethod()
    {
        $decorator = $this->getDecorator();
        $this->assertEquals(
            $decorator(),
            6,
            'Unexpected decoration value given by invoke'
        );
    }

    public function testDecorationToStringMethod()
    {
        $decorator = $this->getDecorator();
        $this->assertEquals(
            (string)$decorator,
            '6',
            'Unexpected decoration value given by cast decorator to string'
        );
    }

    private function getDecorator()
    {
        if (is_null($this->decorator)) {
            Decorator::add('first', function ($c) {
                return $c + 2;
            });
            Decorator::add('second', function ($c) {
                return $c * 2;
            });
            $this->decorator = new Decorator(
                'first',
                'second',
                function () {
                    return 2;
                }
            );
        }

        return $this->decorator;
    }

    /**
     * @param $name
     * @return ReflectionProperty
     */
    private function getPrivateProperty($name)
    {
        $reflection = new ReflectionClass(self::SOURCE_CLASS);
        $property = $reflection->getProperty($name);
        $property->setAccessible(true);

        return $property;
    }

}
