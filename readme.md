# pdecorate

[![CircleCI](https://circleci.com/gh/zhikiri/pdecorate/tree/master.svg?style=svg)](https://circleci.com/gh/zhikiri/pdecorate/tree/master)

Simulation of the Python decorators in PHP

Install with composer: `composer require zhikiri/pdecorate`

## Description

Creation of the new decorator, second parameter *must be* callable.

Allow to use class methods, function names and Closure class instances (anonymous function)

```php
Pdecorate::add('italic', function ($content) {
  return "<i>{$content}</i>";
});
```

Get instance of the decoration

First of all pass the decorators and the last parameter <b>must be</b> the callable function.

```php
$decoration = new Decorator(
    'italic',
    function () {
        return 'decoration text';
    }
);
```

Decoration execution:
- cast to string `(string)$decoration`
- execute the Decorator instance `$decoration()`
- run the Decorator call method `$decoration->call()`

Result of the current decoration will be: `<i>decoration text</i>`