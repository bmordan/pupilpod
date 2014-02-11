<?php
class Activity extends AppModel{
	
	public $hasMany = 'Outcome';

	function getAccordion($hidden = null){
		if($hidden != 1){$hide = 0;}else{$hide = 1;}
		$areas = ClassRegistry::init('Option')->find('list',array('conditions'=>array('list_id'=>'area')));
		$structure = array();
		foreach($areas as $k => $v){
			$area = array();
			$area[$k] = $this->Query("SELECT DISTINCT subject_id FROM activities WHERE hide=".$hide." AND area_id=".$k." ORDER BY subject_id");
			foreach($area[$k] as $i => $asid){
				array_push($area[$k][$i],$this->Query("SELECT DISTINCT id FROM activities WHERE area_id=".$k." AND subject_id=".$asid['activities']['subject_id']." AND hide=".$hide.";"));
			}
			array_push($structure,$area);
		}
		#return $structure;
		return $structure;
	}
	
	function getdAccordion(){
		$pupils = ClassRegistry::init('Pupil')->getDialysisPupils();
		$structure = array();
		foreach($pupils as $id=>$name){
			$pupil = array();
			$pupil[$id] = $this->query("SELECT DISTINCT subject_id FROM activities WHERE pupil_id=".$id);
			foreach($pupil[$id] as $i => $sid){
				array_push($pupil[$id][$i],$this->query("SELECT DISTINCT id FROM activities WHERE area_id=1040 AND hide=0 AND subject_id=".$sid['activities']['subject_id']." AND pupil_id=".$id));
			}
			array_push($structure,$pupil);
		}
		return $structure;
	}
	
	function getdPath($activity_id){
		$tree = $this->getdAccordion();
		$array = array();
		foreach($tree as $k1 => $v1){
			foreach($v1 as $k2 => $v2){
				foreach($v2 as $k3 => $v3){
					foreach($v3 as $k4 => $v4){
						foreach($v4 as $k5 => $v5){
							if(is_array($v5) && $activity_id == $v5['activities']['id']){$path = $k1.$k3.$k5;}
						}
					}
				}				
			}
		}
		return $path;		
	}
	
	function getPath($activity_id){// this function returns three INTs which locate an activity in the accordion so you can pass this to the index page and have that activity spring open
		$tree = $this->getAccordion();
		$array = array();
		foreach($tree as $k1 => $v1){
			foreach($v1 as $k2 => $v2){
				foreach($v2 as $k3 => $v3){
					foreach($v3 as $k4 => $v4){
						foreach($v4 as $k5 => $v5){
							if(is_array($v5) && $activity_id == $v5['activities']['id']){$path = $k1.$k3.$k5;}
						}
					}
				}
			}
		}		
		return $path;
	}
	
	function getActivity($id,$staff_id,$pupil_id = null){// this function renders an activity from the database into an html accordion insert with select button, edit and delete
		$a = $this->find('all',array('conditions'=>array('id'=>$id)));
			$html = '<h3>'.$a[0]['Activity']['title'].'</h3>';
			$html .= '<div>';
				$html .= '<p>'.$a[0]['Activity']['activity'].'</p>';
				
				if(isset($pupil_id)){//something different for dialysis
					$html .= '<p style="width:20%;float:left;font-size:80%;"><input type="checkbox" name="'.$pupil_id.'-'.$id.'" class="regular-checkbox big-checkbox" value="1" id="'.$id.'"><label for="'.$id.'">This One</label></p>';
				}else{
					$html .= '<p style="width:20%;float:left;font-size:80%;"><input type="checkbox" name="'.$id.'" class="regular-checkbox big-checkbox" value="1" id="'.$id.'"><label for="'.$id.'">This One</label></p>';
				}
				
				$html .= '<p class="icons-home">
				<a href="'.Configure::read('webroot').'/activitys/hide/'.$id.'">
				<img src="'.Configure::read('webroot').'/img/icons/eyegray.png">
				</a></p>'; 
				
				$html .= '<p class="icons-home"><a href="'.Configure::read('webroot').'/activitys/delete/'.$id.'/'.$staff_id.'">
				<img src="'.Configure::read('webroot').'/img/icons/delete.png">
				</a></p>';
				
				$html .= '<p class="icons-home">
				<a href="'.Configure::read('webroot').'/activitys/edit/'.$id.'">
				<img src="'.Configure::read('webroot').'/img/icons/edit.png">
				</a></p>';
				
			$html .= '</div>';	

		return $html;
	}
	
	function getHtml($structure,$staff_id){
		$colors = array('#fdc323','#68b821','#fb6e00','#e40a74','#42a1ca');$c = 0;

		$html = '<div id="areas">';
			foreach($structure as $n => $areas){
				foreach($areas as $area => $subjects){
					$html .= '<h3>'.ClassRegistry::init('Option')->getOption($area).'</h3>';
					//open area
					if($c < count($colors)){
						$html .= '<div class="subjects" style="background:'.$colors[$c].';">';++$c;
					}
					
						
						foreach($subjects as $header => $activities){
							$html .= '<h3>'.ClassRegistry::init('Option')->getOption($activities['activities']['subject_id']).'</h3>';
							//open subject
							$html .= '<div class="activities">';
								
									foreach($activities[0] as $k => $a){
										
										$html .= $this->getActivity($a['activities']['id'],$staff_id);

									}
									
							//close subject
							$html .= '</div>';
						}
					
					//close area
					$html .= '</div>';
				}
			}
	
		$html .= '</div>';
		return $html;
	}
	
	function getdHtml($structure,$staff_id){//this function renders the dialysis accordion
		$html = '<div id="areas">';
			foreach($structure as $n => $pupils){
				foreach($pupils as $pupil => $subjects){
					$html .= '<h3>'.ClassRegistry::init('Pupil')->getName($pupil).'</h3>';
					//open area
					$html .= '<div class="subjects" style="background:#e40a74;">';
						
						foreach($subjects as $header => $activities){
							$html .= '<h3>'.ClassRegistry::init('Option')->getOption($activities['activities']['subject_id']).'</h3>';
							//open subject
							$html .= '<div class="activities">';
								
									foreach($activities[0] as $k => $a){
										
										$html .= $this->getActivity($a['activities']['id'],$staff_id,$pupil);

									}
									
							//close subject
							$html .= '</div>';
						}
					
					//close area
					$html .= '</div>';
				}
			}
	
		$html .= '</div>';
		return $html;
	}
	
	function addPupilActivityTarget($pupil_id,$activity_ids,$target_id,$date){//this function inserts or updates the crux table pupils_activities_targets
		
		
		$activity_id = explode(',',$activity_ids);
		$check = $this->query("SELECT * FROM pupils_activities_targets WHERE pupil_id=".$pupil_id." AND activity_id IN(".$activity_ids.") AND date='".$date."';");
		
		if(empty($check)){
			//INSERT if the record is not present
			foreach($activity_id as $k=>$v){
				$this->query("INSERT INTO pupils_activities_targets (pupil_id,activity_id,target_id,date) VALUES (".$pupil_id.",".$v.",".$target_id.",'".$date."');");
			}
			
		}else{
			//UPDATE if the record is present
			foreach($check as $n => $row){
				foreach($activity_id as $k=>$v){
					$this->query("UPDATE pupils_activities_targets SET pupil_id=".$pupil_id.",activity_id=".$v.",target_id=".$target_id.",date='".$date."' WHERE id=".$row['pupils_activities_targets']['id'].";");
				}
			}
		}
	}
	
	function getActivityReportStructure($pupil_id,$period){//this function returns an array with all the relvent ids in the correct place for rendering with html | $period is an array of two dates accessed like $period[0] and $period[1]
		$array = array();
		//get the subject_ids
			$sub_ids = $this->query("SELECT DISTINCT activities.subject_id FROM activities JOIN pupils_activities_targets ON pupils_activities_targets.activity_id=activities.id WHERE pupils_activities_targets.pupil_id=".$pupil_id);
		//add arrays of targets to the subjects	
			foreach($sub_ids as $k=>$v){
			
				$targetArray = $this->query("SELECT targets.id FROM targets JOIN pupils_targets ON pupils_targets.target_id=targets.id WHERE pupils_targets.pupil_id=".$pupil_id." AND targets.option_id=".$v['activities']['subject_id'].";");
				
				$activityArray = $this->query("SELECT activities.id FROM activities JOIN pupils_activities_targets ON activities.id=pupils_activities_targets.activity_id WHERE pupils_activities_targets.pupil_id=".$pupil_id." AND activities.subject_id=".$v['activities']['subject_id'].";");
				
				$structure[$v['activities']['subject_id']] = array($targetArray,$activityArray);
					
			}
			debug($structure);
		//next
		
	}
	
	function getActivityObjective($id){
		$this->id = $id;
		return $this->field('title');
	}
	
	function getActivityField($id){
		$this->id = $id;
		return $this->field('activity');
	}
	
	function getActivityFields($ids){
		$return = $this->find('all',array('conditions'=>array('id'=>$ids)));
		return $return;
	}
	
	function getTarget($id){
		$this->id = $id;
		return $this->field('target');
	}	
	
	function getSubject($activity_id){
		$subject_id = $this->find('first',array('conditions'=>array('id'=>$activity_id),'fields'=>'subject_id'));
		return $subject_id['Activity']['subject_id'];
	}
}
?>