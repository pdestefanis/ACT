<script type="text/javascript">
//<![CDATA[
    jQuery(document).ready(function($){
	$('.flash_success').animate({opacity: 1.0}, 3000).fadeOut();
});
//]]>
</script>
	<table cellpadding="0" cellspacing="0">
	<tr>
	<td><?php 
		//$drugskittypes = $this->requestAction('kittypes/edit');
		$i = 0;
		if (!empty($drugskittypes[0]['Kittype']))
			echo "Drugs included in " . $drugskittypes[0]['Kittype']['code'];
		else 
			echo "This kittype does not contain any drugs.";
		?>&nbsp;</td> <td> </td> 
		
		<?php
		foreach ($drugskittypes as $drugskittype):
			$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	</tr>
	<tr<?php echo $class;?>>
		<td><?php
		echo $this->Html->link($drugskittype['Drug']['name'] . ", " . $drugskittype['Drug']['presentation'], array('controller' => 'drugs', 'action' => 'view', $drugskittype['Drug']['id']));	
		
		?>
		</td>
		<td class="actions">
			<?php 
			echo $ajax->link(__('Delete', true), array('controller' => 'drugs_kittypes', 'action' => 'delete',$drugskittype['DrugsKittype']['id']), array('update' => 'drugList'), sprintf(__('Are you sure you want to delete %s?', true), $drugskittype['Drug']['name'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	
