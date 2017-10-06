<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\SesameModel as SesameModel;
class IndexController extends Controller {
    public function index(){
        $this->show('<a href="__ROOT__/mobile.php/home/user/login">进入</a>');
    }
}