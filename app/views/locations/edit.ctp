<?php
echo $javascript->link('jquery.min', false);
echo $crumb->getHtml('Edit Facility', null, 'auto' ) ;
echo '<br /><br />' ;
?> 
<div class="locations form">
<?php echo $this->Form->create('Location');?>
	<fieldset>
 		<legend><?php __('Edit Facility'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('shortname');
		
		echo $this->Form->input('locationLatitude', array('label' => 'Latitude'));
		echo $this->Form->input('locationLongitude',  array('label' => 'Longitude'));
		echo $this->Form->input('parent_id', array('label' => 'Parent'));
		//print_r($this->Form);
		
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
<?php
 echo "<div id='update'>";
			 echo $this->element('update_j_s_o_n');

			 echo "</div>";
	 echo '<div class="cont">';
	
	 echo $this->GoogleMapv3->map(array('div'=>array('height'=>'270', 'width'=>'400'), 
	 		'content' => 'Loading', 
	 		'scrollwheel' => 'true'));
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
						'icon'=> 'http://www.google.com/intl/en_us/mapfiles/ms/micons/blue-dot.png',
						'draggable' => 'true'
						);
				 $this->GoogleMapv3->addMarker($options);
				 //$this->GoogleMapv3->addInfoWindow($options);
		}
		 $this->GoogleMapv3->addMarkerOnClick('New facility');
		echo  $this->GoogleMapv3->script();
		
    
	echo '</div>';?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $access->checkHtml('Locations/delete', 'delete', 'Delete','delete/' . $this->Form->value('Location.id'), 'delete', $this->Form->value('Location.name') ); ?></li>
	</ul>
</div>