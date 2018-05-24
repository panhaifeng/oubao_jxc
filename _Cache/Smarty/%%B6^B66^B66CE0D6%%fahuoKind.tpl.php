<?php /* Smarty version 2.6.10, created on 2018-04-19 15:15:16
         compiled from Search/fahuoKind.tpl */ ?>
<select name="fahuoKind" id="fahuoKind">
	<option value='' <?php if ($this->_tpl_vars['arr_condition']['fahuoKind'] == ''): ?> selected="selected" <?php endif; ?>>过账类型</option>
	<option value='销售出库' <?php if ($this->_tpl_vars['arr_condition']['fahuoKind'] == '销售出库'): ?> selected="selected" <?php endif; ?>>销售出库</option>
	<option value='运费过账' <?php if ($this->_tpl_vars['arr_condition']['fahuoKind'] == '运费过账'): ?> selected="selected" <?php endif; ?>>运费过账</option>
    <option value='退换入库' <?php if ($this->_tpl_vars['arr_condition']['fahuoKind'] == '退换入库'): ?> selected="selected" <?php endif; ?>>退换入库</option>
    <option value='订单减免' <?php if ($this->_tpl_vars['arr_condition']['fahuoKind'] == '订单减免'): ?> selected="selected" <?php endif; ?>>订单减免</option>
    <option value='订单优惠' <?php if ($this->_tpl_vars['arr_condition']['fahuoKind'] == '订单优惠'): ?> selected="selected" <?php endif; ?>>订单优惠</option>
</select>