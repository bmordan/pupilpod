<?php
class OptionsController extends AppController{	
	public function index($id = null){
		$this->set('options',$this->Option->find('all'));
		$this->set('name',$this->Option->optionFlip(81));
	}
	public function sen($pupil_id){
		$this->set('name',ClassRegistry::init('Pupil')->field('name'));
		$list = $this->Option->find('all',array('conditions'=>array('list_id'=>'sen'),'fields'=>array('id','name')));
		$this->set('list',$list);
		if($this->request->is('post')){
			$this->Option->saveSen($this->data,$pupil_id);
			$this->redirect(array('controller'=>'pupils','action'=>'view',$pupil_id));
		}
	}
	
	public function senEdit($pupil_id){
		$this->set('name',ClassRegistry::init('Pupil')->field('name'));
		$list = $this->Option->find('all',array('conditions'=>array('list_id'=>'sen'),'fields'=>array('id','name')));
		$this->set('list',$list);
		$this->set('defaults',$this->Option->getSenIds($pupil_id));
		if($this->request->is('post')){
			$this->Option->clearEdit($pupil_id);
			$this->Option->saveSen($this->data,$pupil_id);
			$this->redirect(array('controller'=>'pupils','action'=>'view',$pupil_id));
		}
	}
	
	public function addSubject(){
		$get = $this->params['url']['data']['sub'];
		$check = $this->Option->find('all',array('conditions'=>array('name'=>$get)));
		if(empty($check)){
			$this->Option->Query("INSERT INTO options (name,created,list_id) VALUES ('".$get."','".date('Y-m-d',time())."','subjects');");
			$this->redirect(array('controller'=>'activitys','action'=>'add'));
		}else{
			$this->Session->setFlash("That subject is already on the list");
			$this->redirect(array('controller'=>'activitys','action'=>'add'));
		}
		
		
	}
	
}