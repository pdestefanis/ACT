<?php echo $javascript->link('prototype', false); ?>
<?php
echo $crumb->getHtml('View Patient', null, 'auto' ) ;
echo '<br /><br />' ;
?> 
<div class="patients view">
<h2><?php __('Patient');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Number'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $patient['Patient']['number']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Consent'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo ($patient['Patient']['consent']?'Yes':'No'); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Registered at'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php 
				if (isset($locations[$patient['Patient']['location_id']]))
				echo $locations[$patient['Patient']['location_id']]; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Current Kit'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php 
				if ($units != -1)
					foreach ($units as $unit)
						echo $access->checkHtml('Units/view', 'link', $allUnits[$unit] ,'/units/view/' . $unit) . "</br>";
				 ?>
			&nbsp;
		</dd>
	</dl>
</div>

<div class="actions">
	<?php 
	echo $access->checkHtml('Patients/delete', 'html', '<h3>Actions</h3>','' ); ?>
	<ul>
		<li><?php echo $access->checkHtml('Patients/delete', 'delete', 'Delete','delete/' . $patient['Patient']['id'], 'delete', $patient['Patient']['number'] ); ?></li>
		<li><?php echo $access->checkHtml('Patients/edit', 'link', 'Edit','edit/' . $patient['Patient']['id']); ?></li>
	</ul>
</div>