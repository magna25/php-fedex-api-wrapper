# php-fedex-api-wrapper
This is dead simple Fedex API Wrapper class created for those who wish to integrate automated shipping to their ecommerce website or any project.

It is built according to the Fedex Web developers guidlines.

<b>Usage</b>

The usage is extremely simple, all you need to do is open up Authentication.php and put in your credentials (Account and Meter #). In order to create labels you need to get certified by fedex and you have to do that by yourself. The basic functions like Tracking and requesting rates work with out certification.


<b>Examples</b>

examples.php has all the examples..here is a an example of tracking request..
```
require_once('autoloader.php');
use Tracking;

$obj = new Tracking;
$response = $obj->track('XXX');
```

<b>Bugs</b>

There are some functions that aren't working proplerly. For example trying to email the label to a customer instead of printing doesn't work.
