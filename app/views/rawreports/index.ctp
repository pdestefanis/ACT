<?php $javascript->link('jquery.min', false); $javascript->link('common', false); ?>
<?php
	echo $crumb->getHtml('System Management', null, '' ) ;
	echo '<br /><br />' ;
?> 


<div class="rawreports index">
	<h2><?php __('Raw Reports');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<!--<th><?php echo $this->Paginator->sort('id');?></th> -->
			<th><?php echo $this->Paginator->sort('raw_message');?></th>
			<th><?php echo $this->Paginator->sort('message_code');?></th>
			<th><?php echo $this->Paginator->sort('Report received on', 'created');?></th>
			<th><?php echo $this->Paginator->sort('phone_id');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($rawreports as $rawreport):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<!--<td><?php echo $rawreport['Rawreport']['id']; ?>&nbsp;</td>-->
		<td><?php echo $rawreport['Rawreport']['raw_message']; ?>&nbsp;</td>
		<td><?php echo $rawreport['Rawreport']['message_code']; ?>&nbsp;</td>
		<td><?php echo $rawreport['Rawreport']['created']; ?>&nbsp;</td>
		<td>
			<?php
				if ($rawreport['Phone']['deleted'] == 0 )
					echo $this->Html->link($rawreport['Phone']['name'], array('controller' => 'phones', 'action' => 'view', $rawreport['Phone']['id'])); 
				else
					echo 'Deleted: ' . ($rawreport['Phone']['name']);
				?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $rawreport['Rawreport']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $rawreport['Rawreport']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $rawreport['Rawreport']['id']), null, sprintf(__('Are you sure you want to delete %s?', true), $rawreport['Rawreport']['raw_message'])); ?>
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
		<li><?php echo $this->Html->link(__('New Raw Report', true), array('action' => 'add')); ?></li>
	</ul>
</div>
