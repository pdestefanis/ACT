<div id="sdrug">
<?php echo $javascript->link('prototype', false); ?>
<?php
	echo $crumb->getHtml('Drug Stock', null, '' ) ;
	echo '<br /><br />' ;
?> 
		
		
<div class="drugs index">
	<?php echo $this->element('sdrugs'); ?>
</div>
</div>