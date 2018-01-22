<?php

namespace Pdecorate;

class Pdecorate
{
    private static $fns = [];

    private $decorators = [];

    public function __construct(...$arguments)
    {
        $decorating = array_pop($arguments);
        $names = array_filter($arguments, [Pdecorate::class, 'has']);

        $this->decorators = array_merge(
            [$decorating],
            array_map(function ($name) {
                return self::$fns[$name];
            }, $names)
        );
    }

    public static function add($name, $fn)
    {
        if (!is_callable($fn)) {
            throw new \InvalidArgumentException('Decorator function is not callable');
        }
        self::$fns[$name] = $fn;
    }

    public static function remove($name)
    {
        unset(self::$fns[$name]);
    }

    public static function has($name)
    {
        return isset(self::$fns[$name]);
    }

    public function __toString()
    {
        return (string)$this->call();
    }

    public function call(...$arguments)
    {
        return array_reduce(
            $this->decorators,
            function ($carry, $fn) use ($arguments) {
                return $carry = $carry === null
                    ? call_user_func($fn, ...$arguments)
                    : call_user_func($fn, $carry, ...$arguments);
            },
            null
        );
    }

    public function __invoke(...$arguments)
    {
        return $this->call(...$arguments);
    }
}