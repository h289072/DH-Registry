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
 
$filter = $this->Session->read('filter');
?>

<li class="filter">Search Options:</li>

<li class="filter">
	<?php
	echo $this->Form->create('Course', array('url' => array(
		'controller' => 'courses',
		'action' => 'index'
	)));
	
	$this->Form->inputDefaults(array(
		'empty' => ' - all - ',
		'required' => false,		// as the validation scheme in the model has this field mandatory, the formHelper sets this attribute to true, thus triggering HTML 5 browser-validation!
		'onchange' => 'this.form.submit()'
	));
	
	echo $this->Form->input('country_id');
	echo $this->Form->input('city_id');
	echo $this->Form->input('institution_id');
	
	echo $this->Form->input('course_type_id', array(
		'label' => 'Education'
	));
	
	echo $this->element('taxonomy/taxonomy_filter', array('dropdownChecklist' => true));
	
	echo $this->Form->end(array(
		'label' => 'Show Results',
		'div' => array('id' => 'submit_filter')
	));
	?>
</li>

<?php
if(!empty($filter))
	echo '<li class="filter">' . $this->Html->link('Reset all Filters', array(
		'controller' => 'courses',
		'action' => 'reset'
	),
	array('class' => 'form-button')) . '</li>';
?>

<?php $this->Html->scriptStart(array('inline' => false)); ?>
$(document).ready(function() {
	document.getElementById("submit_filter").style.display = "none";
});
<?php $this->Html->scriptEnd(); ?>






