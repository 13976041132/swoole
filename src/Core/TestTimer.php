<?php

    namespace Eli\Job\Core;

    use  Eli\Job\Core\Queue\RedisQueue;

    class TestTimer extends Timer
    {

        public function testTick(){
            $redis = new RedisQueue(Config::$config_items['queue']);
            $pop_data = $redis->connect()->getRedis()->lPop('dd');
            $redis->connect()->getRedis()->lPush('dd',$pop_data);
        }
        public function test1Tick(){
            $redis = new RedisQueue(Config::$config_items['queue']);
            $pop_data = $redis->connect()->getRedis()->lPop('dd');
            $redis->connect()->getRedis()->lPush('dd',$pop_data);
        }
    }