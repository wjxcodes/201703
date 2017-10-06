<?php
namespace Home\Controller;
use Think\Controller;
header("content-type:text/html;charset=utf8");
class BaseController extends Controller{
	function _initialize(){
 	  	if(session('aname')){
 	  		$base_auth=M('auth');
 	  		$base_where['username']=session('aname');
 	  		$base_data=$base_auth->field('auth')->where($base_where)->find();
 	  		if(!$base_data){
 	  			$this->redirect('Home/Index/index');
 	  		}
 	  		$base_auth=explode('-', $base_data['auth']);
 	  		if(!in_array(CONTROLLER_NAME, $base_auth)){
 	  			$this->redirect('Home/Index/index',array(),2,'权限不足');
 	  			
 	  		}
 	  		$this->base_auth=$base_auth;
 	  	}else{
 	  		$this->redirect('Home/Index/index');
 	  	}
 	  	
	}
}