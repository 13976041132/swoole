<?php
/**
 * @author      : xingfuyi
 * @createTime  : 2019/3/27 下午5:13
 * @description :
 */

namespace swoole\Http;

class Request
{

    //请求的头部信息。类型为数组，所有key均为小写
    public $heaher = array();

    //Http请求相关的服务器信息
    public $server = [];

    //get参数，格式为数组
    public $get = [];

    //POST参数，格式为数组
    public $post = [];

    //请求携带的COOKIE信息，格式为键值对数组
    public $cookie = [];

    /*类型为以form名称为key的二维数组。
    *与PHP的$_FILES相同。最大文件尺寸不得超过package_max_length设置的值。
    *请勿使用Swoole\Http\Server处理大文件上传
    */
    public $file = [];

    //获取原始的POST包体，用于非application/x-www-form-urlencoded格式的Http POST请求
    public function rawContent(){}

    //获取完整的原始Http请求报文。包括Http Header和Http Body
    public function getData()
    {
    }
}
