<script language='javascript'>
var controller = '{$smarty.get.controller}';
{literal}
$(function(){
    //全选
    $("#checkall").click(function(){
        $('input[name="check[]"]').prop("checked",this.checked);
    });

    //开票
    $('#kaipiao').click(function(){
        var orderId = getOrderIds();
        if(!orderId){
            layer.alert('至少选中一个订单');
            return false;
        }

        if(!validate_client())return false;

        window.location.href="?controller="+controller+"&action=AddOrder&orderIds="+orderId;
    });

    //标记完成ss
    $('#overKp,#canneloverKp').click(function(){
        var orderId = getOrderIds();
        if(!orderId){
            layer.alert('至少选中一个订单');
            return false;
        }
        
        var msg = "确认标记完成吗";
        if($(this).attr('id') == 'canneloverKp')msg = "确认取消完成吗";
        if(!confirm(msg+"？")){
            return false;
        }

        //ajax操作
        // debugger;
        var over = $(this).attr('over');
        var url = "?controller="+controller+"&action=SetOrderKpOver";
        var param = {'orderId':orderId,"over":over};
        layer.msg('系统正在处理……');
        $.getJSON(url,param,function(json){
            if(json.success==true){
                window.location.href=window.location.href;
            }
            else{
                layer.alert(json.msg);
            }
        });
    });

});

function getOrderIds(){
    var orderId = [];
    $('input[name="check[]"]').each(function(){
        if(this.checked)orderId.push($(this).val());
    });
    return orderId.join(',');
}

//验证客户是否一致
function validate_client(){
    var client = [];
    var taitou = [];
    $('input[name="check[]"]').each(function(){
        if(this.checked){
            client.push($(this).attr('clientId'));
            taitou.push($(this).attr('taitou'));
        }
    });

    client = unique(client);
    if(client.length>1){
        alert('客户信息必须一致，请确认');
        return false;
    }

    taitou = unique(taitou);
    if(taitou.length>1){
        if(!confirm("抬头不一致，确定继续开票，取消重新选择订单")){
            return false;
        }
        return true;
    }
    return true;

}

/**
  * 去除数组重复元素
  */
function unique(data){ 
    data = data || []; 
    var a = {}; 
    for (var i=0; i<data.length; i++) { 
        var v = data[i]; 
        if (typeof(a[v]) == 'undefined'){ 
            a[v] = 1; 
        } 
    }; 
    data.length=0; 
    for (var i in a){ 
        data[data.length] = i; 
    } 
    return data; 
} 
{/literal}
</script>
