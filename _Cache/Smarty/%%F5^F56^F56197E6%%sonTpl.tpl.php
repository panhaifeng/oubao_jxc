<?php /* Smarty version 2.6.10, created on 2018-05-16 15:15:15
         compiled from Cangku/sonTpl.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'webcontrol', 'Cangku/sonTpl.tpl', 1, false),)), $this); ?>
<?php $this->_cache_serials['Lib/App/../../_Cache/Smarty\%%F5^F56^F56197E6%%sonTpl.tpl.inc'] = '612045b8706f5446e0075edcc9f30d23';  if ($this->caching && !$this->_cache_including) { echo '{nocache:612045b8706f5446e0075edcc9f30d23#0}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'LoadJsCss','src' => "Resource/Script/jquery.json.js"), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:612045b8706f5446e0075edcc9f30d23#0}';}?>

<?php if ($this->caching && !$this->_cache_including) { echo '{nocache:612045b8706f5446e0075edcc9f30d23#1}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'LoadJsCss','src' => "Resource/Script/ajaxfileupload.js"), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:612045b8706f5446e0075edcc9f30d23#1}';}?>

<script language="javascript">
var controller = '<?php echo $_GET['controller']; ?>
';
<?php echo '
$(function(){
	/**
	* 码单按钮单机事件
	* 打开码单出库界面
	*/
	//点击申购单号
	$(\'[name="btnMadan"]\').click(function(){
		var tr = $(this).parents(\'.trRow\');
		var index=$(\'[name="btnMadan"]\').index(this);
		var url="?controller="+controller+"&action=ViewMadan&index="+index;
		madan_layer = $.layer({
		      type: 2,
		      shade: [1],
		      fix: true,
		      title: \'选择\',
		      maxmin: false,
		      iframe: {src : url},
		      // border:false,
		      area: [\'99%\' , \'99%\'],
		      close: function(index){//关闭时触发
		          
		      },
		      //回调函数定义
		      callback:function(index,ret) {
		      	if(ret.ok!=1) return false;
		      	$(\'[name="Madan[]"]\',tr).val(ret.data);
		      	$(\'[name="cnt[]"]\',tr).val(ret.cnt);
		      	$(\'[name="cntJian[]"]\',tr).val(ret.cntJian);
	      	}
    	});
	})

	$(\'[name="productId[]"]\').data(\'onSel\',function(ret){
		var tr=$(this).parents(\'.trRow\');
		$(\'[name="productId[]"]\',tr).val(ret.proCode);
		$(\'[name="proName[]"]\',tr).val(ret.proName);
		$(\'[name="chengfen[]"]\',tr).val(ret.chengfen);
		$(\'[name="shazhi[]"]\',tr).val(ret.shazhi);
		$(\'[name="jwmi[]"]\',tr).val(ret.jingmi+\'*\'+ret.weimi);
		$(\'[name="menfu[]"]\',tr).val(ret.menfu);
		// $(\'[name="color[]"]\',tr).val(ret.color);
	});

	/**
	 * 复制按钮
	*/
	$(\'[id="btnCopy"]\',\'#table_main\').click(function(){
		var tr = $(this).parents(\'.trRow\');
		var nt = tr.clone(true);
	    
	    //有些数据需要制空
	    $(\'[name="pihao[]"]\',nt).val(\'\');
	    $(\'[name="cnt[]"]\',nt).val(\'\');
	    $(\'[name="cntJian[]"]\',nt).val(\'\');
	    $(\'[name="cntM[]"]\',nt).val(\'\');
	    $(\'[name="Madan[]"]\',nt).val(\'\');
	    $(\'[name="id[]"]\',nt).val(\'\');
	    //拼接
	    tr.after(nt);
	});

	/**
	 * 码单导入采购入库
	*/
	$(\'.form-group\').on(\'change\',\'[name=madanExport]\',function(){
		if(!$(\'input[id=madanExport]\').val()) return;
        var url="?controller="+controller+"&action=SaveMadanExport";
        $.ajaxFileUpload({
            url: url,
            secureuri:false,
            fileElementId:\'madanExport\',
            dataType: \'json\',
            success: function(json){
                if(json.success==false){
                    layer.alert(json.msg);
                }else{
                	var data = json.dataMadan;
                    var length = data.length;
                    // 1.取得所以要填充的控件
                    var madan = $(\'[name="Madan[]"]\');
                    var cntJian = $(\'[name="cntJian[]"]\');
                    var cnt = $(\'[name="cnt[]"]\');
                    var proCode = $(\'[name="proCode[]"]\');
                    var pihao = $(\'[name="pihao[]"]\');
                    // 2.将得到的json进行循环，判断json的花型六位号和proCode[]的值是否一直，是则将tr取到，并将该行json填充至该tr控件中
                    for (var i = 0; i < data.length; i++) {
                    	proCode.each(function(){
                			var trs2 = $(this).parents(\'tr\');
                			var tr=$(this).parents(\'.trRow\');
              			  	var proCode = $(\'[name="proCode[]"]\',tr).val();
                    		if(proCode==data[i].productId){
								$(\'[name="Madan[]"]\',tr).val(data[i].jsonData[i]);
								$(\'[name="cnt[]"]\',tr).val(data[i].cnt);
                                $(\'[name="cntJian[]"]\',tr).val(data[i].rollNo);
								$(\'[name="pihao[]"]\',tr).val(data[i].pihao);
                    		}
                    	});
                    };
                }
            },
            async: false//同步操作
        });
	});


	/**
	 * 码单导入加工入库
	*/
	$(\'.form-group\').on(\'change\',\'[name=madanExport2]\',function(){
		if(!$(\'input[id=madanExport2]\').val()) return;
        var url="?controller="+controller+"&action=SaveMadanExport";
        $.ajaxFileUpload({
            url: url,
            secureuri:false,
            fileElementId:\'madanExport2\',
            dataType: \'json\',
            success: function(json){
                if(json.success==false){
                    layer.alert(json.msg);
                }else{
                	var data = json.dataMadan;
                    var length = data.length;
                    // 1.取得所以要填充的控件
                    var madan = $(\'[name="Madan[]"]\');
                    var cntJian = $(\'[name="cntJian[]"]\');
                    var cnt = $(\'[name="cnt[]"]\');
                    var productId = $(\'[name="productId[]"]\');
                    var pihao = $(\'[name="pihao[]"]\');
                    // 2.将得到的json进行循环，判断json的花型六位号和productId[]的值是否一直，是则将tr取到，并将该行json填充至该tr控件中
                    for (var i = 0; i < data.length; i++) {
                    	productId.each(function(){
                			var trs2 = $(this).parents(\'tr\');
                			var tr=$(this).parents(\'.trRow\');
              			  	var productId = $(\'[name="productId[]"]\',tr).val();
                    		if(productId==data[i].productId){
								$(\'[name="Madan[]"]\',tr).val(data[i].jsonData[i]);
								$(\'[name="cnt[]"]\',tr).val(data[i].cnt);
								$(\'[name="cntJian[]"]\',tr).val(data[i].rollNo);
								$(\'[name="pihao[]"]\',tr).val(data[i].pihao);
                    		}
                    	});
                    };
                }
            },
            async: false//同步操作
        });
	});
});

function tb_remove(){
	layer.close(madan_layer); //执行关闭
}
'; ?>

</script>