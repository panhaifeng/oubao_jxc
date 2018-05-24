<?php /* Smarty version 2.6.10, created on 2018-04-09 16:19:35
         compiled from Main2Son/select.tpl */

$this->_cache_serials['Lib/App/../../_Cache/Smarty\%%CE^CEB^CEB10D65%%select.tpl.inc'] = '8714ab3fb23c43d2d8f4a3873e4e797e';

?><?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'Main2Son/select.tpl', 3, false),array('function', 'webcontrol', 'Main2Son/select.tpl', 5, false),)), $this); ?>
<?php
function _smarty_tplfunc_8714ab3fb23c43d2d8f4a3873e4e797e_0(&$_smarty)
{
echo $_smarty->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'BtSelect','model' => $_smarty->_tpl_vars['item']['model'],'condition' => $_smarty->_tpl_vars['item']['condition'],'options' => $_smarty->_tpl_vars['item']['options'],'value' => $_smarty->_tpl_vars['item']['value'],'itemName' => ((is_array($_tmp=@$_smarty->_tpl_vars['item']['name'])) ? $_smarty->_run_mod_handler('default', true, $_tmp, @$_smarty->_tpl_vars['key']) : smarty_modifier_default($_tmp, @$_smarty->_tpl_vars['key'])),'emptyText' => $_smarty->_tpl_vars['item']['emptyText'],'optionType' => $_smarty->_tpl_vars['item']['optionType'],'isSearch' => $_smarty->_tpl_vars['item']['isSearch']), $_smarty);
}



?>
