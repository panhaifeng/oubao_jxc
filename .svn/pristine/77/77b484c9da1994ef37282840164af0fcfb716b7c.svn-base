<!DOCTYPE html>
<html>
<head>
  <title>{webcontrol type='GetAppInf' varName='systemName'}</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel="stylesheet" href="Resource/bootstrap/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="Resource/Css/scrollbar.css">
  {webcontrol type='LoadJsCss' src="Resource/Script/jquery.1.9.1.js"}
  {webcontrol type='LoadJsCss' src="Resource/Script/layer/layer.js"}
  {webcontrol type='LoadJsCss' src="Resource/bootstrap/bootstrap/js/bootstrap.js"}
</head>

<script type="text/javascript">
var controller = '{$smarty.get.controller}';
{literal}
  $(function(){
   $('[name="setShenhe"]').click(function(e){
    var id = $(this).attr('id');
    var title = $(this).attr('title');
    if(!id){
      alert("该审核缺少id，刷新后重新操作！");
      return false;
    }
    var url="?controller="+controller+"&action=SetUser&id="+id;
    //2014-9-24 by jeff,改为使用layer
    $.layer({
      type: 2,
      shade: [1],
      fix: false,
      title: title,
      maxmin: true,
      iframe: {src : url},
      area: ['660px' , '440px'],
      close: function(index){//关闭时触发
          document.location.href=document.location.href;
      },
      //回调函数定义
      callback:function(index,ret) {
        
      }
    });
  });
  });
</script>
<style type="text/css">

.bs-callout {
  padding: 20px;
  margin: 10px 0;
  border: 1px solid #eee;
  border-left-width: 5px;
  border-radius: 3px;
}
.bs-callout-warning {
  border-left-color: #1b809e;
}
.bs-callout h3{
  margin-top: -6px;
  color: #1b809e;
}
</style>
{/literal}
<body>
<div class="container">
  <h3>设置审核人员</h3>
  <!-- 审核菜单 -->
  <div id="tree" class="col-md-12">
  {foreach from=$Node key=key item=node}
    <div class="col-xs-12 bs-callout bs-callout-warning">
      <h3>{$key}审核</h3>
      <div class="btn-group" role="group" aria-label="">
        {foreach from=$node item=n key=k}
          <button type="button" class="btn btn-default" id="{$n.id}" name="setShenhe" title="{$key} > {$n.text}">
            {if $n.haveSet==1}<label class="glyphicon glyphicon-ok text-success"></label>
            <font class="text-success">{$n.text}</font>
            {else}
            {$n.text}
            {/if}
          </button>
        {/foreach}
      </div>
    </div>
  {/foreach}
  </div>
</div>
</body>
</html>