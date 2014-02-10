<?php
class Option extends AppModel{

	function getOption($id){//this function returns the name for a id
		$name = $this->find('list',array('conditions'=>array('id'=>$id)));
		return $name[$id];
	}
	public function getJson($array){//this function returns an array in json format for the lea autocomplete
		$json = '[';
		foreach($array as $k => $v){
			$json .= '{"value":"'.$v['Option']['id'].'","label":"'.$v['Option']['name'].'"}';
			if($k != count($array)-1){$json .= ',';}
		}
		$json .= ']';
		return $json;
	}
	function saveSen($array,$pupil_id){//this function saves option_id into pupils_sens
		foreach($array['Sens'] as $k=>$v){
			if($v != '0'){// check so only added once
				$check = $this->Query('SELECT * FROM pupils_sens WHERE pupil_id='.$pupil_id.' AND option_id='.$v);
				if(empty($check)){
					$this->Query('INSERT INTO pupils_sens (pupil_id,option_id) VALUES ("'.$pupil_id.'","'.$v.'")');
				}
			}
		}
		return;
	}
	
	function getSenIds($pupil_id){//this function gets a pupils sen ids
		$sql = $this->query('SELECT * FROM pupils_sens WHERE pupil_id='.$pupil_id);
		$ids = array();
		foreach($sql as $n => $a){
			array_push($ids,$a['pupils_sens']['option_id']);
		}
		return $ids;
	}
	
	function clearEdit($pupil_id){//this clears a pupils previous sen ids before adding edited set
		$this->query('DELETE FROM pupils_sens WHERE pupil_id='.$pupil_id);
	}
	
	public function getSubjects(){
		$subjects = $this->find('all',array('conditions'=>array('list_id'=>'subjects','id !='=>'0'),'fields'=>array('id as value','name as label'),'order'=>'id'));
		$array = array();
		foreach($subjects as $n => $d){
			$options = array('value'=>$d['Option']['value'],'name'=>$d['Option']['label']);
			array_push($array,$options);
		}
		return $array;		
	}
	
	public function getAreas(){
		$areas = $this->find('all',array('conditions'=>array('list_id'=>'area'),'fields'=>array('id as value','name as label')));
		$array = array();
		foreach($areas as $n => $d){
			$options = array('value'=>$d['Option']['value'],'name'=>$d['Option']['label']);
			array_push($array,$options);
		}
		return $array;		
	}	
	
	public function getEthnicity(){
		$array = array();
		$ethnicity = $this->find('all',array('conditions'=>array('list_id'=>'ethnicity'),'fields'=>array('name'),'order'=>'id DESC'));
		foreach($ethnicity as $n => $d){
			$options = array('name'=>$d['Option']['name'],'value'=>$d['Option']['name']);
			array_push($array,$options);
		}
		return $array;
	}
	
}