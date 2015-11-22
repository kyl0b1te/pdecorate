# php-decorator
<img src="https://scrutinizer-ci.com/g/zhikiri/php-decorator/badges/build.png?b=master">
<img src="https://scrutinizer-ci.com/g/zhikiri/php-decorator/badges/quality-score.png?b=master">

Simulation of the Python decorators in PHP.<br>

Creation of the new decorator, second parameter <b>must be</b> callable.<br>
Allow to use class methods, function names and Closure class instances (anonymous function)<br>
<code>
Decorator::add('italic', function ($content) {
  return '\<i\>'.$content.'\</i\>';
});
</code>

Get instance of the decoration.<br>
First of all pass the decorators and the last parameter <b>must be</b> the callable function.
<pre>
$decoration = new Decorator(
    'italic',
    function () {
        return 'decoration text';
    }
);
</pre>

Decoration execution<br>
- cast to string
<pre>echo $decoration;</pre>
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
