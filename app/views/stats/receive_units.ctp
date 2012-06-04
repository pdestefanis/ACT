<?php echo $javascript->link('jquery.min', false); ?>
<?php echo $javascript->link('prototype', false); ?>
<?php //echo $javascript->link('common', false); ?>
<script type="text/javascript">
//<![CDATA[
    jQuery(document).ready(function($){
	$('.flash_success').animate({opacity: 1.0}, 3000).fadeOut();
});   
//]]>
</script>	

<div id="assign" >
<?php
	echo $crumb->getHtml(__('Receive Unit', true), null, 'auto' ) ;
	echo '<br /><br />' ;
	
?> 

<div class ="form">
<?php echo $this->element('receive_units'); ?>
</div>
</div>