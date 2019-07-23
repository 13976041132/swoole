<?php

    function classAutoLoader($class){

        $substr_name = substr($class,strpos($class, '\\')+4);
        $className = str_replace('\\','/', $substr_name);

        $classFile= __DIR__ . '/src/' . $className.'.php';
        if(is_file($classFile)&&!class_exists($className)){
            include $classFile;
        }

    }
    spl_autoload_register('classAutoLoader');


    function test(){echo 333;
    sleep(6);
    }