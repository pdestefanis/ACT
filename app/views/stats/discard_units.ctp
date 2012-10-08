<?php //echo $javascript->link('prototype', false);
echo $javascript->link(array('jquery.min',
            'date',
            'jquery.datePicker',
            'cake.datePicker'
            )); 
?>

<?php
	echo $crumb->getHtml(__('Discard Unit', true), null, 'auto' ) ;
	echo '<br /><br />' ;
?> 

<div class="stats form">
<?php echo $this->Form->create('Stat');?>
	<fieldset>
 		<legend><?php __('Discard Unit'); ?></legend>
	<?php
		echo $this->Form->hidden('status_id', array('value' => 3));
		echo $this->Form->hidden('user_id', array('value' => $userId));
		if (empty($units))
			echo $this->Form->input('unit_id', array('empty' => __('No kits available', true)));
		else
			echo $this->Form->input('unit_id');
		echo $this->Form->input('location_id', array('label' => __('Facility', true), 'empty' => '---Select---') );
		echo $datePicker->picker('created', array(
							        'label' => __('Date', true),
							        'type' => 'date',
							        'dateFormat' => 'DMY',
							        'minYear' => date('Y') - 1,
							        'maxYear' => date('Y') +1,
									));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
