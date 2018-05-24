<?php /* Smarty version 2.6.10, created on 2018-04-09 16:46:11
         compiled from Search/clientId.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'webcontrol', 'Search/clientId.tpl', 2, false),)), $this); ?>
<?php $this->_cache_serials['Lib/App/../../_Cache/Smarty\%%2F^2FE^2FE0CAEB%%clientId.tpl.inc'] = '013d991318bb5e1d5af831ca8cabdde2'; ?><select name="clientId" id="clientId" class="select2">
<?php if ($this->caching && !$this->_cache_including) { echo '{nocache:013d991318bb5e1d5af831ca8cabdde2#0}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'TmisOptions','model' => 'Jichu_Client','selected' => $this->_tpl_vars['arr_condition']['clientId'],'emptyText' => '客户选择','valueKey' => 'member_id'), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:013d991318bb5e1d5af831ca8cabdde2#0}';}?>

</select>