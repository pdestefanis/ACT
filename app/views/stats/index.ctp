﻿<div id="LoadingDiv" style="display:none;">
		<img src="../img/ajax-loader.gif" alt="" /></div>
<div id='update'>
<?php 
	echo $javascript->link('prototype', false); ?>
<?php
	echo $crumb->getHtml('Updates', null, '' ) ;
	echo '<br /><br />' ;
?>
<div class="updates index" id="updates_index">
<?php echo $this->element('updates_index'); ?>
</div>
