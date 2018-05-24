<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html>
<head>
<meta name="viewport" id="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Cache-control" content="no-cache" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>审核查看</title>
<link rel="stylesheet" href="Resource/bootstrap/bootstrap/css/bootstrap.css">
{literal}
<style type="text/css">
body{
    background-color: #dedede;
}
.title{
    background: #fff;
    padding: 10px 10px;
    border-radius: 4px;
}
.status{
    clear: both;
}
.col-self-6{
    width: 50%;
    float: left;
}
.list-group-item{
    margin-bottom: 6px;
    border-radius: 4px;
}
</style>
{/literal}
</head>
<body>
<div class="container-fluid">
    <h3 class="title">销售合同审核列表</h3>
    <ul class="list-group">
    {foreach from=$arr_field_value item=item}
        <li class="list-group-item">
            <div class="row">
                <div class="col-xs-12">{$item._edit}</div>
            </div>
            <div class="row">
                <div class="col-xs-12">订单:{$item.orderCode}</div>
            </div>
            <div class="row">
                <div class="col-xs-12 text-primary">客户:{$item.compName}</div>
            </div>
            <div class="row">
                <div class="col-xs-12 text-danger">日期:{$item.orderTime}</div>
            </div>
            <div class="row">
                <div class="col-xs-12 text-success">产品:{$item.proCode} {$item.proName}</div>
            </div>
            <div class="row">
                <div class="col-xs-6">金额:{$item.m_pro}</div>
                <div class="col-xs-6">数量:{$item.cnt}</div>
            </div>
            <div class="row">
                <div class="col-xs-6 status">
                    <div class="col-self-6">状态:</div>
                    <div class="col-self-6">{$item.status}</div>
                </div>
                <div class="col-xs-6">
                    类型:{$item.kind}
                </div>
            </div>
        </li>
    {/foreach}
    </ul>
</div>
</body>
</html>
