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
	<h2><?php __('Reports');?></h2>
	
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort(__('Facility', true), 'Location.name');?></th>
			<th><?php echo 'User';?></th>
			<th><?php echo $this->Paginator->sort(__('Report', true));?></th>
			<th><?php echo $this->Paginator->sort(__('Date', true),'created');?></th>
			<th><?php echo __('ID', true);?></th>
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
				if ($stat['Location']['deleted'] == 0 )
					echo $access->checkHtml('Locations/view', 'text', $stat['Location']['name'], '/locations/view/' . $stat['Location']['id'] );
				else
					echo 'Deleted: ' . ($stat['Location']['name']);
				?>
		</td>
		<td>
			<?php
				if (empty($stat['Messagereceived']['id'])) //user
					echo $access->checkHtml('Users/view', 'text', $stat['User']['name'], '/users/view/' . $stat['User']['id'] );
				else {
					if ($stat['Phone']['deleted'] == 0 )
						echo $access->checkHtml('Phones/view', 'text', $stat['Phone']['name'], '/phones/view/' . $stat['Phone']['id'] );
					else
						echo __('Deleted: ', true) . ($stat['Phone']['name']);
				}
				?>
		
		</td>
		
		<td>
			<?php 
					if (isset ($stat['Messagereceived']['rawmessage']))
					echo $access->checkHtml('Stats/view', 'text', $stat['Messagereceived']['rawmessage'], '/stats/view/' . $stat['Stat']['id'] ); 
				else
					echo $access->checkHtml('Stats/view', 'text', $statuses[$stat['Stat']['status_id']] 
									//. " " . $stat['Stat']['quantity']
									. " " . (isset($units[$stat['Stat']['unit_id']])?$units[$stat['Stat']['unit_id']]:'')
									. " " . (isset($patients[$stat['Stat']['patient_id']])?$patients[$stat['Stat']['patient_id']]:'')
									, '/stats/view/' . $stat['Stat']['id'] ); 	
				?>
			&nbsp;
		</td>
		<td>
		<?php
				echo $access->checkHtml('Stats/view', 'text', $stat['Stat']['created'], '/stats/view/' . $stat['Stat']['id'] ); 
		?>
		</td>
		<td>
			<?php
				if (isset ($stat['Messagereceived']['Messagesent'][0]['rawmessage']))
					echo $access->checkHtml('Messagesents/view', 'text', $stat['Messagereceived']['Messagesent'][0]['rawmessage'], '/messagesents/view/' . $stat['Messagereceived']['Messagesent'][0]['id'] ); 
				else
					echo __("Facility update", true);
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
	<?php 
	echo $access->checkHtml('Stats/add', 'html', '<h3>Actions</h3>','' ); ?>
	<ul>
		<li><?php echo $access->checkHtml('Stats/assignUnits', 'link', __('Assign Kits', true),'/stats/assignUnits' ); ?></li>
		<li><?php echo $access->checkHtml('Stats/receiveUnits', 'link', __('Receive Kits', true),'/stats/receiveUnits' ); ?></li>
		<li><?php echo $access->checkHtml('Stats/discardUnits', 'link', __('Discard Kits', true),'/stats/discardUnits' ); ?></li>
	</ul>
</div>
