<?php

/**
 * Author : Pierre CHARLES
 * Auto loader function
 */

    $cacheFile = __DIR__ .DIRECTORY_SEPARATOR."cache.php";
    $Directories = [ 'Model' => '../', 'Controller' => '../'];

    if (file_exists($cacheFile)) {
        $cache = require $cacheFile;
    }
    else {
        $cache = [];
    }
    spl_autoload_register(function ($className) use (&$cache, $cacheFile, $Directories) {
        if (isset($cache[$className])) {
            $path = $cache[$className];
        }
        else {
            $path = str_replace(array('\\', '_'), '/', $className) . '.php';
        }
        foreach ($Directories as $key => $dir) {
            $completePath = __DIR__ . '/' . $dir . '/' . $path;
            if (file_exists($completePath)) {
                require_once($completePath);
                $cache[str_replace(array('\\', '_'), '/', $className)] = $path;
            }
        }
        file_put_contents($cacheFile, sprintf("<?php\n return %s;", var_export($cache, true)));
    });
