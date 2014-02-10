<?php
class TargetsController extends AppController{
	public function index($pupil_id = null){
		$this->set('targets',$this->Target->find('all'));
	}
	public function add($staff_id,$pupil_id = null){
		$this->set('subjects',ClassRegistry::init('Option')->getSubjects());
		$this->set('json',ClassRegistry::init('Pupil')->json('false'));
		$this->set('staff_id',$staff_id);
		$this->set('targets',$this->Target->getTargets($staff_id));
		if(isset($pupil_id)){
			$this->set('pupil_id',$pupil_id);
			$this->set('name',ClassRegistry::init('Pupil')->getName($pupil_id));
			}
		
		if($this->request->is('post')){
			$this->Target->preSave($this->data);
			$this->redirect(array('action'=>'add',$staff_id));
		}
	}
	
	public function edit($id){
		$target = $this->Target->findById($id);
		$staff_id = $target['Target']['staff_id'];
		$this->set('id',$id);
		$this->set('json',ClassRegistry::init('Pupil')->json('false'));
		$this->set('subjects',ClassRegistry::init('Option')->getSubjects());
		$this->set('pupils',$this->Target->Query("SELECT * FROM pupils_targets WHERE target_id=".$id));
		$this->set('staff_id',$staff_id);
		$this->set('targets',$this->Target->getTargets($staff_id));

		if(!$id) {throw new NotFoundException(__('Invalid post'));}

		
		if(!$target) {throw new NotFoundException(__('error'));}

		if($this->request->is(array('post', 'put'))) {
			$this->Target->id = $id;
			if($this->Target->save($this->request->data)) {
				return $this->redirect(array('action' => 'edit',$id));
			}
		}
		
		if (!$this->request->data){$this->request->data = $target;}
		

	}
	
	function removePupil($id,$target_id){
		$this->Target->Query("DELETE FROM pupils_targets WHERE id=".$id);
		$this->redirect(array('action'=>'edit',$target_id));
	}
	
	function addPupil($pupil_id,$target_id){
		$check = $this->Target->Query("SELECT * FROM pupils_targets WHERE target_id=".$target_id." AND pupil_id=".$pupil_id);
		if(empty($check)){
			$this->Target->Query("INSERT INTO pupils_targets (target_id,pupil_id) VALUES(".$target_id.",".$pupil_id.")");
			$this->redirect(array('action'=>'edit',$target_id));
		}
	}
	
	public function delete($id,$staff_id,$confirmed = null){
		$this->set('target',$this->Target->find('all',array('conditions'=>array('id'=>$id))));
		$this->set('staff_id',$staff_id);
		if(isset($confirmed)){
			if($confirmed == '1'){//remove the target and dependancies
				$this->Target->Query("DELETE FROM targets WHERE id=".$id.";DELETE FROM pupils_targets WHERE target_id=".$id.";UPDATE activities SET target_id=1 WHERE target_id=".$id.";");
			}
			$this->redirect(array('action'=>'add',$staff_id));
		}
	}
}
?>