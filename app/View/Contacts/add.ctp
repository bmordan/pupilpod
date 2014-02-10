<script>$(document).ready(function($) {$('html').addClass('skybeamsblur');});</script>

<div class="formsection">
	<?php echo '<h2>'.$this->Html->image('icons/contacts.png').' Add Contacts for '.$name.'</h2>';?>

		<?php
			if($contacts == false){
				echo '<h2><i>No Contacts</i></h2>';
			}else{
				foreach($contacts as $k=>$v){
					if($v['Contact']['report'] == '1'){$default = ' [set as default email]';}else{$default = '';}
					echo '<ul>';
							echo '<li><h2>'.$v['Contact']['first_name'].' '.$v['Contact']['last_name'].'</h2><hr><li>';
							echo '<li><strong>Role | </strong>'.$v['Contact']['role'].'<li>';
							echo '<li><strong>Phone | </strong> '.$v['Contact']['phone'].'<li>';
							echo '<li><strong>Email | </strong> '.$v['Contact']['email'].$default.'<li>';
							echo '<li>'.$this->Html->image('icons/edit.png',array('url'=>array('action'=>'edit',$v['Contact']['id'],$pupil_id))).'<li>';
					echo '</ul>';
				}
			}
		?>
	<!-- SKIP redirect -->
		<div id="skip">
			<?php if($sen == '1'){
				echo '<form method="GET" action="/mis/options/sen/'.$pupil_id.'">';
			}else{
				echo '<form method="GET" action="/mis/pupils/view/'.$pupil_id.'">';
			}?>
				<input type="submit" value="Back">
			</form>
		</div>	
	<!-- End SKIP redirect -->
	
</div>
<div class="formsection">
	
	<?php
		echo $this->Form->create('Contact');
		echo $this->Form->input('pupil_id',array('value'=>$pupil_id,'type'=>'hidden'));
		echo $this->Form->input('first_name');
		echo $this->Form->input('last_name');
		echo $this->Form->input('phone');
		echo $this->Form->input('role');
		echo $this->Form->input('email');
		echo $this->Form->input('report',array('type'=>'checkbox','label'=>'Send Reports','class'=>'regular-checkbox big-checkbox'));	
		echo $this->Form->end('Add');
	?>

</div>


<script>
	$(document).ready(function(){
		$('div.input.checkbox').hide();
		$('#ContactEmail').change(function(){
			if($(this).val() != ''){
				$('div.input.checkbox').show();
			}else{
				$('div.input.checkbox').hide();
			}	
		});
	});	
</script>