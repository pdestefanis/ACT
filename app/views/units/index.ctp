<div id="LoadingDiv" style="display:none;">
		<img src="../img/ajax-loader.gif" alt="" /></div>
<div id='update'>
<div id='update'>
<?php 
	echo $javascript->link('prototype', false); ?>
<?php
	echo $crumb->getHtml('Units', null, '' ) ;
	echo '<br /><br />' ;
?>
<div class="units index" id="units_index">
<?php echo $this->element('units_index'); ?>

</div>