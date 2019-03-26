<?php
/**
 * @author      : xingfuyi
 * @createTime  : 2019/3/19 下午5:16
 * @description :
 */

    if(!function_exists('classAutoLoader')){
        function classAutoLoader($class){

        	$substr_name = substr($class,strpos($class, '\\'));
            $className = str_replace('\\','/', $substr_name);
            $classFile=__DIR__.'/src/'. $className.'.php';
            if(is_file($classFile)&&!class_exists($className)){
			} include $classFile;

        }

    }
    spl_autoload_register('classAutoLoader');

(new swoole\swoole\Process())->run();