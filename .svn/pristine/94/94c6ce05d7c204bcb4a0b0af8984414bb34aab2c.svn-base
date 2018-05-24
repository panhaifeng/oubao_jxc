{webcontrol type='LoadJsCss' src="Resource/Script/jquery.json.js"}
<script language="javascript">
var controller = '{$smarty.get.controller}';
var kuwei='';
{literal}
$(function(){
    /**
    * 码单按钮单机事件
    * 打开码单出库界面
    */
    //点击申购单号
    $('[name="btnMadan"]').click(function(){
        if(kuwei!='现货'){
          alert('仓库必须是现货！');return;
        }
        var tr = $(this).parents('.trRow');
        var index=$('[name="btnMadan"]').index(this);
        var url="?controller="+controller+"&action=ViewMadan&index="+index;
        madan_layer = $.layer({
              type: 2,
              shade: [1],
              fix: false,
              title: '选择',
              maxmin: true,
              iframe: {src : url},
              // border:false,
              area: ['1024px' , '640px'],
              close: function(index){//关闭时触发
                  
              },
              //回调函数定义
              callback:function(index,ret) {
                if(ret.ok!=1) return false;
                $('[name="Madan[]"]',tr).val(ret.data);
                $('[name="cnt[]"]',tr).val(ret.cnt);
                $('[name="cntJian[]"]',tr).val(ret.cntJian);
            }
        });
    })
  
    $('[name="dahuo2proId[]"]').data('beforeOpen',function(url){
       if(kuwei!='现货'){
          alert('仓库必须是现货！');
          return false;
       }else return url;
    });

    $('[name="productId[]"]').data('onSel',function(ret){
        var tr=$(this).parents('.trRow');
        $('[name="productId[]"]',tr).val(ret.proCode);
        $('[name="proName[]"]',tr).val(ret.proName);
        $('[name="chengfen[]"]',tr).val(ret.chengfen);
        $('[name="shazhi[]"]',tr).val(ret.shazhi);
        $('[name="jwmi[]"]',tr).val(ret.jingmi+'*'+ret.weimi);
        $('[name="menfu[]"]',tr).val(ret.menfu);
        // $('[name="color[]"]',tr).val(ret.color);
    });
    $('[name="dahuo2proId[]"]').data('onSel',function(ret){
        var tr=$(this).parents('.trRow');
        var productId=$('[name="productId[]"]',tr).val();
        if( !productId || productId!=(ret.proCode)){
          alert('选择的花型六位号需要与订单选择的花型六位号一致');return;
        }
    });


    $('#kuweiIdru').change(function(){
        init();
    })

    init();
});

function tb_remove(){
    layer.close(madan_layer); //执行关闭
}
//初始化
function init(){
    kuwei=$('#s2id_kuweiIdru').children().children('span.select2-chosen').html();
    kuwei=kuwei.substring(0,2);
    $('#kuweiru').val(kuwei);
    if(kuwei=='现货'){
      $('[name="cnt[]"]').attr('readonly',true);
      $('[name="cntJian[]"]').attr('readonly',true);
    }
    else{
      $('[name="cnt[]"]').attr('readonly',false);
      $('[name="cntJian[]"]').attr('readonly',false);
    }
}
{/literal}
</script>