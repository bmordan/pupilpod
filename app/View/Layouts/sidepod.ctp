<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	
	<title>
		<?php if(isset($title_for_layout)){echo $title_for_layout;}else{echo 'SandboxPages';} ?>
	</title>
	
	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->css(array('mis','token-input'));
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
		echo $this->Html->script(array('jquery-1.10.2','jquery.tokeninput'));
	?>
	<link href='http://fonts.googleapis.com/css?family=Happy+Monkey|Peralta' rel='stylesheet' type='text/css'>

	<meta name="viewport" content="initial-scale=1">
	
</head>
<body style="background:#a2b2b2">
<div id="sidepod">
		<a href="staffs/index"><div class="sicon" id="staff"></div></a>
		<a href="pupils/add"><div class="sicon" id="add"></div></a>
</div>
<div id="desk">
<?php echo $this->Session->flash(); ?>
<?php echo $this->fetch('content'); ?>
</div>

<?php echo $this->Js->writeBuffer(); // Write cached scripts ?>
</body>
</html>
