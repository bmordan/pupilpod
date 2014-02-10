<script>$(document).ready(function($) {$('html').addClass('ofstedblur');});</script>

<div class="formsection" style="overflow:hidden;">
	<p>You are about to remove the following activity from the database. This will unlink the pupil from this activity (but not delete the activity) BUT it <u>will</u> delete the evaluation and nextsteps data.</p>
	<hr />
		<?php debug($row); ?>
	<hr />
	<p style="float:left;clear:both;">Are you sure you want to do this?</p>
	<?php echo $this->Html->image('icons/delete.png',array('url'=>array('action'=>'remove',$id,$pupil_id,true),'style'=>'float:right;display:inline;'));?>
	<p style="float:left;clear:both;">You can edit this Evaluation?</p>
	<?php echo $this->Html->image('icons/edit.png',array('url'=>array('action'=>'edit',$id,$pupil_id),'style'=>'float:right;display:inline;'));?>
</div>