<script>$(document).ready(function($) {$('html').addClass('skybeamsblur');});</script>
<div class="formsection">
	
	<label>Did you mean one of these pupils?</label>
	<?php
		foreach($results as $k=>$r){
			echo '<p style="padding:2%;margin:2%;background:#68b821;font-weight:bold;color:#fff;>'.$this->Html->link($r[1],array('action'=>'view',$r[0]));
			echo $this->html->image('icons/arrow-forward-white.png',array('style'=>'float:right;margin-top:-3.3%;','url'=>array('action'=>'view',$r[0]))).'</p>';
		}
	?>
</div>

<?php #echo '<pre>';echo print_r($results);echo '<pre>';?>