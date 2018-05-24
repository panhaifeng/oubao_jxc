<?php /* Smarty version 2.6.10, created on 2018-04-09 17:03:48
         compiled from Search/is_delivery.tpl */ ?>
<select name="is_delivery" id="is_delivery">
    <option value=''>是否需要发货</option>
    <option value='Y'  selected="selected" >需要发货</option>
    <option value='N' <?php if ($this->_tpl_vars['arr_condition']['is_delivery'] == 'N'): ?> selected="selected" <?php endif; ?>>不需要发货</option>
</select>