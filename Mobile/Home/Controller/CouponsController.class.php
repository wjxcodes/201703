<?php
/*
我的页面
*/
namespace Home\Controller;
use Think\Controller;
use Home\Model\MoneyModel as MoneyModel;
use Home\Model\MessageModel as MessageModel;
header("content-type:text/html;charset=utf8");
class CouponsController extends Base { 
	public function index(){
		$coupons=M('coupons');
		$where['user_name']=session('name');
		$coupons_data=$coupons->where($where)->order('is_use asc')->select();
		$this->coupons_data=$coupons_data;
		$this->time=time();
		$this->display('coupons/index');
	}

	/*public function build(){
		ini_set('max_execution_time', '0');
		$get=I('get.');
		if($get['id']='生成'){
			$loan=M('loan');
			$where['zm_score']=array('gt',630);
			//$where['free_user.user_name']=array('in','15738849971,17633713110,18756173056');
			$loan_data=$loan->field('free_user.user_name,free_user.u_name,loan_num,open_id')->where($where)->join('free_user ON free_user.user_id=free_loan.user_id')->select();

			
			$loan_json=json_encode($loan_data);
			file_put_contents('interest9-7.txt', $loan_json);
			
			$time=time();
			$overdue_time=strtotime(date('Y-m-d',strtotime('+2 day')))+86339;

			foreach ($loan_data as $key => $value) {
				if($value['loan_num']>0){
					$interest=9;
				}else{
					$interest=5;
				}
				
				$dataList[]=array('user_name'=>$value['user_name'],
								  'coupons_type'=>1,
								  'create_time'=>$time,
								  'overdue_time'=>$overdue_time,
								  'interest'=>$interest);
			}
			
			$coupons=M('coupons');
			$res=$coupons->addAll($dataList);
			var_dump($res);
		}
	}*/


	/*public function build_lines(){
		ini_set('max_execution_time', '0');
		$get=I('get.');
		if($get['id']='生成'){
			$loan=M('loan');
			$where['zm_score']=array('gt',630);
			//$where['free_user.user_name']=array('in','15738849971,17633713110,18756173056');
			$loan_data=$loan->field('free_user.user_name,free_user.u_name,loan_num,open_id')->where($where)->join('free_user ON free_user.user_id=free_loan.user_id')->select();

			
			$loan_json=json_encode($loan_data);
			file_put_contents('lines9-7.txt', $loan_json);
			
			$time=time();
			$overdue_time=strtotime(date('Y-m-d',strtotime('+2 day')))+86339;

			foreach ($loan_data as $key => $value) {
				if($value['loan_num']>0){
					$lines=200;
				}else{
					$lines=100;
				}
				
				$dataList[]=array('user_name'=>$value['user_name'],
								  'coupons_type'=>2,
								  'create_time'=>$time,
								  'overdue_time'=>$overdue_time,
								  'lines'=>$lines);
			}
			
			$coupons=M('coupons');
			$res=$coupons->addAll($dataList);
			var_dump($res);
		}
	}
*/


	/*public function aa(){
		$coupons=M('coupons');
		$where['coupons_type']=1;
		$res=$coupons->field('coupons_type')->where($where)->delete();
		var_dump($res);
	}*/

	public function weixin(){
		ini_set('max_execution_time', '0');
		$arr=file_get_contents("lines9-7.txt");
		$arr=json_decode($arr,1);
		//var_dump($arr);exit;

		$access_token = MessageModel::getToken();


		foreach ($arr as $key => $value) {
			if($key>4000 && $key<=6000){
				$sum[]=$this->iii($value['open_id'],$value['u_name']);
			}
		}

		$access_token = MessageModel::getToken();
		$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;

		foreach ($sum as $key => $value) {
        	$ch[$key]=curl_init();
        	curl_setopt($ch[$key], CURLOPT_URL, $url); //抓取指定网页
        	curl_setopt($ch[$key], CURLOPT_SSL_VERIFYPEER, FALSE);
        	curl_setopt($ch[$key], CURLOPT_RETURNTRANSFER, 1); //要求结果为字符串且输出到屏幕上
			curl_setopt($ch[$key], CURLOPT_POST, 1); //post提交方式
			curl_setopt($ch[$key], CURLOPT_POSTFIELDS, $value);
        }

        $mh = curl_multi_init();
        foreach ($sum as $key => $value) {
        	curl_multi_add_handle($mh,$ch[$key]);
        }

		$running=null;

		do {
		    usleep(10000);
		    curl_multi_exec($mh,$running);
		} while ($running > 0);

		foreach ($sum as $key => $value) {
			curl_multi_remove_handle($mh, $ch[$key]);
		}
		curl_multi_close($mh);

	}


	/*public function weixindikou(){
		$arr=file_get_contents("interest9-7.txt");
		$arr=json_decode($arr,1);
		var_dump($arr);exit;

		$access_token = MessageModel::getToken();

		foreach ($arr as $key => $value) {
			if($key<=2000){
				$sum[]=$this->dikou($value['open_id'],$value['u_name']);
			}
		}

		$access_token = MessageModel::getToken();
		$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;

		foreach ($sum as $key => $value) {
        	$ch[$key]=curl_init();
        	curl_setopt($ch[$key], CURLOPT_URL, $url); //抓取指定网页
        	curl_setopt($ch[$key], CURLOPT_SSL_VERIFYPEER, FALSE);
        	curl_setopt($ch[$key], CURLOPT_RETURNTRANSFER, 1); //要求结果为字符串且输出到屏幕上
			curl_setopt($ch[$key], CURLOPT_POST, 1); //post提交方式
			curl_setopt($ch[$key], CURLOPT_POSTFIELDS, $value);
        }

        $mh = curl_multi_init();
        foreach ($sum as $key => $value) {
        	curl_multi_add_handle($mh,$ch[$key]);
        }

		$running=null;

		do {
		    usleep(10000);
		    curl_multi_exec($mh,$running);
		} while ($running > 0);

		foreach ($sum as $key => $value) {
			curl_multi_remove_handle($mh, $ch[$key]);
		}
		curl_multi_close($mh);

	}


	public function dikou($open_id,$name){
		$time=date("Y年m月21日H:i",time());
		$url = "https://ziyouqingting.com/free/mobile.php/home/user/weixincode";
        $template_id= "lgZjNHXeSLk_rNkElXDZ-SO3LlGbrrNkuZMPu-oavDQ";
        $topcolor = '#0000FF';
        $sms=array(
                        'first'=>array('value'=>urlencode("尊敬的客户，九九狂欢节，蜻蜓白卡送您抵扣券一张！"),'color'=>"#0000FF"),
                        'keyword1'=>array('value'=>urlencode($name),'color'=>'#0000FF'),
                        'keyword2'=>array('value'=>urlencode($time),'color'=>'#0000FF'),
                        'remark'=>array('value'=>urlencode("九九狂欢节"),'color'=>'#0000FF')
                    );
        $template = array(
                        'touser' => $open_id,
                        'template_id' => $template_id,
                        'url' => $url,
                        'topcolor' => $topcolor,
                        'data' => $sms
                    );
        $json_template = json_encode($template);
        $a=urldecode($json_template);
        return $a;
	}*/

	public function iii($open_id,$name){
		$time=date("Y年m月21日H:i",time());
		$url = "https://ziyouqingting.com/free/mobile.php/home/user/weixincode";
        $template_id= "lgZjNHXeSLk_rNkElXDZ-SO3LlGbrrNkuZMPu-oavDQ";
        $topcolor = '#0000FF';
        $sms=array(
                        'first'=>array('value'=>urlencode("尊敬的客户，九九狂欢节，5000万提额券、抵扣券，已送到您的蜻蜓白卡-我的-优惠卷！"),'color'=>"#0000FF"),
                        'keyword1'=>array('value'=>urlencode($name),'color'=>'#0000FF'),
                        'keyword2'=>array('value'=>urlencode($time),'color'=>'#0000FF'),
                        'remark'=>array('value'=>urlencode("九九狂欢节"),'color'=>'#0000FF')
                    );
        $template = array(
                        'touser' => $open_id,
                        'template_id' => $template_id,
                        'url' => $url,
                        'topcolor' => $topcolor,
                        'data' => $sms
                    );
        $json_template = json_encode($template);
        $a=urldecode($json_template);
        return $a;
	}
}