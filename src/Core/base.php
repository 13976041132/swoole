<?php


    //环境常量
   define('IS_CLI', PHP_SAPI == 'cli' ? true : false);
   define('IS_MAC', strpos(PHP_OS,'Darwin') !== false);
