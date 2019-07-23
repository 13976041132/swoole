<?php

    /**
     *
     */
    namespace  Eli\Job\Core\Interf;

    interface IswooleService{

        public function start();
        public function restart();
        public function stop();
        public function kill();
        public function help();


    }