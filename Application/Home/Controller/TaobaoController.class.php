<?php 
/*
*淘宝
*/
namespace Home\Controller;
use Think\Controller;
header("content-type:text/html;charset=utf8");
class TaobaoController extends BaseController{
	public function index(){
	$taobao=M('taobao');
     $get=I('get.');
     $where['user_name']=$get['tel'];
     $taobao_data=$taobao->where($where)->find();
    $curl = curl_init();
	curl_setopt_array($curl, array(
	CURLOPT_URL => "http://e.apix.cn/apixanalysis/tb/retrieve/ele_business/taobao/query?query_code=".$taobao_data['token']."",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	CURLOPT_HTTPHEADER => array(
	"accept: application/json",
	"apix-key: 64672249571d47376d435abbe8c3c602",
	"content-type: application/json"
	),
	));
	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
	echo "cURL Error #:" . $err;
	}else{
		$response=json_decode($response,1);
		$accountSafeInfo=$response['accountSafeInfo'];
		$addrs=$response['addrs'];
		$bindAccountInfo=$response['bindAccountInfo'];
		$orderList=$response['orderList'];
		$personalInfo=$response['personalInfo'];

		$this->personalInfo=$personalInfo;
		$this->accountSafeInfo=$accountSafeInfo;
		$this->addrs=$addrs;
		$this->bindAccountInfo=$bindAccountInfo;


		$mytime=(int)date("Ymd", strtotime("-1 year"));
		foreach ($orderList as $key => $value) {
			if($value['orderStatus']=='交易成功'){
                 
                 $arr[$key]['businessDate']=$value['businessDate'];
						$arr[$key]['orderProducts']=$value['orderProducts'];
						$arr[$key]['orderStatus']=$value['orderStatus'];
						$arr[$key]['orderTotalPrice']=$value['orderTotalPrice'];
						$arr[$key]['orderid']=$value['orderid'];

						
				$date=(int)str_replace('-','',$value['businessDate']);
				if($date>=$mytime){
					$lost_money+=$value['orderTotalPrice'];
						
				}

			}
		}
		$this->mytime=$mytime;
		$this->lost_money=$lost_money;
		$this->arr=$arr;
		$this->money=$money;

		$this->display();
	}
}
	public function info(){


         $taobao=M('taobao');
         $get=I('get.');
         $where['user_name']=$get['tel'];
         $user=M('user');
         $user_data=$user->field('u_name')->where($where)->find();
         $this->user_data=$user_data;
         $taobao_data=$taobao->where($where)->find();
          if($taobao_data['token']){
            $taobao_info=M('taobao_info');
            $taobao_info_data=$taobao_info->where($where)->find();
	            if($taobao_info_data){
		            $accountSafeInfo=json_decode($taobao_info_data['accountsafeinfo'],1);
					$addrs=json_decode($taobao_info_data['addrs'],1);
					$bindAccountInfo=json_decode($taobao_info_data['bindaccountinfo'],1);
					$personalInfo=json_decode($taobao_info_data['personalinfo'],1);
					$orderlist=json_decode($taobao_info_data['orderlist'],1);

                    $this->mytime=$taobao_info_data['create_time'];
                    $this->arr=$orderlist;
                    $this->lost_money=$taobao_info_data['money'];
		            $this->personalInfo=$personalInfo;
					$this->accountSafeInfo=$accountSafeInfo;
					$this->addrs=$addrs;
					$this->bindAccountInfo=$bindAccountInfo;
					$hint="从数据库读出信息";
	            }else{
                 		$curl = curl_init();
						curl_setopt_array($curl, array(
						CURLOPT_URL => "http://e.apix.cn/apixanalysis/tb/retrieve/ele_business/taobao/query?query_code=".$taobao_data['token']."",
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_ENCODING => "",
						CURLOPT_MAXREDIRS => 10,
						CURLOPT_TIMEOUT => 30,
						CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
						CURLOPT_CUSTOMREQUEST => "GET",
						CURLOPT_HTTPHEADER => array(
						"accept: application/json",
						"apix-key: 64672249571d47376d435abbe8c3c602",
						"content-type: application/json"
						),
						));
						$response = curl_exec($curl);
						$err = curl_error($curl);

						curl_close($curl);

						if ($err) {
						echo "cURL Error #:" . $err;
						}else{
							$response=json_decode($response,1);
							$accountSafeInfo=$response['accountSafeInfo'];
							$addrs=$response['addrs'];
							$bindAccountInfo=$response['bindAccountInfo'];
							$orderList=$response['orderList'];
							$personalInfo=$response['personalInfo'];

							$this->personalInfo=$personalInfo;
							$this->accountSafeInfo=$accountSafeInfo;
							$this->addrs=$addrs;
							$this->bindAccountInfo=$bindAccountInfo;


							$mytime=(int)date("Ymd", strtotime("-1 year"));
							foreach ($orderList as $key => $value) {
								if($value['orderStatus']=='交易成功'){
                                     
									$date=(int)str_replace('-','',$value['businessDate']);
									if($date>=$mytime){
										$lost_money+=$value['orderTotalPrice'];
										$i+=1;
										    if($i<=30){
												$arr[$key]['businessDate']=$value['businessDate'];
												$arr[$key]['orderProducts']=$value['orderProducts'];
												$arr[$key]['orderStatus']=$value['orderStatus'];
												$arr[$key]['orderTotalPrice']=$value['orderTotalPrice'];
												$arr[$key]['orderid']=$value['orderid'];
										    }
									}

								}
							}
							$this->mytime=$mytime;
							$this->lost_money=$lost_money;
							$this->arr=$arr;
							$this->money=$money;
							   $save['accountSafeInfo']=json_encode($accountSafeInfo);
							   $save['bindAccountInfo']=json_encode($bindAccountInfo);
							   $save['personalInfo']=json_encode($personalInfo);
							   $save['addrs']=json_encode($addrs);
							   $save['orderList']=json_encode($arr);
							   $save['user_name']=$get['tel'];
							   $save['create_time']=json_encode($mytime);
							   $save['money']=$lost_money;

							$res=$taobao_info->add($save);
							if($res){
                                 $hint="数据储存成功";
							}else{
								 $hint="数据储存失败";
							}
						}
                }
             $this->hint=$hint;
          }else{
               die("<script>alert('还未获取到信息！');history.back();</script>");
          }



	    $this->display();
	}
}