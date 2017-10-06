<?php
namespace Home\Model;
use Think\Model;
class JdModel extends Model{
	static public function jd_data($token){

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "http://e.apix.cn/apixanalysis/jd/retrieve/ele_business/jingdong/query?query_code=".$token."",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
		    "accept: application/json",
		    "apix-key: f5da3756ebdd4a295dd6849b9778f9d3",
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
}