<?php

namespace Decoration;

/**
 * Interface Decorator
 * Structure for define decorators
 * @package Decoration
 */
interface Decorator
{

    /**
     * Get the decorator action
     * @return \Closure decorator action
     */
    public function decorator();

}