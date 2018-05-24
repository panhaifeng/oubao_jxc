<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
{webcontrol type='LoadJsCss' src="Resource/Script/ext/adapter/ext/ext-base.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/ext/ext-all.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.1.9.1.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/layer/layer.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/common.js"}
{webcontrol type='LoadJsCss' src="Resource/bootstrap/bootstrap/css/bootstrap.css"}
{webcontrol type='LoadJsCss' src="Resource/Script/Calendar/WdatePicker.js"}
{webcontrol type='LoadJsCss' src="Resource/Css/page.css"}
{webcontrol type='LoadJsCss' src="Resource/Css/SearchItemTpl.css"}
{webcontrol type='LoadJsCss' src="Resource/Script/ext/resources/css/ext-all.css"}
{webcontrol type='LoadJsCss' src="Resource/Css/TblList.css"}
{webcontrol type='LoadJsCss' src="Resource/Script/tblList.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/TmisGrid.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.query.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/select2/select2_ie.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/select2/select2_ie.css"}
{webcontrol type='LoadJsCss' src="Resource/Css/scrollbar.css"}
<script language="javascript">
var _head = {$arr_field_info|@json_encode};
var _hasSearch = {if $arr_condition}true{else}false{/if};
var _printUrl = '{$print_href|default:null}';//打印的url
var _showExport = '{$fn_export|default:null}';//是否显示导出
var _showExport1 = '{$fn_export1|default:null}';//是否显示导出
var _debug = false;//打开调试，不进行viewport，不显示蒙版
{literal}
try{//解决ie6下背景图片延迟的问题
	document.execCommand("BackgroundImageCache", false, true);
}
catch (e){
}
Ext.onReady(function() {
	var divGrid = document.getElementById('divGrid');

	$('#divList', divGrid)[0].onscroll = function() {
		duiqiHead(this.parentNode);
	}

	//开始布局
	var items = [];
	if(_hasSearch && document.getElementById('searchGuide')) items.push({
		xtype: 'box',
		region: 'north',
		height: 32,
		// border:false,
		//frame:true,
		contentEl: 'searchGuide'
	});

	var bbar = [{
		xtype: 'tbtext',
		contentEl: 'p_bar'
	}, '->',
	{
		text: ' 刷 新 ',
		iconCls: 'x-tbar-loading',
		cls: 'x-btn-mc',
		handler: function() {
			window.location.href = window.location.href
		}
	}];
	if(_printUrl || typeof(fnPrint) == 'function') {
		bbar.push('-');
		bbar.push({
			text: '打 印',
			iconCls: 'btnPrint',
			handler: function() {
				if(typeof(fnPrint) == 'function') {
					fnPrint();
					return;
				}
				window.location.href = _printUrl;
			}
		});
	}
	if(_showExport) {
		bbar.push('-');
		bbar.push({
			text: '导 出',
			iconCls: 'btnExport',
			handler: function() {
				window.location.href = _showExport;
				//alert('导出');
			}
		});
	}
	if(_showExport1) {
		bbar.push('-');
		bbar.push({
			text: '全部导出',
			iconCls: 'btnExport',
			handler: function() {
				window.location.href = _showExport1;
				//alert('导出');
			}
		});
	}
	if(divGrid) items.push({
		id: 'gridView',
		collapsible: false,
		region: 'center',
		layout: 'fit',
		contentEl: 'divGrid',
		border:false,
		autoScroll: false,
		bbar: bbar
	});
	if(!_debug) var viewport = new Ext.Viewport({
		layout: 'border',
		items: items,
		onLayout: function() {
			layoutGrid(divGrid)
		},
		onRender: function() {
			setCellsWidth(divGrid, _head);

			//鼠标移上变边框
			// $('[name="divRow"]', divGrid).hover(fnOver, fnOut);

			$('[name="divRow"]', divGrid).hover(fnOver, fnOut).click(function() {
				$('.rowSelected').removeClass('rowSelected');
				$(this).addClass('rowSelected');

			});
			//表头以上改变cursor
			$('.headTd', divGrid).mousemove(changeCursor);

			//使列宽可调整
			var splitZone = new SplitDragZone(divGrid);
			//载入插件
			if(typeof(afterRender) != 'undefined') afterRender();
			Ext.QuickTips.init(); //使得所有标记了ext:qtip='点击显示订单跟踪明细'的元素显示出tip,比如
			Ext.apply(Ext.QuickTips.getQuickTip(), {
				dismissDelay: 0
			});
			//<input value='' ext:qtip='点击显示订单跟踪明细' />
			renderForm(document.getElementById('FormSearch'));
		}
	});
	
	//自动加载鼠标提示信息
	qtipToCellContents();
	//处理搜索
	autoSearchDiv();
	thickboxInit();

	$('.select2').select2({'width':'120'});
	
	Ext.get('loading').remove();
	Ext.get('loading-mask').remove();
	
});


//选择月份后改变dateFrom和dateTo
function changeDate(obj){
	//alert(obj.value);
	var df = document.getElementById('dateFrom');
	var dt = document.getElementById('dateTo');
	var d=new Date();
	var year=d.getFullYear();
	var m=parseInt(obj.value)+1;
	if(m<10) m="0"+m;
	df.value=year+'-'+m+'-'+'01';
	//如果为1、3、5、7、8、10、12一个月为31天
	if(obj.value=='0'||obj.value=='2'||obj.value=='4'||obj.value=='6'||obj.value=='7'||obj.value=='9'||obj.value=='11')
	{

		dt.value=year+'-'+m+'-'+'31';
	}
	//如果是2月份判断是否闰年
	if(obj.value=='1') {
		if((year%4==0 && year%100!=0) || year%400==0){
			dt.value=year+'-'+m+'-'+'29';
		}else{
			dt.value=year+'-'+m+'-'+'28';;
		}
	}
	if(obj.value=='13') {
		df.value='{/literal}{php}echo date("2010-01-01");{/php}{literal}';
		dt.value='{/literal}{php}echo date("Y-m-d",mktime(0,0,0,1,0,date("Y")+1));{/php}{literal}';
	}
	if(obj.value=='3'||obj.value=='5'||obj.value=='8'||obj.value=='10')
	{
		dt.value=year+'-'+m+'-'+'30';
	}

}

function form_submit(){
	document.getElementById("FormSearch").submit();
}
</script>
{/literal}
{*需要在action中对sonTpl进行赋值,
$smarty->assign('sonTpl',sonTpl.tpl');
并新建一个sonTpl.tpl模板，如下：
literal
<script language='javascript'>
function fnPrint() {
	alert(1);
}
</script>
/literal
模板中可定义fnPrint(打印触发函数),或者print_href变量
*}
{if $sonTpl}{include file=$sonTpl}{/if}
</head>
<body style='position:static'>
<div id="loading-mask"></div>
<div id="loading">
  <div class="loading-indicator"><img src="Resource/Image/loader.gif" width="100" height="100" style="margin-right:8px;" align="absmiddle"/></div>
</div>
{if $smarty.get.no_edit!=1}{include file="_Search.tpl"}{/if}
{*传递no_edit=1,可使_edit字段不显示*}
{*grid*}
<div id="divGrid" class="divGrid">
  <div id="divHead"> 
    <!--增加一个headOffset层，宽度为list的宽度+滚动条的宽度，为了在拖动时始终能看到表头背景-->
    <div id='divHeadOffset'> 
      <!--必须定义border="0" cellpadding="0" cellspacing="0",否则offsetWidth会超出-->
      <table border="0" cellpadding="0" cellspacing="0" id='tblHead'>
        <tr> {foreach from=$arr_field_info item=item key=key}
          {if $key!='_edit' || $smarty.get.no_edit!=1}
          <td class='headTd'><div class='headTdDiv'>{if $item|@is_string==1}{if $key!='_edit' || $smarty.get.no_edit!=1}{$item}{/if}{else}{if $item.sort}{$item.text}&nbsp;<a href='{url controller=$smarty.get.controller action=$smarty.get.action sortBy=$key sort="asc"}'><span class="glyphicon glyphicon-arrow-up"></span></a>&nbsp;<a href='{url controller=$smarty.get.controller action=$smarty.get.action sortBy=$key sort="desc"}'><span class="glyphicon glyphicon-arrow-down"></span></a>{else}{$item.text}{/if}{/if}</div></td>
          {/if}
          {/foreach} </tr>
      </table>
    </div>
  </div>
  <div class="x-clear"></div>
  <div id="divList"> 
    <!--必须定义border="0" cellpadding="0" cellspacing="0",否则offsetWidth会超出--> 
    
    {*字段的值*}
    {include file=$TblListView|default:'TblListView.tpl'} </div>
</div>

{*显示其他信息*}
{if $otherView}{include file=$otherView}{/if}

<div id='p_bar'>{$page_info}
  {*
  <div style='float:left; margin-left:1px;font-size:13px;'> {$page_info} </div>
  <div style='float:right; margin-right:10px;'> <a href="javascript:window.location.href=window.location.href" title="刷新" style="margin-left:10px;"><img src="Resource/Image/refresh.gif" /></a> {if $fn_export}<a href="#" style="margin-left:10px;"><img src="Resource/Image/daochu.gif" /> 导出</a>{/if}{if $fn_export1}<a href="#" style="margin-left:10px;"><img src="Resource/Image/daochu.gif" /> 全部导出</a>{/if}
    {if $print_href}<a href="{$print_href}" title="打印" style="margin-left:10px;" target="_blank"><img src="Resource/Image/print.png" /></a>{/if} </div>
  *} </div>
</body>
</html>