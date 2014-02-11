<div class="formsection">
<?php 

	$Y = date('Y',strtotime($today));
	if(date('n',strtotime($today))<8){$Y = $Y - 1;}
	$thisY = $Y.'-08-31';
	$EYFS = $Y-4;$EYFS_END = $Y-2;
	$PRI = $Y-10;$PRI_END = $Y-5;
	$SEC = $Y-19;$SEC_END = $Y-11;
?>

	<h3><?php echo 'hello today is '.$today; ?></h3>
	<h3><?php echo 'BASED ON AGE ON 31st Aug '.$Y?></h3>
	<h3><?php echo 'EYFS option_id=1037 BETWEEN "'.$EYFS.'-08-31" AND "'.$EYFS_END.'-08-31"'?></h3>
	<h3><?php echo 'PRIMARY option_id=1038 BETWEEN "'.$PRI.'-08-31" AND "'.$PRI_END.'-08-31"'?></h3>
	<h3><?php echo 'SECONDARY option_id=1039 BETWEEN "'.$SEC.'-08-31" AND "'.$SEC_END.'-08-31"'?></h3>
	<h3><?php echo 'DIALYSIS option_id=1040 BETWEEN "'.$EYFS.'-08-31" AND "'.$SEC_END.'-08-31"'?></h3>
	
	<p>UPDATE pupils SET pupils.option_id=1037 WHERE pupils.date_of_birth BETWEEN "<?php echo $EYFS.'-08-31" AND "'.$EYFS_END.'-08-31";'?></p>
	<p>UPDATE pupils SET pupils.option_id=1038 WHERE pupils.date_of_birth BETWEEN "<?php echo $PRI.'-08-31" AND "'.$PRI_END.'-08-31";'?></p>
	<p>UPDATE pupils SET pupils.option_id=1039 WHERE pupils.date_of_birth BETWEEN "<?php echo $SEC.'-08-31" AND "'.$SEC_END.'-08-31";'?></p>
	<p>UPDATE pupils SET pupils.option_id=1040 WHERE pupils.dialysis=1;</p>
	<p>SELECT option_id,COUNT(DISTINCT id) as pupils FROM pupils GROUP BY option_id</p>
</div>