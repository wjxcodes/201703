<?php
namespace Home\Model;
use Think\Model;
header("content-type:text/html;charset=utf8");
class SurveyModel extends Model{
	static public function four_ele($credit_data){
		$information=array("姓名与其他信息匹配，匹配后的信息被一个用户使用","电话号码与其他信息匹配，匹配后的信息近期较活跃","身份证号与其他信息匹配，匹配后的信息被一个用户使用","银行卡号与其他信息匹配，匹配后的信息经过认证","姓名与其他信息匹配，匹配后的信息经过认证","身份证号与其他信息匹配，匹配后的信息经过认证");
		$to_match['tel_a']=in_array($credit_data['tel_a'],$information);
        $to_match['u_name_a']=in_array($credit_data['u_name_a'],$information);
        $to_match['identity_a']=in_array($credit_data['identity_a'],$information);
        $to_match['bank_card_a']=in_array($credit_data['bank_card_a'],$information);
        $match=self::disting($to_match,$credit_data);
        return $match;
	}

	static public function three_ele($credit_data){
		$information=array("姓名与其他信息匹配，匹配后的信息被一个用户使用","电话号码与其他信息匹配，匹配后的信息近期较活跃","身份证号与其他信息匹配，匹配后的信息被一个用户使用","银行卡号与其他信息匹配，匹配后的信息经过认证","姓名与其他信息匹配，匹配后的信息经过认证","身份证号与其他信息匹配，匹配后的信息经过认证");
		$to_match['u_name_a1']=in_array($credit_data['u_name_a1'],$information);
		$to_match['identity_a1']=in_array($credit_data['identity_a1'],$information);
		$to_match['user_name_a']=in_array($credit_data['user_name_a'],$information);
        $match=self::disting($to_match,$credit_data);
        return $match;
	}

	static public function two_ele($credit_data){
		$element=array("姓名与其他信息匹配，匹配后的信息被一个用户使用","电话号码与其他信息匹配，匹配后的信息近期较活跃","电话号码与其他信息匹配，但匹配后的信息近期不活跃","姓名与其他信息匹配，但匹配后的信息未经认证","姓名与其他信息匹配，匹配后的信息经过认证");
		$to_ele['linkman_name_a']=in_array($credit_data['linkman_name_a'],$element);
        $to_ele['linkman_tel_a']=in_array($credit_data['linkman_tel_a'],$element);
        $ele=self::disting($to_ele,$credit_data);
        return $ele;
	}

	static public function clan($credit_data){
		$element=array("姓名与其他信息匹配，匹配后的信息被一个用户使用","电话号码与其他信息匹配，匹配后的信息近期较活跃","电话号码与其他信息匹配，但匹配后的信息近期不活跃","姓名与其他信息匹配，但匹配后的信息未经认证","姓名与其他信息匹配，匹配后的信息经过认证");
		$to_ele['clan_name_a']=in_array($credit_data['clan_name_a'],$element);
        $to_ele['clan_tel_a']=in_array($credit_data['clan_tel_a'],$element);
        $clan=self::disting($to_ele,$credit_data);
        return $clan;
	}

	static public function hit_detail($hit_detail){
		$hit_detail=explode("||",$hit_detail);
		$element=array("身份证当天在多个商户申请","身份证近一周（不包含当天）在多个商户申请","身份证近一月（不包含当天）在多个商户申请","手机号当天在多个商户申请","手机号近一周（不包含今天）在多个商户申请","手机号近一月（不包含今天）在多个商户申请");
		foreach ($hit_detail as $key => $value) {
			if($value!=' '){
				$t_value=trim($value);
				$hit=in_array($t_value,$element);
				if(!$hit){
					$h_detail.=$value."||";
				}
			}
		}
		return $h_detail;
	}
	static public function disting($data,$credit_data){
		foreach ($data as $key => $value) {
            if(!$value){
                $match[$key]=$credit_data[$key];
            }
        }
        return $match;
	}

}