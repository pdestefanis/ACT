<?php
echo $crumb->getHtml('System Management',  null, 'auto' ) ;
echo '<br /><br />' ;
?>
<div class="phones form">
<?php echo $this->Form->create('Stat', array('action' => 'options'));?>
	<fieldset>
 		<legend><?php __('Options'); ?></legend>
	<?php
		echo $this->Form->input('ndigits', array('label' => 'Last n digits of phone number to consider', 
				'value' =>  $this->Form->value('ndigits') ));
		echo $this->Form->hidden('ndigitsOld', array('value' =>  $this->Form->value('ndigitsOld') ));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	
</div>