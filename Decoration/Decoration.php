<?php

namespace Decoration;

class Decoration
{

    private $decorators = [];

    public function __construct()
    {
        $arguments = func_get_args();
        $this->decorators =
            array_pop($arguments)
            + array_map(
                function ($decorator) {
                    if ($decorator instanceof Decorator) {
                        return $decorator->decorator();
                    }
                },
                array_reverse($arguments)
            );
    }

    public function __toString()
    {
        return (string)$this->decorate();
    }

    public function __invoke()
    {
        return call_user_func_array([$this, 'decorate'], func_get_args());
    }

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