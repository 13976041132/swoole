<?php
/**
 * @author      : xingfuyi
 * @createTime  : 2019/3/27 下午5:26
 * @description :
 */

namespace Swoole;

class Process
{

    /**
     * @var int 可将队列设置为非阻塞
     */
    const IPC_NOWAIT = 256;

    /**
     * 进程的PID
     * @var int
     */
    public $pid;

    /**
     * 管道PIPE
     * @var int
     */
    public $pipe;

    //创建子进程
    public function __construct(callable $function,
                                bool $redirect_stdin_stdout = FALSE,
                                int $pipe_type = 2,
                                bool $enable_coroutine = FALSE)
    {

    }

    /**
     * 执行fork系统调用，启动进程
     * @return integer
     */
    public function start()
    {

    }

    //name方法应当在start之后的子进程回调函数中使用
    public function name($name)
    {

    }

    /*
     * $execfile指定可执行文件的绝对路径，如 "/usr/bin/python"
     *  $args是一个数组，是exec的参数列表，如 array('test.py', 123)，相当与python test.py 123
     * 执行成功后，当前进程的代码段将会被新程序替换。子进程蜕变成另外一套程序。父进程与当前进程仍然是父子进程关系
     * 在start前执行
     */
    public function exec(string $execfile, array $args)
    {

    }

    //向管道内写入数据
    public function write(string $data)
    {

    }

    //从管道中读取数据
    public function read(int $buffer_size=8192)
    {

    }

    //设置管道读写操作的超时时间
    public function setTimeout(double $timeout)
    {

    }

    /*设置管道是否为阻塞模式。默认Process的管道为同步阻塞
    *在异步程序中使用swoole_event_add添加管道事件监听时底层会自动将管道设置为非阻塞
    *在异步程序中使用swoole_event_write异步写入数据时底层会自动将管道设置为非阻塞
    */
    public function setBlocking(bool $blocking = TRUE)
    {

    }
    /*启用消息队列作为进程间通信
    $msgkey是消息队列的key，默认会使用ftok(__FILE__, 1)作为KEY
    $mode通信模式，默认为2，表示争抢模式，所有创建的子进程都会从队列中取数据
    如果创建消息队列失败，会返回false。可使用swoole_strerror(swoole_errno()) 得到错误码和错误信息。
    使用模式2后，创建的子进程无法进行单独通信，比如发给特定子进程。
    $process对象并未执行start，也可以执行push/pop向队列推送/提取数据
    消息队列通信方式与管道不可共用。消息队列不支持EventLoop，使用消息队列后只能使用同步阻塞模式
    */
    public function useQueue(int $msgkey = 0, int $mode = 2)
    {

    }

    /**
     *查看消息队列状态
     * 返回一个数组，包括2项信息
     * queue_num 队列中的任务数量
     * queue_bytes 队列数据的总字节数
     */
    public function statQueue()
    {

    }

    /*
     * 删除队列。此方法与useQueue成对使用，useQueue创建队列，使用freeQueue销毁队列。销毁队列后队列中的数据会被清空
     */
    public function freeQueue()
    {

    }

    /*
     * 投递数据到消息队列中
     */
    public function push(string $data)
    {

    }

    /*
    * 投递数据到消息队列中
    */
    public function pop(int $maxsize = 8192)
    {

    }

    /*
    * 用于关闭创建的好的管道
     * $which 指定关闭哪一个管道，默认为0表示同时关闭读和写，1：关闭写，2关闭读
    */
    public function close(int $which = 0)
    {

    }

    /*
     * 用于关闭创建的好的管道
     * $which 指定关闭哪一个管道，默认为0表示同时关闭读和写，1：关闭写，2关闭读
     * 在父进程中，执行Process::wait可以得到子进程退出的事件和状态码
    */
    public function exit(int $which = 0)
    {

    }

    /**
     *向指定pid进程发送信号
     * 默认的信号为SIGTERM，表示终止进程
     *@param int $signo=0，可以检测进程是否存在，不会发送信号
     *@param $pid int
     * @return bool
     */
    public static function kill($pid, $signo = SIGTERM)
    {

    }

    /**
     * 回收结束运行的子进程
     * @param bool $blocking
     * @return array
     */
    public static function wait(bool $blocking = TRUE)
    {

    }

    /**
     * 使当前进程蜕变为一个守护进程
     *$nochdir，为true表示不要切换当前目录到根目录。
     *$noclose，为true表示不要关闭标准输入输出文件描述符。
     * 此函数在1.7.5版本后可用
     * 1.9.1或更高版本修改了默认值，现在默认nochir和noclose均为true
     *蜕变为守护进程时，该进程的PID将发生变化，可以使用getmypid()来获取当前的PID
     * @return bool
    */
    public static function daemon(bool $nochdir = TRUE, bool $noclose = TRUE)
    {

    }

    /*
     * 设置异步信号监听。
     *   此方法基于signalfd和eventloop是异步IO，不能用于同步程序中
     *   同步阻塞的程序可以使用pcntl扩展提供的pcntl_signal
     *   $callback如果为null，表示移除信号监听
     *   如果已设置了此信号的回调函数，重新设置时会覆盖历史设置
    */
    public static function signal( $signo, callable $callback)
    {

    }

    /*
    *$interval_usec 定时器间隔时间，单位为微秒。如果为负数表示清除定时器
    *$type 定时器类型
    *0 表示为真实时间,触发SIGALAM信号
    *1 表示用户态CPU时间，触发SIGVTALAM信号
    *2 表示用户态+内核态时间，触发SIGPROF信号
    *设置成功返回true，失败返回false，可以使用swoole_errno得到错误码
    */
    public static function alarm(int $interval_usec, int $type = ITIMER_REAL): bool
    {

    }

    /*
    *设置CPU亲和性，可以将进程绑定到特定的CPU核上
    *$cpu_set内的元素不能超过CPU核数
    *CPU-ID不得超过（CPU核数 - 1）
    *使用 swoole_cpu_num() 可以得到当前服务器的CPU核数
    *setAffinity函数在1.7.18以上版本可用
    */
    public static function setAffinity(array $cpu_set)
    {

    }

    /*
    *将管道导出为Coroutine\Socket对象
    */
    public static function exportSocket()
    {

    }

}
