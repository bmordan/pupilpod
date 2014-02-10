<?php
class UsersController extends AppController{
	public function index($date = null){
		if(empty($date)){$today = date('Y-m-d',time());}else{$today = date('Y-m-d',strtotime($date));}
		$this->set('today',$today);
	}
}