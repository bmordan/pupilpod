<div class="formsection">
	<?php
		echo '<li>Period of reports '.$from.' - '.$to.'</li>';
		echo '<li>Total pupils : '.$count[0][0]['pupils'].'</li>';
		echo '<li>Total emails sent : '.$sent[0][0]['sent'].'</li>';
		echo '<li>Emails not sent : '.$notsent[0][0]['notsent'].'</li>';
	?>
</div>

<?php echo $this->html->link('send again',array('action'=>'emailSchools')) ?>