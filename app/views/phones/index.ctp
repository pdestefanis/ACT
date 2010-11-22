<?php
echo $crumb->getHtml('System Management', null, '' ) ;
echo '<br /><br />' ;

?> 


<div class="phones index">
	<h2><?php __('Phones');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<!--<th><?php echo $this->Paginator->sort('id');?></th>-->
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('phonenumber');?></th>
			<th><?php echo $this->Paginator->sort('active');?></th>
			<th><?php echo $this->Paginator->sort('location_id');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($phones as $phone):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<!--<td><?php echo $phone['Phone']['id']; ?>&nbsp;</td>-->
		<td><?php echo $phone['Phone']['name']; ?>&nbsp;</td>
		<td><?php echo $phone['Phone']['phonenumber']; ?>&nbsp;</td>
		<td><?php echo ($phone['Phone']['active']?'Active':'Inactive'); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($phone['Location']['name'], array('controller' => 'locations', 'action' => 'view', $phone['Location']['id'])); ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $phone['Phone']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $phone['Phone']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $phone['Phone']['id']), null, sprintf(__('Are you sure you want to delete %s?', true), $phone['Phone']['name'])); ?>
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
		<li><?php echo $this->Html->link(__('New Phone', true), array('action' => 'add')); ?></li>
	</ul>
</div>
