<script>$(document).ready(function($) {$('html').addClass('activityblur');});</script>
<script>
  $(function() {
		$('#addSubjectForm').hide();
		$('#addSubject').click(function(){
			$('#addSubjectForm').toggle("slow");
		});	
  });
</script>
<div class="box l">
	<?php
		echo $this->Form->Create('Activity');
		echo $this->Form->input('staff_id',array('type'=>'hidden','value'=>$staff_id));
		echo $this->Form->input('area_id',array('label'=>'Area','type'=>'select','options'=>$areas));
		echo $this->Form->input('subject_id',array('label'=>'Subject ','type'=>'select','options'=>$subjects));
		echo $this->Form->input('title');
		echo $this->Form->input('activity',array('label'=>'Activity-Lesson Plan','type'=>'textarea'));
		echo $this->Form->end('Add');
		
		echo '<div style="float:right;">'.$this->html->image('icons/spanner.png',array('id'=>'addSubject')).'</div>';
		echo '<div id="addSubjectForm"><form action="/mis/options/addSubject" method="get">'.$this->form->input('sub',array('label'=>'Add this subject')).'<input type="submit" value="Add to list"></div>';
	?>
</div>