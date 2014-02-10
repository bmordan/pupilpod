<script>$(document).ready(function($) {$('html').addClass('ofstedblur');});</script>

<?php
	$this->form->create('Outcomes');
	$this->form->input('activity_ids',array('type'=>'hidden','value'=>$activity_ids));
?>


<div class="formsection">
<?php
	//if the pupils have been selected
	if(isset($forms)){
		echo $this->form->create('Save');
		echo $forms;
		echo $this->form->end('Save All');
	}else{
	//and if they haven't
		echo '<h2>Adding Records for '.date('D d M \'y',strtotime($date)).'</h2>';
		echo '<h2>Choose your pupils below</h2>';
		echo $this->Html->image('icons/user-add.png',array('id'=>'addPupil'));
		echo $this->Form->create('pupilList');
		echo $this->Form->input('ids',array('label'=>false));
		echo $this->Form->end('Add');
	}
?>
</div>





<script type="text/javascript">
	$(document).ready(function() {$("#pupilListIds").tokenInput(<?php echo $json; ?>);});
</script>

<script type="text/javascript">
	$(document).ready(function() {
		$("input[type=button]").click(function () {
			alert("Would submit: " + $(this).siblings("input[type=text]").val());
		});
		$('#pupilListAddForm').hide();
		$('#addPupil').click(function(){
			$('#pupilListAddForm').toggle("slow");
		});

		$('#award').click(function () {
			if($('#award').attr("src") == "/mis/img/icons/award-gray.png"){
				$('#award').attr("src","/mis/img/icons/award-red.png");
			}else{
				$('#award').attr("src","/mis/img/icons/award-gray.png");
			}
		});		
	});
</script>