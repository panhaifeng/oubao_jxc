<select name="isBaofei" id="isBaofei">
    <option value='' {if $arr_condition.isBaofei == ''} selected="selected" {/if}>是否报废</option>
    <option value='0' {if $arr_condition.isBaofei == '0'} selected="selected" {/if}>是</option>
    <option value='1' {if $arr_condition.isBaofei == '1'} selected="selected" {/if}>否</option>
</select>