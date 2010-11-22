<?php
	echo $crumb->getHtml('Edit User', null, 'auto') ;
	echo '<br /><br />';
?>
<div class="users form">
<?php echo $this->Form->create('User');?>
	<fieldset>
 		<legend><?php __('Edit User'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('username');
		echo $this->Form->input('password');
		echo $form->input('confirm_passwd', array('type' => 'password', 'label' => 'Confirm Password'));
		echo $this->Form->input('group_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('User.id')), null, sprintf(__('Are you sure you want to delete %s?', true), $this->Form->value('User.username'))); ?></li>

	</ul>
</div>
