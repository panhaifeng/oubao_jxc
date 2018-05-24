<?php
/**
 * 自动计划任务
 *
 * @copyright  eqinfo lwj
 */

//自动化计划任务列表
$crontab_list = array(
    array(
        'time'=>'120',//分钟
        'isActive'=>'1',//0不开启，1开启
        'address'=>'Controller_SMS_SMS@send',//controller@function
        'desc'=>'合同审核2小时未审核自动提醒'
    ),
);

?>

