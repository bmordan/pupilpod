<?php
class SchoolsController extends AppController{
	public function addEmails(){
		if($this->request->is('post')){
			$this->School->updateEmails($this->data);
			$this->redirect(array('controller'=>'attendances','action'=>'autoReporting'));
		}
	}
	
	public function edit($id){
		if (!$id) {
			throw new NotFoundException(__('error'));
		}

		$school = $this->School->findById($id);
		if (!$school) {
			throw new NotFoundException(__('error'));
		}

		if ($this->request->is(array('post', 'put'))) {
			$this->School->id = $id;
			if ($this->School->save($this->request->data)) {
				$this->Session->setFlash(__('Your school has been updated.'));
				return $this->redirect(array('controller'=>'attendances','action' => 'autoReporting'));
			}
			$this->Session->setFlash(__('Unable to update your school.'));
		}

		if (!$this->request->data) {
			$this->request->data = $school;
		}	
	}
	
	function emailSchools(){
		
	}
}
?>