<?php /* Smarty version 2.6.10, created on 2018-05-16 15:15:14
         compiled from Main2Son/calendar.tpl */

$this->_cache_serials['Lib/App/../../_Cache/Smarty\%%44^446^446ABBB5%%calendar.tpl.inc'] = '3b2925defa9d785432efc0de12a9d9ca';

?><?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'Main2Son/calendar.tpl', 3, false),array('function', 'webcontrol', 'Main2Son/calendar.tpl', 5, false),)), $this); ?>
<?php
function _smarty_tplfunc_3b2925defa9d785432efc0de12a9d9ca_0(&$_smarty)
{
echo $_smarty->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'BtCalendar','itemName' => ((is_array($_tmp=@$_smarty->_tpl_vars['item']['name'])) ? $_smarty->_run_mod_handler('default', true, $_tmp, @$_smarty->_tpl_vars['key']) : smarty_modifier_default($_tmp, @$_smarty->_tpl_vars['key'])),'value' => $_smarty->_tpl_vars['item']['value'],'disabled' => $_smarty->_tpl_vars['item']['disabled'],'readonly' => $_smarty->_tpl_vars['item']['readonly']), $_smarty);
}



?>
