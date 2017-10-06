<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\WeixinModel as WeixinModel;
header("content-type:text/html;charset=utf8");
class WeixinController extends Controller {
	public function index(){
		if($_GET){
			$get=I('get.');
			var_dump($get);
			if($get['password']=="请求一次微信"){
				$a=WeixinModel::getnewToken();
				$b=WeixinModel::getToken();
				var_dump($a);
				echo "<br/>";
				
					file_put_contents("access_token.txt", date('Y-m-d H:i:s',time())." 更换token*".PHP_EOL,FILE_APPEND); 
				
				
				var_dump($b);
			}
		}
	}
}