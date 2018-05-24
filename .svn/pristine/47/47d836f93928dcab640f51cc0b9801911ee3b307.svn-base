<!DOCTYPE html>
<html>
<head>
<title>发货编辑</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.1.9.1.js"}
{webcontrol type='LoadJsCss' src="Resource/bootstrap/bootstrap/css/bootstrap.css"}
{webcontrol type='LoadJsCss' src="Resource/Css/scrollbar.css"}
{webcontrol type='LoadJsCss' src="Resource/Script/layer/layer.js"}
</head>

<script type="text/javascript">
var controller = '{$smarty.get.controller}';

{literal}
$(function(){
    $('[name="btnJiaoyan"]').click(function(){
        var that = this;
        var id = $('#id').val();
        var qrcodes = $('#qrcodes').val();
        if(!qrcodes){
            layer.alert('没有条码是不能校验的');
            return;
        }
        if(!id){
            layer.alert('出库单信息丢失了，请重新进入出库单操作');
            return;
        }

        //开始禁止重复点击校验按钮
        $(that).attr('disabled',true);
        $(that).text('校验中..');
        var url="?controller="+controller+"&action=DoJianyan";
        var param = {'id':id,'qrcodes':qrcodes};

        $.ajax({
          type: "POST",
          url: url,
          data: param,
          success: function(json){
            if(json.success==false){
                layer.alert(json.msg);
            }else{
                $('[name="status"]').text(json.text);
            }
          },
          dataType: 'json',
          async: false//同步操作
        });

        $(that).text('开始校验');
        $(that).removeAttr('disabled');
    });

    ///返回按钮
    $('#back').click(function(){
        window.parent.location.href = window.parent.location.href;
        // parent.tb_remove();
    });
});
</script>
<style type="text/css">

</style>
{/literal}
<body>
<div class='container'>
<form name="form1" id="form1" action="{url controller=$smarty.get.controller action='DoJianyan'}" method="post">
    <input type="hidden" name="id" id="id" value="{$Plan.id}">
    <!-- 校验码单 -->
    <div class="row">
      <div class="col-xs-12">
        <div class="form-group">
            <label for="qrcodes" class="col-sm-5 control-label">校验配货码单:</label>
            <div class="col-sm-7">
                <div class="input-group">
                    <input type="text" class="form-control" {if !$Plan.peihuoId>0}disabled placeholder="没有配货单，不需要校验码单"{else}placeholder="码单条码"{/if} name="qrcodes" id="qrcodes" >
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button" name="btnJiaoyan">开始校验</button>
                        <button class="btn btn-info" type="button" name="status">{if $Plan.jiaoyan_res!=''}{$Plan.jiaoyan_res}{else}结果{/if}</button>
                    </span>
                </div>
            </div>
        </div>
        </div>
    </div>
    
    
    <!-- 按钮区 -->
    <div class="form-group col-xs-12">
        <div class="text-center">
            <input class="btn btn-warning" type="button" id="back" name="back" value=" 返 回 ">
        </div>
    </div>
</form>
</div>
</body>
</html>