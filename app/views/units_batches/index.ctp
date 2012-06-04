<div class="unitsBatches index">
	<h2><?php __('Units Batches');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('unit_id');?></th>
			<th><?php echo $this->Paginator->sort('batch_id');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($unitsBatches as $unitsBatch):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $unitsBatch['UnitsBatch']['id']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($unitsBatch['Unit']['id'], array('controller' => 'units', 'action' => 'view', $unitsBatch['Unit']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($unitsBatch['Batch']['id'], array('controller' => 'batches', 'action' => 'view', $unitsBatch['Batch']['id'])); ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $unitsBatch['UnitsBatch']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $unitsBatch['UnitsBatch']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $unitsBatch['UnitsBatch']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $unitsBatch['UnitsBatch']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Kits Batch', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Kits', true), array('controller' => 'units', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Kit', true), array('controller' => 'units', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Batches', true), array('controller' => 'batches', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Batch', true), array('controller' => 'batches', 'action' => 'add')); ?> </li>
	</ul>
</div>
