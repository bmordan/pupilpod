<script>$(document).ready(function($) {$('html').addClass('activityblur');});</script>

<div class="forsection">
<?php 
	echo $this->form->create('dactivitys');
	echo $accordion;
	echo $this->form->end('Select above');
	echo '<li id="add-activity-icon">'.$this->Html->image('icons/globe-add.png',array('url'=>array('controller'=>'activitys','action'=>'add',$staff_id),'title'=>'add to activities','class'=>'icons-list-img')).$this->Html->link('add an activity',array('controller'=>'activitys','action'=>'add')).'</li>';
?>
</div>


<script>
  $(function() {
	var areas = <?php if($index[0] == '-'){echo 'false';}else{echo $index[0];}?>;
    var subjects = <?php if($index[1] == '-'){echo 'false';}else{echo $index[1];}?>;
    var activities = <?php if($index[2] == '-'){echo 'false';}else{echo $index[2];}?>; 
    var settings = {heightStyle: "content",collapsible: true,active: false};
   
    $( "#areas" ).accordion(settings);
	$( ".subjects" ).accordion(settings);
	$( ".activities" ).accordion(settings);
	$( "#areas" ).accordion("option","active",areas);
	$( ".subjects" ).accordion("option","active",subjects);
	$( ".activities" ).accordion("option","active",activities);

  });
 </script>