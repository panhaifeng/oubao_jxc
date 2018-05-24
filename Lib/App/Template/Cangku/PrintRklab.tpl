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
        LODOP.PRINT_INITA(0,0,500,500,"");
        for(var i=0;i<obj.length;i++){
            LODOP.NewPage();
            LODOP.SET_PRINT_PAGESIZE(1,1050,730,"条码打印");
            LODOP.ADD_PRINT_BARCODE(11,96,186,49,"Code39",obj[i].qrcode);
            LODOP.ADD_PRINT_LINE(83,9,82,385,0,1);
            LODOP.ADD_PRINT_LINE(103,10,102,386,0,1);
            LODOP.ADD_PRINT_LINE(129,9,128,385,0,1);
            LODOP.ADD_PRINT_LINE(153,9,152,385,0,1);
            LODOP.ADD_PRINT_LINE(178,10,177,386,0,1);
            LODOP.ADD_PRINT_LINE(202,9,201,385,0,1);
            LODOP.ADD_PRINT_LINE(226,9,225,385,0,1);
            LODOP.ADD_PRINT_LINE(270,146,82,147,0,1);
            LODOP.ADD_PRINT_TEXT(84,11,100,20,"Ready Stock No:");
            LODOP.ADD_PRINT_TEXT(84,151,217,20,obj[i].productId);
            LODOP.ADD_PRINT_TEXT(107,11,100,20,"Article No:");
            LODOP.ADD_PRINT_TEXT(107,151,218,20,obj[i].proName);
            LODOP.ADD_PRINT_TEXT(131,10,100,20,"Mill No:");
            LODOP.ADD_PRINT_TEXT(132,150,218,20,obj[i].millNo);
            LODOP.ADD_PRINT_TEXT(156,10,100,20,"Content:");
            LODOP.ADD_PRINT_TEXT(157,149,218,20,obj[i].chengfen);
            LODOP.ADD_PRINT_TEXT(182,11,100,20,"Width:");
            LODOP.ADD_PRINT_TEXT(205,10,100,20,"Quantity:");
            LODOP.ADD_PRINT_LINE(249,9,248,385,0,1);
            LODOP.ADD_PRINT_TEXT(228,11,100,20,"Roll No:");
            LODOP.ADD_PRINT_TEXT(180,149,216,20,obj[i].menfu);
            LODOP.ADD_PRINT_TEXT(206,149,117,20,obj[i].cntM);
            LODOP.ADD_PRINT_TEXT(230,149,100,20,obj[i].rollNo);
            LODOP.ADD_PRINT_TEXT(252,11,100,20,"Bale No:");
            LODOP.ADD_PRINT_TEXT(251,150,100,20,0);
            LODOP.ADD_PRINT_LINE(226,315,201,316,0,1);
            LODOP.ADD_PRINT_TEXT(207,321,25,20,"M");
            LODOP.ADD_PRINT_RECT(8,9,376,264,0,1);
            // LODOP.PRINT_DESIGN();
        }
    };      
    
    
</script> 

<style type="text/css">
table tr{height:40px;}
.title{font-weight:bold; text-align:right;}
</style>
{/literal}
</head>

<body onLoad="prn1_preview();window.location.href=('?controller=Cangku_Xianhuo_QtRk&action=Right')" onafterprint="return window_onafterprint()" onbeforeprint="return window_onbeforeprint()">
<table width="800"  border="0" align="center" cellpadding="0" cellspacing="0">
     <tr align="center">
       <td height="25" style="font-size:24px; font-weight:bold;">
  {webcontrol type='GetAppInf' varName='compName'}-标签打印</td>
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
