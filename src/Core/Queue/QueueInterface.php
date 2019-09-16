<?php

    /**
     * 定义接口
     */

    namespace Eli\Job\Core\Queue;

    interface QueueInterface{

        /**
         * 消费数据
         */

        public function consume();

        /**
         * 抛弃数据
         */

        public function discard();

        /**
         * 取出数据
         */

        public function pop();

        /**
         * 压入数据
         */

        public function push();

        public function connect();


    }