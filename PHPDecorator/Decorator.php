<?php

namespace PHPDecorator;

class Decorator
{

    private static $decorators = array();

    public static function add($name, $function)
    {
        if (!is_callable($function)) {
            throw new \InvalidArgumentException('Decorator function is not callable');
        }
        self::$decorators[$name] = function () use ($function) {
            return call_user_func_array($function, array());
        };
    }

    public static function decorate()
    {
        $arguments = func_get_args();
        $to_decorate = array_pop($arguments);
        if (!is_callable($to_decorate)) {
            throw new \InvalidArgumentException('Function to decorate is not callable');
        }

        return function () {};
    }

}

Decorator::add('italic', function ($fn) {

    return '<i>'.$fn().'</i>';

});

Decorator::add('bold', function ($fn) {

    return '<b>'.$fn().'</b>';
});

$decorated = Decorator::decorate(
    'italic',
    'bold',
    function () {
        return 'hello world';
    }
);
