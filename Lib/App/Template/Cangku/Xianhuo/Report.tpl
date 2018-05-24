<script language='javascript'>
var controller = '{$smarty.get.controller}';
{literal}
$(function(){

    //库存导出
    $('#kucun').click(function(){


        window.location.href="?controller="+controller+"&action=ckReport";
    });


});
{/literal}
</script>
