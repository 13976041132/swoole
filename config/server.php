<?php
    return array(

        'set' => array(
            'worker_num' => 2,
            'max_request' => 3,
            'daemonize' => 1
        ),
        'host'=>'127.0.0.1',
        'port'=>9999,

        'onEvent'=>array(
            'Receive'    =>array('\swoole\event\server','onReceive'),
            'WorkerStart'=>array('\swoole\event\server','onWorkerStart'),
            'Connect'    =>array('\swoole\event\server','onConnect'),
            'Close'=>array('\swoole\event\server','onClose'),
            'Task'    =>array('\swoole\event\server','onTask'),
            'Finish'=>array('\swoole\event\server','onFinish'),
        ),
        'log_file'=>'/usr/local/var/www/swoole/swoole.log',
    );