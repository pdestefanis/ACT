<?php echo $javascript->link('prototype', false); ?>
<?php
echo $crumb->getHtml('Edit Patient', null, 'auto' ) ;
echo '<br /><br />' ;
?> 
<div class="patients form">
<?php echo $this->Form->create('Patient');?>
	<fieldset>
 		<legend><?php __('Edit Patient'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('number');
		echo $this->Form->radio('consent', array('1' => 'Yes', '0' => 'No'), null, array('value' => $this->Form->value('consent')));
		echo $this->Form->input('location_id', array('label' => 'Registered at'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<?php 
	echo $access->checkHtml('Patients/delete', 'html', '<h3>Actions</h3>','' ); ?>
	<ul>
		<li><?php echo $access->checkHtml('Patients/delete', 'delete', 'Delete','delete/' . $this->Form->value('Patient.id'), 'delete', $this->Form->value('Patient.number') ); ?></li>
		
	</ul>
</div>