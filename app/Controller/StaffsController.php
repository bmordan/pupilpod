<?php
class StaffsController extends AppController{
	public function index(){
		$this->set('title_for_layout','MIS 2.0 HOME');
		$id = 31;
		$this->set('staffMember',$this->Staff->find('all',array('conditions'=>array('id'=>$id))));
		$this->set('json',ClassRegistry::init('Pupil')->json('false'));
	}
}