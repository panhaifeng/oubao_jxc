{webcontrol type='LoadJsCss' src="Resource/Script/jquery.json.js"}
{webcontrol type='LoadJsCss' src="Resource/bootstrap/bootstrap/js/bootstrap.autocomplete.js"}
<script language="javascript">
var controller = '{$smarty.get.controller}';
var _ds = {$arr_field_value|@json_encode};
{literal}
$(function(){
    /**
    * 码单按钮单机事件
    * 打开码单出库界面
    */
    //点击申购单号
    $('[name="btnMadan"]').click(function(){
        //先取得批号和产品Id
        var tr = $(this).parents('.trRow');
        var pihao=$('[name="pihao[]"]',tr).val();
        var productId=$('[name="productId[]"]',tr).val();
        var chukuId=$('[name="id[]"]',tr).val();
        var index=$('[name="btnMadan"]').index(this);
        var kind=$('#kind').val();
        if(!pihao||!productId){
          alert('批号和花型六位号必选！'); return;
        }
        if(productId==''){
            alert('请选择产品！');
            return false;
        }
        var url="?controller="+controller+"&action=ViewMadan&productId="+productId+"&chukuId="+chukuId+"&pihao="+pihao+"&index="+index;
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
                $('[name="Madan[]"]',tr).val(ret.madanIds);
                //采购退库 数值不变，后台代码处理
                /*if(kind=='采购退库'){
                  $('[name="cntM[]"]',tr).val(ret.cntM*-1);
                  $('[name="cnt[]"]',tr).val(ret.cntM*-1);
                  $('[name="cntJian[]"]',tr).val(ret.cntJian*-1);
                }else{*/
                  // $('[name="danjia[]"]',tr).val(ret.danjia);
                  $('[name="cntM[]"]',tr).val(ret.cntM);
                  $('[name="cnt[]"]',tr).val(parseFloat(ret.cntM).toFixed(2));
                  $('[name="cntJian[]"]',tr).val(ret.cntJian);
                /*}*/
                $('[name="unit[]"]',tr).val('M');
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
        ;
        //2015-11-4 by jiang 如果是现货调货 每次点击产品 数量清空
        var kind=$('#kind').val();
        var cangkuName=$('#cangkuName').val();
        if(kind=='调货'&&cangkuName=='现货仓库'){
          $('[name="cnt[]"]',tr).val('');
          $('[name="cntJian[]"]',tr).val('');
          $('[name="Madan[]"]',tr).val('');
        }
    });

    //2015-11-6 by jiang 如果是现货调货 每次批号改变 数量清空
    $('[name="pihao[]"]').change(function(){
        var tr=$(this).parents('.trRow');
        var kind=$('#kind').val();
        var cangkuName=$('#cangkuName').val();
        if(kind=='调货'&&cangkuName=='现货仓库'){
          $('[name="cnt[]"]',tr).val('');
          $('[name="cntJian[]"]',tr).val('');
          $('[name="Madan[]"]',tr).val('');
        }
    });


    //渲染批号
    if($('[name="pihao[]"]').length>0){
    $('[name="pihao[]"]').each(function(){
      var tr = $(this).parents('.trRow');
      autoComplete(tr);
    });
  }  
  
    //产品打开前
    // $('[name="productId[]"]').data('beforeOpen',function(url){
    //       var kuweiId=$('#kuweiId').val();
    //       if(kuweiId>0)url+="&kuweiId="+kuweiId;
    //       return url;
    //   });
   $('[name="pihao[]"]').data('beforeOpen',function(url){
          var tr = $(this).parents('.trRow');
          var productId=$('[name="productId[]"]',tr).val();
          if(productId>0)url+="&productId="+productId;
          return url;
      });
   $('[name="pihao[]"]').data('onSel',function(ret){
      var tr = $(this).parents('.trRow');
      $('[name="pihao[]"]',tr).val(ret.millNo);
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
	
  if(typeof(beforeAdd) == 'function'){
      beforeAdd(nt,'#table_main');
  }
	//拼接
	tr.after(nt);
  });

});

function autoComplete(tr){
   //去除浏览器自带的自动完成 
  $('[name="pihao[]"]',tr).attr('autocomplete','off');
  $('[name="pihao[]"]',tr).autocomplete({
     source:function(query,process){
     //debugger;
      var matchCount = this.options.items;
      $.post("?controller=Cangku_Chuku&action=Autocomplete",{"title":query},function(respData){
        var myobj=eval(respData);
        return process(myobj);
      });
    },
    formatItem:function(item){
      return item["pihao"];
    },
    setValue:function(item){
      return {'data-value':item["pihao"]};
    }
  });
}

function beforeAdd(tr,tblId){
  //去掉原来的渲染 
  var cell = '[name="pihao[]"]';
  
  //不知道有什么作用 先注释掉 2016年10月11日 13:46:53 
  // $(cell,tr).replaceWith(function(){
  //   var html = $(cell,_tr[tblId]).parent().html();
  //   return html;
  // });

  $(cell,tr).val('');
  //重新渲染
  autoComplete(tr);
}

function tb_remove(){
    layer.close(madan_layer); //执行关闭
}
{/literal}
</script>