<div class="nodes index">
	<h2><?php __('Nodes');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('balance');?></th>
			<th><?php echo $this->Paginator->sort('blocks');?></th>
			<th><?php echo $this->Paginator->sort('Peers','connections'); // We use the title "Peers" here because it is much shorter than "connections" ?></th>
			<th><?php echo $this->Paginator->sort('version');?></th>
			<th><?php echo $this->Paginator->sort('generate');?></th>
			<th><?php echo $this->Paginator->sort('khps');?></th>
			<th><?php echo $this->Paginator->sort('Pending','pending_blocks');?></th>
			<th><?php echo $this->Paginator->sort('Generated','generated_blocks');?></th>
			<th><?php echo $this->Paginator->sort('last_update');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($nodes as $node):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $this->Html->link($node['Node']['name'], array('action' => 'view', $node['Node']['id'])); ?>&nbsp;</td>
		<?php if ($node['Node']['status'] == 'online'): ?>
		<td><?php echo $number->precision($node['Node']['balance'],2); ?>&nbsp;</td>
		<td><?php echo $node['Node']['blocks']; ?>&nbsp;</td>
		<td><?php echo $node['Node']['connections']; ?>&nbsp;</td>

		<?php if ($node['Node']['version'] === NULL): ?>
		<td>n/a</td>
		<?php else: ?>
		<td><?php echo $node['Node']['version']; ?>&nbsp;</td>
		<?php endif; ?>

		<?php if ($node['Node']['generate'] == 1): ?>
		<td>True&nbsp;</td>
		<?php else: ?>
		<td>False&nbsp;</td>
		<?php endif; ?>

		<?php if ($node['Node']['khps'] === NULL): ?>
		<td>n/a</td>
		<?php else: ?>
		<td><?php echo $number->precision($node['Node']['khps'],0); ?>&nbsp;</td>
		<?php endif; ?>

		<?php if ($node['Node']['pending_blocks'] === NULL): ?>
		<td>n/a</td>
		<?php else: ?>
		<td><?php echo $node['Node']['pending_blocks']; ?>&nbsp;</td>
		<?php endif; ?>

		<?php if ($node['Node']['generated_blocks'] === NULL): ?>
		<td>n/a</td>
		<?php else: ?>
		<td><?php echo $node['Node']['generated_blocks']; ?>&nbsp;</td>
		<?php endif; ?>

		<?php else: ?>
		<td colspan=8><?php echo $node['Node']['status']; ?>&nbsp;</td>
		<?php endif; ?>
		<td><?php echo $time->timeAgoInWords($node['Node']['last_update']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $node['Node']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $node['Node']['id']), null, sprintf(__('Are you sure you want to delete %s?', true), $node['Node']['name'])); ?>
		</td>
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
