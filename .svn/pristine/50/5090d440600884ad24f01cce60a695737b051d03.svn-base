<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>码单——单卷单匹编辑</title>
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.validate.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/common.js"}
<link href="Resource/Css/validate.css" type="text/css" rel="stylesheet">
{webcontrol type='LoadJsCss' src="Resource/Script/ext/resources/css/ext-all.css"}
{webcontrol type='LoadJsCss' src="Resource/Script/ext/adapter/ext/ext-base.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/ext/ext-all.js"}
<link rel="stylesheet" href="Resource/bootstrap/bootstrap/css/bootstrap.css">
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.json.js"}
{literal}
<style type="text/css">
body{ text-align:left;}
input{ height: 24px !important; min-width: 40px; padding-left: 3px !important; padding-right: 2px !important;border-radius: 0px !important;}
td,div {font-size:12px; white-space:nowrap; white-space:nowrap;}
#main {margin: 10px 0px 0px 10px; width:1075px;}
.div_caidan{width:100%; border:0px; float:left; font-size:14px; color:#00F; padding:3px 0 0 10px; height:22px; border-bottom:1px solid #bbbbbb; cursor:pointer;}
.j {width:42px; float:left;border:0px solid #000; margin-top: 2px;margin-left: 2px;}
.s {width:50px; float:left;border:0px solid #000; margin-top: 2px;margin-left: -1px;}
.l {width:60px; float:left;border:0px solid #000; margin-top: 2px;margin-left: -1px;}
.k {width:59px; float:left;border:0px solid #000; margin-top: 2px;margin-left: -1px;}
.classData {clear:both; margin-top:0px;margin-left:3px;}
#toolbar { text-align:center; width:100%; margin-top:5px; margin-bottom:10px;}
.classHead {width:1100px;clear:both;text-align:left; font-weight:bold;}
.headDuan{width:210px; float:left; text-align: center;}
#divPage {width:1050px; clear:both;}
#divBlock {width:210px; float:left;}
#divDuan {width:100%; clear:both;}
.memo_s{font-size: 12px;color: #999;float: left;padding: 5px 10px 5px 15px;}
</style>
{/literal}
<script language="javascript">
var index='{$smarty.get.index}';
// var index = parent.layer.getFrameIndex(window.name);
var data=parent.$('[name="Madan[]"]').eq(index).val();
if(data=='')data='[]';
var _cache=eval('('+data+')');
var page=1;
{literal}
$(function(){	
	/*
	*设置页面布局
	*/
	var southItem = {
		xtype: 'box',
		region: 'south',
		height:30,
		contentEl: 'footer'
	};

	var centerItem = {
		  region:'center',
		  layout:'border',
		  items:[{
			//title:'明细(选中上边某行显示)',
			id : 'pihao',
			region:'west',
			title:'卷号[<a href="javascript:;" title="Alt+N 快捷添加" style="color:green;font-size:13px;" name="addMenuLink" id="addMenuLink" accesskey="N">+5页</a>]',
			layout:'fit',
			contentEl: 'caidan',
			margins: '-2 -2 -2 -2',
			autoScroll:true,
			width:115
			//split: true
		  },
		  {
			  id : 'gridView',
			  title:'<div><div style="float:left">码单明细</div><div id="divHeji" style="float:right;color:green;font-size:13px;padding-right:70px;"></div></div>',
			  collapsible: false,
			  region:'center',
			  margins: '-2 -2 -2 -2',
			  layout:'fit',
			  contentEl: 'tab',
			  autoScroll: true
			}]
	  };

    var viewport = new Ext.Viewport({
        layout: 'border',
        items: [southItem,centerItem]
    });
	//页面布局end
	//禁止回车键提交
	$('#form1').keydown(function(e){
		if(e.keyCode==13){
			if(e.target.type!='textarea')event.returnValue=false;
		}
	});
	/*
	*获取最大的卷号，用于判断共需多少页，每页100个卷号，每100个卷号用一个层作为导航
	*左侧菜单栏加载导航信息，判断需要加载的个数
	*/
	var maxPi=0;
	if(_cache){
		for(var k in _cache){
			if(isNaN(parseInt(k)))continue;
			if(!_cache[k])continue;
			if(_cache[k].rollNo>maxPi)maxPi=parseInt(_cache[k].rollNo);
		}
	}
	if(maxPi>0)page=Math.ceil(maxPi/100);
	//加载层******************
	for(var i=0;i<page;i++){
		var div='<div class="div_caidan">'+(i*100+1)+'—'+(i*100+100)+'</div>';
		$('#caidan').append(div);
	}
	/*
	*end************************************
	*/
	/////////////////////////////////////////////////////////////
	//加载码单列表
	getMadanList();
	//卷号选择菜单的事件
	$('.div_caidan').live('mouseover',function(){
		//如果该div 被选中，则不作处理
		// if(this.style.background=='#f0f061')return;
		// this.style.background='#dedede';
		this.style.fontWeight='bold';
	});
	$('.div_caidan').live('mouseout',function(){
		//如果该div 被选中，则不作处理
		// if(this.style.background=='#f0f061')return;
		// this.style.background='#ffffff';
		this.style.fontWeight='';
	});
	$('.div_caidan').live('click',function(){
		clear();
		this.style.background='#f0f061';
		//改变卷号的值并加载对应的码单明细
		var strnum=$(this).html();
		var num=parseInt(strnum.substring(0,strnum.indexOf('—')));
		selectNum(num);
		getDataByrollNo(num);
		$(this).attr('active',true);
	});
	$('.div_caidan:first').click();
	//+100
	$('#addMenuLink').click(function(){
		for(var i=0;i<5;i++){
			addMenu();
		}
	});
	
	//设置卷号,修改缓存
	$('[name="cntFormat[]"]').live('change',function(){
		var pos=$('[name="'+$(this).attr('name')+'"]').index(this);
		var rollNo=$('[name="rollNo[]"]').eq(pos).val();
		changeCache(pos,rollNo-1);
		
		getHeji();
	});
	//设置条码,修改缓存
	$('[name="qrcode[]"]').live('change',function(){
		var pos=$('[name="qrcode[]"]').index(this);
		var rollNo=$('[name="rollNo[]"]').eq(pos).val();
		changeCache(pos,rollNo-1);
	});

	//设置库区,修改缓存
	$('[name="kuqu[]"]').live('change',function(){
		var pos=$('[name="kuqu[]"]').index(this);
		var rollNo=$('[name="rollNo[]"]').eq(pos).val();
		changeCache(pos,rollNo-1);
		kuquName=$(this).val();
		var url ="?controller=Cangku_Ruku&action=GetkuquId";
	  	var param = {'kuquName':kuquName};
	    $.ajax({
	      type: "POST",
	      url: url,
	      data: param,
	      dataType: "json",
	      success: function(json){
	        if(json.success==false){
	            alert(json.msg);
	        }else{
	            $('[name="kuquId[]"]').val(json.data);
	            changeCache(pos,rollNo-1);
	        }
	      },
	     
		})
	});

	
	//加载前100卷号的数据
	getDataByrollNo(1);
	
	//初始化合计信息
	getHeji();
});
//清空菜单颜色
function clear(){
	$('.div_caidan').each(function(){
		this.style.background='#ffffff';
	});
	$('.div_caidan').attr('active','');
}
//添加菜单
function addMenu(){
	var strnum=$('.div_caidan:last').html();
	var num=parseInt(strnum.substr(strnum.indexOf('—')+1))+1;
	//添加新的层
	var div='<div class="div_caidan">'+num+'—'+(num+99)+'</div>';
	$('#caidan').append(div);
}

//加载码单明细
function getMadanList(){
	//载入新数据
	var t = [];
	var tJuan =0;
	var tCnt =0;
	var page =1;
	//加载码单
	for(var ii=0;ii<page;ii++) {
		t.push("<div id='divPage'>");
		for(var j=0;j<5;j++) {
			t.push("<div id='divBlock'>");
			//每20卷卷合计和数量合计
			var _s=0;
			var _j=0;
			for(var k=0;k<20;k++) {
				var i = ii*100+j*20+k;

				t.push("<div id='divDuan'>");
				//卷号
				t.push("<div class='j'>");
				t.push("<input name='rollNo[]' readonly value='"+(i+1)+"' class='juan form-control'/>");
				t.push("</div>");

				//数量
				t.push("<div class='s'>");
				//debugger;
				t.push("<input name='cntFormat[]' value='' class='cnt form-control' onkeydown='moveNext(event,this)'/>");
				t.push("</div>");

				//条码信息
				t.push("<div class='l'>");
				t.push("<input name='qrcode[]' value='' onclick='this.select()' class='qrcode form-control' />");
				t.push("</div>");

				//库区信息
				t.push("<div class='k'>");
				t.push("<input name='kuqu[]' id='kuqu[]' value='' onclick='this.select()' class='kuqu form-control' onkeydown='moveNext(event,this)'/><input name='kuquId[]' value='' type='hidden'/>");
				t.push("</div>");

				t.push("</div>");//end divDuan

			}
			t.push("</div>");//end divBlock
		}
		t.push("</div>");
	}
	var headArr=[];
	for(var ib=0;ib<5;ib++){
		headArr.push("<div class='headDuan'><div class='j'>卷号</div><div class='s'>数量</div><div class='s'>条码</div><div class='k'>库位</div></div>");
	}
	var divHtml="<div class='classHead'>"+headArr.join('')+"</div>";
	divHtml+=t.join('');
	//加载到div
	$('#tab').html(divHtml);


	//鼠标放在数据上面，title显示数据
	$('.cnt').mouseover(function(){
		var strCnt = $(this).val();
		if(strCnt.indexOf('+')<=0){
			// $(this).attr('title','');
			return;
		}
		$(this).attr('title',strCnt+'='+getHeSplitStr(strCnt));
	});

	//聚焦
	$('.cnt:first').focus();

	//禁止只读控件tab键聚焦
	$('input[readonly]').attr('tabindex',-1);
}
//设置键盘方向键
function moveNext(e,o) {
	var name=o.name;
	var i=$('[name="'+name+'"]').index(o);
	var pos=-1;
	//alert(pos);
	if(e.keyCode==37) {//左
		pos = i-20;
	} else if(!e.altKey&&e.keyCode==38) {//上
		pos = i-1;
	} else if(e.keyCode==39) {//右
		pos = i+20;
	} else if(!e.altKey&&(e.keyCode==40 || e.keyCode==13)) {//下
		pos = i+1;
	}else if(e.altKey&&e.keyCode==13) {//下
		pos = i+1;
		name='cntFormat[]';
	} else if(e.altKey&&(e.keyCode==40 || e.keyCode==13)){
		//获取当前被选中的菜单
		var page=$('.div_caidan').index($('.div_caidan[active=true]'));
		if($('.div_caidan')[page+1]){
			$(o).change();
			$('.cnt:first').focus();
			$('.div_caidan').eq(page+1).click();
		}
		return false;
	}else if(e.altKey&&e.keyCode==38){
		//获取当前被选中的菜单
		var page=$('.div_caidan').index($('.div_caidan[active=true]'));
		if($('.div_caidan')[page-1]){
			$(o).change();
			$('.cnt:first').focus();
			$('.div_caidan').eq(page-1).click();
		}
		return false;
	}
	if(pos>-1) {
		if(pos>99) return false;
		// alert(o.name);
		document.getElementsByName(name)[pos].focus();
		return false;
	}
	return true;
}

//得到总和
function getTotal(){
	var cntHeji=0;
	var cntDuan=0;
	for(var t in _cache){
		if(isNaN(parseInt(t)))continue;
		if(!_cache[t])continue;
		if(_cache[t].cnt==0 || _cache[t].cnt=='')continue;
		var _t=_cache[t].cnt;
		cntHeji += parseFloat(_t||0);
		if(_t!='')cntDuan++;
	}
	return {cnt:cntHeji,cntDuan:cntDuan};
}
//修改缓存
function changeCache(i,p){
	if(!_cache)_cache=[];
	if(!_cache[p])_cache[p]={"id":'',"cnt":'',"cntFormat":'',"rollNo":'',"qrcode":'',"kuqu":'',"kuquId":''};
	var _t=_cache[p];
	_t.cntFormat=$('[name="cntFormat[]"]').eq(i).val();
	_t.rollNo=$('[name="rollNo[]"]').eq(i).val();
	_t.qrcode=$('[name="qrcode[]"]').eq(i).val();
	_t.kuqu=$('[name="kuqu[]"]').eq(i).val();
	_t.kuquId=$('[name="kuquId[]"]').eq(i).val();
	//合计
	_t.cnt=getHeSplitStr(_t.cntFormat);
	_t.cntM=getHeSplitStr(_t.cnt_M);
	// debugger;
}

/*
* 输入字符串形式：15+25+45，自动计算合计
*/
function getHeSplitStr(str){
	str=str?str:'0';
	var temp = str.split('+');
	var cntHeji=0;
	for(var j=0;temp[j];j++) {
		cntHeji += parseFloat(temp[j]||0);
	}
	return cntHeji;
}
//选择批号时改变卷号的值
function selectNum(num){
	$('[name="rollNo[]"]').each(function(){
			this.value=num;
			num++;
	});	
}

//加载数据，以开始的rollNo开始加载
function getDataByrollNo(num){
	var pos=num-1;
	//清空当前值
	$('[name="cntFormat[]"]').attr('value','');
	$('[name="qrcode[]"]').attr('value','');
	$('[name="qrcode[]"]').attr('readonly',false);
	$('[name="kuqu[]"]').attr('value','');
	$('[name="kuqu[]"]').attr('readonly',false);
	$('[name="kuquId[]"]').attr('value','');
	$('[name="kuquId[]"]').attr('readonly',false);
	$('[name="cntFormat[]"]').attr('readonly',false);
	$('[name="cntFormat[]"]').attr('title','');
	//重新赋值
	if(!_cache)return false;	
	for(var t in _cache){
		if(!_cache[t])continue;
		if(t>=pos && t<=(pos+99)){
			var _pos=t%100;
			//使用jquery检索导致速度变慢
			document.getElementsByName('cntFormat[]')[_pos].value=_cache[t].cntFormat;
			if(_cache[t].readonly){
				document.getElementsByName('cntFormat[]')[_pos].readOnly=true;
				document.getElementsByName('cntFormat[]')[_pos].title="该码单已锁定，不能修改";
				document.getElementsByName('qrcode[]')[_pos].readOnly=true;
				document.getElementsByName('kuqu[]')[_pos].readOnly=true;
				document.getElementsByName('kuquId[]')[_pos].readOnly=true;
			}
			_cache[t].qrcode=_cache[t].qrcode==undefined?'':_cache[t].qrcode;
			document.getElementsByName('qrcode[]')[_pos].value=_cache[t].qrcode;
			document.getElementsByName('kuqu[]')[_pos].value=_cache[t].kuqu;
			document.getElementsByName('kuquId[]')[_pos].value=_cache[t].kuquId;
		}
	}
	
}

//求合计
function getHeji(){
	var heji=0;
	var hejiM=0;
	var ma=0;
	for(var t in _cache){
		if(!_cache[t])continue;
		if(isNaN(parseInt(t)))continue;
		heji+=parseFloat(_cache[t].cnt)||0;
		if(_cache[t].cntFormat!='')ma++;
	}
	//合计显示在页面上
	var hejiStr="合计："+"<font color='red'>"+heji.toFixed(2)+"</font>&nbsp;&nbsp;卷数<font color='red'>："+ma+"</font>";
	$('#divHeji').html(hejiStr);
}

function ok(){
	var o=getTotal();
	var obj = {data:$.toJSON(_cache),'ok':1,"cnt":o.cnt,"cntJian":o.cntDuan};
	parent.layer.callback(index,obj);
	parent.tb_remove();
}


</script>

{/literal}
</head>
<body>
<form name="form1" id="form1" action="" method="post"  autocomplete="off">
<div id="caidan">
</div>
<div id='tab'>
</div>
<div id="footer">
<div class="memo_s">[ 快捷键：Alt+N：加5页 ，Alt+Enter：下一卷 ，Alt+↓：下一页 ，Alt+↑：上一页 ，Tab：下一个]</div>
<table id="buttonTable" align="left">
<tr>
	<td>
		<input type="hidden" value="{$ruku2proId}" id='ruku2proId' name="ruku2proId">
		<button class="btn btn-primary btn-sm" type="button" name="Submit" onClick="ok()">确定码单</button>
    </td>
</tr>
</table>
</div>
</form>
</body>
</html>
