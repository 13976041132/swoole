<?php
    return [

        'topics' =>[
            'abc'=>[
                'name'       =>'abc',
                'use_queue'   =>'abc',
                'callback'=>'test',
                'max_worker_nums'=>2,
                'min_worker_nums'=>1,
             ],
         ],
        'timers'=>['Eli\Job\Core\TestTimer'],
        'daemonize' => true,
        'master_pid_log' => '/usr/local/var/www/swoole/var/master_pid.log',
        'master_info_log' => '/usr/local/var/www/swoole/var/master_info.log',
        'queue' =>[
            'queue_drive' =>'redis',
            'host' => '127.0.0.1',
            'port' => 6379,
            'password' => '123456',
            'iqueue_log_file'=>'/usr/local/var/www/swoole/var/iqueue.log'

        ],
        'error_log' => '/usr/local/var/www/swoole/error.log'

    ];