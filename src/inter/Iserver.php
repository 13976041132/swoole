<?php
/**
 * @author      : xingfuyi
 * @createTime  : 2019/3/18 上午10:42
 * @description :
 */

namespace swoole\inter;

 interface Iserver {

	 public function start();


	 public function reload();

	 public function stop();

	 public function kill();
 }
