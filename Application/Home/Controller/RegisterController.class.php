<?php
//已注册
namespace Home\Controller;
use Think\Controller;
use Home\Model\WeixinModel as WeixinModel;
header("content-type:text/html;charset=utf8");
class RegisterController extends BaseController{
	public function index(){
		$user_model = M('User');
        $start = strtotime($_POST['start']);
        $end = strtotime($_POST['end']);
        if ($start && $end) {
            $map['create_time'] = array(array('egt', $start), array('elt', $end + 86400));
            $count = $user_model->where($map)->count(); // 查询满足要求的总记录数
        } else {
            $count = $user_model->count(); // 查询满足要求的总记录数
            $Page = new \Think\Page($count, 20); // 实例化分页类 传入总记录数和每页显示的记录数
            $data = $user_model->order('user_id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
            $show = $Page->show(); // 分页显示输出
        }
        $this->assign('page', $show); // 赋值分页输出
        $this->assign('data', $data);
        $this->assign('count', $count);
        $this->display();
	}
	public function imperfect(){
		$user_model = M('User');
		$map['message'] = 0;
        $count = $user_model->where($map)->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数
		$data = $user_model->order('user_id desc')->where($map)->limit($Page->firstRow.','.$Page->listRows)->select();
        $show = $Page->show();// 分页显示输出
        $this->assign('page',$show);// 赋值分页输出
		$this->assign('data',$data);
		$this->assign('count',$count);
		$this->display();
	}

    public function count(){
        $credit_model = M('Credit');
        $post = I('post.');
        $start_time=strtotime($post['start']);
        $end_time=strtotime($post['end']);
        $end_time=$end_time+86400;
        $where['create_time'] = array(array('EGT',$start_time),array('ELT',$end_time),'and');
        $credit_data = $credit_model->where($where)->order('credit_id asc')->select();
        $count = array();
        foreach ($credit_data as $k => $v) {
            $bool = in_array($v['cuser_name'],$count);
            if ($v['zm_score']>0 && !$bool) {
                $count[$k] = $v['cuser_name'];
                $arr[$k]['cuser_name'] = $v['cuser_name'];
                $arr[$k]['cu_name'] = $v['cu_name'];
                $arr[$k]['create_time'] = date('Y-m-d H:i:s',$v['create_time']);
                $arr[$k]['zm_score'] = $v['zm_score'];
            }
        }
        $this->goods_export($arr);
    }

    public function goods_export($arr){
        $goods_list = array_merge($arr);
        $data = array();
        foreach ($goods_list as $k=>$goods_info){
            $data[$k][cuser_name] = " $goods_info[cuser_name]";
            $data[$k][cu_name] = " $goods_info[cu_name]";
            $data[$k][create_time] = " $goods_info[create_time]";
            $data[$k][zm_score] = " $goods_info[zm_score]";
            
        }
        foreach ($data as $field=>$v){
            if($field == 'cuser_name'){
                $headArr[]='手机号';
            }
            if($field == 'cu_name'){
                $headArr[]='用户名';
            }
            if($field == 'create_time'){
                $headArr[]='时间';
            } 
            if($field == 'zm_score'){
                $headArr[]='芝麻分';
            }
        }
        $filename="流量表".date('Y_m_d',time());
        WeixinModel::getExcel($filename,$headArr,$data);
    }
}