{*
 Author : chenran
 FName  :xsckdan.tpl
 Time   :2015/10/08 
 Remark :出货单打印优化：支持选择多出库单打印
*}
{literal}
<script language='javascript'>

$(function(){
	//全选
    $("#checkedAll").click(function(){

    $('input[name="chk[]"]').prop("checked",this.checked);
    });

    $('#print').click(function(){
        var ck = $('[name="chk[]"]:checked');   //选择器
        //将所有的选中的id用,连接后传递
        var id=[];
        var ord=[];
        var pay=[];
        debugger;
        ck.each(function(i){
            var tr=$(this).parents('.trRow');
            debugger;
            var compName=$('[name="compName"]',tr).html();
            var payment=$('[name="payment"]',tr).html();
            ord.push(compName);
            pay.push(payment);
            id.push(this.value);
        });
        // alert(ord);die;
        if(id.length==0) {
            alert('请选择订单明细进行出库单打印!');
            return false;
        }
        ord=ord.unique();
        if(ord.length>1){
            alert('客户必须一样，才能一起打印！');
            return false;
        }
        pay=pay.unique();
        if(pay.length>1){
            alert('支付方式必须一样，才能一起打印！');
            return false;
        }
        var mode=$('#mode').val();
        var url="?controller=Cangku_"+mode+"_Plan&action=Print&id="+id.join(',');
        // alert(url);return;
        window.open(url);
    });

    $('#chu').click(function(){
        var ck = $('[name="chk[]"]:checked');   //选择器
        //将所有的选中的id用,连接后传递
        var id=[];
        var orderId=[];
        var ord=[];
        ck.each(function(i){
            var tr=$(this).parents('.trRow');
            debugger;
            var compName=$('[name="compName"]',tr).html();
            ord.push(compName);
            id.push(this.value);
            orderId.push(this.value);
        });
        // alert(ord);die;
        if(id.length==0) {
            alert('请选择订单明细进行出库!');
            return false;
        }
        ord=ord.unique();
        if(ord.length>1){
            alert('客户必须一样，才能一起出库！');
            return false;
        }
        var mode=$('#mode').val();
        var url="?controller=Cangku_"+mode+"_Xsck&action=add&id="+id.join(',');
        window.location.replace(url);
    });
});
//去掉重复值
Array.prototype.unique=function(){
    var o={},newArr=[],i,j;
    for( i=0;i<this.length;i++){
        if(typeof(o[this[i]])=="undefined")
        {
            o[this[i]]="";
        }
    }
    for(j in o){
         newArr.push(j)
    }
    return newArr;
}
</script>
{/literal}