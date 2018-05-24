<?php /* Smarty version 2.6.10, created on 2018-04-26 15:05:16
         compiled from Search/caiEmpId.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'webcontrol', 'Search/caiEmpId.tpl', 2, false),)), $this); ?>
<?php $this->_cache_serials['Lib/App/../../_Cache/Smarty\%%1C^1C4^1C4526BF%%caiEmpId.tpl.inc'] = 'c382dfce25683c2e66c1e4e0675e611c'; ?><select name='caiEmpId' class='select2'>       
    <?php if ($this->caching && !$this->_cache_including) { echo '{nocache:c382dfce25683c2e66c1e4e0675e611c#0}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'TmisOptions','model' => 'Jichu_Employ','selected' => $this->_tpl_vars['arr_condition']['caiEmpId'],'emptyText' => '采购人'), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:c382dfce25683c2e66c1e4e0675e611c#0}';}?>
<!-- 去掉离职的判断，2015-10-15，by liuxin -->
    </select>