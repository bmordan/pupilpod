<script>$(document).ready(function($) {$('html').addClass('atriumblur');});</script>
<div class="formsection">
	<?php
		echo $this->form->create('Note');
		echo $this->form->input('staff_id',array('type'=>'hidden','value'=>$staff_id));
		if(isset($name)){
			echo $this->form->input('pupil_id',array('value'=>$pupil_id,'type'=>'hidden'));
			echo '<h2>'.$name.'</h2>';
		}else{
			echo $this->Html->image('icons/user-add.png',array('id'=>'addPupil'));
			echo '<div class="ui-widget"><input id="PupilPupil"></div>';
			echo $this->Form->input('pupil_id',array('type'=>'hidden','id'=>'pupil_id'));		
		}
		echo $this->form->input('date',array('value'=>date('Y-m-d',time()),'label'=>false));
		echo $this->form->input('dayindex',array('type'=>'hidden','value'=>$dayindex));
		echo $this->form->input('note',array('type'=>'textarea','style'=>'width:100%;'));
		echo $this->form->end('Save');
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
				$('#pupil_id').val(ui.item.value);
				return false;
			}
		});
		$('.ui-widget').hide();
		$('#addPupil').click(function(){
			$('.ui-widget').toggle("slow");
		});	
  });
</script>