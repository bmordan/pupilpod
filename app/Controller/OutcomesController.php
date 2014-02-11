<?php
class OutcomesController extends AppController{

	public function index(){
		$this->set('outcomes',$this->Outcome->find('all'));
	}
	
	public function add($activity_ids,$date = null,$pupils_ids = null){
	
		if(empty($date)){$date = date('Y-m-d',time());}
		
		$this->set('json',ClassRegistry::init('Pupil')->json('true'));
		$activitys = $this->Outcome->Query("SELECT * FROM activities WHERE id IN(".$activity_ids.")");
		$this->set('date',$date);
		$this->set('activity_ids',$activity_ids);
		$this->set('activitys',$activitys);
		
		if(isset($pupils_ids)){ //for dialysis skip the pupil selector part
			$pupils = ClassRegistry::init('Pupil')->getPupils($pupils_ids);
			$forms = $this->Outcome->getForms($pupils,$activity_ids);
			$this->set('forms',$forms);
		}
		
		if($this->request->is('post')){
			if(isset($this->data['pupilList'])){
				//query all the pupils
				$pupils = ClassRegistry::init('Pupil')->getPupils($this->data['pupilList']['ids']);
				//build pupil outcome forms
				$forms = $this->Outcome->getForms($pupils,$activity_ids);
				$this->set('forms',$forms);
			}else{
				$this->Outcome->processForms($this->data,$date);
				$this->redirect(array('controller'=>'Staffs','action'=>'index',$this->Auth->user('id')));
			}
			
		}
	}
	
	public function edit($id,$pupil_id) {
		if (!$id) {throw new NotFoundException(__('nope'));}

		$outcome = $this->Outcome->findById($id);
		
		if (!$outcome) {throw new NotFoundException(__('nope'));}

		if ($this->request->is(array('post', 'put'))) {
			$this->Outcome->id = $id;
			if ($this->Outcome->save($this->request->data)) {
				
				$this->redirect(array('action' => 'view',$pupil_id));
			}
		}

		if (!$this->request->data) {$this->request->data = $outcome;}
	}
	
	public function view($pupil_id,$date = null,$report = null){//view a pupils report for this term
		$subject_ids = $this->Outcome->getSubjects($pupil_id);
		$this->set('name',ClassRegistry::init('Pupil')->getName($pupil_id));
		$this->set('pupil_id',$pupil_id);
		
		if(empty($date)){$date = date('Y-m-d',time());}
		$this->set('date',$date);
		$dates = ClassRegistry::init('Attendance')->getTermDates($date);
		$this->set('reportNotes',ClassRegistry::init('Note')->getReportNotes($pupil_id,$dates));
		
		
		if($this->request->is('post')){
			$a = $this->data['settings']['attendance'];
			$t = $this->data['settings']['targets'];
			$act = $this->data['settings']['activities'];
			$o = $this->data['settings']['outcomes'];
				//sort out other combos
				if($a.$t.$act.$o == '0000'){
					$this->Session->setFlash('Whats the point? Choose something. Losser.');
					$this->redirect(array('action'=>'view',$pupil_id));
				}
				if($a.$t.$act.$o == '0001'){$t='0';$act='1';}
				if($t.$act == '01'){$t = '1';$this->set('hideTargets','<script>$(".reports-targets").hide();</script>');}
			$schoolemail = 	ClassRegistry::init('School')->getSchoolEmail($pupil_id);
			$this->set('schoolemail',$schoolemail);
			$subjects = $this->Outcome->getSubjectsRender($pupil_id,$subject_ids,$a,$t,$act,$o,$date);
			$this->set('subjects',$subjects);
			$this->set('settings',$a.$t.$act.$o.'|'.$date);
		}
		
	}
	
	public function remove($id,$pupil_id,$confirm = null){
		$this->set('row',$this->Outcome->find('all',array('conditions'=>array('id'=>$id))));
		$this->set('id',$id);
		$this->set('pupil_id',$pupil_id);
		if($confirm == 1){
			$this->Outcome->delete($id);
			$this->redirect(array('action'=>'view',$pupil_id));
		}
	}
}
?>