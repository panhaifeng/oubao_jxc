{*使用tbllist.tpl模板，最新*}
<base target="_self" />
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
{webcontrol type='LoadJsCss' src="Resource/Script/ext/adapter/ext/ext-base.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/ext/ext-all.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/ext/resources/css/ext-all.css"}

<script language="javascript">
var treeUrl='{$TreeUrl}';
var contentUrl='{$CenterUrl}';
{literal}
Ext.onReady(function() {
	/**
	*整个界面分两部分，左边菜单，右边产品档案
	*/
	//开始布局
	var items = [];
	items.push({
		id:'tree',
		// xtype: 'box',
		region: 'west',
		width: 200,
		minSize: 10,
		maxSize: 400,
		// collapsible: true,
		split: true,
		margins: '2 0 0 2',
		html: '<iframe scrolling="auto" frameborder="0" width="100%" height="100%" id="tree" src="'+treeUrl+'"> </iframe>'
	});

	items.push({
		id:'center',
		// xtype: 'box',
		region: 'center',
		margins: '2 0 0 0',
		autoScroll: false,
		html: '<iframe scrolling="auto" frameborder="0" width="100%" height="100%" id="content" src="'+contentUrl+'"> </iframe>'
	});

	var viewport = new Ext.Viewport({
            layout: 'border',
            items: items
    });
});
{/literal}
</script>
</head>
<body>
</body>
</html>