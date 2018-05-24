<?php /* Smarty version 2.6.10, created on 2018-05-16 15:16:50
         compiled from Caigou/sonTpl.tpl */ ?>
<script language="javascript">
<?php echo '
$(function(){
	$(\'[name="productId[]"]\').data(\'onSel\',function(ret){
		var tr=$(this).parents(\'.trRow\');
		$(\'[name="productId[]"]\',tr).val(ret.proCode);
		$(\'[name="proName[]"]\',tr).val(ret.proName);
		$(\'[name="chengfen[]"]\',tr).val(ret.chengfen);
		$(\'[name="shazhi[]"]\',tr).val(ret.shazhi);
		$(\'[name="jwmi[]"]\',tr).val(ret.jingmi+\'*\'+ret.weimi);
		$(\'[name="menfu[]"]\',tr).val(ret.menfu);
		$(\'[name="pihao[]"]\',tr).val(ret.pihao);
		// $(\'[name="color[]"]\',tr).val(ret.color);
	});
	$(\'[name="supplierId[]"]\').data(\'onSel\',function(ret){
	});
	// $(\'[name="supplierId"]\').data(\'onSel\',function(ret){
	// });
	//点击申购单号
	$(\'[name="shengouId"]\').click(function(){
		var sid=\'\';
		$(\'[name="shengou2proId[]"]\').each(function(){
			sid+=$(this).val()+\',\';
		});
		var url = $(this).attr(\'url\');
		url=url+\'&sid=\'+sid;
		//var url = $(this).attr(\'url\');
		$.layer({
		      type: 2,
		      shade: [1],
		      fix: false,
		      title: \'选择\',
		      maxmin: true,
		      iframe: {src : url},
		      // border:false,
		      area: [\'770px\' , \'440px\'],
		      close: function(index){//关闭时触发
		          
		      },
		      //回调函数定义
		      callback:function(index,ret) {
		      	charu(ret);
		       
	      	}
    	});
	})

	/**
	 * ps ：申购单登记时，选择部门后，申请人选项变为当前部门的员工，2015-09-28，by liuxin
	 * Time：2015/09/28 13:30:51
	 * @author Liuxin
	*/
	$(\'#depId\').change(function(){
        var depId = $(\'#depId\').val();
    	var url = \'?controller=Caigou_Shengou&action=SelectedChanged\';
    	var param = {depId:depId}
    	$.getJSON(url,param,function(json){
	        $("#employId").empty();
	        $(\'#s2id_employId\').children().children(\'span.select2-chosen\').html(\'请选择\');
	        $("#employId").append("<option value = \'\'>请选择</option>");
	        $.each(json, function(i,val){
	            $("#employId").append("<option value = " + val.employId + ">" + val.employName + "</option>");            
	        }); 
    	});
    });

    init();
});

function charu(ret){
	// $(\'#supplierId\').siblings(\'input\').val(ret[0].compName);
	$(\'#compName\').val(ret[0].compName);
	$(\'#supplierId\').val(ret[0].supplierId);
	var trs = $(\'.trRow\');
	var len = trs.length;
	var trTpl = trs.eq(len-1).clone(true);
	var parent = trs.eq(0).parent();
	$(\'input,select\',trTpl).val(\'\');
	for(var i=0;trs[i];i++) {
		var id = $(\'[name="id[]"]\',trs[i]);
		if(id.val()!=\'\') continue;
		trs.eq(i).remove();
	}
	//将选中订单的明细形成新行插入		
	for(var i=0;ret[i];i++) {
		var newTr = trTpl.clone(true);
		//如果是同一个产品者合并
		if(!hebing(ret[i])) continue;
		// $(\'[name="productId[]"]\',newTr).siblings(\'input\').val(ret[i].proCode);
		$(\'[name="productId[]"]\',newTr).val(ret[i].proCode);
		$(\'[name="proCode[]"]\',newTr).val(ret[i].proCode);
		$(\'[name="jiaoqi[]"]\',newTr).val(ret[i].jiaoqi);
		$(\'[name="proName[]"]\',newTr).val(ret[i].proName);
		$(\'[name="chengfen[]"]\',newTr).val(ret[i].chengfen);
		$(\'[name="shazhi[]"]\',newTr).val(ret[i].shazhi);
		$(\'[name="jwmi[]"]\',newTr).val(ret[i].jingmi+\'*\'+ret[i].weimi);
		$(\'[name="menfu[]"]\',newTr).val(ret[i].menfu);
		$(\'[name="pihao[]"]\',newTr).val(ret[i].pihao);

		// $(\'[name="color[]"]\',newTr).val(ret[i].color);
		$(\'[name="cnt[]"]\',newTr).val(ret[i].cnt);
		$(\'[name="unit[]"]\',newTr).val(ret[i].unit);
		$(\'[name="danjia[]"]\',newTr).val(ret[i].danjia);
		$(\'[name="cntM[]"]\',newTr).val(ret[i].cntM);
		$(\'[name="shengou2proId[]"]\',newTr).val(ret[i].sid);
		parent.append(newTr);
	}
}
//合并产品
function hebing(row){
	var trs = $(\'.trRow\');
	for(var i=0;trs[i];i++) {
		var proId = $(\'[name="productId[]"]\',trs[i]);
		var jiaoqi = $(\'[name="jiaoqi[]"]\',trs[i]);
		var danjia = $(\'[name="danjia[]"]\',trs[i]);
		var pihao = $(\'[name="pihao[]"]\',trs[i]);
		var color= $(\'[name="color[]"]\',trs[i]);
		var cnt= $(\'[name="cnt[]"]\',trs[i]);
		var sid=$(\'[name="shengou2proId[]"]\',trs[i]);
		var unit=$(\'[name="unit[]"]\',trs[i]);
		var cntM=$(\'[name="cntM[]"]\',trs[i]);
		if(proId.val()==row.productId&&color.val()==row.color&&jiaoqi.val()==row.jiaoqi&&danjia.val()==row.danjia){
			//如果合并的时候单位不一致  者转换为M
			if(unit.val()!=row.unit){
				cnt.val(parseFloat(cntM.val())+parseFloat(row.cntM));
				unit.val(\'M\');
			}
			else
				cnt.val(parseFloat(cnt.val())+parseFloat(row.cnt));
			sid.val(sid.val()+\',\'+row.sid);
			return false;
		}
	}
	return true;
}
function init(){
	var depId = $(\'#depId\').val();
	var empId=$(\'#employId\').val();
	var url = \'?controller=Caigou_Shengou&action=SelectedChanged\';
	var param = {depId:depId}
	$.getJSON(url,param,function(json){
        $("#employId").empty();
        $("#employId").append("<option value = \'\'>请选择</option>");
        $.each(json, function(i,val){
            $("#employId").append("<option value = " + val.employId + ">" + val.employName + "</option>");            
        });
        $(\'#employId\').val(empId);
	});
}
'; ?>

</script>