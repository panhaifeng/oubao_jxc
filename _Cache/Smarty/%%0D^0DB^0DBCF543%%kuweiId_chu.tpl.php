<?php /* Smarty version 2.6.10, created on 2018-05-08 13:49:05
         compiled from Search/kuweiId_chu.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'webcontrol', 'Search/kuweiId_chu.tpl', 2, false),array('modifier', 'cat', 'Search/kuweiId_chu.tpl', 2, false),)), $this); ?>
<?php $this->_cache_serials['Lib/App/../../_Cache/Smarty\%%0D^0DB^0DBCF543%%kuweiId_chu.tpl.inc'] = '86e15c7ad050f2776ee83b820e5d2712'; ?><select name="kuweiId_chu" id="kuweiId_chu" class='select2'>
<?php if ($this->caching && !$this->_cache_including) { echo '{nocache:86e15c7ad050f2776ee83b820e5d2712#0}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'TmisOptions','model' => 'Jichu_Kuwei','selected' => $this->_tpl_vars['arr_condition']['kuweiId_chu'],'emptyText' => '调出仓库','condition' => ((is_array($_tmp=((is_array($_tmp="ckName='")) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['arr_condition']['kuweiName']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['arr_condition']['kuweiName'])))) ? $this->_run_mod_handler('cat', true, $_tmp, "'") : smarty_modifier_cat($_tmp, "'"))), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:86e15c7ad050f2776ee83b820e5d2712#0}';}?>

</select>