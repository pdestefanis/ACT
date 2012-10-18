<?php echo $javascript->link('prototype', false);
echo $javascript->link(array('jquery.min.js',
		'date.js',
		'jquery.datePicker.js',
		'cake.datePicker.js'
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
							        'dateFormat' => 'YMD',
									'timeFormat' => '24',
							        'minYear' => date('Y') - 1,
							        'maxYear' => date('Y'),
									'separator' => '',
									));
		echo $this->Form->label('Date', 'Date' ,array('id' => 'dateLabel'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>

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
