<select name="order_kind" id="order_kind">
    <option value=''>全部类型</option>
    <option value='大货' {if $arr_condition.order_kind =='大货'} selected="selected" {/if}>大货</option>
    <option value='现货' {if $arr_condition.order_kind =='现货'} selected="selected" {/if}>现货</option>
    <option value='样品' {if $arr_condition.order_kind =='样品'} selected="selected" {/if}>样品</option>
</select>