<script>$(document).ready(function($) {$('html').addClass('skybeamsblur');});</script>
<script>
  $(function() {
		var leas = <?php echo $leas;?>;
		var schools = <?php echo $schools;?>;
		
		//lea autocomplete
		$('#PupilLea').autocomplete({
			source: leas,
			select: function (event, ui){
				$('#PupilLeaId').val(ui.item.value);
				$(this).val(ui.item.label);
				return false;
			}
		});
		//school autocomplete
		$('#PupilSchool').autocomplete({
			minLength: 3,
			source: schools,
			select: function (event, ui){
				$('#PupilSchoolId').val(ui.item.value);
				$(this).val(ui.item.label);
				return false;
			}
		});
  });
</script>
	<div class="formsection">
		<?php echo '<h2>'.$this->html->image('icons/user-add.png').' Add Pupil</h2>';?>
	<?php
		echo $this->Form->create('Pupil');
		echo $this->Form->input('first_name',array('label'=>'Name'));
		echo $this->Form->input('last_name',array('label'=>false));
		echo $this->Form->input('date_of_birth',array('type'=>'date'));
		echo $this->Form->input('ethnicity',array('type'=>'select','default'=>'Not Declared','options'=>$ethnicity,'value'=>$ethnicity));
	?>
	</div>





	<div class="formsection">

	<?php	
		echo $this->Form->input('Male',array('type'=>'checkbox','class'=>'regular-checkbox big-checkbox'));
		echo $this->Form->input('gender',array('type'=>'checkbox','label'=>'Female','class'=>'regular-checkbox big-checkbox'));
		echo $this->Form->input('sibling',array('type'=>'checkbox','class'=>'regular-checkbox big-checkbox'));
		echo $this->Form->input('patient',array('type'=>'checkbox','class'=>'regular-checkbox big-checkbox'));	
		echo $this->Form->input('sen_statement',array('type'=>'checkbox','class'=>'regular-checkbox big-checkbox','label'=>'Statement'));
		echo $this->Form->input('sen',array('type'=>'checkbox','class'=>'regular-checkbox big-checkbox'));
		echo $this->Form->input('dialysis',array('type'=>'checkbox','label'=>'Dialysis Pupil','class'=>'regular-checkbox big-checkbox'));
		echo $this->Form->input('photo_consent',array('type'=>'checkbox','class'=>'regular-checkbox big-checkbox'));
	?>

	</div>










	<div class="formsection">	
	<?php
		echo $this->Form->input('address_1');
		echo $this->Form->input('address_2');
		echo $this->Form->input('city');
		echo $this->Form->input('postcode');
		echo $this->Form->input('lea_id',array('type'=>'hidden'));
	?>
	</div>
	<div class="formsection">
	<div class="ui-widget"><?php echo $this->Form->input('lea',array('type'=>'option','label'=>'Local Authority'));?></div>
	<div class="ui-widget"><?php echo $this->Form->input('school');?><p id="school-address"></p></div>
	</div>
	<div class="formsection">
	<?php
		 echo $this->Form->input('school_id',array('type'=>'hidden'));
		 echo $this->Form->input('notes',array('type'=>'textarea'));	 
		 echo $this->Form->end('Save');
	?>
	</div>
<?php # echo '<pre>';echo print_r($schools);echo '<pre>'; ?>