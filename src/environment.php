<?php

    class environment{

        public function __construct()
        {
            $this->checkPhpVersion();
            $this->checkSwooleExtension();

        }


        public function checkPhpVersion(){
            if(version_compare(PHP_VERSION,'7.0','<')) {
                die('php版本过低，最少需要7.0,请更换PHP版本');
            }
            return true;

        }

        public function checkSwooleExtension(){

           if(!extension_loaded('swoole')){
               die('请安装swoole扩展');
           }
        }

    }

