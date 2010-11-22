<?php echo $javascript->link('jquery.min', false); ?>	
<?php echo $javascript->link('prototype', false); ?>
<?php
echo $crumb->getHtml('Viewing Treatment', null, 'auto' ) ;
echo '<br /><br />' ;
?> 
<div class="treatments form">
<?php echo $this->Form->create('Treatment');?>
	<fieldset>
 		<legend><?php __('Edit Treatment'); ?></legend>
	<?php
		echo $this->Form->input('id'); 
		echo $this->Form->input('code');
		?>
		<div id="drugList"><?php echo $this->element('drugs_treatments'); ?></div> <div id="adddrug">
		<?php echo $ajax->link(__('Add drug to this treatment', true), array('controller' => 'drugs_treatments', 
				'action' =>'add/', $this->Form->value('Treatment.id')), 
				array('update' => 'drugList'));
		?>
		
		</div>
		<?php
		//echo $this->Form->input('Drug'), array( 'type' => 'select', 'multiple' => true ));
		echo $this->Form->input('description', array('type' => 'textarea', 'label' => 'Description'));
		//echo $this->Form->input('units', array('label' => 'Number dispensed'));
		
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
		
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Treatment.id')), null, sprintf(__('Are you sure you want to delete %s?', true), $this->Form->value('Treatment.code'))); ?></li>
		
	</ul>
</div>
