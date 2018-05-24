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
        var trades = [];
        ck.each(function(){
            id.push(this.value);
        });

        if(id.length==0) {
            alert('请选择订单明细进行打印!');
            return false;
        }
        var url="?controller=Cangku_Xianhuo_Xsck&action=PrintSelect&id="+id.join(',');
        // alert(url);return;
        window.open(url);
    });


    $('#print2').click(function(){
        var ck = $('[name="chk[]"]:checked');   //选择器
        //将所有的选中的id用,连接后传递
        var id=[];
        var trades = [];
        ck.each(function(){
            id.push(this.value);
        });

        if(id.length==0) {
            alert('请选择订单明细进行打印!');
            return false;
        }
        var url="?controller=Cangku_Xianhuo_Xsck&action=PrintSelect2&id="+id.join(',');
        // alert(url);return;
        window.open(url);
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