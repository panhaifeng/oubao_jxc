<script language='javascript'>
//建立数据集缓存，用来双击某行时提取数据
var _ds = {$arr_field_value|@json_encode};
//定义ondbclick事件
{literal}
$(function(){
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
		//如果是layer，直接调用layer
		var _doc = parent.parent;
		if(_doc.layer) {
			//获取当前窗口索引
			var index = _doc.layer.getFrameIndex(parent.window.name);
			//返回_cache[0],写在通用模板中
			_doc.layer.callback(index,obj);
			return;
		}
		if(window.opener!=undefined) {
			parent.window.opener.returnValue = obj;
		} else {
			parent.window.returnValue = obj;
		}
		parent.window.close();
	});
});

{/literal}
</script>