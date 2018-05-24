<?php /* Smarty version 2.6.10, created on 2018-04-26 15:05:16
         compiled from Search/caiEmpId.tpl */

$this->_cache_serials['Lib/App/../../_Cache/Smarty\%%1C^1C4^1C4526BF%%caiEmpId.tpl.inc'] = 'c382dfce25683c2e66c1e4e0675e611c';

?><?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'webcontrol', 'Search/caiEmpId.tpl', 2, false),)), $this); ?>
<?php
function _smarty_tplfunc_c382dfce25683c2e66c1e4e0675e611c_0(&$_smarty)
{
echo $_smarty->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'TmisOptions','model' => 'Jichu_Employ','selected' => $_smarty->_tpl_vars['arr_condition']['caiEmpId'],'emptyText' => '采购人'), $_smarty);
}



?>
