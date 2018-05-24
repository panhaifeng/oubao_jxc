<?php /* Smarty version 2.6.10, created on 2018-04-19 16:17:34
         compiled from Caiwu/Ys/Guozhang.tpl */ ?>
<script language='javascript'>
var controller = '<?php echo $_GET['controller']; ?>
';
<?php echo '
$(function(){
    //全选
    $("#checkedAll").click(function(){

    $(\'input[name="chk[]"]\').prop("checked",this.checked);
    });

    //单价变动影响金额
    $(\'[name="danjia[]"]\').on(\'change\',function(){
        var trs = $(this).parents(\'tr\');
        var cnt=$(\'[name="cntM[]"]\',trs).val();
        var danjia=$(this).val();
        if (isNaN(Number(danjia))) {
            danjia = 0;
            $(this).val(danjia);
        }
        var oldMoney = $(\'[name="oldMoney[]"]\',trs).val();
        var money=danjia*cnt;
        var heji = Number($(\'#heji\').html());
        var zhekouMoney=$(\'[name="zhekouMoney[]"]\',trs).val();
        var money2=money-zhekouMoney;
        var newHeji = heji - oldMoney + money2;

        //保存2位小数,2016-12-19 by jeff
        money2 = Number(money2.toFixed(2));
        money = Number(money.toFixed(2));  

        $(\'[name="money[]"]\',trs).val(money2);
        $(\'[name="_money[]"]\',trs).val(money);

        $(\'[name="oldMoney[]"]\',trs).val(money2);
        $(\'#heji\').html(newHeji);
        var id=$(\'[name="id[]"]\',trs).val();
    });

    //折扣金额变化 入账金额变化
    $(\'[name="zhekouMoney[]"]\').on(\'change\',function(){
        
        var trs = $(this).parents(\'tr\');
        var money=Number($(\'[name="_money[]"]\',trs).val());
        var zhekouMoney=Number($(this).val());
        if (isNaN(Number(zhekouMoney))) {
            zhekouMoney = 0;
            $(this).val(zhekouMoney);
        }
        var money2=money-zhekouMoney;
        money2 = Number(money2.toFixed(2));

        var oldMoney = Number($(\'[name="oldMoney[]"]\',trs).val());
        var heji = Number($(\'#heji\').html());
        var newHeji = (heji+money2-oldMoney).toFixed(2);
        $(\'[name="money[]"]\',trs).val(money2);
        $(\'[name="oldMoney[]"]\',trs).val(money2);
        $(\'#heji\').html(newHeji);
        $(\'[name="oldZhekou[]"]\',trs).val(zhekouMoney);

    });

    //入账金额变化 总计金额也随着变化
    $(\'[name="money[]"]\').on(\'change\',function(){
        var trs = $(this).parents(\'tr\');
        var money = Number($(\'[name="money[]"]\',trs).val());
        var _money=$(\'[name="_money[]"]\',trs).val();
        if (isNaN(Number(money))) {
            money = 0;
            $(this).val(money);
        }
        var oldMoney = Number($(\'[name="oldMoney[]"]\',trs).val());
        var heji = Number($(\'#heji\').html());
        var newHeji = heji - oldMoney + money;
        var zhekouMoney=_money-money; 
        zhekouMoney = Number(zhekouMoney.toFixed(2));

        $(\'[name="zhekouMoney[]"]\',trs).val(zhekouMoney);
        $(\'[name="oldZhekou[]"]\',trs).val(zhekouMoney);
        $(\'#heji\').html(newHeji);
        $(\'[name="oldMoney[]"]\',trs).val(money);
       
    });

    //发生金额变化 入账金额也变化 折扣金额置空
    $(\'[name="_money[]"]\').on(\'change\',function(){
        var trs = $(this).parents(\'tr\');
        var money=Number($(\'[name="_money[]"]\',trs).val());
        if (isNaN(Number(money))) {
            money = 0;
            $(this).val(money);
        }
        var cnt=$(\'[name="cnt[]"]\',trs).val();
        var danjia=money/cnt;
        $(\'[name="money[]"]\',trs).val(money);
        $(\'[name="danjia[]"]\',trs).val(danjia.toFixed(2));
        $(\'[name="zhekouMoney[]"]\',trs).val(0);
        $(\'[name="oldZhekou[]"]\',trs).val(0);

        //发生金额变化 总计金额也随着变化
        var oldMoney = Number($(\'[name="oldMoney[]"]\',trs).val());
        var heji = Number($(\'#heji\').html());
        var newHeji = heji - oldMoney + money; 
        newHeji = Number(newHeji.toFixed(2));
        $(\'#heji\').html(newHeji);
        $(\'[name="oldMoney[]"]\',trs).val(money);
    });

    $(\'#save2\').click(function(){
        //2016-12-5 by jeff,3秒内disabled
        var _this = this;
        $(_this).text(\'保存中\').attr(\'disabled\',true);
        setTimeout(function(){
            $(_this).text(\'保存\').attr(\'disabled\',false);
        },3000);
        // return false;
        var ck = $(\'[name="chk[]"]:checked\');   //选择器
        //将所有的选中的id用,连接后传递
        var id=[];
        var danjia=[];
        var money=[];
        var _money=[];
        var zhekouMoney=[];
        ck.each(function(){
            id.push($(this).val());
            var trs = $(this).parents(\'tr\');
            danjia.push($(\'[name="danjia[]"]\',trs).val());        
            money.push($(\'[name="money[]"]\',trs).val());
            _money.push($(\'[name="_money[]"]\',trs).val());
            zhekouMoney.push($(\'[name="zhekouMoney[]"]\',trs).val());
        });
        if(id.length==0) {
            alert(\'请选择过账信息!\');
            return false;
        }
        //只能点击一次保存按钮
        $(\'#save2\').attr("disabled","disabled");
        var url="?controller="+controller+"&action=Save";
        console.log(money);
        var param={ids:id,danjia:danjia,money:money,_money:_money,zhekouMoney:zhekouMoney};
        $.getJSON(url,param,function(json){
             if(json==true){
                alert(\'保存成功\');
             }
            window.location.reload();
         });
    });
});
'; ?>

</script>