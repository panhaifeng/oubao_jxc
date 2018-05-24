<script language='javascript'>
var controller = '{$smarty.get.controller}';
{literal}
//审核按钮点击后改变审核状态
$(function(){
    $('.chkShenhe').click(function(e){
        if(!confirm('确认改变当前记录的审核状态吗?')) return false;
        var _this = this;
        var _old = $(this).prop('checked');
        var param={'id':$(this).prop('value'),'todo':_old};
        var url="?controller="+controller+"&action=ajaxShenhe";
        console.log(url);console.log(param);
        $.post(url,param,function(json){
            console.log(json);
            if(!json) {
                alert('服务器未正确响应');
                $(_this).prop('checked',_old);
                return false;
            }
            if(!json.succ) {
                alert('审核出错：'+json.msg);
                // debugger;
                $(_this).prop('checked',!_old);
                return false;
            }
            return true;
        },'json');
    });
});
{/literal}
</script>
