<?php
echo $crumb->getHtml('Add Location', null, 'auto' ) ;
echo '<br /><br />' ;
?> 

<div class="locations form">
<?php echo $this->Form->create('Location');?>
	<fieldset>
 		<legend><?php __('Add Location'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('shortname');
				echo $this->Form->input('locationLatitude', array('label' => 'Latitude'));
		echo $this->Form->input('locationLongitude',  array('label' => 'Longitude'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
