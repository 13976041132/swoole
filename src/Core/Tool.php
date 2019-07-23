<?php

    namespace Eli\Job\Core;

    class Tool{


        public static function isInterface($class_name,$interface_name){

            $interface_setting = class_implements($class_name,true);
            return isset($interface_setting[$interface_name])?true:false;

        }

        public  static function functionExist($function_name){
            return function_exists($function_name);

    }




    }