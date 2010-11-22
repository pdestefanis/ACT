<?
	echo $crumb->getHtml('View Treatment', null, 'auto') ;
	echo '<br /><br />';
?> 
<div class="treatments view">
<h2><?php  __('Treatment');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<!--<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $drugsreatment['DrugsTreatment']['treatment_id']; ?>
			&nbsp;
		</dd>
		-->
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Code'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $drugsreatment['DrugsTreatment']['drug_id']; ?>
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
					<td><?php echo $drug['code'];?></td>
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
		<li><?php echo $this->Html->link(__('Edit Treatment', true), array('action' => 'edit', $treatment['Treatment']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Locations', true), array('controller' => 'locations', 'action' => 'index')); ?> </li>
				<li><?php echo $this->Html->link(__('List Phones', true), array('controller' => 'phones', 'action' => 'index')); ?> </li>
				<li><?php echo $this->Html->link(__('List Raw Reports', true), array('controller' => 'rawreports', 'action' => 'index')); ?> </li>
				<li><?php echo $this->Html->link(__('List Stats', true), array('controller' => 'stats', 'action' => 'index')); ?> </li>
				<li><?php echo $this->Html->link(__('List Treatments', true), array('controller' => 'treatments', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('List Drugs', true), array('controller' => 'drugs', 'action' => 'index')); ?> </li>
	</ul>
</div>
<div class="related">
	

</div>
<div class="related">
	<h3><?php __('Related Stats');?></h3>
	<?php if (!empty($treatment['Stat'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<!--<th><?php __('Id'); ?></th>-->
		<th><?php __('Report Type'); ?></th>
		<th><?php __('Quantity'); ?></th>
		<!--<th><?php __('Created'); ?></th>-->
		<!--<th><?php __('Drug Id'); ?></th>-->
		<!--<th><?php __('Treatment Id'); ?></th>-->
		<th><?php __('Rawreport Id'); ?></th>
		<th><?php __('Phone Id'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($treatment['Stat'] as $stat):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<!--<td><?php echo $stat['id'];?></td>-->
			<td><?php echo $stat['report_type'];?></td>
			<td><?php echo $stat['quantity'];?></td>
			<!--<td><?php echo $stat['created'];?></td>-->
			<!--<td><?php echo $stat['drug_id'];?></td>-->
			<!--<td><?php echo $stat['treatment_id'];?></td>-->
			<td><?php echo $stat['rawreport_id'];?></td>
			<td><?php echo $stat['phone_id'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'stats', 'action' => 'view', $stat['id'])); ?>
				
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

</div>
