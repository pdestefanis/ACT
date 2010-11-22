<?php
echo $crumb->getHtml('Edit Location', null, 'auto' ) ;
echo '<br /><br />' ;
?> 
<div class="locations form">
<?php echo $this->Form->create('Location');?>
	<fieldset>
 		<legend><?php __('Edit Location'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('shortname');
		
		echo $this->Form->input('locationLatitude', array('label' => 'Latitude'));
		echo $this->Form->input('locationLongitude',  array('label' => 'Longitude'));
		
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Location.id')), null, sprintf(__('Are you sure you want to delete %s?', true), $this->Form->value('Location.name'))); ?></li>
	</ul>
</div>
