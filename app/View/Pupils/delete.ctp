<div class="formsection">
	<p>You are about to delete this pupil and their records. Are you sure you want to do this?</p>
	<hr />
		<?php debug($pupil); ?>
		<?php debug($records); ?>
	<hr />
	<p style="float:left;">I confirm that I want to delete this pupil and their records from the system. This can not be undone.</p>
	<?php echo $this->Html->image('icons/delete.png',array('url'=>array('action'=>'delete',$pupil[0]['Pupil']['id'],true),'style'=>'float:right;display:inline;'));?>
</div>