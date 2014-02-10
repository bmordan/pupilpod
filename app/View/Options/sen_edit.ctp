<script>$(document).ready(function($) {$('html').addClass('skybeamsblur');});</script>

<div class="formsection">
	<?php echo '<h2 style="text-align:center;">Edit Special Educational Needs '.$name.'</h2>';?>
</div>
<div class="formsection">
	<?php
		echo $this->Form->create('Sens');
		foreach($list as $hash => $option){
			if(in_array($option['Option']['id'],$defaults)){//set previous as selected
				echo $this->Form->input($option['Option']['name'],array('type'=>'checkbox','class'=>'regular-checkbox big-checkbox','value'=>$option['Option']['id'],'default'=>true));
			}else{
				echo $this->Form->input($option['Option']['name'],array('type'=>'checkbox','class'=>'regular-checkbox big-checkbox','value'=>$option['Option']['id']));
			}	
		}
	?>	
</div>
<div class="formsection">
<?php echo $this->Form->end('Save');?>
</div>
<?php # echo '<pre>';echo print_r($defaults);echo '<pre>'; ?>