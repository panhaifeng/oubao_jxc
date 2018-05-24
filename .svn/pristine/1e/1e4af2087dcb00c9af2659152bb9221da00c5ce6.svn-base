<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/Calendar/WdatePicker.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.validate.js"}
{webcontrol type='LoadJsCss' src="Resource/Css/validate.css"}
{webcontrol type='LoadJsCss' src="Resource/bootstrap/bootstrap3.0.3/css/bootstrap.css"}

{literal}
<script language="javascript">
$(function(){
	$('#form1').validate({
		rules:{		
			'item':'required',
			'itemName':'required',
			'value':'required'
		},
		submitHandler : function(form){
			$('#Submit').attr('disabled',true);
			form.submit();
		}
	});
});
</script>
{/literal}
</head>
<body>
<form name="form1" id="form1" action="{url controller=$smarty.get.controller action='save'}" method="post" onSubmit="return CheckForm(this)">
<div class="table-responsive">
<table class="table table-hover">
<thead>
  <tr style="background:#C1DDF1;">
    <th>#</th>
	  <th>参数</th>
    <th>说明</th>
    <th>值</th>
	</tr>
</thead>
<tbody>
    <tr>
      <td>1</td>
      <td>Standard_State</td>
      <td>
        标准效率，用于计算挡车工工资单价
        <input name="itemName[]" type="hidden" id="itemName[]" value="标准效率"></td>
      <td>
        <div class="input-group input-group-sm" style="width:260px;">
          <input type="text" class='form-control' id="value[]" name="value[]" value="{$row.Standard_State|default:'90'}">
          <span class="input-group-addon">%</span>
        </div>
        <input type="hidden" id="item[]" name="item[]" value="Standard_State"></td>
    </tr>
    <tr class="active">
      <td>2</td>
      <td>Weimi_Danjia_Xishu</td>
      <td>
        纬密计算单价系数
        <input name="itemName[]" type="hidden" id="itemName[]" value="纬密计算单价系数"></td>
      <td>
        <div class="input-group input-group-sm" style="width:260px;">
          <input type="text" class='form-control' id="value[]" name="value[]" value="{$row.Weimi_Danjia_Xishu}">
        </div>
        <input type="hidden" id="item[]" name="item[]" value="Weimi_Danjia_Xishu"></td>
    </tr>
</tbody>
</table>
</div>
<table id="buttonTable" style="width:100%;">
<tr>
		<td align="center">
		<input class="btn btn-primary" type="submit" id="Submit" name="Submit" value=" 保 存 ">
		</td>
	</tr>
</table>
</form>
</body>
</html>
