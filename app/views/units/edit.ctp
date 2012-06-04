<div class="units form">
<?php echo $this->Form->create('Unit');?>
	<fieldset>
		<legend><?php __('Edit Kit'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('code');
		echo $this->Form->input('batch_id');
		echo $this->Form->input('item_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<?php 
	echo $access->checkHtml('Units/delete', 'html', '<h3>Actions</h3>','' ); ?>
	<ul>

		<li>
		<?php echo $access->checkHtml('Units/delete', 'delete', 'Delete','delete/' . $this->Form->value('Unit.id'), 'delete', $this->Form->value('Unit.code') ); ?></li>
		
	</ul>
</div>