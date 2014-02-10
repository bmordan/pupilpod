<script>$(document).ready(function($) {$('html').addClass('grillblur');});</script>
			
	
<script type="text/javascript">
	$(document).ready(function() {$("#pupilListIds").tokenInput(<?php echo $json; ?>);});
</script>

<!-- top line of navigation -->
<div id="att-section">
	<table id="att-nav-table"><tr>
		<td style="text-align: left;">
			<?php
				echo $this->Html->image('icons/arrow-left-thick.png',array('url'=>array('action'=>'edit',$prev)));
			?>
		</td>
		
		<td style="text-align: center;">
			<?php
				echo '<h4>Week of '.$week.'</h4>';
			?>
		</td>
		
		<td style="text-align: right;">
			<?php
				echo $this->Html->image('icons/arrow-right-thick.png',array('url'=>array('action'=>'edit',$next),'style'=>('float:right;')));
			?>
		</td>
	</tr></table>
</div>

<!-- Attendnace grid -->
<div class="att-section">
		<?php
			echo '<form action="/mis/attendances/save" method="post" accept-charset="utf-8">';
			echo '<h4>Early Years Foundation Stage</h4>'.$html[0];
			echo '<h4>Primary</h4>'.$html[1];
			echo '<h4>Secondary</h4>'.$html[2];
			echo '<h4>Dialysis</h4>'.$html[3];
			echo '<input type="submit" value="save all"></form>';
		?>	
</div>



<!-- add pupil box -->
<div class="att-section">
	<?php 
		echo '<div class="att-section">';
			echo $this->Html->image('icons/user-add.png',array('id'=>'addPupil'));
			echo $this->Form->create('pupilList');
			echo $this->Form->input('ids',array('label'=>false));
			echo $this->Form->input('date',array('type'=>'hidden','value'=>$week));
			echo $this->Form->end('Add');
		echo '</div>';
	?>
</div>
<div style="margin:0% 0% 30% 0%;">
	&nbsp;
</div>


<script type="text/javascript">
	$(document).ready(function() {
		$("input[type=button]").click(function () {
			alert("Would submit: " + $(this).siblings("input[type=text]").val());
		});
		$('#pupilListEditForm').hide();
		$('#addPupil').click(function(){
			$('#pupilListEditForm').toggle("slow");
		});		
	});
</script>
<?php # echo '<pre>';echo print_r($data);echo '<pre>';?>