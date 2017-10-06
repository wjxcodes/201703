<?php 
/*
*   含有  apix  运营商    淘宝   京东     万达     操作
*/
namespace Home\Logic;
use Think\Controller;
header("content-type:text/html;charset=utf8");
class ApixLogic extends Controller{

	public function rank_order($arrUsers,$lev){
		$sort = array(
                'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
                'field'     => $lev,       //排序字段
         );

        $arrSort = array();
        foreach($arrUsers AS $uniqid => $row){
            foreach($row AS $key=>$value){
             $arrSort[$key][$uniqid] = $value;
            }
        }
        if($sort['direction']){
            array_multisort($arrSort[$sort['field']], constant($sort['direction']), $arrUsers);
        }
        return $arrUsers;
	}

    public function jd_curl($token){

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