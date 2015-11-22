<?php

// PHP Decorator class helpers

/**
 * Make chain possible for PHP 5.3 and lower
 * @param object $instance instance of the target class
 * @return object the same instance what was passed
 */
function with($instance)
{
    return $instance;
}