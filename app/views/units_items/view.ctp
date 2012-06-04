<div class="unitsItems view">
<h2><?php  __('Units Item');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $unitsItem['UnitsItem']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Unit'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($unitsItem['Unit']['id'], array('controller' => 'units', 'action' => 'view', $unitsItem['Unit']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Item'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($unitsItem['Item']['name'], array('controller' => 'items', 'action' => 'view', $unitsItem['Item']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Units Item', true), array('action' => 'edit', $unitsItem['UnitsItem']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Units Item', true), array('action' => 'delete', $unitsItem['UnitsItem']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $unitsItem['UnitsItem']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Units Items', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Units Item', true), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Units', true), array('controller' => 'units', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Unit', true), array('controller' => 'units', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Items', true), array('controller' => 'items', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Item', true), array('controller' => 'items', 'action' => 'add')); ?> </li>
	</ul>
</div>
