<?php

    /**
     * @author Eli/邢福宜
     */
    namespace  Eli\Job\Core;

    interface ProcessInterface{

        public function start();

        public function restart();

        public function stop();

        public function kill();


    }