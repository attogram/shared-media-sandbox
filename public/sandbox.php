<?php // attogram/shared-media-sandbox - sandbox.php - v0.0.1

use Attogram\SharedMedia\Sandbox\Sandbox;

$autoload = '../vendor/autoload.php';
if (!is_readable($autoload)) {
    print 'ERROR: Autoloader not found: ' . $autoload;
    return false;
}
require_once($autoload);

$sandbox = new Sandbox();

$sandbox->setTitle('shared-media-sandbox');

$sandbox->setMethods([
// Class, Method, Arg, Identifiers
    ['CLASS_1', 'METHOD_A', 'ARG_1', true],
    ['CLASS_1', 'METHOD_B', 'ARG_2', false],
    ['CLASS_2', 'METHOD_C', 'ARG_3', true],
    ['CLASS_2', 'METHOD_D', 'ARG_4', false],
    ['CLASS_2', 'METHOD_E', false, false],
    ['CLASS_2', 'METHOD_E', false, true],
]);

$sandbox->setVersions([
    'Attogram\SharedMedia\Sandbox\Sandbox',
    'Attogram\SharedMedia\Sandbox\Tools',
    'Attogram\SharedMedia\Sandbox\Logger',
]);

$sandbox->setSources([
    'commons'        => 'https://commons.wikimedia.org/w/api.php',
    'en.wikipedia'   => 'https://en.wikipedia.org/w/api.php',
]);

$sandbox->play();
