<script language="javascript">
{literal}
$(function(){
	$('[name="clientId"]').data('onSel',function(ret){
		$(this).val(ret.member_id);
		if(ret.fire=='是'){
			alert('该业务员已离职！');
			$('[name="employId"]').val();
			$('#s2id_employId').children().children('span.select2-chosen').html();
		}else{
			$('[name="employId"]').val(ret.traderId);
			$('#s2id_employId').children().children('span.select2-chosen').html(ret.traderName);
		}
		
	});
	$('[name="supplierId"]').data('onSel',function(ret){
		$(this).val(ret.id);
		
	});
	$('[name="productId[]"]').data('onSel',function(ret){
		var tr=$(this).parents('.trRow');
		$('[name="productId[]"]',tr).val(ret.proCode);
		$('[name="proName[]"]',tr).val(ret.proName);
		$('[name="chengfen[]"]',tr).val(ret.chengfen);
		$('[name="shazhi[]"]',tr).val(ret.shazhi);
		$('[name="jwmi[]"]',tr).val(ret.jingmi+'*'+ret.weimi);
		$('[name="menfu[]"]',tr).val(ret.menfu);
		$('[name="color[]"]',tr).val(ret.color);
	});

	//金额触发改变事件后触发的事件
	$('[name="money[]"],[name="cost_freight"]').on('change',function(){
		var money = parseFloat($('#cost_freight').val())||0;
		$('[name="money[]"').each(function(){
			var tmoney = parseFloat($(this).val())||0;
			money+=tmoney;
		});

		$('#sumMoney').val(money);
	});
});	
{/literal}
</script>