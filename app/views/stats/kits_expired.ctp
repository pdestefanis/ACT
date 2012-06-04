<div id="LoadingDiv" style="display:none;">
		<img src="../img/ajax-loader.gif" alt="" /></div>
<div id="aggregated_inventory">
<?php echo $javascript->link('prototype', false); ?>
<?php
	echo $crumb->getHtml('Kits Expired', null, '' ) ;
	echo '<br /><br />' ;
?> 
		
		
<div class="inventory index">
	<?php echo $this->element('kits_expired'); ?>
</div>
</div>