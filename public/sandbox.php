<?php // attogram/shared-media-sandbox - sandbox.php - v1.1.3

use Attogram\SharedMedia\Sandbox\Sandbox;

$autoload = '../vendor/autoload.php';
if (!is_readable($autoload)) {
    print 'ERROR: Autoloader Not Found: ' . $autoload;
    return false;
}
require_once($autoload);

if (!class_exists('Attogram\SharedMedia\Sandbox\Sandbox')) {
    print 'ERROR: Sandbox Class Not Found';
    return false;
}

$sandbox = new Sandbox('shared-media-sandbox');

$sandbox->setMethods([
    // Class, Method, ArgName, RequiresIdentifiers
    ['Attogram\SharedMedia\Sandbox\Tools', 'safeString', 'string',   false],
    ['Attogram\SharedMedia\Sandbox\Tools', 'getGet',     '_GET',     false],
    ['Attogram\SharedMedia\Sandbox\Tools', 'hasGet',     '_GET',     false],
    ['Attogram\SharedMedia\Sandbox\Logger', 'emergency', 'message',  false],
    ['Attogram\SharedMedia\Sandbox\Logger', 'alert', 'message',  false],
    ['Attogram\SharedMedia\Sandbox\Logger', 'critical', 'message',  false],
    ['Attogram\SharedMedia\Sandbox\Logger', 'error', 'message',  false],
    ['Attogram\SharedMedia\Sandbox\Logger', 'warning', 'message',  false],
    ['Attogram\SharedMedia\Sandbox\Logger', 'notice', 'message',  false],
    ['Attogram\SharedMedia\Sandbox\Logger', 'info', 'message',  false],
    ['Attogram\SharedMedia\Sandbox\Logger', 'debug', 'message',  false],
]);

$sandbox->setSources([
    // name => Endpoint URL
    'commons'      => 'https://commons.wikimedia.org/w/api.php',
    'en.wikipedia' => 'https://en.wikipedia.org/w/api.php',
]);

$sandbox->setPreCall([
    // ['foo', 'bar'] == $class->foo($this->bar)
]);

$sandbox->play();
