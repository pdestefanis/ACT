<?php echo $javascript->link('prototype', false); 
	echo $javascript->link(array('jquery.min.js',
			'date.js',
			'jquery.datePicker.js',
			'cake.datePicker.js'
	));

echo $crumb->getHtml('Edit Update', null, 'auto' ) ;
echo '<br /><br />' ;
?> 
<div class="stats form">
<?php echo $this->Form->create('Stat');?>
	<fieldset>
 		<legend><?php __('Edit Update'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->hidden('phone_id');
		echo $this->Form->hidden('quantity');
		echo $this->Form->hidden('unit_id');
		if ($this->Form->value('Stat.unit_id') != '')
			echo $this->Form->input('unit_id', array('selected' => $this->Form->value('Stat.unit_id'),
					'disabled' => true));
		if ($this->Form->value('Stat.patient_id') != '')
				echo $this->Form->input('patient_id', array('selected' => $this->Form->value('Stat.patient_id'), 
														'disabled' => true));
		echo $datePicker->picker('created', array(
				'label' => __('Date', true),
				'type' => 'date',
				'dateFormat' => 'YMD',
				'timeFormat' => '24',
				'minYear' => date('Y') - 1,
				'maxYear' => date('Y'),
				'separator' => '',
		));
		echo $this->Form->label('Date', 'Date' ,array('id' => 'dateLabel'));
		echo __('Date can only be changed withing these limits: ', true);
		echo (isset($dateRange['min'][1])?$dateRange['min'][1]:__('No limit', true));
		echo __(' to ', true);
		echo (isset($dateRange['max'][1])?$dateRange['max'][1]:__('No limit', true));
		if ($this->Form->value('Stat.location_id') != '')
		echo $this->Form->input('location_id', array('selected' => $this->Form->value('Stat.location_id'), 
											'label' => 'Facility',
											 'disabled' => true) );
		
		
	
		
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<?php 
	echo $access->checkHtml('Stats/delete', 'html', '<h3>Actions</h3>','' ); ?>
	<ul>
		<li><?php //echo $access->checkHtml('Stats/delete', 'delete', 'Delete','delete/' . $this->Form->value('Stat.id'), 'delete', $this->Form->value('Stat.quantity') ); ?></li>
		
	</ul>
</div>
<script type="text/javascript">
//<![CDATA[ 
jQuery('#StatCreatedDay').change(function () {
	jQuery('#dateLabel').text( jQuery('#StatCreatedYear').val() + "-" 
			+ jQuery('#StatCreatedMonth').val() + "-" + jQuery('#StatCreatedDay').val() );
	})  .change();
jQuery('.current-month, .other-month').live('click', function () {
	jQuery('#dateLabel').text( jQuery('#StatCreatedYear').val() + "-" 
			+ jQuery('#StatCreatedMonth').val() + "-" + jQuery('#StatCreatedDay').val() );
	});
//]]>
</script>