<script>$(document).ready(function($) {$('html').addClass('ofstedblur');});</script>
<div class="formsection">
	<?php echo '<h3>Report Summary for '.$name.'</h3>';?>
	<?php
		if(isset($notes)){
			
			if(!empty($notes[0]['notes']['pcomment'])){
				echo '<h3>Pupil Comment</h3>';
				echo '<p>'.$notes[0]['notes']['pcomment'].'</p>';
			}
			if(!empty($notes[0]['notes']['tcomment'])){
				echo $notes[0]['notes']['tcomment'];
			}
			echo '<p class="r">'.$this->html->image('icons/edit.png',array('url'=>array('action'=>'edit',$notes[0]['notes']['id']))).$this->html->image('icons/delete.png',array('url'=>array('action'=>'delete',$notes[0]['notes']['id']))).'</p>';
				
		}else{
			echo $this->form->Create('Note');
			echo $this->form->input('staff_id',array('type'=>'hidden','value'=>$staff_id));
			echo $this->form->input('pupil_id',array('type'=>'hidden','value'=>$pupil_id));
			echo $this->form->input('date',array('type'=>'hidden','value'=>$date));
		
			if(empty($subjects)){
				echo '<p class="l">No activities or subjects have been recorded yet.</p>';
				echo '<p class="r">'.$this->html->link('Back',array('controller'=>'pupils','action'=>'view',$pupil_id)).'</p>';
			}else{
				foreach($subjects as $k=>$v){
						$header = ClassRegistry::init('Option')->getOption($v);
						echo '<li>'.$header.'</li>';
						echo '<li><input name="'.$v.'" type="textarea"></li>';
				}
				echo $this->form->input('pcomment',array('label'=>'Add comments from the pupil'));
				echo $this->form->end('Save');
			}
		}
	?>
</div>