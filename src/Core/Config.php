<?php
    /**
     * 配置加载类
     */

    namespace Eli\job\Core;

    class Config{

        public static $config_items = [];

        public static function setConfig($config){
            self::$config_items= $config;
        }

        public static function getConfig(){
            return self::$config_items;

        }

        public static function load($file_path){

            if(!file_exists($file_path)){
                die($file_path.'配置文件不存在');
            }
            self::$server_config_items = require_once $file_path;

        }


        public static function get($key){
            return self::$server_config_items[$key]??false;
        }


        public static function set($key,$value){
            return self::$server_config_items[$key] = $value;
        }


        public static function del($key){
            unset(self::$server_config_items[$key]);
        }

    }
