<div class="batches form">
<?php echo $this->Form->create('Batch');?>
	<fieldset>
		<legend><?php __('Add Batch'); ?></legend>
	<?php
		//echo $this->Form->input('expire_date');
		echo $this->Form->input('batch_number');
		echo $this->Form->input('expire_date', array(
							        'label' => __('Expiry date', true),
							        'type' => 'date',
							        'dateFormat' => 'YDM',
							        'minYear' => date('Y') - 1,
							        'maxYear' => date('Y') +1,
    								)
    						);
		
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Batches', true), array('action' => 'index'));?></li>
	</ul>
</div>