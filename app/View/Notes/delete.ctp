<script>$(document).ready(function($) {$('html').addClass('atriumblur');});</script>
<div class="formsection">
	<?php 
		echo '<p>You are about to delete this record</p>';
		echo debug($delete);
		echo '<p>Are you sure you want to remove this note?</p>';
		echo '<p style="float:right;margin:-5% 0% 0% 0%;">'.$this->html->image('icons/delete.png',array('url'=>array('action'=>'delete',$delete['Note']['id'],1))).'</p>';
	?>
</div>