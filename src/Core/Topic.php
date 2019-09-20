<?php


    namespace Eli\Job\Core;

    class Topic
    {

        //任务名称
        public $topic_name        = '';

        public $topic_worker_id   = '';

        public $topic_use_queue   = '';

        public $max_worker_nums   = 0;

        public $min_worker_nums   = 0;

        protected $_function_name = '';

        protected $_event_class_ame   = '';


        public function __construct(array $topic_info)
        {
            $this->topic_name      = $topic_info['name'];

            $this->topic_use_queue = $topic_info['use_queue'];

            $this->min_worker_nums = intval($topic_info['max_worker_nums']);

            $this->max_worker_nums = intval($topic_info['min_worker_nums']);

            if (isset($topic_info['callback'])) {
                if (is_array($topic_info['callback'])) {
                    if (isset($topic_info['callback']['class'])) {
                        $this->_event_class_ame = $topic_info['callback']['class'];
                    }
                } elseif (is_string($topic_info['callback'])) {
                    if(!Tool::functionExist($topic_info['callback'])) {

                    }
                    $this->_function_name = $topic_info['callback'];
                }
            }
        }

        /**
         *调用函数
         */
        public function callUserFunc()
        {
            if ($this->_function_name) {
                call_user_func($this->_function_name);
            }
        }

         public function exampleUserClass(){
             //判断当前的类是否继承了接口
             if (!Tool::isInterface($this->_callback_name, 'queueInterface')) {
                 die('custom class  must interface Iqueue');
             }
             $class_name = $this->_callback_name;
             return new $class_name();
         }
    }