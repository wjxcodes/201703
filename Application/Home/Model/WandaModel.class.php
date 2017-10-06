<?php
namespace Home\Model;
use Think\Model;
class WandaModel extends Model{
    static public function wandacurl($para){
    /*  万达url acct_id */
         $url = "http://credit.wanda.cn/credit-gw/service";
         $acct_id='zyqt_user';


    /*  将数组加密  */
    $str=self::encrypt(json_encode($para,JSON_UNESCAPED_UNICODE));


    /*  传输的 header 头 */
        $headerArr = array(
            'Content-Type: application/json; charset=utf-8',
            "X_WANDA_ACCT_ID:".$acct_id."",
            'Accept:application/json',
            'Content-Length: ' . strlen($str)
        );

    
    /*  curl post 传递 */
			$curl = curl_init(); // 启动一个CURL会话
			curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
			curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
			curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
			//curl_setopt($curl, CURLOPT_VERBOSE, 1); //debug模式
			curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
			curl_setopt($curl, CURLOPT_POSTFIELDS, $str); // Post提交的数据包
			curl_setopt($curl, CURLOPT_TIMEOUT, 10); // 设置超时限制防止死循环
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headerArr);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
			if(defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')){
			            curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
			}
			$tmpInfo = curl_exec($curl); // 执行操作
			$times = 3;
			for($i=0;$i<$times;$i++){
			     if (!curl_errno($curl)){
			         break;
			     }else{
			         $tmpInfo = curl_exec($curl);
			     }
			}
			if (curl_errno($curl)) {
			    throw new Exception(curl_error($curl),0);
			}
			curl_close($curl); // 关闭CURL会话
	        $tmpInfo=self::decrypt($tmpInfo);
	        $tmpInfo = json_decode($tmpInfo,1);
      
        return $tmpInfo;
    }

    /*   req_time 计算*/
    function getMillisecond() {
        list($t1, $t2) = explode(' ', microtime());
        return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
    }

    /*   加密  */
    static public function encrypt($str){
        //AES, 128 ECB模式加密数据
        //$screct_key = $this->_secrect_key;
    /*  AESkey密钥*/    
	  $secrect_key="dde07029dfa3a96330c424f8e8925feb";

        $screct_key = hex2bin($secrect_key);
        $str = trim($str);
        $str = self::addPKCS7Padding($str);
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128,MCRYPT_MODE_CBC),MCRYPT_RAND);
        $encrypt_str =  mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $screct_key, $str, MCRYPT_MODE_CBC, '0000000000000000');
        return base64_encode($encrypt_str);
    }

    /* 加密填充算法  */
	static public function addPKCS7Padding($source){
	        $source = trim($source);
	        $block = mcrypt_get_block_size('rijndael-128', 'cbc');
	        $pad = $block - (strlen($source) % $block);
	        if ($pad <= $block) {
	            $char = chr($pad);
	            $source .= str_repeat($char, $pad);
	        }
	        return $source;
	}

    /*  解密 */
    function decrypt($str){
        //AES, 128 ECB模式加密数据
    /*  AESkey密钥*/ 
	    $secrect_key="dde07029dfa3a96330c424f8e8925feb";
        $screct_key = hex2bin($secrect_key);
        $str = base64_decode($str);
        //$screct_key = base64_decode($screct_key);
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128,MCRYPT_MODE_CBC),MCRYPT_RAND);
        $encrypt_str =  mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $screct_key, $str, MCRYPT_MODE_CBC, '0000000000000000');
        $encrypt_str = self::stripPKSC7Padding($encrypt_str);
        return $encrypt_str;
    }

    /*  解密填充算法  */
	function stripPKSC7Padding($source){
	        $char = substr($source, -1);
	        $num = ord($char);
	        $source = substr($source,0,-$num);
	        return $source;
    }
}