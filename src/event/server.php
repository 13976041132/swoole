<?php
    namespace Swoole\event;

    class server{

        public static function onReceive(\swoole\server $server, int $fd, int $reactor_id, string $data){
            var_dump($data);
            $server->send($fd, '我想测试下');
            $server->close($fd,true);

        }


        /**
         * @param \swoole\server $server
         * @param int $worker_id
         * 此事件在worker进程/task_worker启动时发生
         * 发生PHP致命错误或者代码中主动调用exit时，Worker/Task进程会退出，管理进程会重新创建新的进程 onWorkerStart/onStart是并发执行的，没有先后顺序
         * 通过$worker_id参数的值来，判断worker是普通worker还是task_worker。$worker_id>= $serv->setting'worker_num' 时表示这个进程是task_worker。
         */
        public static function onWorkerStart(\swoole\server $server, int $worker_id){
            echo 33;

        }

        /**
         * @param \swoole\server $serv
         * @param int $fd
         * @param int $from_id
         * 有新的连接进入时，在worker进程中回调。
         * onConnect/onClose这2个回调发生在worker进程内，
         * 而不是主进程。如果需要在主进程处理连接/关闭事件，
         * 请注册onMasterConnect/onMasterClose回调。onMasterConnect/onMasterClose回调总是先于onConnect/onClose被执行
         */

        public static function onConnect(\swoole\server $serv, int $fd, int $from_id){

        }

        /**
         * @param \swoole\server $serv
         * @param int $fd 连接的描述符
         * @param int $from_id reactor的id，无用
         *
         * TCP客户端连接关闭后，在worker进程中回调此函数。
         * 无论close由客户端发起还是服务器端主动调用swoole_server_close关闭连接，
         * 都会触发此事件。 因此只要连接关闭，就一定会回调此函数
         */

        public static function onClose(\swoole\server $serv, int $fd, int $from_id){
            echo $fd;

        }

        /**
         * @param \swoole\server $serv
         * @param int $task_id 任务ID
         * @param int $from_id 来自于哪个worker进程
         * @param string $data 任务内容
         * task_worker进程处理任务的回调
         * 在task_worker进程内被调用。worker进程可以使用swoole_server_task函数向task_worker进程投递新的任务。
         * 可以直接将任务结果字符串通过return方式返回给worker进程。worker进程将在onFinish回调中收到结果。
         * 注：如果serv->set(array('task_worker_num' => 8)) task_id 并不是从1-8 而是递增的
         */

        public static function onTask(\swoole\server $serv,int $task_id,int $from_id,string $data){

        }

        /**
         * @param \swoole\server $serv
         * @param int $task_id 任务ID
         * @param string $data 任务结果
         */

        public static function onFinish(\swoole\server $serv, int $task_id, string $data){

        }



    }