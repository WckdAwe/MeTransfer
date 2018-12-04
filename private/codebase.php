<?php
namespace main;

if (!session_id()) {
    session_start();
}

/** PACKAGE MANAGER **/
define('ROOT', dirname(__DIR__));
require_once(ROOT.'/private/codebase/PackageManager/Autoload.php');
use Tres\PackageManager\Autoload;
$manifest = require(ROOT.'/private/manifest.php');
$autoload = new Autoload(ROOT.'/', $manifest);
/** END OF PACKAGE MANAGER **/

