<?php

    /**
     * 注册自动加载
     */
    spl_autoload_register(function ($class)
    {
        $substr_name = substr($class,strpos($class, '\\')+4);
        $className = str_replace('\\','/', $substr_name);

        $classFile= __DIR__ . '/src/' . $className.'.php';
        if(is_file($classFile)&&!class_exists($className)){
            include $classFile;
        }
    });

    /**
     * 注册异常错误机制
     */

    set_exception_handler(function (Throwable $exception){
        echo __FILE__.':'.$exception->getMessage().PHP_EOL;
    });

    /**
     * 注册自定义错误信息
     */
    set_error_handler(function(int $errno , string $error){
        echo $error.PHP_EOL;
        return false;
    });

    /**
     * 脚本完结注册
     */
    register_shutdown_function(function(){
        echo  '脚本执行完毕'.PHP_EOL;

    });
