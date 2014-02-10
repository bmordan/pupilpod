<?php
class School extends AppModel{
	public function getSchool($id){
		$school = $this->find('all',array('conditions'=>array('id'=>$id),'fields'=>array('name','address_1')));
		return $school[0]['School']['name'].' | '.$school[0]['School']['address_1'];
	}
	
	public function getSchoolEmail($pupil_id){
		$email = $this->Query("SELECT schools.email FROM schools JOIN pupils ON pupils.school_id=schools.id WHERE pupils.id=".$pupil_id);
		if(!empty($email[0]['schools']['email'])){return $email[0]['schools']['email'];}
		
	}	
	
	function updateEmails($array){//this function updates the email field in the schools table
		foreach($array as $id => $email){
			if(!empty($email)){
				$this->Query('UPDATE schools SET email="'.$email.'" WHERE id='.$id.';');
			}
		}
	}
}
?>