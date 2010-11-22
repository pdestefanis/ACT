<?php
	echo $crumb->getHtml('System Management', null, '' );
	echo '<br /><br />'; ?> 
<div class="treatments index">
	<h2><?php __('Treatments');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<!--<th><?php echo $this->Paginator->sort('id');?></th>-->
			<th><?php echo $this->Paginator->sort('code');?></th>
			<th><?php echo "Drugs";?></th>
			
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($treatments as $treatment):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<!--<td><?php echo $treatment['Treatment']['id']; ?>&nbsp;</td>-->
		<td><?php echo $this->Html->link($treatment['Treatment']['code'], array('controller' => 'treatments', 'action' => 'view', $treatment['Treatment']['id'])); ?>&nbsp;</td>
		<td>
			<?php 
			
			if (!empty($treatment['Drug'])): ?>
			<?php
				$i = 1;
				foreach ($treatment['Drug'] as $drug): ?>
					<?php echo $this->Html->link($drug['name'] . ", " . $drug['presentation'] , array('controller' => 'drugs', 'action' => 'view', $drug['id']));
					if ($i++ < count( $treatment['Drug']))
						echo "<br/>";
					?>
			<?php endforeach; ?>
			<?php endif; ?>
		</td>
		
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $treatment['Treatment']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $treatment['Treatment']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $treatment['Treatment']['id']), null, sprintf(__('Are you sure you want to delete %s?', true), $treatment['Treatment']['code'])); ?>
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
		<li><?php echo $this->Html->link(__('New Treatment', true), array('action' => 'add')); ?></li>
	</ul>
</div>
