<?php echo $javascript->link('prototype', false); ?>
<?php
echo $crumb->getHtml('Edit Status', null, 'auto' ) ;
echo '<br /><br />' ;
?> 

<div class="statuses form">
<?php echo $this->Form->create('Status');?>
	<fieldset>
 		<legend><?php __('Edit Status'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Status.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Status.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Statuses', true), array('action' => 'index'));?></li>
	</ul>
</div>