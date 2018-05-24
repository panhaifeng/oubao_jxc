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
        var peihuoId=$('[name="peihuoId[]"]',tr).val();
        var planId=$('[name="planId[]"]',tr).val();
        var chukuId=$('[name="id[]"]',tr).val();
        var danjia=$('[name="danjia[]"]',tr).val();
        var url="?controller="+controller+"&action=ViewPeihuo&index="+index+'&peihuoId='+peihuoId+'&planId='+planId+'&chukuId='+chukuId;
        madan_layer = $.layer({
              type: 2,
              shade: [1],
              fix: false,
              title: '选择',
              maxmin: true,
              iframe: {src : url},
              // border:false,
              area: ['1024px' , '540px'],
              close: function(index){//关闭时触发
                  
              },
              //回调函数定义
              callback:function(index,ret) {
                $('[name="Madan[]"]',tr).val(ret.madanIds);
                $('[name="cnt[]"]',tr).val(parseFloat(ret.cntM).toFixed(2));
                $('[name="money[]"]',tr).val((ret.cntM)*danjia);
            }
        });
    })
  
    //全部出货功能
    $('[name="chuhuo_all"]').click(function(){
        var sid=[];
        $('[name="peihuoId[]"]').each(function(){
            sid.push($(this).val());
        });
        var peihuoId=sid.join(',');
         var url="?controller="+controller+"&action=AutoPeihuo";
         var param = {'peihuoId':peihuoId};
         $.ajax({
              type: "POST",
              url: url,
              data: param,
              success: function(json){
                if(json.success==false){
                    layer.alert(json.msg);
                }else{
                  var data = json.dataMadan;
                  var length = data.length;
                  var madan = $('[name="Madan[]"]');
                  var cnt = $('[name="cnt[]"]');
                  var productId = $('[name="productId[]"]');
                  // 2.将得到的json进行循环，判断json的花型六位号和productId[]的值是否一直，是则将tr取到，并将该行json填充至该tr控件中
                  for (var i = 0; i < data.length; i++) {
                    productId.each(function(){
                    var trs2 = $(this).parents('tr');
                    var tr=$(this).parents('.trRow');
                    var productId = $('[name="productId[]"]',tr).val();
                    var danjia = $('[name="danjia[]"]',tr).val();
                      if(productId==data[i].productId){
                            $('[name="Madan[]"]',tr).val(data[i].madanId);
                            $('[name="cnt[]"]',tr).val(data[i].cntM);
                            $('[name="money[]"]',tr).val((data[i].cntM)*danjia);
                      }
                    });
                  };
                }
              },
              dataType: 'json',
              async: false//同步操作
          });
    })
});

function tb_remove(){
    layer.close(madan_layer); //执行关闭
}
{/literal}
</script>