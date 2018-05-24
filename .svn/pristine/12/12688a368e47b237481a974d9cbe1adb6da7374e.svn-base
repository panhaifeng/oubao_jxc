{webcontrol type='LoadJsCss' src="Resource/Script/jquery.json.js"}
<script language="javascript">
var controller = '{$smarty.get.controller}';
{literal}
$(function(){
  /**
  * 码单按钮单机事件
  * 打开码单出库界面
  */
  //点击申购单号
  $('[name="btnMadan"]').click(function(){
    var tr = $(this).parents('.trRow');
    var index=$('[name="btnMadan"]').index(this);
    var url="?controller="+controller+"&action=ViewMadan2&index="+index;
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

  /**
   * 复制按钮
  */
  $('[id="btnCopy"]','#table_main').click(function(){
    var tr = $(this).parents('.trRow');
    var nt = tr.clone(true);
      
      //有些数据需要制空
      $('[name="pihao[]"]',nt).val('');
      $('[name="cnt[]"]',nt).val('');
      $('[name="cntJian[]"]',nt).val('');
      $('[name="cntM[]"]',nt).val('');
      $('[name="Madan[]"]',nt).val('');
      $('[name="id[]"]',nt).val('');
      //拼接
      tr.after(nt);
  });
});

function tb_remove(){
  layer.close(madan_layer); //执行关闭
}
{/literal}
</script>