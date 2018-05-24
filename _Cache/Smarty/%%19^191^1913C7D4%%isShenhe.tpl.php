<?php /* Smarty version 2.6.10, created on 2018-04-09 16:46:14
         compiled from Search/isShenhe.tpl */ ?>
<select name="isShenhe" id="isShenhe">
    <option value='all' <?php if ($this->_tpl_vars['arr_condition']['isShenhe'] == 'all'): ?> selected="selected" <?php endif; ?>>全部</option>
    <option value='' <?php if ($this->_tpl_vars['arr_condition']['isShenhe'] == ''): ?> selected="selected" <?php endif; ?>>未完成</option>
    <option value='yes' <?php if ($this->_tpl_vars['arr_condition']['isShenhe'] == 'yes'): ?> selected="selected" <?php endif; ?>>通过</option>
    <option value='no' <?php if ($this->_tpl_vars['arr_condition']['isShenhe'] == 'no'): ?> selected="selected" <?php endif; ?>>拒绝</option>
</select>