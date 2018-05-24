<?php /* Smarty version 2.6.10, created on 2018-05-08 13:49:05
         compiled from Search/kuweiId_ru.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'webcontrol', 'Search/kuweiId_ru.tpl', 2, false),array('modifier', 'cat', 'Search/kuweiId_ru.tpl', 2, false),)), $this); ?>
<?php $this->_cache_serials['Lib/App/../../_Cache/Smarty\%%83^834^83453543%%kuweiId_ru.tpl.inc'] = '5d59b46065e4163404647cbc60e44eba'; ?><select name="kuweiId_ru" id="kuweiId_ru" class='select2'>
<?php if ($this->caching && !$this->_cache_including) { echo '{nocache:5d59b46065e4163404647cbc60e44eba#0}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'TmisOptions','model' => 'Jichu_Kuwei','selected' => $this->_tpl_vars['arr_condition']['kuweiId_ru'],'emptyText' => '调入仓库','condition' => ((is_array($_tmp=((is_array($_tmp="ckName!='")) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['arr_condition']['kuweiName']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['arr_condition']['kuweiName'])))) ? $this->_run_mod_handler('cat', true, $_tmp, "'") : smarty_modifier_cat($_tmp, "'"))), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:5d59b46065e4163404647cbc60e44eba#0}';}?>

</select>