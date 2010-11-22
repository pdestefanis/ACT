<?php
echo $crumb->getHtml('Add Raw Report', null, 'auto' ) ;
echo '<br /><br />' ;

?> 
<div class="rawreports form">
<?php echo $this->Form->create('Rawreport');?>
	<fieldset>
 		<legend><?php __('Add Rawreport'); ?></legend>
	<?php
		echo $this->Form->input('raw_message');
		echo $this->Form->input('message_code');
		echo $this->Form->input('phone_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
