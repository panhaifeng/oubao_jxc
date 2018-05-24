<?php /* Smarty version 2.6.10, created on 2017-03-19 16:34:06
         compiled from Welcome.tpl */

$this->_cache_serials['Lib/App/../../_Cache/Smarty\%%09^099^0997F726%%Welcome.tpl.inc'] = '207ed95c8db80e335fab6f73e9709d16';

?><?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'webcontrol', 'Welcome.tpl', 3, false),array('modifier', 'date_format', 'Welcome.tpl', 159, false),)), $this); ?>
<?php
function _smarty_tplfunc_207ed95c8db80e335fab6f73e9709d16_0(&$_smarty)
{
echo $_smarty->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'GetAppInf','varName' => 'systemName'), $_smarty);
}


function _smarty_tplfunc_207ed95c8db80e335fab6f73e9709d16_1(&$_smarty)
{
echo $_smarty->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'LoadJsCss','src' => "Resource/Script/jquery.1.9.1.js"), $_smarty);
}


function _smarty_tplfunc_207ed95c8db80e335fab6f73e9709d16_2(&$_smarty)
{
echo $_smarty->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'LoadJsCss','src' => "Resource/Script/layer/layer.js"), $_smarty);
}


function _smarty_tplfunc_207ed95c8db80e335fab6f73e9709d16_3(&$_smarty)
{
echo $_smarty->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'LoadJsCss','src' => "Resource/Script/chart/esl.js"), $_smarty);
}


function _smarty_tplfunc_207ed95c8db80e335fab6f73e9709d16_4(&$_smarty)
{
echo $_smarty->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'LoadJsCss','src' => "Resource/Script/Calendar/WdatePicker.js"), $_smarty);
}



?>
