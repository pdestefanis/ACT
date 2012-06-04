<div class="unitsBatches form">
<?php echo $this->Form->create('UnitsBatch');?>
	<fieldset>
		<legend><?php __('Edit Units Batch'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('unit_id');
		echo $this->Form->input('batch_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('UnitsBatch.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('UnitsBatch.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Kits Batches', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Kits', true), array('controller' => 'units', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Kit', true), array('controller' => 'units', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Batches', true), array('controller' => 'batches', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Batch', true), array('controller' => 'batches', 'action' => 'add')); ?> </li>
	</ul>
</div>
