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
class CheckUrlsTask extends Shell {
	
	public $uses = array('Course');
	
	
	public function execute($sendMails = null, $to = null) {
		Configure::write('App.fullBaseUrl', Configure::read('App.consoleBaseUrl'));
		
		$collection = $this->Course->checkUrls();
		if(Configure::read('debug') > 0 AND $to == null) $to = 'mail@hendrikschmeer.de';
		$this->out('Debug level: ' . Configure::read('debug'));
		$this->out('Alternative addressee (debug): ' . $to);
		if(!empty($collection)) {
			if(Configure::read('debug') > 0) {
				//debug(count($collection));
			}
			if($sendMails !== false) {
				App::uses('CakeEmail', 'Network/Email');
				
				$this->out('Sending emails to:');
				foreach($collection as $email => $data) {
					if($email == 'no_owner') continue;
					
					$this->out($email . ': ' . $data['maintainer']);
					
					$Email = new CakeEmail('default');
					$subject_prefix = (Configure::read('App.EmailSubjectPrefix'))
						? trim(Configure::read('App.EmailSubjectPrefix')) . ' '
						: '';
					
					if(!empty($to)) $email = $to;
					$options = array(
						'subject_prefix' => $subject_prefix,
						'subject' => 'Invalid URLs',
						'emailFormat' => 'text',
						'template' => 'invalid_urls',
						'layout' => 'default',
						'email' => $email,
						'data' => $data
					);
					if(is_string($options['email'])) {
						$Email->to($options['email']);
						$Email->emailFormat($options['emailFormat']);
						$Email->subject($options['subject_prefix'] . $options['subject']);
						$Email->template($options['template'], $options['layout']);
						$Email->viewVars(array(
							'data' => $options['data']
						));
						if(Configure::read('debug') > 0 AND empty($to)) {
							$Email->transport('Debug');
						}
						$Email->send();
					}
					unset($Email);
				} 
			}
		}
	}
	
	
}
?>