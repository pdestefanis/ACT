	
	<?php 
	if($access->check('Tracks/index') ) {
		?> <h3><?php __('Related Reports');?></h3> 
		<?php 
		if (!empty($drug['Track']) || !empty($kit['Track']) || !empty($rawreport['Track']) || !empty($phone['Track'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Level'); ?></th>
		<th><?php __('From Location'); ?></th>
		<th><?php __('Location'); ?></th>
		<th><?php __('Patient'); ?></th>
		<th><?php __('Status'); ?></th>
		<th><?php __('Phone'); ?></th>
		<th><?php __('Raw Report'); ?></th>
		<th><?php __('Date'); ?></th>

	</tr>
	<?php
		$i = 0;
	if (!empty($drug['Track'])) {
		foreach ($drug['Track'] as $track) {
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>

			<td><?php echo $this->Html->link($track['quantity'], array('controller' => 'tracks', 'action' => 'view', $track['id'])); ?></td>
			<td><?php if (!empty($locations[$track['location_id']])) echo $this->Html->link($locations[$track['location_id']], array('controller' => 'locations', 'action' => 'view', $track['location_id'])); else echo 'Location deleted.'; ?></td>
			<td><?php if (!empty($phones[$track['phone_id']])) echo $this->Html->link($phones[$track['phone_id']], array('controller' => 'phones', 'action' => 'view', $track['phone_id'])); else echo 'Phone deleted.'; ?></td>
			<td><?php echo $this->Html->link($track['rawreport_id'], array('controller' => 'rawreports', 'action' => 'view', $track['rawreport_id'])); ?></td>

		</tr>
<?php }
	}
	if (!empty($kit['Track'])) {
		foreach ($kit['Track'] as $track) {
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>

			<td><?php 
			
				
			if (!empty($locations[$track['location_id']])) {
				$loc = array_values($locations[$track['location_id']]);
				$lev = array_keys($locations[$track['location_id']]);
				echo $this->Html->link($levels[$lev[0]], array('controller' => 'levels', 'action' => 'view', $lev[0])); 
			}
			if (!empty($locations[$track['parent_location_id']])) {
				$ploc = array_values($locations[$track['parent_location_id']]);
				$plev = array_keys($locations[$track['parent_location_id']]);
				echo $this->Html->link($levels[$plev[0]], array('controller' => 'levels', 'action' => 'view', $plev[0])); 
			}
			?></td>
			<td><?php if (!empty($locations[$track['parent_location_id']])) echo $this->Html->link($ploc[0], array('controller' => 'locations', 'action' => 'view', $track['parent_location_id'])); else echo ''; ?></td>
			<td><?php if (!empty($locations[$track['location_id']])) echo $this->Html->link($loc[0], array('controller' => 'locations', 'action' => 'view', $track['location_id'])); else echo ''; ?></td>
			<td><?php  if (!empty($patients[$track['patient_id']]))
				echo $this->Html->link($patients[$track['patient_id']], array('controller' => 'patients', 'action' => 'view', $track['patient_id'])); 
				else echo '';
			?></td>
			<td><?php  if (!empty($statuses[$track['status_id']]))
				echo $statuses[$track['status_id']]; 
			?></td>
			<td><?php if (!empty($phones[$track['phone_id']])) echo $this->Html->link($phones[$track['phone_id']], array('controller' => 'phones', 'action' => 'view', $track['phone_id'])); else echo ''; ?></td>
			<td><?php echo $this->Html->link($track['rawreport_id'], array('controller' => 'rawreports', 'action' => 'view', $track['rawreport_id'])); ?></td>
			<td><?php echo $track['created']; ?></td>

		</tr>
	<?php }
	}
	if (!empty($rawreport['Track'])) {
		foreach ($rawreport['Track'] as $track) {
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<td><?php 
			
				
			if (!empty($locations[$track['location_id']])) {
				$loc = array_values($locations[$track['location_id']]);
				$lev = array_keys($locations[$track['location_id']]);
				echo $this->Html->link($levels[$lev[0]], array('controller' => 'levels', 'action' => 'view', $lev[0])); 
			}
			if (!empty($locations[$track['parent_location_id']])) {
				$ploc = array_values($locations[$track['parent_location_id']]);
				$plev = array_keys($locations[$track['parent_location_id']]);
				echo $this->Html->link($levels[$plev[0]], array('controller' => 'levels', 'action' => 'view', $plev[0])); 
			}
			?></td>
			<td><?php if (!empty($locations[$track['parent_location_id']])) echo $this->Html->link($ploc[0], array('controller' => 'locations', 'action' => 'view', $track['parent_location_id'])); else echo ''; ?></td>
			<td><?php if (!empty($locations[$track['location_id']])) echo $this->Html->link($loc[0], array('controller' => 'locations', 'action' => 'view', $track['location_id'])); else echo ''; ?></td>
			<td><?php  if (!empty($patients[$track['patient_id']]))
				echo $this->Html->link($patients[$track['patient_id']], array('controller' => 'patients', 'action' => 'view', $track['patient_id'])); 
				else echo '';
			?></td>
			<td><?php  if (!empty($statuses[$track['status_id']]))
				echo $statuses[$track['status_id']]; 
			?></td>
			<td><?php if (!empty($phones[$track['phone_id']])) echo $this->Html->link($phones[$track['phone_id']], array('controller' => 'phones', 'action' => 'view', $track['phone_id'])); else echo ''; ?></td>
			<td><?php echo $this->Html->link($track['rawreport_id'], array('controller' => 'rawreports', 'action' => 'view', $track['rawreport_id'])); ?></td>
			<td><?php echo $track['created']; ?></td>

		</tr>
	
	<?php }
	}
	
	if (!empty($phone['Track'])) {
		foreach ($phone['Track'] as $track) {
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		
		<td><?php 
			
				
			if (!empty($locations[$track['location_id']])) {
				$loc = array_values($locations[$track['location_id']]);
				$lev = array_keys($locations[$track['location_id']]);
				echo $this->Html->link($levels[$lev[0]], array('controller' => 'levels', 'action' => 'view', $lev[0])); 
			}
			if (!empty($locations[$track['parent_location_id']])) {
				$ploc = array_values($locations[$track['parent_location_id']]);
				$plev = array_keys($locations[$track['parent_location_id']]);
				echo $this->Html->link($levels[$plev[0]], array('controller' => 'levels', 'action' => 'view', $plev[0])); 
			}
			?></td>
			<td><?php if (!empty($locations[$track['parent_location_id']])) echo $this->Html->link($ploc[0], array('controller' => 'locations', 'action' => 'view', $track['parent_location_id'])); else echo ''; ?></td>
			<td><?php if (!empty($locations[$track['location_id']])) echo $this->Html->link($loc[0], array('controller' => 'locations', 'action' => 'view', $track['location_id'])); else echo ''; ?></td>
			<td><?php  if (!empty($patients[$track['patient_id']]))
				echo $this->Html->link($patients[$track['patient_id']], array('controller' => 'patients', 'action' => 'view', $track['patient_id'])); 
				else echo '';
			?></td>
			<td><?php  if (!empty($statuses[$track['status_id']]))
				echo $statuses[$track['status_id']]; 
			?></td>
			<td><?php if (!empty($phones[$track['phone_id']])) echo $this->Html->link($phones[$track['phone_id']], array('controller' => 'phones', 'action' => 'view', $track['phone_id'])); else echo ''; ?></td>
			<td><?php echo $this->Html->link($track['rawreport_id'], array('controller' => 'rawreports', 'action' => 'view', $track['rawreport_id'])); ?></td>
			<td><?php echo $track['created']; ?></td>

		</tr>
	<?php }
	}?>
	</table>
<?php endif; 
}
?>