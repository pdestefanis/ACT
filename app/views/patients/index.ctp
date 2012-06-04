<?php echo $javascript->link('prototype', false); ?>
<?php
echo $crumb->getHtml('Patient Listing', null, 'auto' ) ;
echo '<br /><br />' ;
?> 
<div class="patients index">
	<h2><?php __('Patients');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('number');?></th>
			<th><?php echo $this->Paginator->sort('consent');?></th>
			<th><?php echo $this->Paginator->sort(__('Registered at',true), 'location_id');?></th>
			<th><?php echo $this->Paginator->sort(__('Date of consent' ,true), 'created');?></th>
			<th><?php echo __('Kit Provided' ,true);?></th>

	</tr>
	<?php
	$i = 0;
	foreach ($patients as $patient):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $access->checkHtml('Patients/view', 'text', $patient['Patient']['number'], '/patients/view/' . $patient['Patient']['id'] ); ?>
		</td>
		<td><?php echo ($patient['Patient']['consent']?'Yes':'No'); ?>&nbsp;</td>
		<td><?php 
			if (isset($locations[$patient['Patient']['location_id']]))
				echo $locations[$patient['Patient']['location_id']]; 
			
			?>&nbsp;</td>
		<td><?php echo ($patient['Patient']['created']); ?>&nbsp;</td>
		<td><?php 
			$received = 0;
			$sent = 0;
			foreach ($patient['Stat'] as $stat){
				$received += ($stat['status_id'] == 1?1:0);
				$sent += ($stat['status_id'] == 2?1:0);;
			} 
			
			if (empty($patient['Stat']))
				echo __('No', true);
			else if ($sent == $received || $sent < $received)
				echo __('Returned', true);
			else if ($sent > $received)
				echo __('Yes', true);
				
		 ?>&nbsp;</td>
		
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
	<?php 
	echo $access->checkHtml('Patients/add', 'html', '<h3>Actions</h3>','' ); ?>
	<ul>
		<li><?php echo $access->checkHtml('Patients/add', 'link', 'New Patient','add/'); ?></li>
		
	</ul>
</div>