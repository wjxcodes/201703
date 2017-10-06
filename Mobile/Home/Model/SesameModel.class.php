<?php
namespace Home\Model;
use Think\Model;
use zmxy\ZmopSdk;
use zmxy\zmop\ZmopClient;
use zmxy\zmop\request\ZhimaCreditIvsDetailGetRequest;
use zmxy\zmop\request\ZhimaCreditScoreGetRequest;
use zmxy\zmop\request\ZhimaCreditWatchlistiiGetRequest;
use zmxy\zmop\request\ZhimaCreditAntifraudRiskListRequest;
class SesameModel extends Model{
	/*   反欺诈数据接口    */
	Static public function anti_four($user_data){
		/*  身份证四要素查询tionid标识   最后一位永远为1 */
		$tionid = date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 5, 13), 1))), 0, 16);
		$tionid=$tionid."1";
        /*  密钥和产品编号 */
		$gatewayUrl = "https://zmopenapi.zmxy.com.cn/openapi.do";
		$privateKeyFile = "/usr/keys/rsa_private_key.pem";
		$zmPublicKeyFile = "/usr/keys/rsa_public_key.pem";
		$charset = "UTF-8";
		$appId = "1002203";
		/*  身份证四要素查询  */
		$client = new ZmopClient($gatewayUrl, $appId, $charset, $privateKeyFile, $zmPublicKeyFile);
		$request = new ZhimaCreditIvsDetailGetRequest();
		$request->setChannel("apppc");
		$request->setPlatform("zmop");
		$request->setProductCode("w1010100000000000103"); //必要参数，IVS的产品码是固定的，无需修改
		$request->setTransactionId($tionid); //必要参数，业务流水号
		$request->setCertNo($user_data['identity']);
		$request->setName($user_data['u_name']);
		$request->setMobile($user_data['user_name']);
		$request->setBankCard($user_data['bank_card']);
		$response = $client->execute($request);
		return $response;
	}

    static public function anti_linkman($user_data){
    	/*  紧急联系人要素查询tionid标识   最后一位永远为2 */
		$tionid1 = date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 5, 13), 1))), 0, 16);
	    $tionid1=$tionid1."2";
	    /*  密钥和产品编号 */
		$gatewayUrl = "https://zmopenapi.zmxy.com.cn/openapi.do";
		$privateKeyFile = "/usr/keys/rsa_private_key.pem";
		$zmPublicKeyFile = "/usr/keys/rsa_public_key.pem";
		$charset = "UTF-8";
		$appId = "1002203";
		/*  紧急联系人2要素  */
	    $client1 = new ZmopClient($gatewayUrl, $appId, $charset, $privateKeyFile, $zmPublicKeyFile);
	    $request1 = new ZhimaCreditIvsDetailGetRequest();
	    $request1->setChannel("apppc");
	    $request1->setPlatform("zmop");
	    $request1->setProductCode("w1010100000000000103"); //必要参数，IVS的产品码是固定的，无需修改
	    $request1->setTransactionId($tionid1); //必要参数，业务流水号
	    $request1->setName($user_data['linkman_name']);
	    $request1->setMobile($user_data['linkman_tel']);
	    $response1 = $client1->execute($request1);
	    return $response1;
    }

    static public function clan($user_data){
    	/*  亲属  紧急联系人要素查询tionid标识   最后一位永远为9 */
		$tionid1 = date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 5, 13), 1))), 0, 16);
	    $tionid1=$tionid1."9";
	    /*  密钥和产品编号 */
		$gatewayUrl = "https://zmopenapi.zmxy.com.cn/openapi.do";
		$privateKeyFile = "/usr/keys/rsa_private_key.pem";
		$zmPublicKeyFile = "/usr/keys/rsa_public_key.pem";
		$charset = "UTF-8";
		$appId = "1002203";
		/*  紧急联系人2要素  */
	    $client1 = new ZmopClient($gatewayUrl, $appId, $charset, $privateKeyFile, $zmPublicKeyFile);
	    $request1 = new ZhimaCreditIvsDetailGetRequest();
	    $request1->setChannel("apppc");
	    $request1->setPlatform("zmop");
	    $request1->setProductCode("w1010100000000000103"); //必要参数，IVS的产品码是固定的，无需修改
	    $request1->setTransactionId($tionid1); //必要参数，业务流水号
	    $request1->setName($user_data['clan_name']);
	    $request1->setMobile($user_data['clan_tel']);
	    $response1 = $client1->execute($request1);
	    return $response1;
    }


    static public function anti_three($user_data){
    	/*  身份证三要素查询tionid标识   最后一位永远为2 */
        $tionid2 = date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 5, 13), 1))), 0, 16);
        $tionid2=$tionid2."3";
        /*  密钥和产品编号 */
		$gatewayUrl = "https://zmopenapi.zmxy.com.cn/openapi.do";
		$privateKeyFile = "/usr/keys/rsa_private_key.pem";
		$zmPublicKeyFile = "/usr/keys/rsa_public_key.pem";
		$charset = "UTF-8";
		$appId = "1002203";
		/*  身份证三要素查询  */
        $client2 = new ZmopClient($gatewayUrl, $appId, $charset, $privateKeyFile, $zmPublicKeyFile);
        $request2 = new ZhimaCreditIvsDetailGetRequest();
        $request2->setChannel("apppc");
        $request2->setPlatform("zmop");
        $request2->setProductCode("w1010100000000000103"); //必要参数，IVS的产品码是固定的，无需修改
        $request2->setTransactionId($tionid2); //必要参数，业务流水号
        $request2->setName($user_data['u_name']);
        $request2->setCertNo($user_data['identity']);
        $request2->setMobile($user_data['user_name']);
        $response2 = $client2->execute($request2);
        return $response2;
    }                   
    /*  芝麻分接口  */
    static public function anti_score($user_data){
        /*  芝麻分要素查询tionid标识   最后一位永远为4 */
		$tionid_score=date('Ymdhis').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 5, 13), 1))), 0, 15);
		$tionid_score=$tionid_score."4";
		/*  密钥和产品编号 */
		$gatewayUrl_score = "https://zmopenapi.zmxy.com.cn/openapi.do";
		$privateKeyFile_score = "/usr/keyi/rsa_private_key.pem";
		$zmPublicKeyFile_score = "/usr/keyi/zmf_public_key.pem";
		$charset_score = "UTF-8";
		$appId_score = "1002359";
        /*  芝麻分查询  */
		$client_score = new ZmopClient($gatewayUrl_score,$appId_score,$charset_score,$privateKeyFile_score,$zmPublicKeyFile_score);
		$request_score = new ZhimaCreditScoreGetRequest();
		$request_score->setPlatform("zmop");
		$request_score->setTransactionId($tionid_score);// 必要参数 
		$request_score->setProductCode("w1010100100000000001");// 必要参数 
		$request_score->setOpenId($user_data['zhima_openid']);// 必要参数 
		$response_score = $client_score->execute($request_score);
        return $response_score;
    }
    /* 行业内名单查询*/
    static public function anti_focus($user_data){
	    /*  芝麻分要素查询tionid标识   最后一位永远为5 */
	    $tionid_focus=date('Ymdhis').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 5, 13), 1))), 0, 15);
	    $tionid_focus=$tionid_focus."5";
	    /*  密钥和产品编号 */
	    $gatewayUrl_focus = "https://zmopenapi.zmxy.com.cn/openapi.do";
	    $privateKeyFile_focus = "/usr/keyi/rsa_private_key.pem";
	    $zmPublicKeyFile_focus = "/usr/keyi/hyn_public_key.pem";
	    $charset_focus = "UTF-8";
	    $appId_focus = "1002360";
        /* 行业内名单查询*/
	    $client_focus = new ZmopClient($gatewayUrl_focus,$appId_focus,$charset_focus,$privateKeyFile_focus,$zmPublicKeyFile_focus);
	    $request_focus = new ZhimaCreditWatchlistiiGetRequest();
	    $request_focus->setPlatform("zmop");
	    $request_focus->setProductCode("w1010100100000000022");// 必要参数 
	    $request_focus->setTransactionId($tionid_focus);// 必要参数 
	    $request_focus->setOpenId($user_data['zhima_openid']);// 必要参数 
	    $response_focus = $client_focus->execute($request_focus);
        return $response_focus;
    }

    static public function anti_fraud($user_data){
		$gatewayUrl_fraud = "https://zmopenapi.zmxy.com.cn/openapi.do";
		$privateKeyFile_fraud = "/usr/keyi/rsa_private_key.pem";
		$zmPublicKeyFile_fraud = "/usr/keyi/qzgzqd_public_key.pem";
		$charset_fraud = "UTF-8";
		$appId_fraud = "1002327";
		$tionid_fraud=date('Ymdhis').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 5, 13), 1))), 0, 13);
		$client_fraud = new ZmopClient($gatewayUrl_fraud,$appId_fraud,$charset_fraud,$privateKeyFile_fraud,$zmPublicKeyFile_fraud);
		$request_fraud = new ZhimaCreditAntifraudRiskListRequest();
		$request_fraud->setChannel("apppc");
		$request_fraud->setPlatform("zmop");
		$request_fraud->setProductCode("w1010100003000001283");  //必要参数，IVS的产品码是固定的，无需修改  
		$request_fraud->setTransactionId($tionid_fraud);  //必要参数，业务流水号
		$request_fraud->setCertNo($user_data['identity']);
		$request_fraud->setCertType("IDENTITY_CARD");
		$request_fraud->setName($user_data['u_name']);
		$request_fraud->setMobile($user_data['bank_tel']);
		$request_fraud->setBankCard($user_data['bank_card']);
		$response_fraud = $client_fraud->execute($request_fraud);
        return $response_fraud;
	}	
    /* JSON 处理为数组*/
	Static public function object_array($array) {
              if (is_object($array)) {
                $array = (array)$array;
              }
              if (is_array($array)) {
                foreach ($array as $key => $value) {
                  $array[$key] = self::object_array($value);
                }
              }
        return $array;
    }
    Static public function risk_code($response_value){    
		$risk = array(
		'R_CN_001' => '身份证号集中网络欺诈风险名单', 
		'R_CN_002' => '身份证号曾经被泄露', 
		'R_CN_003' => '身份证号曾经被冒用', 
		'R_CN_004' => '身份证号出现在风险关联网络', 
		'R_CN_JJ_01' => '身份证当天在多个商户申请',
		'R_CN_JJ_02' => '身份证近一周（不包含当天）在多个商户申请',
		'R_CN_JJ_03' => '身份证近一月（不包含当天）在多个商户申请',
		'R_PH_001'=>'手机号击中网络欺诈风险名单',
		'R_PH_002'=>'手机号疑似多个用户公用',
		'R_PH_003'=>'手机号疑似虚拟运营商小号',
		'R_PH_004'=>'手机号疑似二次放号',
		'R_PH_005'=>'手机号失联风险高',
		'R_PH_006'=>'手机号稳定性弱',
		'R_PH_JJ_01'=>'手机号当天在多个商户申请',
		'R_PH_JJ_02'=>'手机号近一周（不包含今天）在多个商户申请',
		'R_PH_JJ_03'=>'手机号近一月（不包含今天）在多个商户申请',
		'R_BC_001'=>'银行卡击中网络欺诈风险名单',
		'R_BC_002'=>'银行卡曾经被泄露',
		'R_BC_003'=>'银行卡曾经被冒用',
		'R_AD_001'=>'疑似虚假地址',
		'R_MC_001'=>'疑似篡改的MAC',
		'R_MC_002'=>'MAC击中网络欺诈风险名单',
		'R_MC_003'=>'手机MAC近期内不活跃',
		'R_MC_004'=>'手机MAC较新',
		'R_MC_005'=>'恶意攻击MAC',
		'R_MC_006'=>'疑似中介MAC',
		'R_MC_JJ_01'=>'当天多个用户通过该MAC申请',
		'R_MC_JJ_02'=>'近一周（不包含当天）多个用户通过该MAC申请',
		'R_MC_JJ_03'=>'近一月（不包含当天）多个用户通过该MAC申请',
		'R_IP_001'=>'代理IP',
		'R_IP_002'=>'服务器IP',
		'R_IP_003'=>'热点IP',
		'R_IP_004'=>'IP近期不活跃',
		'R_IP_005'=>'IP较新',
		'R_IP_006'=>'IP上聚集多个设备',
		'R_IP_007'=>'IP设备分布异常',
		'R_IP_008'=>'疑似中介IP',
		'R_IP_JJ_01'=>'当天多个用户通过该IP申请',
		'R_IP_JJ_02'=>'近一周（不包含当天）多个用户通过该IP申请',
		'R_IP_JJ_03'=>'近一月（不包含当天）多个用户通过该IP申请',
		'R_IM_001'=>'IMEI击中网络欺诈风险名单',
		'R_IM_002'=>'IMEI近期不活跃',
		'R_IM_003'=>'IMEI较新',
		'R_IM_004'=>'疑似虚假IMEI',
		'R_IM_JJ_01'=>'当天多个用户通过该IMEI申请',
		'R_IM_JJ_02'=>'近一周（不包含当天）多个用户通过该IMEI申请',
		'R_IM_JJ_03'=>'近一月（不包含当天）多个用户通过该IMEI申请');
          foreach ($response_value as $k => $v) {
             foreach($risk as $k1=>$v1){
                  if ($v==$k1) {
                      $return_risk .= $v1." || ";
                  }
               }
           }
        return $return_risk;    
      }
     Static public function identity_bool($message){    
		  $str = array(410000,410100,410101,410102,410103,410104,410105,410106,410108,410122,410181,410182,410183,410184,410185,410200,410201,410202,410203,410204,410205,410211,410221,410222,410223,410224,410225,410300,410301,410302,410303,410304,410305,410306,410311,410322,410323,410324,410325,410326,410327,410328,410329,410381,410400,410401,410402,410403,410404,410411,410421,410422,410423,410425,410481,410482,410500,410501,410502,410503,410504,410511,410522,410523,410526,410527,410581,410600,410601,410602,410603,410611,410621,410622,410700,410701,410702,410703,410704,410711,410721,410724,410725,410726,410727,410728,410781,410782,410800,410801,410802,410803,410804,410811,410821,410822,410823,410825,410881,410882,410883,410900,410901,410902,410922,410923,410926,410927,410928,411000,411001,411002,411023,411024,411025,411081,411082,411100,411101,411102,411121,411122,411123,411200,411201,411202,411221,411224,411281,411282,411300,411301,411302,411303,411321,411322,411323,411324,411325,411326,411327,411328,411329,411330,411381,411400,411401,411402,411403,411421,411422,411423,411424,411425,411426,411481,411500,411501,411502,411503,411521,411522,411523,411524,411525,411526,411527,411528,412700,412701,412702,412721,412722,412723,412724,412725,412726,412727,412728,412800,412801,412821,412822,412823,412824,412825,412826,412827,412828,412829);
		         ; 
		  $arr = trim(substr(362326199705012445,0,6));
		  $bool = in_array($arr, $str);
		  return $bool;
    }

    Static public function getRandomString($len){
    	$lenth = strlen($len);
        $strcode = array();
        if($lenth < 6){
          $num = $lenth - 6;
          $arr = str_split(str_pad($len,6,0,STR_PAD_LEFT));
        }elseif($lenth == 6){
          $arr = str_split($len);
        }
            $m[0]=array(0,Q,q,W,w,O);
            $m[1]=array(Y,1,y,U,u,I);
            $m[2]=array(A,a,2,S,s,D);
            $m[3]=array(R,r,T,3,t,i);
            $m[4]=array(Z,z,X,x,4,c);
            $m[5]=array(V,v,B,b,d,5);
            $m[6]=array(6,K,k,L,l,p);
            $m[7]=array(H,7,h,J,j,o);
            $m[8]=array(F,f,8,G,g,P);
            $m[9]=array(N,n,M,9,m,e);

        return $m[$arr[0]][rand(0,5)].$m[$arr[1]][rand(0,5)].$m[$arr[2]][rand(0,5)].$m[$arr[3]][rand(0,5)].$m[$arr[4]][rand(0,5)].$m[$arr[5]][rand(0,5)];
	}

	Static public function checkW($bonuscodes){
        if(preg_match("/^[A-Za-z0-9]+$/",$bonuscodes) && strlen($bonuscodes)==6){
            return true;
        }else{
            return false;
        }
    }
       
}