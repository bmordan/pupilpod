<?php
class Attendance extends AppModel{
	
	function getCodes(){
		return array(
		' '=>' ',
		'/'=>'/',
		'\\'=>'\\',
		'W'=>'W',
		'M'=>'M',
		'I'=>'I',
		'D'=>'D',
		'#'=>'#',
		'C'=>'C',
		'B'=>'B',
		'T'=>'T',
		's'=>'s',
		'L'=>'L',
		'U'=>'U',
		'O'=>'O',
		'V'=>'V',
		'~'=>'~',
		'F'=>'F',
		'H'=>'H',
		'_'=>'_',
		'P'=>'P',
		'R'=>'R',
		'*'=>'*',
		'|'=>'|'
		);
	}

	function getMonday($date){// throw this function a date YYYY-MM-DD and it will return the Monday of that week YYYY-MM-DD
		$sub = date('N',strtotime($date))-1;
		return date('Y-m-d',strtotime($date.' -'.$sub.' days'));
	}
	function next($week){return date('Y-m-d',strtotime($week.' +1 Week'));}
	
	function prev($week){return date('Y-m-d',strtotime($week.' -1 Week'));}

	function getTermDates($date = null,$termKey = null,$getTermsArray = null){// Throw this function a date it will return the start and end dates in single quotes. Feed this function a date followed by the termKey and you get the dates for a particular term. $termKey format= YYYY_[number of term autumn=1,spring=2,summer=3][halfterm=1 or 2], to just the whole getTermArray call like this getTermDates(null,null,true) it will server you the $terms array below
	
		$terms = array(
			'2014_32' => array('2014-06-02','2014-07-23'),
			'2014_31' => array('2014-04-22','2014-05-23'),
			'2014_22' => array('2014-02-24','2014-04-04'),
			'2014_21' => array('2014-01-05','2014-02-15'),
			'2013_12' => array('2013-11-04','2013-12-21'),
			'2013_11' => array('2013-09-03','2013-10-25'),
			'2013_32' => array('2013-06-03','2013-07-24'),
			'2013_31' => array('2013-04-15','2013-05-24'),
			'2013_22' => array('2013-02-25','2013-03-28'),
			'2013_21' => array('2013-01-07','2013-02-15'),
			'2012_12' => array('2012-11-05','2012-12-21'),
			'2012_11' => array('2012-09-04','2012-10-26')			
		);
		
		if(isset($termKey)){
			return $terms[$termKey];
		}else{
			foreach($terms as $term => $dates){
				if($date < $dates[1] && $date > $dates[0]){
					return array("'".$dates[0]."'","'".$dates[1]."'");
				}
			}
		}
		
		if(!empty($getTermsArray) && $date == null){
			return $terms;
		}
	}
	
	function setPupilsessions($from,$to){
		$this->Query("CREATE OR REPLACE VIEW pupilsessions AS SELECT pupil_id,SUM(actual) as actual,option_id
		FROM attendances
		WHERE date BETWEEN ".$from." AND ".$to."
		GROUP BY pupil_id
		ORDER BY SUM(actual) DESC;");
	}
		
	function getEmptySchoolEmails(){//this queries the pupilsession view so set it correctly before calling this
		$data = $this->Query("SELECT CONCAT(pupils.first_name,' ',pupils.last_name) as Pupils,pupilsessions.actual,schools.name,schools.address_1,schools.email,schools.id
		FROM pupilsessions
		JOIN (pupils,schools) ON (pupilsessions.pupil_id=pupils.id AND pupils.school_id=schools.id) WHERE schools.id NOT IN(67921,67935,67936,67924) AND pupils.option_id != '1037';");
		$html = '<form action="/mis/schools/addEmails" method="post" accept-charset="utf-8">
		<div>';
		foreach($data as $n => $row){
			$school = $row['schools']['name'].' | '.$row['schools']['address_1'];
			$html .= '
				<div>'.$row[0]['Pupils'].' ('.$row['pupilsessions']['actual'].' sessions)
				<b>'.substr($school,0,55).'</b>...  ';
			//add the blank field for email ...	
			if($row['schools']['email'] == ''){
				$html .= '
				<input name="'.$row['schools']['id'].'" style="display:inline;font-size:90%;width:100%;background:#e40a74;color:#fff;">';
			}else{
			//or display the email + edit ...
				$html .= '
				<a href="/mis/schools/edit/'.$row['schools']['id'].'" style="font-style:italic;color:#42a1ca;font-size:90%;">edit</a> | '.
				$row['schools']['email'].'';
			}
			$html .= '<hr /></div>';
			
		}
		$html .= '<input type="submit" value="save all"></form>';
		return $html;
	}
		
	function getRegister($pupil_id,$from,$to){//this function returns an html table of a pupils attendance for a period
		$name = ClassRegistry::init('Pupil')->getName($pupil_id);
		$register = $this->Query("SELECT
		max(if((attendances.week = NULL),'',DATE_FORMAT(attendances.week,'%D %b%y'))) AS Week,
		max(if((attendances.dayindex = '10'),attendances.code,'')) AS MonAM,
		max(if((attendances.dayindex = '11'),attendances.code,'')) AS MonPM,
		max(if((attendances.dayindex = '20'),attendances.code,'')) AS TueAM,
		max(if((attendances.dayindex = '21'),attendances.code,'')) AS TuePM,
		max(if((attendances.dayindex = '30'),attendances.code,'')) AS WedAM,
		max(if((attendances.dayindex = '31'),attendances.code,'')) AS WedPM,
		max(if((attendances.dayindex = '40'),attendances.code,'')) AS ThuAM,
		max(if((attendances.dayindex = '41'),attendances.code,'')) AS ThuPM,
		max(if((attendances.dayindex = '50'),attendances.code,'')) AS FriAM,
		max(if((attendances.dayindex = '51'),attendances.code,'')) AS FriPM
		FROM attendances
		WHERE attendances.pupil_id=".$pupil_id." 
		AND attendances.week BETWEEN ".$from." AND ".$to."
		GROUP BY week");
		if(!empty($register)){
			$html = '<fieldset><legend>'.$name.'</legend>';
			$html .= '<table class="att-getRegister"><tr><td style="text-align:right;"></td>
						<td style="text-align:left;"><i>Week Starting</i></td>
						<td colspan="2">Mon</td>
						<td>&nbsp;</td>
						<td colspan="2">Tue</td>
						<td>&nbsp;</td>
						<td colspan="2">Wed</td>
						<td>&nbsp;</td>
						<td colspan="2">Thu</td>
						<td>&nbsp;</td>
						<td colspan="2">Fri</td></tr>';
			foreach($register as $n => $w){
				$html .= '<tr><td class="att-headers-report">'.$w[0]['Week'].'</td>';
				$html .= '<td class="att-code-space">&nbsp;</td>';
				$html .= '<td class="att-code-report">'.$w[0]['MonAM'].'</td><td class="att-code-report">'.$w[0]['MonPM'].'</td>';
				$html .= '<td class="att-code-space">&nbsp;</td>';
				$html .= '<td class="att-code-report">'.$w[0]['TueAM'].'</td><td class="att-code-report">'.$w[0]['TuePM'].'</td>';
				$html .= '<td class="att-code-space">&nbsp;</td>';
				$html .= '<td class="att-code-report">'.$w[0]['WedAM'].'</td><td class="att-code-report">'.$w[0]['WedPM'].'</td>';
				$html .= '<td class="att-code-space">&nbsp;</td>';
				$html .= '<td class="att-code-report">'.$w[0]['ThuAM'].'</td><td class="att-code-report">'.$w[0]['ThuPM'].'</td>';
				$html .= '<td class="att-code-space">&nbsp;</td>';
				$html .= '<td class="att-code-report">'.$w[0]['FriAM'].'</td><td class="att-code-report">'.$w[0]['FriPM'].'</td></tr>';
			}
			$html .= '</table></fieldset>';
		}else{
			$html = '<p>No attendance data for '.$name.' for this term</p>';
		}
		
		return $html;
	}
	
	function getCodeValue($code){
		$actual = array('/','\\\\','\\','W','J','B','L','P','S','V');
		$potential = array('/','\\\\','\\','W','I','M','O','C','G','N','S','T','U');
		if(in_array($code,$actual)){$a=1;}else{$a=0;}
		if(in_array($code,$potential)){$p=1;}else{$p=0;}
		return array($a,$p);
	}
	
	function getMultiAttendanceForm($ids){// this function creates a multi pupil attendance form
		$list = explode(',',$ids['pupilList']['ids']);
		$html = '<form action="/mis/staffs/index" method="post" accept-charset="utf-8"><div class="formsection"><table style="width:105%;">';
		foreach($list as $k=>$id){
			$name = ClassRegistry::init('Pupil')->getName($id);
			//table name | code selector | comment
			$html .= '<tr><td style="width:30%;font-size:50%;text-align:right;">'.$name.'</td>';
			$html .= '<td><select class="att-form-code" name="code_'.$k.'">';
					foreach($this->getCodes() as $a=>$c){
						if($c == '/'){
						$html .= '<option value="'.$a.'" selected>'.$c.'</option>';
						}else{
						$html .= '<option value="'.$a.'">'.$c.'</option>';
						}
						
					}					
			$html .= '</select></td>';
			$html .= '<td style="width:100%;"><input name="comment_'.$k.'" maxlength="500" type="text" class="att-comment"></td></tr>';
		}
		return $html .= '</table><input type="submit" value="save all"></form></div>';
	}
	
	function getAttendance($monday,$area,$ids = null){// this needs a monday YYYY-MM-DD date and returns a classic register data after a clean
	
		$this->query("DELETE FROM attendances WHERE code = ' ';");
	
		if($ids == null){
			$data = $this->query("SELECT attendances.pupil_id,
									CONCAT(pupils.first_name,' ',pupils.last_name) as name,
									max(if((attendances.dayindex = '10'),attendances.code,'')) AS MonAM,
									max(if((attendances.dayindex = '11'),attendances.code,'')) AS MonPM,
									max(if((attendances.dayindex = '20'),attendances.code,'')) AS TueAM,
									max(if((attendances.dayindex = '21'),attendances.code,'')) AS TuePM,
									max(if((attendances.dayindex = '30'),attendances.code,'')) AS WedAM,
									max(if((attendances.dayindex = '31'),attendances.code,'')) AS WedPM,
									max(if((attendances.dayindex = '40'),attendances.code,'')) AS ThuAM,
									max(if((attendances.dayindex = '41'),attendances.code,'')) AS ThuPM,
									max(if((attendances.dayindex = '50'),attendances.code,'')) AS FriAM,
									max(if((attendances.dayindex = '51'),attendances.code,'')) AS FriPM,
									SUM(attendances.actual) as Sessions
									FROM attendances JOIN pupils ON attendances.pupil_id=pupils.id
									WHERE attendances.week = '".$monday."' AND pupils.option_id=".$area."
									GROUP BY attendances.pupil_id
									ORDER BY pupils.first_name
									");
			return $data;
		}else{
			$data = $this->query("SELECT attendances.pupil_id,
									CONCAT(pupils.first_name,' ',pupils.last_name) as name,
									max(if((attendances.dayindex = '10'),attendances.code,'')) AS MonAM,
									max(if((attendances.dayindex = '11'),attendances.code,'')) AS MonPM,
									max(if((attendances.dayindex = '20'),attendances.code,'')) AS TueAM,
									max(if((attendances.dayindex = '21'),attendances.code,'')) AS TuePM,
									max(if((attendances.dayindex = '30'),attendances.code,'')) AS WedAM,
									max(if((attendances.dayindex = '31'),attendances.code,'')) AS WedPM,
									max(if((attendances.dayindex = '40'),attendances.code,'')) AS ThuAM,
									max(if((attendances.dayindex = '41'),attendances.code,'')) AS ThuPM,
									max(if((attendances.dayindex = '50'),attendances.code,'')) AS FriAM,
									max(if((attendances.dayindex = '51'),attendances.code,'')) AS FriPM,
									SUM(attendances.actual) as Sessions
									FROM attendances JOIN pupils ON attendances.pupil_id=pupils.id
									WHERE attendances.week = '".$monday."' AND pupils.option_id=".$area."
									GROUP BY attendances.pupil_id
									UNION
									SELECT pupils.id,
									CONCAT(pupils.first_name,' ',pupils.last_name) as name,
									'' as MonAM,
									'' as MonPM,
									'' as TueAM,
									'' as TuePM,
									'' as WedAM,
									'' as WedPM,
									'' as ThuAM,
									'' as ThuPM,
									'' as FriAM,
									'' as FriPM,
									'' as Sessions
									FROM pupils
									WHERE pupils.id IN(".$ids.")
									ORDER BY name;");
											
			return $data;		
		}
	}
	
	function htmlAttendance($data,$monday){if(!empty($data)){
	
		$html = '<table class="att-table">
			<tr>
				<td></td>
				<td style="background:#777;border: 1px solid #ccc;">Mon</td>
				<td style="background:#ccc;border: 1px solid #777;">Tue</td>
				<td style="background:#777;border: 1px solid #ccc;">Wed</td>
				<td style="background:#ccc;border: 1px solid #777;">Thu</td>
				<td style="background:#777;border: 1px solid #ccc;">Fri</td>
				<td></td>
			</tr>	';
			
		foreach($data as $k => $v){
			//set the pupil id as $id so new empty pupil not effected by script
			if(isset($v['attendances']['pupil_id'])){
				$id = $v['attendances']['pupil_id'];
			}else{
				$id = $v[0]['pupil_id'];
			}
			//yellow markers for new pupils
			if($v[0]['Sessions'] == ''){$html .= '<tr style="background:#fdc323;">';}else{$html .= '<tr>';}
			
			
			//linked pupil name
			$html .= '	<td style="text-align:right;background:#ccc;border:1px solid #777;">
					<input name="'.$id.'" type="hidden" value="'.$id.'">
					
					<a href="'.Configure::read('webroot').'/pupils/view/'.$id.'" class="att-name">'.$v[0]['name'].'</a>
				</td>';
	
			//MonAM
			$html .= '<td style="background:#777;width:14%;">';
			if(empty($v[0]['MonAM'])){//no attendance data
				$html .= '<select class="att-code-form" name="'.$id.'-MonAM">';
				foreach($this->getCodes() as $a=>$c){
					$html .= '<option value="'.$a.'">'.$c.'</option>';
				}
				$html .= '</select>';
			}else{// attendance data preselect
				$html .= '<select class="att-code-form selected" name="'.$id.'-MonAM">';
				foreach($this->getCodes() as $a=>$c){
					if($v[0]['MonAM'] == $c){
						$html .= '<option value="'.$v[0]['MonAM'].'" selected>'.$v[0]['MonAM'].'</option>';
					}else{
						$html .= '<option value="'.$c.'">'.$c.'</option>';
					}	
				}
				$html .= '</select>';
			}//End MonAM

			//MonPM
			if(empty($v[0]['MonPM'])){//no attendance data
				$html .= '<select class="att-code-form" name="'.$id.'-MonPM">';
				foreach($this->getCodes() as $a=>$c){
					$html .= '<option value="'.$a.'">'.$c.'</option>';
				}
				$html .= '</select>';
			}else{// attendance data preselect
				$html .= '<select class="att-code-form selected" name="'.$id.'-MonPM">';
				foreach($this->getCodes() as $a=>$c){
					if($v[0]['MonPM'] == $c){
						$html .= '<option value="'.$v[0]['MonPM'].'" selected>'.$v[0]['MonPM'].'</option>';
					}else{
						$html .= '<option value="'.$c.'">'.$c.'</option>';
					}	
				}
				$html .= '</select>';
			}
			$html .= '</td>';
			//End MonPM

			//TueAM
			$html .= '<td style="background:#ccc;">';
			if(empty($v[0]['TueAM'])){//no attendance data
				$html .= '<select class="att-code-form" name="'.$id.'-TueAM">';
				foreach($this->getCodes() as $a=>$c){
					$html .= '<option value="'.$a.'">'.$c.'</option>';
				}
				$html .= '</select>';
			}else{// attendance data preselect
				$html .= '<select class="att-code-form selected" name="'.$id.'-TueAM">';
				foreach($this->getCodes() as $a=>$c){
					if($v[0]['TueAM'] == $c){
						$html .= '<option value="'.$v[0]['TueAM'].'" selected>'.$v[0]['TueAM'].'</option>';
					}else{
						$html .= '<option value="'.$c.'">'.$c.'</option>';
					}	
				}
				$html .= '</select>';
			}//End TueAM

			//TuePM
			if(empty($v[0]['TuePM'])){//no attendance data
				$html .= '<select class="att-code-form" name="'.$id.'-TuePM">';
				foreach($this->getCodes() as $a=>$c){
					$html .= '<option value="'.$a.'">'.$c.'</option>';
				}
				$html .= '</select>';
			}else{// attendance data preselect
				$html .= '<select class="att-code-form selected" name="'.$id.'-TuePM">';
				foreach($this->getCodes() as $a=>$c){
					if($v[0]['TuePM'] == $c){
						$html .= '<option value="'.$v[0]['TuePM'].'" selected>'.$v[0]['TuePM'].'</option>';
					}else{
						$html .= '<option value="'.$c.'">'.$c.'</option>';
					}	
				}
				$html .= '</select>';
			}
			$html .= '</td>';
			//End TuePM
	
			//WedAM
			$html .= '<td style="background:#777;width:14%;">';
			if(empty($v[0]['WedAM'])){//no attendance data
				$html .= '<select class="att-code-form" name="'.$id.'-WedAM">';
				foreach($this->getCodes() as $a=>$c){
					$html .= '<option value="'.$a.'">'.$c.'</option>';
				}
				$html .= '</select>';
			}else{// attendance data preselect
				$html .= '<select class="att-code-form selected" name="'.$id.'-WedAM">';
				foreach($this->getCodes() as $a=>$c){
					if($v[0]['WedAM'] == $c){
						$html .= '<option value="'.$v[0]['WedAM'].'" selected>'.$v[0]['WedAM'].'</option>';
					}else{
						$html .= '<option value="'.$c.'">'.$c.'</option>';
					}	
				}
				$html .= '</select>';
			}//End WedAM

			//WedPM
			if(empty($v[0]['WedPM'])){//no attendance data
				$html .= '<select class="att-code-form" name="'.$id.'-WedPM">';
				foreach($this->getCodes() as $a=>$c){
					$html .= '<option value="'.$a.'">'.$c.'</option>';
				}
				$html .= '</select>';
			}else{// attendance data preselect
				$html .= '<select class="att-code-form selected" name="'.$id.'-WedPM">';
				foreach($this->getCodes() as $a=>$c){
					if($v[0]['WedPM'] == $c){
						$html .= '<option value="'.$v[0]['WedPM'].'" selected>'.$v[0]['WedPM'].'</option>';
					}else{
						$html .= '<option value="'.$c.'">'.$c.'</option>';
					}	
				}
				$html .= '</select>';
			}
			$html .= '</td>';
			//End WedPM			

			//ThuAM
			$html .= '<td style="background:#ccc;width:14%;">';
			if(empty($v[0]['ThuAM'])){//no attendance data
				$html .= '<select class="att-code-form" name="'.$id.'-ThuAM">';
				foreach($this->getCodes() as $a=>$c){
					$html .= '<option value="'.$a.'">'.$c.'</option>';
				}
				$html .= '</select>';
			}else{// attendance data preselect
				$html .= '<select class="att-code-form selected" name="'.$id.'-ThuAM">';
				foreach($this->getCodes() as $a=>$c){
					if($v[0]['ThuAM'] == $c){
						$html .= '<option value="'.$v[0]['ThuAM'].'" selected>'.$v[0]['ThuAM'].'</option>';
					}else{
						$html .= '<option value="'.$c.'">'.$c.'</option>';
					}	
				}
				$html .= '</select>';
			}//End ThuAM

			//ThuPM
			if(empty($v[0]['ThuPM'])){//no attendance data
				$html .= '<select class="att-code-form" name="'.$id.'-ThuPM">';
				foreach($this->getCodes() as $a=>$c){
					$html .= '<option value="'.$a.'">'.$c.'</option>';
				}
				$html .= '</select>';
			}else{// attendance data preselect
				$html .= '<select class="att-code-form selected" name="'.$id.'-ThuPM">';
				foreach($this->getCodes() as $a=>$c){
					if($v[0]['ThuPM'] == $c){
						$html .= '<option value="'.$v[0]['ThuPM'].'" selected>'.$v[0]['ThuPM'].'</option>';
					}else{
						$html .= '<option value="'.$c.'">'.$c.'</option>';
					}	
				}
				$html .= '</select>';
			}
			$html .= '</td>';
			//End ThuPM

			//FriAM
			$html .= '<td style="background:#777;width:14%;">';
			if(empty($v[0]['FriAM'])){//no attendance data
				$html .= '<select class="att-code-form" name="'.$id.'-FriAM">';
				foreach($this->getCodes() as $a=>$c){
					$html .= '<option value="'.$a.'">'.$c.'</option>';
				}
				$html .= '</select>';
			}else{// attendance data preselect
				$html .= '<select class="att-code-form selected" name="'.$id.'-FriAM">';
				foreach($this->getCodes() as $a=>$c){
					if($v[0]['FriAM'] == $c){
						$html .= '<option value="'.$v[0]['FriAM'].'" selected>'.$v[0]['FriAM'].'</option>';
					}else{
						$html .= '<option value="'.$c.'">'.$c.'</option>';
					}	
				}
				$html .= '</select>';
			}//End FriAM

			//FriPM
			if(empty($v[0]['FriPM'])){//no attendance data
				$html .= '<select class="att-code-form" name="'.$id.'-FriPM">';
				foreach($this->getCodes() as $a=>$c){
					$html .= '<option value="'.$a.'">'.$c.'</option>';
				}
				$html .= '</select>';
			}else{// attendance data preselect
				$html .= '<select class="att-code-form selected" name="'.$id.'-FriPM">';
				foreach($this->getCodes() as $a=>$c){
					if($v[0]['FriPM'] == $c){
						$html .= '<option value="'.$v[0]['FriPM'].'" selected>'.$v[0]['FriPM'].'</option>';
					}else{
						$html .= '<option value="'.$c.'">'.$c.'</option>';
					}	
				}
				$html .= '</select>';
			}
			$html .= '</td>';
			//End FriPM

			$html .= '<td style="background:#ccc;border: 1px solid #777;width:5%;">'.$v[0]['Sessions'].'<td></tr>';
		}
		$html .= '<input type="hidden" name="week" value="'.$monday.'">';
		$html .= '</table>';
		return $html;}
		else{// data is empty
			$test = $this->getMonday(date('Y-m-d',time()));
			if($monday > $test){
				return $html = '<p id="flash">Not yet you muppet !<br />lets just take one week at a time...</p>';
			}else{
				return $html = '<p id="flash">No attendance yet for this area</p>';
			}
			
		}
	}
	
	function bigSave($array,$week){
		$echo = '';
		foreach($array as $k => $v){
			if($k == $v){//new pupil starts
				$id = $v;
			}
			if(!empty($v)){
				if($v == '\\'){$v = $v.'\\';}
				$d = substr($k,-5,3);
				if(in_array($d,array('Mon','Tue','Wed','Thu','Fri'))){
					if($d == 'Mon'){
								$date = date('Y-m-d',strtotime($week.' +0 days'));
								$day = '1';
					}elseif($d == 'Tue'){
								$date = date('Y-m-d',strtotime($week.' +1 days'));
								$day = '2';
					}elseif($d == 'Wed'){
								$date = date('Y-m-d',strtotime($week.' +2 days'));
								$day = '3';
					}elseif($d == 'Thu'){
								$date = date('Y-m-d',strtotime($week.' +3 days'));
								$day = '4';
					}elseif($d == 'Fri'){
								$date = date('Y-m-d',strtotime($week.' +4 days'));
								$day = '5';
					}else{
								
					}
					if(substr($k,-2) == 'AM'){$index = '0';}else{$index = '1';}
					$option_id = ClassRegistry::init('Pupil')->getArea($id);
					$code = $this->getCodeValue($v);
					$actual = $code[0];
					$potential = $code[1];
					$check = $this->query("SELECT * FROM attendances WHERE pupil_id=".$id." AND date='".$date."' AND dayindex=".$day.$index.";");
					if(empty($check)){// write to database
						$this->query("INSERT INTO attendances (pupil_id,week,date,dayindex,actual,potential,code,option_id) VALUES(".$id.",'".$week."','".$date."',".$day.$index.",".$actual.",".$potential.",'".$v."',".$option_id.");");
					}else{// delete if set back to blank
						if($v == ' ' || $v == ''){
							$this->query("DELETE FROM attendances WHERE pupil_id=".$id." AND date='".$date."' AND dayindex=".$day.$index.";");
						}else{
							$this->query("UPDATE attendances SET pupil_id=".$id.",week='".$week."',date='".$date."',dayindex=".$day.$index.",actual=".$actual.",potential=".$potential.",code='".$v."' WHERE id=".$check[0]['attendances']['id'].";");					
						}
					}	
				}	
			}
		//end of foreach $v
		}
		return $echo;
	}
	
	public function littleSave($pupil_id,$code,$date,$index){//this function will insert a single attendance by just passing the pupil_id, code and date and return the insert id
	
		$week = $this->getMonday($date);
		if($code == '\\'){$code = $code.'\\';}
		$value = $this->getCodeValue($code);
		
		//dont double entry attendance check first before inserting or updating
		$check = $this->query("SELECT * FROM attendances WHERE pupil_id=".$pupil_id." AND date='".$date."' AND dayindex=".$index.";");
		
		if(empty($check)){
			
			$this->query("INSERT INTO attendances (pupil_id,week,date,dayindex,actual,potential,code) VALUES (".$pupil_id.",'".$week."','".$date."',".$index.",".$value[0].",".$value[1].",'".$code."');");
			
			$id = $this->query("SELECT MAX(id) as id FROM attendances;");
			return $id[0][0]['id'];
			
		}else{
			$this->query("UPDATE attendances SET week='".$week."',date='".$date."',dayindex=".$index.",actual=".$value[0].",potential=".$value[1].",code='".$code."' WHERE id=".$check[0]['attendances']['id'].";");

			return $check[0]['attendances']['id'];
		}
	}
	
	function autoEmailer($p_id,$att,$tar,$act,$out,$comment = null,$from,$to){//this function controls the sending of emails to schools
		$array = array();
			foreach($p_id as $n => $pupilsessions){
				$pupil = array();
				foreach($pupilsessions as $k => $v){
	
					$schoolEmail = ClassRegistry::init('School')->getSchoolEmail($v['pupil_id']);
					//if their is no email skip
					if(!empty($schoolEmail)){
						$wrapper = '<p>Dear Attendance Officer,</p><p>'.ClassRegistry::init('Pupil')->getName($v['pupil_id']).' recently attended Evelina Hospital School. We are sending their attendance to you so that you can add this to your records. There are a few attendance marks that need some explaination. Firstly we use the "W" code to indicate a session this pupil recieved on the wards. We record pupil\'s attendance at their home school with a "D", you can ignore these. Our attendance marks "/","\","W" should be entered on your registers as either "D" - Dual Registered or "B" - Educated Off-Site.</p>';
						
						if(!empty($comment)){$wrapper .= '<p>'.$comment.'</p>';}
						if($att == '1'){
						$pupil[$v['pupil_id']] = $wrapper.$this->getRegister($v['pupil_id'],$from,$to).'<p>If you have any questions please contact the office on 020 7188 2267 or <a href="mailto:bmordan@evelina.southwark.sch.uk?subject=Query from '.$schoolEmail.'" style="font-size:90%;">bmordan@evelina.southwark.sch.uk</a></p><p>Yours Sincerly</p><p>Evelina Hospital School <i>office team</i></p>';
						}
					}
				}
				array_push($array,$pupil);
			}			
		return $array;
	}

	function getPupilAttendance($pupil_id){//this function returns an array of a pupils attendance grouped into half term sections
		
		$this->query("DELETE FROM attendances WHERE code = ' ';");
		
		$array = array();
		foreach($this->getTermDates(null,null,true) as $k => $d){
			$sql = $this->query("SELECT
									DATE_FORMAT(attendances.week,'%Y-%m-%d') as week,
									max(if((attendances.dayindex = '10'),attendances.code,'')) AS MonAM,
									max(if((attendances.dayindex = '11'),attendances.code,'')) AS MonPM,
									max(if((attendances.dayindex = '20'),attendances.code,'')) AS TueAM,
									max(if((attendances.dayindex = '21'),attendances.code,'')) AS TuePM,
									max(if((attendances.dayindex = '30'),attendances.code,'')) AS WedAM,
									max(if((attendances.dayindex = '31'),attendances.code,'')) AS WedPM,
									max(if((attendances.dayindex = '40'),attendances.code,'')) AS ThuAM,
									max(if((attendances.dayindex = '41'),attendances.code,'')) AS ThuPM,
									max(if((attendances.dayindex = '50'),attendances.code,'')) AS FriAM,
									max(if((attendances.dayindex = '51'),attendances.code,'')) AS FriPM,
									SUM(attendances.actual) as Sessions
									FROM attendances WHERE pupil_id=".$pupil_id."
									AND attendances.week BETWEEN '".$d[0]."' AND '".$d[1]."'
									GROUP BY attendances.week
									ORDER BY attendances.week DESC");
			array_push($array,array($k,$sql));						
		}
		return $array;							
	}
	
	function getPupilAttendanceHtml($data,$id){// this renders the data structure from getPupilAttendance() into an interactive register form
		
		$empty = 0;
		$html = '<form action="/mis/attendances/pupil/'.$id.'" method="post" accept-charset="utf-8">';
		
		
		foreach($data as $n => $sets){
			if(!empty($sets[1])){// title the section correctly
				$term = $sets[0][5];
					switch($term){
						case '1':
							$term = 'Autumn Term '.substr($sets[0],0,-3);
							break;
						case '2':
							$term = 'Spring Term '.substr($sets[0],0,-3);
							break;
						case '3':
							$term = 'Summer Term '.substr($sets[0],0,-3);
							break;			
					}
				$html .= '<fieldset style="border: 0px;">
							<h4>'.$term.'</h4>';
				$html .= '<table class="att-table">
							<tr>
								<td style="background:#ccc;border: 1px solid #777;">Week</td>
								<td style="background:#777;border: 1px solid #ccc;">Mon</td>
								<td style="background:#ccc;border: 1px solid #777;">Tue</td>
								<td style="background:#777;border: 1px solid #ccc;">Wed</td>
								<td style="background:#ccc;border: 1px solid #777;">Thu</td>
								<td style="background:#777;border: 1px solid #ccc;">Fri</td>
								<td></td>
							</tr>	';
					foreach($sets[1] as $k => $v){
							$html .= '<tr>';
							
							$html .= '<td style="background:#ccc;text-align:right;">'.date('j M y',strtotime($v[0]['week'])).'</td>';
							
								//MonAM
								$html .= '<td style="background:#777;width:14%;">';
								if(empty($v[0]['MonAM'])){//no attendance data
									$html .= '<select class="att-code-form" name="'.$v[0]['week'].'_10">';
									foreach($this->getCodes() as $a=>$c){
										$html .= '<option value="'.$a.'">'.$c.'</option>';
									}
									$html .= '</select>';
								}else{// attendance data preselect
									$html .= '<select class="att-code-form selected" name="'.$v[0]['week'].'_10">';
									foreach($this->getCodes() as $a=>$c){
										if($v[0]['MonAM'] == $c){
											$html .= '<option value="'.$v[0]['MonAM'].'" selected>'.$v[0]['MonAM'].'</option>';
										}else{
											$html .= '<option value="'.$c.'">'.$c.'</option>';
										}	
									}
									$html .= '</select>';
								}//End MonAM

								//MonPM
								if(empty($v[0]['MonPM'])){//no attendance data
									$html .= '<select class="att-code-form" name="'.$v[0]['week'].'_11">';
									foreach($this->getCodes() as $a=>$c){
										$html .= '<option value="'.$a.'">'.$c.'</option>';
									}
									$html .= '</select>';
								}else{// attendance data preselect
									$html .= '<select class="att-code-form selected" name="'.$v[0]['week'].'_11">';
									foreach($this->getCodes() as $a=>$c){
										if($v[0]['MonPM'] == $c){
											$html .= '<option value="'.$v[0]['MonPM'].'" selected>'.$v[0]['MonPM'].'</option>';
										}else{
											$html .= '<option value="'.$c.'">'.$c.'</option>';
										}	
									}
									$html .= '</select>';
								}
								$html .= '</td>';
								//End MonPM

								//TueAM
								$html .= '<td style="background:#ccc;width:14%;">';
								if(empty($v[0]['TueAM'])){//no attendance data
									$html .= '<select class="att-code-form" name="'.$v[0]['week'].'_20">';
									foreach($this->getCodes() as $a=>$c){
										$html .= '<option value="'.$a.'">'.$c.'</option>';
									}
									$html .= '</select>';
								}else{// attendance data preselect
									$html .= '<select class="att-code-form selected" name="'.$v[0]['week'].'_20">';
									foreach($this->getCodes() as $a=>$c){
										if($v[0]['TueAM'] == $c){
											$html .= '<option value="'.$v[0]['TueAM'].'" selected>'.$v[0]['TueAM'].'</option>';
										}else{
											$html .= '<option value="'.$c.'">'.$c.'</option>';
										}	
									}
									$html .= '</select>';
								}//End TueAM

								//TuePM
								if(empty($v[0]['TuePM'])){//no attendance data
									$html .= '<select class="att-code-form" name="'.$v[0]['week'].'_21">';
									foreach($this->getCodes() as $a=>$c){
										$html .= '<option value="'.$a.'">'.$c.'</option>';
									}
									$html .= '</select>';
								}else{// attendance data preselect
									$html .= '<select class="att-code-form selected" name="'.$v[0]['week'].'_21">';
									foreach($this->getCodes() as $a=>$c){
										if($v[0]['TuePM'] == $c){
											$html .= '<option value="'.$v[0]['TuePM'].'" selected>'.$v[0]['TuePM'].'</option>';
										}else{
											$html .= '<option value="'.$c.'">'.$c.'</option>';
										}	
									}
									$html .= '</select>';
								}
								$html .= '</td>';
								//End TuePM
						
								//WedAM
								$html .= '<td style="background:#777;width:14%;">';
								if(empty($v[0]['WedAM'])){//no attendance data
									$html .= '<select class="att-code-form" name="'.$v[0]['week'].'_30">';
									foreach($this->getCodes() as $a=>$c){
										$html .= '<option value="'.$a.'">'.$c.'</option>';
									}
									$html .= '</select>';
								}else{// attendance data preselect
									$html .= '<select class="att-code-form selected" name="'.$v[0]['week'].'_30">';
									foreach($this->getCodes() as $a=>$c){
										if($v[0]['WedAM'] == $c){
											$html .= '<option value="'.$v[0]['WedAM'].'" selected>'.$v[0]['WedAM'].'</option>';
										}else{
											$html .= '<option value="'.$c.'">'.$c.'</option>';
										}	
									}
									$html .= '</select>';
								}//End WedAM

								//WedPM
								if(empty($v[0]['WedPM'])){//no attendance data
									$html .= '<select class="att-code-form" name="'.$v[0]['week'].'_31">';
									foreach($this->getCodes() as $a=>$c){
										$html .= '<option value="'.$a.'">'.$c.'</option>';
									}
									$html .= '</select>';
								}else{// attendance data preselect
									$html .= '<select class="att-code-form selected" name="'.$v[0]['week'].'_31">';
									foreach($this->getCodes() as $a=>$c){
										if($v[0]['WedPM'] == $c){
											$html .= '<option value="'.$v[0]['WedPM'].'" selected>'.$v[0]['WedPM'].'</option>';
										}else{
											$html .= '<option value="'.$c.'">'.$c.'</option>';
										}	
									}
									$html .= '</select>';
								}
								$html .= '</td>';
								//End WedPM			

								//ThuAM
								$html .= '<td style="background:#ccc;width:14%;">';
								if(empty($v[0]['ThuAM'])){//no attendance data
									$html .= '<select class="att-code-form" name="'.$v[0]['week'].'_40">';
									foreach($this->getCodes() as $a=>$c){
										$html .= '<option value="'.$a.'">'.$c.'</option>';
									}
									$html .= '</select>';
								}else{// attendance data preselect
									$html .= '<select class="att-code-form selected" name="'.$v[0]['week'].'_40">';
									foreach($this->getCodes() as $a=>$c){
										if($v[0]['ThuAM'] == $c){
											$html .= '<option value="'.$v[0]['ThuAM'].'" selected>'.$v[0]['ThuAM'].'</option>';
										}else{
											$html .= '<option value="'.$c.'">'.$c.'</option>';
										}	
									}
									$html .= '</select>';
								}//End ThuAM

								//ThuPM
								if(empty($v[0]['ThuPM'])){//no attendance data
									$html .= '<select class="att-code-form" name="'.$v[0]['week'].'_41">';
									foreach($this->getCodes() as $a=>$c){
										$html .= '<option value="'.$a.'">'.$c.'</option>';
									}
									$html .= '</select>';
								}else{// attendance data preselect
									$html .= '<select class="att-code-form selected" name="'.$v[0]['week'].'_41">';
									foreach($this->getCodes() as $a=>$c){
										if($v[0]['ThuPM'] == $c){
											$html .= '<option value="'.$v[0]['ThuPM'].'" selected>'.$v[0]['ThuPM'].'</option>';
										}else{
											$html .= '<option value="'.$c.'">'.$c.'</option>';
										}	
									}
									$html .= '</select>';
								}
								$html .= '</td>';
								//End ThuPM

								//FriAM
								$html .= '<td style="background:#777;width:14%;">';
								if(empty($v[0]['FriAM'])){//no attendance data
									$html .= '<select class="att-code-form" name="'.$v[0]['week'].'_50">';
									foreach($this->getCodes() as $a=>$c){
										$html .= '<option value="'.$a.'">'.$c.'</option>';
									}
									$html .= '</select>';
								}else{// attendance data preselect
									$html .= '<select class="att-code-form selected" name="'.$v[0]['week'].'_50">';
									foreach($this->getCodes() as $a=>$c){
										if($v[0]['FriAM'] == $c){
											$html .= '<option value="'.$v[0]['FriAM'].'" selected>'.$v[0]['FriAM'].'</option>';
										}else{
											$html .= '<option value="'.$c.'">'.$c.'</option>';
										}	
									}
									$html .= '</select>';
								}//End FriAM

								//FriPM
								if(empty($v[0]['FriPM'])){//no attendance data
									$html .= '<select class="att-code-form" name="'.$v[0]['week'].'_51">';
									foreach($this->getCodes() as $a=>$c){
										$html .= '<option value="'.$a.'">'.$c.'</option>';
									}
									$html .= '</select>';
								}else{// attendance data preselect
									$html .= '<select class="att-code-form selected" name="'.$v[0]['week'].'_51">';
									foreach($this->getCodes() as $a=>$c){
										if($v[0]['FriPM'] == $c){
											$html .= '<option value="'.$v[0]['FriPM'].'" selected>'.$v[0]['FriPM'].'</option>';
										}else{
											$html .= '<option value="'.$c.'">'.$c.'</option>';
										}	
									}
									$html .= '</select>';
								}
								$html .= '</td>';
								//End FriPM
							$html .= '</tr>';	
					}
				
				$html .= '</table>';
				$html .= '</fieldset>';
				
			}else{
				++$empty;
			}
		}
		
		$html .= '<input type="submit" value="Save All">';
		
		if(count($data) == $empty){
			return '<p style="margin:10% 0% 0% 15%;"> ...has no attendance data. Boo :(</p>';
		}else{
			return $html;
		}
		
		
	}
	
	
}
