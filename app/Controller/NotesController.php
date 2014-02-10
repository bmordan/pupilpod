<?php
class NotesController extends AppController{

	public function view($staff_id,$pupil_id = null){
		$this->set('staff_id',$staff_id);
		if(empty($pupil_id)){
			$this->set('notes',$this->Note->find('all',array('conditions'=>array('staff_id'=>$staff_id,'reportstatus' => null),'order'=>array('date'=>'DESC'))));
		}else{
			$this->set('pupil_id',$pupil_id);
			$this->set('notes',$this->Note->find('all',array('conditions'=>array('pupil_id'=>$pupil_id),'order'=>array('date'=>'DESC'))));
		}
		
		
	}
	
	public function add($staff_id,$pupil_id = null){
		
		if(date('H',time()) > 12){$i=1;}else{$i=0;}	
		$dayindex = date('w',time()).$i;	
		$this->set('dayindex',$dayindex);
		$this->set('staff_id',$staff_id);
		
			if(!empty($pupil_id)){
				$this->set('pupil_id',$pupil_id);
				$this->set('name',ClassRegistry::init('Pupil')->getName($pupil_id));
			}else{
				$this->set('json',ClassRegistry::init('Pupil')->json('false'));
			}
			

		if($this->request->is('post')){
			if(!empty($this->data['pupil_id'])){$pupil_id = $this->data['pupil_id'];}
			$this->Note->save($this->data);
			$id = $this->Note->getLastInsertId();
			if(isset($pupil_id)){$this->Note->query("UPDATE notes SET pupil_id=".$pupil_id." WHERE id=".$id);}
			$this->redirect(array('action'=>'view',$staff_id));
		}		
	}
	
	public function edit($id = null) {
		if (!$id) {
			throw new NotFoundException(__('Aucune baise façon lady'));
		}

		$note = $this->Note->findById($id);
		$staff_id = $note['Note']['staff_id'];
		
		if (!$note) {
			throw new NotFoundException(__('Aucune baise façon lady'));
		}
		
		if ($this->request->is(array('post', 'put'))) {
			$this->Note->id = $id;
			if ($this->Note->save($this->request->data)) {
				return $this->redirect(array('action' => 'view',$staff_id));
			}
		}

		if (!$this->request->data) {
			$this->request->data = $note;
		}
	}
	
	public function comment($staff_id,$pupil_id,$date = null){
		if(empty($date)){$date = date('Y-m-d',time());}
		$this->set('pupil_id',$pupil_id);
		$this->set('staff_id',31);
		$this->set('date',$date);
		$this->set('name',ClassRegistry::init('Pupil')->getName($pupil_id));
		$this->set('subjects',ClassRegistry::init('Outcome')->getSubjects($pupil_id));
		$dates = ClassRegistry::init('Attendance')->getTermDates($date);
		$check = $this->Note->query("SELECT * FROM notes WHERE pupil_id=".$pupil_id." AND date BETWEEN ".$dates[0]." AND ".$dates[1]." AND tcomment IS NOT NULL;");
		
		if(!empty($check)){
			$this->set('notes',$check);
		}
		
		if($this->request->is('post')){	
			if(empty($check)){
				$tcomment = $this->Note->compactComment($this->data);
				$this->Note->save($this->data);
				$this->Note->query("UPDATE notes SET tcomment=\"".$tcomment."\",note='Final Report Summary Added' WHERE id =".$this->Note->getLastInsertId());
				$this->redirect(array('controller'=>'outcomes','action'=>'view',$pupil_id));
			}else{
				$this->redirect(array('action'=>'edit',$check[0]['notes']['id']));
				$this->session->setFlash('This report summary has already been written. Edit this one to update this pupil\'s report');
			}	
		}
	}	
	
	public function delete($id,$confirm = null){
		$delete = $this->Note->findById($id);
		$this->set('delete',$delete);
		$staff_id = $delete['Note']['staff_id'];
		
		if(isset($confirm) && $confirm == 1){
			$this->Note->delete($id);
			$this->redirect(array('action'=>'view',$staff_id));
		}
	}
}