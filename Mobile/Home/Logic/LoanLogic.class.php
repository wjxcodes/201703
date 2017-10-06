<?php
namespace Home\Logic;
use Think\Controller;
class LoanLogic extends Controller{

	public function interest_type($interest_type,$time){

		$arr=array(0=>array(14=>0.12,7=>0.06),
				   1=>array(14=>0.10,7=>0.05)
			 );

		return  $arr[$interest_type][$time];
	}

	public function poundage($money,$interest){
		$poundage=$money*$interest;
		return $poundage;
	}

	public function sum_cost($poundage){
		$arr['shenji']=sprintf('%.2f',$poundage*0.475);
        $arr['lixi']=sprintf('%.2f',$poundage*0.025);
        $arr['guanli']=sprintf('%.2f',$poundage*0.50);
        return $arr;
	}

}