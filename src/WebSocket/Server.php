<?php
/**
 * @author      : xingfuyi
 * @createTime  : 2019/3/27 下午4:15
 * @description :
 */

namespace Swoole\WebSocket;

class Server extends \Swoole\Server
{
    //推送数据到前端
   public function push( $fd, $data, $opcode = 1, $finish = TRUE){}

   //判断连接是否存在
   public function exist($fd)
   {
       parent::exist($fd);
   }

   //打包WebSocket消息
   public function pack($data, $opcode = 1, $finish = TRUE, $mask = FALSE): Frame{}

    //主动向websocket客户端发送关闭帧并关闭该连接
    public function disconnect($fd, $code = 1000, $reason = ""){}

    //检查连接是否为有效的WebSocket客户端连接。此函数与exist方法不同，exist方法仅判断是否为TCP连接，无法判断是否为已完成握手的WebSocket客户端
    public function isEstablished(int $fd){}

}
