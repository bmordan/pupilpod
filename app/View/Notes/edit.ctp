<script>$(document).ready(function($) {$('html').addClass('atriumblur');});</script>
<div class="formsection">
	<?php
		echo $this->form->create('Note');
		echo $this->form->input('id',array('type'=>'hidden'));
		echo $this->form->input('staff_id',array('type'=>'hidden','value'=>'31'));
		if(isset($name)){
			echo $this->form->input('pupil_id',array('value'=>$pupil_id,'type'=>'hidden'));
			echo '<h2>'.$name.'</h2>';
		}
		
		echo $this->form->input('date',array('value'=>date('Y-m-d',time()),'label'=>false));
		echo $this->form->input('dayindex',array('type'=>'hidden'));
		echo $this->form->input('note',array('placeholder'=>'Private Teachers Note'));
		echo $this->form->input('tcomment',array('label'=>'Teachers Comment'));
		echo $this->form->input('pcomment',array('label'=>'Pupils Comment'));
		echo $this->form->end('Update');
	?>
</div>