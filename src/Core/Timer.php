<?php

    /**
     * @author Eli/邢福宜
     */

    namespace Eli\Job\Core;


    class Timer
    {
        const INTERVAL = 5 * 1000; //时间间隔为5s

        const IS_START = 1; //是否启动

        public static $timer_id_pool = [];

        public $register_tick_task_pool  = [];

        public $register_after_task_pool = [];

        protected $_filter_suffix_methods = ['_registerTick','_registerAfter'];


        /**
         * 注册定时任务
         */
         public function register(){
             $this->_getAllTaskTimer();
             $this->_registerAfter();
             $this->_registerTick();
         }

        public function tick($task_name, $interval = 0)
        {
            if (Tool::methodExist($this, $task_name)) {
                $timer_id = \Swoole\Timer::tick(is_int($interval) ? $interval : self::INTERVAL, [$this , $task_name]);
                self::$timer_id_pool[$timer_id] = self::IS_START;
            }

        }

        /**
         * 清除定时器
         * @param $timer_id
         */
        public function clear($timer_id)
        {
            \Swoole\Timer::clear($timer_id);
            unset(self::$timer_id_pool[$timer_id]);
        }

        /**
         * 创建一次性的定时器
         * @param $task_name
         * @param $interval
         */
        public function after($task_name,$interval)
        {
            if(Tool::methodExist($this,$task_name)){
                $timer_id = \Swoole\Timer::after(is_int($interval)?:self::INTERVAL,[$this,$task_name]);
                self::$timer_id_pool[$timer_id] = self::IS_START;
            }
        }

        /**
         * 注册一次性的定时器
         */
        protected function _registerAfter(){
            foreach($this->register_after_task_pool as $task_name=>$interval_times){
                $this->after($task_name,$interval_times);
            }
        }

        /**
         * 注册间隔定时器
         */
        protected function _registerTick(){
            foreach($this->register_tick_task_pool as $task_name=>$interval_times){
                $this->tick($task_name,$interval_times);
            }
        }

        /**
         * 获取全部的任务定时器
         */
        protected function _getAllTaskTimer()
        {
            $all_method = get_class_methods($this);
            foreach($all_method as $method_name){
                if(in_array($method_name,$this->_filter_suffix_methods)){
                    continue;
                }
                if (stripos($method_name, 'tick') && stripos($method_name, 'tick', -4)) {
                    $this->register_tick_task_pool[$method_name] = self::INTERVAL;
                    continue;
                }
                if (stripos($method_name, 'after') && stripos($method_name, 'after', -5)) {
                    $this->register_after_task_pool[$method_name] = self::INTERVAL;
                    continue;
                }
            }
        }
    }