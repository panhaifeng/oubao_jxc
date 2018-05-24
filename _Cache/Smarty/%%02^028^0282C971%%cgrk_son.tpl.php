<?php /* Smarty version 2.6.10, created on 2018-04-26 15:05:16
         compiled from Cangku/Xianhuo/cgrk_son.tpl */ ?>
<?php echo '
<script language=\'javascript\'>

$(function(){
    // 明细的状态： a. 完成  rukuOver:1  b. 未完成 rukuOver:0
    // 功能
    // 标记完成
    // 标记未完成
    // 全选 / 消除所有选择
    $("#checkedAll").click(function(){
        $(\'input[name="ck[]"]\').prop("checked",this.checked);
        //  TODO- 全选时->确认：标记完成； 选中{仅所有未完成的} 
    });

    // 标记完成
    $("#finish").click(function(){
        // 获取所有选中的checkbox
        var $checkbox = $(\'input[name="ck[]"]:checked\');
        // 获取保存地址
        var href = $(\'#finish\').data(\'href\');
        // 获取所有选中的sid,
        var trades = [];
        // 遍历，拼接数据
        $checkbox.each(function(){
            var $checked = $(this);
            trades.push({\'id\':$checked.data(\'id\')});

        });

        // 提交后，控制"完成"按钮为不可点击状态; 
        $.post(href,{\'trades\':trades},function(data){

            if(data.flag && \'success\' == data.flag){
                // 处理成功
                // 提示成功
                // 刷新页面
                alert(\'入库已完成!\');
                window.location.reload()
                return  false;
            }else{
                // 失败处理
                // 1.提示标记失败
                // 2.不刷新页面
                alert(\'入库失败!\');
                return  false;
            }
            // 解锁"完成"按钮
        }, \'json\');
    });
});

</script>
'; ?>