<script language="javascript">
var kwruId=0;
var kwruName='';
{literal}
$(function(){
	$('#kuweiId,#kuweiIdru').change(function(){
		var kuweiId=$('#kuweiId').val();
		var kuweiIdru=$('#kuweiIdru').val();
		if(kuweiId==kuweiIdru){
			alert('本库位不能调入本库位！');
			$('#s2id_kuweiIdru').children().children('span.select2-chosen').html(kwruName);
			$('#kuweiIdru').val(kwruId);
		}else{
			//已锁定的码单调入库位不能修改
			var id=$('#cgckId').val();
			if(id){
				var url="?controller=Cangku_Xianhuo_Diaohuo&action=lockMandan";
				var param={'id':id};
				$.post(url,param,function(json){
					if(json.success){
						alert('已有码单锁定，禁止修改');
						$("#kuweiIdru").val(kwruId);
						$('#s2id_kuweiIdru').children().children('span.select2-chosen').html(kwruName);
					}
				},'json');
			}
		}
	});
	init2();
});
function init2(){
	kwruId=$("#kuweiIdru").val();
	kwruName=$('#s2id_kuweiIdru').children().children('span.select2-chosen').html();
}
{/literal}
</script>