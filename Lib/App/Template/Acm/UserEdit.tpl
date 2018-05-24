<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.validate.js"}

{webcontrol type='LoadJsCss' src="Resource/Css/validate.css"}
<link rel="stylesheet" href="Resource/bootstrap/bootstrap/css/bootstrap.css">
{literal}
<script language="javascript">
$(function(){
	$.validator.addMethod("checkPass", function(value, element) {
		var o = document.getElementById('passwd');	
		if(o.value!=value || value=='')
		return false;
		return true;
	}, "密码不匹配!");
	
	$('#form1').validate({
		rules:{
			userName:"required",
			realName:"required",
			passwd:"required",
			PasswdConfirm:"checkPass"
		},
		submitHandler : function(form){
			$('[name="Submit"]').attr('disabled',true);
			form.submit();
		}
	});
	//ret2cab();
});
</script>
<style type="text/css">
#divMain{width:100%; border:0px #D4E2F4 solid;}
#divLeft{width:47%; border:0px #D4E2F4 solid; float:left;}
#divRight{width:47%; border:0px #D4E2F4 solid; float:right;}
#tblLeft{ width:100%;}
#tblLeft tr td{border:0px; border-bottom:1px #D4E2F4 dotted; padding-left:20px;height: 26px;}

#tblLeft tr td input{border:0px;}
#tblRight{ width:100%;}
#tblRight tr td{border:0px; border-bottom:1px #D4E2F4 dotted; padding-left:20px;height: 26px;}
#tblRight tr td input{border:0px;}
#mainTable tr td{
	border: 0px;
}
#mainTable input{
	width: 200px;
}
body{
	margin: 5px 5px;
}
</style>
{/literal}

</head>

<body>
<form name="form1" id="form1" action="{url controller=$smarty.get.controller action='save'}" method="post">
<input name="id" type="hidden" id="id" value="{$aUser.id}" />
<input name="from" type="hidden" id="from" value="right" />

<div class="panel panel-default">
  <div class="panel-heading"><h3 class="panel-title" style="text-align:left;">基本信息</h3></div>
  <div class="panel-body">
    <div class="row">
     <table id="mainTable" class="table ">
	  <tr>
	    <td align="right" class="tdTitle">用户名：</td>
	    <td><input name="userName" type="text" id="userName" value="{$aUser.userName}" class="form-control" /></td>
	    <td align="right" class="tdTitle">真实姓名：</td>
	    <td><input name="realName" type="text" id="realName" value="{$aUser.realName}" class="form-control"/></td>
	    <td align="right" class="tdTitle">身份证号：</td>
	    <td><input name="shenfenzheng" type="text" id="shenfenzheng" value="{$aUser.shenfenzheng}" class="form-control"/></td>
	    </tr>
	  <tr>
	    <td align="right" class="tdTitle">登陆密码：</td>
	    <td><input name="passwd" type="password" id="passwd" value="{$aUser.passwd}" class="form-control"/></td>
	    <td align="right" class="tdTitle">密码确认：</td>
	    <td><input name="PasswdConfirm" type="password" id="PasswdConfirm" value="{$aUser.passwd}" class="form-control" check="^\S+$" warning="重复密码不能为空！"/></td>
	     <td align="right" class="tdTitle">手机号码：</td>
	    <td><input name="phone" type="text" id="phone" value="{$aUser.phone}" class="form-control"/></td>
	    </tr>
	 </table>
    </div>       
  </div>
</div>

<div id="divMain">
<div id='divLeft'>
<div class="panel panel-default">
  <div class="panel-heading"><h3 class="panel-title" style="text-align:left;">选择角色</h3></div>
  <div class="panel-body" style="overflow:auto;height:260px;">
		<table id="tblLeft">
		{foreach from=$aUser.Role item=item}
		<tr>
			<td><input type="checkbox" name="roles[]" id="ckb{$item.id}" value="{$item.id}" {if $item.isChecked==1}checked{/if}>
		    <label for="ckb{$item.id}">{$item.roleName}</label></td>
		</tr>
		{/foreach}
		</table>
	</div>
</div>
</div>
</div>
<div id="divRight">
<div class="panel panel-default">
  <div class="panel-heading"><h3 class="panel-title" style="text-align:left;">选择相关人员（指定员工后，可看到该员工的客户与合同）</h3></div>
  <div class="panel-body" style="overflow:auto;height:260px;">
		<table id="tblRight">
		{foreach from=$aUser.Trader item=item}
		<tr>
			<td><input type="checkbox" name="traders[]" id="trader{$item.id}" value="{$item.id}" {if $item.isChecked==1}checked{/if}>
		    <label for="trader{$item.id}">{$item.employName}</label></td>
		</tr>
		{/foreach}
		</table>
</div>
</div>
</div>
</div>
<table id="buttonTable" align="center">
	<tr>
    	<!-- <td><input type="submit" id="Submit" name="Submit" class="btn btn-primary" value='保存并新增下一个' class="button"></td> -->
    	<td><input type="submit" id="Submit" name="Submit" class="btn btn-success" value='保存' class="button"></td>
        <td>&nbsp;<input type="button" id="Back" name="Back" class="btn btn-danger" value='返回' onClick="javascript:window.history.go(-1);" class="button"></td>
    </tr>
</table>
</form>
</body>
</html>
