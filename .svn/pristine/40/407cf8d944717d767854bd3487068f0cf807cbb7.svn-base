{*T1.tpl中需要用到的js代码,注意这里只能写通用性代码，个性化的功能需要另外建立tpl,参考生产计划的编辑模板实现过程*}
{webcontrol type='LoadJsCss' src="Resource/Script/Calendar/WdatePicker.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/common.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.1.9.1.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/layer/layer.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/jquery.validate.js"}
{webcontrol type='LoadJsCss' src="Resource/bootstrap/bootstrap/js/bootstrap.js"}
{webcontrol type='LoadJsCss' src="Resource/bootstrap/bootstrap/js/tooltip.js"}
{webcontrol type='LoadJsCss' src="Resource/bootstrap/bootstrap/js/jeffCombobox.js"}
{webcontrol type='LoadJsCss' src="Resource/bootstrap/bootstrap/js/TableHeader.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/select2/select2.js"}
{webcontrol type='LoadJsCss' src="Resource/Script/select2/select2.css"}

<script language="javascript">
var _removeUrl='?controller={$smarty.get.controller}&action={$RemoveByAjax|default:RemoveByAjax}';
var _removeUrl2='?controller={$smarty.get.controller}&action=RemoveQitaByAjax';
var _rules = {$rules|@json_encode};
var controller = '{$smarty.get.controller}';
var _tr = new Array();
{literal}
$(function(){
  ret2cab();
  $('#table_main').tableHeader();
  $('#table_else').tableHeader();
  
  //支持下拉框可以搜索的功能
  $('.select2').select2();

  //复制空行
  var tblIds = ['#table_main','#table_else'];
  //存在多个表格，复制行，并复制基础的事件，如明细表中一些选择事件，删除，
  for(var i=0;tblIds[i];i++){
    var _rows=$('.trRow',tblIds[i]);
    _tr[tblIds[i]] = _rows.eq(_rows.length-1).clone(true);
    $('input,select',_tr[tblIds[i]]).val('');
  }

  //日历下拉按钮点击后触发calendar;
  $('[id="btnCalendar"]').click(function(){
    var p = $(this).parents('.input-group');
    // debugger;
    //WdatePicker({el:'d12'})
    var id=$('input',p).attr('id');
    // debugger;
    WdatePicker({el:id});
  });


  //time类型
  $('[id="btnCalendarTime"]').click(function(){
    var p = $(this).parents('.input-group');

    var id=$('input',p).attr('id');

    WdatePicker({el:id,dateFmt:'yyyy-M-d H:mm:ss'});
  });
  
  //删除行,临时写在这里，后期需要用sea.js封装
  $('[id="btnRemove"]','#table_main').click(function(){
    fnRemove('#table_main',this,_removeUrl,'id[]');
  });

  //复制行,临时写在这里，后期需要用sea.js封装
  $('#btnAdd','#table_main').click(function(){
    fnAdd('#table_main');
  });

  $('[id="btnRemove"]','#table_else').click(function(){
    fnRemove('#table_else',this,_removeUrl2,'qtid[]');
  });

  //复制行,临时写在这里，后期需要用sea.js封装
  $('#btnAdd','#table_else').click(function(){
    fnAdd('#table_else');
  });

  //通用的弹出选择控件的事件定义,
  //里面暴露一个onSelect
  //另有
  $('[name="btnPop"]').click(function(e){
    var p = $(this).parents('.clsPop');
    //弹出窗地址
    var url = $(this).attr('url');    

    var textFld= $(this).attr('textFld');
    var hiddenFld= $(this).attr('hiddenFld');
    var id = $('.hideId',p).attr('id');
    var tip = $(this).attr('tip')||'选择列表';

     //打开窗口之前处理url地址
   if($("[name='"+id+"']").data("beforeOpen")){
      url=$('.hideId',p).data('beforeOpen').call($('.hideId',p),url);
      if(url==false)return;
    }
    var _width = parseInt($(this).attr('dialogWidth'))||750;

    //2014-9-24 by jeff,改为使用layer
    $.layer({
      type: 2,
      shade: [1],
      fix: false,
      title: tip,
      maxmin: false,
      iframe: {src : url},
      // border:false,
      area: [_width+'px' , '440px'],
      close: function(index){//关闭时触发
          
      },
      //回调函数定义
      callback:function(index,ret) {
        //选中行后填充textBox和对应的隐藏id
        // debugger;
        $('#textBox',p).val(ret[textFld]);
        $('.hideId',p).val(ret[hiddenFld]);
        //执行回调函数,就是触发自定义事件:onSel
        // if(!$("[name='"+id+"']").data("events") || !$("[name='"+id+"']").data("events")['onSel']) {
          
        // }
        if(!$("[name='"+id+"']").data("onSel")) {
          alert('未发现对popup控件 '+id+ ' 的回调函数进行定义,您可能需要在sonTpl中用data进行事件绑定:\n$("[name=\''+id+'[]\']").data(\'onSel\',function(ret){...})');
          return;
        }
        // debugger;

        $('.hideId',p).data('onSel').call($('.hideId',p),ret);
      }
    });
  });

  //明细信息中用到的数量字段名字会有变动
  if($('[name="cnt[]"]').length>0){
    getMoney('cnt[]');
  }
  if($('[name="cntYaohuo[]"]').length>0){
    getMoney('cntYaohuo[]');
  }

  /**
  * 添加5行方法，适应于多个table
  */
  function fnAdd(tblId) {
    var rows = $('.trRow',tblId);
    var len = rows.length;

    for(var i=0;i<5;i++) {
      var nt = rows.eq(len-1).clone(true);
      $('input,select',nt).val('');
      $('input[type="radio"],input[type="checkbox"]',nt).attr('checked',false);

       //加载新增后运行的代码
      if(typeof(beforeAdd) == 'function'){
        beforeAdd(nt,tblId);
      }
      //拼接
      rows.eq(len-1).after(nt);
    }

    return;
  }
  /**
  * 删除行方法，适应于多个table
  */
  function fnRemove(tblId,obj,url,idname) {
    //利用ajax删除,后期需要利用sea.js进行封装
    var url=url;
    // alert(url);
    var trs = $('.trRow',tblId);
    // alert(trs.length);
    if(trs.length<=1) {
      alert('至少保存一个明细');
      return;
    }

    var tr = $(obj).parents('.trRow');
    var id = $('[name="'+idname+'"]',tr).val();
    // alert(id);
    if(!id) {
      tr.remove();
      return;
    }

    if(!confirm('此删除不可恢复，你确认吗?')) return;
    var param={'id':id};
    $.post(url,param,function(json){
      if(!json.success) {
        var msg=json.msg?json.msg:'出错'
        alert(json.msg);
        return;
      }
      tr.remove();
    },'json');
    return;
  }


  //计算明细信息中的金额信息
  function getMoney(name){
    $('[name="'+name+'"],[name="danjia[]"]').change(function(){
        var tr = $(this).parents(".trRow");
        var danjia = parseFloat($('[name="danjia[]"]',tr).val()||0);
        var cnt = parseFloat($('[name="'+name+'"]',tr).val()||0);
        $('[name="money[]"]',tr).val((danjia*cnt).toFixed(2));

        $('[name="money[]"]',tr).change();
        return;
    });
  }

  //输入金额，自动计算单价

  //表单验证
  //表单验证应该被封装起来。
  var rules = $.extend({},_rules);
  /**
  *添加几个常用的验证规则
  */
  //重复验证，默认验证该model对应表数据，指定的某字段是否重复，如果有其他需求需要个性化代码

  $.validator.addMethod("repeat", function(value, element) {
    var url="?controller="+controller+'&action=Repeat';
    var param = {field:element.name,fieldValue:value,id:$('#id').val()};

    var repeat=true;
    //通过ajax获取值是否已经存在
    $.ajax({
      type: "GET",
      url: url,
      data: param,
      success: function(json){
        repeat = json.success;
      },
      dataType: 'json',
      async: false//同步操作
    });
    return repeat;
  }, "已存在");

  $('#form1').validate({
    rules:rules,
    submitHandler : function(form){
      var r=true;
      if(typeof(beforeSubmit)=="function") {
        r = beforeSubmit();
      }
      if(!r) return;
      if($('[name="Submit"]').attr('submits')!='submiting'){
        $('[name="Submit"]').attr('submits','submiting');
        form.submit();
      }
    }
    ,errorPlacement: function(error, element) {
      var type = element.attr('type');
      var obj = element;
      if(type=='hidden') {//如果是hidden控件，需要取得非hidden控件，否则位置会报错
        var par = element.parents('.input-group');
        obj = $('input[type="text"]',par);
      }
      var errorText = error.text();
      obj.attr('data-toggle','tooltip').attr('data-placement','bottom').attr('title',errorText);
      obj.tooltip('show');      
    }

    // ,debug:true
    ,onfocusout:false
    ,onclick:false
    ,onkeyup:false
  });
});
{/literal}
</script>