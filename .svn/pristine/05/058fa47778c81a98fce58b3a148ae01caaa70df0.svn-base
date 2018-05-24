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
        LODOP.PRINT_INITA(0,0,500,800,"");
        for(var i=0;i<obj.length;i++){
            LODOP.NewPage();
            LODOP.SET_PRINT_PAGESIZE(1,800,1200,"条码打印");
            LODOP.ADD_PRINT_RECT(10,10,283,95,0,1);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",12);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_RECT(107,10,283,288,0,1);
            LODOP.ADD_PRINT_LINE(138,10,137,293,0,1);
            LODOP.ADD_PRINT_LINE(168,10,167,293,0,1);
            LODOP.ADD_PRINT_LINE(200,10,199,293,0,1);
            LODOP.ADD_PRINT_LINE(230,10,229,293,0,1);
            LODOP.ADD_PRINT_LINE(261,10,260,293,0,1);
            LODOP.ADD_PRINT_LINE(290,126,137,127,0,1);
            LODOP.ADD_PRINT_TEXT(115,11,143,24,"WarehouseNo:");
            LODOP.ADD_PRINT_TEXT(115,156,134,20,obj[i].chukuCode);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(146,12,124,20,"PatternSN:");
            LODOP.ADD_PRINT_TEXT(206,14,115,20,"Component:");
            LODOP.ADD_PRINT_TEXT(144,139,150,20,obj[i].productId);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(175,138,155,20,obj[i].menfu,obj[i].kezhong);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(174,15,120,20,"PatternNo:");
            LODOP.ADD_PRINT_TEXT(206,138,147,20,obj[i].chengfen);
            LODOP.ADD_PRINT_TEXT(230,15,100,20,"Customer:：");
            LODOP.ADD_PRINT_LINE(293,10,292,293,0,1);
            LODOP.ADD_PRINT_TEXT(269,13,115,20,"Consignee:");
            LODOP.ADD_PRINT_LINE(292,174,260,175,0,1);
            LODOP.ADD_PRINT_LINE(292,203,260,204,0,1);
            LODOP.ADD_PRINT_TEXT(269,176,30,20,"TEL");
            LODOP.ADD_PRINT_TEXT(236,131,154,20,obj[i].compName);
            LODOP.ADD_PRINT_TEXT(269,130,47,20,obj[i].ship_name);
            LODOP.ADD_PRINT_TEXT(269,211,91,20,obj[i].ship_mobile);
            LODOP.ADD_PRINT_TEXT(296,13,100,20,"Delivery Add::");
            LODOP.ADD_PRINT_LINE(326,10,325,293,0,1);
            LODOP.ADD_PRINT_LINE(394,91,291,92,0,1);
            LODOP.ADD_PRINT_TEXT(331,16,65,20,"TOTAL QTY");
            LODOP.ADD_PRINT_LINE(372,148,324,149,0,1);
            LODOP.ADD_PRINT_TEXT(354,16,63,20,"QTY:");
            LODOP.ADD_PRINT_TEXT(376,17,45,20,"Email:");
            LODOP.ADD_PRINT_LINE(347,10,346,148,0,1);
            LODOP.ADD_PRINT_LINE(373,10,372,150,0,1);
            LODOP.ADD_PRINT_TEXT(328,150,100,20,"Rolls:");
            LODOP.ADD_PRINT_TEXT(344,151,100,20,"Roll No.:");
            LODOP.ADD_PRINT_TEXT(359,151,100,20,"Bale No.:");
            LODOP.ADD_PRINT_TEXT(327,239,53,20,obj[i].cntJian);
            LODOP.ADD_PRINT_TEXT(343,240,52,15,obj[i].rollNo);
            LODOP.ADD_PRINT_TEXT(358,240,50,20,"0");
            LODOP.ADD_PRINT_LINE(341,150,340,293,0,1);
            LODOP.ADD_PRINT_LINE(358,148,357,293,0,1);
            LODOP.ADD_PRINT_LINE(373,150,372,293,0,1);
            LODOP.ADD_PRINT_LINE(395,10,394,293,0,1);
            LODOP.ADD_PRINT_TEXT(297,96,186,20,obj[i].ship_addr);
            LODOP.ADD_PRINT_TEXT(327,93,51,20,obj[i].cntM);
            LODOP.ADD_PRINT_TEXT(352,93,48,20,obj[i].buLength);
            LODOP.ADD_PRINT_TEXT(376,94,187,20,"info@ydyork.com");
            // LODOP.ADD_PRINT_LINE(415,10,414,293,0,1);
            // LODOP.ADD_PRINT_LINE(432,10,431,293,0,1);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_LINE(372,236,325,237,0,1);
            LODOP.ADD_PRINT_BARCODE(25,67,186,49,"Code39",obj[i].qrcode);
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
