<?php

App::uses('CakeEmail', 'Network/Email');

class AttendancesController extends AppController{

	public function index(){
		if(!empty($_GET['ids'])){
			$list = explode(',',$_GET['ids']);$html = '';
			foreach($list as $n => $id){
				$render = ClassRegistry::init('Activity')->find('all',array('conditions'=>array('id'=>$id)));
				$html .= $render[0]['Activity']['activity'];
				$html .= '<input name="Activity-'.$render[0]['Activity']['id'].'" type="hidden" value="'.$render[0]['Activity']['id'].'">';
			}
		$this->set('html',$html);
		}
		
		$this->set('json',ClassRegistry::init('Pupil')->json('true'));
		if($this->request->is('post')){
			$data = $this->Attendance->getMultiAttendanceForm($this->data);
			$this->set('data',$data);
		}
	}
	
	public function save(){
		if($this->request->is('post')){
			$week = $this->data['week'];
			$this->Attendance->bigSave($this->data,$week);
			$this->redirect(array('action'=>'edit',$week));
		}
		else{
			$this->set('data',array('no'=>'data <a href="/mis/attendances/edit"> back</a>'));
		}
	}
	
	public function edit($date = null,$ids = null){
		$this->set('title_for_layout','Weekly Attendance');
		$this->set('json',ClassRegistry::init('Pupil')->json('true'));
		if($date == null){$today = date('Y-m-d',time());$date = $this->Attendance->getMonday($today);}
		$this->set('next',$this->Attendance->next($date));
		$this->set('prev',$this->Attendance->prev($date));
		$this->set('week',date('d-M-Y',strtotime($date)));
		$areas = array(1037,1038,1039,1040);$html = array();
		foreach($areas as $k=>$v){
			if($ids == null){ // load a page from the database
				$data = $this->Attendance->getAttendance($date,$v);
				array_push($html,$this->Attendance->htmlAttendance($data,$date));
			}else{ // intergrate added pupils
				$id = explode(',',$ids);
				$sortedids = '';
				foreach($id as $k=>$i){
					$area = ClassRegistry::init('Pupil')->getArea($i);
					if($area == $v){$sortedids .= $i.',';}
				}
				$data = $this->Attendance->getAttendance($date,$v,substr($sortedids,0,-1));
				array_push($html,$this->Attendance->htmlAttendance($data,$date));
			}
		}
		$this->set('html',$html);
		if($this->request->is('post')){// if submitted reload page with additional pupils
			$ids = $this->data['pupilList']['ids'];
			$this->redirect(array('action'=>'edit',date('Y-m-d',strtotime($this->data['pupilList']['date'])),$ids));		
		}
	}
	
	public function pupil($pupil_id){
		$this->set('title_for_layout','Pupil Attendance');
		$data = $this->Attendance->getPupilAttendance($pupil_id);
		$html = $this->Attendance->getPupilAttendanceHtml($data,$pupil_id);
		$this->set('pupil_id',$pupil_id);
		$this->set('date',$this->Attendance->getMonday(date('Y-m-d',time())));
		$this->set('name',ClassRegistry::init('Pupil')->getName($pupil_id));
		$this->set('html',$html);
		
		if($this->request->is('post')){
			foreach($this->data as $dates => $code){
				$k = explode('_',$dates);
				if($code == '\\'){$code = $code.'\\';}
				$this->Attendance->littleSave($pupil_id,$code,$k[0],$k[1]);
			}
			$this->redirect(array('action'=>'pupil',$pupil_id));
		}
	}
	
	public function comment(){
		$this->set('json',ClassRegistry::init('Pupil')->json('true'));
	}
	
	public function autoReporting($date = null){
		$this->set('title_for_layout','Manage Reporting'); 
		if(empty($date)){$date = date('Y-m-d',time());}
		$dates = $this->Attendance->getTermDates($date);
		//set pupilsessions for the unique collection of pupils for this term
		$this->Attendance->setPupilsessions($dates[0],$dates[1]);			
		$form = $this->Attendance->getEmptySchoolEmails();
		$this->set('form',$form);
	}
	
	public function emailSchools($date = null){//sends emails to schools
		if(empty($date)){$date = date('Y-m-d',time());}
		$dates = $this->Attendance->getTermDates($date);
		$this->Attendance->setPupilsessions($dates[0],$dates[1]);
		
		if($this->request->is('post')){
			$exclude = ClassRegistry::init('Note')->getAlreadySent($dates[0],$dates[1]);
			$pupils = $this->Attendance->query("SELECT pupil_id FROM pupilsessions WHERE pupil_id NOT IN(".$exclude.");");
			foreach($pupils as $k=>$v){
				$pupil_id = $v['pupilsessions']['pupil_id'];
				$schoolemail = ClassRegistry::init('School')->getSchoolEmail($pupil_id);
				if(!empty($schoolemail)){
					$header =  ClassRegistry::init('Contact')->getHeaderText($pupil_id,$dates[0],$dates[1]);
					$footer = ClassRegistry::init('Contact')->getFooterText($pupil_id);
					$style = ClassRegistry::init('Contact')->getStyle();
					$cc = ClassRegistry::init('Contact')->getCc($pupil_id);
					$subject_ids = ClassRegistry::init('Outcome')->getSubjects($pupil_id);
					$report = ClassRegistry::init('Attendance')->getRegister($pupil_id,$dates[0],$dates[1]);
					$emailContent = $style.$header.$report.$footer;
					$this->set('html',$emailContent);
					//send the email
					$Email = new CakeEmail();
					$Email->config('lgfl');
					$Email->emailFormat('html');
					$Email->from('bmordan@evelina.southwark.sch.uk');
					#$Email->to('office@evelina.southwark.sch.uk');
					$Email->to($schoolemail);
					if(!empty($cc)){$Email->cc($cc);}
					$Email->subject('School Report from Evelina Hospital School');
					try{
						if($Email->send($emailContent)){
							ClassRegistry::init('Note')->logReport($pupil_id,$schoolemail);
						}else{
							ClassRegistry::init('Note')->logReportError($pupil_id,'Error in try');
						}
					}catch( Exception $e ){
						$error = $e->getMessage();
						ClassRegistry::init('Note')->logReportError($pupil_id,$error);
					}
				}				
			}
			$this->redirect(array('action'=>'emailresults'));
		}
	}
	
	public function emailresults($date = null){
		if(empty($date)){$date = date('Y-m-d',time());}
		$dates = ClassRegistry::init('Attendance')->getTermDates($date);
		$this->set('from',$dates[0]);
		$this->set('to',$dates[1]);
		$this->set('sent',$this->Attendance->query("SELECT COUNT(id) as sent FROM notes WHERE reportstatus='1053' AND date BETWEEN ".$dates[0]." AND ".$dates[1].";"));
		
		$this->set('notsent',$this->Attendance->query("SELECT COUNT(id) as notsent FROM notes WHERE reportstatus='1054' AND date BETWEEN ".$dates[0]." AND ".$dates[1].";"));
		
		$this->set('count',$this->Attendance->query("SELECT COUNT(pupil_id) as pupils FROM pupilsessions"));
	}
	
}
