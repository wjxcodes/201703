<?php
/*
*已放款
*/
namespace Home\Controller;
use Think\Controller;
header("content-type:text/html;charset=utf8");
class AnrongController extends BaseController{

	public function index(){
		$get=I('get.');
		$user_name=$get['id'];
		$anrong_data=D('anrong')->find_username($user_name);
//var_dump($anrong_data);exit;
		if(!$anrong_data){
			$user_data=D('user')->find_username($user_name);
			$loan_data=D('loan')->find_username($user_name);

			if($loan_data['is_loan']!=1){
				die("<script>alert('该用户没有在借款中！');history.back();</script>");
			}
//请求安融的数据
			$errors=0;
			do{
				$anrong_data=D('anrong','Logic')->curl($user_data,$loan_data,$errors);
				var_dump($anrong_data);exit;
				$anrong_data=json_decode($anrong_data,1);
				if($anrong_data['errors']){
					$i+=1;
					$errors=$anrong_data['errors'][0]['errorCode'];
				}else{
					$i=5;
				}
	
			} while ($anrong_data['errors'] && $i<2);

			$add_res=D('anrong')->add_all($user_name,$anrong_data);
			if(!$add_res){
				die("<script>alert('插入数据库出错！');history.back();</script>");
			}
		}
		
        $this->anrong_data=$anrong_data;
        $this->display();
	}

	public function todoquery(){
		$anrong_data=D('anrong','Logic')->curlquery();
		var_dump($anrong_data);
	}

	public function xinyan(){
		$loan_data=D('loan')->is_loan();
		foreach ($loan_data as $key => &$value) {
			if($value['loan_time']==1){
                   $loan_time=7;
            }else if($value['loan_time']==2){
                 $loan_time=14;
            }

			$overdue_show=D('loan','Logic')->overdue_show($value['is_pay'],$loan_time,$value['renewal_days'],$value['loan_amount']);
			$data[$value['user_name']]=$value;
			if($overdue_show['day']>0){
				$data[$value['user_name']]['day']=$overdue_show['day'];
			}
		}


		$record_data=D('record')->select_all();

		foreach ($record_data as $key => $value) {
			if($value['loan_time']==1){
                   $loan_time=7;
            }else if($value['loan_time']==2){
                 $loan_time=14;
            }
            $be_overdue=D('record','Logic')->be_overdue($value['pay_time'],$loan_time,$value['xutime'],$value['pay_money'],$value['repayment_time']);
            //var_dump($be_overdue);
            
            if($data[$value['user_name']]==''){
            	$data[$value['user_name']]=$value;
            }
            if($be_overdue['day']>0){
            	$data[$value['user_name']]['day'].=",".$be_overdue['day'];
            }
            
		}
		//$data=json_encode($data);
		//$data=file_put_contents("D:/xinyan/record.txt", $data);
		var_dump($data);
	}

	public function credit(){
		$arr=file_get_contents("D:/xinyan/record.txt");
		$arr=json_decode($arr,1);

		foreach ($arr as $key => $value) {
			$data[$key]=$value;
			$data[$key]['state']="发生业务关系";
		}
		$credit_data=D('credit')->select_all(1);
		foreach ($credit_data as $key => $value) {
			if($data[$value['user_name']]==''){
				$data[$value['user_name']]=$value;
			}
		}


		$credit_data2=D('credit')->no_matched();
		foreach ($credit_data2 as $key => $value) {
			if($data[$value['user_name']]==''){
				$data[$value['user_name']]=$value;
			}
		}

		$data=json_encode($data);
		$data=file_put_contents("D:/xinyan/xinyan.txt", $data);
		var_dump($data);
	}

	public function get(){
		$arr=file_get_contents("D:/xinyan/xinyan.txt");
		$arr=json_decode($arr,1);
		var_dump($arr);
	}

	public function excel(){
		$arr=file_get_contents("D:/xinyan/xinyan.txt");
		$arr=json_decode($arr,1);
		$goods_list = array_merge($arr);
        $data = array();
        foreach ($goods_list as $k=>$goods_info){
            $data[$k][u_name] = " $goods_info[u_name]";
            $data[$k][user_name] = " $goods_info[user_name]";
            $data[$k][identity] = " $goods_info[identity]";
        }

        foreach ($data as $field=>$v){
            if($field == 'u_name'){
                $headArr[]='姓名';
            }
           if($field == 'user_name'){
                $headArr[]='手机号';
            }
            if($field == 'identity'){
                $headArr[]='身份证号';
            }
        }

        $filename="新颜信息表".date('Y_m_d',time());

        D('excel','Logic')->getExcel($filename,$headArr,$data);
	}
}