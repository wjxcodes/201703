<?php
/*
*管理员权限
*/ 
namespace Home\Controller;
use Think\Controller;
header("content-type:text/html;charset=utf8");
class ManagerController extends BaseController{
    // 管理员列表
    public function index(){
        $auth_model = M('auth');
        $count = $auth_model->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
        $auth_data = $auth_model->order('id')->limit($Page->firstRow.','.$Page->listRows)->select();
        $show = $Page->show();// 分页显示输出

        foreach ($auth_data as $key => &$value) {
            $value['new_auth']=explode('-',$value['auth']);
        }
        $auth_limit=M('auth_limit');
        $auth_limit_data=$auth_limit->select();

        $this->assign('auth_limit_data',$auth_limit_data);
        $this->assign('page',$show);// 赋值分页输出
        $this->assign('auth_data',$auth_data);
        $this->display();
    }

    //添加控制器

    public function addauth(){
        if($_POST){
            $post=I('post.');
            $auth_limit=M('auth_limit');
            $save['module']=$post['module'];
            $save['controller']=$post['controller'];
            $save['create_time']=time();
            $res=$auth_limit->add($save);
            if($res){
                $this->redirect('Home/Manager/index');
            }else{
                die("<script>alert('网络原因添加失败,请稍后再试！！！');history.back();</script>");
            }
        }
        $this->display();
    }

	//添加管理员

    public function add(){
        $auth_limit=M('auth_limit');
        $auth_limit_data=$auth_limit->select();

        if($_POST){
            $post=I('post.');
            foreach ($post as $key => $value) {
                $str=substr($key,0,6);

                if($str=='module'){
                    $module.=$value."-";
                }
            }
            $module=rtrim($module,'-');

            $auth=M('auth');
            $save['authname']=$post['authname'];
            $save['username']=$post['username'];

            $salf=$this->getRandChar(5);
            $save['salf']=$salf;
            $save['password']=md5($post['password'].$salf);
            $save['auth']=$module;
            $save['create_time']=time();
            $auth_res=$auth->add($save);
            if($auth_res){
                $this->redirect('Home/Manager/index');
            }else{
                die("<script>alert('网络原因添加失败,请稍后再试！！！');history.back();</script>");
            }
        }
        
        $this->limit_data=$auth_limit_data;
        $this->display();
    }

    public function update(){
        if($_GET){
            $get=I('get.');
            $auth=M('auth');
            $where['id']=$get['id'];
            $auth_data=$auth->where($where)->find();
            $auth_data['auth']=explode("-",$auth_data['auth']);
        }

        $auth_limit=M('auth_limit');
        $auth_limit_data=$auth_limit->select();

        if($_POST){
            $post=I('post.');
            if($get['id']==''){
                $this->redirect('Home/Manager/index');
            }
            $auth_where['id']=$get['id'];
            foreach ($post as $key => $value) {
                $str=substr($key,0,6);

                if($str=='module'){
                    $module.=$value."-";
                }
            }
            $module=rtrim($module,'-');
            
            if($post['password']!=''){
                $salf=$this->getRandChar(5);
                $save['salf']=$salf;
                $save['password']=md5($post['password'].$salf);
            }
            

            $save['auth']=$module;
            $save['update_time']=time();

            $res=$auth->where($where)->save($save);
            if($res){
                $this->redirect('Home/Manager/index');
            }else{
                die("<script>alert('网络原因添加失败,请稍后再试！！！');history.back();</script>");
            }
        }
        $this->get_id=$get['id'];
        $this->auth_limit_data=$auth_limit_data;
        $this->auth_data=$auth_data;
        $this->display();
    }

    //产生一个随机字符串
    public function getRandChar($length){
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol)-1;

        for($i=0;$i<$length;$i++){
            $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }
        return $str;
    }
}