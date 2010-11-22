<?php 
 echo $javascript->link('prototype', false);
$this->layout = 'ajax';
echo $this->Form->create('DrugsTreatment');?>
<div class = "input text required"> 
	<?php
		echo $this->Form->hidden('treatment_id', array('value' => $this->passedArgs[0]));
		echo $this->Form->input('drug_id');
		//echo $this->Form->input('quantity', array('label' => 'Initial number dispensed'));
?> </div><?php
		//echo $this->Form->input('units', ;
//echo $this->Form->end(__('Submit', true));
echo $ajax->submit('Add', array('url' => '/drugs_treatments/add/' . $this->passedArgs[0] , 'update' => 'drugList')); 

?>

