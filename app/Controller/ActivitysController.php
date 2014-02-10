<?php
class ActivitysController extends AppController{

	public function index($index = null){
		//control the accordion by passing the index (0 index like an array) of the activity to the view '---' means all closed '11-' would be second arrordion, second subject open all activities closed etc
		
		if(!empty($index)){$this->set('index',$index);}else{$this->set('index','---');}
		$staff_id = 31;
		$structure = $this->Activity->getAccordion();
		$this->set('accordion',$this->Activity->getHtml($structure,$staff_id));
		$this->set('staff_id',$staff_id);
		if($this->request->is('post')){
			#if(empty($ids)){$this->redirect(array('action'=>'index'));}
			$ids = '';//pack the selected ids into a string for an IN() query
			foreach($this->data as $k => $v){$ids .= $k.',';}
			$this->redirect(array('controller'=>'Outcomes','action'=>'add',substr($ids,0,-1)));
		}
	}
	
	public function dindex($index = null){//this is for the dialysis team
		//control the accordion by passing the index (0 index like an array) of the activity to the view '---' means all closed '11-' would be second arrordion, second subject open all activities closed etc
		
		if(!empty($index)){$this->set('index',$index);}else{$this->set('index','---');}
		$staff_id = 26;
		$structure = $this->Activity->getdAccordion();
		$this->set('accordion',$this->Activity->getdHtml($structure,$staff_id));
		$this->set('staff_id',$staff_id);
		if($this->request->is('post')){
			if(empty($this->data)){$this->redirect(array('action'=>'dindex'));}
			$activities_ids = '';
			$pupils_ids = '';
			foreach($this->data as $ids => $i){
				$id = explode('-',$ids);
				$pupils_ids .= $id[0].',';
				$activities_ids .= $id[1].',';
			}
			$this->redirect(array('controller'=>'Outcomes','action'=>'add',substr($activities_ids,0,-1),date('Y-m-d',time()),substr($pupils_ids,0,-1)));
		}
	}
	
	public function add(){
		$this->set('areas',ClassRegistry::init('Option')->getAreas());
		$this->set('subjects',ClassRegistry::init('Option')->getSubjects());
		$this->set('staff_id',31);
		$this->set('dus',ClassRegistry::init('Pupil')->getDialysisPupils());
		if($this->request->is('post')){
			$this->Activity->save($this->data);
			if($this->data['Activity']['area_id'] == 1040){// send DUS team to dindex
				$this->redirect(array('action'=>'dindex',$this->Activity->getdPath($this->Activity->getLastInsertId())));
			}else{// everyone else goes to standard index
				$this->redirect(array('action'=>'index',$this->Activity->getPath($this->Activity->getLastInsertId())));
			}
		}
	}
	
	public function edit($id){
		$this->set('areas',ClassRegistry::init('Option')->getAreas());
		$this->set('subjects',ClassRegistry::init('Option')->getSubjects());
		$this->set('staff_id',31);	
		if (!$id) {throw new NotFoundException(__('je crois que non'));}
		$activity = $this->Activity->findById($id);
		if (!$activity) {throw new NotFoundException(__('je crois que non'));}
		if ($this->request->is(array('post', 'put'))) {
			$this->Activity->id = $id;
			if ($this->Activity->save($this->request->data)) {
				return $this->redirect(array('action' => 'index',$this->Activity->getPath($id)));
			}
			$this->Session->setFlash(__('je crois que non'));
		}
		if (!$this->request->data) {$this->request->data = $activity;}
	}
	
	public function delete($id,$staff_id,$confirmed = null){
		$this->set('activity',$this->Activity->find('all',array('conditions'=>array('id'=>$id))));
		$this->set('staff_id',$staff_id);
		if(isset($confirmed)){
			if($confirmed == '1'){//remove the activity and dependancies
				$this->Activity->Query("DELETE FROM activities WHERE id=".$id.";DELETE FROM outcomes WHERE activity_id=".$id.";");
			}
			$this->redirect(array('action'=>'index'));
		}		
	}
	
	public function hide($id){
		$index = substr($this->Activity->getPath($id),0,-1)."-";
		$this->Activity->query("UPDATE activities SET hide=1 WHERE id =".$id);
		$this->redirect(array('action'=>'index',$index));
	}
	
	public function show(){
		if(!empty($index)){$this->set('index',$index);}else{$this->set('index','---');}
		$staff_id = 31;
		$structure = $this->Activity->getAccordion(1);
		$this->set('accordion',$this->Activity->getHtml($structure,$staff_id));
		$this->set('staff_id',$staff_id);
		if($this->request->is('post')){
			#if(empty($ids)){$this->redirect(array('action'=>'index'));}
			$ids = '';//change all the ids to hide=0
			foreach($this->data as $k => $v){
				$this->Activity->query("UPDATE activities SET hide=0 WHERE id=".$k);
				$path = $this->Activity->getPath($k);
			}
			$this->redirect(array('action'=>'index',$path));
			
		}	
	}
}
?>