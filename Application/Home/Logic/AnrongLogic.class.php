<?php 
/*
*
*/
namespace Home\Logic;
use Think\Controller;
header("content-type:text/html;charset=utf8");
class AnrongLogic extends Controller{
	public function curl($user_data,$loan_data,$error=0){

		$postUrl="	https://p2ptest.creditdata.cn:10000/p2p/remote/mspClientQuery.shtml";

		if($error==122){
			$identity=substr($user_data['identity'],0,2)."0000";
		}else{
			$identity=substr($user_data['identity'],0,6);
		}
		$curlPost=array('member'=>2190,
						'sign'=>'42X8jYvKIChem',
						'customerName'=>$user_data['u_name'],
						'paperNumber'=>$user_data['identity'],
						'loanId'=>$loan_data['loan_order'],
						'loanTypeDesc'=>'消费',
						'applyAssureType'=>'D',
						'applyLoanMoney'=>$loan_data['loan_amount'],
						'applyLoanTimeLimit'=>1,
						'applyDate'=>date("Y-m-d",$loan_data['loan_request']),
						'applyCreditCity'=>$identity);
		
		$ch = curl_init(); //初始化curl
		curl_setopt($ch, CURLOPT_URL, $postUrl); //抓取指定网页
		curl_setopt($ch, CURLOPT_HEADER, 0); //设置header
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //要求结果为字符串且输出到屏幕上
		curl_setopt($ch, CURLOPT_POST, 1); //post提交方式
		curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
		$data = curl_exec($ch); //运行curl
		curl_close($ch);
		return $data;
	}

	
	public function curlquery(){
		$postUrl="https://p2ptest.creditdata.cn:10000/p2p/remote/toDoQuery.shtml";
		$curlPost=array('member'=>2190,
						'sign'=>'42X8jYvKIChem',
						'type'=>'1');
		$data=$this->getcurl($postUrl,$curlPost);
		return $data;
	}

	public function getcurl($postUrl,$curlPost){
		$ch = curl_init(); //初始化curl
		curl_setopt($ch, CURLOPT_URL, $postUrl); //抓取指定网页
		curl_setopt($ch, CURLOPT_HEADER, 0); //设置header
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //要求结果为字符串且输出到屏幕上
		curl_setopt($ch, CURLOPT_POST, 1); //post提交方式
		curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
		$data = curl_exec($ch); //运行curl
		curl_close($ch);
		return $data;
	}
}