<div class="units view">
<h2><?php  __('Kit');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Code'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $unit['Unit']['code']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Batch Number'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $batches[$unit['Unit']['batch_id']]; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $access->checkHtml('Units/edit', 'link', 'Edit Kit','edit/' . $unit['Unit']['id'] ); ?></li>
		<li><?php echo $access->checkHtml('Units/delete', 'delete', 'Delete','delete/' . $unit['Unit']['id'], 'delete', $unit['Unit']['code'] ); ?></li>
	</ul>
</div>

