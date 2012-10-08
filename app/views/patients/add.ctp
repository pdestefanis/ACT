<?php echo $javascript->link('prototype', false); ?>
<?php
echo $crumb->getHtml('Add Patient', null, 'auto' ) ;
echo '<br /><br />' ;
?> 
<div class="patients form">
<?php echo $this->Form->create('Patient');?>
	<fieldset>
 		<legend><?php __('Add Patient'); ?></legend>
	<?php
		echo $this->Form->input('number', array('after' => '<p class="help">' . __('Should start with a P or 7 and be followed by 5 or 6 digits.', true) . '</p>'));
		echo $this->Form->radio('consent', array('1' => 'Yes', '0' => 'No'), null, array('value' => '0'));
		echo $this->Form->input('location_id', array('label' => 'Registered at'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
	</ul>
</div>