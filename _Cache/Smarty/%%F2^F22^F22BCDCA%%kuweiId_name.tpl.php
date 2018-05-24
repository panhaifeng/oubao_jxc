<?php /* Smarty version 2.6.10, created on 2018-05-08 13:49:15
         compiled from Search/kuweiId_name.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'webcontrol', 'Search/kuweiId_name.tpl', 2, false),array('modifier', 'cat', 'Search/kuweiId_name.tpl', 2, false),)), $this); ?>
<?php $this->_cache_serials['Lib/App/../../_Cache/Smarty\%%F2^F22^F22BCDCA%%kuweiId_name.tpl.inc'] = 'adcff7e1cb8aa970750fd0c1050b14bc'; ?><select name="kuweiId_name" id="kuweiId_name" class='select2'>
<?php if ($this->caching && !$this->_cache_including) { echo '{nocache:adcff7e1cb8aa970750fd0c1050b14bc#0}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'TmisOptions','model' => 'Jichu_Kuwei','selected' => $this->_tpl_vars['arr_condition']['kuweiId_name'],'emptyText' => '选择仓库','condition' => ((is_array($_tmp=((is_array($_tmp="ckName='")) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['arr_condition']['kuweiName']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['arr_condition']['kuweiName'])))) ? $this->_run_mod_handler('cat', true, $_tmp, "'") : smarty_modifier_cat($_tmp, "'"))), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:adcff7e1cb8aa970750fd0c1050b14bc#0}';}?>

</select>