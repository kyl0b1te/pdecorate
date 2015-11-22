<?php

use \PHPDecorator\Decorator;

class DecoratorTest extends \Codeception\TestCase\Test
{

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

    public function testCreateDecorator()
    {
        Decorator::add('test', function () {});
        $this->assertTrue(Decorator::has('test'));
    }

    public function testDecorationCallMethod()
    {
        $this->assertTrue($this->getDecorator()->call() == 6);
    }

    public function testDecorationInvokeMethod()
    {
        $decorator = $this->getDecorator();
        $this->assertTrue($decorator() == 6);
    }

    public function testDecorationToStringMethod()
    {
        $decorator = $this->getDecorator();
        $this->assertTrue((string)$decorator == '6');
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

}