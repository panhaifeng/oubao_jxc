<!DOCTYPE html>
<html>
<head>
<title>选择码单</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.1.9.1.js"}
{webcontrol type='LoadJsCss' src="Resource/bootstrap/bootstrap/css/bootstrap.css"}
{webcontrol type='LoadJsCss' src="Resource/Css/scrollbar.css"}
{webcontrol type='LoadJsCss' src="Resource/Script/layer/layer.js"}
</head>

<script type="text/javascript">
var controller = '{$smarty.get.controller}';
var index = '{$smarty.get.index}';
var madan_id=parent.$('[name="Madan[]"]').eq(index).val();
{literal}
$(function(){
    //初始化显示的数据
    var madan_ids = madan_id.split(',');
    //选中之前选择的数据，
    $('[name="docheck[]"]').each(function(){
        if(madan_ids.indexOf(this.value)>=0){
            $(this).prop('checked',true);
        }
    });


    //全选/反选
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
        var obj = {'madanIds':che_data.madanId,'cntM':che_data.cntMi,'cntJian':che_data.cntJian,'danjia':che_data.danjia};
        parent.layer.callback(index,obj);
        parent.tb_remove();
    });

    //back
    $('#back').click(function(){
        parent.tb_remove();
    });
});

function getSelData(){
    var data = [];
    var cnt = 0;
    var danjia = 0;
    var i=0;
    $('[name="docheck[]"]:checked').each(function(){
        // if(!this.disabled){
            data.push(this.value);
            var t_cnt = parseFloat($(this).attr('cntMi'))||0;
            cnt+=t_cnt;
            var t_danjia = parseFloat($(this).attr('danjia'))||0;
            danjia = t_danjia;
            i++;
        // }
    });

    return {'madanId':data.join(','),'cntMi':cnt.toFixed(6),'cntJian':i,'danjia':danjia.toFixed(6)};
}
</script>
<style type="text/css">
    .bottom-5{margin-bottom: 5px;}
</style>
{/literal}
<body>
<div class='container-fluid'>
<form name="form1" id="form1" action="{url controller=$smarty.get.controller action='Peihuo'}" method="post">
    <input type="hidden" name="id" id="id" value="{$Peihuo.id}">
    <!-- 校验码单 -->
    <div class="row">
        <div class="col-xs-12">
            <div class="form-group">
                <pre>
                产品信息: {$Product.proCode}   {$Product.proName} {$Product.jingmi} {$Product.weimi} {$Product.chengfen} {$Product.zhengli} {$Product.wuliaoKind} {$Product.zuzhi}
                </pre>
            </div>
        </div>
    </div>

    <div class="form-contrl"><input type="checkbox" name='sel' value="">全选/反选
    {if $ispei!='true'}(显示的是该批号该花型六位号的码单){/if}</div> 
    {foreach from=$Peihuo.Peihuo item=ph key=key}
    <div class="row" style="margin-top:5px;">
        <div class="col-xs-2">
        <p class="bg-success"><strong>{$key}</strong></p>
        </div>
    </div>
    <div class="row">
        {foreach from=$ph item = madan key = k}
        <div class="col-xs-3">
            <div class="form-control bottom-5">
                <p {if $madan.disabled}class="text-danger" title='{$madan.title}'{/if}>
                <b>{$madan.rollNo}</b># {$madan.cnt}{$madan.unit} <!-- ({$madan.cntM} M) -->&nbsp;&nbsp;
                <input type="checkbox" name='docheck[]' value='{$madan.id}' cntMi='{$madan.cntMi}' style="width:15px;height:15px;" {if $madan.checked}checked{/if} {if $madan.disabled}disabled{/if}>
                </p>
            </div>
        </div>
        {/foreach}
    </div>
    {/foreach}
    
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