<?php echo $javascript->link('prototype', false); ?>
<?php
echo $crumb->getHtml('Edit Update', null, 'auto' ) ;
echo '<br /><br />' ;
?> 
<div class="stats form">
<div class="help">
Only user or phone field can be changed. The facility will be populated, after selection, to point to the current user's or phone's associated facility. Manual updates give you the option to select the user; site updates give you the option to select the phone.
</div>
<?php echo $this->Form->create('Stat');?>
	<fieldset>
 		<legend><?php __('Edit Update'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('status_id', array('disabled' => 'disabled'));
		echo $this->Form->input('quantity', array('disabled' => 'disabled'));
		echo $this->Form->input('sent_to', array('options' => $locationsp, 'label' => 'Receiving Facility', 'empty' => '---Select---'));
		echo $this->Form->input('patient_id', array('empty' => '---Select---'));
		//echo $this->Form->input('item_id', array('empty' => '---Select---'));
		//echo $this->Form->input('rawreport_id');
		
		$updatesel = 'update_facility_select';
		if ( $this->Form->value('phone_id') != '') {
			echo $this->Form->input('phone_id');
			$options = array('url' => $updatesel, 'update' => 'StatLocationId');
			echo $ajax->observeField('StatPhoneId', $options);
		} else if ( $this->Form->value('user_id') != '') {
			echo $this->Form->input('user_id' );
			$options = array('url' => $updatesel, 'update' => 'StatLocationId');
			echo $ajax->observeField('StatUserId', $options);
		}
		$updatesel = 'update_patient_select' ;
		$options = array('url' => $updatesel, 'update' => 'StatPatientId');
		echo $ajax->observeField('StatStatusId', $options); 
		$updatesel = 'update_sent_to_select' ;
		$options2 = array('url' => $updatesel, 'update' => 'StatSentTo');
		echo $ajax->observeField('StatStatusId', $options2);
		//disable on select
		$updatesel = 'update_facility_select' ;
		$options3 = array('url' => $updatesel, 'update' => 'StatSentTo');
		echo $ajax->observeField('StatPatientId', $options3); 
		$updatesel = 'update_facility_select' ;
		$options4 = array('url' => $updatesel, 'update' => 'StatPatientId');
		echo $ajax->observeField('StatSentTo', $options4);	
		echo $this->Form->input('location_id', array('empty' => 'Please select user above', 'label' => 'Facility') );
		
		
	
		
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<?php 
	echo $access->checkHtml('Stats/delete', 'html', '<h3>Actions</h3>','' ); ?>
	<ul>
		<li><?php echo $access->checkHtml('Stats/delete', 'delete', 'Delete','delete/' . $this->Form->value('Stat.id'), 'delete', $this->Form->value('Stat.quantity') ); ?></li>
		
	</ul>
</div>