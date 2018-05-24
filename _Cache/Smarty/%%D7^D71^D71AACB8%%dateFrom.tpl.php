<?php /* Smarty version 2.6.10, created on 2018-04-09 16:46:11
         compiled from Search/dateFrom.tpl */ ?>
<?php if ($this->_tpl_vars['arr_condition']['dateTo'] !== null): ?>
<select name="dateSelect" id="dateSelect" onchange="changeDate(this)">
	<option value= -1>月份</option>
		<?php unset($this->_sections['loop']);
$this->_sections['loop']['loop'] = is_array($_loop=12) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['loop']['name'] = 'loop';
$this->_sections['loop']['show'] = true;
$this->_sections['loop']['max'] = $this->_sections['loop']['loop'];
$this->_sections['loop']['step'] = 1;
$this->_sections['loop']['start'] = $this->_sections['loop']['step'] > 0 ? 0 : $this->_sections['loop']['loop']-1;
if ($this->_sections['loop']['show']) {
    $this->_sections['loop']['total'] = $this->_sections['loop']['loop'];
    if ($this->_sections['loop']['total'] == 0)
        $this->_sections['loop']['show'] = false;
} else
    $this->_sections['loop']['total'] = 0;
if ($this->_sections['loop']['show']):

            for ($this->_sections['loop']['index'] = $this->_sections['loop']['start'], $this->_sections['loop']['iteration'] = 1;
                 $this->_sections['loop']['iteration'] <= $this->_sections['loop']['total'];
                 $this->_sections['loop']['index'] += $this->_sections['loop']['step'], $this->_sections['loop']['iteration']++):
$this->_sections['loop']['rownum'] = $this->_sections['loop']['iteration'];
$this->_sections['loop']['index_prev'] = $this->_sections['loop']['index'] - $this->_sections['loop']['step'];
$this->_sections['loop']['index_next'] = $this->_sections['loop']['index'] + $this->_sections['loop']['step'];
$this->_sections['loop']['first']      = ($this->_sections['loop']['iteration'] == 1);
$this->_sections['loop']['last']       = ($this->_sections['loop']['iteration'] == $this->_sections['loop']['total']);
?>
			<option value=<?php echo $this->_sections['loop']['index']; ?>
><?php echo $this->_sections['loop']['index']+1; ?>
月</option>
		<?php endfor; endif; ?>
	<option value=13>全部</option>
</select>
<?php endif; ?>
<input name="dateFrom" type="text" id="dateFrom" value="<?php echo $this->_tpl_vars['arr_condition']['dateFrom']; ?>
" size="10" onclick="calendar()" emptyText='选择日期' placeholder='选择日期' /><?php if ($this->_tpl_vars['arr_condition']['dateTo'] !== null): ?>-<input name="dateTo" size="10" type="text" id="dateTo" value="<?php echo $this->_tpl_vars['arr_condition']['dateTo']; ?>
"  onclick="calendar()" emptyText='选择日期' placeholder='选择日期'/><?php endif; ?>