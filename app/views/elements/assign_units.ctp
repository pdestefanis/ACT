<script type="text/javascript">
//<![CDATA[
   function checkDisplayed() {
      //  $("#StatSelection").click(function(){
        if (jQuery("#StatSelection").val() == "0" ) {
        	jQuery("#patient_div").slideDown(); 
        	jQuery("#location_div").slideUp();
        } else if (jQuery("#StatSelection").val() == "1" ) {
        	jQuery("#patient_div").slideUp(); 
        	jQuery("#location_div").slideDown();
        } else {
        	jQuery("#patient_div").slideUp(); 
        	jQuery("#location_div").slideUp();
        }
     //});
};   
jQuery(document).ready(function () {
	if (jQuery("#StatSelection").val() == "0" ) {
    	jQuery("#patient_div").slideDown(); 
    	jQuery("#location_div").slideUp();
	} else if (jQuery("#StatSelection").val() == "1" ) {
    	jQuery("#patient_div").slideUp(); 
    	jQuery("#location_div").slideDown();
    }
	});
//]]>
</script>
<?php 
	echo $this->Form->create('Stat');?>
	<table>
 		<tr>
	<?php
		echo $this->Form->hidden('status_id', array('value' => 2));
		echo $this->Form->hidden('user_id', array('value' => $userId));
		echo "<td>";
		$checkOptions = array(0 => __('Patient', true), 1 => __('Facility', true));
		echo $this->Form->input('selection', array('type' => 'select', 
								'options' => $checkOptions, 
								'empty' =>  __('---Select---', true),
								'label' => __('Assign to', true),
								'div' => array ('class' => 'input select required assignSelect',
												'id' => 'assignSelect')
		 ) );
		
		echo $this->Form->input('patient_id', array('empty' => __('---Select---', true), 
								'div' => array ('id' => 'patient_div', 
										'style' => 'display:none;', 
										'class' => 'input select required')
								));
		echo "<br/>";
		//echo "</td>";
		
		//echo "<td>";
		echo $this->Form->input('location_id', array('label' => __('Facility', true), 'empty' => '---Select---',
								'div' => array ('id' => 'location_div', 
										'style' => 'display:none;', 
										'class' => 'input select required')
								));
		//echo "</td>";
		//echo "<td>";
		
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
		echo "</td>";	
		echo "<td>";
		echo $this->Form->input('Unit', array('type' => 'select', 'multiple' => true, 'size' => 10 ));
		echo "</td>";	
		echo "<td>";
		//echo $this->Form->end(__('Submit', true));
		if (empty($units)) {
			echo __("No more kits left", true);
			echo $ajax->submit('Assign', array('url' => '/stats/assignUnits/' .$lastUnits, 'update' => 'assign', 'disabled' => 'true'));
		} else
			echo $ajax->submit('Assign', array('url' => '/stats/assignUnits/' . $lastUnits, 'update' => 'assign'));
		echo "</td>";
		//disable on select
		/*$updatesel = 'update_patient_select' ;
		$options4 = array('url' => $updatesel, 'update' => 'StatPatientId');
		echo $ajax->observeField('StatLocationId', $options4);*/
		
		$options = array('url' => 'update_patient_select', 'update' => 'StatPatientId', 'after' => 'checkDisplayed()');
		echo $ajax->observeField('StatSelection', $options);
		
		$options = array('url' => 'update_facility_select', 'update' => 'StatLocationId', 'after' => 'checkDisplayed()');
		echo $ajax->observeField('StatSelection', $options);

	?>
	</tr>
	</table>	
	
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
