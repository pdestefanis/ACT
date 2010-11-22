<div id="streatment">
<?php echo $javascript->link('prototype', false); ?>
<?php
	echo $crumb->getHtml('Treatments', null, '' ) ;
	echo '<br /><br />' ;
?> 
<div class="treatments index">
	<?php echo $this->element('streatments'); ?>
</div>
</div>
