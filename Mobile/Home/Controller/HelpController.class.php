<?php
/*
帮助页面
*/
namespace Home\Controller;
use Think\Controller;
header("content-type:text/html;charset=utf8");
class HelpController extends Base { 
	public function index(){

        $this->display('help/index');
    }

    public function help_1(){
        
        $this->display('help/help_1');

    }
    public function help_2(){
        
    	$this->display('help/help_2');

    }

    public function help_3(){
        
        $this->display('help/help_3');

    }
    public function help_4(){
        
        $this->display('help/help_4');

    }
    public function help_5(){
        
        $this->display('help/help_5');
        
    }
}