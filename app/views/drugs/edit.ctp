<?php
echo $crumb->getHtml('Editing drug', null, 'auto' ) ;
echo '<br /><br />' ;
//echo $html->link('View Drug', 'view') ;
?> 
<div class="drugs form">
<?php echo $this->Form->create('Drug');?>
	<fieldset>
 		<legend><?php __('Edit Drug'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('code');
		echo $this->Form->input('presentation');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Drug.id')), null, sprintf(__('Are you sure you want to delete %s?', true), $this->Form->value('Drug.code'))); ?></li>
		
				
	</ul>
</div>
