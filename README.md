# php-decorator

[![Build Status](https://travis-ci.org/zhikiri/php-decorator.svg?branch=master)](https://travis-ci.org/zhikiri/php-decorator)

Simulation of the Python decorators in PHP.<br>

Install with composer: <code>composer require zhikiri/php-decorator</code>

Creation of the new decorator, second parameter <b>must be</b> callable.<br>
Allow to use class methods, function names and Closure class instances (anonymous function)<br>
<pre>
Decorator::add('italic', function ($content) {
  return "< i>".$content."< /i>";
});
</pre>

Get instance of the decoration.<br>
First of all pass the list of wanted decorators to apply and the callable function.
<pre>
$decoration = new Decorator(
    'italic',
    function () {
        return 'decoration text';
    }
);
</pre>

Can be set a context for decoration, it will apply the decorators and decorating function.
<pre>$decoration->with(new StdClass())</pre>
Method return decoration instance, so chain is accepted
 
For check the decoration context can be used <code>isWith()</code> method
<pre>$decoration->isWith('stdClass')</pre>
 
For remove decoration context use <code>with()</code> method with <code>null</code> as a parameter

Decoration execution<br>
- cast to string
<pre>(string)$decoration;</pre>
- execute the Decorator instance
<pre>$decoration();</pre>
- run the Decorator call method
<pre>$decoration->call();</pre>

Result of the current decoration will be: <pre>\<i\>decoration text\</i\></pre>

For support chains in PHP < 5.4 can be used function <code>PHPDecorator/with</code><br>
<pre>
echo with(new Decorator(
    'italic',
    function () {
        return 'decoration text';
    }
))->call()
</pre>
