<?php /* Smarty version 2.6.10, created on 2018-04-09 16:46:10
         compiled from _Search.tpl */

$this->_cache_serials['Lib/App/../../_Cache/Smarty\%%FC^FCA^FCAC0DF5%%_Search.tpl.inc'] = 'ff5454f4f365de097a3f3630b5d30bc3';

?><?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'cat', '_Search.tpl', 12, false),array('modifier', 'default', '_Search.tpl', 29, false),array('function', 'url', '_Search.tpl', 27, false),)), $this); ?>
<?php
function _smarty_tplfunc_ff5454f4f365de097a3f3630b5d30bc3_0(&$_smarty)
{
echo $_smarty->_plugins['function']['url'][0][0]->_pi_func_url(array('controller' => $_GET['controller'],'action' => 'add'), $_smarty);
}


function _smarty_tplfunc_ff5454f4f365de097a3f3630b5d30bc3_1(&$_smarty)
{
echo $_smarty->_plugins['function']['url'][0][0]->_pi_func_url(array('controller' => $_GET['controller'],'action' => 'list'), $_smarty);
}



?>
