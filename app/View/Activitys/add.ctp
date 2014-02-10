<script>$(document).ready(function($) {$('html').addClass('activityblur');});</script>

<div class="box l">
	<?php
		echo $this->Form->Create('Activity');
		echo $this->Form->input('staff_id',array('type'=>'hidden','value'=>$staff_id));
		echo $this->Form->input('area_id',array('label'=>'Area','type'=>'select','options'=>$areas));
		echo $this->Form->input('pupil_id',array('type'=>'select','options'=>$dus,'label'=>false,'empty'=>true));
		echo $this->Form->input('subject_id',array('label'=>'Subject ','type'=>'select','options'=>$subjects));
		echo $this->Form->input('title',array('label'=>'Objective'));
		echo $this->Form->input('activity',array('label'=>'Activity','type'=>'textarea'));
		echo $this->Form->end('Add');
		
		echo '<div style="float:right;">'.$this->html->image('icons/plus.png',array('id'=>'addSubject','title'=>'add a subject to the list')).'</div>';
		echo '<div id="addSubjectForm"><form action="/mis/options/addSubject" method="get">'.$this->form->input('sub',array('label'=>'Add this subject')).'<input type="submit" value="Add to list"></div>';
	?>
</div>
<?php #echo '<pre>';echo print_r($areas);echo '<pre>';?>
<script>
  $(function() {
		$('#ActivityPupilId').hide();
		$('#addSubjectForm').hide();
		$('#addSubject').click(function(){
			$('#addSubjectForm').toggle("slow");
		});
		$('#ActivityAreaId').blur(function(){
			if($('#ActivityAreaId option:selected').val() == '1040'){
				$('#ActivityPupilId').show();
			}else{
				$('#ActivityPupilId').hide();
			};
		});
		
  });
</script>