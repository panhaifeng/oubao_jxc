<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>打印出库单</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  {webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
  {webcontrol type='LoadJsCss' src="Resource/Script/Print/LodopFuncs.js"}
  <object id="LODOP_OB" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width=0 height=0> 
    <embed id="LODOP_EM" type="application/x-print-lodop" width=0 height=0 pluginspage="install_lodop.exe"></embed>
    <param name="CompanyName" value="常州易奇信息科技有限公司">
    <param name="License" value="664717080837475919278901905623">
  </object> 
</head>

<script type="text/javascript">
var controller = '{$smarty.get.controller}';
var id = '{$smarty.get.id}';
{literal}
  $(function(){
    $('#button').click(function(){
      var url="?controller="+controller+"&action=UpdatePrint";
      var param={'id':id};
      $.getJSON(url,param,function(data){

      });
    });
  });

  var LODOP; //声明为全局变量
  function PrintTable(){
    //加载打印控件
    LODOP=getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));
    //设置纸张  
    //LODOP.SET_PRINT_PAGESIZE(0,'203mm','290mm','A4')
    LODOP.PRINT_INIT("易奇科技报表打印");
    //设置居中显示LODOP.ADD_PRINT_HTM(Top,Left,Width,Height,strHtml)
    //in(英寸)、cm(厘米) 、mm(毫米) 、pt(磅)、px(1/96英寸) 、%(百分比)，如"10mm"表示10毫米。
    LODOP.ADD_PRINT_HTM("0%", "0%", "90%", "100%", document.getElementById("printBox").innerHTML);
    LODOP.SET_PRINT_STYLEA(0,"Horient",2);
    // LODOP.ADD_PRINT_BARCODE(100,27,151,39,"",$('#tiaoma').val());
    //预览
    // LODOP.PRINT_DESIGN();
    LODOP.PREVIEW();    
  };
  function loadPrint(){
    var auto={/literal}{$smarty.get.auto|@json_encode}{literal};
    if(auto==1)document.getElementById('button').click();
  }
</script>
{/literal}
<body onload="loadPrint()">
<div id="printBox" style=" width:720px; margin:0 auto; position:relative;">
{*以下为lodop载入时需要css,不能放到head中*}
{literal}
<style>
/*打印按钮样式*/
#divPrint { position:fixed; bottom:10px; right:250px;}
#divPrint input {  background-color: #0362fd !important; border:0px; color: #fff !important; font-family: "Segoe UI",Helvetica,Arial,sans-serif;cursor: pointer; outline: medium none; font-size:14px; padding: 7px 14px;}
.tablec tr td{
   border-bottom:1px solid #000;
   border-right:1px solid #000;
   height:16px;
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
<table width="710px"  border="0" align="center" cellpadding="0" cellspacing="0" >
  <!-- <table width="100%"  border="0" cellspacing="0" cellpadding="1" > -->
    <tr>
      <td width="20%">&nbsp;</td>
      <td width="50%" align="center"><strong style="font-size:22px;">常州溢代约克国际贸易有限公司</strong></td>
      <td width="30%">&nbsp;</td>
    </tr>
    <tr>
      <td width="20%" style="font-size:13px;">付款方式：{$rowset.0.payment|default:'&nbsp;'}</td>
      <td width="50%" align="center" style="font-size:13px;">YD YORK INTERNATIONAL TRADE（CHANGZHOU）CO LTD</td>
      <td width="30%">&nbsp;</td>
    </tr>
    <!-- <tr>
      <td width="20%" style="font-size:13px;"><input type='hidden' id='tiaoma' value='STYD15815005'/></td>
      <td>&nbsp;</td>
      <td width="30%">&nbsp;</td>
    </tr> -->
  </table>
  <table  width="710px">
        <!-- <tr>
            <td width="20%">&nbsp;</td>
            <td width="50%">&nbsp;</td>
            <td width="30%">&nbsp;</td>
        </tr> -->
        <tr>
            <td>&nbsp;</td>
            <td width="50%" align="center"><strong style="font-size:22px;">送货单</strong></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td width="20%">&nbsp;</td>
            <td width="50%">&nbsp;</td>
            <td width="30%" style="font-size:13px;">出货时间:{$rowset.0.chukuDate|default:'&nbsp;'}</td>
        </tr>
  </table>
  <table  class="tablec"  align="center" cellpadding="0" cellspacing="0" >
        <tr class="top">
            <td width="160" class="left">客户名称</td>
            <td colspan="5"  >{$rowset.0.compName|default:'&nbsp;'}</td>
            <td colspan="2" width="171">{$rowset.0.orderCode|default:'&nbsp;'}</td>
        </tr>
        <tr >
            <td width="160" class="left">收货单位</td>
            <td colspan="5"  align="center">{$rowset.0.compName|default:'&nbsp;'}</td>
            <td width="171" >收货人</td>
            <td width="92">{$rowset.0.ship_name|default:'&nbsp;'}</td>
        </tr>
        <tr>
            <td width="160"  class="left">收货地址</td>
            <td colspan="5"  align="center">{$rowset.0.ship_addr|default:'&nbsp;'}</td>
            <td width="171" >收货单位 联系电话</td>
            <td width="92">{$rowset.0.ship_mobile|default:'&nbsp;'}</td>
        </tr>

        <tr>
          <td valign="center" height="19" class="left">备注</td>
          <td height="19" colspan="7" valign="center">&nbsp;</td>
    </tr>
        <tr>
            <td width="160" valign="center" height="50" class="left">花型六位号<br/>Pattern SN</td>
            <td width="84" valign="center" height="50">客户花型<br/>Pattern NO</td>
            <td width="84" valign="center" height="50">品名<br/>OrderNo </td>
            <td width="80" valign="center"  height="50">规格<br/>Spec</td>
            <td width="94" valign="center"  height="50">件数 &nbsp; NO OF BALES</td>
            <td width="92" valign="center"  height="50">卷号<br/>NO OF ROLLS</td>
            <td width="171" valign="center"  height="50">送货数<br/>QTY.</td>
            <td width="92" valign="center"  height="50">&nbsp;</td>
        </tr>
        {foreach from=$rowset item=item} 
        <tr>
            <td width="160" valign="center" class="left">{$item.proCode}</td>
            <td width="84" valign="center"></td>
            <td width="84" valign="center">{$item.proName}</td>
            <td width="80" valign="center">{$item.guige}</td>
            <td width="94" valign="center">{$item.baleNo}</td>
            <td width="92" valign="center">{$item.rollNo}</td>
            <td width="171" valign="center">{$item.cnt}{$item.unit}</td>
            <td width="92" valign="center">&nbsp;</td>
        </tr>
        {/foreach}
        <tr>
            <td width="160" class="left" colspan="4" align="center">合计</td>
            <td width="94" valign="center">{$heji.baleNo|default:'&nbsp;'}</td>
            <td width="92" valign="center">{$heji.rollNo|default:'&nbsp;'}</td>
            <td width="171" valign="center">{$heji.cnt|default:'&nbsp;'}</td>
            <td width="92" valign="center"></td>
        </tr>
</table>
<table border="0px" width="710px">
        <tr>
            <td align="left" width="20%">打印人:<font style="padding-left:15px; padding-right:15px;">{$rowset.0.people|default:'&nbsp;'}</font></td>
            <td width="35%" align="center">打印时间：{$rowset.0.time|default:'&nbsp;'}</td>
            <td width="45%" align="left" style="padding-right:30px;">收货单位人员确认后签字或盖章：<font style="border-bottom: 1px dotted #000; ">   </font></td>
        </tr>
</table>
</div>
<!-- <input type="button" id="btn_print" value='确定并打印 (打印后表示发货成功)' onclick="PrintTable()"> -->
<!-- 打印按钮 -->
<div id="divPrint" class="wrap">
    <input type="button" name="button" id="button" value="打印" onclick="PrintTable()"/>
</div>
</body>
</html>