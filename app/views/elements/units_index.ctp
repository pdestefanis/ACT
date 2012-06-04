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

	<h2><?php __('Units');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('code');?></th>
			<th><?php echo __('Batch');?></th>
			<th class="actions"><?php __('Actions');?></th>
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
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $unit['Unit']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $unit['Unit']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $unit['Unit']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $unit['Unit']['id'])); ?>
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
		<li><?php echo $access->checkHtml('Units/add', 'link', 'New Unit','add' ); ?></li>
		
	</ul>
</div>