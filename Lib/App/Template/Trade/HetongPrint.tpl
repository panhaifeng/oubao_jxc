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
.tdd{font-size: 30px;height: 50px;}
.tablec tr td{

   border-bottom:1px solid black;
   height:50px;
   font-size: 13px;
}
.tabzhu tr td{
  height:23px;
  font-size: 13px;
}
.top td{
  border:1px solid black;
  font-weight: bold;
}

.tdHead { line-height:20px;vertical-align: top; white-space: nowrap; height: 28px; font-size: 13px;}
.tdtxt{ line-height:20px;vertical-align: top;font-size: 13px;}
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
        <td align="center" class="tdd"><b>售货确认书</b></td>
      </tr>
      <tr>
        <td align="center" class="tdd"><b>SALES CONFIRMATION</b></td>
      </tr>
    </table></td>
  </tr>
  <tr><td class="tdd">&nbsp;</td></tr>
  <tr>
    <td>
      <table width="100%"  border="0" cellspacing="0" cellpadding="1" class="tabzhu" >
        <tr>
          <td width="60%">卖方(Sellers)：常州溢代约克国际贸易有限公司</td>
        </tr>
          <tr>
          <td width="60%">买方(Buyers)：{$row.0.compName|default:'&nbsp;'}</td>
          <td>合同编号(s/c.No)：{$row.0.orderCode|default:'&nbsp;'}</td>
        </tr>
        <tr>
          <td>地址(ADD)：{$row.0.ship_addr|default:'&nbsp;'}</td>
          <td>签订时间(Date)：{$row.0.orderTime|default:'&nbsp;'}</td>
        </tr>
        <tr>
          <td>TEL:{$row.0.mobile|default:'&nbsp;'}</td>
          <td>签订地点(Signed At):常州</td>
        </tr>
        <tr>
          <td colspan="2">兹经买卖双方同意成交下列商品订立条款如下：</td>
        </tr>
        <tr>
          <td colspan="2">The undersigned Sellers and Buyers have agreed to close the following transactions according to the terms </td>
        </tr>
        <tr>
          <td colspan="2">and conitions stipulated below;</td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
      </table>
      </td>
  </tr>
  <tr><td>
    <table width="100%"  border="0" cellspacing="0" cellpadding="1" class="tablec">
      <tr align="center" class="top">
        <td colspan="2">流转单序号</td>
        <td colspan="2">货号<br/>Art.No</td>
        <td>商品名称与规格<br/>Description of Goods</td>
        <td>数量<br/>QTY</td>
        <td>单价<br/>Unit Price</td>
        <td>币种<br/>Currency</td>
        <td>金额<br/>Amount</td>
      </tr>
      {foreach from=$row item=item key=key}  
    <tr align="center" class="top">
        <td colspan="2">{$item.orderCode}-{$key+1}</td>
        <td colspan="2">{$key+1|default:'&nbsp;'}</td>
        <td>{$item.proName}&{$item.chengfen}</td>
        <td>{$item.cnt|default:'&nbsp;'}</td>
        <td>{$item.danjia|default:'&nbsp;'}/{$item.unit}</td>
        <td >{$item.currency|default:'&nbsp;'}</td>
        <td>{$item.ymoney|default:'&nbsp;'}</td>
    </tr>
    {/foreach}
    <tr align="center" class="top">
        <td colspan="3"><b>付款方式/Type of Payment</b>:&nbsp;</td>
        <td colspan="2"><b>Total:</b>&nbsp;{$hj.cnt}{$row.0.unit}</td>
        <td colspan="2"><b>freight:</b>&nbsp;{$row.0.cost_freight}</td>
        <td colspan="2">{$row.0.currency}&nbsp;{$row.0.money}</td>
    </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
    <table width="100%"  border="0" cellspacing="0" cellpadding="1">
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td class='tdHead'>第二 质量标准：</td><td class='tdtxt'>按客户确认的品质样和颜色样生产，不良率3%以内。不同缸号的面料不能混用。如果乙方对甲方的产品质量有异议，请在收货后15天内提出，乙方开裁视为合格。</td>
      </tr>
      <tr>
      <td class='tdHead'>第三 包装标准：</td><td  class='tdtxt'>塑料袋包装。特殊要求另行协商。</td>
      </tr>
      <tr>
        <td class='tdHead'>第四 交货数量：</td><td class='tdtxt'>大货数量允许 ±3%。</td>
      </tr>
      <tr>
        <td class='tdHead'>第五 交货方式：</td><td class='tdtxt'>由甲方送货到乙方指定国内地点， 费用由甲方负责，特殊情况另行协商。</td>
      </tr>
       <tr>
        <td class='tdHead'>第六 交货时间：</td><td class='tdtxt'>自乙方定金到甲方账户，并在乙方确认大货产前样品质后开始算交期。</td>
      </tr>
      <tr>
        <td class='tdHead'>第七 结算方式：</td><td class='tdtxt'>电汇方式，预付合同总金额的30%作为定金，余款提货前结清，如分批交货的，定金在最后一批货款中结算，付清全款后，开具增值税发票。
电汇方式，预付定金30%，面料到厂后马上支付60%，剩余10%一个月以内付清后，开具增值税发票。
电汇方式，发货后一个月内付款。</td>
      </tr>
        <tr>
        <td class='tdHead'>第八 争议解决：</td><td class='tdtxt'>本协议在履行过程中如发生争议，由双方协商解决；如协商不能解决，按下列两种方式解决(1) 提交签约地仲裁委员会仲裁； (2) 依法向人民法院起诉；</td>
      </tr> 
       <tr>
        <td class='tdHead'>第九  </td><td class='tdtxt'>本合同一式两份，由供、需方各执一份，双方签名盖章有效。</td>
      </tr> 
       <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table width="100%"  border="0" cellpadding="1" cellspacing="0">
      <tr>
        <td width="60%">甲方签字盖章</td>
        <td>乙方签字盖章</td>
      </tr>
      <tr>
        <td>委托代理人：<input type="text" style="width:100px; border:0px;" value="{$aRow.Trader.employName}" /></td>
        <td>委托代理人：<input type="text" style="width:100px; border:0px;" value="{$aRow.Client.people}"></td>
      </tr>
        <tr>
        <td>时间：&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="text" style="width:100px; border:0px;" value="" /></td>
        <td>时间：&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="text" style="width:100px; border:0px;" value="" /></td>
      </tr>
    </table></td>
  </tr>
</table>
<div id="prn" align="center">
<input type="button" id="button1" name="button1" value="打印" onclick="window_onbeforeprint();prnbutt_onclick();window_onafterprint();" />
</div>
</body>
</html>