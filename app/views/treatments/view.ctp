<div id="main">
<?php echo $javascript->link('prototype', false); ?>
<?php
	echo $crumb->getHtml('View Treatment', null, 'auto') ;
	echo '<br /><br />';
?> 
<div class="treatments view">
<h2><?php  __('Treatment');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<!--<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $treatment['Treatment']['id']; ?>
			&nbsp;
		</dd>
		-->
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Code'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $treatment['Treatment']['code']; ?>
			&nbsp;
		</dd>
		
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Description'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $treatment['Treatment']['description']; ?>
			&nbsp;
		</dd>
		
	
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Drugs'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php if (!empty($treatment['Drug'])):?>
			<table cellpadding = "0" cellspacing = "0">
			<?php
				$i = 1;
				foreach ($treatment['Drug'] as $drug):
					$class = null;
					if ($i++ % 2 == 0) {
						$class = ' class="altrow"';
					}
				?>
				<tr<?php echo $class;?>>
					<td><?php echo $drug['code'];
						echo ", ";
						echo $drug['presentation'];?></td>
				</tr>
			<?php endforeach; ?>
			</table>
		<?php endif; ?>
	</dd>	
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<?php if($access->check('Treatments') ) { ?>
		<li><?php echo $this->Html->link(__('Edit Treatment', true), array('action' => 'edit', $treatment['Treatment']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $treatment['Treatment']['id']), null, sprintf(__('Are you sure you want to delete %s?', true), $treatment['Treatment']['code'])); ?></li>
		<?php } ?>
	</ul>
</div>
<div class="related">
	

</div>
<div class="related">
<?php 	echo $this->Form->create('Config');
		$v = $ajax->remoteFunction(array('url' => 'view/' . $treatment['Treatment']['id'], 'update' => 'main', 'with' => 'Form.serialize(this.form)')); 
		echo $this->Form->input('limit', array('label' => 'Display limit', 'options' => array('10' => 10,'20' => 20,'50' => 50, '100' => 100), 'default' => 20, 'onChange' => $v));
		echo $this->Form->end(__('', true));
?>
<?php echo $this->element('related_stats'); ?>

</div>
</div>
