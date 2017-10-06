<?php
namespace Home\Controller;
use Think\Controller;
class Base extends Controller{
    public function _initialize(){
    	/*if (empty(session('name'))) {
	      $this->redirect('user/login');
	    }*/
    }
}