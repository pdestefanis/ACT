<?php
echo $crumb->getHtml('Viewing Statistic', null, 'auto' ) ;
echo '<br /><br />' ;

?> 
<div class="stats view">
<h2><?php  __('Stat');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		
		<dt<?php if ($i % 2 == 0) echo $class;?>>
			<?php if ($stat['Drug']['id'] != 0 && $stat['Drug']['id'] != null){ ?>
				<?php __('Quantity'); ?></dt>
				<?php } else  {?>
					<?php __('People'); ?></dt>
			<?php } ?>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $stat['Stat']['quantity']; ?>
			&nbsp;
		</dd>
		
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Report received on'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $stat['Stat']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Drug'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($stat['Drug']['name'], array('controller' => 'drugs', 'action' => 'view', $stat['Drug']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Treatment'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($stat['Treatment']['code'], array('controller' => 'treatments', 'action' => 'view', $stat['Treatment']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Rawreport'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($stat['Rawreport']['raw_message'], array('controller' => 'rawreports', 'action' => 'view', $stat['Rawreport']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Phone'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
		
			<?php
				if ($stat['Phone']['deleted'] == 0 )
					echo $this->Html->link($stat['Phone']['name'], array('controller' => 'phones', 'action' => 'view', $stat['Phone']['id']));
				else
					echo 'Deleted: ' .  $stat['Phone']['name'];
				?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Location'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
	
			<?php
				if ($stat['Location']['deleted'] == 0 )
					echo $this->Html->link($stat['Location']['name'], array('controller' => 'phones', 'action' => 'view', $stat['Location']['id']));
				else
					echo 'Deleted: ' .  $stat['Location']['name'];
				?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Stat', true), array('action' => 'edit', $stat['Stat']['id'])); ?> </li>
		
	</ul>
</div>
