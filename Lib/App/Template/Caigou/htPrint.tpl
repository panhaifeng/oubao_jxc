<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
<link href="Resource/Css/Print.css" type="text/css" rel="stylesheet" />
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
.tdd{font-size: 25px;height: 20px;}
.tablec tr td{
   border-right:1px solid black;
   border-bottom:1px solid black;
   border-color:#000 1px;
     height:30px;
}
.left{
    border-left:1px solid black;
}
.top td{
    border-top:1px solid black;
}
</style>
{/literal}
</head>
<body onafterprint="return window_onafterprint()" onbeforeprint="return window_onbeforeprint()">
<table width="710px"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="100%"  border="0" cellspacing="0" cellpadding="1">
      <tr>
        <td align="center" >&nbsp;</td>
      </tr>
      <tr>
        <td align="center" class="tdd"><b>常州溢代约克国际贸易有限公司采购单</b></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>
      <table width="100%"  border="0" cellspacing="0" cellpadding="1">
        <tr>
          <td width="60%">甲方（供方）：{$row.0.compName|default:'&nbsp;'}</td>
          <td>合同编号：{$row.0.orderCode|default:'&nbsp;'}</td>
        </tr>
        <tr>
          <td>乙方（需方）：常州溢代约克国际贸易有限公司</td>
          <td>签订地点：常州</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table>
      </td>
  </tr>
  <tr><td>
    <table width="100%"  border="0" cellspacing="0" cellpadding="1" class="tablec">
      <tr align="center" class="top">
        <td class="left">供应商</td>
        <td >产品交期</td>
        <td>花型六位号</td>
        <td>成分</td>
        <td>颜色</td>
        <td>纱支</td>
        <td>经纬密</td>
        <td>门幅</td>
        <td>要货数</td>
        <td>单价</td>
      </tr>
      {foreach from=$row item=item}  
    <tr>
        <td class="left">{$item.compName}</td>
        <td>{$item.jiaoqi}</td>
        <td>{$item.proCode}</td>
        <td>{$item.chengfen}</td>
        <td>{$item.color}</td>
        <td>{$item.shazhi}</td>
        <td>{if $item.jingmi!=''||$item.weimi!=''}{$item.jingmi}*{$item.weimi}{/if}&nbsp;</td>
        <td>{$item.menfu}</td>
        <td>{$item.cnt}</td>
        <td>{$item.danjia}</td>
    </tr>
    {/foreach}
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <!-- <tr>
    <td><table width="100%"  border="0" cellpadding="1" cellspacing="0">
      <tr>
        <td width="60%">甲方签字盖章</td>
        <td>乙方签字盖章</td>
      </tr>
      <tr>
        <td>委托代理人：{$aRow.Trader.employName}</td>
        <td>委托代理人：{$aRow.Client.people}</td>
      </tr>
        <tr>
        <td>时间：&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td>时间：&nbsp;&nbsp;&nbsp;&nbsp;</td>
      </tr>
    </table></td>
  </tr> -->
</table>
<div id="prn" align="center">
<input type="button" id="button1" name="button1" value="打印" onclick="window_onbeforeprint();prnbutt_onclick();window_onafterprint();" />
</div>
</body>
</html>