<?php
echo $crumb->getHtml('Add Drug', null, 'auto' ) ;
echo '<br /><br />' ;

?> 
<div class="drugs form">
<?php echo $this->Form->create('Drug');?>
	<fieldset>
 		<legend><?php __('Add Drug'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('code');
		echo $this->Form->input('presentation', array ('options' => array('Oral solution' => 'Oral solution', 'Tablets' => 'Tablets')));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
