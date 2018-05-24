<?php /* Smarty version 2.6.10, created on 2018-04-09 17:03:48
         compiled from Search/employId.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'webcontrol', 'Search/employId.tpl', 3, false),)), $this); ?>
<?php $this->_cache_serials['Lib/App/../../_Cache/Smarty\%%DB^DB3^DB36CDEC%%employId.tpl.inc'] = '83d2eaea8c41b7a0192b64ccfe51655c'; ?><select name='employId' class='select2'>
<!-- 去掉离职的判断，2015-10-15，by liuxin -->		
	<?php if ($this->caching && !$this->_cache_including) { echo '{nocache:83d2eaea8c41b7a0192b64ccfe51655c#0}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'TmisOptions','model' => 'Jichu_Employ','selected' => $this->_tpl_vars['arr_condition']['employId'],'emptyText' => '业务员'), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:83d2eaea8c41b7a0192b64ccfe51655c#0}';}?>

	</select>