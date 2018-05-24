<?php /* Smarty version 2.6.10, created on 2018-04-09 16:19:35
         compiled from Main2Son/calendarTime.tpl */

$this->_cache_serials['Lib/App/../../_Cache/Smarty\%%DD^DDA^DDAF3987%%calendarTime.tpl.inc'] = 'e7bc8678f82f6b8e9cd734298e62892b';

?><?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'Main2Son/calendarTime.tpl', 3, false),array('function', 'webcontrol', 'Main2Son/calendarTime.tpl', 5, false),)), $this); ?>
<?php
function _smarty_tplfunc_e7bc8678f82f6b8e9cd734298e62892b_0(&$_smarty)
{
echo $_smarty->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'BtCalendarTime','itemName' => ((is_array($_tmp=@$_smarty->_tpl_vars['item']['name'])) ? $_smarty->_run_mod_handler('default', true, $_tmp, @$_smarty->_tpl_vars['key']) : smarty_modifier_default($_tmp, @$_smarty->_tpl_vars['key'])),'value' => $_smarty->_tpl_vars['item']['value'],'disabled' => $_smarty->_tpl_vars['item']['disabled'],'readonly' => $_smarty->_tpl_vars['item']['readonly']), $_smarty);
}



?>
