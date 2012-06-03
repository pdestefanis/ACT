<?php echo $javascript->link('prototype', false); ?>

<?php
	echo $crumb->getHtml('New Update', null, 'auto' ) ;
	echo '<br /><br />' ;
?> 

<div class="stats form">
<?php echo $this->Form->create('Stat');?>
	<fieldset>
 		<legend><?php __('New Update'); ?></legend>
	<?php
		//echo $this->Form->input('modifier_id');
		echo $this->Form->input('status_id', array('empty' => '---Select---'));
		echo $this->Form->input('quantity');
		echo $this->Form->input('item_id', array('div' => array ('id' => 'item_div', 'style' => 'display:none;'), 'options' => array(1 => 'kit')));
		echo $this->Form->input('sent_to', array('options' => $locationsp, 'label' => 'Receiving Facility', 'empty' => '---Select---'));//,  'div' => array ('id' => 'parent_div', 'style' => 'display:none;', 'class' => 'input select required')));
		echo $this->Form->input('patient_id', array('empty' => '---Select---'));//, array('div' => array ('id' => 'patient_div', 'style' => 'display:none;'), 'empty' => '---Select---'));
	
		echo $this->Form->input('user_id');
		echo $this->Form->input('location_id', array('label' => 'Facility') );
	
				
		$updatesel = 'update_facility_select' ;
		$options = array('url' => $updatesel, 'update' => 'StatLocationId');
		echo $ajax->observeField('StatUserId', $options);
		
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
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
