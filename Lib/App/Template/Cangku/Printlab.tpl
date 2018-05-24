
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
<script language="javascript" src="Resource/Script/Print/LodopFuncs.js"></script>
<object id="LODOP" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width=0 height=0> 
    <embed id="LODOP_EM" type="application/x-print-lodop" width=0 height=0 pluginspage="install_lodop.exe"></embed>
</object> 
<script language="javascript" src="Resource/Script/jquery.js"></script>
<script language="javascript" src="Resource/Script/jquery.validate.js"></script>
<link href="Resource/Css/Print.css" type="text/css" rel="stylesheet" />
{literal}
<script language="javascript">
function prnbutt_onclick() { 
    window.print(); 
    return true; 
} 
    //传递的参数处理   
    var obj ={/literal}{$row|@json_encode}{literal};    

    function prn1_preview() {       
        CreateOneFormPage(obj.id);  
        LODOP.PREVIEW();
        // LODOP.PRINT_design();     
    };
    function prn1_setup() {     
        CreateOneFormPage(obj.id);
        LODOP.PRINT_setup();    
    };
    function prn1_design() {    
        CreateOneFormPage(obj.id);
        LODOP.PRINT_design();   
    };  
    var LODOP;
    function CreateOneFormPage(strPartNumber,strCodeValue){ 
        LODOP=getLodop(document.getElementById('LODOP'),document.getElementById('LODOP_EM'));  
        LODOP.ADD_PRINT_HTM("20","0.15cm","RightMargin:0.1cm","BottomMargin:20mm");
        LODOP.PRINT_INITA(0,0,500,850,"");
        for(var i=0;i<obj.length;i++){
            LODOP.NewPage();
            // debugger;
            LODOP.SET_PRINT_PAGESIZE(1,800,1200,"条码打印");
            LODOP.ADD_PRINT_RECT(5,10,283,110,0,1);
            LODOP.ADD_PRINT_TEXT(52,24,273,24,"Andreazza    Castelli");
            LODOP.SET_PRINT_STYLEA(0,"FontName","kunstler Script");
            LODOP.SET_PRINT_STYLEA(0,"FontSize",26);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_RECT(118,10,283,334,0,1);
            LODOP.ADD_PRINT_LINE(138,10,137,293,0,1);
            LODOP.ADD_PRINT_LINE(168,10,167,293,0,1);
            LODOP.ADD_PRINT_LINE(197,10,196,293,0,1);
            LODOP.ADD_PRINT_LINE(223,10,222,293,0,1);
            LODOP.ADD_PRINT_LINE(256,10,255,293,0,1);
            LODOP.ADD_PRINT_LINE(280,126,137,127,0,1);
            LODOP.ADD_PRINT_TEXT(121,12,143,24,"WarehouseNo./订单编号:");
            LODOP.ADD_PRINT_TEXT(121,156,134,20,obj[i].orderCode);
            LODOP.ADD_PRINT_TEXT(146,12,124,20,"PatternSN/花型六位号:");
            LODOP.ADD_PRINT_TEXT(200,14,115,20,"Component/成分:");
            LODOP.ADD_PRINT_TEXT(144,139,150,20,obj[i].productId);
            LODOP.ADD_PRINT_TEXT(173,138,155,20,obj[i].menfu,obj[i].kezhong);
            LODOP.ADD_PRINT_TEXT(173,15,120,20,"PatternNo/花型号:");
            LODOP.ADD_PRINT_TEXT(200,138,147,20,obj[i].chengfen);
            LODOP.ADD_PRINT_TEXT(226,15,100,20,"Customer:\n客户名称：");
            LODOP.ADD_PRINT_LINE(280,10,279,293,0,1);
            LODOP.ADD_PRINT_TEXT(259,13,115,20,"Consignee/收货人:");
            LODOP.ADD_PRINT_LINE(281,174,255,175,0,1);
            LODOP.ADD_PRINT_LINE(281,203,255,204,0,1);
            LODOP.ADD_PRINT_TEXT(261,176,30,20,"TEL");
            LODOP.ADD_PRINT_TEXT(226,131,154,20,obj[i].compName);
            LODOP.ADD_PRINT_TEXT(260,130,47,20,obj[i].ship_name);
            LODOP.ADD_PRINT_TEXT(261,211,91,20,obj[i].ship_mobile);
            LODOP.ADD_PRINT_TEXT(287,13,100,20,"Delivery Add:\n交货地址:");
            LODOP.ADD_PRINT_LINE(318,10,317,293,0,1);
            LODOP.ADD_PRINT_LINE(386,91,279,92,0,1);
            LODOP.ADD_PRINT_TEXT(321,17,63,20,"QTY/总长:");
            LODOP.ADD_PRINT_LINE(365,148,317,149,0,1);
            LODOP.ADD_PRINT_TEXT(344,16,63,20,"QTY/布长:");
            LODOP.ADD_PRINT_TEXT(367,17,45,20,"Email:");
            LODOP.ADD_PRINT_LINE(342,10,341,148,0,1);
            LODOP.ADD_PRINT_LINE(367,10,366,150,0,1);
            LODOP.ADD_PRINT_TEXT(318,150,100,20,"Rolls/总件数:");
            LODOP.ADD_PRINT_TEXT(344,152,100,20,"Roll No./卷号:");
            LODOP.ADD_PRINT_TEXT(321,236,53,20,obj[i].cntJian);
            LODOP.ADD_PRINT_TEXT(347,239,52,15,obj[i].rollNo);
            LODOP.ADD_PRINT_LINE(342,145,341,293,0,1);
            LODOP.ADD_PRINT_LINE(367,148,366,293,0,1);
            LODOP.ADD_PRINT_LINE(367,150,366,293,0,1);
            LODOP.ADD_PRINT_LINE(388,10,387,293,0,1);
            LODOP.ADD_PRINT_TEXT(289,96,186,20,obj[i].ship_addr);
            LODOP.ADD_PRINT_TEXT(317,93,51,20,obj[i].cntM);
            LODOP.ADD_PRINT_TEXT(342,93,48,20,obj[i].buLength);
            LODOP.ADD_PRINT_TEXT(368,94,187,20,"info@ydyork.com");
            LODOP.ADD_PRINT_LINE(410,10,409,293,0,1);
            LODOP.ADD_PRINT_LINE(428,10,427,293,0,1);
            LODOP.ADD_PRINT_TEXT(391,23,260,20,"江苏省常州市武进纺织工业园(新312国道西侧)");
            LODOP.ADD_PRINT_TEXT(410,68,171,20,"www.acfabric.com");
            LODOP.ADD_PRINT_TEXT(429,80,137,20,"TEL:400-1133-885");
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_LINE(366,236,319,237,0,1);
            LODOP.ADD_PRINT_BARCODE(9,67,186,49,"Code39",obj[i].qrcode);
            LODOP.ADD_PRINT_TEXT(86,76,147,20,"  安爵&缔丽\n");
            LODOP.SET_PRINT_STYLEA(0,"FontName","楷体");
            LODOP.SET_PRINT_STYLEA(0,"FontSize",16);
            LODOP.ADD_PRINT_TEXT(99,135,100,20,"1919");
            LODOP.SET_PRINT_STYLEA(0,"FontName","Lucida Calligraphy");
            LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
            LODOP.ADD_PRINT_TEXT(50,145,100,20,"&");
            LODOP.SET_PRINT_STYLEA(0,"FontName","kunstler Script");
            LODOP.SET_PRINT_STYLEA(0,"FontSize",26);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(433,274,17,20,obj[i].yema);
        }
            // LODOP.PRINT_DESIGN();

    };      
    
    
</script> 

<style type="text/css">
table tr{height:40px;}
.title{font-weight:bold; text-align:right;}
</style>
{/literal}
</head>

<body onLoad="prn1_preview();window.location.href='{url controller=$smarty.get.controller}&action=right'" onafterprint="return window_onafterprint()" onbeforeprint="return window_onbeforeprint()">
<table width="800"  border="0" align="center" cellpadding="0" cellspacing="0">
     <tr align="center">
       <td height="25" style="font-size:24px; font-weight:bold;">
  {webcontrol type='GetAppInf' varName='compName'}-条码打印</td>
     </tr>
      <tr>
       <td height="100" valign="middle" align="center"><font size="+1"><b>{$row.proCode|default:'&nbsp;'}</b> </font></td>
     </tr>
     <tr align="center" bgcolor="#FFFFFF">
       <td height="25">&nbsp;</td>
     </tr>
  </table>
<div id="prn" align="center">
 <input type="button" value="打印预览" onClick="prn1_preview()">
</div>
</body>
</html>
