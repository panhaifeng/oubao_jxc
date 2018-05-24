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
    // LODOP.TEXT_SHOW_BORDER("single");
    //应欧宝需求条码暂时隐藏
    // LODOP.ADD_PRINT_BARCODE(40,265,151,39,"",$('#tiaoma').val());

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
/*#trmain td{
  border-bottom:1px dotted #000;
  padding-left:3px;
  padding-right:2px;
  height:22px;
}

.tb{
border-collapse:collapse;}
.tb td {
border:1px solid black;}*/
</style>
{/literal}
<table width="400px"  border="0" align="center" cellpadding="0" cellspacing="0" >
  <!-- <table width="100%"  border="0" cellspacing="0" cellpadding="1" > -->
    <tr>
      <!-- <td >&nbsp;</td> -->
      <td width="38%" align="center"  ><strong style="font-size:22px;">成品拣货单</strong></td>
      <td width="31%">&nbsp;</td>
    </tr>
    <tr>
      <td></td>
      <td align="center"><input type='hidden' id='tiaoma' value='STYD15815005'/></td>
      <td width="30%">&nbsp;</td>
    </tr>
  </table>
     <table  width="600px">
        <tr>
            <td width="20%">&nbsp;</td>
            <td width="50%">&nbsp;</td>
            <td width="30%" style="font-size:13px;"></td>
        </tr>
  </table>
<table width="320"  border="1" class="top" cellpadding="0" cellspacing="0">
  <tr bordercolor="#F0F0F0" >
    <td height="20" colspan="2" class="left"><div align="center">品名</div></td>
    <td width="80" ><div align="center" >包序号</div></td>
    <td width="120"><div align="center">包号</div></td>
    <td colspan="2"><div align="center">数量</div></td>
  </tr>
  <tr>
    <td width="46" height="20" class="left"><div align="center">货位</div></td>
    <td width="69"><div align="center">{$arr.0.kuqu|default:'&nbsp;'}</div></td>
    <td colspan="4"><div align="center">{$arr.0.productId|default:'&nbsp;'}</div></td>
  </tr>
  {foreach from=$arr item=item}
  <tr>
    <td height="20" colspan="2"><div align="center">{$item.millNo|default:'&nbsp;'}</div></td>
    <td><div align="center">{$item.rollNo|default:'&nbsp;'}</div></td>
    <td><div align="center">{$item.qrcode|default:'&nbsp;'}</div></td>
    <td colspan="3"><div align="right">{$item.cnt|default:'&nbsp;'}</div></td>
  </tr>
  {/foreach}
  <tr>
    <td height="20" colspan="2" class="left"><div align="center">小计：</div></td>
    <td><div align="center">{$hj|default:'&nbsp;'}</div></td>
    <td colspan="3"><div align="right">{$heji.cnt|default:'&nbsp;'}</div></td>
  </tr>
  <tr> 
    <td height="20"><div align="center">合计:</div></td>
    <td><div align="center">1</div></td>
    <td><div align="center">{$hj|default:'&nbsp;'}</div></td>
    <td colspan="3"><div align="right">{$heji.cnt|default:'&nbsp;'}</div></td>
  </tr>
</table>
<table border="0px" width="710px">
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td align="left" width="20%">打印人: &nbsp;{$smarty.session.REALNAME}<font style="padding-left:15px; padding-right:15px;">{$rowset.0.people|default:'&nbsp;'}</font></td>
            <td width="35%" align="center">打印时间：&nbsp;{$time|date_format:'%Y-%m-%d %H:%M:%S'}</td>
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