<script type="text/javascript">
//<![CDATA[
    jQuery(document).ready(function($){
	$('.flash_success').animate({opacity: 1.0}, 3000).fadeOut();
});
//]]>
</script>
<?php 
	echo $this->Form->input('batch_id', array('options' => $batches, 
												'id' => 'UnitBatchId', 
												'name' => 'data[Unit][batch_id]',
												//'empty' => true,
											));
	?>
<div class="batches ajaxForm">
<?php echo $this->Form->create('Batch');?>
	<fieldset>
		<legend><?php __('Add Batch'); ?></legend>
	<?php
		//echo $this->Form->input('expire_date');
		echo $this->Form->input('batch_number');
		echo $this->Form->input('expire_date', array(
							        'label' => __('Expiry date', true),
							        'type' => 'date',
							        'dateFormat' => 'YMD',
							        'minYear' => date('Y') - 1,
							        'maxYear' => date('Y') +1,
    								)
    						);
		
	?>
	</fieldset>
<?php echo $ajax->submit('Add', array('url' => '/batches/addAjax', 'update' => 'batchList')); ?>
</div>