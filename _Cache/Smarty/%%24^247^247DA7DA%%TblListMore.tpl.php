<?php /* Smarty version 2.6.10, created on 2018-04-09 16:46:14
         compiled from TblListMore.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'webcontrol', 'TblListMore.tpl', 6, false),array('function', 'url', 'TblListMore.tpl', 296, false),array('function', 'cycle', 'TblListMore.tpl', 308, false),array('modifier', 'json_encode', 'TblListMore.tpl', 22, false),array('modifier', 'default', 'TblListMore.tpl', 25, false),array('modifier', 'is_string', 'TblListMore.tpl', 296, false),array('modifier', 'escape', 'TblListMore.tpl', 308, false),array('modifier', 'explode', 'TblListMore.tpl', 314, false),)), $this); ?>
<?php $this->_cache_serials['Lib/App/../../_Cache/Smarty\%%24^247^247DA7DA%%TblListMore.tpl.inc'] = 'c4dd1a532b82ba85c21dfe9ded618788'; ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $this->_tpl_vars['title']; ?>
</title>
<?php if ($this->caching && !$this->_cache_including) { echo '{nocache:c4dd1a532b82ba85c21dfe9ded618788#0}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'LoadJsCss','src' => "Resource/Script/ext/adapter/ext/ext-base.js"), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:c4dd1a532b82ba85c21dfe9ded618788#0}';}?>

<?php if ($this->caching && !$this->_cache_including) { echo '{nocache:c4dd1a532b82ba85c21dfe9ded618788#1}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'LoadJsCss','src' => "Resource/Script/ext/ext-all.js"), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:c4dd1a532b82ba85c21dfe9ded618788#1}';}?>

<?php if ($this->caching && !$this->_cache_including) { echo '{nocache:c4dd1a532b82ba85c21dfe9ded618788#2}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'LoadJsCss','src' => "Resource/Script/jquery.1.9.1.js"), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:c4dd1a532b82ba85c21dfe9ded618788#2}';}?>

<?php if ($this->caching && !$this->_cache_including) { echo '{nocache:c4dd1a532b82ba85c21dfe9ded618788#3}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'LoadJsCss','src' => "Resource/Script/layer/layer.js"), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:c4dd1a532b82ba85c21dfe9ded618788#3}';}?>

<?php if ($this->caching && !$this->_cache_including) { echo '{nocache:c4dd1a532b82ba85c21dfe9ded618788#4}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'LoadJsCss','src' => "Resource/Script/common.js"), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:c4dd1a532b82ba85c21dfe9ded618788#4}';}?>

<?php if ($this->caching && !$this->_cache_including) { echo '{nocache:c4dd1a532b82ba85c21dfe9ded618788#5}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'LoadJsCss','src' => "Resource/bootstrap/bootstrap/css/bootstrap.css"), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:c4dd1a532b82ba85c21dfe9ded618788#5}';}?>

<?php if ($this->caching && !$this->_cache_including) { echo '{nocache:c4dd1a532b82ba85c21dfe9ded618788#6}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'LoadJsCss','src' => "Resource/Script/Calendar/WdatePicker.js"), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:c4dd1a532b82ba85c21dfe9ded618788#6}';}?>

<?php if ($this->caching && !$this->_cache_including) { echo '{nocache:c4dd1a532b82ba85c21dfe9ded618788#7}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'LoadJsCss','src' => "Resource/Css/page.css"), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:c4dd1a532b82ba85c21dfe9ded618788#7}';}?>

<?php if ($this->caching && !$this->_cache_including) { echo '{nocache:c4dd1a532b82ba85c21dfe9ded618788#8}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'LoadJsCss','src' => "Resource/Css/SearchItemTpl.css"), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:c4dd1a532b82ba85c21dfe9ded618788#8}';}?>

<?php if ($this->caching && !$this->_cache_including) { echo '{nocache:c4dd1a532b82ba85c21dfe9ded618788#9}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'LoadJsCss','src' => "Resource/Script/ext/resources/css/ext-all.css"), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:c4dd1a532b82ba85c21dfe9ded618788#9}';}?>

<?php if ($this->caching && !$this->_cache_including) { echo '{nocache:c4dd1a532b82ba85c21dfe9ded618788#10}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'LoadJsCss','src' => "Resource/Css/TblList.css"), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:c4dd1a532b82ba85c21dfe9ded618788#10}';}?>

<?php if ($this->caching && !$this->_cache_including) { echo '{nocache:c4dd1a532b82ba85c21dfe9ded618788#11}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'LoadJsCss','src' => "Resource/Script/tblList.js"), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:c4dd1a532b82ba85c21dfe9ded618788#11}';}?>

<?php if ($this->caching && !$this->_cache_including) { echo '{nocache:c4dd1a532b82ba85c21dfe9ded618788#12}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'LoadJsCss','src' => "Resource/Script/select2/select2_ie.js"), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:c4dd1a532b82ba85c21dfe9ded618788#12}';}?>

<?php if ($this->caching && !$this->_cache_including) { echo '{nocache:c4dd1a532b82ba85c21dfe9ded618788#13}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'LoadJsCss','src' => "Resource/Script/select2/select2_ie.css"), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:c4dd1a532b82ba85c21dfe9ded618788#13}';}?>

<?php if ($this->caching && !$this->_cache_including) { echo '{nocache:c4dd1a532b82ba85c21dfe9ded618788#14}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'LoadJsCss','src' => "Resource/Css/scrollbar.css"), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:c4dd1a532b82ba85c21dfe9ded618788#14}';}?>

<script language="javascript">
var _head = <?php echo json_encode($this->_tpl_vars['arr_field_info']); ?>
;
var _head1 = <?php echo json_encode($this->_tpl_vars['arr_field_info2']); ?>
;
var _hasSearch = <?php if ($this->_tpl_vars['arr_condition']): ?>true<?php else: ?>false<?php endif; ?>;
var _printUrl = '<?php echo ((is_array($_tmp=@$this->_tpl_vars['print_href'])) ? $this->_run_mod_handler('default', true, $_tmp, null) : smarty_modifier_default($_tmp, null)); ?>
';//打印的url
var _showExport = '<?php echo ((is_array($_tmp=@$this->_tpl_vars['fn_export'])) ? $this->_run_mod_handler('default', true, $_tmp, null) : smarty_modifier_default($_tmp, null)); ?>
';//是否显示导出
var _sonWidth = parseInt(<?php echo ((is_array($_tmp=@$this->_tpl_vars['sonWidth'])) ? $this->_run_mod_handler('default', true, $_tmp, 400) : smarty_modifier_default($_tmp, 400)); ?>
);//左边主表的宽度
var _subField = '<?php echo $this->_tpl_vars['sub_field']; ?>
';//主表数据中的从表数据字段
var _debug = false;//打开调试，不进行viewport，不显示蒙版
var _arrDebug = [];
<?php echo '
if(_subField == \'\') {
	alert(\'必须定义sub_field\');
}
try { //解决ie6下背景图片延迟的问题
	document.execCommand("BackgroundImageCache", false, true);
} catch(e) {}
Ext.onReady(function() {
	//alert($(\'#TableContainer\').height());
	// $(\'#tblScorll\').height(475);
	var divGrid = document.getElementById(\'divGrid\');
	var divGrid1 = document.getElementById(\'divGridSon\');

	$(\'#divList\', divGrid)[0].onscroll = function() {
		duiqiHead(this.parentNode);
	}

	$(\'#divList\', divGrid1)[0].onscroll = function() {
		duiqiHead(this.parentNode);
	}


	//开始布局
	var items = [];
	if(_hasSearch && document.getElementById(\'searchGuide\')) items.push({
		xtype: \'box\',
		region: \'north\',
		height: 32,
		//frame:true,
		contentEl: \'searchGuide\'
	});

	var bbar = [{
		xtype: \'tbitem\',
		contentEl: \'p_bar\'
	} //不能使用tbtext,会导致上下偏移失常。
	,
	\'->\',
	{
		text: \' 刷 新 \',
		iconCls: \'x-tbar-loading\',
		cls: \'x-btn-mc\',
		handler: function() {
			window.location.href = window.location.href
		}
	}];
	if(_printUrl || typeof(fnPrint) == \'function\') {
		bbar.push(\'-\');
		bbar.push({
			text: \'打 印\',
			iconCls: \'btnPrint\',
			handler: function() {
				if(typeof(fnPrint) == \'function\') {
					fnPrint();
					return;
				}
				window.location.href = _printUrl;
			}
		});
	}
	if(_showExport) {
		bbar.push(\'-\');
		bbar.push({
			text: \'导 出\',
			iconCls: \'btnExport\',
			handler: function() {
				window.location.href = _showExport;
				//alert(\'导出\');
			}
		});
	}

	/*if(document.getElementById(\'p_bar\')) items.push({
		  xtype: \'box\',
		  region: \'south\',
		  height: 22,
		  contentEl: \'p_bar\'
		  //split:true
	  });*/
	if(divGrid) items.push({
		//id : \'gridView\',
		//collapsible: false,
		id: \'viewMain\',
		region: \'center\',
		layout: \'border\',
		border:false,
		bbar: bbar,
		items: [{
			title: \'明细(选中上边某行显示)\',
			id: \'gridView1\',
			region: \'south\',
			layout: \'fit\',
			border:false,
			contentEl: \'divGridSon\',
			split: true // enable resizing
			,
			onLayout: function() {
				layoutGrid(divGrid1)
			},
			//width: _sonWidth
			height:200
		}, {
			id: \'gridView\',
			region: \'center\',
			border:false,
			layout: \'fit\',
			contentEl: \'divGrid\',
			onLayout: function() {
				layoutGrid(divGrid)
			}
		}]
		//contentEl: \'divGrid\',
		//autoScroll: false
	});
	if(!_debug) var viewport = new Ext.Viewport({
		layout: \'border\',
		items: items
		//,afterRender:function(){}
		,
		onRender: function() {
			//根据字段定义设置表格的列宽
			setCellsWidth(divGrid, _head);
			setCellsWidth(divGrid1, _head1);
			//setCellWidth();
			//setCellWidth1();
			//鼠标移上变边框,选中显示子表
			$(\'[name="divRow"]\', divGrid).hover(fnOver, fnOut).click(function() {
				var divList1 = $(\'#divList\', divGrid1)[0];
				$(\'.rowSelected\').removeClass(\'rowSelected\');
				$(this).addClass(\'rowSelected\');

				//子表中插入数据
				$(divList1).html(\'\');
				var json = eval("(" + $(this).attr(\'subJson\') + ")");
				var s = json[_subField];

				//构造html
				var html = [];

				for(var i = 0; s[i]; i++) {
					html.push("<div id=\'divRow\' name=\'divRow\'>");
					html.push(\'<table border="0" cellpadding="0" cellspacing="0" id="tblRow" name="tblRow">\');
					html.push("<tr class=\'trRow\'>");
					for(var k in _head1) {
						html.push("<td><div class=\'valueTdDiv\'>");
						html.push(s[i][k]);
						html.push("</div></td>");
					}
					html.push("</tr>");
					html.push("</table>");
					html.push("</div>");
				}
				$(divList1).html(html.join(\'\'));
				setCellsWidth(divGrid1, _head1);
				duiqiHead(divGrid1);
			});

			//表头以上改变cursor
			$(\'.headTd\', divGrid).mousemove(changeCursor);
			//使列宽可调整
			var splitZone = new SplitDragZone(divGrid);

			//两个grid中tblHead命名相同导致无法拖动，需要大改
			//$(\'.headTd\',divGrid1).mousemove(changeCursor);
			//var splitZone1 = new SplitDragZone(divGrid1);
			//_arrDebug.push(splitZone1);
			//splitZone1 = new SplitDragZone($(\'#divGrid\')[0]);
			if(typeof(afterRender) != \'undefined\') afterRender();
			Ext.QuickTips.init(); //使得所有标记了ext:qtip=\'点击显示订单跟踪明细\'的元素显示出tip,比如
			Ext.apply(Ext.QuickTips.getQuickTip(), {
				dismissDelay: 0
			});
			//<input value=\'\' ext:qtip=\'点击显示订单跟踪明细\' />
			renderForm(document.getElementById(\'FormSearch\'));
		}
	});
	
	//自动加载鼠标提示信息
	qtipToCellContents();
	// //处理搜索
	autoSearchDiv();

	thickboxInit();
	$(\'.select2\').select2({\'width\':\'120\'});

	Ext.get(\'loading\').remove();
	Ext.get(\'loading-mask\').remove();
	
});

//选择月份后改变dateFrom和dateTo


function changeDate(obj) {
	//alert(obj.value);
	var df = document.getElementById(\'dateFrom\');
	var dt = document.getElementById(\'dateTo\');
	var d = new Date();
	var year = d.getFullYear();
	var m = parseInt(obj.value) + 1;
	if(m < 10) m = "0" + m;
	df.value = year + \'-\' + m + \'-\' + \'01\';
	//如果为1、3、5、7、8、10、12一个月为31天
	if(obj.value == \'0\' || obj.value == \'2\' || obj.value == \'4\' || obj.value == \'6\' || obj.value == \'7\' || obj.value == \'9\' || obj.value == \'11\') {

		dt.value = year + \'-\' + m + \'-\' + \'31\';
	}
	//如果是2月份判断是否闰年
	if(obj.value == \'1\') {
		if((year % 4 == 0 && year % 100 != 0) || year % 400 == 0) {
			dt.value = year + \'-\' + m + \'-\' + \'29\';
		} else {
			dt.value = year + \'-\' + m + \'-\' + \'28\';;
		}
	}
	if(obj.value == \'13\') {
		df.value = \'';  echo date("2010-01-01");  echo '\';
		dt.value = \'';  echo date("Y-m-d",mktime(0,0,0,1,0,date("Y")+1));  echo '\';
	}
	if(obj.value == \'3\' || obj.value == \'5\' || obj.value == \'8\' || obj.value == \'10\') {
		dt.value = year + \'-\' + m + \'-\' + \'30\';
	}

}

function form_submit() {
	/*$(\'#\'+name+\' input[type=text]\').each(function(i){
			if(this.value==this.emptyText) this.value=\'\';
			//alert(this.value);
		});*/
	document.getElementById("FormSearch").submit();
}
</script>
'; ?>

<?php if ($this->_tpl_vars['sonTpl']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['sonTpl'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
</head>
<body style='position:static'>
<div id="loading-mask"></div>
  <div id="loading">
    <div class="loading-indicator"><img src="Resource/Image/loader.gif" width="100" height="100" style="margin-right:8px;" align="absmiddle"/></div>
  </div>
<?php if ($_GET['no_edit'] != 1):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "_Search.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
<div id="divGrid" class="divGrid x-hide-display">
	<div id="divHead">
		<!--增加一个headOffset层，宽度为list的宽度+滚动条的宽度，为了在拖动时始终能看到表头背景-->
        <div id='divHeadOffset'>
        	<!--必须定义border="0" cellpadding="0" cellspacing="0",否则offsetWidth会超出-->
			<table border="0" cellpadding="0" cellspacing="0" id='tblHead'>
			  <tr>
				<?php $_from = $this->_tpl_vars['arr_field_info']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
					<?php if ($this->_tpl_vars['key'] != '_edit' || $_GET['no_edit'] != 1): ?>
					<td class='headTd'><div class='headTdDiv'><?php if (is_string($this->_tpl_vars['item']) == 1):  if ($this->_tpl_vars['key'] != '_edit' || $_GET['no_edit'] != 1):  echo $this->_tpl_vars['item'];  endif;  else:  if ($this->_tpl_vars['item']['sort']): ?><a href='<?php if ($this->caching && !$this->_cache_including) { echo '{nocache:c4dd1a532b82ba85c21dfe9ded618788#15}';}echo $this->_plugins['function']['url'][0][0]->_pi_func_url(array('controller' => $_GET['controller'],'action' => $_GET['action'],'sortBy' => $this->_tpl_vars['key']), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:c4dd1a532b82ba85c21dfe9ded618788#15}';}?>
'><?php echo $this->_tpl_vars['item']['text']; ?>
</a><?php else:  echo $this->_tpl_vars['item']['text'];  endif;  endif; ?></div></td>
					<?php endif; ?>
				<?php endforeach; endif; unset($_from); ?>
			  </tr>
			</table>
		</div>
    </div>
	<div class="x-clear"></div>
    <div id="divList">
    	<!--必须定义border="0" cellpadding="0" cellspacing="0",否则offsetWidth会超出-->
	   		<?php $_from = $this->_tpl_vars['arr_field_value']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['field_value']):
?>
		<div id='divRow' name='divRow' style='background-color:<?php echo smarty_function_cycle(array('values' => "#ffffff,#f9f9f9"), $this);?>
'  subJson='<?php echo ((is_array($_tmp=json_encode($this->_tpl_vars['field_value']))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
'>
		<table border="0" cellpadding="0" cellspacing="0" id="tblRow" name="tblRow">
			<?php if ($this->_tpl_vars['field_value']['display'] != 'false'): ?>				  <tr class='trRow' <?php if ($this->_tpl_vars['field_value']['_bgColor'] != ''): ?> style="background-color:<?php echo $this->_tpl_vars['field_value']['_bgColor']; ?>
;" <?php endif; ?>>
				<?php $_from = $this->_tpl_vars['arr_field_info']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
					<?php if ($this->_tpl_vars['key'] != '_edit' || $_GET['no_edit'] != 1): ?>
					  <?php $this->assign('foo', ((is_array($_tmp=".")) ? $this->_run_mod_handler('explode', true, $_tmp, $this->_tpl_vars['key']) : explode($_tmp, $this->_tpl_vars['key']))); ?>
					  <?php $this->assign('key1', $this->_tpl_vars['foo'][0]); ?>
					  <?php $this->assign('key2', $this->_tpl_vars['foo'][1]); ?>
					  <?php $this->assign('key3', $this->_tpl_vars['foo'][2]); ?>
				<td <?php if ($this->_tpl_vars['item']['_color'] != ''): ?> style="color:<?php echo $this->_tpl_vars['item']['_color']; ?>
;" <?php endif; ?>><div class='valueTdDiv'>
					  <?php if ($this->_tpl_vars['key2'] == ''): ?>
						<?php echo ((is_array($_tmp=@$this->_tpl_vars['field_value'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('default', true, $_tmp, '&nbsp;') : smarty_modifier_default($_tmp, '&nbsp;')); ?>

					  <?php elseif ($this->_tpl_vars['key3'] == ''): ?>
						<?php echo ((is_array($_tmp=@$this->_tpl_vars['field_value'][$this->_tpl_vars['key1']][$this->_tpl_vars['key2']])) ? $this->_run_mod_handler('default', true, $_tmp, '&nbsp;') : smarty_modifier_default($_tmp, '&nbsp;')); ?>

					  <?php else: ?>
						<?php echo ((is_array($_tmp=@$this->_tpl_vars['field_value'][$this->_tpl_vars['key1']][$this->_tpl_vars['key2']][$this->_tpl_vars['key3']])) ? $this->_run_mod_handler('default', true, $_tmp, '&nbsp;') : smarty_modifier_default($_tmp, '&nbsp;')); ?>

					  <?php endif; ?>
				</div></td>
					<?php endif; ?>
				<?php endforeach; endif; unset($_from); ?>
			  </tr>
			  <?php endif; ?>
		  </table></div>
		<?php endforeach; endif; unset($_from); ?>
    </div>
</div>

<div id="divGridSon" class="divGrid x-hide-display">
	<div id="divHead">
		<!--增加一个headOffset层，宽度为list的宽度+滚动条的宽度，为了在拖动时始终能看到表头背景-->
        <div id='divHeadOffset'>
        	<!--必须定义border="0" cellpadding="0" cellspacing="0",否则offsetWidth会超出-->
			<table border="0" cellpadding="0" cellspacing="0" id='tblHead'>
			  <tr>
				<?php $_from = $this->_tpl_vars['arr_field_info2']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
					<?php if ($this->_tpl_vars['key'] != '_edit' || $_GET['no_edit'] != 1): ?>
					<td class='headTd'><div class='headTdDiv'><?php if (is_string($this->_tpl_vars['item']) == 1):  if ($this->_tpl_vars['key'] != '_edit' || $_GET['no_edit'] != 1):  echo $this->_tpl_vars['item'];  endif;  else:  if ($this->_tpl_vars['item']['sort']): ?><a href='<?php if ($this->caching && !$this->_cache_including) { echo '{nocache:c4dd1a532b82ba85c21dfe9ded618788#16}';}echo $this->_plugins['function']['url'][0][0]->_pi_func_url(array('controller' => $_GET['controller'],'action' => $_GET['action'],'sortBy' => $this->_tpl_vars['key']), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:c4dd1a532b82ba85c21dfe9ded618788#16}';}?>
'><?php echo $this->_tpl_vars['item']['text']; ?>
</a><?php else:  echo $this->_tpl_vars['item']['text'];  endif;  endif; ?></div></td>
					<?php endif; ?>
				<?php endforeach; endif; unset($_from); ?>
			  </tr>
			</table>
		</div>
    </div>
	<div class="x-clear"></div>
    <div id="divList"></div>
</div>

<div id='p_bar'><?php echo $this->_tpl_vars['page_info']; ?>

	</div>

</body>
</html>