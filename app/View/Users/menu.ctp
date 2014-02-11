<script>$(document).ready(function($) {$('html').addClass('atriumblur');});</script>

<div class="formsection">
	

	<li><?php echo $this->html->link('Year Groups',array('action'=>'dob')); ?><br /><br /><i>tool for quickly looking up date of birth ranges (you can pass a different year at the end of the URL like so /YYYY-MM-DD</i></li>
	
	<hr />
	
	<li><?php echo $this->html->link('Add User',array('action'=>'add')); ?><br /><br />
	<?php
		foreach($users as $k => $v){
			echo $v['User']['fullname'].' | '.$v['User']['jobtitle'].' ('.$v['User']['role'].') | ';
			echo $this->html->link('edit',array('action' => 'edit',$v['User']['id']));
			echo '<br />';
		}
	?>
	</li>

	
</div>