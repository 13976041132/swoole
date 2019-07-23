<?php
    /**
     * @author      : xingfuyi
     * @createTime  : 2019/3/19 下午5:16
     * @description :
     */


    require_once(__DIR__ . '/init.php');

    try{

        if(IS_CLI){

            (new \Eli\Job\Core\Console($config))->run();

        }

    }catch (exception $e){
        var_dump($e);
    }

