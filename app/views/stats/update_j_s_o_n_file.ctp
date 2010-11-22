
<?php
	echo $crumb->getHtml('Update JSON', null, '' ) ;
	echo '<br /><br />' ;
?> 
	
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


<div class="stats form">
<?php echo $this->Form->create('Stat', array('action' => 'updateJSONFile')); ?>
	<fieldset>
 		<legend><?php __('Update File'); ?></legend>
	<?php
		echo $form->textarea('JSONFileArea', array('value' => $fileData, 'disabled' => 'true',  'rows' => '20', 'cols' => '60'));	
		echo $form->hidden('JSONFile', array('value' => $fileData));	?>
	</fieldset>
<?php echo $this->Form->end(__('Update', true));?>
</div>

<?php
	//$this->data['JSONFile'] = $fileData;
	//echo $this->data['JSONFile'] ;//htmlentities($fileData);
	?>