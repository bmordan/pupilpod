<div class="formsection">
<?php
	echo $this->form->create('School');
	echo $this->form->input('id',array('type'=>'hidden'));
	echo $this->form->input('name');
	echo $this->form->input('address_1');
	echo $this->form->input('address_3');
	echo $this->form->input('locality');
	echo $this->form->input('city');
	echo $this->form->input('county');
	echo $this->form->input('postcode');
	echo $this->form->input('edubase_number');
	echo $this->form->input('la_name');
	echo $this->form->input('lea_id');
	echo $this->form->input('phone_number');
	echo $this->form->input('phone_area_code');
	echo $this->form->input('school_type');
	echo $this->form->input('url');
	echo $this->form->input('email');
	echo $this->form->input('administrative_ward');
	echo $this->form->end('update');
?>
</div>