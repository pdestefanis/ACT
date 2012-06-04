	<div class="search">
	<?php	echo $this->Form->create('Search', array('default'=>false) );?>
		<?php
			echo $this->Form->input('search', array('label' => '', 'value' => $this->Form->value('search')));
		?>
	<?php  
		echo $ajax->submit('Filter', array('url'=> '', 'update' => 'facility_inventory', 'loading' => '$(\'LoadingDiv\').show()', 'loaded' => '$(\'LoadingDiv\').hide()' )); 
	?>
</div>
	<div class="title">
	<h2><?php __('Inventory by Site');  ?></h2>
</div>

	
	<table cellpadding="0" cellspacing="0" class="norow">
	
		<tr>
			<th><?php echo __("Site Name", true);?></th>
			<th><?php echo __("Upstream Site", true);?></th>
			<th><?php echo __("Number of Kits", true);?></th>
			<th><?php echo __("Last Delivery Report", true);?></th>
		</tr>

	
	
	<?php	
	$i = 1;
	if (!empty($report)) {
	foreach (array_keys($report) as $loc) :
	
		$class = ' class=\'norow\'';
		if ($i++ % 2 != 0) {
			$class = ' class=\'altrow\'';
		} 
	
		if (!empty($report[$loc])) {

			foreach ($report[$loc] as $r) { 
				if ($r['Assigned'] == 0 && $r['At Patient'] == 0 && $r['Expired'] == 0)
					continue;
				?>
				<tr <?php echo $class;?>>
					
					<td><?php 
						echo $access->checkHtml('Locations/view', 'text', $r['lname'], '/locations/view/' . $loc );
						//echo $this->Html->link($r['lname'], array('controller' => 'locations', 'action' => 'view', $loc)); ?>&nbsp;</td>
					<td><?php 
						if ($r['parent'] != 0)
								echo $access->checkHtml('Locations/view', 'text', $allLocations[$r['parent']], '/locations/view/' . $r['parent'] );
							//echo $this->Html->link($allLocations[$r['parent']], array('controller' => 'locations', 'action' => 'view', $r['parent'])); ?>&nbsp;</td>
					<td><table cellpadding="0" cellspacing="0" class="norow"><?php 
							echo "<tr><td>" . __("Units Assigned", true). "</td>" ;
						?>
						<td class='number'><?php 
							echo $r['Assigned'] . "</td></tr>";  
							echo "<tr><td>" . __("Units Delivered to Patients", true). "</td>" ;
						?>
						<td class='number'><?php 
							echo $r['At Patient'] . "</td></tr>";  
							echo "<tr><td>" . __("Units Discarded", true). "</td>" ;
						?>
						<td class='number'><?php 
							echo $r['Expired'] . "</td></tr>";  
							?>
					</table>
					</td>
					<td><?php echo $access->checkHtml('Stats/view', 'text', $r['screated'], '/stats/view/' . $r['sid'] );
					 ?>&nbsp;</td>
					
				</tr>
		<?php } 
		}
		
	endforeach;
		
	}
	?> </table> 
	<?php




	?>
