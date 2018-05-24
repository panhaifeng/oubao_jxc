<?php /* Smarty version 2.6.10, created on 2017-03-19 16:34:03
         compiled from Main.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'webcontrol', 'Main.tpl', 3, false),)), $this); ?>
<?php $this->_cache_serials['Lib/App/../../_Cache/Smarty\%%8B^8BA^8BAC94BE%%Main.tpl.inc'] = '890637a70bf0aa25d14a36800bc6d250'; ?><html>
<head>
<title><?php if ($this->caching && !$this->_cache_including) { echo '{nocache:890637a70bf0aa25d14a36800bc6d250#0}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'GetAppInf','varName' => 'verName'), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:890637a70bf0aa25d14a36800bc6d250#0}';}?>
</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- <meta http-equiv="X-UA-Compatible" content="chrome=1" /> -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="renderer" content="webkit">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php if ($this->caching && !$this->_cache_including) { echo '{nocache:890637a70bf0aa25d14a36800bc6d250#1}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'LoadJsCss','src' => "Resource/Css/main1.css"), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:890637a70bf0aa25d14a36800bc6d250#1}';}?>

<?php if ($this->caching && !$this->_cache_including) { echo '{nocache:890637a70bf0aa25d14a36800bc6d250#2}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'LoadJsCss','src' => "Resource/bootstrap/bootstrap/css/bootstrap.css"), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:890637a70bf0aa25d14a36800bc6d250#2}';}?>

<?php if ($this->caching && !$this->_cache_including) { echo '{nocache:890637a70bf0aa25d14a36800bc6d250#3}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'LoadJsCss','src' => "Resource/ext/include-ext.js"), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:890637a70bf0aa25d14a36800bc6d250#3}';}?>

<?php if ($this->caching && !$this->_cache_including) { echo '{nocache:890637a70bf0aa25d14a36800bc6d250#4}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'LoadJsCss','src' => "Resource/ext/TabCloseMenu.js"), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:890637a70bf0aa25d14a36800bc6d250#4}';}?>

<?php if ($this->caching && !$this->_cache_including) { echo '{nocache:890637a70bf0aa25d14a36800bc6d250#5}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'LoadJsCss','src' => "Resource/Script/jquery.1.9.1.js"), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:890637a70bf0aa25d14a36800bc6d250#5}';}?>

<?php if ($this->caching && !$this->_cache_including) { echo '{nocache:890637a70bf0aa25d14a36800bc6d250#6}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'LoadJsCss','src' => "Resource/Css/MainNew.css"), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:890637a70bf0aa25d14a36800bc6d250#6}';}?>

<?php if ($this->caching && !$this->_cache_including) { echo '{nocache:890637a70bf0aa25d14a36800bc6d250#7}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'LoadJsCss','src' => "Resource/bootstrap/bootstrap/js/bootstrap.js"), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:890637a70bf0aa25d14a36800bc6d250#7}';}?>

<?php if ($this->caching && !$this->_cache_including) { echo '{nocache:890637a70bf0aa25d14a36800bc6d250#8}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'LoadJsCss','src' => "Resource/Script/ymPrompt/ymPrompt.js"), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:890637a70bf0aa25d14a36800bc6d250#8}';}?>

<?php if ($this->caching && !$this->_cache_including) { echo '{nocache:890637a70bf0aa25d14a36800bc6d250#9}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'LoadJsCss','src' => "Resource/Script/ymPrompt/skin/qq/ymPrompt.css"), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:890637a70bf0aa25d14a36800bc6d250#9}';}?>

<?php echo '
<script type="text/javascript">
	Ext.onReady(function() {
		var imagePath = \'Resource/Script/ext/resources/images\';
		Ext.BLANK_IMAGE_URL = imagePath+\'/default/s.gif\';
		var detailEl;
		var tabs;
		var welcomeUrl=\'index.php?controller=Main&action=Welcome\';

		//加载Tree的数据信息
		var store = Ext.create(\'Ext.data.TreeStore\', {
			root: {
				expanded: true
			},
			proxy: {
				type: \'ajax\',
				url: \'?controller=Main&action=Getmenu\'
			}
		});

		//创建tree
		var treePanel = Ext.create(\'Ext.tree.Panel\', {
			id: \'tree-panel\',
			//title: \'菜单目录\',
			region:\'center\',
			split: true,
			border: false,
			autoScroll: true,
			store:store,
			rootVisible: false,
			lines: false,
			singleExpand: true,
			useArrows: true
		});

		treePanel.on(\'itemclick\', function(tree,record,item,index,e,options){
			var n = record.data;

			//处理tab
			if(!n.leaf) {
				//展开
				record.expand();
				return ;
			}

			// debugger;//alert(\'右边窗口增加一个tab窗口\');
			var sn = this.selModel.selNode || {}; // selNode is null on initial selection
			// var desc = "<p style=\'margin:8px\'>"+(n.attributes.desc||\'没有使用说明\')+\'</p>\';
			var href = n.src;
			var id = \'docs-\' + n.id;
			var text = n.text;
			
			var tab = tabs.getComponent(id);
			if(tab){
				document.getElementById(\'_frm\'+id).src = href;
				tab.show();
				// tabs.setActiveTab(tab);//Active 
			}else{
				var t = tabs.add({
					id:id,
					title: text,
					icon: null,
					html: \'<iframe scrolling="auto" frameborder="0" width="100%" height="100%" src="\'+href+\'" id="_frm\'+id+\'"></iframe>\',
					closable:true,
					listeners: {
						activate : function(o) {
							var record = treePanel.getStore().getNodeById(o.id.slice(5));
							if(!record) return true;
							treePanel.getSelectionModel().select(record);
							// record.parentNode.expand();
							var _node_ = record;
							while(_node_.parentNode){
								_node_.expand();
								_node_ = _node_.parentNode;
							}
						}
					}
				}).show();
			}
		});

		var westItem =  {
			region: \'west\',
			id: \'west-panel\', // see Ext.getCmp() below
			layout: \'border\',
			title: \'SYSTEM\',
			// split: true,
			width: 200,
			minWidth: 100,
			bodyBorder:true,
			border:\'1\',
			maxWidth: 400,
			collapsible: true,
			items: [treePanel]
		};

		var northItem = {
			xtype: \'box\',
			region: \'north\',
			height:45,
			border: false,
			contentEl: \'header\'
		}

		var centerItem = Ext.create(\'Ext.tab.Panel\',{
			id:\'_tabs\',
			region: \'center\', // a center region is ALWAYS required for border layout
			deferredRender: false,
			enableTabScroll:true,
			activeTab: 0,     // first tab initially active
		 	// plain: true,
		 	bodyBorder:true,
		 	// bodyPadding: \'2 0 0 0\',
		    // tabPosition: \'bottom\',
			// margins: \'2 0 0 0\',
			items: [{
				title: \'<span class="glyphicon glyphicon-home"></span>&nbsp;Home\',
				autoScroll: true,
				html: \'<iframe scrolling="auto" frameborder="0" width="100%" height="100%" src="\'+welcomeUrl+\'"></iframe>\'
			}],
			plugins : new Ext.ux.TabCloseMenu()
		});

		tabs = centerItem;

		var viewport = Ext.create(\'Ext.container.Viewport\',{
            layout: \'border\',
            items: [northItem,westItem,centerItem]
        });

        

		setTimeout(function(){
				$(\'#loading\').remove();
				$(\'#loading-mask\').fadeOut({remove:true});
		}, 500);

		//获取通知信息
		// setTimeout(\'getTongzhi()\',500000);
		// setTimeout(\'getMail()\',5000);


		// 获取退库通知信息
		// setTimeout(\'getTktongzhi()\',500000);
	});



	//text表示提示框中要出现的文字，
	//ok表示是显示ok图标还是出错图标
	function showMsg(text,ok) {
		$(\'#divMsg\').text(text).fadeIn(\'slow\');
		setTimeout(function(){$(\'#divMsg\').fadeOut(\'normal\');}, 3500);
	}
	//实时通知
	// function getTongzhi(){
	//  var url=\'?Controller=Main&action=GetNewTrade\';
	//  var param={};
	 
	//  $.getJSON(url,param,function(json){
	//   if(!json) return false;
	//   if(json.cnt>0) {
	//   		if(!json.kindName)json.kindName=\'订单\';
	//    		ymPrompt.confirmInfo({showMask:false,message:\'系统发现新的\'+json.kindName+\'！请查看详细\'+json.title,title:json.kindName,winPos:\'rb\',handler:function(a){
	// 			// if(a==\'ok\') {
	// 			// 	var url=\'?controller=OaMessage&action=right&no_edit=1\';
	// 			// 	window.open(url);
	// 			// }
	// 			//弹出窗口后则不显示弹出窗口了
	// 			var url="?Controller=Main&action=TzNext";
	// 			$.getJSON(url,{},function(json){
	// 			});
	// 			setTimeout(\'getTongzhi()\',500000);
	// 		}}) ;
	//   } else {
	//   		setTimeout(\'getTongzhi()\',500000);
	//   }

	//  });
	// }
	//实时通知
	// function getTktongzhi(){
	//  var url=\'?Controller=Main&action=GetNewTkmsg\';
	//  var param={};
	 
	//  $.getJSON(url,param,function(json){
	//   if(!json) return false;
	//   if(json.cnt>0) {
	//   		if(!json.kindName)json.kindName=\'采购退库\';
	//    		ymPrompt.confirmInfo({showMask:false,message:\'您有一笔采购单号为:\'+json.title+\'的采购退库交易，请尽快确认！\',winPos:\'rb\',handler:function(a){
	// 			// if(a==\'ok\') {
	// 			// 	var url=\'?controller=OaMessage&action=right&no_edit=1\';
	// 			// 	window.open(url);
	// 			// }
	// 			//弹出窗口后则不显示弹出窗口了
	// 			var url="?Controller=Main&action=TzNext";
	// 			$.getJSON(url,{},function(json){
	// 			});
	// 			setTimeout(\'getTktongzhi()\',500000);
	// 		}}) ;
	//   } else {
	//   		setTimeout(\'getTktongzhi()\',500000);
	//   }

	//  });
	// }

	// function getMail(){
	//  var url=\'?controller=main&action=GetMailByAjax\';
	//  var param={};

	//  $.getJSON(url,param,function(json){
	//   if(!json) return false;
	//   if(json.cnt>0) {//如果原来的加急数>0且当前加急数>原来加急数,弹出窗
	//    		ymPrompt.confirmInfo({showMask:false,message:\'系统发现有新的邮件！请进入邮件管理查看详细\',title:\'内部邮件\',winPos:\'rb\',handler:function(a){
	// 			if(a==\'ok\') {
	// 				var url=\'?controller=Mail&action=MailNoRead&no_edit=1\';
	// 				window.open(url);
	// 			}
	// 			setTimeout(\'getMail()\',60000);
	// 		}}) ;
	//   } else {
	//   		setTimeout(\'getMail()\',60000);
	//   }

	//  });
	// }

	/**
	 * 其他自动加载信息
	*/
	$(function(){
		//快捷更新数据方式
		$(\'body\').keydown(function(e){
			var currKey=e.keyCode||e.which||e.charCode;
			//alert(currKey);
			//如果ctrl+alt+shift+A弹出db_change输入框,此功能只开发给开发人员形成db_change文档时用
			if(e.altKey&&e.ctrlKey&&e.shiftKey&&currKey==65) {
				var url = \'?controller=Dbchange&action=Add\';
				window.open(url);
			}
			//如果ctrl+alt+shift+z弹出执行窗口,此功能只给实施人员用
			if(e.altKey&&e.ctrlKey&&e.shiftKey&&currKey==90) {
				var url = \'?controller=Dbchange&action=AutoUpdate\';
				window.open(url);
			}
		});

		//密码修改弹出界面
		$(\'#users\').click(function(){
			var url=\'?controller=Acm_User&action=ChangePwd&parent=1\';			
	        EXT_WIN_PASS = Ext.create(\'Ext.Window\', {
				id:\'winUser\',
		        title: \'密码管理\',
		        width: 870,
		        height: 480,
		        // plain: true,
		        layout: \'fit\',
		        modal:true,
		        html:\'<iframe scrolling="auto" frameborder="0" width="100%" height="100%" src="\'+url+\'"> </iframe>\'
		    }).show();

		    EXT_WIN_PASS.closet = function(){
		    	setTimeout(function(){EXT_WIN_PASS.close()},500)
		    };
	     });
	});
</script>
'; ?>

</head>
<body style="position:relative;">
<!-- 正在载入信息 -->
  <div id="loading-mask"></div>
  <div id="loading">
    <div class="loading-indicator"><img src="Resource/ext/loading.gif" width="120" height="120" style="margin-right:8px;" align="absmiddle"/>正在载入...</div>
  </div>
<!-- 最上面的头部信息 -->
  <div id='header' style="width:100%;height:100%; color:#fff;background:#63B4D2;text-align:center;">
	<div style="float:left; margin-top:8px; padding-top:0px; font:16pt Arial, Helvetica, sans-serif; ">
		<!-- <img src="Resource/Image/logo2.png" style="margin-top:-9px;vertical-align:middle; width:60px;font-family:"微软雅黑", "新宋体";" /> -->
		&nbsp;<?php if ($this->caching && !$this->_cache_including) { echo '{nocache:890637a70bf0aa25d14a36800bc6d250#10}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'GetAppInf','varName' => 'systemName'), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:890637a70bf0aa25d14a36800bc6d250#10}';}?>

		<!-- （<?php if ($this->caching && !$this->_cache_including) { echo '{nocache:890637a70bf0aa25d14a36800bc6d250#11}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'GetAppInf','varName' => 'systemV'), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:890637a70bf0aa25d14a36800bc6d250#11}';}?>
） -->
	</div>
	<div id="divMsg">saving...</div>
	<div style="float:right; padding-top:0px;padding-right:20px;">
	    <ul class="nav nav-pills" role="tablist">
	    	<li role="presentation" class="dropdown">
			    <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
			    	<span class="glyphicon glyphicon-user"></span>
			      <?php echo $_SESSION['REALNAME']; ?>
<span class="caret"></span>
			    </a>
			    <ul class="dropdown-menu">
			      	<li><a href="#" id="users">
			      		<span class='glyphicon glyphicon-leaf'></span>
			      	修改密码</a></li>
		    		<li><a href="?controller=Login&action=logout">
		    			<span class='glyphicon glyphicon-off'></span>
		    		退出登陆</a></li>
			    </ul>
		  	</li>
		  	<!-- <li role="presentation">
			    <a href="http://www.eqinfo.com.cn" target='_blank' title='易奇科技'>
			      	<span class="glyphicon">&copy;</span>
					关于易奇
				</a>
	      	</li> -->
		    <!-- <li role="presentation">
			    <a href="#" id="users">
			      	<span class="glyphicon glyphicon-user" style="font-size:12pt;"></span>
					<?php echo $_SESSION['REALNAME']; ?>

				</a>
		      </li>
		    <li role="presentation">
			    <a href="?controller=Login&action=logout">
			      	<span class='glyphicon glyphicon-off' style="font-size:12pt;"></span>
			      	退出登陆
			    </a>
		    </li> -->
	    </ul>
	</div>
  </div>
</body>
</html>