<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
{literal}

<style type="text/css">
.tablec tr td{
   border-bottom:1px solid #000;
   border-right:1px solid #000;
   height:20px;
   font-size: 13px;
   padding:0px 5px 0px 5px;   
   /*text-align: center;*/
}
.top td{
  border-top:1px solid #000;
  text-align: center;
}
.left{
  border-left:1px solid #000;
  text-align: center;
}
</style>
{/literal}
</head>
<body>
<table width="710px"  border="0" align="center" cellpadding="0" cellspacing="0" >
      <tr>
        <td align="center" ><b>成本价详细</b></td>
      </tr>
</table>
<table width="710px"  border="0" align="center" cellpadding="0" cellspacing="0" class="tablec">
  <tr class="top">
    <td class="left">批号:</td>
    <td>花型六位号:</td>
    <td >数量:</td>
    <td>单价:</td>
    <td>金额:</td>
  </tr>
  {foreach from=$row item=item}
  <tr>
    <td class="left" >{$item.millNo}</td>
    <td>{$item.productId}</td>
    <td>{$item.cntM}</td>
    <td>{$item.danjia}</td>
    <td>{$item.money}</td>
  </tr>
  {/foreach}
  <td height="20" class="left" colspan="4">
    <div align="center">合计:</div></td>
    <td ><div align="left">{$hj.money|default:'&nbsp;'}</div></td>
  </td>
</table>

</body>
</html>