<script>$(document).ready(function($) {$('html').addClass('skybeamsblur');});</script>

<div class="box"><!-- Pupil -->

	
		<?php echo '<h2>'.$this->Html->image('icons/user.png').' '.$data[0][0]['name'].'</h2>';?>
		<br /><br />
		<?php 
			echo $this->Html->image('icons/edit.png',array('url'=>array('action'=>'edit',$data[0][0]['id'])));
			echo $this->Html->image('icons/delete.png',array('url'=>array('action'=>'delete',$data[0][0]['id'])),array('escape'=>false,'confirm'=>'Hold on are you sure this can not be undone?'));
		?>	
</div>
<div class="box">
	
		<?php
		foreach($data[1] as $pupil=>$details){
			echo '<table class="keyvalue">';
			foreach($details as $k=>$v){
				echo '<tr><td class="key">'.$k.' |</td><td class="value">'.$v.'</td></tr>';
			}
			echo '</table>';
		}
		?>
		
</div>


<!-- sen -->
	<?php
		if(!empty($data[2])){
			echo '<div class="formsection"><ul><li><strong>Notes</strong></li>';
			foreach($data[2] as $k=>$v){
				echo '<li>'.$v.'</li>';
			}
			echo '</ul>';
			echo $this->Html->image('icons/edit.png',array('url'=>array('controller'=>'options','action'=>'senEdit',$data[0][0]['id'])));
			echo '</div>';
		}
	?>

<!-- School -->

	<div class="box">
		<?php echo '<h2>'.$this->Html->image('icons/home-outline.png').' '.$data[3][0]['name'].'</h2>';?>
	</div>
	<div class="box">
		<ul>
			<?php
				foreach($data[3][0]['address'] as $v){
					if(!empty($v)){echo '<li>'.$v.'</li>';}
				}
			?>
		</ul>
	</div>

<!-- Contacts -->
<div class="box">
	<div class="">
		<?php echo '<h2>'.$this->Html->image('icons/contacts.png').' Contacts</h2>';?>
	</div>
	<div class="">
		<?php
			if(!empty($data[4])){
				
				foreach($data[4] as $n => $l){
					
					echo '<fieldset><legend>'.$l['first_name'].' '.$l['last_name'].' | '.$l['role'].'</legend>';
					if(!empty($l['phone'])){echo '<li>'.$l['phone'].'</li>';}
					if($l['report'] == '1'){echo '<li>'.$l['email'].' <strong>[Auto Reporting On]</strong></li>';}
					else{if(!empty($l['email'])){echo '<li>'.$l['email'].'</li>';}}
					
					
					echo '<li style="text-align: right;">';
					echo $this->Html->image('icons/edit.png',array('url'=>array('controller'=>'Contacts','action'=>'edit',$l['id'],$l['pupil_id'])));
										
										
					echo $this->Html->image('icons/delete.png',array('url'=>array('controller'=>'Contacts','action'=>'delete',$l['id'],$l['pupil_id']),'escape'=>false,'confirm'=>'Hold on are you sure this can not be undone?'));
					echo '</li>';
					
					echo '</fieldset>';
				}
				
			}else{
				echo '<label>No Contacts</label>';
			}
		?>
		<?php echo '<li>Add Contact '.$this->Html->image('icons/plus.png',array('class'=>'icons-list-img','url'=>array('controller'=>'contacts','action'=>'add',$data[0][0]['id']))).'</li>';?>
	</div>
</div>

<!-- Attendance -->
<div class="box">
	<div class="">
		<?php
			echo '<h2>'.$this->Html->image('icons/th-list.png',array('url'=>array('controller'=>'Attendances','action'=>'pupil',$data[0][0]['id']))).' Attendance</h2>';
			echo $attendance;
		?>
		
		
	<?php echo '<li>Add Attendance '.$this->Html->image('icons/plus.png',array('class'=>'icons-list-img','url'=>array('controller'=>'attendances','action'=>'edit'))).'</li>';?>

	<?php echo '<li>View Attendance '.$this->Html->image('icons/th-list.png',array('class'=>'icons-list-img','url'=>array('controller'=>'attendances','action'=>'pupil',$data[0][0]['id']))).'</li>';?>
	
	</div>
</div>

<!-- Targets -->
<div class="box">
	<div class="">
		<?php echo '<h2>'.$this->Html->image('icons/star-outline.png').' Targets</h2>';?>
		<?php foreach($targets as $n => $arr){
			foreach($arr as $subject => $t){
				echo '<fieldset><legend>'.ClassRegistry::init('Option')->getOption($subject).'</legend>';
				foreach($t as $k=>$v){
					echo '<p class="target-list-display">'.$this->Html->link($v['targets']['target'],array('controller'=>'targets','action'=>'edit',$v['targets']['id'])).' | set by '.ClassRegistry::init('Staff')->getStaff($v['targets']['staff_id']).'</p>';
				}
				echo '</fieldset>';
			}
		}?>
	<?php echo '<li>Add Target '.$this->Html->image('icons/plus.png',array('class'=>'icons-list-img','url'=>array('controller'=>'targets','action'=>'add',31,$data[0][0]['id']))).'</li>';?>	
	</div>
</div>

<!-- Reports -->

	<div class="box">
		<?php echo '<h2>'.$this->Html->image('icons/document-text.png').' Reports</h2>';?>
		<li>
			<?php
				echo $this->Html->link('View Report',array('controller'=>'Outcomes','action'=>'view',$data[0][0]['id']));
			?>
		</li>
		<?php echo '<li>'.$this->html->link('Add Final Summary ',array('controller'=>'notes','action'=>'comment',31,$data[0][0]['id'])).$this->Html->image('icons/plus.png',array('class'=>'icons-list-img','url'=>array('controller'=>'notes','action'=>'comment',31,$data[0][0]['id']))).'</li>';?>
	</div>

<!-- Teachers Notes -->

	<div class="box">
		<?php echo '<h2>'.$this->Html->image('icons/lock-closed.png').' Teachers notes</h2>';?>
		<li>
			<?php
				echo $this->Html->link('View Teachers notes',array('controller'=>'notes','action'=>'view',31,$data[0][0]['id']));
			?>
		</li>
		<?php echo '<li>Add Teachers note '.$this->Html->image('icons/plus.png',array('class'=>'icons-list-img','url'=>array('controller'=>'notes','action'=>'add',31,$data[0][0]['id']))).'</li>';?>
	</div>


<div style="clear:both;width:100%;">
<?php # echo '<pre>';echo print_r($data);echo '<pre>';?>
</div>