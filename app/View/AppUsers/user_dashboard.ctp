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
?>

 
<h2>Your Courses</h2>

<div class="actions">
	<ul>
		<li>
			<?php
			echo $this->Html->link('Add new Course', array(
				'controller' => 'courses',
				'action' => 'add'
			));
			?>
		</li>
	</ul>
</div>


<?php
echo $this->element('dashboard/shibboleth_link');


if(empty($courses)) {
	echo '<p>There are no courses in the registry owned by you.</p>';
}else{
	$this->set('edit', true);	// displays the "Actions" column in all subsequent elements
	echo $this->element('courses/index');
}
?>