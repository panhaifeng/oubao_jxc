<?php /* Smarty version 2.6.10, created on 2018-04-09 16:46:11
         compiled from TblListView.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'TblListView.tpl', 3, false),array('modifier', 'explode', 'TblListView.tpl', 9, false),array('modifier', 'default', 'TblListView.tpl', 15, false),)), $this); ?>
<?php $_from = $this->_tpl_vars['arr_field_value']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['field_value']):
?>
		<div id='divRow' name='divRow' style='background-color:<?php echo smarty_function_cycle(array('values' => "#ffffff,#f9f9f9"), $this);?>
'>
			<table border="0" cellpadding="0" cellspacing="0" id="tblRow" name="tblRow">
				<?php if ($this->_tpl_vars['field_value']['display'] != 'false'): ?>					  <tr class='trRow' <?php if ($this->_tpl_vars['field_value']['_bgColor'] != ''): ?> style="background-color:<?php echo $this->_tpl_vars['field_value']['_bgColor']; ?>
" <?php endif; ?>>
					<?php $_from = $this->_tpl_vars['arr_field_info']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
						<?php if ($this->_tpl_vars['key'] != '_edit' || $_GET['no_edit'] != 1): ?>
						  <?php $this->assign('foo', ((is_array($_tmp=".")) ? $this->_run_mod_handler('explode', true, $_tmp, $this->_tpl_vars['key']) : explode($_tmp, $this->_tpl_vars['key']))); ?>
						  <?php $this->assign('key1', $this->_tpl_vars['foo'][0]); ?>
						  <?php $this->assign('key2', $this->_tpl_vars['foo'][1]); ?>
						  <?php $this->assign('key3', $this->_tpl_vars['foo'][2]); ?>
					<td <?php if ($this->_tpl_vars['item']['_color'] != ''): ?> style="color:<?php echo $this->_tpl_vars['item']['_color']; ?>
" <?php endif; ?>><div class='valueTdDiv'>
						  <?php if ($this->_tpl_vars['key2'] == ''): ?>
							<?php echo ((is_array($_tmp=@$this->_tpl_vars['field_value'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('default', true, $_tmp, '&nbsp;') : smarty_modifier_default($_tmp, '&nbsp;')); ?>

						  <?php elseif ($this->_tpl_vars['key3'] == ''): ?>
							<?php echo ((is_array($_tmp=@$this->_tpl_vars['field_value'][$this->_tpl_vars['key1']][$this->_tpl_vars['key2']])) ? $this->_run_mod_handler('default', true, $_tmp, '&nbsp;') : smarty_modifier_default($_tmp, '&nbsp;')); ?>

						  <?php else: ?>
							<?php echo ((is_array($_tmp=@$this->_tpl_vars['field_value'][$this->_tpl_vars['key1']][$this->_tpl_vars['key2']][$this->_tpl_vars['key3']])) ? $this->_run_mod_handler('default', true, $_tmp, '&nbsp;') : smarty_modifier_default($_tmp, '&nbsp;')); ?>

						  <?php endif; ?>
					</div></td>
						<?php endif; ?>
					<?php endforeach; endif; unset($_from); ?>
				  </tr>
				  <?php endif; ?>
			  </table></div>
		<?php endforeach; endif; unset($_from); ?>