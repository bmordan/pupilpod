<script>$(document).ready(function($) {$('html').addClass('pokadots');});</script>


<div class="box">
	<?php
		echo $this->Form->Create('Target');
		if(isset($pupil_id)){
			echo '<h5>'.$name.'</h5>';
			echo $this->Form->input('pupil_id',array('type'=>'hidden','value'=>$pupil_id));
		}else{
			echo $this->Html->image('icons/user-add.png',array('id'=>'addPupil'));
			echo '<div class="ui-widget"><input id="PupilPupil"></div>';
			echo $this->Form->input('pupil_id',array('type'=>'hidden'));
		}
		
		echo $this->Form->input('staff_id',array('type'=>'hidden','value'=>$staff_id));
		echo $this->Form->input('option_id',array('label'=>'Subject','type'=>'select','options'=>$subjects));
		echo $this->Form->input('target');
		echo $this->Form->end('Add');
	?>
</div>

<div class="box">
	<?php
		$tar = 0;foreach($targets as $k => $t){foreach($t as $sub => $list){$tar = $tar+count($list);}}
		echo '<label>'.$this->session->read('Auth.User.fullname').' | '.$tar.' Targets Set</label>';
		foreach($targets as $n => $option_id){
			foreach($option_id as $subject => $tars){ // subject header
			 echo '<fieldset><legend><h3>'.ClassRegistry::init('Option')->getOption($subject).'</h3></legend>';
			 foreach($tars as $k=>$v){ // targets by subject
				echo '<li style="text-align:right;clear:both;"><p class="target-list-display">'.$v['targets']['target'].'</p> '.
				$this->Html->image('icons/edit.png',array('url'=>array('action'=>'edit',$v['targets']['id']))).' '.
				$this->Html->image('icons/delete.png',array('url'=>array('action'=>'delete',$v['targets']['id'],$staff_id))).'</li>';
			 }
			echo '</fieldset>'; 
			}
		}

	?>
</div>


<script>
  $(function() {
    var pupils = <?php echo $json;?>;
		$('#PupilPupil').autocomplete({
			minLength: 3,
			source: pupils,
			select: function (event, ui){
				$(this).val(ui.item.label);
				$('#TargetPupilId').val(ui.item.value);
				return false;
			}
		});
		$('.ui-widget').hide();
		$('#addPupil').click(function(){
			$('.ui-widget').toggle("slow");
		});	
  });
</script>