<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>打印电子面单</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  {webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
  {webcontrol type='LoadJsCss' src="Resource/Script/Print/2017.01.04/LodopFuncs.js"}
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
    LODOP.ADD_PRINT_HTM("0%", "0%", "375px", "100%", document.getElementById("printBox").innerHTML);
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
<div id="printBox" style=" width:400px; margin:0 auto; position:relative;">
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
{$result}
</div>
<div id="divPrint" class="wrap">
    <input type="button" name="button" id="button" value="打印" onclick="PrintTable()"/>
</div>
</body>
</html>