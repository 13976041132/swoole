<?php
/**
 * @author      : xingfuyi
 * @createTime  : 2019/3/27 下午5:03
 * @description :
 */

namespace Swoole\WebSocket;

class Frame
{

    public $fd;

    public $data;

    public $finish;

    public $opcode;

    public $code;

    public $reason;

}
