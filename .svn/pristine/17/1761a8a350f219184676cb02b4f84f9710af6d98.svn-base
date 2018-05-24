<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
{literal}
<script language="javascript">
function prnbutt_onclick() { 
    window.print(); 
    return true; 
} 
function window_onbeforeprint() { 
    prn.style.visibility ="hidden"; 
    return true; 
} 

function window_onafterprint() { 
    prn.style.visibility = "visible"; 
    return true; 
}
</script>
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
<table width="710px"  border="0" align="center" cellpadding="0" cellspacing="0" class="tablec">
  <tr {if $item.0.hang==0}class="top"{/if}>
    <td colspan="5" class="left">样品拣货单</td>
  </tr>
  <tr>
    <td class="left">订单号:</td>
    <td colspan="4">{$arr.0.orderCode}</td>
  </tr>
  {foreach from=$arr item=it}
  <tr>
    <td class="left" width="20%">花型:{$it.productId}</td>
    <td width="70%">{$it.cnt}</td>
  </tr>
  {/foreach}
  <tr>
    <td class="left">总数量:</td>
    <td colspan="3" align="left"> {$cntJian}件 {$hj.cnt}米</td>
  </tr>
</table>
<div id="prn" align="center">
<input type="button" id="button1" name="button1" value="打印" onclick="window_onbeforeprint();prnbutt_onclick();window_onafterprint();" />
</div>
</body>
</html>