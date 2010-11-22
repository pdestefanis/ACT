<?php echo $javascript->link('prototype', false); ?>

<?php
	echo $crumb->getHtml('Add Statistic', null, 'auto' ) ;
	echo '<br /><br />' ;
?> 

<div class="stats form">
<?php echo $this->Form->create('Stat');?>
	<fieldset>
 		<legend><?php __('Add Report'); ?></legend>
	<?php
		echo $this->Form->input('quantity');
		echo $this->Form->input('drug_id', array('empty' => '---Select---'));
		echo $this->Form->input('treatment_id', array('empty' => '---Select---'));
		echo $this->Form->input('rawreport_id');
		
		echo $this->Form->input('phone_id', array('empty' => '---Select---'));
		$locid = $this->Form->value('location_id');
		if ( $locid == 0 && empty($locid))
			echo $this->Form->input('location_id', array('empty' => 'Please select phone above') );
		else 
			echo $this->Form->input('location_id' );
				
		$updatesel = 'update_select' ;
		$options = array('url' => $updatesel, 'update' => 'StatLocationId');
		echo $ajax->observeField('StatPhoneId', $options);
		
		$options1 = array('url' => $updatesel, 'update' => 'StatTreatmentId');
		echo $ajax->observeField('StatDrugId', $options1);
		
		$options2 = array('url' => $updatesel, 'update' => 'StatDrugId');
		echo $ajax->observeField('StatTreatmentId', $options2);
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
