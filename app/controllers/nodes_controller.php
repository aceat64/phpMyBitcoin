<?php
class NodesController extends AppController {

	var $name = 'Nodes';

	var $helpers = array('Time','Number');

	function beforeFilter() {
		include_once(VENDORS . 'jsonRPCClient.php');
	}

	function index() {
		$this->Node->recursive = 0;
		$nodes = $this->paginate();
		foreach($nodes as &$node) {
			$node['Node']['status'] = 'online';
			if(strtotime($node['Node']['last_update']) < time() - 30) {
				try {
					$bitcoin = new jsonRPCClient("http://{$node['Node']['username']}:{$node['Node']['password']}@{$node['Node']['hostname']}:{$node['Node']['port']}/{$node['Node']['uri']}");
					$info = $bitcoin->getinfo();
					$node['Node']['balance'] = $info['balance'];
					$node['Node']['blocks'] = $info['blocks'];
					//$node['Node']['proxy'] = $info['proxy'];
					$node['Node']['generate'] = $info['generate'];
					//$node['Node']['genproclimit'] = $info['genproclimit'];
					//$node['Node']['difficulty'] = $info['difficulty'];
					if(isset($info['hashespersec'])) {
						$node['Node']['khps'] = $info['hashespersec'] / 1000;
					} else {
						$node['Node']['khps'] = NULL;
					}
					$node['Node']['last_update'] = date('Y-m-d H:i:s');
				} catch (Exception $e) {
					// Can't connect, lets update the status
					$node['Node']['status'] = $e->getMessage();
				}

				try {
					$node['Node']['pending_blocks'] = count($bitcoin->listgenerated(TRUE));
				} catch (Exception $e) {
					$node['Node']['pending_blocks'] = NULL;
				}

				try {
					$node['Node']['generated_blocks'] = count($bitcoin->listgenerated());
				} catch (Exception $e) {
					$node['Node']['generated_blocks'] = NULL;
				}

				// If the node is online, lets update the MySQL entry
				if($node['Node']['status'] == 'online') {
					$this->Node->save($node);
				}
			}
		}
		$this->set('nodes', $nodes);
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid node', true));
			$this->redirect(array('action' => 'index'));
		}
		$node = $this->Node->read(null, $id);

		$node['Node']['status'] = 'online';

		try {
			$bitcoin = new jsonRPCClient("http://{$node['Node']['username']}:{$node['Node']['password']}@{$node['Node']['hostname']}:{$node['Node']['port']}/{$node['Node']['uri']}");
			$info = $bitcoin->getinfo();
			$node['Node']['balance'] = $info['balance'];
			$node['Node']['blocks'] = $info['blocks'];
			$node['Node']['proxy'] = $info['proxy'];
			$node['Node']['generate'] = $info['generate'];
			$node['Node']['genproclimit'] = $info['genproclimit'];
			$node['Node']['difficulty'] = $info['difficulty'];
			if(isset($info['hashespersec'])) {
				$node['Node']['khps'] = $info['hashespersec'] / 1000;
			} else {
				$node['Node']['khps'] = NULL;
			}
			$node['Node']['last_update'] = date('Y-m-d H:i:s');
		} catch (Exception $e) {
			// Can't connect, lets update the status
			$node['Node']['status'] = $e->getMessage();
		}

		try {
			$node['Node']['pending_blocks'] = count($bitcoin->listgenerated(TRUE));
		} catch (Exception $e) {
			$node['Node']['pending_blocks'] = NULL;
		}

		try {
			$blocks = $bitcoin->listgenerated();
			$node['Node']['generated_blocks'] = count($blocks);

			if($node['Node']['generated_blocks'] != 0) {
				$sortArray = array(); 

				foreach($blocks as $block){
					foreach($block as $key=>$value){
						if(!isset($sortArray[$key])){
							$sortArray[$key] = array();
						}
						$sortArray[$key][] = $value;
					}
				}

				$orderby = "genTime"; //change this to whatever key you want from the array

				array_multisort($sortArray[$orderby],SORT_DESC,$blocks);
			}
		} catch (Exception $e) {
			$node['Node']['generated_blocks'] = NULL;
			$blocks = NULL;
		}

		try {
			$transactions = $bitcoin->listtransactions(100,0,TRUE);

			if(count($transactions)) {
				// sorting $transactions may not be necessary, it looks like listtransactions is already sorted based on # of confirmations
				$sortArray = array(); 

				foreach($transactions as $transaction){
					foreach($transaction as $key=>$value){
						if(!isset($sortArray[$key])){
							$sortArray[$key] = array();
						}
						$sortArray[$key][] = $value;
					}
				}

				$orderby = "tx_time"; //change this to whatever key you want from the array

				array_multisort($sortArray[$orderby],SORT_DESC,$transactions);
			}
		} catch (Exception $e) {
			$transactions = NULL;
		}

		// If the node is online, lets update the MySQL entry
		if($node['Node']['status'] == 'online') {
			$this->Node->save($node);
		}

		$this->set(compact('node', 'blocks', 'transactions'));
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
		if (!empty($this->data)) {
			if ($this->Node->save($this->data)) {
				$this->Session->setFlash(__('The node has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The node could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Node->read(null, $id);
		}
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
}
?>
