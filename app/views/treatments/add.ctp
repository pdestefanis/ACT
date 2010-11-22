<?php
	echo $crumb->getHtml('Add Treatment', null, 'auto' ) ;
	echo '<br /><br />' ;

?> 
<div class="treatments form">
<?php echo $this->Form->create('Treatment');?>
	<fieldset>
 		<legend><?php __('Add Treatment'); ?></legend>
	<?php
		echo $this->Form->input('code');
		//echo $this->Form->input('drug_id');
		//echo $this->Form->input('Drug', array('type' => 'select'));//, 'class' =>'input text required'));
		//echo $this->Form->input('units', array('label' => 'Number dispensed'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
