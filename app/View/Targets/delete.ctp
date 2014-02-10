<div class="formsection">
<h2>Are you sure you want to delete this target?</h2>
<p>Below is a data map showing the pupils and activities linked to this target. If you delete this target it will permanently remove those links. Any activities linked to this target will default to the generic hospital education target. Are you sure you want to continue?</p>
<hr /><?php debug($target);?><hr />
<p style="float:left;">I understand the above and want to delete this target</p>
<?php echo $this->Html->image('icons/delete.png',array('url'=>(array('action'=>'delete',$target[0]['Target']['id'],$staff_id,true)),'style'=>'float:right;margin:2% 0% 0% 0%;'))?>
</div>