<?php
class Target extends AppModel{	
	
	public $hasAndBelongsToMany = array('Pupil');
	
	function getRecords($pupil_id){//this function creates an array with a pupils targets-activities-outcomes
		$data = array('massive'=>array('Data','Dump'));
	return $data;
	}
	
	function addDefaultTarget($pupil_id){//stop repeat submissions adds default target to pupil
		$test = $this->query('SELECT * FROM pupils_targets WHERE pupil_id='.$pupil_id.' AND id=1');
		if(empty($test)){
			$this->query('INSERT INTO pupils_targets (target_id,pupil_id) VALUES("1","'.$pupil_id.'")');
		}
	}
	
	function preSave($data){//this function breaks apart the data for the two tables targets and pupils_targets
		$targets = array();
		$pupils_targets = array();
		$targets['Target']['staff_id'] = $data['Target']['staff_id'];
		$targets['Target']['option_id'] = $data['Target']['option_id'];
		$targets['Target']['target'] = $data['Target']['target'];
		$targets['Target']['created'] = date('Y-m-d H:i:s',time());
		$this->save($targets);
		$target_id = $this->getLastInsertId();
		if(!empty($data['Target']['pupil_id'])){//if a pupil has been selected join them with this query
			$pupil_id = $data['Target']['pupil_id'];
			$this->Query("INSERT INTO pupils_targets (target_id,pupil_id) VALUES(".$target_id.",".$pupil_id.");");
		}

	}
	
	function getTargets($staff_id){// this function gets a staff members targets arranged by subject
		$subjectList = $this->Query("SELECT DISTINCT option_id FROM targets WHERE staff_id=".$staff_id." AND id > 2 ORDER BY option_id");
		$array = array();
		foreach($subjectList as $n => $subject){
			$newArray = array();
			$newArray[$subject['targets']['option_id']] = $this->Query("SELECT targets.id,targets.target FROM targets JOIN options ON targets.option_id=options.id WHERE targets.option_id=".$subject['targets']['option_id']." AND targets.staff_id=".$staff_id." AND targets.id > 2;");
			array_push($array,$newArray);
		};
		return $array;
	}
	
	function getPupilTargets($pupil_id){// this function gets a pupils targets arranged by subject
		$subjectList = $this->Query("SELECT DISTINCT option_id FROM targets JOIN pupils_targets ON pupils_targets.target_id=targets.id WHERE pupils_targets.pupil_id=".$pupil_id);
		$array = array();
		foreach($subjectList as $n => $subject){
			$newArray = array();
			$newArray[$subject['targets']['option_id']] = $this->Query("SELECT * FROM targets JOIN pupils_targets ON pupils_targets.target_id=targets.id WHERE pupils_targets.pupil_id=".$pupil_id." AND targets.option_id=".$subject['targets']['option_id']);
			array_push($array,$newArray);
		};
		return $array;
	}
	
	function getTarget($id){
		$this->id = $id;
		return $this->field('target');
	}
	
	function getTargetsDropdown($pupil_id,$activity_id = null){//not this query adds generic targets for selection in the UNION statement. If you want to add more change the IN() here and the >2 in getTargets($staff_id) above
		$ids = $this->Query("SELECT target_id FROM pupils_targets WHERE pupil_id=".$pupil_id." UNION SELECT id FROM targets WHERE id IN(1,2)");
		if(isset($activity_id)){
			$html = '<select name="'.$pupil_id.'_'.$activity_id.'-target" id="targets-dropdown">';
		}else{
			$html = '<select name="'.$pupil_id.'_target" id="targets-dropdown">';
		}

			foreach($ids as $k => $v){
				$html .= '<option value="'.$v[0]['target_id'].'">'.$this->getTarget($v[0]['target_id']).'</option>';
			}
		$html .= '</select>';
		return $html;
	}
	
	function setUpTargets(){//this function is for adding the default target to newly imported pupil database
		$pupils = $this->Query("SELECT id FROM pupils");
		$sql = '';
		foreach($pupils as $k=>$v){
			$sql .= 'INSERT INTO pupils_targets VALUES(NULL,1,'.$v['pupils']['id'].');';
		}
		$this->Query($sql);
	}
}
?>