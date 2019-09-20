<?php

    namespace Eli\Job\Core;

    class Console{

        public $logger     = null;

        protected $_config = '';

        protected $_command_log = APP_PATH.'/var/command.log';


        public function __construct( array $config)
        {
            //加载配置
            Config::setConfig($config);
            $this->_config = Config::getConfig();
            //实例话log对象
            $this->logger = new Log($this->_command_log);
        }

        /**
         * 通过参数调度服务(run)
         * 默认分发process服务
         */
        public  function run(){
            global $argv;
            if(isset($argv[1])){
                $server_name = $argv['2']??'process';

                $first_param = strtolower($argv[1]);

                switch ($first_param) {
                    case 'start':
                        if($server_name === 'process'){
                            $this->startProcess();
                        }
                        break;
                    case 'help':
                        $this->printHelpMessage();
                        break;
                    case 'restart':
                        if($server_name === 'process'){
                            $this->restartProcess();
                        }
                        break;
                    case 'stop':
                        if($server_name === 'process'){
                            $this->stopProcess();
                        }
                        break;
                    case 'kill':
                        if($server_name === 'process'){
                            $this->killProcess();
                        }
                        break;
                }
            }else{
                $this->printHelpMessage();
            }
        }


        /**
         * 启动服务
         */
        public function startProcess()
        {
            $this->logger->log('process starting.....'.PHP_EOL, LOG_INFO);
            //启动进程
            $process = new Process();
            $process->start();
            echo 'process running...' . PHP_EOL;




        }

        /**
         * 打印帮助信息
         */
        public function printHelpMessage(){

        }

        /**
         * 重启服务
         */
        public function restartProcess(){
            $this->logger->log('process restart.....'.PHP_EOL, LOG_INFO);
            //启动进程
            $process = new Process();
            $process->stop() and $process->start();




        }

        /**
         * 平滑停止服务
         */
        public function stopProcess(){
            $this->logger->log('process stopping.....'.PHP_EOL, LOG_INFO);
            //启动进程
            $process = new Process();
            echo $process->stop()?'process stop success':'process stop fail';

        }

        /**
         * 强制杀死服务
         */
        public function killProcess()
        {
            $this->logger->log('process killing.....'.PHP_EOL, LOG_INFO);
            //杀死进程
            $process = new Process();
            echo $process->kill()? 'process exit success...' . PHP_EOL : 'process exit fail...' . PHP_EOL;
        }
    }