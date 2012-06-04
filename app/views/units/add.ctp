<?php echo $javascript->link('jquery.min', false); ?>	
<?php echo $javascript->link('prototype', false); ?>
<?php
echo $crumb->getHtml('Create Unit', null, 'auto' ) ;
echo '<br /><br />' ;
?> 
<div class="units form">
<?php echo $this->Form->create('Unit');?>
	<fieldset>
		<legend><?php __('Add Unit'); ?></legend>
	<?php
		echo $this->Form->input('code');
		
		?><div id="batchList"><?php echo $this->Form->input('batch_id', array('empty' => true) ); ?></div> <div id="addbatch">
		<?php echo $ajax->link(__('Click here to create a new batch', true), array('controller' => 'batches', 
				'action' =>'addAjax'), 
				array('update' => 'batchList'));
		?>
		
		</div> <?php 
		//echo $this->Form->input('Item');
		echo $this->Form->input('item_id');
		
	?>
	<?php echo $this->Form->end(__('Submit', true));?>
	
	</fieldset>
<table id='recent'>
		<tr>
			<th><?php echo __('Unit', true)?></th>
		</tr>
		<?php 
			echo "<br/><h2> ". __("Recently Used Units", true) . "</h2>";
			echo __("To assign these units please click here: ", true); 
			echo $this->Html->link(__('Assign Units', true), array('controller' => 'stats', 'action' => 'assignUnits'));
			if (!empty($lastUnits))
			foreach (explode(",", $lastUnits) as $unit){
				$unitId = $unit; ?>
		<tr id='<?php echo 'removeTr.' . $unitId; ?>'>
			<td><?php echo $allUnits[$unitId]; ?></td>
			
		</tr>
		<?php }?>
	</table>
</div>

<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Units', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Items', true), array('controller' => 'items', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Item', true), array('controller' => 'items', 'action' => 'add')); ?> </li>
	</ul>
</div>
