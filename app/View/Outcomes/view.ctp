<script>$(document).ready(function($) {$('html').addClass('ofstedblur');});</script>
<div class="formsection">
<?php
	echo '<h2>'.$name.'</h2>';
	
	if(isset($subjects)){
		echo $subjects;
			if(isset($schoolemail)){
			
				echo '<li style="float:left;width:48%;">'.$this->Html->image('icons/mail.png',array('url'=>array('controller'=>'Contacts','action'=>'send',$pupil_id,$settings),'title'=>'Send Email','class'=>'icons-list-img')).$this->Html->link($schoolemail,array('controller'=>'Contacts','action'=>'send',$pupil_id,$settings)).'</li>';
				
				echo '<li style="float:right;width:48%;">'.$this->Html->image('icons/printer.png',array('url'=>array('controller'=>'Contacts','action'=>'send',$pupil_id,$settings,'p'),'title'=>'Print Version','class'=>'icons-list-img')).$this->Html->link('Print Version',array('controller'=>'Contacts','action'=>'send',$pupil_id,$settings,'p')).'</li>';
				
			}else{
			
				echo 'No school email';
				
			}
		}else{
			echo $this->form->create('settings');
			
			echo '<div style="clear:both;">';
			echo $this->form->input('attendance',array('type'=>'checkbox','label'=>'Include attendance for term','class'=>'regular-checkbox big-checkbox'));
			
			echo '</div><div style="clear:both;">';
			echo $this->form->input('targets',array('type'=>'checkbox','label'=>'Include targets','class'=>'regular-checkbox big-checkbox'));
			
			echo '</div><div style="clear:both;">';
			echo $this->form->input('activities',array('type'=>'checkbox','label'=>'Include activities by subject','class'=>'regular-checkbox big-checkbox'));
			
			echo '</div><div style="clear:both;">';
			echo $this->form->input('outcomes',array('type'=>'checkbox','label'=>'Include evaluations and next steps','class'=>'regular-checkbox big-checkbox'));
			
			echo '</div><div style="clear:both;">';
			echo $this->form->end('Next');
			echo '</div>';

			if(!empty($reportNotes[0]['notes']['tcomment'])){
				if(!empty($reportNotes[0]['notes']['pcomment'])){echo '<i>"'.$reportNotes[0]['notes']['pcomment'].'"</i>';}
				echo $reportNotes[0]['notes']['tcomment'];
			}else{
				echo '<li>'.$this->Html->link('Add Final Summary',array('controller'=>'notes','action'=>'comment',31,$pupil_id)).'</li>';
			}
	}
?>
</div>

<?php #echo '<pre>';echo print_r($subjects);echo '<pre>'; ?>

<?php if(isset($hideTargets)){echo $hideTargets;}?>