<?php $javascript->link('jquery.min', false); $javascript->link('common', false); ?>
<?php
	echo $crumb->getHtml('System Management', null, '' ) ;
	echo '<br /><br />' ;
?> 
<div class="stats index">
	<h2><?php __('Reports');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('drug_id');?>/<?php echo $this->Paginator->sort('treatment_id');?>
			<th><?php echo $this->Paginator->sort('Quantity 
			/
			People','quantity');?></th>
			<th><?php echo $this->Paginator->sort('location_id');?></th>
			<th><?php echo $this->Paginator->sort('phone_id');?></th>
			<th><?php echo $this->Paginator->sort('Report received on','created');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($stats as $stat):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		
		
		<td>
			<?php 
				if ($stat['Drug']['id'] != 0)
					echo $this->Html->link($stat['Drug']['name'], array('controller' => 'drugs', 'action' => 'view', $stat['Drug']['id'])); 
				else
					echo $this->Html->link($stat['Treatment']['code'], array('controller' => 'treatments', 'action' => 'view', $stat['Treatment']['id'])); 
			?>
		</td>
		<td>
			<?php echo $this->Html->link($stat['Stat']['quantity'], array('controller' => 'rawreports', 'action' => 'view', $stat['Rawreport']['id']));
			?>&nbsp;</td>
		<td>
			
			<?php
				if ($stat['Location']['deleted'] == 0 )
					echo $this->Html->link($stat['Location']['name'], array('controller' => 'phones', 'action' => 'view', $stat['Location']['id']));
				else
					echo 'Deleted: ' . ($stat['Location']['name']);
				?>
		</td>
		<td>
			<?php
				if ($stat['Phone']['deleted'] == 0 )
					echo $this->Html->link($stat['Phone']['name'], array('controller' => 'phones', 'action' => 'view', $stat['Phone']['id']));
				else
					echo 'Deleted: ' . ($stat['Phone']['name']);
				?>
		
		</td>
		
		<td><?php echo $this->Html->link($stat['Stat']['created'], array('controller' => 'rawreports', 'action' => 'view', $stat['Rawreport']['id'])); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $stat['Stat']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $stat['Stat']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $stat['Stat']['id']), null, sprintf(__('Are you sure you want to delete %s?', true), $stat['Stat']['quantity'])); ?>
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
		<li><?php echo $this->Html->link(__('New Report', true), array('action' => 'add')); ?></li>
	</ul>
</div>
