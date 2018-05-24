<?php /* Smarty version 2.6.10, created on 2018-04-26 15:04:10
         compiled from Popup/CommonNew.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'Popup/CommonNew.tpl', 3, false),)), $this); ?>
<base target="_self" />
<?php $this->assign('sonTpl', ((is_array($_tmp=@$this->_tpl_vars['sonTpl'])) ? $this->_run_mod_handler('default', true, $_tmp, 'Popup/_init.tpl') : smarty_modifier_default($_tmp, 'Popup/_init.tpl'))); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'TblList.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>