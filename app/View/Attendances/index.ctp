<script>$(document).ready(function($) {$('html').addClass('grillblur');});</script>

<script type="text/javascript">
    $(document).ready(function() {
        $("input[type=button]").click(function () {
            alert("Would submit: " + $(this).siblings("input[type=text]").val());
        });
    });
</script>
			
	
<script type="text/javascript">
	$(document).ready(function() {$("#pupilListIds").tokenInput(<?php echo $json; ?>);});
</script>	

<div class="formsection">
<?php
	if(isset($html)){
		echo $html;
	}else{
		echo $this->Html->image('icons/globe.png',array('url'=>array('controller'=>'activitys','action'=>'index')));
		echo $this->Html->link(' Select Activities for this session',array('controller'=>'activitys','action'=>'index'));
	}
?>
</div>

<?php 
	if(isset($data)){

		echo $data;

	}else{
		echo '<div class="formsection">';
		echo $this->Html->image('icons/group.png',array('url'=>array('controller'=>'activitys','action'=>'index')));			
			echo $this->Form->create('pupilList');
			echo $this->Form->input('ids',array('label'=>false));
			echo $this->Form->end('Next');
		echo '</div>';
	}
?>
<?php #echo '<pre>';echo print_r(?);echo '<pre>';?>