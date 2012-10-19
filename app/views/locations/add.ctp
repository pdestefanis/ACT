<?php
echo $javascript->link('jquery.min', false);
echo $crumb->getHtml('Add Facility', null, 'auto' ) ;
echo '<br /><br />' ;
?> 

<div class="locations form">
<?php echo $this->Form->create('Location');?>
	<fieldset>
 		<legend><?php __('Add Facility'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('shortname', array('after' => '<p class="help">' . __('Facility code not starting with P or K and followed by 3 to 6 characters.', true) . '</p>'));
		echo $this->Form->input('locationLatitude', array('label' => 'Latitude'));
		echo $this->Form->input('locationLongitude',  array('label' => 'Longitude', 'after' => '<p class="help">' . __('Click and drag to adjust coordinates', true) . '</p>'));
		echo $this->Form->input('parent_id', array('label' => 'Parent'));
	?>
	</fieldset>
	<?php echo $this->Form->end(__('Submit', true));?>
	<?php
	 echo "<div id='update'>";
			 echo $this->element('update_j_s_o_n');

			 echo "</div>";
	 echo '<div class="cont">';
	
	 echo $this->GoogleMapv3->map(array('div'=>array('height'=>'270', 'width'=>'400'), 'content' => 'Loading'));
	echo $this->Html->script($this->GoogleMapv3->apiUrl());
	$pointsJson = json_decode($this->loaded['ajax']->Form->fields['Stat.JSONFile'], TRUE);
	
	foreach($pointsJson['markers'] as $p)
		{
			'var newIcon = MapIconMaker.createMarkerIcon({
				width: 20, height: 34, primaryColor: "#0000FF", cornercolor:"#0000FF"});' ; 
			$options = array(
					'lng' =>$p['point']['longitude'],
					'lat' =>$p['point']['latitude'], 
						'content'=>"<span id='infoWindow'>" . $p['html'] . "</span>",
						'icon'=> 'http://www.google.com/intl/en_us/mapfiles/ms/micons/blue-dot.png'
						);
				 $this->GoogleMapv3->addMarker($options);
				 //$this->GoogleMapv3->addInfoWindow($options);
		}
		 $this->GoogleMapv3->addMarkerOnClick('New facility');
		echo  $this->GoogleMapv3->script();
		
    
	echo '</div>';?>

</div>
