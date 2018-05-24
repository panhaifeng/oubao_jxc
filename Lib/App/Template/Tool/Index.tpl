<html>
<head>
<title>{webcontrol type='GetAppInf' varName='verName'}</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- <meta http-equiv="X-UA-Compatible" content="chrome=1" /> -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="renderer" content="webkit">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
{webcontrol type='LoadJsCss' src="Resource/Css/main1.css"}
{webcontrol type='LoadJsCss' src="Resource/bootstrap/bootstrap/css/bootstrap.css"}
{webcontrol type='LoadJsCss' src="Resource/ext/include-ext.js"}
{webcontrol type='LoadJsCss' src="Resource/ext/TabCloseMenu.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.1.9.1.js"}
{webcontrol type='LoadJsCss' src="Resource/Css/MainNew.css"}
{webcontrol type='LoadJsCss' src="Resource/bootstrap/bootstrap/js/bootstrap.js"}
{literal}
<script type="text/javascript">
	Ext.onReady(function() {
		var imagePath = 'Resource/Script/ext/resources/images';
		Ext.BLANK_IMAGE_URL = imagePath+'/default/s.gif';
		var detailEl;
		var tabs;
		var welcomeUrl='';

		//加载Tree的数据信息
		var store = Ext.create('Ext.data.TreeStore', {
			root: {
				expanded: true
			},
			proxy: {
				type: 'ajax',
				url: '?controller=Tool&action=GetToolMenu'
			}
		});

		//创建tree
		var treePanel = Ext.create('Ext.tree.Panel', {
			id: 'tree-panel',
			//title: '菜单目录',
			region:'center',
			split: true,
			border: false,
			autoScroll: true,
			store:store,
			rootVisible: false,
			lines: false,
			singleExpand: true,
			useArrows: true
		});

		treePanel.on('itemclick', function(tree,record,item,index,e,options){
			var n = record.data;

			//处理tab
			if(!n.leaf) {
				//展开
				record.expand();
				return ;
			}

			// debugger;//alert('右边窗口增加一个tab窗口');
			var sn = this.selModel.selNode || {}; // selNode is null on initial selection
			// var desc = "<p style='margin:8px'>"+(n.attributes.desc||'没有使用说明')+'</p>';
			var href = n.src;
			var id = 'docs-' + n.id;
			var text = n.text;
			
			var tab = tabs.getComponent(id);
			if(tab){
				document.getElementById('_frm'+id).src = href;
				tab.show();
				// tabs.setActiveTab(tab);//Active 
			}else{
				var t = tabs.add({
					id:id,
					title: text,
					icon: null,
					html: '<iframe scrolling="auto" frameborder="0" width="100%" height="100%" src="'+href+'" id="_frm'+id+'"></iframe>',
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
			region: 'west',
			id: 'west-panel', // see Ext.getCmp() below
			layout: 'border',
			title: '系统菜单',
			// split: true,
			width: 200,
			minWidth: 100,
			bodyBorder:true,
			border:'1',
			maxWidth: 400,
			collapsible: true,
			items: [treePanel]
		};

		var northItem = {
			xtype: 'box',
			region: 'north',
			height:45,
			border: false,
			contentEl: 'header'
		}

		var centerItem = Ext.create('Ext.tab.Panel',{
			id:'_tabs',
			region: 'center', // a center region is ALWAYS required for border layout
			deferredRender: false,
			enableTabScroll:true,
			activeTab: 0,     // first tab initially active
		 	// plain: true,
		 	bodyBorder:true,
		 	// bodyPadding: '2 0 0 0',
		    // tabPosition: 'bottom',
			// margins: '2 0 0 0',
			items: [{
				title: '<span class="glyphicon glyphicon-home"></span>&nbsp;首页',
				autoScroll: true,
				html: '<iframe scrolling="auto" frameborder="0" width="100%" height="100%" src="'+welcomeUrl+'"></iframe>'
			}],
			plugins : new Ext.ux.TabCloseMenu()
		});

		tabs = centerItem;

		var viewport = Ext.create('Ext.container.Viewport',{
            layout: 'border',
            items: [northItem,westItem,centerItem]
        });

        

		setTimeout(function(){
				$('#loading').remove();
				$('#loading-mask').fadeOut({remove:true});
		}, 500);

		//获取通知信息
		//setTimeout('getMail()',500);
	});


	//text表示提示框中要出现的文字，
	//ok表示是显示ok图标还是出错图标
	function showMsg(text,ok) {
		$('#divMsg').text(text).fadeIn('slow');
		setTimeout(function(){$('#divMsg').fadeOut('normal');}, 3500);
	}

	/**
	 * 其他自动加载信息
	*/
	$(function(){
		//快捷更新数据方式
		$('body').keydown(function(e){
			var currKey=e.keyCode||e.which||e.charCode;
			//alert(currKey);
			//如果ctrl+alt+shift+A弹出db_change输入框,此功能只开发给开发人员形成db_change文档时用
			if(e.altKey&&e.ctrlKey&&e.shiftKey&&currKey==65) {
				var url = '?controller=Dbchange&action=Add';
				window.open(url);
			}
			//如果ctrl+alt+shift+z弹出执行窗口,此功能只给实施人员用
			if(e.altKey&&e.ctrlKey&&e.shiftKey&&currKey==90) {
				var url = '?controller=Dbchange&action=AutoUpdate';
				window.open(url);
			}
		});

		//密码修改弹出界面
		$('#users').click(function(){
			var url='?controller=Acm_User&action=ChangePwd&parent=1';			
	        EXT_WIN_PASS = Ext.create('Ext.Window', {
				id:'winUser',
		        title: '密码管理',
		        width: 870,
		        height: 480,
		        // plain: true,
		        layout: 'fit',
		        modal:true,
		        html:'<iframe scrolling="auto" frameborder="0" width="100%" height="100%" src="'+url+'"> </iframe>'
		    }).show();

		    EXT_WIN_PASS.closet = function(){
		    	setTimeout(function(){EXT_WIN_PASS.close()},500)
		    };
	     });
	});
</script>
{/literal}
</head>
<body style="position:relative;">
<!-- 正在载入信息 -->
  <div id="loading-mask"></div>
  <div id="loading">
    <div class="loading-indicator"><img src="Resource/ext/loading.gif" width="120" height="120" style="margin-right:8px;" align="absmiddle"/>正在载入...</div>
  </div>
<!-- 最上面的头部信息 -->
  <div id='header' style="width:100%;height:100%; color:#fff;background:#63B4D2;text-align:center;">
	<div style="float:left; margin-top:6px; padding-top:0px; font:16pt Arial, Helvetica, sans-serif; font-weight:bold;">
		<img src="Resource/Image/logo2.png" style="margin-top:-9px;vertical-align:middle; width:60px;font-family:"微软雅黑", "新宋体";" />&nbsp;{webcontrol type='GetAppInf' varName='systemName'}（{webcontrol type='GetAppInf' varName='systemV'}）
	</div>
	<div id="divMsg">saving...</div>
	<div style="float:right; padding-top:0px;padding-right:20px;">
	    <ul class="nav nav-pills" role="tablist">
	    	<li role="presentation" class="dropdown">
			    <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
			    	<span class="glyphicon glyphicon-user"></span>
			      {$smarty.session.REALNAME}<span class="caret"></span>
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
		  	<li role="presentation">
			    <a href="http://www.eqinfo.com.cn" target='_blank' title='易奇科技'>
			      	<span class="glyphicon">&copy;</span>
					关于易奇
				</a>
	      	</li>
	    </ul>
	</div>
  </div>
</body>
</html>