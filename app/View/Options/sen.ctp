<script>$(document).ready(function($) {$('html').addClass('skybeamsblur');});</script>

<div class="formsection">
	<?php echo '<h2 style="text-align:center;">Add Special Educational Needs '.$name.'</h2>';?>
</div>
<div class="formsection">
	<?php
		echo $this->Form->create('Sens');
		foreach($list as $hash => $option){
			echo $this->Form->input($option['Option']['name'],array('type'=>'checkbox','class'=>'regular-checkbox big-checkbox','value'=>$option['Option']['id']));
		}
	?>	
</div>
<div class="formsection">
<?php echo $this->Form->end('Save');?>
</div>

