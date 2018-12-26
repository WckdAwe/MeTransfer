<?php
namespace main;

define('__CONFIG', parse_ini_file('config.ini', true));
define('__ENV', __CONFIG['env']);
define('__DBCONF', __CONFIG['database']);
define('__EMAILS', __CONFIG['emails']);

/** PACKAGE MANAGER **/
define('ROOT', dirname(__DIR__));

if (!session_id()) {
    session_save_path(ROOT.'/sessions');
    session_start();
}

require_once(ROOT . '/private_html/codebase/PackageManager/Autoload.php');

use Tres\PackageManager\Autoload;
$manifest = require(ROOT . '/private_html/manifest.php');
$autoload = new Autoload(ROOT.'/', $manifest);
/** END OF PACKAGE MANAGER **/
