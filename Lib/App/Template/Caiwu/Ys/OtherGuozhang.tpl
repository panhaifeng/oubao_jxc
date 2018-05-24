{literal}
<script language="javascript">
$(function(){
    $('[name="compName"]').data('onSel',function(ret){
    });
    $('[name="supplierId"]').data('onSel',function(ret){
    });
    //订单打开之前
    $('[name="orderId"]').data('beforeOpen',function(url){
        var clientId=$('#clientId').val();
        url+="&clientId="+clientId;
        return url;
    });
    $('[name="orderId"]').data('onSel',function(ret){
		$('#clientId').val(ret.clientId);
		$('#clientId').siblings('input').val(ret.compName);
    });
    $('[name="clientId"]').data('onSel',function(ret){
        $(this).val(ret.member_id);
    });

    
});
</script>
{/literal}