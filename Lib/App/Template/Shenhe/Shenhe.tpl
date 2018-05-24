<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" id="viewport" content="width=device-width, initial-scale=1">
<title>审核</title>
</head>
<link rel="stylesheet" href="Resource/bootstrap/bootstrap/css/bootstrap.css">
</head>
{literal}
<style type="text/css">
.thead{
  width: 30px;
  white-space: nowrap;
  height: 50px;
}
#prnbutt{
  width: 60%;
}
#memo{
  height: 100px;
}
</style>
{/literal}
<body>
<form name="form1" id='form1' method="post" action="{url controller=$smarty.get.controller action='SaveShenhe'}">
<div id='divHtml'>
  <table id="table_main" class="table table-hover">
  <tbody>
        <tr class='trRow'>
          <td class="thead">审核：</td>
          <td>
           <input type='radio' name='status' value='yes' {if $row.status=='yes'}checked="checked"{/if}/>通过&nbsp;&nbsp;
           <input type='radio' name='status' value='no' {if $row.status=='no'}checked="checked" {/if}/>未通过&nbsp;&nbsp;
           {if $row.id>0}<input type='radio' name='status' value='remove'/>取消&nbsp;&nbsp;{/if}
           </td>
        </tr>
        <tr>
          <td class="thead">审核人：</td>
          <td>{$row.User.realName}</td>
        </tr>
        <tr>
        	<td>备注：</td>
        	<td><textarea name='memo' id='memo' class="form-control">{$row.memo}</textarea>
          <input type='hidden' name='id' id='id' value='{$row.id}'/>
          <input type='hidden' name='nodeId' id='nodeId' value='{$Arr.nodeId}'/>
          <input type='hidden' name='nodeName' id='nodeName' value='{$Arr.nodeName}'/>
          <input type='hidden' name='isLast' id='isLast' value='{$Arr.isLast}'/>
          <input type='hidden' name='tableId' id='tableId' value='{$Arr.id}'/>
          </td>
        </tr>
  </tbody>
     </table>
<div align="center" id='prn'>
<input id='prnbutt' type='submit' value="确定" class="btn btn-info">
<!-- <input id='cannel' onclick='window.parent.tb_remove();' type='button' value="取消" class="btn btn-warning"> -->
</div>
</div>
</form>
</body>
</html>
