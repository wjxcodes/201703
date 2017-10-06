<?php
/*
查看界面
*/
namespace Home\Controller;
use Think\Controller;
use Home\Model\LLModel as LLModel;
use lian\llpay_submit;
use lian\llpay_notify;
use lian\llpay_cls_json;
use Home\Model\RepayModel as RepayModel;
use Home\Model\MessageModel as MessageModel;
header("content-type:text/html;charset=utf8");
class LookController extends Base { 
	/*public function jiangxi(){
		ini_set('max_execution_time', '0');
		$loan=M('loan');
		$where['is_loan']=1;
		$where['card_type']=array('NEQ',1);
		$loan_data=$loan->field('open_id,free_user.u_name')->where($where)->join('free_user ON free_user.user_id=free_loan.user_id')->select();



		foreach ($loan_data as $key => $value) {
			$access_token = MessageModel::getToken();
	        $a=MessageModel::wangdong($value['open_id'],$value['u_name'],$access_token);
	        var_dump($a);
		}
		
	}*/

	public function test1(){
		/*$a=S('appid', '1', 3600);
		var_dump($a);exit;*/
		$access_token = MessageModel::getToken();
		var_dump($access_token);
		var_dump(S('appid'));
	}
	public function wangdong(){
		$access_token = MessageModel::getToken();
		$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;
		$open_id="odLcP01FQbtm_rgdmm3qN0LtqXng";
		$u_name="王栋";


        $a=$this->iii($open_id,$u_name);
        //var_dump($a);
        $open_id1="odLcP08wZE_xOEZSaKz-5yMSYjyc";
        $u_name1="闻世坤";
        $a1=$this->iii($open_id1,$u_name1);
        
        $sum[]=$a;
        $sum[]=$a1;
        foreach ($sum as $key => $value) {
        	//var_dump($value);
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
		    var_dump(curl_multi_getcontent($mh));
		} while ($running > 0);

		foreach ($sum as $key => $value) {
			curl_multi_remove_handle($mh, $ch[$key]);
		}
		curl_multi_close($mh);
	}



	public function iii($open_id,$name){

		$url = "https://ziyouqingting.com/free/mobile.php/home/user/weixincode";
        $template_id= "k9nn40Tpg_X473IgyLh1-wTprwqbFQoNvxsyQ8g3xPI";
        $topcolor = '#0000FF';
        $sms=array(
                        'first'=>array('value'=>urlencode("尊敬的客户"),'color'=>"#0000FF"),
                        'keyword1'=>array('value'=>urlencode($name),'color'=>'#0000FF'),
                        'keyword2'=>array('value'=>urlencode("费用下调通知"),'color'=>'#0000FF'),
                        'remark'=>array('value'=>urlencode("为了感谢您长期以来的支持与厚爱，您借款所需的服务费用已【永久下调】，请您按时还款，保持良好的信用，您将有机会获得尊享黑卡机会！谢谢！"),'color'=>'#0000FF')
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



/*	public function is_matched(){
		$access_token = MessageModel::getToken();
		$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;
		
		$arr=file_get_contents("matched");
		$arr=json_decode($arr,1);
		
		  
		foreach ($arr as $key => $value) {
			if($key>1000){
		        $sum[]=$this->iii($value['open_id'],$value['u_name']);
	        }
		}


		foreach ($sum as $key => $value) {
        	var_dump($value);
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




	}*/



	public function test(){
		$loan=M('loan');
		$where['is_loan']=1;
		$loan_data=$loan->where($where)->select();
		//var_dump($loan_data);
		foreach ($loan_data as $key => $value) {
			$str=substr($value['identity'],0,4);
			$id=array('3706');
			$bool=in_array($str,$id);
			if($bool){
				if($value['loan_time']==1){
		           $loan_time=7;
			    }else if($value['loan_time']==2){
		           $loan_time=14;
			    }
				$overdue_show=RepayModel::overdue_show($value['is_pay'],$loan_time,$value['renewal_days'],$value['loan_amount']);
				if($overdue_show['day']>0){
					var_dump($value['user_name']);
					var_dump($overdue_show['day']);
				}
				

			}
		}
	}


	public function chakanj(){
		$arr=file_get_contents("D:/jiangxi/jinka8-10.txt");
		$arr=json_decode($arr,1);
		foreach ($arr as $key => $value) {
			if($value['overdue_type']==1){
			
			}else{
				$data[]=$value['user_name'];
			}
		}
		$loan=M('loan');
		$where['user_name']=array('in',$data);
		$loan_data=$loan->where($where)->select();
		//var_dump($loan_data);

		foreach ($loan_data as $key => $value) {
			if($value['loan_time']==1){
	           $loan_time=7;
		    }else if($value['loan_time']==2){
	           $loan_time=14;
		    }
			$overdue_show=RepayModel::overdue_show($value['is_pay'],$loan_time,$value['renewal_days'],$value['loan_amount']);
			var_dump($overdue_show['day']);
			var_dump(date("Y-m-d H:i:s",$value['is_pay']));
			var_dump(date("Y-m-d H:i:s",$overdue_show['time']));
			var_dump($value['user_name']);
			if($value['renewal_days']!=0){
				$ytime=$overdue_show['time']-$value['renewal_days']*86400;
				var_dump(date("Y-m-d H:i:s",$ytime));
			}

			echo "<br/>";
		}

		echo "<br/>";

		$record=M('record');
		$where1['user_name']=array('in',$data);
		$where1['repayment_time']=array('EGT',"1501171200");
		$record_data=$record->where($where1)->select();
		foreach ($record_data as $key => $value) {
			if($value['loan_time']==1){
	           $loan_time=7;
		    }else if($value['loan_time']==2){
	           $loan_time=14;
		    }
		    $be_overdue=RepayModel::be_overdue($value['pay_time'],$loan_time,$value['xutime'],$value['pay_money'],$value['repayment_time']);

		    var_dump($be_overdue['day']);
		    var_dump(date("Y-m-d H:i:s",$be_overdue['time']));
		    var_dump($value['repayment_money']);
		    var_dump($value['loan_time']);
		    echo "<br/>";
		}
	}

	public function chakany(){

		$arr=file_get_contents("yinka8-10-2.txt");
		$arr=json_decode($arr,1);

		foreach ($arr as $key => $value) {
			if($value['overdue_type']==1){
			
			}else{
				$data[]=$value['user_name'];
			}
		}


		$arr1=file_get_contents("yinka8-10.txt");
		$arr1=json_decode($arr1,1);
		foreach ($arr1 as $key => $value) {
			if($value['overdue_type']==1){
			
			}else{
				$data[]=$value['user_name'];
			}
		}



		$loan=M('loan');
		$where['user_name']=array('in',$data);
		$loan_data=$loan->where($where)->select();
		//var_dump($loan_data);
$time=date("Y-m-d",strtotime('-1 day'));
var_dump($time);
		foreach ($loan_data as $key => $value) {
			if($value['loan_time']==1){
		       $loan_time=7;
		    }else if($value['loan_time']==2){
		       $loan_time=14;
		    }
		    $data[]=$value['user_name'];
			$overdue_show=RepayModel::overdue_show($value['is_pay'],$loan_time,$value['renewal_days'],$value['loan_amount']);
			
			
			$y_time=date("Y-m-d",$overdue_show['time']);
			if($time==$y_time){
var_dump(1);
			}

		}

		echo "<br/>";

		$record=M('record');
		$where1['user_name']=array('in',$data);
		$where1['repayment_time']=array('EGT',"1501171200");
		$record_data=$record->where($where1)->select();
		foreach ($record_data as $key => $value) {
			if($value['loan_time']==1){
		       $loan_time=7;
		    }else if($value['loan_time']==2){
		       $loan_time=14;
		    }
		    $be_overdue=RepayModel::be_overdue($value['pay_time'],$loan_time,$value['xutime'],$value['pay_money'],$value['repayment_time']);

		    
		    $x_time=date("Y-m-d",$be_overdue['time']);

		    if($x_time==$time){
var_dump($be_overdue['day']);

		    }
		
		}
	}
}