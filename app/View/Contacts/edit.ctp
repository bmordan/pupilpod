<script>$(document).ready(function($) {$('html').addClass('skybeamsblur');});</script>

<div class="formsection">
	<?php echo '<h2>'.$this->Html->image('icons/contacts.png').' Edit Contact</h2>';?>
	
	<?php
		echo $this->Form->create('Contact');
		echo $this->Form->input('pupil_id',array('value'=>$pupil_id,'type'=>'hidden'));
		echo $this->Form->input('first_name');
		echo $this->Form->input('last_name');
		echo $this->Form->input('phone');
		echo $this->Form->input('email');
		echo $this->Form->input('role');
		echo $this->Form->input('report',array('type'=>'checkbox','label'=>'Send Reports','class'=>'regular-checkbox big-checkbox'));
		echo $this->Form->end('Save');
	?>

</div>