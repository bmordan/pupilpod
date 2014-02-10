<script>$(document).ready(function($) {$('html').addClass('activityblur');});</script>

<div class="formsection" style="background:#000;">
<?php 
	echo $this->form->create('activitys');
	echo $accordion;
	echo $this->form->end('Show above');
?>
</div>

<script>
  $(function() {
	$(".icons-home").hide()
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