<div class="unitsBatches view">
<h2><?php  __('Units Batch');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $unitsBatch['UnitsBatch']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Unit'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($unitsBatch['Unit']['id'], array('controller' => 'units', 'action' => 'view', $unitsBatch['Unit']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Batch'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($unitsBatch['Batch']['id'], array('controller' => 'batches', 'action' => 'view', $unitsBatch['Batch']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Units Batch', true), array('action' => 'edit', $unitsBatch['UnitsBatch']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Units Batch', true), array('action' => 'delete', $unitsBatch['UnitsBatch']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $unitsBatch['UnitsBatch']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Units Batches', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Units Batch', true), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Units', true), array('controller' => 'units', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Unit', true), array('controller' => 'units', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Batches', true), array('controller' => 'batches', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Batch', true), array('controller' => 'batches', 'action' => 'add')); ?> </li>
	</ul>
</div>
