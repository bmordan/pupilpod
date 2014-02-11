<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	
	<title>
		<?php if(isset($title_for_layout)){echo $title_for_layout;}else{echo 'Evelina Hospital School';} ?>
	</title>
	
	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->css(array('paper2','token-input'));
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');

	?>
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
	<script src="http://10.23.28.5/mis/js/jquery.tokeninput.js"></script>
	<link href='http://fonts.googleapis.com/css?family=Comfortaa|Open+Sans' rel='stylesheet' type='text/css'>
	<meta name="viewport" content="initial-scale=1">
	
</head>
<body>

	<div id="header">
		<?php
			
			echo $this->Html->image('logo-black.png',array('id'=>'logo-white'));
			//only admin users get the spanner
			if($this->session->read('Auth.User.role') == 'admin'){
				echo $this->Html->image('icons/spanner.png',array('url'=>array('controller'=>'users','action'=>'menu'),'class'=>'icons-home','title'=>'Settings'));}
			echo $this->Html->image('icons/media-eject.png',array('url'=>array('controller'=>'users','action'=>'logout'),'class'=>'icons-home','title'=>'Log Off'));
			echo $this->Html->image('icons/home.png',array('url'=>array('controller'=>'users','action'=>'index'),'class'=>'icons-home','title'=>'Home'));
			echo '<div id="flash">'.$this->Session->flash().'</div>';
			
		?>
	</div>


	<div id="paper">
		<?php echo $this->fetch('content'); ?>
	</div>
</body>
</html>
