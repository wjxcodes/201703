<?php 
namespace Home\Model;
use Think\Model;
class LocationModel extends Model{
  Static public function getSignPackage() {
      $jsapiTicket = self::getJsApiTicket();
      // 注意 URL 一定要动态获取，不能 hardcode.
      $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
      $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
      $timestamp = time();
      $nonceStr = self::createNonceStr();
      // 这里参数的顺序要按照 key 值 ASCII 码升序排序
      $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
      $signature = sha1($string);
      $signPackage = array(
                  "appId"     => "wx77c3255a41d184ad",
                  "nonceStr"  => $nonceStr,
                  "timestamp" => $timestamp,
                  "url"       => $url,
                  "signature" => $signature,
                  "rawString" => $string
                );
      return $signPackage; 
    }

    Static public function createNonceStr($length = 16) {
      $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
      $str = "";
      for ($i = 0; $i < $length; $i++) {
          $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
      }
      return $str;
    }

    Static public function getJsApiTicket() {
      // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
      if (S($ticket)) {
        $ticket = $ticket;
      }else{
        $accessToken = self::getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
          $res = json_decode(self::httpGet($url));
          $ticket = $res->ticket;
          if ($ticket) {
            S($ticket,json_encode($data),time()+7000);
          }
      }
      return $ticket;
  }

    Static public function getAccessToken(){   
        $access_token = S('appid');
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
    Static public function httpGet($url) {
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_TIMEOUT, 500);
      // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
      // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
      curl_setopt($curl, CURLOPT_URL, $url);
      $res = curl_exec($curl);
      curl_close($curl);
      return $res;
    }
}