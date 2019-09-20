<?php


//    $redis = new \Redis();
//
//    $redis->connect('127.0.0.1');
//
//    for($i=0;$i<=1000000;$i++){
//        $redis->lPush('abc',rand(1,199999));
//    }
//
//
//exit;
//    if(@$argv[1]){
//        $i = 0;
//        while(1) {
//            if(!\Swoole\Process::kill($argv[1],0)){
//                echo 33;exit;
//
//            }
//            \Swoole\Process::kill($argv[1],SIGUSR1);
//            sleep(2);
//            $i++;
//            if ($i == 5) {
//                exit;
//            }
//        }
//    }
//
////\Swoole\Process::daemon();
//echo posix_getpid();
//
//    $process = new \Swoole\Process(function(\Swoole\Process $process){
//        echo 2;
//        //  $process->exit();
//    });
////开启进程 返回值是开启进程的进程号
//    $pid = $process->start();
//
//
//
//    \Swoole\Process::signal(SIGCHLD, function($sig) {
//        //必须为false，非阻塞模式
//            $process = new \Swoole\Process(function (\Swoole\Process $process) {
//                echo 4;
//                $start_time = time();
//                while (true) {
//                    if ((time() - $start_time) / 2 == 1) {
//                        sleep(4);
//                        break;
//                    }
//
//
//                }
//            });
//            $pid = $process->start();
//    });
////开启进程 返回值是开启进程的进程号
//
//    \Swoole\Process::signal(SIGUSR1, function () {
//        echo 2;
//    });
//
//
//    \Swoole\Process::signal(SIGKILL, function () {
//        echo 5;
//        //退出所有子进程和主进程
//        //$this->_killWorkerAndExitMaster();
//    });
//
//    \Swoole\Process::signal(SIGTERM, function () {
//        echo 466666;
//        //退出所有子进程和主进程
//        //$this->_killWorkerAndExitMaster();
//    });
//
//    \Swoole\Timer::tick(3000,function(){
//        echo 'x';
//
////                foreach($this->worked_bind_topic as $topic){
////                    //链接redis
////                    $redis_queue = new RedisQueue($this->_config['queue']);
////                    $redis_queue->conncet();
////                    if(($list_lenght = $redis_queue->getListCount($topic->topic_name))>self::WARNING_MAX_VALUE_LIMIT){
////                        $redis_queue->logger->log('queue name:'.$topic->topic_name.',Queue Length='.$list_lenght.', Exceeding Threshold');
////
////                        //查看当前队列的进程是否满足有空闲的队列
////
////                    }
////                }
//    });




