#!/usr/local/bin/php
<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
ignore_user_abort(1);

//获取根目录
$_dir = str_replace('\\', '/', dirname(__FILE__));
$dir_name = explode('/', $_dir);
$_dirname = end($dir_name);

$_url = "localhost/{$_dirname}/index.php?controller=Crontab&action=Logs";

// file_get_contents(urlencode($_url.'Logs'));
// file_get_contents(urlencode($_url.'DoCrontab'));

$res = post($_url);


function post($url = '' ,$post_data = array())
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_URL,$url);
    //设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上, 0为直接输出屏幕，非0则不输出
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
    //curl_excc会输出内容，而$result只是状态标记
    $result = curl_exec($ch);
    $errorCode = curl_errno($ch);
    //释放curl句柄
    curl_close($ch);
    if(0 !== $errorCode) {
        return false;
    }
    return $result;
    return json_decode($result,true);
}

