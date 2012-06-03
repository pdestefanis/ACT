<?php
echo $crumb->getHtml('Options',  null, 'auto' ) ;
echo '<br /><br />' ;
?>
<div class="phones form">
<?php echo $this->Form->create('Stat', array('action' => 'options'));?>
	<fieldset>
 		<legend><?php __('Options'); ?></legend>
	<?php
		echo $this->Form->input('ndigits', array('label' => 'Last n digits of phone number to consider', 
				'value' =>  $this->Form->value('ndigits') ));
		echo $this->Form->hidden('ndigitsOld', array('value' =>  $this->Form->value('ndigitsOld') ));
		echo $this->Form->input('limit', array('label' => 'Month limit on graph reports', 
				'value' =>  $this->Form->value('limit') ));
		echo $this->Form->input('appName', array('label' => 'Application Name', 
				'value' =>  $this->Form->value('appName') ));
		
		echo $this->Form->input('level0', array('label' => 'Level 0', 
				'value' =>  $this->Form->value('Facility.level0') ));
		echo $this->Form->input('level1', array('label' => 'Level 1', 
				'value' =>  $this->Form->value('Facility.level1') ));
		
		echo $this->Form->input('level2', array('label' => 'Level 2', 
				'value' =>  $this->Form->value('Facility.level2') ));
		echo $this->Form->input('level3', array('label' => 'Level 3', 
				'value' =>  $this->Form->value('Facility.level3') ));
		echo $this->Form->input('level4', array('label' => 'Level 4', 
				'value' =>  $this->Form->value('Facility.level4') ));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	
</div>