<?php

/*////////////////////////////*/
//                            //
//   Bootstrap application.   //
//                            //
/*////////////////////////////*/

require 'application/core/config.php';

use Application\Core\Config;

ini_set('session.cookie_lifetime', 604800);
ini_set('session.gc_maxlifetime', 604800);

ini_set('expose_php', 'Off');

if (Config::DEBUG)
{
    error_reporting(1);
    ini_set('display_errors', 1);
    (E_ALL);
}

spl_autoload_register(function ($class) 
{
    $explodeDirection = explode('\\', $class);
    $file = end($explodeDirection) . '.php';

    array_pop($explodeDirection);

    $partsDirection = implode('/', $explodeDirection);
    $basePath = dirname(__DIR__);
    $filePaths = [
        $basePath . '/' . $partsDirection . '/' . $file,
        $basePath . '/' . mb_strtolower($partsDirection) . '/' . lcfirst($file),
        $basePath . '/' . $partsDirection . '/' . lcfirst($file),
        $basePath . '/' . $partsDirection . '/' . mb_strtolower($file),
        $basePath . '/' . mb_strtolower($partsDirection) . '/' . $file,
        $basePath . '/' . mb_strtolower($partsDirection . '/' . $file),
    ];

    foreach ($filePaths as $filePath) 
    {
        if (is_file($filePath)) 
        {
            include $filePath;
            break;
        }
    } 
});