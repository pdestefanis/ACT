<div id="sitems">
<?php echo $javascript->link('prototype', false); ?>
<?php
	echo $crumb->getHtml('Inventory by facility', null, '' ) ;
	echo '<br /><br />' ;
?> 
		
		
<div class="drugs index">
	<?php echo $this->element('facility'); ?>
</div>
</div>