	<?php
		
	$fileData = $this->UpdateFile->addFileHeader(); 	

	$i = 0;

	foreach ($locations as $loc) {
		$alarm = false;
		$globalAlarm = false;
		$empty = true;
		
		foreach ($allLocations as $aLoc){
			if ($loc['locations']['parent_id'] == $aLoc['locations']['id']) {
				$parent = $aLoc;
			}
			if ($loc['locations']['parent_id'] == 0) {
				$parent = $loc;
			}
			
		}
			
		
		
		$fileData .= $this->UpdateFile->addPointHeader($i, $loc['locations'], $parent['locations']); 
		if (empty($listitems[$loc['locations']['id']]) && empty($listtreatments[$loc['locations']['id']]) )
			$fileData .= $this->UpdateFile->addCloseQuote(); 
		//get items
		if (!empty($listitems[$loc['locations']['id']])) {
			$fileData .= $this->UpdateFile->addKitsHeader(); 
			for ($j = 0; $j < count($listitems[$loc['locations']['id']]); $j++) { 
					//	print_r($listitems[$loc['locations']['id']][$j]['Listitems']['st']['item_id']);
						$empty = false;
						/*foreach($alerts as $a) {
							if ($a['Alert']['location_id'] == $loc['locations']['id'] //location match
								&& $a['Alert']['unit_id'] == $listitems[$loc['locations']['id']][$j]['Listitems']['st']['unit_id'] //item match
								&& (isset($a['Alert']['Alarm']) && $a['Alert']['Alarm'] == 1)) { //alarm is set we have an alert
								$alarm = true;
								$globalAlarm = true;
							}
						}*/
						if (isset($listitems[$loc['locations']['id']][$j]['Assigned']))
							$fileData .= $this->UpdateFile->addKitsData($listitems[$loc['locations']['id']][$j], $alarm); 
						$alarm = false;
			} 	
			//$chart = $this->element('google_graph');
			if (isset($graphURL[$loc['locations']['id']]))
				$fileData .= $this->UpdateFile->addKitsFooter($globalAlarm, $graphURL[$loc['locations']['id']]);
			else 
				$fileData .= $this->UpdateFile->addKitsFooter($globalAlarm);
			if (empty($listtreatments[$loc['locations']['id']]))
				$fileData .= $this->UpdateFile->addCloseQuote(); 
		}
		//get treatments
		/* if (!empty($listtreatments[$loc['locations']['id']])) {
			$fileData .= $this->UpdateFile->addTreatmentsHeader(); 
			for ($j = 0; $j < count($listtreatments[$loc['locations']['id']]); $j++) { 
				$fileData .= $this->UpdateFile->addTreatmentsData($listtreatments[$loc['locations']['id']][$j]['Listtreatments']); 
			} 
			
			$fileData .= $this->UpdateFile->addTreatmentsFooter(); 
			$fileData .= $this->UpdateFile->addCloseQuote(); 
		} */
		
		$fileData .= $this->UpdateFile->addPointFooter($globalAlarm, $empty);
		$i++;
		?>
	
<?php }
	$fileData .= $this->UpdateFile->addFileFooter();
?>


<div class="stats">
<?php echo $this->Form->create('Stat', array('action' => 'updateJSONFile')); ?>

	<?php
		echo $form->hidden('JSONFile', array('value' => $fileData));	?>

<?php echo $this->Form->end(__('', true));?>
</div>

<?php
	//$this->data['JSONFile'] = $fileData;
	//echo $this->data['JSONFile'] ;//htmlentities($fileData);
	?>