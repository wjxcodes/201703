<?php 
/*
*京东
*/
namespace Home\Controller;
use Think\Controller;
use Home\Model\JdModel as JdModel;
header("content-type:text/html;charset=utf8");
class JdController extends BaseController{
	public function index(){
		$jingdong=M('jingdong');
		$where['user_name']=I('get.id');
		$jingdong_data=$jingdong->where($where)->find();
		if($jingdong_data['token']){
			$response=JdModel::jd_data($jingdong_data['token']);
			//var_dump($response);
			foreach ($response['consumeHistroy']['record'] as $key => $value) {
				$all_money+=1;
			}
			$this->all_money=$all_money;
			$this->response=$response;
		}else{
			die("<script>alert('还未获取到信息！');history.back();</script>");
		}
		$this->display();
	}
}