	<div class="search">
	<?php 
		
	?>
	<h2><?php __('Kits Levels');  ?></h2>
	<?php	echo $this->Form->create('Search');?>
	<?php
		echo $this->Form->input('search', array('label' => ''));
	?>
<?php //echo $this->Form->end(__('Submit', true)); 
	echo $ajax->submit('Filter', array('url' => '' , 'update' => 'tkits')); 
?>
</div>
<?php 
	if (empty($listkits)) {
		echo "Search didn't match any fields";
	} else {
?>
	
	<table cellpadding="0" cellspacing="0" class="norow">
	
		<tr>
			<th><?php echo "Location";?></th>
			<th><?php echo "At Location";?></th>
			<th><?php echo "In Transit";?></th>
			<th><?php echo "At patient";?></th>
			<th><?php echo "Expired";?></th>
			<th><?php echo "Destroyed";?></th>
	</tr>

	
	
	<?php	

	$i = 1;
	foreach ($locations as $loc) :
	
		$class = ' class=\'norow\'';
		if ($i++ % 2 != 0) {
			$class = ' class=\'altrow\'';
		} 
	
		if (!empty($listkits[$loc['locations']['id']])) {

			//for ($j = 0; $j < count($listkits[$loc['locations']['id']]); $j++) { 
			?>
				<tr <?php echo $class;?>>
					
					<td><?php 
						if ($loc['locations']['deleted'] == 0)
						echo $this->Html->link($listkits[$loc['locations']['id']]['name'], array('controller' => 'locations', 'action' => 'view', $listkits[$loc['locations']['id']]['id'])); 
						else
							echo 'Deleted: ' .  $listkits[$loc['locations']['id']]['name'];
						?>&nbsp;</td>
					<td ><?php echo $listkits[$loc['locations']['id']]['count']['atLocation']
									+ $listkits[$loc['locations']['id']]['count']['<-atPatient']
									+ $listkits[$loc['locations']['id']]['count']['notDelivered']
							?>&nbsp;</td>
					<td ><?php echo $listkits[$loc['locations']['id']]['count']['notAccepted'];
							?>&nbsp;</td>
					<td ><?php echo $listkits[$loc['locations']['id']]['count']['atPatient->'];
							?>&nbsp;</td>
					<td ><?php echo $listkits[$loc['locations']['id']]['count']['expired'];
							?>&nbsp;</td>
					<td ><?php echo $listkits[$loc['locations']['id']]['count']['destroyed'];
							?>&nbsp;</td>
				</tr>
		<?php //} 
		}
		
	endforeach;
}
	?>
	</table>
