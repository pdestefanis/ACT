<div class="unitsItems index">
	<h2><?php __('Units Items');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('unit_id');?></th>
			<th><?php echo $this->Paginator->sort('item_id');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($unitsItems as $unitsItem):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $unitsItem['UnitsItem']['id']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($unitsItem['Unit']['id'], array('controller' => 'units', 'action' => 'view', $unitsItem['Unit']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($unitsItem['Item']['name'], array('controller' => 'items', 'action' => 'view', $unitsItem['Item']['id'])); ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $unitsItem['UnitsItem']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $unitsItem['UnitsItem']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $unitsItem['UnitsItem']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $unitsItem['UnitsItem']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Units Item', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Units', true), array('controller' => 'units', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Unit', true), array('controller' => 'units', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Items', true), array('controller' => 'items', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Item', true), array('controller' => 'items', 'action' => 'add')); ?> </li>
	</ul>
</div>