<?php /* Smarty version 2.6.10, created on 2018-04-09 16:46:11
         compiled from Search/status_active.tpl */ ?>
<select name="status_active" id="status_active">
    <option value=''>选择状态</option>
    <option value='未下单' <?php if ($this->_tpl_vars['arr_condition']['status_active'] == '未下单'): ?> selected="selected" <?php endif; ?>>未下单</option>
    <option value='已下单' <?php if ($this->_tpl_vars['arr_condition']['status_active'] == '已下单'): ?> selected="selected" <?php endif; ?>>已下单</option>
</select>