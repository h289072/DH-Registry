<?php
/**
 * Copyright 2014 Hendrik Schmeer on behalf of DARIAH-EU, VCC2 and DARIAH-DE,
 * Credits to Erasmus University Rotterdam, University of Cologne, PIREH / University Paris 1
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

	public $components = array(
		'Users.DefaultAuth',
		'DebugKit.Toolbar',
		'Paginator',
		'Session',
		'Flash',
		'Cakeclient.Crud',
		'RequestHandler'
	);
	
	// paging defaults
	public $paginate = array(
		'limit' => 10,
		'maxLimit' => 200
	);
	
	public $filter = array();
	
	
	public function beforeFilter() {
		// maintain pagination settings
		// maintain pagination settings
		if($paginate = $this->Session->read('Paginate'))
			$this->paginate = array_merge($this->paginate, $paginate);
		if(!empty($this->request->data['Pager'])) {
			$form = $this->request->data['Pager'];
			if(!empty($form['limit']) AND ctype_digit($form['limit'])) {
				$this->paginate['limit'] = $form['limit'];
				$this->Session->write('Paginate.limit', $form['limit']);
			}
		}
		
		if(	!empty($this->request->params['layout'])
		AND	$this->request->params['layout'] == 'iframe'
		) {
			$this->layout = 'iframe';
		}
		
		// disable SSL on the dariah.uni-koeln.de server (bad configuration...)
		if(isset($this->Security))	$this->Security->requireSecure = array();
		
		$this->set('modelName', $this->modelClass);
		
		if($this->request->params['action'] != 'edit') $this->Session->delete('edit');
		
		// for debugging purposes
		if(!$this->Auth->user() AND strpos(APP, 'xampp') !== false AND Configure::read('debug') > 0) {
			//$this->Auth->allow();
			//$this->DefaultAuth->is_admin = true;
			//debug('allowed by debug settings');
		}
		
		if($this->Auth->user('user_role_id') AND $this->Auth->user('user_role_id') < 3) {
			// dynamically load the AclMenu Component
			$this->Crud->loadAclMenu();
			$this->AclMenu->setMenu();
		}
	}
	
	
	public function beforeRedirect($url, $status = null, $exit = true) {
		if(	!empty($this->request->params['layout'])
		AND	$this->request->params['layout'] == 'iframe'
		AND	strpos($url, 'iframe') === false
		) {
			$url = (strpos($url, '/') === 0) ? '/iframe' . $url : '/iframe/' . $url;
			return array(
				'url' => $url,
				'status' => $status,
				'exit' => $exit
			);
		}
	}
	
	
	// reset filter function
	public function reset($filter = null) {
		if(!empty($filter)) {
			// Only remove a single filter key. As the filter keys contain find-conditions in "."-notation, Session::delete() doesn't handle it correctly
			$store = $this->Session->read('filter');
			unset($store[$filter]);
			$this->Session->write('filter', $store);
		}else{
			// remove all filters
			$this->Session->delete('filter');
		}
		$this->redirect(array('action' => 'index'));
	}
	
	
	
	protected function _getFilter() {
		$filter = $this->filter;
		if(empty($filter)) $filter = $this->_setupFilter();
		return $filter;
	}
	
	protected function _setupFilter() {
		// check for previously set filters
		$this->filter = $this->Session->read('filter');
		$this->_postedFilters();
		$this->Session->write('filter', $this->filter);
		return $this->filter;
	}
	
	
	protected function _postedFilter() {}
	
	
	protected function _checkCaptcha() {
		$ip = $_SERVER['REMOTE_ADDR'];
		if(!empty($_SERVER['HTTP_CLIENT_IP'])) 
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) 
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		
		$data = array(
			'secret' => Configure::read('App.reCaptchaPrivateKey'),
			'response' => $this->request->data['g-recaptcha-response'],
			'remoteip' => $ip
		);
		
		// set URL and other appropriate options
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		
		// grab URL and pass it to the browser
		$result = curl_exec($ch);
		
		// close cURL resource, and free up system resources
		curl_close($ch);
		
		print_r($result); // output result for all the kings
	}
	
}





