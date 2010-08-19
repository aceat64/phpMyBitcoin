<div class="nodes view">
<h2><?php echo "List Addesses: {$node['Node']['name']}";?></h2>
<p>Please note, this page will show foreign addresses that have labels. This is not the intended behavior, a future version of the Bitcoin application will hopefully resolve this issue.</p>
	<div class="addresses">
		<h3><?php __('Addresses'); ?></h3>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<th><span style="font-style:italic" class="help" title="Click on an address to set it's label.">Address</span></th>
					<th>Label</th>
					<th><span style="font-style:italic" class="help" title="This is cumulative over the lifetime of the address, it does NOT show how much the address 'owns'.">Amount</span></th>
					<th><span style="font-style:italic" class="help" title="The number of confirmations of the most recent transaction included.">Confirmations</span></th>
				</tr>
			<?php foreach ($addresses as $address): ?>
				<tr>
					<td><?php echo $this->Html->link($address['address'], array('action' => 'setlabel',$node['Node']['id'],$address['address'])); ?></td>
					<td><?php echo $address['label']; ?></td>
					<td><?php echo $number->precision($address['amount'],2); ?></td>
					<td><?php echo $address['confirmations']; ?></td>
				</tr>
			<?php endforeach; ?>
			</table>
	</div>
</div>

<div class="actions">
	<h3><?php __('This Node'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('View Node', true), array('controller'=>'nodes','action' => 'view',$node['Node']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Addresses', true), array('controller'=>'nodes','action' => 'listaddresses', $node['Node']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Send To Address', true), array('controller'=>'nodes','action' => 'sendtoaddress', $node['Node']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Get New Address', true), array('controller'=>'nodes','action' => 'getnewaddress', $node['Node']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Set Generate', true), array('controller'=>'nodes','action' => 'setgenerate', $node['Node']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Edit Node', true), array('controller'=>'nodes','action' => 'edit', $node['Node']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Node', true), array('controller'=>'nodes','action' => 'delete', $node['Node']['id']), null, sprintf(__('Are you sure you want to delete %s?', true), $node['Node']['name'])); ?> </li>
		<li><?php echo $this->Html->link(__('Show Log', true), array('controller'=>'nodes','action' => 'showlog',$node['Node']['id'])); ?> </li>
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
