<?php
if(!empty($title_for_layout)) echo '<h2>'.$title_for_layout.'</h2>';
echo $this->element('layout/actions', array(), array('plugin' => 'Cakeclient'));

echo $this->element('relations/parent_classes', array(), array('plugin' => 'Cakeclient'));
echo $this->element('relations/child_classes', array(), array('plugin' => 'Cakeclient'));
echo $this->element('relations/habtm_classes', array(), array('plugin' => 'Cakeclient'));

echo $this->element('index/filter', array(), array('plugin' => 'Cakeclient'));

echo $this->element('index/bulkprocessor', array(), array('plugin' => 'Cakeclient'));
echo $this->element('index/pager', array(), array('plugin' => 'Cakeclient'));

// the actual listing
echo $this->element('crud/index', array(), array('plugin' => 'Cakeclient'));

echo $this->element('index/bulkprocessor', array(), array('plugin' => 'Cakeclient'));
echo $this->element('index/pager', array(), array('plugin' => 'Cakeclient'));
?>