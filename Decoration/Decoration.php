<?php

namespace Decoration;

/**
 * Class Decoration
 * Create function decoration instance and decorate
 * @package Decoration
 */
class Decoration
{

    private $decorators = [];

    public function __construct()
    {
        $arguments = func_get_args();
        array_push($this->decorators, array_pop($arguments));
        foreach (array_reverse($arguments) as $decorator) {
            if ($decorator instanceof Decorator) {
                array_push($this->decorators, $decorator->decorator());
            }
        }
    }

    /**
     * Run decoration
     * @return string function decoration result
     */
    public function __toString()
    {
        return (string)$this->decorate();
    }

    /**
     * Execute decoration
     * @return mixed function decoration result
     */
    public function __invoke()
    {
        return call_user_func_array([$this, 'decorate'], func_get_args());
    }

    /**
     * Function decoration loop
     * @return mixed decoration result
     */
    private function decorate()
    {
        $arguments = func_get_args();

        return array_reduce(
            $this->decorators,
            function ($decorator, $acc) use ($arguments) {
                return $acc = call_user_func_array($decorator, [$acc, $arguments]);
            },
            null
        );
    }

}