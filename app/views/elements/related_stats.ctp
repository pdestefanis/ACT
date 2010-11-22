	
	<?php 
	if($access->check('Stats/index') ) {
		?> <h3><?php __('Related Reports');?></h3> 
		<?php 
		if (!empty($drug['Stat']) || !empty($treatment['Stat']) || !empty($rawreport['Stat']) || !empty($phone['Stat'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Quantity'); ?></th>
		<th><?php __('Location'); ?></th>
		<th><?php __('Phone'); ?></th>
		<th><?php __('Raw Report'); ?></th>

	</tr>
	<?php
		$i = 0;
	if (!empty($drug['Stat'])) {
		foreach ($drug['Stat'] as $stat) {
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>

			<td><?php echo $this->Html->link($stat['quantity'], array('controller' => 'stats', 'action' => 'view', $stat['id'])); ?></td>
			<td><?php if (!empty($locations[$stat['location_id']])) echo $this->Html->link($locations[$stat['location_id']], array('controller' => 'locations', 'action' => 'view', $stat['location_id'])); else echo 'Location deleted.'; ?></td>
			<td><?php if (!empty($phones[$stat['phone_id']])) echo $this->Html->link($phones[$stat['phone_id']], array('controller' => 'phones', 'action' => 'view', $stat['phone_id'])); else echo 'Phone deleted.'; ?></td>
			<td><?php echo $this->Html->link($stat['rawreport_id'], array('controller' => 'rawreports', 'action' => 'view', $stat['rawreport_id'])); ?></td>

		</tr>
<?php }
	}
	if (!empty($treatment['Stat'])) {
		foreach ($treatment['Stat'] as $stat) {
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>

			<td><?php echo $this->Html->link($stat['quantity'], array('controller' => 'stats', 'action' => 'view', $stat['id'])); ?></td>
			<td><?php if (!empty($locations[$stat['location_id']])) echo $this->Html->link($locations[$stat['location_id']], array('controller' => 'locations', 'action' => 'view', $stat['location_id'])); else echo 'Location deleted.'; ?></td>
			<td><?php if (!empty($phones[$stat['phone_id']])) echo $this->Html->link($phones[$stat['phone_id']], array('controller' => 'phones', 'action' => 'view', $stat['phone_id'])); else echo 'Phone deleted.'; ?></td>
			<td><?php echo $this->Html->link($stat['rawreport_id'], array('controller' => 'rawreports', 'action' => 'view', $stat['rawreport_id'])); ?></td>

		</tr>
	<?php }
	}
	if (!empty($rawreport['Stat'])) {
		foreach ($rawreport['Stat'] as $stat) {
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			
			<td><?php echo $this->Html->link($stat['quantity'], array('controller' => 'stats', 'action' => 'view', $stat['id'])); ?></td>
			<td><?php if (!empty($locations[$stat['location_id']])) echo $this->Html->link($locations[$stat['location_id']], array('controller' => 'locations', 'action' => 'view', $stat['location_id'])); else echo 'Location deleted.'; ?></td>
			<td><?php if (!empty($phones[$stat['phone_id']])) echo $this->Html->link($phones[$stat['phone_id']], array('controller' => 'phones', 'action' => 'view', $stat['phone_id'])); else echo 'Phone deleted.'; ?></td>
			<td><?php echo $this->Html->link($stat['rawreport_id'], array('controller' => 'rawreports', 'action' => 'view', $stat['rawreport_id'])); ?></td>

		</tr>
	<?php }
	}
	
	if (!empty($phone['Stat'])) {
		foreach ($phone['Stat'] as $stat) {
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		
		<tr<?php echo $class;?>>

			<td><?php echo $this->Html->link($stat['quantity'], array('controller' => 'stats', 'action' => 'view', $stat['id'])); ?></td>
			<td><?php if (!empty($locations[$stat['location_id']])) echo $this->Html->link($locations[$stat['location_id']], array('controller' => 'locations', 'action' => 'view', $stat['location_id'])); else echo 'Location deleted.'; ?></td>
			<td><?php if (!empty($phones[$stat['phone_id']])) echo $this->Html->link($phones[$stat['phone_id']], array('controller' => 'phones', 'action' => 'view', $stat['phone_id'])); else echo 'Phone deleted.'; ?></td>
			<td><?php echo $this->Html->link($stat['rawreport_id'], array('controller' => 'rawreports', 'action' => 'view', $stat['rawreport_id'])); ?></td>

		</tr>
	<?php }
	}?>
	</table>
<?php endif; 
}
?>