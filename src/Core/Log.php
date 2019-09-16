<?php

    namespace Eli\Job\Core;

    class Log{

        protected $_error_file = '';
        protected $_log_file = '';

        public function __construct($log_file,$error_file='')
        {
            $this->_log_file = $log_file;
            $this->_error_file = $error_file;

        }

        /**
         * @param $log_message
         * @param int $level
         * @param string $file_path
         */
        public function log($log_message, $level=LOG_ERR, $file_path = '')
        {
            switch($level){
                case 4:
                    $level = 'LOG_WARNING';
                    break;
                case 5:
                    $level = 'LOG_NOTICE';
                    break;
                case 6:
                    $level = 'LOG_INFO';
                    break;
                case 7:
                    $level = 'LOG_DEBUG';
                    break;
                default:
                    $level = 'LOG_ERR';
            }
            $log_message = '['.$level.']:'.$log_message.PHP_EOL;
            $this->write($log_message,$file_path?:$this->_log_file);
        }

        public function exceptionLog($log_message){
             $this->log($log_message,LOG_ERR,$this->_error_file);
        }

        /**
         * @param $log_message
         * @param $file_path
         * @return bool
         */
        public function write($log_message,$file_path){

            if($this->createFileDir(dirname($file_path))){
                error_log($log_message,3,$file_path);
                return true;
            }
            return false;
        }

        /**
         * @param $file_base_path
         * @return bool
         */
        public function createFileDir($file_base_path)
        {
            if (!is_dir($file_base_path)) {
                //创建文件
                return mkdir($file_base_path, 0777, true);

            }
            return true;
        }

    }