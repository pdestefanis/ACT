<div class="search">
	<?php	echo $this->Form->create('Search', array('default'=>false) );?>
		<?php
			echo $this->Form->input('search', array('label' => '', 'value' => isset($this->passedArgs[0])?$this->passedArgs[0]:$this->Form->value('search')));
			$paginator->options(array('url' => 
					(($this->Form->value('search') =='')?
						(isset($this->passedArgs[0])?$this->passedArgs[0]:$this->Form->value('search'))
						:$this->Form->value('search'))
					)
				); 
		?>
	<?php  
		echo $ajax->submit('Filter', array('url'=> '', 'update' => 'update', 'loading' => '$(\'LoadingDiv\').show()', 'loaded' => '$(\'LoadingDiv\').hide()' )); 
	?>
</div>

	<h2><?php __('Kits');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('code');?></th>
			<th><?php echo __('Batch');?></th>
			<th><?php echo __('Created');?></th>
			<th><?php echo __('First Assigned');?></th>
			<th><?php echo __('Opened');?></th>
			<th><?php echo __('Comment');?></th>
		
	</tr>
	<?php
	
	$i = 0;
	foreach ($units as $unit):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $access->checkHtml('Units/view', 'link', $unit['Unit']['code'],'view/' . $unit['Unit']['id'] ); 
				?>&nbsp;</td>
		<td><?php 
			if (isset($batches[$unit['Unit']['batch_id']]))
				echo $batches[$unit['Unit']['batch_id']]; ?>&nbsp;</td>
		<td><?php echo $unitDates[$unit['Unit']['id']]['created']; ?>&nbsp;</td>
		<td><?php echo $unitDates[$unit['Unit']['id']]['assigned']; ?>&nbsp;</td>
		<td><?php echo $unitDates[$unit['Unit']['id']]['opened']; ?>&nbsp;</td>
		<td><?php echo $unit['Unit']['comment']; ?>&nbsp;</td>
		
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
		<li><?php echo $access->checkHtml('Units/add', 'link', 'New Kit','add' ); ?></li>
		
	</ul>
</div>
