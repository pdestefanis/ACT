<div id="LoadingDiv" style="display:none;">
		<img src="../img/ajax-loader.gif" alt="" /></div>
<div id="aggregated_inventory">
<?php echo $javascript->link('prototype', false); ?>
<?php
	echo $crumb->getHtml(__('Kits In Transit', true), null, '' ) ;
	echo '<br /><br />' ;
?> 
		
		
<div class="inventory index">
	<?php echo $this->element('kits_in_transit'); ?>
</div>
</div>