<?php /* Smarty version 2.6.10, created on 2017-03-19 16:33:55
         compiled from Login.tpl */

$this->_cache_serials['Lib/App/../../_Cache/Smarty\%%E8^E8A^E8A2FF7B%%Login.tpl.inc'] = 'e7dc6ff67ba0353312fdb77d33876c48';

?><?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'webcontrol', 'Login.tpl', 14, false),array('function', 'url', 'Login.tpl', 32, false),array('modifier', 'default', 'Login.tpl', 20, false),array('modifier', 'date_format', 'Login.tpl', 60, false),)), $this); ?>
<?php
function _smarty_tplfunc_e7dc6ff67ba0353312fdb77d33876c48_0(&$_smarty)
{
echo $_smarty->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'GetAppInf','varName' => 'systemV'), $_smarty);
}


function _smarty_tplfunc_e7dc6ff67ba0353312fdb77d33876c48_1(&$_smarty)
{
echo $_smarty->_plugins['function']['url'][0][0]->_pi_func_url(array('controller' => $_GET['controller'],'action' => 'login'), $_smarty);
}



?>
