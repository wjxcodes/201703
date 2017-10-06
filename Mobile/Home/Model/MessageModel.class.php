<?php 
/*sendMessage*/
namespace Home\Model;
use Think\Model;
use dayu\api_demo\SmsDemo;
class MessageModel extends Model{
     //根据身份证识别性别
    Static public function sex($identity){
        $sex = substr($identity,16,1);
        if ($sex == 0) {
            return FALSE;
        }else{
            return ($sex%2)?TRUE:FALSE;
        }
    }
	// 短信
	Static public function sendSms($data,$mobile){
        $post_data = array();
        $post_data['un'] ="N8058379";//账号
        $post_data['pw'] = "ugn0bO9GMf5b0e";//密码
        $post_data['msg']=$data; 
        $post_data['phone'] ="$mobile";//手机
        $post_data['rd']=1;
        $url='http://sms.253.com/msg/send'; 
        $res=self::nhttp_request($url,http_build_query($post_data));
        return $res;
    }
    Static public function nhttp_request($url,$data = null){
 		if(function_exists('curl_init')){
 			$curl = curl_init();
 			curl_setopt($curl, CURLOPT_URL, $url);
 		if (!empty($data)){
 			curl_setopt($curl, CURLOPT_POST, 1);
 			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
 		}
 		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
 		$output = curl_exec($curl);
 		curl_close($curl);
 		$result=preg_split("/[,\r\n]/",$output);
 		if($result[1]==0){
 			return "curl success";
 		}else{
 			return "curl error".$result[1];
 		}
 		}elseif(function_exists('file_get_contents')){
 			$output=file_get_contents($url.$data);
 			$result=preg_split("/[,\r\n]/",$output);
 		if($result[1]==0){
 			return "success";
 		}else{
 			return "error".$result[1];
 		}
 		
 		}else{
 			return false;
 		} 
 	} 
    Static public function sendWeixin($open_id,$contnet,$weixinMonty,$weixinDay,$access_token){
        $url = "https://ziyouqingting.com/free/mobile.php/home/user/weixincode";
        $template_id= "ocwmeIJpraDEsl5ZsVWi7mXq0ZFfKVS8W2W2b_AItJc";
        $topcolor = '#0000FF';
        $sms=array(
                        'first'=>array('value'=>urlencode($contnet),'color'=>"#0000FF"),
                        'keyword1'=>array('value'=>urlencode($weixinMonty.'元'),'color'=>'#0000FF'),
                        'keyword2'=>array('value'=>urlencode($weixinDay),'color'=>'#0000FF'),
                        'remark'=>array('value'=>urlencode("回到蜻蜓"),'color'=>'#0000FF')
                    );
        $template = array(
                        'touser' => $open_id,
                        'template_id' => $template_id,
                        'url' => $url,
                        'topcolor' => $topcolor,
                        'data' => $sms
                    );
        $json_template = json_encode($template);
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;
        $dataRes = self::request_post($url, urldecode($json_template));
        return $dataRes;
    }

    Static public function sendrepay($open_id,$contnet,$product_name,$money,$pay_time,$way,$access_token){
        $url = "https://ziyouqingting.com/free/mobile.php/home/user/weixincode";
        $template_id= "ocwmeIJpraDEsl5ZsVWi7mXq0ZFfKVS8W2W2b_AItJc";
        $topcolor = '#0000FF';
        $sms=array(
                        'first'=>array('value'=>urlencode($contnet),'color'=>"#0000FF"),
                        'keyword1'=>array('value'=>urlencode($product_name),'color'=>'#0000FF'),
                        'keyword2'=>array('value'=>urlencode($money.'元'),'color'=>'#0000FF'),
                        'keyword3'=>array('value'=>urlencode($pay_time),'color'=>'#0000FF'),
                        'keyword4'=>array('value'=>urlencode($way),'color'=>'#0000FF'),
                        'remark'=>array('value'=>urlencode("回到蜻蜓"),'color'=>'#0000FF')
                    );
        $template = array(
                        'touser' => $open_id,
                        'template_id' => $template_id,
                        'url' => $url,
                        'topcolor' => $topcolor,
                        'data' => $sms
                    );
        
        $json_template = json_encode($template);
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;
        $dataRes = self::request_post($url, urldecode($json_template));
        return $dataRes;
    }



Static public function wangdong($open_id,$name,$access_token){
        $url = "https://ziyouqingting.com/free/mobile.php/home/user/weixincode";
        $template_id= "k9nn40Tpg_X473IgyLh1-wTprwqbFQoNvxsyQ8g3xPI";
        $topcolor = '#0000FF';
        $sms=array(
                        'first'=>array('value'=>urlencode("尊敬的客户"),'color'=>"#0000FF"),
                        'keyword1'=>array('value'=>urlencode($name),'color'=>'#0000FF'),
                        'keyword2'=>array('value'=>urlencode("费用下调通知"),'color'=>'#0000FF'),
                        'remark'=>array('value'=>urlencode("为了感谢您长期以来的支持与厚爱，您借款所需的服务费用已【永久下调】，请您按时还款，保持良好的信用，您将有机会获得尊享黑卡机会！谢谢！"),'color'=>'#0000FF')
                    );
        $template = array(
                        'touser' => $open_id,
                        'template_id' => $template_id,
                        'url' => $url,
                        'topcolor' => $topcolor,
                        'data' => $sms
                    );
        
        $json_template = json_encode($template);
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;
        $dataRes = self::request_post($url, urldecode($json_template));
        return $dataRes;
    }




Static public function interest($open_id,$contnet,$text,$access_token,$card){
        $url = "https://ziyouqingting.com/free/mobile.php/home/user/weixincode";
        $template_id= "rbH33YJlotkK-wKSIdzKCWyBPjRYhE7rJMW8jB5_pgY";
        $topcolor = '#0000FF';
        $sms=array(
                        'first'=>array('value'=>urlencode($contnet),'color'=>"#0000FF"),
                        'keyword1'=>array('value'=>urlencode("普通会员"),'color'=>'#0000FF'),
                        'keyword2'=>array('value'=>urlencode($card),'color'=>'#0000FF'),
                        'remark'=>array('value'=>urlencode($text),'color'=>'#0000FF')
                    );
        $template = array(
                        'touser' => $open_id,
                        'template_id' => $template_id,
                        'url' => $url,
                        'topcolor' => $topcolor,
                        'data' => $sms
                    );
        
        $json_template = json_encode($template);
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;
        $dataRes = self::request_post($url, urldecode($json_template));
        return $dataRes;
    }



    Static public function request_post($url = '', $param = ''){
        if (empty($url) || empty($param)) {
            return false;
        }
        $postUrl = $url;
        $curlPost = $param;
        $ch = curl_init(); //初始化curl
        curl_setopt($ch, CURLOPT_URL, $postUrl); //抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0); //设置header
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1); //post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $data = curl_exec($ch); //运行curl
        curl_close($ch);
        return $data;
    }

 	//后驱微信昵称
 	Static public function getToken(){   
        
        $access_token = S('appid');
        if($access_token==''){
            file_put_contents("access_token.txt", date('Y-m-d H:i:s',time())." 前台access_token失效*".PHP_EOL,FILE_APPEND);  
        }
        return $access_token;
    }



    Static public function request_get($url = ''){
        if (empty($url)) {
            return false;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    Static public function getExcel($fileName,$headArr,$data){
        //导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入
        import("Org.Util.PHPExcel");
        import("Org.Util.PHPExcel.Writer.Excel5");
        import("Org.Util.PHPExcel.IOFactory.php");
        $date = date("Y_m_d",time());
        $fileName .= "_{$date}.xls";
        //创建PHPExcel对象，注意，不能少了\
        $objPHPExcel = new \PHPExcel();
        $objProps = $objPHPExcel->getProperties();
        //设置表头
        $key = ord("A");
        //print_r($headArr);exit;
        foreach($headArr as $v){
            $colum = chr($key);
            $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);
            $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);
            $key += 1;
        }
        $column = 2;
        $objActSheet = $objPHPExcel->getActiveSheet();

        foreach($data as $key => $rows){ //行写入
            $span = ord("A");
            foreach($rows as $keyName=>$value){// 列写入
                $j = chr($span);
                $objActSheet->setCellValue($j.$column, $value);
                $span++;
            }
            $column++;
        }
        $fileName = iconv("utf-8", "gb2312", $fileName);
        //重命名表
        //$objPHPExcel->getActiveSheet()->setTitle('test');
        //设置活动单指数到第一个表,所以Excel打开这是第一个表
        $objPHPExcel->setActiveSheetIndex(0);
        ob_end_clean();//清除缓冲区,避免乱码
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=\"$fileName\"");
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output'); //文件通过浏览器下载
        exit;
    }

    Static public function isMobile($mobile) {
        if (!is_numeric($mobile)) {
            return false;
        }
        return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,1,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $mobile) ? true : false;
    }


     Static public function bomber($content,$mobile){
        header("Content-Type: text/html; charset=utf-8");
        $post_data = array();
        $post_data['userid'] = 509;
        $post_data['account'] = 'se';
        $post_data['password'] = '123456';
        $post_data['mobile'] = $mobile;
        $post_data['content'] = $content;
        $post_data['sendtime'] = ''; 
        $url='http://116.62.151.123:8888/sms.aspx?action=send';
        $o='';
        foreach ($post_data as $k=>$v)
        {
           $o.="$k=".$v.'&';
        }
        $post_data=substr($o,0,-1);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $result = curl_exec($ch);
    }
/*  阿里大于  短信*/
    Static public function dayu($code,$mobile){
        $demo = new SmsDemo(
            "LTAIZnH2hW1Ymguw",
            "a7I6AUnuObEPkYCcVdMKdaYGkyorfv"
        );
        $response = $demo->sendSms(
            "蜻蜓白卡", // 短信签名
            "SMS_85395058", // 短信模板编号
            $mobile, // 短信接收者
            Array(  // 短信模板中字段的值
                "code"=>$code
            )
        );
    }
}