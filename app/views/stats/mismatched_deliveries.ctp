<div id="LoadingDiv" style="display:none;">
		<img src="../img/ajax-loader.gif" alt="" /></div>
<div id="aggregated_inventory">
<?php echo $javascript->link('prototype', false); ?>
<?php
	echo $crumb->getHtml('Mismatched Deliveries', null, '' ) ;
	echo '<br /><br />' ;
?> 
		
		
<div class="inventory index">
	<?php echo $this->element('mismatched_deliveries'); ?>
</div>
</div>