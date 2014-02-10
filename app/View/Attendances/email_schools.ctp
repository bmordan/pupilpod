<div class="formsection">

<?php echo $this->Html->image('icons/mail.png',array('url'=>array('action'=>'autoReporting'))).' '.$this->Html->link('auto email schools',array('action'=>'autoReporting'),array('class'=>'h1'));?>
	
	
	<?php
	
		if(!empty($output[0])){
			echo $this->form->create('send');
			foreach($output as $n => $table){
				foreach($table as $id => $html){
					echo '<div class="preview-email">';
					echo $html;
					echo $this->form->input($id,array('type'=>'checkbox','label'=>'Send this one','class'=>'regular-checkbox big-checkbox'));
					echo '</div>';
				}
			}
			echo $this->form->end('Send All');
		}else{
			echo $this->form->create('settings');
			
			echo '<div style="clear:both;">';
			echo $this->form->input('attendance',array('type'=>'checkbox','label'=>'Include attendance for term','class'=>'regular-checkbox big-checkbox'));
			
			echo '</div><div style="clear:both;">';
			echo $this->form->input('targets',array('type'=>'checkbox','label'=>'Include targets','class'=>'regular-checkbox big-checkbox'));
			
			echo '</div><div style="clear:both;">';
			echo $this->form->input('activities',array('type'=>'checkbox','label'=>'Include activities by subject','class'=>'regular-checkbox big-checkbox'));
			
			echo '</div><div style="clear:both;">';
			echo '<p style="font-size:90%;">Include a covering comment<br /><i>(for batch email reports this is added to all)</i></p>';
			echo $this->form->input('comment',array('type'=>'textarea','label'=>false));
			
			echo '</div><div style="clear:both;">';
			echo $this->form->end('Next');
			echo '</div>';
		}
	?>
	
</div>