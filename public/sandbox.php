<?php // attogram/shared-media-sandbox - sandbox.php - v1.1.0

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

$sandbox = new Sandbox('Sandbox Sandbox');

$sandbox->setMethods([
    // Class, Method, ArgName, RequiresIdentifiers
    ['Attogram\SharedMedia\Sandbox\NullObject', 'METHOD_A', 'ARG_1', true],
    ['Attogram\SharedMedia\Sandbox\NullObject', 'METHOD_B', 'ARG_2', false],
    ['Attogram\SharedMedia\Sandbox\NullObject', 'METHOD_C', false,   true],
    ['Attogram\SharedMedia\Sandbox\NullObject', 'METHOD_D', false,   false],
]);

$sandbox->setSources([
    // name => Endpoint URL
    'commons'      => 'https://commons.wikimedia.org/w/api.php',
    'en.wikipedia' => 'https://en.wikipedia.org/w/api.php',
]);

$sandbox->setPreCall([
    // ['foo', 'bar'] == $class->foo($this->bar)
    ['setPageid', 'pageids'],      // Set the pageid identifier
    ['setTitle', 'titles'],        // Set the title identifier
    ['setEndpoint', 'endpoint'],   // Set the API endpoint
    ['setResponseLimit', 'limit'], // Set the # of responses to get
]);

$sandbox->play();
