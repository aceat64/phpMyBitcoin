<?php
class NodesController extends AppController {

	var $name = 'Nodes';

	var $helpers = array('Time','Number');

	var $bitcoin;

	function beforeFilter() {
		parent::beforeFilter();
		// Include the PHP JSON-RPC client class
		include_once(VENDORS . 'jsonRPCClient.php');
		// Just making sure we default to no recursion
		$this->Node->recursive = 0;
	}

	function index() {
		$nodes = $this->paginate();
		foreach ($nodes as &$node) {
			// If last_update is older than 30s, TODO: change this to a setting saved in the database
			if (strtotime($node['Node']['last_update']) < time() - 30) {
				// Connect to the node
				$this->_connect($node);
				// Update the node
				$node = $this->_updatenode($node);
			}
		}
		$this->set('nodes', $nodes);
	}

	function view($id = null) {
		// No $id, obviously we won't be able to find the node to edit
		if (!$id) {
			$this->Session->setFlash(__('Invalid node', true));
			$this->redirect($this->referer());
		}
		// Try to read the node's info
		$node = $this->Node->read(null, $id);
		// 404, node not found
		if (empty($node)) {
			$this->Session->setFlash(__('Invalid node', true));
			$this->redirect($this->referer());
		}

		// Connect to the node
		$this->_connect($node);
		// Update the node
		$node = $this->_updatenode($node);

		// If the node is online lets populate $transactions
		if($node['Node']['status'] == 'online') {
			try {
				$transactions = $this->bitcoin->listtransactions(100,0,TRUE);
				if (count($transactions)) {
					// sorting $transactions may not be necessary, it looks like listtransactions is already sorted based on # of confirmations or by date
					$sortArray = array();
					foreach ($transactions as $transaction) {
						foreach ($transaction as $key=>$value) {
							if (!isset($sortArray[$key])) {
								$sortArray[$key] = array();
							}
						$sortArray[$key][] = $value;
						}
					}

					$orderby = "txtime"; //change this to whatever key you want from the array

					array_multisort($sortArray[$orderby],SORT_DESC,$transactions);
				}
			} catch (Exception $e) {
				// This node doesn't support listtransactions
				$transactions = NULL;
			}
		}
		$this->set(compact('node', 'transactions'));
	}

	function showlog($id = null) {
		// No $id, obviously we won't be able to find the node
		if (!$id) {
			$this->Session->setFlash(__('Invalid node', true));
			$this->redirect($this->referer());
		}
		// Try to read the node's info
		$node = $this->Node->read(null, $id);
		// 404, node not found
		if (empty($node)) {
			$this->Session->setFlash(__('Invalid node', true));
			$this->redirect($this->referer());
		}
		// Grab the logs for this node
		$logs = $this->paginate('Log',array('model'=>'Node','model_id'=>$id));
		$this->set(compact('node','logs'));
	}

	function add() {
		if (!empty($this->data)) {
			$this->Node->create();
			if ($this->Node->save($this->data)) {
				$this->Session->setFlash(__('The node has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The node could not be saved. Please, try again.', true));
			}
		}
	}

	function edit($id = null) {
		// No $id and no form data
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid node', true));
			$this->redirect(array('action' => 'index'));
		}
		// Try to read the node's info
		$node = $this->Node->read(null, $id);
		// 404, node not found
		if (empty($node)) {
			$this->Session->setFlash(__('Invalid node', true));
			$this->redirect($this->referer());
		}

		// If there's form data
		if (!empty($this->data)) {
			// Use the password from the database if the password field was empty in the form data
			if (empty($this->data['Node']['password'])) {
				$this->data['Node']['password'] = $node['Node']['password'];
			}
			if ($this->Node->save($this->data)) {
				$this->Session->setFlash(__('The node has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The node could not be saved. Please, try again.', true));
			}
		}
		// Populate the form data if it's empty
		if (empty($this->data)) {
			$this->data = $node;
		}
		$this->set('node', $node);
	}

	function delete($id = null) {
		// You want me to delete what?
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for node', true));
			$this->redirect(array('action'=>'index'));
		}
		// Try to delete the node
		if ($this->Node->delete($id)) {
			$this->Session->setFlash(__('Node deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Node was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}

	function sendtoaddress($id = null) {
		// No $id and no form data
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid node', true));
			$this->redirect(array('action' => 'index'));
		}

		// Form data but no $id
		if (!$id) {
			// Set $id to what's in the form data
			$id = $this->data['Node']['id'];
		}

		// Read the node's info
		$node = $this->Node->read(null, $id);

		// 404, node not found
		if (empty($node)) {
			$this->Session->setFlash(__('Invalid node', true));
			$this->redirect($this->referer());
		}

		// We've got form data, lets try to send some bitcoins
		if ($this->data) {
			try {
				// Connect to the node
				$this->_connect($node);

				// Typecasting $this->data['Node']['amount'] to float because that's the only thing that works. This may not be a good idea though.
				$this->bitcoin->sendtoaddress($this->data['Node']['address'],(float)$this->data['Node']['amount']);

				// Write to the log so we know who is wasting our money				
				$this->Node->customLog('sendtoaddress', $node['Node']['id'], array('title' => $node['Node']['name'], 'description' => "Node \"{$node['Node']['name']}\" ({$node['Node']['id']}) {$this->data['Node']['amount']} BTC sent to {$this->data['Node']['address']}"));
			} catch (Exception $e) {
				// Something broke or maybe we're broke :)
				$this->Session->setFlash($e->getMessage());
				$this->redirect($this->referer());
			}
			$this->Session->setFlash(__('Bitcoins sent', true));
			$this->redirect(array('action' => 'view',$id));
		}
		$this->set('node', $node);
	}

	function setgenerate($id = null) {
		// No $id and no form data
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid id for node', true));
			$this->redirect($this->referer());
		}

		// Form data but no $id
		if (!$id) {
			// Set $id to what's in the form data
			$id = $this->data['Node']['id'];
		}

		// Read the node's info
		$node = $this->Node->read(null, $id);

		// 404, node not found
		if (empty($node)) {
			$this->Session->setFlash(__('Invalid node', true));
			$this->redirect($this->referer());
		}

		// Connect to the node
		$this->_connect($node);

		// No form data, so lets grab the node's generate settings so we can show the user what they are		
		if (empty($this->data)) {
			try {
				// Too bad getgenerate only shows true/false, so we have to call getinfo and grab just what we need
				$info = $this->bitcoin->getinfo();
				$node['Node']['generate'] = $info['generate'];
				$node['Node']['genproclimit'] = $info['genproclimit'];
			} catch (Exception $e) {
				$this->Session->setFlash(__('Unable to connect to node', true));
				$this->redirect($this->referer());
			}
		}

		// We've got form data, so lets try to change the node's generate settings
		if (!empty($this->data)) {
			try {
				// If generate blocks is checked
				if ($this->data['Node']['setgenerate']) {
					$this->bitcoin->setgenerate(true,(int)$this->data['Node']['genproclimit']);

					// Log that a user changed this node's settings
					$this->Node->customLog('setgenerate', $node['Node']['id'], array('title' => $node['Node']['name'], 'description' => "Node \"{$node['Node']['name']}\" ({$node['Node']['id']}) setgenerate true {$this->data['Node']['genproclimit']} called"));
				} else {
					$this->bitcoin->setgenerate(false,(int)$this->data['Node']['genproclimit']);

					// Log that a user changed this node's settings
					$this->Node->customLog('setgenerate', $node['Node']['id'], array('title' => $node['Node']['name'], 'description' => "Node \"{$node['Node']['name']}\" ({$node['Node']['id']}) setgenerate false {$this->data['Node']['genproclimit']} called"));
				}
			} catch (Exception $e) {
				$this->Session->setFlash(__('Unable to connect to node', true));
				$this->redirect($this->referer());
			}
			$this->redirect(array('action' => 'view',$id));
		}
		$this->set('node', $node);
	}

	function listaddresses($id = null) {
		// No $id, obviously we won't be able to find the node
		if (!$id) {
			$this->Session->setFlash(__('Invalid node', true));
			$this->redirect($this->referer());
		}

		// Read the node's info
		$node = $this->Node->read(null, $id);

		// 404, node not found
		if (empty($node)) {
			$this->Session->setFlash(__('Invalid node', true));
			$this->redirect($this->referer());
		}

		try {
			// Connect to the node
			$this->_connect($node);
			$addresses = $this->bitcoin->listreceivedbyaddress(0,true);
		} catch (Exception $e) {
			$this->Session->setFlash(__('Unable to connect to node', true));
			$this->redirect($this->referer());
		}

		$this->set(compact('node','addresses'));
	}

	function setlabel($id = null,$address = null) {
		// No $id and no form data
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid id for node', true));
			$this->redirect($this->referer());
		}

		// No $address and no form data
		if (!$address && empty($this->data)) {
			$this->Session->setFlash(__('No address specified', true));
			$this->redirect($this->referer());
		}

		// Form data but no $id
		if (!$id) {
			// Set $id to what's in the form data
			$id = $this->data['Node']['id'];
		}

		// Read the node's info
		$node = $this->Node->read(null, $id);

		// 404, node not found
		if (empty($node)) {
			$this->Session->setFlash(__('Invalid node', true));
			$this->redirect($this->referer());
		}

		// Connect to the node
		$this->_connect($node);

		// No form data, so lets grab the label for this address
		if (empty($this->data)) {
			try {
				$label = $this->bitcoin->getlabel($address);
			} catch (Exception $e) {
				$this->Session->setFlash(__('Unable to connect to node', true));
				$this->redirect($this->referer());
			}
		}

		// We've got form data, so lets try to set the label for this address
		if (!empty($this->data)) {
			try {
				$this->bitcoin->setlabel($this->data['Node']['address'],$this->data['Node']['label']);

				// Log that a user changed the label for this address on this node
				$this->Node->customLog('setlabel', $node['Node']['id'], array('title' => $node['Node']['name'], 'description' => "Node \"{$node['Node']['name']}\" ({$node['Node']['id']}) set label to \"{$this->data['Node']['label']}\" for address \"{$this->data['Node']['address']}\""));
			} catch (Exception $e) {
				$this->Session->setFlash(__('Unable to connect to node', true));
				$this->redirect($this->referer());
			}
			$this->redirect(array('action' => 'view',$id));
		}
		$this->set(compact('node','address','label'));
	}

	function getnewaddress($id = null) {
		// No $id and no form data
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid node', true));
			$this->redirect(array('action' => 'index'));
		}

		// Form data but no $id
		if (!$id) {
			// Set $id to what's in the form data
			$id = $this->data['Node']['id'];
		}

		// Read the node's info
		$node = $this->Node->read(null, $id);
		// 404, node not found
		if (empty($node)) {
			$this->Session->setFlash(__('Invalid node', true));
			$this->redirect($this->referer());
		}

		// We have form data, so lets try to generate a new address
		if ($this->data) {
			try {
				// Connect to the node
				$this->_connect($node);
				$address = $this->bitcoin->getnewaddress($this->data['Node']['label']);

				// Log that a user created a new address with the following $address and $label
				$this->Node->customLog('getnewaddress', $node['Node']['id'], array('title' => $node['Node']['name'], 'description' => "Node \"{$node['Node']['name']}\" ({$node['Node']['id']}) newaddress \"{$address}\" created with label \"{$this->data['Node']['label']}\""));
			} catch (Exception $e) {
				$this->Session->setFlash($e->getMessage());
				$this->redirect($this->referer());
			}
			$this->Session->setFlash(__('Address created', true));
			$this->redirect(array('action' => 'listaddresses',$id));
		}
		$this->set('node', $node);
	}

	function _updatenode($node = NULL) {
		if($node === NULL) {
			return FALSE;
		}
		try {
			// run getinfo on this node
			$info = $this->bitcoin->getinfo();

			// Map $info to $node
			foreach($info as $key => $item) {
				if($item !== NULL) {
					$node['Node'][$key] = $item;
				} else {
					$node['Node'][$key] = NULL;
				}
			}

			// Obviously getinfo worked, so the node is online
			$node['Node']['status'] = 'online';
		} catch (Exception $e) {
			// Can't connect, we populate the status field with the error message, even though the view typically doesn't output it
			$node['Node']['status'] = $e->getMessage();
		}

		// Only count the pending/generated blocks if the node is online
		if ($node['Node']['status'] == 'online') {
			try {
				// we can't just count() because that will include blocks that are not accepted
				$generated_blocks = $this->bitcoin->listgenerated();
				$node['Node']['pending_blocks'] = 0;
				$node['Node']['generated_blocks'] = 0;
				foreach ($generated_blocks as $block) {
					if ($block['accepted']) {
						if ($block['maturesIn'] == 0) {
							$node['Node']['generated_blocks']++;
						} elseif ($block['maturesIn'] > 0) {
							$node['Node']['pending_blocks']++;
						}
					}
				}
			} catch (Exception $e) {
				// This node doesn't support the listgenerated method
				$node['Node']['pending_blocks'] = NULL;
				$node['Node']['generated_blocks'] = NULL;
			}
		}

		// Set the new timestamp
		$node['Node']['last_update'] = date('Y-m-d H:i:s');
		// Detach LogableBehavior so the log file doesn't get filled with node updates/refreshes
		$this->Node->Behaviors->detach('Logable');
		$this->Node->save($node);

		return $node;
	}

	function _connect($node) {
		if(!$node) {
			return FALSE;
		}

		// Instantiate a new object for the node. The URI is required when using the relay.php script but we don't have to worry about passing a URI to the actual bitcoin client because it'll just be ignored.
		$this->bitcoin = new jsonRPCClient("http://{$node['Node']['username']}:{$node['Node']['password']}@{$node['Node']['hostname']}:{$node['Node']['port']}/{$node['Node']['uri']}");
		return TRUE;
	}
}
?>
