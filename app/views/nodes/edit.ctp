<div class="nodes form">
<?php echo $this->Form->create('Node');?>
	<fieldset>
 		<legend><?php echo "Edit Node: {$node['Node']['name']}"; ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('hostname');
		echo $this->Form->input('port');
		echo $this->Form->input('uri');
		echo $this->Form->input('username');
		echo $this->Form->input('password',array('label'=>'Password (Unchanged if empty)','value'=>''));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
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
