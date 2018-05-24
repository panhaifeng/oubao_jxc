<?php /* Smarty version 2.6.10, created on 2018-04-09 16:19:35
         compiled from Main2Son/select.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'Main2Son/select.tpl', 3, false),array('function', 'webcontrol', 'Main2Son/select.tpl', 5, false),)), $this); ?>
<?php $this->_cache_serials['Lib/App/../../_Cache/Smarty\%%CE^CEB^CEB10D65%%select.tpl.inc'] = '8714ab3fb23c43d2d8f4a3873e4e797e'; ?><div class="col-xs-4">
  <div class="form-group">
    <label for="<?php echo ((is_array($_tmp=@$this->_tpl_vars['item']['name'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['key']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['key'])); ?>
" class="col-sm-3 control-label lableMain"><?php echo $this->_tpl_vars['item']['title']; ?>
:</label>
    <!--增加了登记界面选择框的isSearch，2015-09-28，by liuxin-->
    <div class="col-sm-9"><?php if ($this->caching && !$this->_cache_including) { echo '{nocache:8714ab3fb23c43d2d8f4a3873e4e797e#0}';}echo $this->_plugins['function']['webcontrol'][0][0]->_pi_func_webcontrol(array('type' => 'BtSelect','model' => $this->_tpl_vars['item']['model'],'condition' => $this->_tpl_vars['item']['condition'],'options' => $this->_tpl_vars['item']['options'],'value' => $this->_tpl_vars['item']['value'],'itemName' => ((is_array($_tmp=@$this->_tpl_vars['item']['name'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['key']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['key'])),'emptyText' => $this->_tpl_vars['item']['emptyText'],'optionType' => $this->_tpl_vars['item']['optionType'],'isSearch' => $this->_tpl_vars['item']['isSearch']), $this);if ($this->caching && !$this->_cache_including) { echo '{/nocache:8714ab3fb23c43d2d8f4a3873e4e797e#0}';}?>
      
    </div>
  </div>
</div>