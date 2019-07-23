<?php

    namespace Swoole\core;

    use Swoole\Server as swooleServer;

    class server
    {

        public static $server = '';

        public function __construct($host,$port)
        {
            self::$server = new swooleServer($host,$port);
        }

        public function run()
        {
            $this->set();
            $this->onEvent();
            $this->start();

        }

        public function onEvent(){
             $events = \config::get('onEvent');

             foreach($events as $event => $callback){
                 self::$server->on($event,$callback);
             }

        }
        public function start(){
            self::$server->start();
        }

        public function set(){
            self::$server->set(\config::get('set'));
        }



        public function addProcess(){
        }

    }
