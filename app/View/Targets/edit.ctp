<script>$(document).ready(function($) {$('html').addClass('pokadots');});</script>


<div class="box">
	<?php
		echo $this->Form->Create('Target');
		echo $this->Form->input('staff_id',array('type'=>'hidden','value'=>$staff_id));
		echo $this->Form->input('option_id',array('label'=>'Subject','type'=>'select','options'=>$subjects));
		echo $this->Form->input('target');
		echo $this->Form->end('Update');
		
	?>
</div>

<div class="box">
	<?php  
		if(empty($pupils)){
			echo '<li>Add Pupils to this target:</li><li><div class="ui-widget"><input id="pupil"><span id="pid"></span></div></li>';}
		else{
			echo '<ul><p>Pupils attached to this target:</p>';
				foreach($pupils as $n => $q){
					echo '<li style="background:#fb6e00;margin:2%;padding:5%;">
					
					'.$this->Html->link(ClassRegistry::init('Pupil')->getName($q['pupils_targets']['pupil_id']),array('controller'=>'pupils','action'=>'view',$q['pupils_targets']['pupil_id'])).'
					
					'.$this->Html->image('icons/delete.png',array('url'=>array('action'=>'removePupil',$q['pupils_targets']['id'],$q['pupils_targets']['target_id']),'style'=>'float:right;margin-top:-5%;')).'</li>';
				}
			echo '<li>Add more pupils? start typing in the box below</li>';	
			echo '<li><div class="ui-widget"><input id="pupil"><span id="pid"></span></div></li>';
			echo '</ul>';
		}
		#echo '<pre>';echo print_r($pupils);echo '<pre>';
	?>
</div>


<div class="box">
	<?php
		$tar = 0;foreach($targets as $k => $t){foreach($t as $sub => $list){$tar = $tar+count($list);}}
		echo '<label>'.ClassRegistry::init('Staff')->getStaff($staff_id).' | '.$tar.' Targets Set</label>';
		foreach($targets as $n => $option_id){
			foreach($option_id as $subject => $tars){ // subject header
			 echo '<fieldset><legend><h3>'.ClassRegistry::init('Option')->getOption($subject).'</h3></legend>';
			 foreach($tars as $k=>$v){ // targets by subject
				echo '<li style="text-align:right;"><p class="target-list-display">'.$v['targets']['target'].'</p> '.
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
	var target_id = <?php echo $id;?>;
		$('#pupil').autocomplete({
			source: pupils,
			select: function (event, ui){
				$('#pid').append("<a href='http://10.23.28.5/mis/targets/addPupil/" + ui.item.value + "/" + target_id + "'><img src='http://10.23.28.5/mis/img/icons/plus.png'/></a>");
				$(this).val(ui.item.label);
				return false;
			}
		});
  });
</script>