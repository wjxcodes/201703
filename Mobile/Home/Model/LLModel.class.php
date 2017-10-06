<?php 

namespace Home\Model;
use Think\Model;
class LLModel extends Model{


      static public function card_config(){
            $llpay_config['oid_partner'] = '201706141001821167';

      //秘钥格式注意不能修改（左对齐，右边有回车符）
$llpay_config['RSA_PRIVATE_KEY'] ='-----BEGIN RSA PRIVATE KEY-----
MIICXAIBAAKBgQC6tJ/VfJtMxUDDrXEQJBTFDhUSSqeJcZXsKlzcN5Z/f80A44AC
I/AL1jXkhGwkKBOBTFruDTq4kMa1JKigQCziYw0V1G50tXnV+f36PoC+j2kdiDOT
+/IPvA/KwpKzExI+6TPOHpUqXj9Sybnx14+NhjxncX5F6nMvjD+lnFRKMwIDAQAB
AoGARmjVmgIK4xHEUcb3y3l/56xe3+4NbnTKcCytplLES8LbgclJvgTlguE+o7YY
vi3q5SjxZbLRgUb/2NJBUWXCRtziMb+7mgTE1SVAvZrM7viBSyNu0a9T3Yc+GzYC
pSwOTWA0Dw4M5ZiX/s34eFcHGySeJJSjWBTQNj04RcnuOyECQQDc775BePS3rqKB
Cm4DBxVQg8Jrfup0Bg9QB96ZrYiI9P2rasQTdD5C5D8NzUGTb175uYaU4tpuH9G6
w7UN8QexAkEA2FYjplXX0vbxsqBXwK9Zc+0zXvonRCSsFdsBUfIcpF36YGB2G7O4
f3CWMclNV13M4AE5ZQDMHVQh5xX22hBNIwJAGJpR83SGu9Wknv4MViX5x6eEhPfz
H8x09BKMRUy/wZCWlvoir4/oRwanxt+uh76FMwXn6LiCXnUIo+WbSdwnYQJBAIis
gwFHIn8JvFEZs9br8RuoM9hBOiV29bEpF4Bp8WZ2aQQSbQu7U0hQHNN/VfloLVMn
8ta41juBN5oC6l2CBvkCQF+XqG7P976dALwBmu0bULeoKChViD/zIFGcHJhMQ5sj
JuiPnH2mIt7/9DdD27YZuWw4ZE+kc0bWuwro2lFqQ7c=
-----END RSA PRIVATE KEY-----'; 


      //安全检验码，以数字和字母组成的字符
      $llpay_config['key'] = '201708071000001539_qingtingkeji_2017061605';

      //↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑

      //版本号
      $llpay_config['version'] = '1.1';

      //请求应用标识 为wap版本，不需修改
      $llpay_config['app_request'] = '3';

      //签名方式 不需修改
      $llpay_config['sign_type'] = strtoupper('RSA');

      //字符编码格式 目前支持 gbk 或 utf-8
      $llpay_config['input_charset'] = strtolower('utf-8');

      //访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
      $llpay_config['transport'] = 'https';
      return $llpay_config;
      }



	static public function llconfig(){
		$llpay_config['oid_partner'] = '201706141001821167';

      //秘钥格式注意不能修改（左对齐，右边有回车符）
$llpay_config['RSA_PRIVATE_KEY'] ='-----BEGIN RSA PRIVATE KEY-----
MIICXAIBAAKBgQC6tJ/VfJtMxUDDrXEQJBTFDhUSSqeJcZXsKlzcN5Z/f80A44AC
I/AL1jXkhGwkKBOBTFruDTq4kMa1JKigQCziYw0V1G50tXnV+f36PoC+j2kdiDOT
+/IPvA/KwpKzExI+6TPOHpUqXj9Sybnx14+NhjxncX5F6nMvjD+lnFRKMwIDAQAB
AoGARmjVmgIK4xHEUcb3y3l/56xe3+4NbnTKcCytplLES8LbgclJvgTlguE+o7YY
vi3q5SjxZbLRgUb/2NJBUWXCRtziMb+7mgTE1SVAvZrM7viBSyNu0a9T3Yc+GzYC
pSwOTWA0Dw4M5ZiX/s34eFcHGySeJJSjWBTQNj04RcnuOyECQQDc775BePS3rqKB
Cm4DBxVQg8Jrfup0Bg9QB96ZrYiI9P2rasQTdD5C5D8NzUGTb175uYaU4tpuH9G6
w7UN8QexAkEA2FYjplXX0vbxsqBXwK9Zc+0zXvonRCSsFdsBUfIcpF36YGB2G7O4
f3CWMclNV13M4AE5ZQDMHVQh5xX22hBNIwJAGJpR83SGu9Wknv4MViX5x6eEhPfz
H8x09BKMRUy/wZCWlvoir4/oRwanxt+uh76FMwXn6LiCXnUIo+WbSdwnYQJBAIis
gwFHIn8JvFEZs9br8RuoM9hBOiV29bEpF4Bp8WZ2aQQSbQu7U0hQHNN/VfloLVMn
8ta41juBN5oC6l2CBvkCQF+XqG7P976dALwBmu0bULeoKChViD/zIFGcHJhMQ5sj
JuiPnH2mIt7/9DdD27YZuWw4ZE+kc0bWuwro2lFqQ7c=
-----END RSA PRIVATE KEY-----'; 


      //安全检验码，以数字和字母组成的字符
      $llpay_config['key'] = '201708071000001539_qingtingkeji_2017061605';

      //↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑

      //版本号
      $llpay_config['version'] = '1.0';

      //请求应用标识 为wap版本，不需修改
      $llpay_config['app_request'] = '3';


      //签名方式 不需修改
      $llpay_config['sign_type'] = strtoupper('RSA');

      //订单有效时间  分钟为单位，默认为10080分钟（7天） 
      $llpay_config['valid_order'] ="10080";

      //字符编码格式 目前支持 gbk 或 utf-8
      $llpay_config['input_charset'] = strtolower('utf-8');

      //访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
      $llpay_config['transport'] = 'https';
      return $llpay_config;
  	}
}