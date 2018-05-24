<select name="isHetong" id="isHetong">
    <option value='' {if $arr_condition.isHetong == ''} selected="selected" {/if}>是否生成合同</option>
    <option value='yes' {if $arr_condition.isHetong == 'yes'} selected="selected" {/if}>已生成合同</option>
    <option value='no' {if $arr_condition.isHetong == 'no'} selected="selected" {/if}>未生成合同</option>
</select>