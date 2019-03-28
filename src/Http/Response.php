<?php
/**
 * @author      : xingfuyi
 * @createTime  : 2019/3/27 下午5:18
 * @description :
 */

namespace swoole\Http;

class Response
{

    //设置响应携带的COOKIE信息，格式为键值对数组
    public $cookie = [];

    //设置响应的头部信息。类型为数组，所有key均为小写
    public $heaher = [];

    //发送Http状态码
    public function status(int $http_status_code)
    {

    }

    //发送Http跳转。调用此方法会自动end发送并结束响应
    public function redirect(string $url, int $http_code = 302)
    {
    }

    //启用Http Chunk分段向浏览器发送相应内容。关于Http Chunk可以参考Http协议标准文档。
    public function write(string $data)
    {
    }

    //发送文件到浏览器
    public function sendfile(string $filename, int $offset = 0, int $length = 0)
    {
    }

    //发送Http响应体，并结束请求处理
    public function end(string $html)
    {
    }

    //分离响应对象。使用此方法后，$response对象销毁时不会自动end，与Http\Response::create和Server::send配合使用
    public function detach()
    {
    }

    //分离响应对象。使用此方法后，$response对象销毁时不会自动end，与Http\Response::create和Server::send配合使用
    public static function create(int $fd): Response
    {
    }


}
