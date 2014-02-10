<script>$(document).ready(function($) {$('html').addClass('grillblur');});</script>
<!-- top line of navigation -->
<div id="att-section">
	<?php echo '<h1 class="att-pupil-name">'.$this->Html->link($name,array('controller'=>'Pupils','action'=>'view',$pupil_id)).'</h1>';?>
	<?php echo $html;?>
	<?php echo '<div class="icon-list-img" style="float:right;margin:0% 5% 0% 0%;">'.$this->Html->image('icons/th-list-add.png',array('url'=>array('controller'=>'Attendances','action'=>'edit',$date,$pupil_id))).'</div>';?>
</div>