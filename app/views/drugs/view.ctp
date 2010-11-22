<div id="main">
<?php echo $javascript->link('prototype', false); ?>
<?php
echo $crumb->getHtml('View Drug', null, 'auto' ) ;
echo '<br /><br />' ;

?> 

<div class="drugs view">
<h2><?php  __('Drug');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<!--<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $drug['Drug']['id']; ?>
			&nbsp;
		</dd>
		-->
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Name'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $drug['Drug']['name']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Code'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $drug['Drug']['code']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Presentation'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $drug['Drug']['presentation']; ?>
			&nbsp;
		</dd>
	</dl>
	<br/>
	<?php __('This drug is included in these treatments');?>
	<?php 
	
	if (!empty($drug['Treatment'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		
		<th><?php __('Code'); ?></th>
		
		
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($drug['Treatment'] as $treatment):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			
			<td><?php echo $treatment['code'];?></td>
			
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'treatments', 'action' => 'view', $treatment['id'])); ?>
				
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<?php if($access->check('Treatments') ) { ?>
		<li><?php echo $this->Html->link(__('Edit Drug', true), array('action' => 'edit', $drug['Drug']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Drug', true), array('action' => 'delete', $drug['Drug']['id']), null, sprintf(__('Are you sure you want to delete %s?', true), $drug['Drug']['code'])); ?> </li>
		<?php }  ?>
				
	</ul>
</div>

<div class="related">
<?php echo $this->Form->create('Config', array('action' => 'view/' . $drug['Drug']['id']));
		$v = $ajax->remoteFunction(array('url' => 'view/' . $drug['Drug']['id'], 'update' => 'main', 'with' => 'Form.serialize(this.form)')); 
		echo $this->Form->input('limit', array('label' => 'Display limit', 'options' => array('10' => 10,'20' => 20,'50' => 50, '100' => 100), 'default' => 20, 'onChange' => $v));
		echo $this->Form->end(__('', true));
	?>
<?php echo $this->element('related_stats'); ?>

</div>

</div>
