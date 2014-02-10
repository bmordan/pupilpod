<?php
class Outcome extends AppModel{

	
	function getForms($pupils,$activity_ids,$date = null){//this function takes a pre queried 'Pupil->getPupils($ids)' array of pupils data with targets attached if they have them
		
		if($date == null){$date = date('Y-m-d',time());}
		$day = date('w',strtotime($date));
		
		$activity_id = explode(',',$activity_ids);//explode the list of activities and fetch the data from the activities table put them in $activityArray
		foreach($activity_id as $k => $v){
			$activityArray = ClassRegistry::init('Activity')->getActivityFields($activity_id);
		}
		
		// start to build the html form
		
		$html = '';
		foreach($pupils as $n => $d){

			//pupil name and attendance boxes
				$html .= '<input name="'.$d['pupils']['id'].'_pupilId" value="'.$d['pupils']['id'].'" type="hidden">';
				$html .= '<fieldset><legend><h2>';
				$html .= $d['pupils']['first_name'].' '.$d['pupils']['last_name'];
				//AMPM box
					$html .= '
					<select name="'.$d['pupils']['id'].'_AMPM" style="background:#e40a74;color:#fff;">';
						if(date('H',time())<=12){
							$html .= '<option value="'.$day.'0" selected>AM</option>';
						}else{
							$html .= '<option value="'.$day.'0">AM</option>';
						}
						if(date('H',time())>12){
							$html .= '<option value="'.$day.'1" selected>PM</option>';
						}else{
							$html .= '<option value="'.$day.'1">PM</option>';
						}
					$html .= '</select>';
				//code box
					$html .= '<select name="'.$d['pupils']['id'].'_code" style="background:#e40a74;color:#fff;">';
						foreach(ClassRegistry::init('Attendance')->getCodes() as $a=>$c){
							if(date('H',time())<=12 && $c == '/'){
								$html .= '<option value="'.$a.'" selected>'.$c.'</option>';
							}elseif(date('H',time())>12 && $c == '\\'){
								$html .= '<option value="'.$a.'" selected>'.$c.'</option>';
							}else{
								$html .= '<option value="'.$a.'">'.$c.'</option>';
							}
						}
					$html .= '</select></h2></legend>';
					
					
				//activity target outcomes
				foreach($activityArray as $n => $a){
					
					//target dropdown
						$targets = ClassRegistry::init('Target')->getTargetsDropdown($d['pupils']['id'],$a['Activity']['id']);
						$html .= '<div class="form-item targets-dropdown">'.$targets.'</div>';
					
					//baseline assessments different form
						if($a['Activity']['subject_id'] == '1013'){
							$html .= '<div class="form-item">';
							$html .= '<p style="background:#fdc323;">'.$a['Activity']['activity'].'<input name="'.$d['pupils']['id'].'_'.$a['Activity']['id'].'-baseline" maxlength="4" id="outcomes-baseline-box"></p>';
							$html .= '<input name="'.$d['pupils']['id'].'_'.$a['Activity']['id'].'-expected" type="hidden" value="bl">';
							$html .= '<input name="'.$d['pupils']['id'].'_STOP" value="'.$activity_ids.'" type="hidden">';
							$html .= '</div>';
							
						}else{
					//activities	
							$html .= '<div class="form-item">';
							//populate activity
								$html .= '<p class="outcomes-activity"><strong>Activity: </strong>'.$a['Activity']['activity'].'</p>';
								$html .= '<input name="'.$d['pupils']['id'].'_STOP" value="'.$activity_ids.'" type="hidden"></div>';
							//expected selectors
								$html .= '<div class="form-item">';
									$html .= '<div class="outcomes-form-expected-holder">
									<input name="'.$d['pupils']['id'].'_'.$a['Activity']['id'].'-expected" type="radio" value="-1" class="outcomes-form-expected">
									<label>Below Expected</label>
									</div>';
									$html .= '<div class="outcomes-form-expected-holder">
									<input name="'.$d['pupils']['id'].'_'.$a['Activity']['id'].'-expected" type="radio" value="1" checked class="outcomes-form-expected">
									<label>Expected</label>
									</div>';
									$html .= '<div class="outcomes-form-expected-holder">
									<input name="'.$d['pupils']['id'].'_'.$a['Activity']['id'].'-expected" type="radio" value="1+" class="outcomes-form-expected">
									<label>Above Expected</label>
									</div>';
								$html .= '</div>';
								
							//outcomes and next steps
								$html .= '<div class="form-item">';			
									$html .= '<textarea name="'.$d['pupils']['id'].'_'.$a['Activity']['id'].'-outcomes" placeholder="Evaluation" id="outcomes-eval"></textarea>';
									$html .= '<input name="'.$d['pupils']['id'].'_'.$a['Activity']['id'].'-nextsteps" placeholder="Next steps" id="outcomes-next">';
								$html .= '</div>';
							
							//award toggle
								$html .= '<div><input name="'.$d['pupils']['id'].'_'.$a['Activity']['id'].'-award" type="checkbox" id="outcomes-award"><label></label></div>';
							//end
							$html .= '';
						}
				//end of foreach activities
				}

				
				$html .= '</fieldset>';
		}		
		return $html;
	}
	
	function processForms($data,$date){
		$save = array();
		// divide the form data into pupil arrays
		foreach($data as $k=>$v){
			$id_k = explode('_',$k);
			$pupil[$id_k[0]][$id_k[1]] = $v;	
		}
		
		//deal with each pupil
		foreach($pupil as $pid => $data){
	
			//save attendance Attendance->littleSave($pupil_id,$code,$date,$index)
			$attendance_id = ClassRegistry::init('Attendance')->littleSave($data['pupilId'],$data['code'],$date,$data['AMPM']);
			
			//sort the activitys into arrays and push them into a big save array
			$activity_ids = explode(',',$data['STOP']);
			
			foreach($activity_ids as $n => $aid){
				$subject_id = ClassRegistry::init('Activity')->getSubject($aid);
				$a['Outcome']['pupil_id'] = $pid;
				$a['Outcome']['attendance_id'] = $attendance_id;
				$a['Outcome']['activity_id'] = $aid;
				$a['Outcome']['subject_id'] = $subject_id;
				$a['Outcome']['expected'] = $data[$aid.'-expected'];
					if(!empty($data[$aid.'-outcomes'])){
						$a['Outcome']['outcomes'] = $data[$aid.'-outcomes'];}
					else{
						$a['Outcome']['outcomes'] = null;}
					if(!empty($data[$aid.'-nextsteps'])){
						$a['Outcome']['nextsteps'] = $data[$aid.'-nextsteps'];}
					else{
						$a['Outcome']['nextsteps'] = null;}
				$a['Outcome']['target_id'] = $data[$aid.'-target'];
				if(isset($data[$aid.'-award'])){$a['Outcome']['award'] = 1;}else{$a['Outcome']['award'] = 0;}
				if(isset($data[$aid.'-baseline'])){$a['Outcome']['baseline'] = $data[$aid.'-baseline'];}else{$a['Outcome']['baseline'] = null;}
				array_push($save,$a);			
			}			
		}
		//save each 'row'
		$this->saveMany($save);
	}
	
	function getSubjects($pupil_id){//this function returns the structure for rendering with html a pupils activities by subject
		$sql = $this->query("SELECT DISTINCT subject_id FROM outcomes WHERE pupil_id=".$pupil_id);
		$subject_ids = array();
		foreach($sql as $k=>$v){
			array_push($subject_ids,$v['outcomes']['subject_id']);
		}
		return $subject_ids;
	}
	
	function getSubjectsRender($pupil_id,$subject_ids,$a,$t,$act,$o,$date){
	
		$dates = ClassRegistry::init('Attendance')->getTermDates($date);
		
		$html = '';
		
			if($a == '1'){// if selected get the register
				$html .= ClassRegistry::init('Attendance')->getRegister($pupil_id,$dates[0],$dates[1]);
			}
		
			if($t == '1'){// if targets is selected
				foreach($subject_ids as $k => $sid){//foreach subject box
				
				if($sid != 1013){// not baseline
					$html .= '<fieldset class="reports-fieldset"><legend><h2>';
					$html .= ClassRegistry::init('Option')->getOption($sid);
					$html .= '</h2></legend>';
					
					$targets = $this->query("SELECT DISTINCT target_id FROM outcomes WHERE pupil_id=".$pupil_id." AND subject_id=".$sid.";");
					
						foreach($targets as $k => $tid){//foreach target
							
							$html .= '<label class="reports-targets">'.ClassRegistry::init('Target')->getTarget($tid['outcomes']['target_id']).'</label>';
							if($act == '1'){
							//if selected iterate activities and outcomes
							$activity_ids = $this->query("SELECT DISTINCT id,activity_id FROM outcomes WHERE pupil_id=".$pupil_id." AND subject_id=".$sid." AND target_id=".$tid['outcomes']['target_id'].";");
								foreach($activity_ids as $k => $aid){//foreach activity
									
									
									$html .= '
									<fieldset class="reports-activities-box">
										<span class="deleteActivity">
											<a href="'.Configure::read('webroot').'/outcomes/remove/'.$aid['outcomes']['id'].'/'.$pupil_id.'">
											<img src="'.Configure::read('webroot').'/img/icons/delete.png">
											</a>
										</span>
									<p><strong>'.ClassRegistry::init('Activity')->getActivityObjective($aid['outcomes']['activity_id']).'</strong> - '.ClassRegistry::init('Activity')->getActivityField($aid['outcomes']['activity_id']).'
									</p>';
									
									if($o == '1'){//if outcomes are seelcted
										$outcomes = $this->query("SELECT outcomes.outcomes,outcomes.nextsteps,attendances.date,outcomes.award FROM outcomes JOIN attendances ON outcomes.attendance_id=attendances.id WHERE outcomes.activity_id=".$aid['outcomes']['activity_id']." AND outcomes.pupil_id=".$pupil_id);

										foreach($outcomes as $n => $oid){//foreach outcome
											if(!empty($oid['outcomes']['outcomes'])){
												$html .= '<br /><b><i>Evaluation - </i></b>'.$oid['outcomes']['outcomes'];
												//edit icon here
												$html .= '<span class="deleteActivity">
															<a href="'.Configure::read('webroot').'/outcomes/edit/'.$aid['outcomes']['id'].'/'.$pupil_id.'">
															<img src="'.Configure::read('webroot').'/img/icons/edit.png">
															</a>
														</span>';
											}
											
											if(!empty($oid['outcomes']['nextsteps'])){
												$html .= '<i> - Nextsteps - </i>'.$oid['outcomes']['nextsteps'];
											}
											
											if(!empty($oid['outcomes']['outcomes'])){
												$html .= '<br /><i style="font-size:75%;"> recorded on '.date('D d M \'y',strtotime($oid['attendances']['date'])).'</i>';
											}
											
											if($oid['outcomes']['award'] == 1){
												$html .= '<img src="'.Configure::read('webroot').'/img/icons/award-red.png" id="reports-award">';
											}
										}

									}
									$html .= '</fieldset>';
								}
							}//end of activity		
						}//end of targets
						
						
					$html .= '</fieldset>';	
					}else{// baseline
						$html .= '<fieldset style="margin-bottom: 3% 0% 3% 0%;"><legend><h2>';
						$html .= ClassRegistry::init('Option')->getOption($sid);
						$html .= '</h2></legend>';
						$html .= $this->getBaselines($pupil_id);
						$html .= '</fieldset>';
					}
				}
				
				
			}
			
			$reportNotes = ClassRegistry::init('Note')->getReportNotes($pupil_id,$dates);
			if(!empty($reportNotes)){
				$html .= '
					<div class="deleteActivity">
						<a href="'.Configure::read('webroot').'/notes/edit/'.$reportNotes[0]['notes']['id'].'">
							<img src="'.Configure::read('webroot').'/img/icons/edit.png">
						</a>
					</div>';
			
				if(!empty($reportNotes[0]['notes']['pcomment'])){
				
					$html .= '<h3>Pupil\'s Evaluation</h3><p>'.$reportNotes[0]['notes']['pcomment'].'</p>';
					
				}
				if(!empty($reportNotes[0]['notes']['tcomment'])){
					
					$html .= '<h3>Teacher\'s Comments</h3><p>'.$reportNotes[0]['notes']['tcomment'].'</p>';

				}
			}
			
		return $html;
		}
		
		function getBaselines($pupil_id){
			$html = '';
			
			$activitys = $this->query("SELECT DISTINCT outcomes.activity_id FROM outcomes WHERE subject_id=1013 AND pupil_id=".$pupil_id);
			
			foreach($activitys as $k=>$v){
				$html .= '<table class="reports-table">';
				
				$baseline = $this->query("SELECT activities.title,activities.activity FROM activities WHERE id =".$v['outcomes']['activity_id']);
				
						
				$dateScores = $this->query("SELECT attendances.date,outcomes.baseline FROM outcomes JOIN attendances ON outcomes.attendance_id=attendances.id WHERE outcomes.subject_id=1013 AND outcomes.pupil_id=".$pupil_id." AND outcomes.activity_id=".$v['outcomes']['activity_id']." ORDER BY attendances.date");
				
				// baseline box top row
				if(!empty($baseline[0]['activities']['title'])){
					$html .= '<tr>
						<td class="report-table-left">
							<h5>'
							.$baseline[0]['activities']['title'].
							'</h5>
						</td>';
				}else{
					$html .= '<td class="report-table-left"></td>';
				}
				$html .= '<td class="report-table-right">'.date('d-M-y',strtotime($dateScores[0]['attendances']['date'])).'</td>';
				
					if(!empty($dateScores[1]['attendances']['date'])){
						$html .= '<td class="report-table-right">'.date('d-M-y',strtotime($dateScores[1]['attendances']['date'])).'</td></tr>';
					}else{
						$html .= '<td class="report-table-right">--</td></tr>';
					}
				// baseline box bottom row	
				$html .= '<tr>';
				if(!empty($baseline[0]['activities']['activity'])){
					$html .= '<td class="report-table-left">'.$baseline[0]['activities']['activity'].'</td>';
				}else{
					$html .= '<td class="report-table-left"></td>';
				}
				
				$html .= '<td class="report-table-right"><strong>'.$dateScores[0]['outcomes']['baseline'].'</strong></td>';
				
					if(!empty($dateScores[1]['outcomes']['baseline'])){
						$html .= '<td class="report-table-right"><strong>'.$dateScores[1]['outcomes']['baseline'].'</strong></td>';
					}else{
						$html .= '<td class="report-table-right"><i>awaiting result</i></td>';
					}
					
				$html .= '</tr>';
			$html .= '</table>';
			}
			
			return $html;
		}
	
}
?>