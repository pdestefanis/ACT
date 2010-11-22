<div id="main">
<?php echo $javascript->link('prototype', false); ?>
<?php
	echo $crumb->getHtml('Viewing Phone',  null, 'auto' ) ;
	echo '<br /><br />' ;
?>

<div class="phones view">
<h2><?php  __('Phone');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<!--<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $phone['Phone']['id']; ?>
			&nbsp;
		</dd>
		-->
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Name'); ?></dt>
				<dd<?php if ($i++ % 2 == 0) echo $class;?>>
					<?php echo $phone['Phone']['name']; ?>
					&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Phonenumber'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $phone['Phone']['phonenumber']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Active'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo ($phone['Phone']['active']?'Active':'Inactive'); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Location'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($phone['Location']['name'], array('controller' => 'locations', 'action' => 'view', $phone['Location']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Phone', true), array('action' => 'edit', $phone['Phone']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $phone['Phone']['id']), null, sprintf(__('Are you sure you want to delete %s?', true), $phone['Phone']['name'])); 
		?></li>
	</ul>
</div>
<div class="related">
<?php echo $this->Form->create('Config', array('action' => 'view/' . $phone['Phone']['id']));
		$v = $ajax->remoteFunction(array('url' => 'view/' . $phone['Phone']['id'], 'update' => 'main', 'with' => 'Form.serialize(this.form)')); 
		echo $this->Form->input('limit', array('label' => 'Display limit', 'options' => array('10' => 10,'20' => 20,'50' => 50, '100' => 100), 'default' => 20, 'onChange' => $v));
		echo $this->Form->end(__('', true));
	?>
	<h3><?php __('Related Rawreports');?></h3>
	<?php if (!empty($phone['Rawreport'])):?>
	
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<!--<th><?php __('Id'); ?></th>-->
		<th><?php __('Raw Message'); ?></th>
		<th><?php __('Message Code'); ?></th>
		<!--<th><?php __('Created'); ?></th>-->
		<!--<th><?php __('Phone Id'); ?></th>-->
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($phone['Rawreport'] as $rawreport):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<!--<td><?php echo $rawreport['id'];?></td>-->
			<td><?php echo $rawreport['raw_message'];?></td>
			<td><?php echo $rawreport['message_code'];?></td>
			<!--<td><?php echo $rawreport['created'];?></td>
			<td><?php echo $rawreport['phone_id'];?></td>-->
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'rawreports', 'action' => 'view', $rawreport['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'rawreports', 'action' => 'edit', $rawreport['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'rawreports', 'action' => 'delete', $rawreport['id']), null, sprintf(__('Are you sure you want to delete %s?', true), $rawreport['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		
	</div>
</div>

<div class="related">
<?php echo $this->element('related_stats'); ?>

</div>


</div>
