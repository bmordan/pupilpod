<?php
class Staff extends AppModel{
	
	public $hasMany = array('Target','Activity');
	public $virtualFields = array('name' => 'CONCAT(first_name," ",last_name)');
	
	function getStaff($id){
		$name = $this->find('list',array('conditions'=>array('id'=>$id),'fields'=>array('id','name')));
		return $name[$id];
	}
}