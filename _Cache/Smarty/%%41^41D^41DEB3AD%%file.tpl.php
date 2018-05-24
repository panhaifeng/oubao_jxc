<?php /* Smarty version 2.6.10, created on 2018-05-16 15:15:14
         compiled from Main2Son/file.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'Main2Son/file.tpl', 3, false),array('function', 'webcontrol', 'Main2Son/file.tpl', 5, false),)), $this); ?>
<?php $this->_cache_serials['Lib/App/../../_Cache/Smarty\%%41^41D^41DEB3AD%%file.tpl.inc'] = '02cb01d7820a5f071efed465479b6505'; ?><div class="col-xs-4">
    <div class="form-group">
        <label for="<?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['name'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['key']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['key'])); ?>
" class="col-sm-3 control-label lableMain lableMain"><?php echo $this->_tpl_vars['item']['title']; ?>
:</label>
        <div class="col-sm-9">
        	<?php if ($this->caching && !$this->_cache_including) { echo '{nocache:02cb01d7820a5f071efed465479b6505#0}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'BtFile','itemName' => ((is_array($_tmp=@$this->_tpl_vars['item']['name'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['key']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['key'])),'disabled' => $this->_tpl_vars['item']['disabled'],'readonly' => $this->_tpl_vars['item']['readonly'],'addonPre' => $this->_tpl_vars['item']['addonPre'],'addonEnd' => $this->_tpl_vars['item']['addonEnd']), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:02cb01d7820a5f071efed465479b6505#0}';}?>

        </div>
    </div>
</div>