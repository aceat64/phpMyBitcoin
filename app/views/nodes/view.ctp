<div class="nodes view">
<h2><?php  __('Node');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Name'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $node['Node']['name']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('hostname'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $node['Node']['hostname']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Port'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $node['Node']['port']; ?>
			&nbsp;
		</dd>
		<?php if(!empty($node['Node']['uri'])): ?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Uri'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $node['Node']['uri']; ?>
			&nbsp;
		</dd>
		<?php endif; ?>
		<?php if(!empty($node['Node']['proxy'])): ?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Proxy'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $node['Node']['proxy']; ?>
			&nbsp;
		</dd>
		<?php endif; ?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Username'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $node['Node']['username']; ?>
			&nbsp;
		</dd>
		<?php if($node['Node']['status'] == 'online'): ?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Balance'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $number->precision($node['Node']['balance'],2); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Blocks'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $node['Node']['blocks']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Connections'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $node['Node']['connections']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Version'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php if($node['Node']['version'] === NULL): ?>
			n/a
			<?php else: ?>
			<?php echo $node['Node']['version']; ?>
			<?php endif; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Generate'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php if($node['Node']['generate'] == 1): ?>
			True
			&nbsp;
			<?php else: ?>
			False
			&nbsp;
			<?php endif; ?>
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Proc Limit'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php if($node['Node']['genproclimit'] == -1): ?>
			Unlimited
			&nbsp;
			<?php else: ?>
			<?php echo $node['Node']['genproclimit']; ?>
			&nbsp;
			<?php endif; ?>
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Difficulty'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $node['Node']['difficulty']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Khps'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php if($node['Node']['khps'] === NULL): ?>
			n/a
			<?php else: ?>
			<?php echo $number->precision($node['Node']['khps'],0); ?>
			<?php endif; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Pending Blocks'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php if($node['Node']['pending_blocks'] === NULL): ?>
			n/a
			<?php else: ?>
			<?php echo $node['Node']['pending_blocks']; ?>
			<?php endif; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Generated Blocks'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php if($node['Node']['generated_blocks'] === NULL): ?>
			n/a
			<?php else: ?>
			<?php echo $node['Node']['generated_blocks']; ?>
			<?php endif; ?>
			&nbsp;
		</dd>
		<?php else: ?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Status'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $node['Node']['status']; ?>
			&nbsp;
		</dd>
		<?php endif; ?>
	</dl>
	<?php if($node['Node']['status'] == 'online'): ?>
	<div class="transactions">
		<h3><?php __('Transactions'); ?></h3>
		<?php if(isset($transactions) && count($transactions) > 0): ?>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<th>Status</th>
					<th>Date</th>
					<th>Description</th>
					<th>Debit</th>
					<th>Credit</th>
				</tr>
			<?php foreach($transactions as $transaction): ?>
				<tr>
					<?php if($transaction['confirmations'] < 6): ?>
					<td><span class="help" title="Tx ID: <?php echo $transaction['txid']; ?>"><?php echo $transaction['confirmations']; ?>/unconfirmed</span></td>
					<?php else: ?>
					<td><span class="help" title="Tx ID: <?php echo $transaction['txid']; ?>"><?php echo $transaction['confirmations']; ?> confirmations</span></td>
					<?php endif; ?>
					<td><?php echo $time->nice($transaction['txtime']); ?></td>

					<?php if($transaction['category'] == 'debit'): ?>

					<?php if(empty($transaction['label'])): ?>
					<td>To: <span style="font-style:italic" class="help" title="Address: <?php echo $transaction['address']; ?>">unknown</span></td>
					<?php else: ?>
					<td>To: <span class="help" title="Address: <?php echo $transaction['address']; ?>"><?php echo $transaction['label']; ?></span></td>
					<?php endif; ?>
					<td>-<?php echo $number->precision($transaction['amount'],2); ?></td>
					<td>&nbsp;</td>

					<?php elseif($transaction['category'] == 'credit'): ?>

					<?php if(empty($transaction['label'])): ?>
					<td>From: unknown, Received with: <span style="font-style:italic" class="help" title="Address: <?php echo $transaction['address']; ?>">no label</span></td>
					<?php else: ?>
					<td>From: unknown, Received with: <span class="help" title="Address: <?php echo $transaction['address']; ?>"><?php echo $transaction['label']; ?></span></td>
					<?php endif; ?>
					<td>&nbsp;</td>
					<td>+<?php echo $number->precision($transaction['amount'],2); ?></td>

					<?php elseif($transaction['category'] == 'mixed_debit'): ?>

					<td>Generated, matures in <?php echo 120 - $transaction['confirmations']; ?> more blocks</td>
					<td>&nbsp;</td>
					<td>+<?php echo $number->precision($transaction['amount'],2); ?></td>

					<?php elseif($transaction['category'] == 'generated'): ?>

					<td>Generated</td>
					<td>&nbsp;</td>
					<td>+<?php echo $number->precision($transaction['amount'],2); ?></td>

					<?php else: ?>

					<td colspan="2"><?php echo $transaction['category']; ?> <?php echo $number->precision($transaction['amount'],2); ?></td>

					<?php endif; ?>
				</tr>
			<?php endforeach; ?>
			</table>
		<?php elseif($transactions === NULL): ?>
			<p>This node does not support viewing transactions.</p>
		<?php else: ?>
			<p>This node has no transactions yet.</p>
		<?php endif; ?>
	</div>
	<?php endif; ?>
</div>

<div class="actions">
	<h3><?php __('Nodes'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('List Nodes', true), array('controller'=>'nodes','action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Node', true), array('controller'=>'nodes','action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Edit Node', true), array('controller'=>'nodes','action' => 'edit', $node['Node']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Node', true), array('controller'=>'nodes','action' => 'delete', $node['Node']['id']), null, sprintf(__('Are you sure you want to delete %s?', true), $node['Node']['name'])); ?> </li>
	</ul>
	<h3><?php __('Users'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('List Users', true), array('controller'=>'users','action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User', true), array('controller'=>'users','action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Logout', true), array('controller'=>'users','action' => 'logout')); ?> </li>
	</ul>
</div>
