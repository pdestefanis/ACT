<?php
echo $crumb->getHtml('Viewing Raw Report', null, 'auto' ) ;
echo '<br /><br />' ;

?> 
<div class="rawreports view">
<h2><?php  __('Rawreport');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<!--<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $rawreport['Rawreport']['id']; ?>
			&nbsp;
		</dd>
		-->
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Raw Message'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $rawreport['Rawreport']['raw_message']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Message Code'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $rawreport['Rawreport']['message_code']; ?>
			&nbsp;
		</dd>
		
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Phone'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php
				if ($rawreport['Phone']['deleted'] == 0 )
					echo $this->Html->link($rawreport['Phone']['name'], array('controller' => 'phones', 'action' => 'view', $rawreport['Phone']['id']));
				else
					echo 'Deleted: ' .  $rawreport['Phone']['name'];
				?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Rawreport', true), array('action' => 'edit', $rawreport['Rawreport']['id'])); ?> </li>
		
	</ul>
</div>
<div class="related">

<?php echo $this->element('related_stats'); ?>

</div>

	
</div>
