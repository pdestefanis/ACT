<div class="unitsItems form">
<?php echo $this->Form->create('UnitsItem');?>
	<fieldset>
		<legend><?php __('Edit Units Item'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('unit_id');
		echo $this->Form->input('item_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('UnitsItem.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('UnitsItem.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Kits Items', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Kits', true), array('controller' => 'units', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Kit', true), array('controller' => 'units', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Items', true), array('controller' => 'items', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Item', true), array('controller' => 'items', 'action' => 'add')); ?> </li>
	</ul>
</div>
