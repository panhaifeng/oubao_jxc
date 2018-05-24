<select name="status_active" id="status_active">
    <option value=''>选择状态</option>
    <option value='未下单' {if $arr_condition.status_active == '未下单'} selected="selected" {/if}>未下单</option>
    <option value='已下单' {if $arr_condition.status_active == '已下单'} selected="selected" {/if}>已下单</option>
</select>