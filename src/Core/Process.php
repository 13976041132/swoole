<?php

    namespace Eli\Job\Core;

    use Exception;
    use Swoole\Timer;
    use Eli\Job\Core\Queue\RedisQueue;
    use Swoole\Process as swoole_process;

    /**
     * Class Process
     * @package Eli\Job\Core
     * @author Eli|邢福宜
     * @date:2019-8-20
     */

    class Process implements ProcessInterface
    {

        const TIMER_TICK                       = 5 * 1000;     //定时器，每五秒执行一次
        const WORKER_ASSIGN                    = 'dynamic';    //进程是否支持动态分配
        const CREATE_TRY_TIME                  = 5;            //创建尝试次数
        const MASTER_STATUS_WAIT               = 'wait';       //平滑退出
        const MASTER_STATUS_RUN                = 'run';        //进程启动时的状态
        const MASTER_STATUS_STOP               = 'stop';       //进程停止时的状态
        const WARNING_MAX_VALUE_LIMIT          = 10000;        //队列阻塞警报阀值
        const WORKER_CONSUME_QUEUE_MAX_LENGTH  = 500;          //进程消费队列数据的上线

        private $__master_id             = 0;                               //当前进程id
        private $__master_status         = 'run';                           //默认状态
        private $__worker_pids           = [];                              //工作的子进程id
        private $__list_worker_nums      = [];                              //需要创建的进程
        private $__worker_bind_topic     = [];                              //任务绑定的对象
        private $__worker_info_log       = APP_PATH.'/var/worker_info.log'; //worker_info_log
        private $__master_pid_log        = APP_PATH.'/var/master_pid.log';  //主进程的文件
        private $__master_info_log       = APP_PATH.'/var/master_info.log'; //主进程的信息文件
        private $__error_log             = APP_PATH.'/var/error.log';       //错误日记记录


        protected $_queue_worker_count = [];                              //每个队列子进程创建的数量
        protected $_config             = '';                              //配置信息
        protected $_logger             = null;                            //log对象
        protected $_is_daemon          = false;                           //是否守护进程
        public    $topics              = [];

        public function __construct()
        {
            try{
                $this->_config = Config::getConfig();

                $this->topics  = $this->_config['topics']??[];

                $this->_is_daemon = $this->_config['daemon']?false:false;

                $this->__master_pid_log = $this->_config['master_pid_log']??$this->__master_pid_log;

                $this->__master_info_log = $this->_config['master_info_log']??$this->__master_info_log;

                $this->_logger = new Log($this->_config['worker_info_log']??$this->__worker_info_log,$this->__error_log);

            }catch (Exception $e){
                die($e->getMessage());
            }
        }

        /**
         * 初始化数据
         */
        protected function _init()
        {
            try{
                //先判断进程是否在启用
                if ($this->_checkRunning()) {

                    $this->_logger->log('process always running', LOG_INFO);

                    die('process always running...' . PHP_EOL);
                }
                //设置守护进程
                if ($this->_setDaemon()) {
                    //获取当前进程的id
                    $this->__master_id = posix_getpid();
                    //记录当前进程的id
                    if(!$this->_saveMasterIdToLog()){
                        throw new Exception('save master id fail');
                    }
                }
            }catch (Exception $e){
                $this->_logger->exceptionLog($e->getMessage());
                exit();

            }
        }

        /**
         * 注册信号
         */
        protected function _registerSignal()
        {
            swoole_process::signal(SIGUSR1, function () {

            });

            swoole_process::signal(SIGCHLD, function () {
                $this->_newWorkerJob();
            });

            swoole_process::signal(SIGKILL, function () {
                //退出所有子进程和主进程
                $this->_killWorkerAndExitMaster();
            });

            swoole_process::signal(SIGINT, function () {
                //退出所有子进程和主进程
               $this->_killWorkerAndExitMaster();
                echo 2;
            });

            swoole_process::signal(SIGTERM, function () {
                //退出所有子进程和主进程
                $this->_killWorkerAndExitMaster();
            });

            swoole_process::signal(SIGUSR2, function () {
                $this->__master_status = self::MASTER_STATUS_WAIT;
                if (count($this->__worker_pids)) {
                    $this->_exitMaster();
                }
            });

        }


        /**
         * 注册定时器
         */
        protected function _registerTime()
        {
            /**
             * 注册定时器,查看是否需要队列数据是否阻塞
             */
            Timer::tick(self::TIMER_TICK, function () {
                foreach ($this->__worker_bind_topic as $topic) {
                    //链接redis
                    $redis_queue = new RedisQueue($this->_config['queue']);
                    $redis_queue->connect();
                    if (($list_length = $redis_queue->getListCount($topic->topic_name)) > self::WARNING_MAX_VALUE_LIMIT) {
                        $redis_queue->logger->log('queue name:' . $topic->topic_name . ',Queue Length=' . $list_length . ', Exceeding Threshold');
                        /**
                         * 查看当前队列的进程是否满足有空闲的队列
                         * 是否动态创建子进程
                         */
                        if ($this->_isDynamicCreatedWorker($topic)) {
                            $this->_createProcess($topic);
                        }
                    }
                    $redis_queue->free();
                }
            });
        }

        /**
         * 创建子进程
         */
        protected function _registerTopic()
        {
            foreach ($this->topics as $topic_name => $topic_info) {

                $topic = new Topic($topic_info);

                $this->__worker_bind_topic[] = $topic;

                //创建进程
                for($created_sum = 0; $created_sum < $topic->min_worker_nums; $created_sum++){
                    $this->_createProcess($topic);
                }
            }

        }


        /**
         * 当子进程退出的退出,则重新创建进程
         */
        protected function _newWorkerJob()
        {
            try{
                while ($ret = swoole_process::wait(false)) {
                    $topic = $this->__worker_pids[$ret['pid']];
                    unset($this->__worker_pids[$ret['pid']]);
                    $this->_subQueueWorker($topic);

                    if ($this->__master_status === self::MASTER_STATUS_RUN
                        && $topic->min_worker_nums > $this->_queue_worker_count[$topic->topic_name]) {

                        //判断当前的队列进程是否需要创建子进程
                        $process = $this->_createProcess($topic);
                        if ($process) {
                            $this->_setWorkerName($topic->topic_name, $process);
                        }
                        //$this->_logger->log('child worker restart, topic name is ' . $worker_topic->topic_name . PHP_EOL, LOG_INFO);
                    }
                }
            }catch (Exception $e){

            }

        }


        /**
         *退出子进程
         */
        protected function _killWorkerAndExitMaster()
        {

            $this->__master_status = self::MASTER_STATUS_STOP;
            //杀死进程
            foreach ($this->__worker_pids as $worker_id => $topic) {
                swoole_process::kill($worker_id);
                unset($this->__worker_pids[$worker_id]);
            }
            $this->_logger->log('process termination,__master_id:' . $this->__master_id . ',opt time:' . date('Y-m-d H:i:s') . PHP_EOL, LOG_INFO);
            $this->_exitMaster();
        }


        /**
         *退出主进程
         */
        protected function _exitMaster()
        {

            //清理文件信息

            @unlink($this->__master_pid_log);

            //子进程退出完后，主进程直接用exit()退出,不要用kill/exit，会触发信号监听
            exit();
        }

        /**
         * @param $topic
         * @param swoole_process $process
         */
        protected function _setWorkerName($topic, swoole_process $process)
        {
            if (!IS_MAC) {
                $process->name($topic);
            }
        }

        protected function _setDaemon()
        {
            return $this->_is_daemon ? swoole_process::daemon() : false;
        }


        /**
         * 校验主进程是否存在
         * @return bool
         */
        protected function _checkRunning()
        {
            if ($this->__master_id || file_exists($this->__master_pid_log)) {
                //获取进程的数据
                $this->__master_id = $this->__master_id ?: trim(file_get_contents($this->__master_pid_log));
                //多次确认进程是否在运行
                for($try_time = 0; $try_time<self::CREATE_TRY_TIME;$try_time++){
                    if (!swoole_process::kill($this->__master_id, 0)) {
                        return false;
                    }
                }
                return true;
            }
            return false;
        }

        /**
         * 当前进程记录到文件中
         * @return bool
         */
        protected function _saveMasterIdToLog()
        {
            if ($this->_logger->createFileDir((dirname($this->__master_pid_log)))) {
                file_put_contents($this->__master_pid_log, $this->__master_id);
                return true;
            }
            return false;

        }

        /**
         * 设置队列名字数量
         */
        protected function _setListWorkerNums()
        {

            foreach (Config::get('topics') as $topic_name => $info) {
                $this->__list_worker_nums[$topic_name] = $info['worker_num'];
            }

        }

        /**
         * @param Topic $topic
         * @return bool|swoole_process
         */
        protected function _createProcess(Topic $topic)
        {
            $process = new swoole_process(function () use ($topic) {
                try{
                    if (!swoole_process::kill($this->__master_id, 0)) {
                        exit();
                    }
                    //连接redis
                    $redis_queue = new RedisQueue($this->_config['queue']);
                    $redis_queue->connect();
                    if ($redis_queue->getListCount($topic->topic_use_queue) > 0) {
                        $pop_data_count = $pop_empty_time = 0;

                        do {
                            $data = $redis_queue->getRedis()->lPop($topic->topic_use_queue);
                            if (empty($data)) {
                                usleep(100);

                                if (++$pop_empty_time > 10) {
                                    break;
                                }
                                continue;
                            }
                            $pop_data_count++;
                        } while ($pop_data_count < self::WORKER_CONSUME_QUEUE_MAX_LENGTH);
                    } else {
                        sleep(2);
                    }
                    $redis_queue->free();
                }catch (Exception $e){
                    if(isset($redis_queue) && $redis_queue instanceof RedisQueue){
                        $redis_queue->free();
                    }
                    $this->_logger->exceptionLog($e);
                }

            });
            $worker_pid = 0;
            for($try_time = 0; $try_time<self::CREATE_TRY_TIME;$try_time++){
                if ($worker_pid = $process->start()) {
                    break;
                }
                sleep(1);
            }
            if (!$worker_pid) {
                $this->_logger->log('fork worker fail...' . PHP_EOL);
                return false;
            }
            $this->_addQueueWorker($topic);

            $this->__worker_pids[$worker_pid] = $topic;

            return $process;

        }

        /**
         * 是否需要动态添加子进程
         * @param Topic $topic
         * @return bool
         */
        protected function _isDynamicCreatedWorker(Topic $topic)
        {
            return $this->_queue_worker_count[$topic->topic_name] < $topic->max_worker_nums;
        }

        /**
         * 自增子进程数量
         * @param Topic $topic
         */
        protected function _addQueueWorker(Topic $topic)
        {

            if (!isset($this->_queue_worker_count[$topic->topic_name])) {
                $this->_queue_worker_count[$topic->topic_name] = 0;
            } else {
                $this->_queue_worker_count[$topic->topic_name]++;
            }
        }


        /**
         * 自减子进程数量
         *  @param $topic
         */
        protected function _subQueueWorker(Topic $topic)
        {

            $this->_queue_worker_count[$topic->topic_name]--;
        }


        public function start()
        {
            $this->_init();
            $this->_registerTopic();
            $this->_registerSignal();
            $this->_registerTime();
        }

        /**
         * 强制杀死进程
         */
        public function kill()
        {
            for($try_time=0; $try_time<self::CREATE_TRY_TIME;$try_time++){
                //获取进程退出
                if (!$this->_checkRunning()) {
                    return true;
                }
                @swoole_process::kill($this->__master_id);
                sleep(1);
            }

            return false;
        }

        /**
         * 重启进程
         */
        public function restart()
        {
            $this->stop() and $this->start();
        }

        /**
         * 平滑停止服务
         * @return bool
         */
        public function stop()
        {
            try{
                for($try_time=0; $try_time<self::CREATE_TRY_TIME;$try_time++){
                    //获取进程退出
                    if (!$this->_checkRunning()) {
                        return true;
                    }
                    @swoole_process::kill($this->__master_id, SIGUSR2);
                    sleep(2);
                }
                return false;
            }catch (Exception $e){
                $this->_logger->exceptionLog($e->getMessage());
                return false;
            }
        }

    }
