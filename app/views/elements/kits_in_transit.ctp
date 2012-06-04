<div class="search">
<?php	echo $this->Form->create('Search', array('default'=>false) );?>
	<?php
		echo $this->Form->input('search', array('label' => '', 'value' => $this->Form->value('search')));
	?>
<?php  
	echo $ajax->submit('Filter', array('url'=> '', 'update' => 'aggregated_inventory', 'loading' => '$(\'LoadingDiv\').show()', 'loaded' => '$(\'LoadingDiv\').hide()' )); 
?>
<div class="title">
	<h2><?php __('Kits In Transit');  ?></h2>
</div>

	<table cellpadding="0" cellspacing="0" class="norow">
	
		<tr>
			<th><?php echo __("Receiving Facility", true);?></th>
			<th><?php echo __("Number of Kits Sent (kits arriving)", true);?></th>
			
			
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
						
						<td class='number'><?php echo $r['Sent to']  ; ?>&nbsp;</td>


				</tr>
		<?php } 
		}
		
	endforeach;
	}	
		
	?> </table> 
	<?php




	?>
	</table>
