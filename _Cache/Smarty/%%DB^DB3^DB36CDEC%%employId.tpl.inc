<?php /* Smarty version 2.6.10, created on 2018-04-09 17:03:48
         compiled from Search/employId.tpl */

$this->_cache_serials['Lib/App/../../_Cache/Smarty\%%DB^DB3^DB36CDEC%%employId.tpl.inc'] = '83d2eaea8c41b7a0192b64ccfe51655c';

?><?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'webcontrol', 'Search/employId.tpl', 3, false),)), $this); ?>
<?php
function _smarty_tplfunc_83d2eaea8c41b7a0192b64ccfe51655c_0(&$_smarty)
{
echo $_smarty->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'TmisOptions','model' => 'Jichu_Employ','selected' => $_smarty->_tpl_vars['arr_condition']['employId'],'emptyText' => '业务员'), $_smarty);
}



?>
