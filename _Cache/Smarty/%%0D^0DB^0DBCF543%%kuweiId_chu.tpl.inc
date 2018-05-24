<?php /* Smarty version 2.6.10, created on 2018-05-08 13:49:05
         compiled from Search/kuweiId_chu.tpl */

$this->_cache_serials['Lib/App/../../_Cache/Smarty\%%0D^0DB^0DBCF543%%kuweiId_chu.tpl.inc'] = '86e15c7ad050f2776ee83b820e5d2712';

?><?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'webcontrol', 'Search/kuweiId_chu.tpl', 2, false),array('modifier', 'cat', 'Search/kuweiId_chu.tpl', 2, false),)), $this); ?>
<?php
function _smarty_tplfunc_86e15c7ad050f2776ee83b820e5d2712_0(&$_smarty)
{
echo $_smarty->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'TmisOptions','model' => 'Jichu_Kuwei','selected' => $_smarty->_tpl_vars['arr_condition']['kuweiId_chu'],'emptyText' => '调出仓库','condition' => ((is_array($_tmp=((is_array($_tmp="ckName='")) ? $_smarty->_run_mod_handler('cat', true, $_tmp, $_smarty->_tpl_vars['arr_condition']['kuweiName']) : smarty_modifier_cat($_tmp, $_smarty->_tpl_vars['arr_condition']['kuweiName'])))) ? $_smarty->_run_mod_handler('cat', true, $_tmp, "'") : smarty_modifier_cat($_tmp, "'"))), $_smarty);
}



?>
