<?php 
/*
*征信统计
*/
namespace Home\Controller;
use Think\Controller;
use Home\Model\RepayModel as RepayModel;
header("content-type:text/html;charset=utf8");
class StatisController extends BaseController{
	public function index(){
		$post=I('post.');
		$loan=M('loan');
		$where['is_pay']=array('gt',0);
		$loan_data=$loan->where($where)->field('free_loan.user_id,is_pay,loan_time,renewal_days,loan_amount,zm_score,huabei_lines,valid_tel,year_expense,urgency_tel,tel_valid_time,interflow_tel')
		->join('free_credit_statistics ON free_credit_statistics.user_id=free_loan.user_id')
		->select();


		$record=M('record');
		$record_data=$record->field('loan_time,pay_time,xutime,pay_money,repayment_time,zm_score,huabei_lines,valid_tel,year_expense,urgency_tel,tel_valid_time,interflow_tel')
		->join('free_credit_statistics ON free_credit_statistics.user_id=free_record.user_id')
		->select();
        
        $record_data2=$record->field('loan_time,pay_time,xutime,pay_money,repayment_time')->select();
        if($post['code']==1){
        	$sta_time=strtotime($post['start']);
        	$end_time=strtotime($post['end']);
        }else{
        	$sta_time=strtotime('-1 week');
        	$end_time=time();
        }
        

        $time=($end_time-$sta_time)/86400;
        $ini_time=strtotime(date("Y-m-d",$sta_time));

        for($i=1;$i<=$time;$i++){
        	$s_time=$ini_time+($i-1)*86400;
        	$e_time=$ini_time+$i*86400;
        	$time_arr[]=array('s_time'=>$s_time,'e_time'=>$e_time);
        }

        foreach ($record_data2 as $key => $value) {
        	if($value['loan_time']==1){
				$loan_time=7;
			}else if($value['loan_time']==2){
				$loan_time=14;
			}
        	$return_arr=RepayModel::be_overdue($value['pay_time'],$loan_time,$value['xutime'],$value['pay_money'],$value['repayment_time']);
        	foreach ($time_arr as $k => $v) {
        		if($value['pay_time']>=$v['s_time'] && $value['pay_time']<$v['e_time']){
        			if($return_arr['day']>0){//逾期还款
        				$time_arr[$k]['due']+=1;
		        	}else{//未逾期还款
		        		$time_arr[$k]['to']+=1;
		        	}
        		}
        	}
        }

        foreach ($loan_data as $key => $value) {
        	if($value['loan_time']==1){
				$loan_time=7;
			}else if($value['loan_time']==2){
				$loan_time=14;
			}
			$return_arr=RepayModel::overdue_show($value['is_pay'],$loan_time,$value['renewal_days'],$value['loan_amount']);
			foreach ($time_arr as $k => $v) {
        		if($value['is_pay']>=$v['s_time'] && $value['is_pay']<$v['e_time']){
        			if($return_arr['day']>0){//逾期
        				$time_arr[$k]['loan_due']+=1;
		        	}else{//未逾期
		        		$time_arr[$k]['loan_to']+=1;
		        	}
        		}
        	}
        }
        if($post['code']==2){
        	$sta_time_sta=strtotime($post['start']);
        	$end_time_end=strtotime($post['end'])+86400;
        }else{
        	$sta_time_sta=1483200000;
        	$end_time_end=1512057600;
        }

/*已还款 */
		foreach ($record_data as $key => $value) {
			if($value['loan_time']==1){
				$loan_time=7;
			}else if($value['loan_time']==2){
				$loan_time=14;
			}
			$return_arr=RepayModel::be_overdue($value['pay_time'],$loan_time,$value['xutime'],$value['pay_money'],$value['repayment_time']);
			$interflow_str=mb_substr($value['interflow_tel'],0,1,'utf-8');
			if($interflow_str=="有"){
				$interflow_tel=mb_substr(strstr($value['interflow_tel'],"个",true),1,8,'utf-8');
			}else{
				$interflow_tel=$value['interflow_tel'];
			}

			if($value['pay_time']>=$sta_time_sta && $value['pay_time']<$end_time_end){
				if($return_arr['day']>0){//逾期还款
/*芝麻分*/				
				if($value['zm_score']<600){
					$record_arr['zm_due600']+=1;
				}else if($value['zm_score']<650 && $value['zm_score']>=600){
					$record_arr['zm_due650']+=1;
/*芝麻650分   花呗分层*/					
					/*if($value['huabei_lines']<1000 && $value['huabei_lines']!=''){
						$record_arr['zm_650hb_due1000']+=1;
					}else if($value['huabei_lines']<2000 && $value['huabei_lines']>=1000){
						$record_arr['zm_650hb_due2000']+=1;
					}else if($value['huabei_lines']<3000 && $value['huabei_lines']>=2000){
						$record_arr['zm_650hb_due3000']+=1;
					}else if($value['huabei_lines']<4000 && $value['huabei_lines']>=3000){
						$record_arr['zm_650hb_due4000']+=1;
					}else if($value['huabei_lines']<5000 && $value['huabei_lines']>=4000){
						$record_arr['zm_650hb_due5000']+=1;
					}else if($value['huabei_lines']>=5000){
						$record_arr['zm_650hb_due6000']+=1;
					}else if($value['huabei_lines']=''){
						$record_arr['zm_650hb_due_empty']+=1;
					}*/
/*芝麻650分   淘宝消费分层*/
					/*if($value['year_expense']<1000 && $value['year_expense']!=''){
						$record_arr['zm_650xf_due1000']+=1;
					}else if($value['year_expense']<2000 && $value['year_expense']>=1000){
						$record_arr['zm_650xf_due2000']+=1;
					}else if($value['year_expense']<3000 && $value['year_expense']>=2000){
						$record_arr['zm_650xf_due3000']+=1;
					}else if($value['year_expense']<5000 && $value['year_expense']>=3000){
						$record_arr['zm_650xf_due5000']+=1;
					}else if($value['year_expense']<10000 && $value['year_expense']>=5000){
						$record_arr['zm_650xf_due10000']+=1;
					}else if($value['year_expense']>10000){
						$record_arr['zm_650xf_due11000']+=1;
					}*/
				}else if($value['zm_score']<700 && $value['zm_score']>=650){
					$record_arr['zm_due700']+=1;
				}else if($value['zm_score']<750 && $value['zm_score']>=700){
					$record_arr['zm_due750']+=1;
				}else if($value['zm_score']>750){
					$record_arr['zm_due800']+=1;
				}
/*花呗*/
				if($value['huabei_lines']<1000 && $value['huabei_lines']!=''){
					$record_arr['hb_due1000']+=1;
				}else if($value['huabei_lines']<2000 && $value['huabei_lines']>=1000){
					$record_arr['hb_due2000']+=1;
				}else if($value['huabei_lines']<3000 && $value['huabei_lines']>=2000){
					$record_arr['hb_due3000']+=1;
				}else if($value['huabei_lines']<4000 && $value['huabei_lines']>=3000){
					$record_arr['hb_due4000']+=1;
				}else if($value['huabei_lines']<5000 && $value['huabei_lines']>=4000){
					$record_arr['hb_due5000']+=1;
				}else if($value['huabei_lines']>=5000){
					$record_arr['hb_due6000']+=1;
				}else if($value['huabei_lines']=''){
					$record_arr['hb_due_empty']+=1;
				}
/*有效号码*/	
				if($value['valid_tel']<10 && $value['valid_tel']!=''){
					$record_arr['yx_due10']+=1;
				}else if($value['valid_tel']<20 && $value['valid_tel']>=10){
					$record_arr['yx_due20']+=1;
				}else if($value['valid_tel']<30 && $value['valid_tel']>=20){
					$record_arr['yx_due30']+=1;
				}else if($value['valid_tel']<40 && $value['valid_tel']>=30){
					$record_arr['yx_due40']+=1;
				}else if($value['valid_tel']<50 && $value['valid_tel']>=40){
					$record_arr['yx_due50']+=1;
				}
/*淘宝消费*/
				if($value['year_expense']<1000 && $value['year_expense']!=''){
					$record_arr['xf_due1000']+=1;
				}else if($value['year_expense']<2000 && $value['year_expense']>=1000){
					$record_arr['xf_due2000']+=1;
				}else if($value['year_expense']<3000 && $value['year_expense']>=2000){
					$record_arr['xf_due3000']+=1;
				}else if($value['year_expense']<5000 && $value['year_expense']>=3000){
					$record_arr['xf_due5000']+=1;
				}else if($value['year_expense']<10000 && $value['year_expense']>=5000){
					$record_arr['xf_due10000']+=1;
				}else if($value['year_expense']>10000){
					$record_arr['xf_due11000']+=1;
				}
/*紧急联系人通话*/
				if($value['urgency_tel']<20 && $value['urgency_tel']!=''){
					$record_arr['ut_due20']+=1;
				}else if($value['urgency_tel']<50 && $value['urgency_tel']>=20){
					$record_arr['ut_due50']+=1;
				}else if($value['urgency_tel']<100 && $value['urgency_tel']>=50){
					$record_arr['ut_due100']+=1;
				}else if($value['urgency_tel']<150 && $value['urgency_tel']>=100){
					$record_arr['ut_due150']+=1;
				}else if($value['urgency_tel']>150){
					$record_arr['ut_due200']+=1;
				}
/*前10位通话平局值*/
				if($value['tel_valid_time']<50 && $value['tel_valid_time']!=''){
					$record_arr['pj_due50']+=1;
				}else if($value['tel_valid_time']<100 && $value['tel_valid_time']>=50){
					$record_arr['pj_due100']+=1;
				}else if($value['tel_valid_time']<150 && $value['tel_valid_time']>=100){
					$record_arr['pj_due150']+=1;
				}else if($value['tel_valid_time']<200 && $value['tel_valid_time']>=150){
					$record_arr['pj_due200']+=1;
				}else if($value['tel_valid_time']>200){
					$record_arr['pj_due250']+=1;
				}
/*互通电话量*/
				if($interflow_tel<20 && $value['tel_valid_time']!=''){
					$record_arr['ht_due20']+=1;
				}else if($interflow_tel<40 && $value['tel_valid_time']>=20){
					$record_arr['ht_due40']+=1;
				}else if($interflow_tel<60 && $value['tel_valid_time']>=40){
					$record_arr['ht_due60']+=1;
				}else if($interflow_tel<80 && $value['tel_valid_time']>=60){
					$record_arr['ht_due80']+=1;
				}else if($interflow_tel>80){
					$record_arr['ht_due100']+=1;
				}
				$record_arr['zm_dueren']+=1;
			}else{//直接还款 
/*芝麻分*/
				if($value['zm_score']<600){
					$record_arr['zm_to600']+=1;
				}else if($value['zm_score']<650 && $value['zm_score']>=600){
					$record_arr['zm_to650']+=1;
/*芝麻650分   花呗分层*/
					/*if($value['huabei_lines']<1000 && $value['huabei_lines']!=''){
						$record_arr['zm_650hb_to1000']+=1;
					}else if($value['huabei_lines']<2000 && $value['huabei_lines']>=1000){
						$record_arr['zm_650hb_to2000']+=1;
					}else if($value['huabei_lines']<3000 && $value['huabei_lines']>=2000){
						$record_arr['zm_650hb_to3000']+=1;
					}else if($value['huabei_lines']<4000 && $value['huabei_lines']>=3000){
						$record_arr['zm_650hb_to4000']+=1;
					}else if($value['huabei_lines']<5000 && $value['huabei_lines']>=4000){
						$record_arr['zm_650hb_to5000']+=1;
					}else if($value['huabei_lines']>=5000){
						$record_arr['zm_650hb_to6000']+=1;
					}else if($value['huabei_lines']=''){
						$record_arr['zm_650hb_to_empty']+=1;
					}*/
/*芝麻650分   淘宝消费分层*/
					/*if($value['year_expense']<1000 && $value['year_expense']!=''){
						$record_arr['zm_650xf_to1000']+=1;
					}else if($value['year_expense']<2000 && $value['year_expense']>=1000){
						$record_arr['zm_650xf_to2000']+=1;
					}else if($value['year_expense']<3000 && $value['year_expense']>=2000){
						$record_arr['zm_650xf_to3000']+=1;
					}else if($value['year_expense']<5000 && $value['year_expense']>=3000){
						$record_arr['zm_650xf_to5000']+=1;
					}else if($value['year_expense']<10000 && $value['year_expense']>=5000){
						$record_arr['zm_650xf_to10000']+=1;
					}else if($value['year_expense']>10000){
						$record_arr['zm_650xf_to11000']+=1;
					}*/
				}else if($value['zm_score']<700 && $value['zm_score']>=650){
					$record_arr['zm_to700']+=1;
				}else if($value['zm_score']<750 && $value['zm_score']>=700){
					$record_arr['zm_to750']+=1;
				}else if($value['zm_score']>750){
					$record_arr['zm_to800']+=1;
				}
/*花呗*/
				if($value['huabei_lines']<1000 && $value['huabei_lines']!=''){
					$record_arr['hb_to1000']+=1;
				}else if($value['huabei_lines']<2000 && $value['huabei_lines']>=1000){
					$record_arr['hb_to2000']+=1;
				}else if($value['huabei_lines']<3000 && $value['huabei_lines']>=2000){
					$record_arr['hb_to3000']+=1;
				}else if($value['huabei_lines']<4000 && $value['huabei_lines']>=3000){
					$record_arr['hb_to4000']+=1;
				}else if($value['huabei_lines']<5000 && $value['huabei_lines']>=4000){
					$record_arr['hb_to5000']+=1;
				}else if($value['huabei_lines']>=5000){
					$record_arr['hb_to6000']+=1;
				}else if($value['huabei_lines']=''){
					$record_arr['hb_to_empty']+=1;
				}
/*有效号码*/
				if($value['valid_tel']<10 && $value['valid_tel']!=''){
					$record_arr['yx_to10']+=1;
				}else if($value['valid_tel']<20 && $value['valid_tel']>=10){
					$record_arr['yx_to20']+=1;
				}else if($value['valid_tel']<30 && $value['valid_tel']>=20){
					$record_arr['yx_to30']+=1;
				}else if($value['valid_tel']<40 && $value['valid_tel']>=30){
					$record_arr['yx_to40']+=1;
				}else if($value['valid_tel']<50 && $value['valid_tel']>=40){
					$record_arr['yx_to50']+=1;
				}
/*淘宝消费*/
				if($value['year_expense']<1000 && $value['year_expense']!=''){
					$record_arr['xf_to1000']+=1;
				}else if($value['year_expense']<2000 && $value['year_expense']>=1000){
					$record_arr['xf_to2000']+=1;
				}else if($value['year_expense']<3000 && $value['year_expense']>=2000){
					$record_arr['xf_to3000']+=1;
				}else if($value['year_expense']<5000 && $value['year_expense']>=3000){
					$record_arr['xf_to5000']+=1;
				}else if($value['year_expense']<10000 && $value['year_expense']>=5000){
					$record_arr['xf_to10000']+=1;
				}else if($value['year_expense']>10000){
					$record_arr['xf_to11000']+=1;
				}
/*紧急联系人通话*/
				if($value['urgency_tel']<20 && $value['urgency_tel']!=''){
					$record_arr['ut_to20']+=1;
				}else if($value['urgency_tel']<50 && $value['urgency_tel']>=20){
					$record_arr['ut_to50']+=1;
				}else if($value['urgency_tel']<100 && $value['urgency_tel']>=50){
					$record_arr['ut_to100']+=1;
				}else if($value['urgency_tel']<150 && $value['urgency_tel']>=100){
					$record_arr['ut_to150']+=1;
				}else if($value['urgency_tel']>150){
					$record_arr['ut_to200']+=1;
				}
/*前10位通话平局值*/
				if($value['tel_valid_time']<50 && $value['tel_valid_time']!=''){
					$record_arr['pj_to50']+=1;
				}else if($value['tel_valid_time']<100 && $value['tel_valid_time']>=50){
					$record_arr['pj_to100']+=1;
				}else if($value['tel_valid_time']<150 && $value['tel_valid_time']>=100){
					$record_arr['pj_to150']+=1;
				}else if($value['tel_valid_time']<200 && $value['tel_valid_time']>=150){
					$record_arr['pj_to200']+=1;
				}else if($value['tel_valid_time']>200){
					$record_arr['pj_to250']+=1;
				}
/*互通电话量*/
				if($interflow_tel<20 && $value['tel_valid_time']!=''){
					$record_arr['ht_to20']+=1;
				}else if($interflow_tel<40 && $value['tel_valid_time']>=20){
					$record_arr['ht_to40']+=1;
				}else if($interflow_tel<60 && $value['tel_valid_time']>=40){
					$record_arr['ht_to60']+=1;
				}else if($interflow_tel<80 && $value['tel_valid_time']>=60){
					$record_arr['ht_to80']+=1;
				}else if($interflow_tel>80){
					$record_arr['ht_to100']+=1;
				}
				$record_arr['zm_toren']+=1;
				}
			}
			
			
			
		}


/*未还款*/  

		foreach ($loan_data as $key => $value) {
			if($value['loan_time']==1){
				$loan_time=7;
			}else if($value['loan_time']==2){
				$loan_time=14;
			}
			$return_arr=RepayModel::overdue_show($value['is_pay'],$loan_time,$value['renewal_days'],$value['loan_amount']);
			$interflow_str=mb_substr($value['interflow_tel'],0,1,'utf-8');
			if($interflow_str=="有"){
				$interflow_tel=mb_substr(strstr($value['interflow_tel'],"个",true),1,8,'utf-8');
			}else{
				$interflow_tel=$value['interflow_tel'];
			}
			if($value['is_pay']>=$sta_time_sta && $value['is_pay']<$end_time_end){
				if($return_arr['day']>0){//逾期
	/*芝麻分*/				
					if($value['zm_score']<600){
						$arr['zm_due600']+=1;
					}else if($value['zm_score']<650 && $value['zm_score']>=600){
						$arr['zm_due650']+=1;
	/*芝麻650分   花呗分层*/					
						/*if($value['huabei_lines']<1000 && $value['huabei_lines']!=''){
							$arr['zm_650hb_due1000']+=1;
						}else if($value['huabei_lines']<2000 && $value['huabei_lines']>=1000){
							$arr['zm_650hb_due2000']+=1;
						}else if($value['huabei_lines']<3000 && $value['huabei_lines']>=2000){
							$arr['zm_650hb_due3000']+=1;
						}else if($value['huabei_lines']<4000 && $value['huabei_lines']>=3000){
							$arr['zm_650hb_due4000']+=1;
						}else if($value['huabei_lines']<5000 && $value['huabei_lines']>=4000){
							$arr['zm_650hb_due5000']+=1;
						}else if($value['huabei_lines']>=5000){
							$arr['zm_650hb_due6000']+=1;
						}else if($value['huabei_lines']=''){
							$arr['zm_650hb_due_empty']+=1;
						}*/
	/*芝麻650分   淘宝消费分层*/
						/*if($value['year_expense']<1000 && $value['year_expense']!=''){
							$arr['zm_650xf_due1000']+=1;
						}else if($value['year_expense']<2000 && $value['year_expense']>=1000){
							$arr['zm_650xf_due2000']+=1;
						}else if($value['year_expense']<3000 && $value['year_expense']>=2000){
							$arr['zm_650xf_due3000']+=1;
						}else if($value['year_expense']<5000 && $value['year_expense']>=3000){
							$arr['zm_650xf_due5000']+=1;
						}else if($value['year_expense']<10000 && $value['year_expense']>=5000){
							$arr['zm_650xf_due10000']+=1;
						}else if($value['year_expense']>10000){
							$arr['zm_650xf_due11000']+=1;
						}*/
					}else if($value['zm_score']<700 && $value['zm_score']>=650){
						$arr['zm_due700']+=1;
					}else if($value['zm_score']<750 && $value['zm_score']>=700){
						$arr['zm_due750']+=1;
					}else if($value['zm_score']>750){
						$arr['zm_due800']+=1;
					}
	/*花呗*/
					if($value['huabei_lines']<1000 && $value['huabei_lines']!=''){
						$arr['hb_due1000']+=1;
					}else if($value['huabei_lines']<2000 && $value['huabei_lines']>=1000){
						$arr['hb_due2000']+=1;
					}else if($value['huabei_lines']<3000 && $value['huabei_lines']>=2000){
						$arr['hb_due3000']+=1;
					}else if($value['huabei_lines']<4000 && $value['huabei_lines']>=3000){
						$arr['hb_due4000']+=1;
					}else if($value['huabei_lines']<5000 && $value['huabei_lines']>=4000){
						$arr['hb_due5000']+=1;
					}else if($value['huabei_lines']>=5000){
						$arr['hb_due6000']+=1;
					}else if($value['huabei_lines']=''){
						$arr['hb_due_empty']+=1;
					}
	/*有效号码*/	
					if($value['valid_tel']<10 && $value['valid_tel']!=''){
						$arr['yx_due10']+=1;
					}else if($value['valid_tel']<20 && $value['valid_tel']>=10){
						$arr['yx_due20']+=1;
					}else if($value['valid_tel']<30 && $value['valid_tel']>=20){
						$arr['yx_due30']+=1;
					}else if($value['valid_tel']<40 && $value['valid_tel']>=30){
						$arr['yx_due40']+=1;
					}else if($value['valid_tel']<50 && $value['valid_tel']>=40){
						$arr['yx_due50']+=1;
					}
	/*淘宝消费*/
					if($value['year_expense']<1000 && $value['year_expense']!=''){
						$arr['xf_due1000']+=1;
					}else if($value['year_expense']<2000 && $value['year_expense']>=1000){
						$arr['xf_due2000']+=1;
					}else if($value['year_expense']<3000 && $value['year_expense']>=2000){
						$arr['xf_due3000']+=1;
					}else if($value['year_expense']<5000 && $value['year_expense']>=3000){
						$arr['xf_due5000']+=1;
					}else if($value['year_expense']<10000 && $value['year_expense']>=5000){
						$arr['xf_due10000']+=1;
					}else if($value['year_expense']>10000){
						$arr['xf_due11000']+=1;
					}
	/*紧急联系人通话*/
					if($value['urgency_tel']<20 && $value['urgency_tel']!=''){
						$arr['ut_due20']+=1;
					}else if($value['urgency_tel']<50 && $value['urgency_tel']>=20){
						$arr['ut_due50']+=1;
					}else if($value['urgency_tel']<100 && $value['urgency_tel']>=50){
						$arr['ut_due100']+=1;
					}else if($value['urgency_tel']<150 && $value['urgency_tel']>=100){
						$arr['ut_due150']+=1;
					}else if($value['urgency_tel']>150){
						$arr['ut_due200']+=1;
					}
	/*前10位通话平局值*/
					if($value['tel_valid_time']<50 && $value['tel_valid_time']!=''){
						$arr['pj_due50']+=1;
					}else if($value['tel_valid_time']<100 && $value['tel_valid_time']>=50){
						$arr['pj_due100']+=1;
					}else if($value['tel_valid_time']<150 && $value['tel_valid_time']>=100){
						$arr['pj_due150']+=1;
					}else if($value['tel_valid_time']<200 && $value['tel_valid_time']>=150){
						$arr['pj_due200']+=1;
					}else if($value['tel_valid_time']>200){
						$arr['pj_due250']+=1;
					}
	/*互通电话量*/
					if($interflow_tel<20 && $value['tel_valid_time']!=''){
						$arr['ht_due20']+=1;
					}else if($interflow_tel<40 && $value['tel_valid_time']>=20){
						$arr['ht_due40']+=1;
					}else if($interflow_tel<60 && $value['tel_valid_time']>=40){
						$arr['ht_due60']+=1;
					}else if($interflow_tel<80 && $value['tel_valid_time']>=60){
						$arr['ht_due80']+=1;
					}else if($interflow_tel>80){
						$arr['ht_due100']+=1;
					}
					$arr['zm_dueren']+=1;
				}else{//未逾期

				}
				$arr['ren']+=1;
			}
		}

		$this->time_arr=$time_arr;
		$this->record_arr=$record_arr;
		$this->arr=$arr;
		$this->display(); 
	}


	public function test(){
		$credit_statistics=M('credit_statistics');
		$where['user_name']=18616860783;
		$data=$credit_statistics->where($where)->find();
		var_dump($data['interflow_tel']);
		$interflow_str=mb_substr($data['interflow_tel'],0,1,'utf-8');
		if($interflow_str=="有"){
			$interflow_tel=mb_substr(strstr($data['interflow_tel'],"个",true),1,8,'utf-8');
		}else{
			$interflow_tel=$data['interflow_tel'];
		}
		var_dump($interflow_tel);
	}
}