<?php
class NodesController extends AppController {

	var $name = 'Nodes';

	var $helpers = array('Time','Number');

	function beforeFilter() {
		parent::beforeFilter();
		include_once(VENDORS . 'jsonRPCClient.php');
		$this->Node->recursive = 0;
	}

	function index() {
		$this->Node->recursive = 0;
		$nodes = $this->paginate();
		foreach ($nodes as &$node) {
			if (strtotime($node['Node']['last_update']) < time() - 30) {
				try {
					$bitcoin = new jsonRPCClient("http://{$node['Node']['username']}:{$node['Node']['password']}@{$node['Node']['hostname']}:{$node['Node']['port']}/{$node['Node']['uri']}");
					$info = $bitcoin->getinfo();
					if (isset($info['version'])) {
						$node['Node']['version'] = $info['version'];
					} else {
						$node['Node']['version'] = NULL;
					}
					$node['Node']['balance'] = $info['balance'];
					$node['Node']['blocks'] = $info['blocks'];
					$node['Node']['connections'] = $info['connections'];
					//$node['Node']['proxy'] = $info['proxy'];
					$node['Node']['generate'] = $info['generate'];
					//$node['Node']['genproclimit'] = $info['genproclimit'];
					//$node['Node']['difficulty'] = $info['difficulty'];
					if (isset($info['hashespersec'])) {
						$node['Node']['khps'] = $info['hashespersec'] / 1000;
					} else {
						$node['Node']['khps'] = NULL;
					}
					$node['Node']['status'] = 'online';
					$node['Node']['last_update'] = date('Y-m-d H:i:s');
				} catch (Exception $e) {
					// Can't connect, lets update the status
					$node['Node']['status'] = $e->getMessage();
					$node['Node']['last_update'] = date('Y-m-d H:i:s');
					// Detach LogableBehavior so the log file doesn't get filled with node updates/refreshes
					$this->Node->Behaviors->detach('Logable');
					$this->Node->save($node);
				}

				if ($node['Node']['status'] == 'online') {
					try {
						// we can't just count() because that will include blocks that are not accepted
						$pending_blocks = $bitcoin->listgenerated(TRUE);
						$node['Node']['pending_blocks'] = 0;
						foreach ($pending_blocks as $block) {
							if ($block['accepted']) {
								$node['Node']['pending_blocks']++;
							}
						}
					} catch (Exception $e) {
						$node['Node']['pending_blocks'] = NULL;
					}

					try {
						// we can't just count() because that will include blocks that are not accepted
						$generated_blocks = $bitcoin->listgenerated();
						$node['Node']['generated_blocks'] = 0;
						foreach ($generated_blocks as $block) {
							if ($block['accepted']) {
								$node['Node']['generated_blocks']++;
							}
						}
					} catch (Exception $e) {
						$node['Node']['generated_blocks'] = NULL;
					}

					// Detach LogableBehavior so the log file doesn't get filled with node updates/refreshes
					$this->Node->Behaviors->detach('Logable');
					$this->Node->save($node);
				}
			}
		}
		$this->set('nodes', $nodes);
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid node', true));
			$this->redirect($this->referer());
		}
		$node = $this->Node->read(null, $id);
		if (empty($node)) {
			$this->Session->setFlash(__('Invalid node', true));
			$this->redirect($this->referer());
		}

		try {
			$bitcoin = new jsonRPCClient("http://{$node['Node']['username']}:{$node['Node']['password']}@{$node['Node']['hostname']}:{$node['Node']['port']}/{$node['Node']['uri']}");
			$info = $bitcoin->getinfo();
			if (isset($info['version'])) {
				$node['Node']['version'] = $info['version'];
			} else {
				$node['Node']['version'] = NULL;
			}
			$node['Node']['balance'] = $info['balance'];
			$node['Node']['blocks'] = $info['blocks'];
			$node['Node']['connections'] = $info['connections'];
			$node['Node']['proxy'] = $info['proxy'];
			$node['Node']['generate'] = $info['generate'];
			$node['Node']['genproclimit'] = $info['genproclimit'];
			$node['Node']['difficulty'] = $info['difficulty'];
			if (isset($info['hashespersec'])) {
				$node['Node']['khps'] = $info['hashespersec'] / 1000;
			} else {
				$node['Node']['khps'] = NULL;
			}
			$node['Node']['status'] = 'online';
			$node['Node']['last_update'] = date('Y-m-d H:i:s');
		} catch (Exception $e) {
			// Can't connect, lets update the status
			$node['Node']['status'] = $e->getMessage();
			$node['Node']['last_update'] = date('Y-m-d H:i:s');
			// Detach LogableBehavior so the log file doesn't get filled with node updates/refreshes
			$this->Node->Behaviors->detach('Logable');
			$this->Node->save($node);
		}

		if ($node['Node']['status'] == 'online') {
			try {
				// we can't just count() because that will include blocks that are not accepted
				$pending_blocks = $bitcoin->listgenerated(TRUE);
				$node['Node']['pending_blocks'] = 0;
				foreach ($pending_blocks as $block) {
					if ($block['accepted']) {
						$node['Node']['pending_blocks']++;
					}
				}
			} catch (Exception $e) {
				$node['Node']['pending_blocks'] = NULL;
			}

			try {
				// we can't just count() because that will include blocks that are not accepted
				$generated_blocks = $bitcoin->listgenerated();
				$node['Node']['generated_blocks'] = 0;
				foreach ($generated_blocks as $block) {
					if ($block['accepted']) {
						$node['Node']['generated_blocks']++;
					}
				}
			} catch (Exception $e) {
				$node['Node']['generated_blocks'] = NULL;
				$blocks = NULL;
			}

			try {
				$transactions = $bitcoin->listtransactions(100,0,TRUE);
				if (count($transactions)) {
					// sorting $transactions may not be necessary, it looks like listtransactions is already sorted based on # of confirmations
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
				$transactions = NULL;
			}

			// Detach LogableBehavior so the log file doesn't get filled with node updates/refreshes
			$this->Node->Behaviors->detach('Logable');
			$this->Node->save($node);
		}
		$this->set(compact('node', 'transactions'));
	}

	function showlog($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid node', true));
			$this->redirect($this->referer());
		}
		$node = $this->Node->read(null, $id);
		$logs = $this->paginate('Log',array('model'=>'Node','model_id'=>$id));
		if (empty($node)) {
			$this->Session->setFlash(__('Invalid node', true));
			$this->redirect($this->referer());
		}
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
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid node', true));
			$this->redirect(array('action' => 'index'));
		}
		$node = $this->Node->read(null, $id);
		if (empty($node)) {
			$this->Session->setFlash(__('Invalid node', true));
			$this->redirect($this->referer());
		}

		if (!empty($this->data)) {
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
		if (empty($this->data)) {
			$this->data = $node;
		}
		$this->set('node', $node);
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for node', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Node->delete($id)) {
			$this->Session->setFlash(__('Node deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Node was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}

	function sendtoaddress($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid node', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!$id) {
			$id = $this->data['Node']['id'];
		}
		$node = $this->Node->read(null, $id);

		if ($this->data) {
			try {
				$bitcoin = new jsonRPCClient("http://{$node['Node']['username']}:{$node['Node']['password']}@{$node['Node']['hostname']}:{$node['Node']['port']}/{$node['Node']['uri']}");
				// Typecasting $this->data['Node']['amount'] to float because that's the only thing that works. This may not be a good idea though.
				$bitcoin->sendtoaddress($this->data['Node']['address'],(float)$this->data['Node']['amount']);
				$this->Node->customLog('sendtoaddress', $node['Node']['id'], array('title' => $node['Node']['name'], 'description' => "Node \"{$node['Node']['name']}\" ({$node['Node']['id']}) {$this->data['Node']['amount']} BTC sent to {$this->data['Node']['address']}"));
			} catch (Exception $e) {
				$this->Session->setFlash($e->getMessage());
				$this->redirect($this->referer());
			}
			$this->Session->setFlash(__('Bitcoins sent', true));
			$this->redirect(array('action' => 'view',$id));
		}
		$this->set('node', $node);
	}

	function setgenerate($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid id for node', true));
			$this->redirect($this->referer());
		}
		if (!$id) {
			$id = $this->data['Node']['id'];
		}
		$node = $this->Node->read(null, $id);
		if (empty($node)) {
			$this->Session->setFlash(__('Invalid node', true));
			$this->redirect($this->referer());
		}

		if (empty($this->data)) {
			try {
				$bitcoin = new jsonRPCClient("http://{$node['Node']['username']}:{$node['Node']['password']}@{$node['Node']['hostname']}:{$node['Node']['port']}/{$node['Node']['uri']}");
				$info = $bitcoin->getinfo();
				$node['Node']['generate'] = $info['generate'];
				$node['Node']['genproclimit'] = $info['genproclimit'];
			} catch (Exception $e) {
				$this->Session->setFlash(__('Unable to connect to node', true));
				$this->redirect($this->referer());
			}
		}

		if (!empty($this->data)) {
			try {
				$bitcoin = new jsonRPCClient("http://{$node['Node']['username']}:{$node['Node']['password']}@{$node['Node']['hostname']}:{$node['Node']['port']}/{$node['Node']['uri']}");
				if ($this->data['Node']['setgenerate']) {
					$bitcoin->setgenerate(true,(int)$this->data['Node']['genproclimit']);
					$this->Node->customLog('setgenerate', $node['Node']['id'], array('title' => $node['Node']['name'], 'description' => "Node \"{$node['Node']['name']}\" ({$node['Node']['id']}) setgenerate true {$this->data['Node']['genproclimit']} called"));
				} else {
					$bitcoin->setgenerate(false,(int)$this->data['Node']['genproclimit']);
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
		if (!$id) {
			$this->Session->setFlash(__('Invalid node', true));
			$this->redirect($this->referer());
		}	
		$node = $this->Node->read(null, $id);
		if (empty($node)) {
			$this->Session->setFlash(__('Invalid node', true));
			$this->redirect($this->referer());
		}

		try {
			$bitcoin = new jsonRPCClient("http://{$node['Node']['username']}:{$node['Node']['password']}@{$node['Node']['hostname']}:{$node['Node']['port']}/{$node['Node']['uri']}");
			$addresses = $bitcoin->listreceivedbyaddress(0,true);
		} catch (Exception $e) {
			$this->Session->setFlash(__('Unable to connect to node', true));
			$this->redirect($this->referer());
		}

		$this->set(compact('node','addresses'));
	}

	function setlabel($id = null,$address = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid id for node', true));
			$this->redirect($this->referer());
		}
		if (!$address && empty($this->data)) {
			$this->Session->setFlash(__('No address specified', true));
			$this->redirect($this->referer());
		}
		if (!$id) {
			$id = $this->data['Node']['id'];
		}
		$node = $this->Node->read(null, $id);
		if (empty($node)) {
			$this->Session->setFlash(__('Invalid node', true));
			$this->redirect($this->referer());
		}

		if (empty($this->data)) {
			try {
				$bitcoin = new jsonRPCClient("http://{$node['Node']['username']}:{$node['Node']['password']}@{$node['Node']['hostname']}:{$node['Node']['port']}/{$node['Node']['uri']}");
				$label = $bitcoin->getlabel($address);
			} catch (Exception $e) {
				$this->Session->setFlash(__('Unable to connect to node', true));
				$this->redirect($this->referer());
			}
		}

		if (!empty($this->data)) {
			try {
				$bitcoin = new jsonRPCClient("http://{$node['Node']['username']}:{$node['Node']['password']}@{$node['Node']['hostname']}:{$node['Node']['port']}/{$node['Node']['uri']}");
				$bitcoin->setlabel($this->data['Node']['address'],$this->data['Node']['label']);
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
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid node', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!$id) {
			$id = $this->data['Node']['id'];
		}
		$node = $this->Node->read(null, $id);

		if ($this->data) {
			try {
				$bitcoin = new jsonRPCClient("http://{$node['Node']['username']}:{$node['Node']['password']}@{$node['Node']['hostname']}:{$node['Node']['port']}/{$node['Node']['uri']}");
				$address = $bitcoin->getnewaddress($this->data['Node']['label']);
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
}
?>
