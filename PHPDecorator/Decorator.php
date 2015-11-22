<?php

namespace PHPDecorator;

/**
 * Simulate the Python decorators in PHP
 * Class Decorator
 * @package PHPDecorator
 */
class Decorator
{

    private static $decorators = array();

    private $decoration = array();

    /**
     * Create new decorator
     * @param string $name the name of the future decorator
     * @param array|\Closure $function function with decorator logic
     */
    public static function add($name, $function)
    {
        if (!is_callable($function)) {
            throw new \InvalidArgumentException('Decorator function is not callable');
        }
        self::$decorators[$name] = $function;
    }

    /**
     * Check is the decorator exist or not
     * @param string $name target decorator name
     * @return bool return true if decorator exist
     */
    public static function has($name)
    {
        return isset(self::$decorators[$name]);
    }

    /**
     * Set the decorators list
     */
    public function __construct()
    {
        $arguments = func_get_args();
        $this->decoration[] = array_pop($arguments);

        $arguments = array_reverse($arguments);
        foreach ($arguments as $step) {
            if (!isset(self::$decorators[$step])) {
                throw new \InvalidArgumentException("Decorator $step is not defined");
            }

            $this->decoration[] = self::$decorators[$step];
        }
    }

    /**
     * Execute decoration
     * @return mixed|null result of the target function with decoration
     */
    public function call()
    {
        $arguments = func_get_args();
        $result = null;
        foreach ($this->decoration as $key => $step) {
            if (!is_callable($step)) continue;

            $result = (!$key
                ? call_user_func_array($step, $arguments)
                : call_user_func($step, $result, $arguments)
            );
        }

        return $result;
    }

    /**
     * Cast decoration result to string
     * @return string decoration result
     */
    public function __toString()
    {
        return (string)$this->call();
    }

    /**
     * Execute the function decoration
     * @return mixed|null function decoration result
     */
    public function __invoke()
    {
        return $this->call();
    }

}
