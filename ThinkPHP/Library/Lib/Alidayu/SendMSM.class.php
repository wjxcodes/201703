<?php
namespace Lib\Alidayu;

include('TopSdk.php');
// use Library\Lib\Alidayu\top\TopClient;
use TopClient;
use AlibabaAliqinFcSmsNumSendRequest;

class SendMSM extends TopClient {
	public function send($recNum,$smsParam,$smsTemplatiCode,$smsFreeSiqnName){
		$c = new TopClient;
		$c ->appkey = "23703175" ;
		$c ->secretKey = "c6812c08232f0bcb2ef874b83ae545ec";
		$req = new AlibabaAliqinFcSmsNumSendRequest;
		$req ->setExtend( "" );
		$req ->setSmsType( "normal" );
		$req ->setSmsFreeSignName( "$smsFreeSiqnName" );
		$req ->setSmsParam( "$smsParam" );
		$req ->setRecNum( "$recNum" );
		$req ->setSmsTemplateCode( "$smsTemplatiCode" );
		$resp = $c ->execute( $req );
		return $resp;
	}

}

?>