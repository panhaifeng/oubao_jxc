<?php /* Smarty version 2.6.10, created on 2018-04-26 15:05:16
         compiled from Search/isRkOver.tpl */ ?>
<select name="isRkOver" id="isRkOver">
    <option value='2' <?php if ($this->_tpl_vars['arr_condition']['isRkOver'] == '2'): ?> selected="selected" <?php endif; ?>>是否完成</option>
    <option value='1' <?php if ($this->_tpl_vars['arr_condition']['isRkOver'] == '1'): ?> selected="selected" <?php endif; ?>>已完成</option>
    <option value='0' <?php if ($this->_tpl_vars['arr_condition']['isRkOver'] == '0'): ?> selected="selected" <?php endif; ?>>未完成</option>
</select>