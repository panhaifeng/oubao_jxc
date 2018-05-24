<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.1.9.1.js"}
<title>{$title}</title>
<link rel="stylesheet" href="Resource/bootstrap/bootstrap3.0.3/css/bootstrap.css">
{literal}
<script language="javascript">
	$(function(){
		$('#btnCancel').click(function(){
			window.parent.location.href=window.parent.location.href;
		});	   
		$('#btnBack,#btnBackAll').click(function(){
			var id=$('#id').val();
			var url="?controller=Mail&action=add&isBack=1&mailId="+id;
			if(this.id=='btnBackAll'){
				url+="&isAll=1";
			}
			window.parent.location.href=url;
		});		   
	});
</script>
<style type="text/css">
.btnGroup{
  position: absolute;
  top: 0px;
  right: 0px;
}
.line{border-bottom: solid 1px #996600;}
td{font-size:12px;}
input{ border:1px #999 solid; height:25px;}
#accepter{
  padding: 1px 5px 1px 0px;
}
</style>
{/literal}
</head>
<body>
<table id="mainTable">
<input type="hidden" name="hiddenField" id="hiddenField" value="{$smarty.session.USERNAME}">
<input type="hidden" name="id" id="id" value="{$row.id}">
<!-- 按钮组 -->
<div class="btn-group btnGroup">
  <button type="button" class="btn btn-danger" name="btnCancel" id="btnCancel">关 闭</button>
  <button type="button" class="btn btn-success" name="btnBack" id="btnBack">回 复</button>
  <button type="button" class="btn btn-warning" name="btnBackAll" id="btnBackAll">全部回复</button>
</div>
<!-- 标题 -->
<pre>
<span class="glyphicon glyphicon-leaf"></span>&nbsp;主题：{$row.title|default:'&nbsp;'}
<span class="glyphicon glyphicon-calendar"></span>&nbsp;发送时间：{$row.dt|default:$smarty.now|date_format:'%Y-%m-%d'}
<span title='发件人' class="glyphicon glyphicon-send"></span>&nbsp;发件人：{$row.Sender.realName}
<span title='收件人' class="glyphicon glyphicon-gift"></span>&nbsp;收件人：{foreach from=$accepter item=item}<span id="accepter">{$item.realName}</span>{/foreach}
</pre>
<div style="border:1px solid #efefef;min-height:220px;padding:5px 8px 5px 8px;">
{$row.content|default:'&nbsp;'}
</div>
</body>
</html>
