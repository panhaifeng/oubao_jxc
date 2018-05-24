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
   border-color:#CCCCCC;
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
<p align="center"><strong>常州溢代约克国际贸易有限公司</strong></p>
<p align="center" style="font-size:13px;">YD YORK INTERNATIONAL TRADE （CHANG ZHOU） CO LTD</p>
<p align="center" style="font-size: 18px;"><strong>成品拣货单</strong></p>
<p align="center" style="font-size: 14px; font-weight: bold;"> 订单编号：{$temp.0.orderCode}</p>

<table width="683" height="110"border="1" align="center" cellpadding="0" cellspacing="0" class="tablec">
  <tr>
    <td width="67" height="23" style="font-weight: bold"><div align="center" >货位</div></td>
    <td width="119" style="font-weight: bold"><div align="center">品名</div></td>
    <td width="105" style="font-weight: bold"><div align="center">包序号</div></td>
    <td width="111" style="font-weight: bold"><div align="center">包号</div></td>
    <!-- <td width="119"><div align="center">布号</div></td> -->
    <td width="137" style="font-weight: bold"><div align="center">数量</div></td>
    <td width="130" style="font-weight: bold"><div align="center">单位</div><div align="center"></div></td>
  </tr>
  {foreach from=$arr key=key item=item}
  <tr>
    <td style="font-weight: bold"><div align="center">{$key}</div></td>

    <td style="font-weight: bold"><div align="center"></div></td>
    <td colspan="5" style="font-weight: bold"><div align="center"></div></td>
  </tr>
  {foreach from=$item  item=it}
  <tr>
    <td style="font-weight: bold"><div align="center"></div></td>
    <td style="font-weight: bold"><div align="center">{$it.millNo}</div></td>
    <td style="font-weight: bold"><div align="center">{$it.rollNo}</div></td>
    <td style="font-weight: bold"><div align="center">{$it.qrcode}</div></td>
    <!-- <td><div align="center"></div>{$it.millNo}</td> -->
    <td style="font-weight: bold"><div align="center">{$it.cnt}</div></td>
    <td style="font-weight: bold"><div align="center">{$it.unit}</div>      <div align="center"></div></td>
  </tr>
{/foreach}

  <tr>
    <td colspan="2" bgcolor="#CCCCCC" style="font-weight: bold"><div align="center">小计：      </div></td>
    <td bgcolor="#CCCCCC" style="font-weight: bold"><div align="center">{$item.0.cntJian}</div></td>
    <td bgcolor="#CCCCCC" style="font-weight: bold"><div align="center"></div></td>
    <td bgcolor="#CCCCCC" style="font-weight: bold"><div align="center">{$item.0.cntSum}</div></td>
    <td bgcolor="#CCCCCC" style="font-weight: bold"><div align="center">{$it.unit}</div>      <div align="center"></div></td>
  </tr>
{/foreach}

    <tr>
    <td colspan="2" bgcolor="#CCCCCC" style="font-weight: bold"><div align="center">总计：      </div></td>
    <td bgcolor="#CCCCCC" style="font-weight: bold"><div align="center">{$item.0.zongJ}</div></td>
    <td bgcolor="#CCCCCC" style="font-weight: bold"><div align="center"></div></td>
    <td bgcolor="#CCCCCC" style="font-weight: bold"><div align="center">{$item.0.zongM}</div></td>
    <td bgcolor="#CCCCCC" style="font-weight: bold"><div align="center">{$it.unit}</div>      <div align="center"></div></td>
  </tr>
  
</table>

<blockquote>&nbsp;</blockquote>
</body>
</html><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>拣货单打印</title>
</head>

<body>
<div id="prn" align="center">
<input type="button" id="button1" name="button1" value="打印" onclick="window_onbeforeprint();prnbutt_onclick();window_onafterprint();" />
</div>
</body>
</html>
