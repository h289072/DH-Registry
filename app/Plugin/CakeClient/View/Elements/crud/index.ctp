<?php
	// set the listname like "naughtyUsersList" for controller NaughtyUsers
	$list = Inflector::variable($controllerName) . 'List';
	echo '<div class="scroll_wrapper">';
		echo '<table>';
			
			echo $this->element('index/table_head');
			
			if(!empty($$list)) {
				foreach($$list as $k => $record) {
					if(isset($record[$modelName])) {
						echo '<tr>';
							if(!empty($bulkprocessing)) {
								echo '<td class="select">';
									echo $this->Form->input('Bulkprocessor.' . $record[$modelName]['id'], array(
										'type' => 'checkbox',
										'hiddenField' => false,
										'label' => false,
										'value' => $record[$modelName]['id'],
										'class' => 'bulkprocessor_item'
									));
								echo '</td>';
							}
							if(!empty($crudActions) AND $this->Display->hasContextActions($crudActions)) {
								echo '<td class="actions">';
									echo $this->element('index/actions', array(
										'record_id' => $record[$modelName]['id']
									));
								echo '</td>';
							}
							if(	isset($crudRelations)
							AND	(!empty($crudRelations['belongsTo']) OR !empty($crudRelations['hasMany']) OR !empty($crudRelations['hasAndBelongsToMany']))
							) {
								echo '<td class="children">';
									echo $this->element('index/parent_models', array('record' => $record));
									echo $this->element('index/child_models', array('record' => $record));
									echo $this->element('index/habtm_models', array('record' => $record));
								echo '</td>';
							}
							foreach($crudFieldlist as $key => $fieldDef) {
								$fieldname = $key;
								$fieldModelName = $modelName;
								fieldnameSplit($key, $fieldname, $fieldModelName);
								
								$_fieldname = $fieldname;
								// be aware of NULL values!
								if(isset($record[$fieldModelName]) AND array_key_exists($fieldname, $record[$fieldModelName])) {
									$value = $record[$fieldModelName][$fieldname];
									// check whether we have a foreign model field to display
									if(isset($fieldDef['displayField'])) {
										$key = $fieldname = $fieldDef['displayField'];
										$fieldModelName = $modelName;
										fieldnameSplit($key, $fieldname, $fieldModelName);
										$foreignKeyValue = $record[$fieldModelName][$fieldname];
									}
									if(!empty($fieldDef['display'])) {
										if(!is_array($fieldDef['display'])) $fieldDef['display'] = array('method' => $fieldDef['display']);
										if(!empty($foreignKeyValue)) {
											$fieldDef['display']['label'] = $foreignKeyValue;
										}else{
											// if the foreignKey field has no value (NULL), make no links or other fancy stuff
											$fieldDef['display'] = array('method' => 'display');
										}
										$value = $this->Display->dispatch($fieldDef['display'], $value, $record[$modelName], $_fieldname);
									}
									unset($foreignKeyValue);
									
									echo '<td>' . $value . '</td>';
								}
							}
						echo '</tr>';
					}
				}
			}
		echo '</table>';
	echo '</div>';
?>