<?php echo $javascript->link('jquery.min', false); 
	echo $javascript->link('prototype', false); 
	echo $javascript->link(array('jquery.min.js',
			'date.js',
			'jquery.datePicker.js',
			'cake.datePicker.js'
	));
	echo $crumb->getHtml('Create Kit', null, 'auto' ) ;
	echo '<br /><br />' ;
?> 
<div class="units form">
<?php echo $this->Form->create('Unit');?>
	<fieldset>
		<legend><?php __('Add Kit'); ?></legend>
	<?php
		echo $this->Form->input('code', array('after' => '<p class="help">' . __('Should be four digits.', true) . '</p>'));
		
		?><div id="batchList"><?php echo $this->Form->input('batch_id', array('empty' => true) ); ?></div> <div id="addbatch">
		<?php echo $ajax->link(__('Click here to create a new batch', true), array('controller' => 'batches', 
				'action' =>'addAjax'), 
				array('update' => 'batchList'));
		?>
		
		</div> <?php 
		echo $this->Form->input('location_id', array('empty' => '---Select---','label' => __('Facility', true), 'div' => array ('class' => 'required')));
		echo $datePicker->picker('created', array(
							        'label' => __('Date', true),
							         'type' => 'date',
							        'dateFormat' => 'YMD',
									'timeFormat' => '24',
							        'minYear' => date('Y') - 1,
							        'maxYear' => date('Y'),
									'separator' => '',
									));
		echo $this->Form->label('Date', '' ,array('id' => 'dateLabel'));
		echo $this->Form->input('item_id');
		echo $this->Form->input('comment');
		
	?>
	<?php echo $this->Form->end(__('Submit', true));?>
	
	</fieldset>
<!-- <table id='recent'>
		<tr>
			<th><?php // echo __('Unit', true)?></th>
		</tr>
		<?php 
			/*echo "<br/><h2> ". __("Recently Used Units", true) . "</h2>";
			echo __("To assign these units please click here: ", true); 
			echo $this->Html->link(__('Assign Units', true), array('controller' => 'stats', 'action' => 'assignUnits'));
			if (!empty($lastUnits))
			foreach (explode(",", $lastUnits) as $unit){
				$unitId = $unit;*/ ?>
		<tr id='<?php //echo 'removeTr.' . $unitId; ?>'>
			<td><?php //echo $allUnits[$unitId]; ?></td>
			
		</tr>
		<?php // }?>
	</table> -->
</div>
<script type="text/javascript">
//<![CDATA[ 
jQuery('#UnitCreatedDay').change(function () {
	jQuery('#dateLabel').text( jQuery('#UnitCreatedYear').val() + "-" 
			+ jQuery('#UnitCreatedMonth').val() + "-" + jQuery('#UnitCreatedDay').val() );
	})  .change();
jQuery('.current-month, .other-month').live('click', function () {
	jQuery('#dateLabel').text( jQuery('#UnitCreatedYear').val() + "-" 
			+ jQuery('#UnitCreatedMonth').val() + "-" + jQuery('#UnitCreatedDay').val() );
	});
//]]>
</script>
