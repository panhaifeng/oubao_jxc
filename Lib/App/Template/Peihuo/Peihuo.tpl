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
{foreach from=$row item=item}
  <tr {if $item.0.hang==0}class="top"{/if}>
    <td colspan="5" class="left">Packing List</td>
  </tr>
  <tr>
    <td class="left">Component:</td>
    <td colspan="4">{$item.0.Product.Content}</td>
  </tr>
  <tr>
    <td class="left">Construction:</td>
    <td colspan="4">{$item.0.Product.shazhi}</td>
  </tr>
  <tr>
    <td class="left">Composition:</td>
    <td colspan="4">{$item.0.Product.chengfen}</td>
  </tr>
  <tr>
    <td class="left">Pattern No:</td>
    <td colspan="4">{$item.0.Product.proCode}</td>
  </tr>
  <tr >
    <td class="left">Mill No:</td>
    <td colspan="4" >{$item.0.millNo}</td>
  </tr>
  <tr>
    <td class="left">Bale No:</td>
    <td colspan="3">Qty:(M) Of Each Bale</td>
    <td colspan="3">Total(M)</td>
  </tr>
  {foreach from=$item item=it}
  <tr>
    <td class="left">{$it.baleNo|default:'&nbsp;'}</td>
    <td align="right">Roll:{$it.rollNo}</td>
    <td width="10%">{$it.cnt}</td>
    <td width="50%">{$it.kuqu}</td>
    <td>{$it.cntM}</td>
  </tr> 
  {/foreach}
{/foreach}
<tr>
    <td class="left" width="20%">Total:</td>
    <td width="20%" align="right">{$zJianshu}</td>
    <td colspan="2">&nbsp;</td>
    <td width="10%">{$zShuliang.cntM}</td>
  </tr>
</table>
<div id="prn" align="center">
<input type="button" id="button1" name="button1" value="打印" onclick="window_onbeforeprint();prnbutt_onclick();window_onafterprint();" />
</div>
</body>
</html>
