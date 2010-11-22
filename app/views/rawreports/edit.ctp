<?php
echo $crumb->getHtml('Edit Raw Report', null, 'auto' ) ;
echo '<br /><br />' ;

?> 

<div class="rawreports form">
<?php echo $this->Form->create('Rawreport');?>
	<fieldset>
 		<legend><?php __('Edit Rawreport'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('raw_message');
		echo $this->Form->input('message_code');
		echo $this->Form->input('phone_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Rawreport.id')), null, sprintf(__('Are you sure you want to delete %s?', true), $this->Form->value('Rawreport.raw_message'))); ?></li>
		
	</ul>
</div>
