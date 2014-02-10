<?php



class ContactsController extends AppController{

	public function add($pupil_id){
		$this->set('pupil_id',$pupil_id);
		ClassRegistry::init('Pupil')->id = $pupil_id;
		$this->set('name',ClassRegistry::init('Pupil')->field('name'));
		$this->set('sen',ClassRegistry::init('Pupil')->field('sen'));
		$contacts = $this->Contact->find('all',array('conditions'=>array('pupil_id'=>$pupil_id)));
		if(empty($contacts)){
			$this->set('contacts',false);
		}else{
			$this->set('contacts',$contacts);
		}
		if($this->request->is('post')){
			if($this->Contact->save($this->data)){
				$this->redirect(array('action'=>'add',$pupil_id));
			}
		}
	}
	
	public function edit($id = null,$pupil_id = null) {
	
		if (!$id) {throw new NotFoundException(__('No Id No Contact'));}
		$contact = $this->Contact->findById($id);
		if (!$contact) {throw new NotFoundException(__('Invalid Contact'));}
		$this->set('pupil_id',$pupil_id);
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->Contact->id = $id;
			$check = $this->Contact->find('all',array('conditions'=>array('report'=>'1')));
			if ($this->Contact->save($this->request->data)) {
				$this->Session->setFlash(__('Contact updated'));
				return $this->redirect(array('controller'=>'Pupils','action' => 'view',$pupil_id));
			}
			$this->Session->setFlash(__('Unable to update Contact'));
		}
	

		if (!$this->request->data) {
			$this->request->data = $contact;
		}
	}
	
	public function delete($id,$pupil_id){
		if($this->Contact->delete($id)){
			$this->redirect(array('controller'=>'Pupils','action'=>'view',$pupil_id));
		}		
	}
	

}