<div class="batches form">
<?php echo $this->Form->create('Batch');?>
	<fieldset>
		<legend><?php __('Edit Batch'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('expire_date');
		echo $this->Form->input('batch_number');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Batch.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Batch.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Batches', true), array('action' => 'index'));?></li>
	</ul>
</div>