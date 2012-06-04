<div class="unitsBatches form">
<?php echo $this->Form->create('UnitsBatch');?>
	<fieldset>
		<legend><?php __('Add Units Batch'); ?></legend>
	<?php
		echo $this->Form->input('unit_id');
		echo $this->Form->input('batch_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Units Batches', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Units', true), array('controller' => 'units', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Unit', true), array('controller' => 'units', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Batches', true), array('controller' => 'batches', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Batch', true), array('controller' => 'batches', 'action' => 'add')); ?> </li>
	</ul>
</div>