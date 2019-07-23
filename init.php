<?php


    /**
     * 加载function
     */

    define('APP_PATH',__DIR__);
    require_once(APP_PATH . '/src/Core/base.php');
    require_once(APP_PATH . '/function.php');
    require_once(APP_PATH . '/src/environment.php');
    new environment();
    $config = require_once(APP_PATH . '/config/process.php');



