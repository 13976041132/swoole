<?php
/**
 * @author      : xingfuyi
 * @createTime  : 2019/3/18 上午10:39
 * @description :
 */

namespace swoole\swoole;

use swoole\inter\Iserver;

class Process extends BaseServer implements Iserver
{

    public static $swoole_process = '';

    public $master_id  = '';
    public $manager_id = '';
    public $worker_ids = [];

    public function __construct()
    {
        $this->createMaster();
//        if (!self::$swoole_process instanceof \swoole_process) {
//            self::$swoole_process = new \swoole_process(
//                function () {
//
//                    while (TRUE) {
//                        echo 2;
//                        sleep(2);
//                    }
//                }
//            );
//        }
    }

    public function run()
    {
        $this->set([
                       'reactor_num'   => 2, //reactor thread num
                       'worker_num'    => 4,    //worker process num
                       'backlog'       => 128,   //listen backlog
                       'max_request'   => 50,
                       'dispatch_mode' => 1,
                       'daemonize'     => 1,
                       'log_file'      => '/usr/local/var/www/myComponent/swoole.log',
                   ]);
        $this->start();
    }

    public function start()
    {
        self::$swoole_process->start();
    }

    public function reload()
    {
        self::$swoole_process->reload();
    }

    public function stop()
    {
        self::$swoole_process->stop();
    }

    public function kill()
    {
    }

    public function set($config)
    {
        self::$swoole_process->set($config);
    }

    public function getConfig(array $config)
    {

    }

    public function checkEnv()
    {
        if (substr(php_sapi_name(), 0, 3) === 'cli') {
            trigger_error(' The current environment is not cli');
        }
    }

    public function checkExtension()
    {
        if (!extension_loaded('swoole')) {
            trigger_error('please install PHP swoole extension');
        }
    }
}
