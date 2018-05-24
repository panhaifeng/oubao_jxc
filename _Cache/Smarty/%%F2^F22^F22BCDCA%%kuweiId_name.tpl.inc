<?php /* Smarty version 2.6.10, created on 2018-05-08 13:49:15
         compiled from Search/kuweiId_name.tpl */

$this->_cache_serials['Lib/App/../../_Cache/Smarty\%%F2^F22^F22BCDCA%%kuweiId_name.tpl.inc'] = 'adcff7e1cb8aa970750fd0c1050b14bc';

?><?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'webcontrol', 'Search/kuweiId_name.tpl', 2, false),array('modifier', 'cat', 'Search/kuweiId_name.tpl', 2, false),)), $this); ?>
<?php
function _smarty_tplfunc_adcff7e1cb8aa970750fd0c1050b14bc_0(&$_smarty)
{
echo $_smarty->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'TmisOptions','model' => 'Jichu_Kuwei','selected' => $_smarty->_tpl_vars['arr_condition']['kuweiId_name'],'emptyText' => '选择仓库','condition' => ((is_array($_tmp=((is_array($_tmp="ckName='")) ? $_smarty->_run_mod_handler('cat', true, $_tmp, $_smarty->_tpl_vars['arr_condition']['kuweiName']) : smarty_modifier_cat($_tmp, $_smarty->_tpl_vars['arr_condition']['kuweiName'])))) ? $_smarty->_run_mod_handler('cat', true, $_tmp, "'") : smarty_modifier_cat($_tmp, "'"))), $_smarty);
}



?>
