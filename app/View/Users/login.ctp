<script>$(document).ready(function($) {$('html').addClass('pokadots');});</script>
<div class="bigsection">
	<div class="lcol">
		<?php echo $this->Html->image('logo1.png');?>
	</div>

	<div class="rcol">
		<?php 
			echo $this->Session->flash('auth');
			echo $this->Form->create('User');
			echo $this->Form->input('username',array('label'=>false));
			echo $this->Form->input('password',array('label'=>false));
			echo $this->Form->end(__('Login'));
		?>
	</div>
</div>	