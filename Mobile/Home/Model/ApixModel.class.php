<?php
namespace Home\Model;
use Think\Model;
class ApixModel extends Model{
	static public function apix_url(){
		$curl = curl_init();
         $md5=md5('ajax'.$_SESSION['name']);
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "http://e.apix.cn/apixanalysis/mobile/yys/phone/carrier/page?callback_url=https://ziyouqingting.com/free/mobile.php/home/apix/tel_callback_url?id=".$_SESSION['name']."&success_url=https://ziyouqingting.com/free/mobile.php/home/apix/tel_success_url?id=".$md5."&failed_url=https://ziyouqingting.com/free/mobile.php/home/approve/index&phone_no=".$_SESSION['name']."",
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
			  $data=json_decode($response,1);
            }
		return $data['url'];
	}

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
		  $response="错误";
		} else {
		  $response=json_decode($response,1);
		}
		return $response;
	} 
}