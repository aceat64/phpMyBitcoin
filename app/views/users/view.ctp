<div class="users view">
<h2><?php  __('User');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Username'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $user['User']['username']; ?>
			&nbsp;
		</dd>
	</dl>

	<h2><?php __('Logs');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('Date','created');?></th>
			<th><?php echo $this->Paginator->sort('description');?></th>
			<th><?php echo $this->Paginator->sort('ip');?></th>
			<th><?php echo $this->Paginator->sort('action');?></th>
			<th><?php echo $this->Paginator->sort('change');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($logs as $log):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $log['Log']['created']; ?>&nbsp;</td>
		<td><?php echo $log['Log']['description']; ?>&nbsp;</td>
		<td><?php echo $log['Log']['ip']; ?>&nbsp;</td>
		<td><?php echo $log['Log']['action']; ?>&nbsp;</td>
		<td><?php echo $log['Log']['change']; ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>

</div>
<div class="actions">
	<h3><?php __('This User'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('View User', true), array('action' => 'view', $user['User']['id'])); ?></li>
		<li><?php echo $this->Html->link(__('Edit User', true), array('action' => 'edit', $user['User']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete User', true), array('action' => 'delete', $this->Form->value('User.id')), null, sprintf(__('Are you sure you want to delete %s?', true), $this->Form->value('User.username'))); ?></li>
	</ul>
	<h3><?php __('Nodes'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('List Nodes', true), array('controller'=>'nodes','action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Node', true), array('controller'=>'nodes','action' => 'add')); ?> </li>
	</ul>
	<h3><?php __('Users'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('List Users', true), array('controller'=>'users','action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User', true), array('controller'=>'users','action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Logout', true), array('controller'=>'users','action' => 'logout')); ?> </li>
	</ul>
	<h3><?php __('Logging'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('View Logs', true), array('controller'=>'logs','action' => 'index')); ?> </li>
	</uL>
</div>
