<script>$(document).ready(function($) {$('html').addClass('pokadots');});</script>

<div class="formsection">
<?php echo $this->Form->create('User'); ?>

<?php
	echo '<fieldset><legend>Edit User</legend>';
	echo $this->Form->input('username',array('label' => false,'placeholder' => "Username?"));
	echo $this->Form->input('password',array('label' => false,'placeholder' => "Password?"));
	echo '</fieldset>';
	echo $this->Form->input('role', array(
		'options' => array('admin' => 'Admin', 'staff' => 'Staff', 'guest' => 'Guest')
	));
	echo $this->Form->input('fullname');
	echo $this->Form->input('jobtitle');
	echo $this->Form->input('option_id',array('type' => 'select','options' => $areas,'label'=>'Select your area'));
?>

<?php echo $this->Form->end(__('Submit')); ?>
</div>