<?php

namespace PHPDecorator;

use InvalidArgumentException as InvalidData;

/**
 * Simulate the Python decorators in PHP
 * Class Decorator
 * @package PHPDecorator
 */
class Decorator
{

    private static $decorators = array();

    private $decoration = array();
    private $context;

    /**
     * Create new decorator
     * @param string $name the name of the future decorator
     * @param array|\Closure $function function with decorator logic
     */
    public static function add($name, $function)
    {
        if (!is_callable($function)) {
            throw new InvalidData('Decorator function is not callable');
        }
        self::$decorators[$name] = $function;
    }

    /**
     * Remove decorator from the list
     * @param string $name decorator function name
     */
    public static function remove($name)
    {
        if (self::has($name)) {
            unset(self::$decorators[$name]);
        }
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
                throw new InvalidData("Decorator $step is not defined");
            }
            $this->decoration[] = self::$decorators[$step];
        }
    }

    /**
     * Bind context for the current decoration
     * @param object|null $context applying decorators context
     * @return Decorator instance of the current decoration
     */
    public function with($context)
    {
        if (!is_object($context) && $context !== null) {
            throw new InvalidData("Context should be and object");
        }
        $this->context = $context;

        return $this;
    }

    /**
     * Is current decoration will be made with some context
     * @param string $context context class name
     * @return bool true in case of correct suggestion
     */
    public function isWith($context)
    {
        return $this->context !== null && is_a($this->context, $context, true);
    }

    /**
     * Execute decoration
     * @return mixed|null result of the target function with decoration
     */
    public function call()
    {
        $result = null;
        $arguments = func_get_args();
        foreach ($this->decoration as $key => $step) {
            if (!is_callable($step)) continue;

            if ($step instanceof \Closure && $this->context !== null) {
                $step = $step->bindTo($this->context);
            }
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
