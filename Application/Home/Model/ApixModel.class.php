<?php
/*
	含有  apix  运营商表    淘宝表   京东表     万达  信息表
*/
namespace Home\Model;
use Think\Model;
class ApixModel extends Model{

//查询一条  运营商信息 
	public function find_username($user_name){
		$apix=M('apix');
		$where['user_name']=$user_name;
		$data=$apix->where($where)->order('id desc')->find();
		return $data;
	}
//引用
//apix/tel


//查询一条  淘宝信息 
	public function taobao_find($user_name){
		$taobao_info=M('taobao_info');
		$where['user_name']=$user_name;
		$data=$taobao_info->where($where)->order('id desc')->find();
		return $data;
	}
//引用
//apix/taobao



//查询一条 万达信息  
	public function wanda_find($user_name){
		$wdinfo = M('wdinfo');
		$where['user_name']=$user_name;
		$data=$wdinfo->where($where)->order('id desc')->find();
		return $data;
	}
//引用
//apix/tel

//查询一条  淘宝信息 
	public function jd_find($user_name){
		$jingdong=M('jingdong');
		$where['user_name']=$user_name;
		$data=$jingdong->where($where)->order('id desc')->find();
		return $data;
	}
//引用
//apix/jd



//////////////////////////////旧

	static public function apix_data($token){
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => "http://e.apix.cn/apixanalysis/mobile/retrieve/phone/data/analyzed?query_code=".$token."",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
		    "accept: application/json",
		    "apix-key: aeaff902ab054c1e6b4d8b2c514afb86",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  $response=json_decode($response,1);
		}
		return $response;
	} 

	static public function apix_taobao($token){
		$curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL => "http://e.apix.cn/apixanalysis/tb/retrieve/ele_business/taobao/query?query_code=".$token."",
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
		}
		return $response;
	}
//////////////////////////////旧

}