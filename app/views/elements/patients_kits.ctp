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
	<h2><?php __('Patients with Kits');?></h2>
	
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo __('Patient ID', true);?></th>
			<th><?php echo __('Kit provided at', true);?></th>
			<th><?php echo __('Date', true);?></th>
	</tr>
	<?php

	$i = 0;
	if (empty($stats))
		echo __("There are no patients holding kits", true);
	foreach ($stats as $sent):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
		
	
	?>
	<tr<?php echo $class;?>>
		<td>
			<?php

					echo $access->checkHtml('Patients/view', 'text', $patients[$sent['Stat']['patient_id']], '/patients/view/' . $sent['Stat']['patient_id']);

				?>
		</td>
		<td>
			<?php
				echo $access->checkHtml('Locations/view', 'text', $locations[$statIdLoc[$sent['Stat']['id']]], '/locations/view/' . $statIdLoc[$sent['Stat']['id']] );
				?>
		
		</td>
		<td>
		<?php
				echo $access->checkHtml('Stats/view', 'text', $sent['Stat']['created'], '/stats/view/' . $sent['Stat']['id'] ); 
		?>
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
	
</div>