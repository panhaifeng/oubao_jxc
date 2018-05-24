<!DOCTYPE html>
<html>
<head>
<title>选择码单</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.1.9.1.js"}
{webcontrol type='LoadJsCss' src="Resource/bootstrap/bootstrap/css/bootstrap.css"}
{webcontrol type='LoadJsCss' src="Resource/Script/layer/layer.js"}
</head>
{literal}
<style type="text/css">
.j {width:42px; float:left;border:0px solid #000; margin-top: 2px;margin-left: 2px;}
.s {width:54px; float:left;border:0px solid #000; margin-top: 2px;margin-left: -1px;}
.c {width:20px; float:left;border:0px solid #000; margin-top: 2px;margin-left: 2px;}
.j1 {width:42px; float:left;border:0px solid #000; margin-top: 2px;margin-left: 10px;}
.s1 {width:54px; float:left;border:0px solid #000; margin-top: 2px;margin-left: -1px;}
</style>
{/literal}
<script type="text/javascript">
var index='{$smarty.get.index}';
{literal}
$(function(){
    //全选和反选
    $('[name="sel"]').click(function(){
        $('[name="docheck[]"]').prop('checked',this.checked);
    });
    //ok
    $('#ok').click(function(){
        var che_data = getSelData();
        if(!che_data.madanId){
            layer.alert("先选择要出库的码单信息");
        }

        //返回已选择的数据
        var obj = {'madanIds':che_data.madanId,'cntM':che_data.cntM,'cnt':che_data.cnt,'cntJian':che_data.cntJian};
        parent.layer.callback(index,obj);
        parent.tb_remove();
    });
});
function getSelData(){
    var data = [];
    var cnt = 0;
    var i=0;
    $('[name="docheck[]"]:checked').each(function(){
        if(!this.disabled){
            data.push(this.value);
            var t_cntM = parseFloat($(this).attr('cntM'))||0;
            cntM+=t_cntM;
             var t_cnt = parseFloat($(this).attr('cnt'))||0;
            cnt+=t_cnt;
            i++;
        }
    });

    return {'madanId':data.join(','),'cntM':cntM,'cntM':cntM,'cntJian':i};
}
</script>

{/literal}
<body>
<div class='container'>
<form name="form1" id="form1" action="{url controller=$smarty.get.controller action='Peihuo'}" method="post">
    <input type="hidden" name="id" id="id" value="{$Peihuo.id}">
    <!-- 校验码单 -->
    <div class="row">
        <div class="col-xs-12">
            <div class="form-group">
                <pre>
                产品信息: {$row.0.proCode}   {$row.0.proName} {$row.0.jingmi} {$row.0.weimi} {$row.0.chengfen} {$row.0.zhengli} {$row.0.wuliaoKind} {$row.0.zuzhi}
                </pre>
            </div>
        </div>
    </div>

    <div class="form-contrl"><input type="checkbox" name='sel' value="">全选/反选</div> 
    <div class="row">
         <div class="col-xs-3">
            <div class='j1'>卷号</div><div class='s1'>数量</div><div class='s1'>条码</div><div class='j1'>选择</div>
        </div>
        <div class="col-xs-3">
            <div class='j1'>卷号</div><div class='s1'>数量</div><div class='s1'>条码</div><div class='j1'>选择</div>
        </div>
        <div class="col-xs-3">
            <div class='j1'>卷号</div><div class='s1'>数量</div><div class='s1'>条码</div><div class='j1'>选择</div>
        </div>
        <div class="col-xs-3">
            <div class='j1'>卷号</div><div class='s1'>数量</div><div class='s1'>条码</div><div class='j1'>选择</div>
        </div>
    </div>
    <div class="row">
        {foreach from=$row item=item}
        <div class="col-xs-3">
            <div class="form-control">
                <div class='j'>{$item.rollNo}</div><div class='s'>{$item.cnt}{$item.unit}</div><div class='s'>{$item.qrcode}</div><div class='c'><input type="checkbox" name='docheck[]' value='{$item.id}' cntM='{$item.cntM}' cnt='{$item.cnt}' style="width:15px;height:15px;"/></div>
            </div>
        </div>
        {/foreach}
    </div>
    
    <!-- 按钮区 -->
    <div class="form-group col-xs-12">
        <div class="text-center">
            <input class="btn btn-default" type="button" id="ok" name="ok" value=" 确定 ">
            <input class="btn btn-warning" type="button" id="back" name="back" value=" 返 回 ">
        </div>
    </div>
</form>
</div>
</body>
</html>