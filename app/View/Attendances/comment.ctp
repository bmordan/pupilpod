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

<?php 
	if(isset($data)){
		echo '<div class="att-section">';
		echo $data;
		echo '</div>';
	}else{
		echo '<div class="att-section"><p>Add attendance with comments</p>';
			echo $this->Form->create('pupilList');
			echo $this->Form->input('ids',array('label'=>false));
			echo $this->Form->end('Next');
		echo '</div>';
	}
?>
<?php #echo '<pre>';echo print_r($data);echo '<pre>';?>