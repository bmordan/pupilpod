<?php
class PupilsController extends AppController{
    public function index(){
        $this->set('pupils',$this->Pupil->find('all'));
    }
	
	public function add(){
	// fetch list of leas and format for autocomplete:
		$leas = ClassRegistry::init('Option')->find('all',array('fields'=>array('id','name'),'conditions'=>array('list_id'=>'la')));
		$this->set('leas',ClassRegistry::init('Option')->getJson($leas));
	// fetch the schools list from file
		$schools = file_get_contents('http://10.23.28.5/mis/js/schools.json');
		$this->set('schools',$schools);
	// fetch the ethnicity
		$this->set('ethnicity',ClassRegistry::init('Option')->getEthnicity());
	//form submission	
		if($this->request->is('post')){	
			if($this->Pupil->save($this->data)){
				//add optionId
				$this->Pupil->addOptionId($this->Pupil->id);
				$this->redirect(array('controller'=>'contacts','action'=>'add',$this->Pupil->id));
			}else{
				$this->Session->setFlash('Oh no didn\'t work');
			}
		
		}
	}
	
	public function view($id){
		$this->set('title_for_layout',$this->Pupil->getName($id).' | '.$id);
		$this->set('data',$this->Pupil->getPupilView($id));
		$this->set('targets',ClassRegistry::init('Target')->getPupilTargets($id));
		$dates = ClassRegistry::init('Attendance')->getTermDates(date('Y-m-d',time()));
		$this->set('attendance',ClassRegistry::init('Attendance')->getRegister($id,$dates[0],$dates[1]));
	}
	
	public function edit($id = null){
	
		if(!$id){throw new NotFoundException(__('No pupil with that id'));}
		$pupil = $this->Pupil->findById($id);
		if(!$pupil){throw new NotFoundException(__('No pupil with that id'));}
		//default values for the lea and school autocomplete
		// fetch list of leas and format for autocomplete:
		$leas = ClassRegistry::init('Option')->find('all',array('fields'=>array('id','name'),'conditions'=>array('list_id'=>'la')));
		$this->set('leas',ClassRegistry::init('Option')->getJson($leas));
		// fetch the schools list from file
		$schools = file_get_contents('http://10.23.28.5/mis/js/schools.json');
		$this->set('schools',$schools);		
		$this->set('lea',ClassRegistry::init('Option')->getOption($pupil['Pupil']['lea_id']));
		$this->set('school',ClassRegistry::init('School')->getSchool($pupil['Pupil']['school_id']));
		// fetch the ethnicity
		$this->set('ethnicity',ClassRegistry::init('Option')->getEthnicity());
		$this->set('ethnicityDefault',$pupil['Pupil']['ethnicity']);
		
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->Pupil->id = $id;
			if ($this->Pupil->save($this->request->data)) {
				$this->Pupil->addOptionId($id);
				return $this->redirect(array('action' => 'view',$id));
			}
			$this->Session->setFlash(__('Update failed'));
		}

		if (!$this->request->data) {$this->request->data = $pupil;}
	
	}

	
	public function report($pupil_id,$date = null){
		if(!isset($date)){$date = date('Y-m-d',time());}
		$period = ClassRegistry::init('Attendance')->getTermDates($date);
		$data = ClassRegistry::init('Activity')->getActivityReportStructure($pupil_id,$period);
		$this->set('data',$data);
	}
	
	public function delete($id,$confirm = null){
	#	if($this->request->is('get')){throw new MethodNotAllowedException();}
		$this->set('pupil',$this->Pupil->Find('all',array('conditions'=>array('id'=>$id))));
		$this->set('records',ClassRegistry::init('Target')->getRecords($id));
		if($confirm == '1'){
			$this->Pupil->delete($id);
			$this->Session->setFlash('Pupil Deleted');
			return $this->redirect(array('controller'=>'staffs','action' => 'index'));
		}		
	}
	
	public function search($string = null){
		$results = $this->Pupil->getSearchResults($string);
		$this->set('results',$results);
	}
	
}
?>