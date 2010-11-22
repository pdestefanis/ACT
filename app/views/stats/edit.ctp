<?php echo $javascript->link('prototype', false); ?>
<?php
echo $crumb->getHtml('Edit Statistic', null, 'auto' ) ;
echo '<br /><br />' ;
?> 
<div class="stats form">
<?php echo $this->Form->create('Stat');?>
	<fieldset>
 		<legend><?php __('Edit Report'); ?></legend>
	<?php
		echo $this->Form->input('id');

		echo $this->Form->input('quantity');
		echo $this->Form->input('drug_id', array('empty' => '---Select---'));
		echo $this->Form->input('treatment_id', array('empty' => '---Select---'));
		echo $this->Form->input('rawreport_id');
		echo $this->Form->input('phone_id');
		
		
		echo $this->Form->input('location_id');
		
		$updatesel = 'update_select' ;
		$options = array('url' => $updatesel, 'update' => 'StatLocationId');
		echo $ajax->observeField('StatPhoneId', $options);
		
		$options = array('url' => $updatesel, 'update' => 'StatTreatmentId');
		echo $ajax->observeField('StatDrugId', $options);
		
		$options = array('url' => $updatesel, 'update' => 'StatDrugId');
		echo $ajax->observeField('StatTreatmentId', $options);
		
		
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Stat.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Stat.quantity'))); ?></li>
		
	</ul>
</div>