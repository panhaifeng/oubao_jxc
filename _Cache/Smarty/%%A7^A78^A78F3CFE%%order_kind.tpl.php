<?php /* Smarty version 2.6.10, created on 2018-04-09 17:03:48
         compiled from Search/order_kind.tpl */ ?>
<select name="order_kind" id="order_kind">
    <option value=''>全部类型</option>
    <option value='大货' <?php if ($this->_tpl_vars['arr_condition']['order_kind'] == '大货'): ?> selected="selected" <?php endif; ?>>大货</option>
    <option value='现货' <?php if ($this->_tpl_vars['arr_condition']['order_kind'] == '现货'): ?> selected="selected" <?php endif; ?>>现货</option>
    <option value='样品' <?php if ($this->_tpl_vars['arr_condition']['order_kind'] == '样品'): ?> selected="selected" <?php endif; ?>>样品</option>
</select>