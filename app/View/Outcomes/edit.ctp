<script>$(document).ready(function($) {$('html').addClass('ofstedblur');});</script>
<div class="formsection">
	<?php
		echo $this->form->create('Outcome');
		echo $this->form->input('id',array('type'=>'hidden'));
		echo $this->form->input('pupil_id',array('type'=>'hidden'));
		echo $this->form->input('attendance_id',array('type'=>'hidden'));
		echo $this->form->input('activity_id',array('type'=>'hidden'));
		echo $this->form->input('subject_id',array('type'=>'hidden'));
		echo $this->form->input('expected',array('type'=>'hidden'));
		echo $this->form->input('outcomes');
		echo $this->form->input('nextsteps');
		echo $this->form->input('target_id',array('type'=>'hidden'));
		echo $this->form->input('award',array('type'=>'hidden'));
		echo $this->form->input('baseline',array('type'=>'hidden'));
		echo $this->form->end('Update');
	?>
</div>