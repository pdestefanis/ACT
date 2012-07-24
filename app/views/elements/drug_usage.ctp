<div class="search">
	<?php echo $this->Form->create('Search', array('default'=>false) );?>
	<?php
	echo $this->Form->input('search', array('label' => '', 'value' => $this->Form->value('search')));
	?>
	<?php
	echo $ajax->submit('Filter', array('url'=> '', 'update' => 'aggregated_inventory', 'loading' => '$(\'LoadingDiv\').show()', 'loaded' => '$(\'LoadingDiv\').hide()' ));
	?>
	<div class="title">
		<h2>
			<?php __('Drug Usage'); ?>
		</h2>
	</div>

	<table cellpadding="0" cellspacing="0" class="norow">
		<tr>
			<th><?php echo __("Facility", true);?></th>
			<th><?php echo __("Level", true);?></th>
			<?php 
			$numMonths = 5;
			for ($k = $numMonths-1; $k >= 0 ;$k--) {
				if ($k == 0)
					echo "<th>" .  __("Current Month", true) . "</th>";
				else
					echo "<th>" .  __("Month - " . $k, true) . "</th>";
				}?>

		</tr>

		<?php
		if (!empty($reportAll)) {
			$i = 1;
			foreach (array_keys($reportAll) as $loc) {
					$class = ' class=\'norow\'';
					if ($i++ % 2 != 0) {
						$class = ' class=\'altrow\'';
					}
					?>
					<tr <?php echo $class;?>> <?php
					if ($this->Form->value('search') != '' && (stripos($reportAll[$loc][1]['lname'], $this->Form->value('search')) === FALSE
							&& stripos($reportAll[$loc][1]['iname'], $this->Form->value('search')) === FALSE
							&& stripos($reportAll[$loc][1]['icode'], $this->Form->value('search')) === FALSE
							/* && $r['aggregated'] <= $this->Form->value('search')
							 && $r['own'] <= $this->Form->value('search') */
					) )
						continue;
					?>
					<td><?php
					echo $access->checkHtml('Locations/view', 'text', str_pad("", $reportAll[$loc][1]['level'], "-", STR_PAD_LEFT) . $reportAll[$loc][1]['lname'], '/locations/view/' . $loc);
					?>&nbsp;</td>
					<td><?php
					if (isset($lev[$reportAll[$loc][1]['level']]) && !empty ($lev[$reportAll[$loc][1]['level']]) )
						echo $lev[$reportAll[$loc][1]['level']];
					else
						echo $reportAll[$loc][1]['level'];
					?>&nbsp;</td>
					
					<?php 
					$k = $numMonths-1;
						
					while ($k >= 0) {
						?>
														<td class='number'><?php 
							if (!empty($report[$numMonths-$k-1][$loc])){
								foreach ($report[$numMonths-$k-1][$loc] as $rep )
									echo $rep['Total']['At Patient']; 
							} else { echo  "-"; }
							?>&nbsp;</td>
								
							<?php
							$k--;
					}
				$k = $numMonths-1;
				
				?> 
				</tr>
				<?php 
			}// end foreach (array_keys($reportAll) as $loc) {
		}//end if (!empty($reportAll)) {

		?>
	</table>
	<?php




	?>
	</table>