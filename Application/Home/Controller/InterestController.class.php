<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\RepayModel as RepayModel;
use Home\Model\WeixinModel as WeixinModel;
header("content-type:text/html;charset=utf8");
class InterestController extends BaseController {
/*金卡查询*/	
	public function gold(){
		$file=file_get_contents("D:/jiangxi/7-30.txt");
		$file=json_decode($file,1);
		foreach ($file as $key => $value) {
			$user_name[]=$key;
		}
		
		$loan=M('loan');
		$where['free_loan.user_name']=array('in',$user_name);
		$loan_data=$loan->field('free_loan.user_name,lines,loan_lines')->where($where)->join('free_user ON free_user.user_id=free_loan.user_id')->select();

		foreach ($loan_data as $key => $value) {
			if($value['lines']==''){
				$value['lines']=1000;
			}
			$money=$value['lines']+$value['loan_lines'];
			var_dump($money);var_dump($value['user_name']);
		}
	}

	public function index(){
		if($_POST){
			$post=I('post.');
			
			$loan=M('loan');

	

			if($post['loan_num']||$post['loan_num_i']){
				if($post['loan_num']){
					$loan_num=array('EGT',$post['loan_num']);
				}
				if($post['loan_num_i']){
					$loan_num_i=array('lt',$post['loan_num_i']);
				}
				$where['loan_num']=array($loan_num,$loan_num_i);
			}


			if($post['is_loan']==2){

			}else if($post['is_loan']==1){
				$where['is_loan']=1;
			}else if($post['is_loan']==0){
				$where['is_loan']=array('NEQ',1);
			}
			
			if($post['interest_type']==1){
				$where['interest_type']=1;
			}else if($post['interest_type']=='i'){
				$where['interest_type']=array('NEQ',1);
			}
			if($post['zm_score']||$post['zm_score_i']){
				if($post['zm_score']){
					$zm_score=array('EGT',$post['zm_score']);
				}
				if($post['zm_score_i']){
					$zm_score_i=array('lt',$post['zm_score_i']);
				}
				$where['zm_score']=array($zm_score,$zm_score_i);
			}
			if($post['day']){
				$day=(-$post['day']);
			}
			
			if($post['lines']||$post['lines_i']){
				if($post['lines']){
					$fen_lines=$post['lines'];
				}
				if($post['lines_i']){
					$sum_lines=$post['lines_i'];
				}
			}

			switch ($post['card']) {
				case '1':
					$where['card_type']=array('NEQ',2);
					break;
				case '2':
					$where['card_type']=array('NEQ',1);
					break;
				case '3':
					$where['card_type']=0;
					break;
				default:
					# code...
					break;
			}

			$loan_data=$loan->field('free_user.user_name,free_user.identity,u_name,is_pay,zm_score,renewal_days,loan_amount,is_loan,loan_num,lines,loan_lines,interest_type,open_id,card_type')->where($where)->join('free_user ON free_user.user_id=free_loan.user_id')->select();


			foreach ($loan_data as $key => $value) {
				if($value['loan_time']==1){
					$time=7;
				}else if($value['loan_time']=2){
					$time=14;
				}
				$overdue_show=RepayModel::overdue_show($value['is_pay'],$time,$value['renewal_days'],$value['loan_amount']);

				if($overdue_show['day']<1 && $overdue_show['day']>$day){
					if($value['lines']==''){
						$value['lines']=1000;
					}
					$lines=$value['lines']+$value['loan_lines'];
					if($lines>=$fen_lines && $lines<$sum_lines){
						$user_name[]=$value['user_name'];
						$new_data[$key]['lines']=$lines;
						$new_data[$key]['loan_time']=$value['loan_time'];
						$new_data[$key]['identity']=$value['identity'];
						$new_data[$key]['card_type']=$value['card_type'];
						$new_data[$key]['open_id']=$value['open_id'];
						$new_data[$key]['loan_lines']=$value['loan_lines'];
						$new_data[$key]['user_name']=$value['user_name'];
						$new_data[$key]['u_name']=$value['u_name'];
						$new_data[$key]['zm_score']=$value['zm_score'];
						$new_data[$key]['is_loan']=$value['is_loan'];
						$new_data[$key]['loan_num']=$value['loan_num'];
						$new_data[$key]['interest_type']=$value['interest_type'];
						$new_data[$key]['day']=$overdue_show['day'];

						if($value['interest_type']==1){
							$data['interest_minus']+=1;
						}else{
							$data['interest_add']+=1;
						}
					}
					
				}

			}

			$record=M('record');
			if($user_name==''){
				
			}else{
				$record_where['user_name']=array('in',$user_name);
				$record_data=$record->where($record_where)->select();
				foreach ($record_data as $key => $value) {
					if($value['loan_time']==1){
			           $loan_time=7;
				    }else if($value['loan_time']==2){
			           $loan_time=14;
				    }
				    $be_overdue=RepayModel::be_overdue($value['pay_time'],$loan_time,$value['xutime'],$value['pay_money'],$value['repayment_time']);
				    if($be_overdue['day']>0){
				    	if($be_overdue['day']>2){
				    		$overdue_day[$value['user_name']]['overdue_type']=1;
				    	}
				    	$overdue_day[$value['user_name']]['overdue'].=$be_overdue['day'].',';
				    }
				}

				foreach ($new_data as $key => $value) {
					foreach ($overdue_day as $k => $v) {
						if($value['user_name']==$k){
							$new_data[$key]['overdue_day']=$v['overdue'];
							$new_data[$key]['overdue_type']=$v['overdue_type'];
						}
					}
				}
			}
			
			$json_data=json_encode($new_data);
			//file_put_contents("D:/jiangxi/jinka8-10.txt",$json_data);
			$this->new_data=$new_data;
			$this->data=$data;
		}
		$this->display();
	}
/*金卡提额*/
	/*public function jinka(){
		$arr=file_get_contents("D:/jiangxi/jinka8-10.txt");
		$arr=json_decode($arr,1);
		$loan=M('loan');
		foreach ($arr as $key => $value) {
			
			if($value['overdue_type']==1){
			
			}else{
				$where['user_name']=$value['user_name'];

				$lines=(2000-$value['lines'])+$value['loan_lines'];

				$save['loan_lines']=$lines;

				$save['card_type']=1;
				$save['card_time']=time();
				if($value['loan_time']==1){
					$save['interest']=0.05;
				}else if($value['loan_time']==2){
					$save['interest']=0.1;
				}
				$save['interest_type']=1;

				$res=$loan->where($where)->save($save);
			}
		}

	}
*/

/*金卡微信*/
	/*public function sendjinka(){
		$arr=file_get_contents("D:/jiangxi/jinka8-10.txt");
		$arr=json_decode($arr,1);
		foreach ($arr as $key => $value) {

			if($value['overdue_type']==1){
			
			}else{

				$data="【蜻蜓卡】尊敬的".$value['u_name']."，您已成为蜻蜓白卡金卡会员。降低了您借款所需的服务费用，请您继续保持良好的信用。";
				WeixinModel::bomber($value['user_name'],$data);

				$access_token = WeixinModel::getToken();
		        $contnet = "尊敬的".$value['u_name']."，您的会员级别有所变更，具体信息如下：";
		        $text="您已成为蜻蜓白卡金卡会员。降低了您借款所需的服务费用，请您继续保持良好的信用。";
		        $card="金卡会员";
		        $a=WeixinModel::interest($value['open_id'],$contnet,$text,$access_token,$card);
		        var_dump($a);
			}

	    }
	}
*/



/*银卡提额*/
	/*public function yinka(){
		$arr=file_get_contents("D:/jiangxi/yinka8-10-2.txt");
		$arr=json_decode($arr,1);


		$loan=M('loan');
		foreach ($arr as $key => $value) {

			if($value['overdue_type']==1){
			
			}else{
				$where['user_name']=$value['user_name'];
				$lines=(1500-$value['lines'])+$value['loan_lines'];
				$save['loan_lines']=$lines;
				$save['card_type']=2;
				$save['card_time']=time();
				$res=$loan->where($where)->save($save);
				var_dump($res);
				$linesaa+=(1500-$value['lines']);
			}
		}
		var_dump($linesaa);
	}*/


/*银卡微信*/
	/*public function weixin(){
		$arr=file_get_contents("D:/jiangxi/yinka8-10-2.txt");
		$arr=json_decode($arr,1);
		foreach ($arr as $key => $value) {
			if($value['overdue_type']==1){
				
			}else{
				$data="【蜻蜓卡】尊敬的".$value['u_name']."，您已成为蜻蜓白卡银卡会员，您的借款额度已提升，请您继续保持良好的信用。";
				WeixinModel::bomber($value['user_name'],$data);

				$access_token = WeixinModel::getToken();
		        $contnet = "尊敬的".$value['u_name']."，您的会员级别有所变更，具体信息如下：";
		        $text="蜻蜓白卡为感谢您的支持，您的借款额度已提升，请您继续保持良好的信用。";
		        $card="银卡会员";
		        $a=WeixinModel::interest($value['open_id'],$contnet,$text,$access_token,$card);
		        var_dump($a);
		    }
		}
	}*/
}