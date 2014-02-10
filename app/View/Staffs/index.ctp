<script>$(document).ready(function($) {$('html').addClass('atriumblur');});</script>


	
	<div class="box">
		<h2><?php echo $this->Html->image('icons/home.png',array('class'=>'icons-list-img')).' '.$staffMember[0]['Staff']['name']?></h2>
		<div><ul>
			<li>Area | Office</li>
		</ul></div>
	</div>

	<div class="box">
		<h2>Pupils</h2>
		<div><ul>
			<?php
				echo '<li class="icons-li">'.$this->Html->image('icons/user-add.png',array('url'=>array('controller'=>'pupils','action'=>'add'),'title'=>'Add a Pupil','class'=>'icons-list-img')).$this->Html->link('Add a Pupil',array('controller'=>'pupils','action'=>'add')).'</li>';
				echo '<li class="icons-li">'.$this->Html->image('icons/user-find.png',array('title'=>'find a pupil','class'=>'icons-list-img hide-pupil')).'<a> Find a Pupil</a></li>';
			?>
		</ul></div>
		<div class="ui-widget">
			<input id="pupil"><span id="pid"></span>
		</div>
	</div>

	<div class="box">
		<h2>Targets</h2>
		<div><ul>
			<?php
				echo '<li class="icons-li">'.$this->Html->image('icons/star-outline.png',array('url'=>array('controller'=>'targets','action'=>'add',$staffMember[0]['Staff']['id']),'title'=>'manage targets','class'=>'icons-list-img')).$this->Html->link('Manage Targets',array('controller'=>'targets','action'=>'add',$staffMember[0]['Staff']['id'])).'</li>';
			?>
		</ul></div>
	</div>


	<div class="box">
		<h2>Activities</h2>
		<div><ul>
			<?php
				echo '<li class="icons-li">'.$this->Html->image('icons/globe.png',array('url'=>array('controller'=>'activitys','action'=>'index'),'title'=>'manage activities','class'=>'icons-list-img')).$this->Html->link('Manage Activities',array('controller'=>'activitys','action'=>'index')).'</li>';
			?>
		</ul></div>
	</div>	

	<div class="box">
		<h2>Attendance</h2>
		<div><ul>
			<?php
				
				echo '<li class="icons-li">'.$this->Html->image('icons/th-list.png',array('title'=>'pupil attendance','class'=>'icons-list-img show-att-pupil')).'<a class="show-att-pupil">Pupil Attendance</a></li>';
				
				echo '<div id="hide-att-pupil">
						<div class="ui-widget">
							<input id="att-pupil"><span id="aid"></span>
						</div>
					</div>';
				
				echo '<li class="icons-li">'.$this->Html->image('icons/th-small-outline.png',array('url'=>array('controller'=>'attendances','action'=>'edit'),'title'=>'manage registers','class'=>'icons-list-img')).$this->Html->link('Manage Registers',array('controller'=>'attendances','action'=>'edit')).'</li>';
			?>
		</ul></div>
	</div>
	
	<div class="box">
		<h2>Reports</h2>
		<div><ul>
			<?php
				
				echo '<li class="icons-li">'.$this->Html->image('icons/document-text.png',array('title'=>'pupil reports','class'=>'icons-list-img show-pupil-report')).'<a class="show-pupil-report">View a Pupil\'s Report</a></li>';
				
				echo '<div id="hide-pupil-report">
						<div class="ui-widget">
							<input id="pupil-report"><span id="rid"></span>
						</div>
					</div>';
				
				echo '<li class="icons-li">'.$this->Html->image('icons/group.png',array('url'=>array('controller'=>'attendances','action'=>'autoReporting'),'title'=>'Manage Auto Reporting','class'=>'icons-list-img')).$this->Html->link('Manage Reporting',array('controller'=>'attendances','action'=>'autoReporting')).'</li>';				
				
			?>
		</ul></div>
	</div>
	
	
	<div class="box">
		<h2>Notes</h2>
		<div><ul>
			<?php
				echo '<li class="icons-li">'.$this->Html->image('icons/lock-closed.png',array('url'=>array('controller'=>'notes','action'=>'view',$staffMember[0]['Staff']['id']),'title'=>'teacher notes','class'=>'icons-list-img')).$this->Html->link('Teachers Notes',array('controller'=>'notes','action'=>'view',$staffMember[0]['Staff']['id'])).'</li>';
			?>
		</ul></div>
	</div>
	
	

<script>
  $(function() {
    var pupils = <?php echo $json;?>;
	
		$('#pupil').hide();
		$('.hide-pupil').click(function(){
			$('#pupil').toggle("slow");
		});
	
		$('#pupil').autocomplete({
			minLength: 3,
			source: pupils,
			select: function (event, ui){

					$('#pid').append("<a href='http://10.23.28.5/mis/pupils/view/" + ui.item.value + "'><img src='http://10.23.28.5/mis/img/icons/arrow-forward.png'/></a>");
					$(this).val(ui.item.label);console.log(ui.item.label);
					return false;
				
			},
			response: function(event, ui) {
				if (ui.content.length === 0) {
					$("#pid").replaceWith("<img src='http://10.23.28.5/mis/img/icons/zoom.png'/ id='search'>");
				}
				$('#search').click(function(){
					window.open('http://10.23.28.5/mis/pupils/search/' + $('#pupil').val());
				});
			}
				
		});
		
		$('#hide-att-pupil').hide();
		$('.show-att-pupil').click(function(){
			$('#hide-att-pupil').toggle("slow");
		});
		
		$('#att-pupil').autocomplete({
			source: pupils,
			minLength: 3,
			select: function (event, ui){
				$('#aid').append("<a href='http://10.23.28.5/mis/attendances/pupil/" + ui.item.value + "'><img src='http://10.23.28.5/mis/img/icons/arrow-forward.png'/></a>");
				$(this).val(ui.item.label);
				return false;
			}
		});
		
		$('#hide-pupil-report').hide();
		$('.show-pupil-report').click(function(){
			$('#hide-pupil-report').toggle("slow");
		});
		
		$('#pupil-report').autocomplete({
			source: pupils,
			minLength: 3,
			select: function (event, ui){
				$('#rid').append("<a href='http://10.23.28.5/mis/outcomes/view/" + ui.item.value + "'><img src='http://10.23.28.5/mis/img/icons/arrow-forward.png'/></a>");
				$(this).val(ui.item.label);
				return false;
			}
		});
		

		
  });
  </script>

<?php # echo '<pre>';echo print_r($json);echo '<pre>';?>