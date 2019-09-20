<?php

    namespace Eli\Job\Core;

    class Tool{

        public static function isInterface($class_name,$interface_name)
        {
            $interface_setting = class_implements($class_name,true);
            return isset($interface_setting[$interface_name])?true:false;
        }

        public  static function functionExist($function_name)
        {
            return function_exists($function_name);

        }

        /**
         * 判断方法是否存在
         * @param $object
         * @param $method_name
         * @return bool
         */
        public static function methodExist($object, $method_name)
        {
            return method_exists($object,$method_name);
        }
    }