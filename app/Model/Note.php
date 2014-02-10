<?php
class Note extends AppModel{

	function compactComment($formdata){
		$tcomment = '';
		foreach($formdata as $subject_id => $comment){
			if($subject_id != 'Note'){
				if(!empty($comment)){
					$tcomment .= '<p><u>'.ClassRegistry::init('Option')->getOption($subject_id).'</u></p>';
					$tcomment .= '<p>'.$comment.'</p>';
				}
			}
		}
		return $tcomment;
	}
	
	function getReportNotes($pupil_id,$dates){
		return $this->query("SELECT id,tcomment,pcomment FROM notes WHERE pupil_id=".$pupil_id." AND date BETWEEN ".$dates[0]." AND ".$dates[1].";");
	}
	
	function getPupilNotes($pupil_id){
		return $this->query("SELECT * FROM notes WHERE pupil_id=".$pupil_id.";");
	}
	
	function logReport($pupil_id,$schoolemail){
		$this->query("INSERT into notes (staff_id,pupil_id,note,date,reportstatus) VALUES (31,".$pupil_id.",'".$schoolemail."','".date('Y-m-d H:i:s',time())."',1053) ;");
	}
	
	function logReportError($pupil_id,$error){
		$this->query("INSERT into notes (staff_id,pupil_id,note,date,reportstatus) VALUES (31,".$pupil_id.",'".$error."','".date('Y-m-d H:i:s',time())."',1054) ;");
	}	
	
	function getAlreadySent($from,$to){
		$exclude = '';
		$pupils = $this->query("SELECT pupil_id FROM notes WHERE reportstatus=1053 AND date BETWEEN ".$from." AND ".$to.";");
		foreach($pupils as $k => $v){
			$exclude .= $v['notes']['pupil_id'].',';
		}
		return substr($exclude,0,-1);
	}
}