<?php echo $javascript->link('prototype', false); ?>
<?php
echo $crumb->getHtml('View Status', null, 'auto' ) ;
echo '<br /><br />' ;
?> 
<div class="statuses view">
<h2><?php  __('Status');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Name'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $status['Status']['name']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Status', true), array('action' => 'edit', $status['Status']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Status', true), array('action' => 'delete', $status['Status']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $status['Status']['name'])); ?> </li>
	</ul>
</div>
