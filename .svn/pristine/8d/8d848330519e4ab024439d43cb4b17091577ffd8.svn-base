
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>

<script language="javascript" src="Resource/Script/Print/2017.01.04/LodopFuncs.js"></script>
<object id="LODOP" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width=0 height=0>
    <param name="CompanyName" value="常州易奇信息科技有限公司">
    <param name="License" value="664717080837475919278901905623">
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
// console.log(obj);
    var pre = function() {
        CreateOneFormPage(obj.id);  
        LODOP.PREVIEW();
        //LODOP.PRINT_DESIGN(); 
    }; 
    var des = function() {
        CreateOneFormPage(obj.id);  
        //LODOP.PREVIEW();
        LODOP.PRINT_DESIGN(); 
    };     
    //有几百ms的通讯通道构造时间，必须要,否则报websocket没准备好的错误
    //window.onload = setTimeout(pre, 1000);  
    function prn1_setup() {     
        CreateOneFormPage(obj.id);
        LODOP.PRINT_setup();    
    };
    function prn1_design() {    
        CreateOneFormPage(obj.id);
        LODOP.PRINT_DESIGN();   
    };  
    var LODOP;
    function test() {
        LODOP=getLodop(); 
        //LODOP.ADD_PRINT_HTM("20","0.15cm","RightMargin:0.1cm","BottomMargin:20mm");
        LODOP.PRINT_INITA(10,10,"8cm","6cm","测试任务");
        LODOP.SET_PRINT_PAGESIZE(1,"8cm","6cm","条码打印");
        LODOP.ADD_PRINT_TEXT(0,0,170,20,'测试内容');
        LODOP.PRINT_DESIGN();  
    }

    function CreateOneFormPage(strPartNumber,strCodeValue){ 
        
        //LODOP=getLodop(document.getElementById('LODOP'),document.getElementById('LODOP_EM'));  
        LODOP=getLodop(); 
        LODOP.ADD_PRINT_HTM("20","0.15cm","RightMargin:0.1cm","BottomMargin:20mm");
        LODOP.PRINT_INITA(0,0,500,800,"");

        //起始位置，相当于原点
        var _x=20,_y=20;
        for(var i=0;i<obj.length;i++){
            // if(obj[i].productId!='100040') continue;
            LODOP.NewPage();
            LODOP.SET_PRINT_PAGESIZE(1,"8cm","6cm","条码打印");
            // LODOP.ADD_PRINT_BARCODE(27,199,110,50,"Code39",obj[i].productId);
            LODOP.ADD_PRINT_IMAGE(_y,190,110,106,"<img border='0' src='Resource/Image/code.png'/>");
            var j=0;
            LODOP.SET_PRINT_STYLEA(0,"ShowBarText",0);
            LODOP.ADD_PRINT_TEXT(_y+16*(j++),_x,170,20,"Pattern SN:"+obj[i].productId);
            //LODOP.ADD_PRINT_TEXT(8,90,100,20,obj[i].productId);

            LODOP.ADD_PRINT_TEXT(_y+16*(j++),_x,170,20,"Operator：");
            //LODOP.ADD_PRINT_TEXT(24,90,53,22,"");

            LODOP.ADD_PRINT_TEXT(_y+16*(j++),_x,170,20,"Applicant:");
            //LODOP.ADD_PRINT_TEXT(41,92,51,20,"");

            LODOP.ADD_PRINT_TEXT(_y+16*(j++),_x,170,20,"Qty:"+obj[i].cnt);
            //LODOP.ADD_PRINT_TEXT(59,43,42,20,obj[i].cnt);

            LODOP.ADD_PRINT_TEXT(_y+16*(j++),_x,170,20,"Density:"+obj[i].jingmi+'*'+obj[i].weimi);
            //LODOP.ADD_PRINT_TEXT(78,65,71,20,obj[i].jingmi+'*'+obj[i].weimi);

            LODOP.ADD_PRINT_TEXT(_y+16*(j++),_x,170,20,"Width:"+obj[i].menfu);
            //LODOP.ADD_PRINT_TEXT(95,55,54,20,obj[i].menfu);

            LODOP.ADD_PRINT_TEXT(_y+16*(j++),_x,170,20,"GSM:"+obj[i].kezhong);
            //LODOP.ADD_PRINT_TEXT(112,52,50,20,obj[i].kezhong);

            LODOP.ADD_PRINT_TEXT(_y+16*(j++),_x,170,20,"Composition:"+obj[i].chengfen);
            //LODOP.ADD_PRINT_TEXT(128,90,110,20,obj[i].chengfen);
            
            LODOP.ADD_PRINT_TEXT(_y+16*(j++),_x,280,20,"Construction:"+obj[i].shazhi);
            //LODOP.ADD_PRINT_TEXT(146,93,180,20,obj[i].shazhi);

            LODOP.ADD_PRINT_TEXT(_y+16*(j++),_x,280,20,"Fininshing:"+obj[i].zhengli);
            //LODOP.ADD_PRINT_TEXT(166,83,180,20,obj[i].zhengli);

            LODOP.ADD_PRINT_TEXT(_y+16*(j++),_x,280,20,"Customer："+obj[i].compName);
            //LODOP.ADD_PRINT_TEXT(183,84,220,20,obj[i].compName);
            
            LODOP.ADD_PRINT_TEXT(_y+16*(j++),_x,170,20,"Date："+obj[i].dt);
            //LODOP.ADD_PRINT_TEXT(200,52,206,20,obj[i].dt);
            
            LODOP.ADD_PRINT_TEXT(125,190,123,20,"www.acfabric.com");
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8);
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

<body>
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
 <input type="button" value="打印预览" onClick="pre()">
 <input type="button" value="设计模式" onClick="des()">
 <input type="button" value="测试" onClick="test()">
</div>
</body>
</html>
