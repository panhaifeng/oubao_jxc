<select name="kind" id="kind">
	<option value='' {if $arr_condition.kind==''} selected="selected" {/if}>入库类型</option>
	<option value='采购入库' {if $arr_condition.kind =='采购入库'} selected="selected" {/if}>采购入库</option>
	<option value='其他入库' {if $arr_condition.kind =='其他入库'} selected="selected" {/if}>其他入库</option>
    <option value='加工入库' {if $arr_condition.kind =='加工入库'} selected="selected" {/if}>加工入库</option>
    <option value='采购退库' {if $arr_condition.kind =='采购退库'} selected="selected" {/if}>采购退库</option>
    <option value='调货' {if $arr_condition.kind =='调货'} selected="selected" {/if}>调货</option>
</select>