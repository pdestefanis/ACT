<div class="search">
<?php	echo $this->Form->create('Search', array('default'=>false) );?>
	<?php
		echo $this->Form->input('search', array('label' => '', 'value' => $this->Form->value('search')));
	?>
<?php  
	echo $ajax->submit('Filter', array('url'=> '', 'update' => 'aggregated_inventory', 'loading' => '$(\'LoadingDiv\').show()', 'loaded' => '$(\'LoadingDiv\').hide()' )); 
?>
<div class="title">
	<h2><?php __('Kits Expired/Discarded');  ?></h2>
</div>

	<table cellpadding="0" cellspacing="0" class="norow">
	
		<tr>
			<th><?php echo __("Facility Name", true);?></th>
			<th><?php echo __("Upstream Facility", true);?></th>
			<th><?php echo __("Number of Kits", true);?></th>
			
			
		</tr>

	
	
	<?php
	//echo "<pre>" . print_r($report, true) . "</pre>";
	if (!empty($report)) {
	$i = 1;
	foreach (array_keys($report) as $loc) :
		
		$class = ' class=\'norow\'';
		if ($i++ % 2 != 0) {
			$class = ' class=\'altrow\'';
		} 
		
		if (!empty($report[$loc])) {
			foreach ($report[$loc] as $r) { 
				if ($this->Form->value('search') != ''  && (stripos($r['lname'], $this->Form->value('search')) === FALSE
						&& stripos($r['iname'], $this->Form->value('search')) === FALSE 
						&& stripos($r['icode'], $this->Form->value('search')) === FALSE 
						/* && $r['aggregated'] <= $this->Form->value('search')
						&& $r['own'] <= $this->Form->value('search')  */
						) )
					continue;
			?>
				<tr <?php echo $class;?>>
					
					<td><?php 
						echo $access->checkHtml('Locations/view', 'text', str_pad("", $r['level'], "-", STR_PAD_LEFT) . $r['lname'], '/locations/view/' . $loc);
						?>&nbsp;</td>
					<td><?php 
						if ($r['level'] != 0 && isset($allLocations[$r['parent']]))
							echo $access->checkHtml('Locations/view', 'text',  $allLocations[$r['parent']], '/locations/view/' . $r['parent']);
						
						?>&nbsp;</td>
						<td class='number'><?php echo $r['Expired']; ?>&nbsp;</td>


				</tr>
		<?php 
			} 
		}
		
	endforeach;
	}	
		
	?> </table> 
	<?php




	?>
	</table>
