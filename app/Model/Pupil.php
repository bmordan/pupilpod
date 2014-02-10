<?php

class Pupil extends AppModel{
	
	public $hasMany = array('pupils_sens','contact');
	
	public $virtualFields = array('name' => 'CONCAT(first_name," ",last_name)');
	
	public function json($multi = null){//this function returns an array in json format for the pupil autocomplete both single and multi - pass 'true' to turn on multi format
		$array = $this->find('all',array('fields' => array('id','name'),'order' => 'Pupil.first_name'));
		$json = '[';
		
		if($multi == 'false'){
		//single selector format
			foreach($array as $k => $v){
				$json .= '{"value":"'.$v['Pupil']['id'].'","label":"'.$v['Pupil']['name'].'"}';
				if($k != count($array)-1){$json .= ',';}
			}
		}else{
			foreach($array as $k => $v){
				$json .= '{"id":"'.$v['Pupil']['id'].'","name":"'.$v['Pupil']['name'].'"}';
				if($k != count($array)-1){$json .= ',';}	
			}
		}	
		
		$json .= ']';
		return $json;
	}	
	function getAge($dob){//this function returns an age from $dob formatted as 'dd-mm-yyyy'
		list($d,$m,$y) = explode('-', $dob);	
		if(($m = (date('m') - $m)) < 0){$y++;}elseif($m == 0 && date('d') - $d < 0){$y++;}
		return date('Y') - $y;
	}
	
	function getName($id){
		$name = $this->find('first',array('conditions'=>array('id'=>$id),'field'=>array('name')));
		if(!empty($name['Pupil']['name'])){return $name['Pupil']['name'];}
	}
	
	function getArea($id){
		$this->id = $id;
		return $this->field('option_id');
	}
	
	function getGender($id){
		$gender = $this->find('first',array('conditions'=>array('id'=>$id),'field'=>array('gender')));
		if($gender['Pupil']['gender'] == 0){return 'his';}else{return 'her';}
	}
	
	function getPupilView($id){//this function tweeks the database data into presentable info to view
		if (!$id) {throw new NotFoundException(__('Nope. Try another'));}
		$pupil = $this->findById($id);
		if (!$pupil) {throw new NotFoundException(__('Nope. Try another'));}
		
		$name = array();
		$pupil = array();
		$sens = array();
		$contacts = array();
		$school = array();
		$targets = array();
		
		//get data
		$data = $this->find('all',array('conditions'=>array('id'=>$id)));
		$data1 = ClassRegistry::init('school')->find('all',array('conditions'=>array('id'=>$data[0]['Pupil']['school_id'])));
		//get presentable variables
		if($data[0]['Pupil']['gender'] == '0'){$gender = 'Male';}else{$gender = 'Female';}
		if(!empty($data[0]['Pupil']['ethnicity'])){$ethnicity = $data[0]['Pupil']['ethnicity'];}else{$ethnicity = 'None Recorded';}
		if($data[0]['Pupil']['sibling'] == '1'){$sibling = 'Sibling';}else{$sibling = 'Patient';}
		if($data[0]['Pupil']['dialysis'] == '1'){$dialysis = ' [D]';}else{$dialysis = '';}
		$yeargroupkeystage = $this->getAgeYearKeyStage(date('d-m-Y',strtotime($data[0]['Pupil']['date_of_birth'])));
		$yeargroup = $yeargroupkeystage[0];
		$keystage = $yeargroupkeystage[1];
		if(isset($data[0]['pupils_sens'])){
			foreach($data[0]['pupils_sens'] as $k => $a){
				array_push($sens,ClassRegistry::init('Option')->getOption($a['option_id']));
			}
		}
		if(!empty($data[0]['Pupil']['notes'])){
			array_push($sens,$data[0]['Pupil']['notes']);
		}
		if(!empty($data[0]['contact'])){
			foreach($data[0]['contact'] as $n => $l){
				array_push($contacts,$l);
			}
		}
		
		//build pupil array
		$name[] = array(
			"id" => $data[0]['Pupil']['id'],
			"name" => $data[0]['Pupil']['name'].$dialysis
		);
		$pupil[] = array(
			"date of birth" => date('d/m/Y',strtotime($data[0]['Pupil']['date_of_birth'])),
			"age" => $this->getAge(date('d-m-Y',strtotime($data[0]['Pupil']['date_of_birth']))),
			"gender" => $gender,
			"ethnicity" => $ethnicity,
			"status" => $sibling,
			"year group" => $yeargroup,
			"key stage" => $keystage
		);
		if(!empty($data1)){
			$school[] = array(
				"name" => $data1[0]['school']['name'],
				"address" => array(
					$data1[0]['school']['name'],
					$data1[0]['school']['address_1'],
					$data1[0]['school']['address_3'],
					$data1[0]['school']['locality'],
					$data1[0]['school']['city'],
					$data1[0]['school']['postcode'],
					$data1[0]['school']['la_name'],
					'phone | '.$data1[0]['school']['phone_number'],
					$data1[0]['school']['school_type']
				)
			);
		}else{
			$school[] = array(
				"name" => "No School Found",
				"address" => array(
					"May not be statutory school age",
					"May not be enrolled at a school",
					"School is not known"
				)
			);		
		}

		return array($name,$pupil,$sens,$school,$contacts);
	}
	
	function getAgeYearKeyStage($dob){//this function works out year group and key stage from dob 'dd-mm-yyyy'
			
		// get the pupils age on 31st Aug
			$age = $this->getAge($dob);
			$explode = explode('-',$dob);
			if($explode[1] < 9){
				$ygTest = $age;
			}else{
				$ygTest = $age-1;
			}
		// put them in a year group
			switch($ygTest){
				case null:$yg = 'no date of birth';break;
				case '2':$yg = 'Pre-School';break;
				case '3':$yg = 'Pre-School';break;
				case '4':$yg = 'Reception';break;
				case '5':$yg = 'Year 1';break;
				case '6':$yg = 'Year 2';break;
				case '7':$yg = 'Year 3';break;
				case '8':$yg = 'Year 4';break;
				case '9':$yg = 'Year 5';break;
				case '10':$yg = 'Year 6';break;
				case '11':$yg = 'Year 7';break;
				case '12':$yg = 'Year 8';break;
				case '13':$yg = 'Year 9';break;
				case '14':$yg = 'Year 10';break;
				case '15':$yg = 'Year 11';break;
				case '16':$yg = 'School Leaver';break;
				case '17':$yg = 'School Leaver';break;
				case '18':$yg = 'School Leaver';break;
			}
					
		// assign the Key Stage	
			switch($ygTest){
				case '4':$ks = 'Key Stage 1';break;
				case '5':$ks = 'Key Stage 1';break;
				case '6':$ks = 'Key Stage 1';break;						
				case '7':$ks = 'Key Stage 2';break;
				case '8':$ks = 'Key Stage 2';break;
				case '9':$ks = 'Key Stage 2';break;
				case '10':$ks = 'Key Stage 2';break;						
				case '11':$ks = 'Key Stage 3';break;
				case '12':$ks = 'Key Stage 3';break;						
				case '13':$ks = 'Key Stage 3';break;
				case '14':$ks = 'Key Stage 4 GCSE';break;
				case '15':$ks = 'Key Stage 4 GCSE';break;
				case '16':$ks = 'School Leaver A-level,Btec etc';break;						
				case '17':$ks = 'School Leaver A-level,Btec etc';break;
				case '18':$ks = 'School Leaver A-level,Btec etc';break;						
				case '19':$ks = 'School Leaver A-level,Btec etc';break;												
				default:$ks = 'Can\'t place into a Key Stage';break;	
			}
			return array($yg,$ks);
			
	}
	
	function addOptionId($pupil_id){
		$this->id = $pupil_id;
		$dialysis = $this->field('dialysis');
		$dob = strtotime($this->field('date_of_birth'));
		$today = date('Y-m-d',time());
		$Y = date('Y',time());
		if(date('n',time())<8){$Y = $Y - 1;}
		$thisY = $Y.'-08-31';
		$EYFS = $Y-4;$EYFS_END = $Y-2;
		$PRI = $Y-10;$PRI_END = $Y-5;
		$SEC = $Y-19;$SEC_END = $Y-11;
		
		if($dob >= strtotime($EYFS.'-08-31') && $dob <= strtotime($EYFS_END.'-08-31')){
			$this->query("UPDATE pupils SET option_id=1037 WHERE id=".$pupil_id);
		}
		if($dob >= strtotime($PRI.'-08-31') && $dob <= strtotime($PRI_END.'-08-31')){
			$this->query("UPDATE pupils SET option_id=1038 WHERE id=".$pupil_id);
		}
		if($dob >= strtotime($SEC.'-08-31') && $dob <= strtotime($SEC_END.'-08-31')){
			$this->query("UPDATE pupils SET option_id=1039 WHERE id=".$pupil_id);
		}
		if($dialysis == '1'){
			$this->query("UPDATE pupils SET option_id=1040 WHERE id=".$pupil_id);
		}
	}
	
	public function getPupils($ids){
		$sql = "SELECT * FROM pupils WHERE pupils.id IN(".$ids.")";
		return $this->Query($sql);
	}
		
	function getSearchResults($string){
		$terms = explode(' ',$string);
		$pupils = $this->find('list',array('fields'=>array('id','name')));
		$results = array();
		foreach($pupils as $id => $name){
			similar_text($string,$name,$percent);
			if($percent >65){
				array_push($results,array($id,$name,$percent));
			}
		}
		function sorter($a,$b){
			return $b[2] - $a[2];
		}
		usort($results,'sorter');
		return $results;
	}
	
	function getDialysisPupils(){
		$list = $this->find('list',array('conditions'=>array('dialysis'=>1),'fields'=>array('id','name')));
		return $list;
	}
	
}
?>