<?php $javascript->link('prototype'); ?>
<div id='js_errors' class='message' style='display:none'>
</div>
<?php
echo $crumb->getHtml('Viewing Treatment', null, 'auto' ) ;
echo '<br /><br />' ;

?> 
<div class="treatments form">
<?php echo $this->Form->create('DrugsTreatment');?>
	<fieldset>
 		<legend><?php __('Edit Treatment'); ?></legend>
	<?php
		echo $this->Form->input('drug_id', array('type' => 'select'));
		echo $this->Form->input('treatment_id', array('type' => 'select'));
		//echo $this->Form->input('qunatity');
		//echo $this->Form->input('Drug', array('type' => 'select', 'class' => 'input select required')); //, 'multiple' =>  'checkbox'));
		
		//echo $this->Form->input('Drug.DrugsTreatment.quantity');
		
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>