<script language='javascript'>
//建立数据集缓存，用来双击某行时提取数据
var _ds = {$arr_field_value|@json_encode};
//定义ondbclick事件
{literal}
$(function(){
	//全选或者反选
	$("#checkedAll").click(function(){

    $('input[name="ck[]"]').prop("checked",this.checked);
    });

	$('.trRow').dblclick(function(e){
		var pos = $('.trRow').index(this);
		//ds可能为对象，不是纯粹的array,所以这里不能直接使用_ds[pos]
		var i=0;
		for(var k in _ds) {
			if(typeof(_ds[k])=='function') continue;
			if(i==pos) {
				var obj = _ds[k];
				break;
			}
			i++;
		}
		//2014-9-24 by jeff,如果是layer，直接调用layer
		if(parent.layer) {
			//获取当前窗口索引
			var index = parent.layer.getFrameIndex(window.name);
			//返回_cache[0],写在通用模板中
			parent.layer.callback(index,obj);
			return;
		}
		if(window.parent.ymPrompt) window.parent.ymPrompt.doHandler(obj,true);//return false;
		else {
			if(window.parent.thickboxCallBack) {
				//return false;
				window.parent.tb_remove();
				window.parent.thickboxCallBack(obj,pos);
				window.close();
				return;
			}
			// debugger;
			if(window.opener!=undefined) {
				window.opener.returnValue = obj;
			} else {
				window.returnValue = obj;
			}
			window.close();			
		}
	});
	//保存
	$("#choose").click(function(){
		var obj = [];
		var supplierId=[];
		$('[name="ck[]"]').each(function(){
			if(this.checked){
				var i=$(this).val();
				for(var k in _ds) {
					if(i==k) {
						obj.push(_ds[k]);
						supplierId.push(_ds[k].supplierId);
						break;
					}
				}
		 	}
		});

		//2015-9-21 如果是修改还要判断供应商是否与已经登记的供应商一致
		if(parent.$('#cgId').val()){
			supplierId.push(parent.$('#supplierId').val());
		}
		supplierId=supplierId.unique();
		if(supplierId.length>1){
			alert('必须选择同一个供应商');
			return '';
		}
		//如果是layer，直接调用layer
		if(parent.layer) {
			//获取当前窗口索引
			var index = parent.layer.getFrameIndex(window.name);
			//返回_cache[0],写在通用模板中
			parent.layer.callback(index,obj);
			return;
		}
		if(window.opener!=undefined) {
			window.opener.returnValue = obj;
		} else {
			window.returnValue = obj;
		}
			window.close();
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
{/literal}
</script>