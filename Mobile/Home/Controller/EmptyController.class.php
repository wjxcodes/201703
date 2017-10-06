<?php
/*
空页面
*/
namespace Home\Controller;
use Think\Controller;
class EmptyController extends Controller {
    public function index(){
        $this->display('empty/index');
    }
}