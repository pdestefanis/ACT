<?php
	echo $crumb->getHtml('Add User', null, 'auto') ;
	echo '<br /><br />';
?>
<div class="users form">
<?php echo $this->Form->create('User');?>
	<fieldset>
 		<legend><?php __('Add User'); ?></legend>
	<?php
		echo $this->Form->input('username');
		echo $this->Form->input('password');
		echo $form->input('confirm_passwd', array('type' => 'password', 'label' => 'Confirm Password'));
		
		
		//echo $form->label('User.confirm_passwd', 'Confirm password');
    		//echo $form->password('User.confirm_passwd', array('size' => '10') ); 
		echo $this->Form->input('group_id');
		
		
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
