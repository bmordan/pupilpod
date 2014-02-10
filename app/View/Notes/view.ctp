<script>$(document).ready(function($) {$('html').addClass('atriumblur');});</script>
<div class="formsection">
	<table>
	<?php 
		foreach($notes as $k=>$v){
			echo '<tr>';
				echo '<td>'.date('d M Y',strtotime($v['Note']['date'])).'</td>';
				if(!empty($v['Note']['pupil_id'])){
					echo '<td><u>'.$this->html->link(ClassRegistry::init('Pupil')->getName($v['Note']['pupil_id']),array('controller'=>'pupils','action'=>'view',$v['Note']['pupil_id'])).'</u></td>';
				}else{
					echo '<td></td>';
				}
				echo '<td>'.$v['Note']['note'].'</td>';
				echo '<td>'.$this->html->image('icons/edit.png',array('url'=>array('action'=>'edit',$v['Note']['id']))).'</td>';
				echo '<td>'.$this->html->image('icons/delete.png',array('url'=>array('action'=>'delete',$v['Note']['id']))).'</td>';
			echo '</tr>';
		}
	?>
	</table>
	<?php echo $this->html->image('icons/plus.png',array('url'=>array('action'=>'add',$staff_id))).'<p style="display:inline;"> Add a note</p>';?>
	<?php 
	if(isset($pupil_id)){
		echo '<p style="display:inline;float:right;">Back to pupil view '.$this->html->image('icons/user.png',array('url'=>array('controller'=>'pupils','action'=>'view',$pupil_id))).'</p>';
	}
	?>
</div>