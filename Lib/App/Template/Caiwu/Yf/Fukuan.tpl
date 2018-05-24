<script language="javascript">
{literal}
$(function(){
    $('[name="supplierId"]').data('onSel',function(ret){
        $(this).val(ret.id);
    });

}); 
{/literal}
</script>