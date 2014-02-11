<script>$(document).ready(function($) {$('html').addClass('pokadots');});</script>

<div id="loginbox">

	<div id="loginlogo"><?php echo $this->html->image('pupilpod_shadow.png'); ?></div>

	<?php 
		echo $this->Form->create('User');
		echo $this->Form->input('username',array('placeholder'=>"username?",'label'=>false));
		echo $this->Form->input('password',array('placeholder'=>"password?",'label'=>false));
		echo $this->Session->flash('auth');
		echo $this->Form->end(__('Login'));
	?>

</div>	