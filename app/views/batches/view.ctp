<div class="batches view">
<h2><?php  __('Batch');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $batch['Batch']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Expire Date'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $batch['Batch']['expire_date']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Batch Number'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $batch['Batch']['batch_number']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Batch', true), array('action' => 'edit', $batch['Batch']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Batch', true), array('action' => 'delete', $batch['Batch']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $batch['Batch']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Batches', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Batch', true), array('action' => 'add')); ?> </li>
	</ul>
</div>
