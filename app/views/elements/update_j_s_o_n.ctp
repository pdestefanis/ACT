	<?php
		
	$fileData = $this->UpdateFile->addFileHeader(); 	

	$i = 0;
	
	foreach ($locations as $loc) :
	
		
		
		$fileData .= $this->UpdateFile->addPointHeader($i, $loc['locations']); 
		if (empty($listdrugs[$loc['locations']['id']]) && empty($listtreatments[$loc['locations']['id']]) )
			$fileData .= $this->UpdateFile->addCloseQuote(); 
		//get drugs
		if (!empty($listdrugs[$loc['locations']['id']])) {
			$fileData .= $this->UpdateFile->addDrugsHeader(); 
			for ($j = 0; $j < count($listdrugs[$loc['locations']['id']]); $j++) { 
						$fileData .= $this->UpdateFile->addDrugsData($listdrugs[$loc['locations']['id']][$j]['Listdrugs']); 
			} 	
			
			$fileData .= $this->UpdateFile->addDrugsFooter(); 
			if (empty($listtreatments[$loc['locations']['id']]))
				$fileData .= $this->UpdateFile->addCloseQuote(); 
		}
		//get treatments
		if (!empty($listtreatments[$loc['locations']['id']])) {
			$fileData .= $this->UpdateFile->addTreatmentsHeader(); 
			for ($j = 0; $j < count($listtreatments[$loc['locations']['id']]); $j++) { 
				$fileData .= $this->UpdateFile->addTreatmentsData($listtreatments[$loc['locations']['id']][$j]['Listtreatments']); 
			} 
			
			$fileData .= $this->UpdateFile->addTreatmentsFooter(); 
			$fileData .= $this->UpdateFile->addCloseQuote(); 
		}
		
		$fileData .= $this->UpdateFile->addPointFooter();
		$i++;
		?>
	
<?php endforeach; 
	$fileData .= $this->UpdateFile->addFileFooter();
?>


<div class="stats actions">
<?php echo $this->Form->create('Stat', array('action' => 'updateJSONFile')); ?>

	<?php
		echo $form->hidden('JSONFile', array('value' => $fileData));	?>

<?php echo $this->Form->end(__('', true));?>
</div>

<?php
	//$this->data['JSONFile'] = $fileData;
	//echo $this->data['JSONFile'] ;//htmlentities($fileData);
	?>