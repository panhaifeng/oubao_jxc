<?php /* Smarty version 2.6.10, created on 2018-04-26 15:05:16
         compiled from Search/supplierId.tpl */

$this->_cache_serials['Lib/App/../../_Cache/Smarty\%%48^48B^48B2CE66%%supplierId.tpl.inc'] = 'f8673127f5f727831b0cb22d3cf590db';

?><?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'webcontrol', 'Search/supplierId.tpl', 2, false),)), $this); ?>
<?php
function _smarty_tplfunc_f8673127f5f727831b0cb22d3cf590db_0(&$_smarty)
{
echo $_smarty->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'TmisOptions','model' => 'Jichu_Supplier','selected' => $_smarty->_tpl_vars['arr_condition']['supplierId'],'emptyText' => '选择供应商','condition' => 'isJiagong=0'), $_smarty);
}



?>
