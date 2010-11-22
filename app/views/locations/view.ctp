<?php
echo $crumb->getHtml('Viewing locations', null, 'auto' ) ;
echo '<br /><br />' ;

?> 
<div class="locations view">
<h2><?php  __('Location');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<!--
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $location['Location']['id']; ?>
			&nbsp;
		</dd>
		-->
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Name'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $location['Location']['name']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Shortname'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $location['Location']['shortname']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Latitude'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $location['Location']['locationLatitude']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Longitude'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $location['Location']['locationLongitude']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<?php if($access->check('Drugs') ) { ?>
		<li><?php echo $this->Html->link(__('Edit Location', true), array('action' => 'edit', $location['Location']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $location['Location']['id']), null, sprintf(__('Are you sure you want to delete %s?', true), $location['Location']['name'])); ?></li>
		<?php }  ?>
	</ul>
</div>
<div class="related">
	<h3><?php 
		if($access->check('Phones') ) {
		__('Related Phones');?></h3>
	<?php if (!empty($location['Phone'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		 <th><?php __('Name'); ?></th> 
		<th><?php __('Phonenumber'); ?></th>
		<th><?php __('Active'); ?></th>
		<!--<th><?php __('Location Id'); ?></th> -->
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($location['Phone'] as $phone):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
		<?php if ($phone['deleted'] == 0)  {?>
			<td><?php echo $phone['name'];?></td>
			<td><?php echo $phone['phonenumber'];?></td>
			<td><?php echo ($phone['active']?'Active':'Inactive');?></td>
			<!-- <td><?php echo $phone['location_id'];?></td> -->
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'phones', 'action' => 'view', $phone['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'phones', 'action' => 'edit', $phone['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'phones', 'action' => 'delete', $phone['id']), null, sprintf(__('Are you sure you want to delete %s?', true), $phone['name'])); ?>
			</td>
			<?php } ?>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; 

}?>

</div>
