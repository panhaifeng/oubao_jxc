<?php /* Smarty version 2.6.10, created on 2018-04-09 16:19:35
         compiled from Main2Son/textarea.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'Main2Son/textarea.tpl', 3, false),array('function', 'webcontrol', 'Main2Son/textarea.tpl', 5, false),)), $this); ?>
<?php $this->_cache_serials['Lib/App/../../_Cache/Smarty\%%78^783^7832AFF8%%textarea.tpl.inc'] = '5020de49dc7f8fcbc2d58c167342fb7b'; ?><div class="col-xs-12">
		<div class="form-group">
    	<label for="<?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['name'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['key']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['key'])); ?>
" class="col-sm-1 control-label lableMain"><?php echo $this->_tpl_vars['item']['title']; ?>
:</label>
        <div class="col-sm-11">
          <?php if ($this->caching && !$this->_cache_including) { echo '{nocache:5020de49dc7f8fcbc2d58c167342fb7b#0}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'BtTextArea','itemName' => ((is_array($_tmp=@$this->_tpl_vars['item']['name'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['key']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['key'])),'value' => $this->_tpl_vars['item']['value'],'disabled' => $this->_tpl_vars['item']['disabled'],'readonly' => $this->_tpl_vars['item']['readonly']), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:5020de49dc7f8fcbc2d58c167342fb7b#0}';}?>

        </div>
    </div>
</div>