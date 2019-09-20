<?php

    namespace Eli\Job\Core\Queue;

    use Exception;
    use Redis;
    use Eli\Job\Core\Log;

    class RedisQueue implements QueueInterface {

        public $logger = '';

        public $redis = '';

        protected $_host = '';

        protected $_port = '';

        protected $_password = '';

        public static $using_redis_pool = [];//使用资源池
        public static $not_use_redis_pool = [];//未使用资源池


        public function __construct($config)
        {
            $this->logger = new Log($config['iqueue_log_file']);
            $this->_host  = $config['host'];
            $this->_port  = $config['port'];
            $this->_password = $config['password'];
        }

        /**
         * 查看是否存在未使用的连接
         */



        /**
         * 连接redis
         */
        public function connect()
        {
            try{
                $this->redis = new Redis();
                $this->redis->connect($this->_host,$this->_port);
                if($this->_password){
                    $this->redis->auth($this->_password);
                }
            }catch (Exception $e){
                $this->logger->log($e->getMessage().PHP_EOL,LOG_ERR);
                return false;
            }
            return $this;
        }

        /**
         * 丢弃队列数据
         */

        public function discard()
        {
            // TODO: Implement discard() method.
        }

        /**
         *  弹出数据
         */

        public function pop()
        {
            // TODO: Implement pop() method.
        }


        /**
         * 弹入队列数据
         */

        public function push()
        {
            // TODO: Implement push() method.
        }

        /**
         * 消费队列数据方法
         */

        public function consume()
        {
            // TODO: Implement consume() method.
        }


        /**
         * 释放连接
         *
         */
        public function free(){
            if($this->redis instanceof Redis){
                $this->getRedis()->close();
            }
        }


        /**
         * 查看队列长度
         * @param $list_key
         * @return bool|int
         */
        public function getListCount($list_key){
            try{
                if(!$this->redis instanceof Redis){
                    throw new Exception('please connect redis...');
                }
                return $this->getRedis()->lLen($list_key);
            }catch (Exception $e){
                $this->logger->log($e->getMessage());
                return false;

            }
        }


        /**
         * 获取对象实例
         * @return Redis|string
         */
        public function getRedis(){
            return $this->redis;
        }
    }