<?php /* Smarty version 2.6.10, created on 2018-05-08 13:49:15
         compiled from Cangku/Xianhuo/Report.tpl */ ?>
<script language='javascript'>
var controller = '<?php echo $_GET['controller']; ?>
';
<?php echo '
$(function(){

    //库存导出
    $(\'#kucun\').click(function(){


        window.location.href="?controller="+controller+"&action=ckReport";
    });


});
'; ?>

</script>