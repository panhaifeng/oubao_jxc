<?php /* Smarty version 2.6.10, created on 2018-05-16 15:15:14
         compiled from Main2Son/file.tpl */

$this->_cache_serials['Lib/App/../../_Cache/Smarty\%%41^41D^41DEB3AD%%file.tpl.inc'] = '02cb01d7820a5f071efed465479b6505';

?><?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'Main2Son/file.tpl', 3, false),array('function', 'webcontrol', 'Main2Son/file.tpl', 5, false),)), $this); ?>
<?php
function _smarty_tplfunc_02cb01d7820a5f071efed465479b6505_0(&$_smarty)
{
echo $_smarty->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'BtFile','itemName' => ((is_array($_tmp=@$_smarty->_tpl_vars['item']['name'])) ? $_smarty->_run_mod_handler('default', true, $_tmp, @$_smarty->_tpl_vars['key']) : smarty_modifier_default($_tmp, @$_smarty->_tpl_vars['key'])),'disabled' => $_smarty->_tpl_vars['item']['disabled'],'readonly' => $_smarty->_tpl_vars['item']['readonly'],'addonPre' => $_smarty->_tpl_vars['item']['addonPre'],'addonEnd' => $_smarty->_tpl_vars['item']['addonEnd']), $_smarty);
}



?>
