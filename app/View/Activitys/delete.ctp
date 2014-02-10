<div class="formsection">
<h2>Are you sure you want to delete this activity?</h2>
<p>Below is a data map showing the pupils and activities linked to this target. If you delete this activity it will permanently remove those links. Any Targets linked to this target will not be effected. Are you sure you want to continue?</p>
<hr /><?php debug($activity);?><hr />
<p style="float:left;">I understand the above and want to delete this activity</p>
<?php echo $this->Html->image('icons/delete.png',array('url'=>(array('action'=>'delete',$activity[0]['Activity']['id'],$staff_id,true)),'style'=>'float:right;margin:2% 0% 0% 0%;'))?>
</div>