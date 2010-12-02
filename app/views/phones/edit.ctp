<?php
echo $crumb->getHtml('Edit Phone',  null, 'auto' ) ;
echo '<br /><br />' ;
?>
<div class="phones form">
<?php echo $this->Form->create('Phone');?>
	<fieldset>
 		<legend><?php __('Edit Phone'); ?></legend>
	<?php
		//echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('phonenumber');
		//echo $this->Form->input('active');
		if (isset($this->passedArgs[1]) && $this->passedArgs[1] == 1)
			echo $this->Form->hidden('deleted');
		echo $this->Form->radio('active', array('1' => 'Active', '0' => 'Inactive'), null, array('value' => $this->Form->value('active')));
		echo $this->Form->input('location_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Phone.id')), null, sprintf(__('Are you sure you want to delete %s?', true), $this->Form->value('Phone.name'))); ?></li>
		
	</ul>
</div>
