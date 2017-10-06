<?php
/*
Apix  返回  请求   new
*/
namespace Home\Controller;
use Think\Controller;
use Home\Model\ApixModel as ApixModel;
header("content-type:text/html;charset=utf8");
class ApixController extends Controller {
/*  通话异步回调  */
	public function tel_callback_url(){
	    $get=I('get.');
	    $apix=M('apix');
	    $save['user_name']=$get['id'];
	    $save['token']=$get['token'];
	    $save['state']=1;
	    $save['create_time']=time();
	    $res=$apix->add($save);
	    if($res){

	    }else{
	    	file_put_contents("tel_apix_callback.txt", "apix表插入失败:手机号:".$get['id']."token:".$get['token']."时间:".date("Y-m-d H:i:s",time())."*".PHP_EOL,FILE_APPEND);  
	    }
    }

/*  通话主动回调*/

    public function tel_success_url(){
      	if(empty($_SESSION['name'])){
            $this->redirect('user/login');
        }
       	$get=I('get.');
       	$md5=md5('ajax'.$_SESSION['name']);
       	if($get['id']==$md5){
          	$tel_record=M('tel_record');
          	$where['user_name']=$_SESSION['name'];

          	$tel_record_data=$tel_record->where($where)->find();

          	if($tel_record_data){
             	$save['is_collect']=2;
             	$save['create_time']=time();
             	$res=$tel_record->where($where)->save($save);
          	}else{
             	$save['user_name']=$_SESSION['name'];
             	$save['is_collect']=2;
             	$save['create_time']=time();
             	$res=$tel_record->where($where)->add($save);
          	}

          	if($res){
                die("<script>window.location='/free/mobile.php/home/info/index'</script>");
          	}else{
              	die("<script>window.location='/free/mobile.php/home/info/index'</script>");
          	}
       	}
    }

    public function tel_request(){
    	$apix=M('apix');
    	$tel_record=M('tel_record');

    	$tel_where['is_collect']=2;
    	$time=time()-3600;
    	$tel_where['create_time']=array('lt',$time);
    	$tel_record_data=$tel_record->where($tel_where)->select();
    	if($tel_record_data){
    		foreach ($tel_record_data as $key => $value) {
    			var_dump($value['user_name']);
    			var_dump(date('Y-m-d H:i:s',$value['create_time']));
    		}
    	}

    	
    	$where['state']=1;
    	$apix_data=$apix->field('id,user_name,token,create_time')->where($where)->select();

		foreach ($apix_data as $key => $value) {
			$return_data=ApixModel::apix_data($value['token']);
			if($return_data!="错误" && $return_data!='' && $return_data['errorCode']==''){
				if($return_data['callRecordsInfo']==null){
					$apix_fail_where['id']=$value['id'];
					$apix_fail_save['state']=3;
					$apix->where($apix_fail_where)->save($apix_fail_save);
					$tel_record_fail_where['user_name']=$value['user_name'];
					$tel_record_fail_save['is_collect']=3;
					$tel_record->where($tel_record_fail_where)->save($tel_record_fail_save);
				}else{
						foreach ($return_data['callRecordsInfo'] as $k => $v) {
			            	if($key<100){
			                   $callRecordsInfo[$k]=$v;
			            	}
			            }
			            $save['basic']=json_encode($return_data['basicInfo']);
			            $save['phone']=json_encode($return_data['phoneInfo']);
			            $save['decei']=json_encode($return_data['deceitRisk']);
			            $save['consume']=json_encode($return_data['consumeInfo']);
			            $save['callrecords']=json_encode($callRecordsInfo);
			            $save['contactarea']=json_encode($return_data['contactAreaInfo']);
			            $save['specialcall']=json_encode($return_data['specialCallInfo']);
			            $save['phoneoff']=json_encode($return_data['phoneOffInfos']);
			            $save['state']=2;

			            $apix_where['id']=$value['id'];

			            $res=$apix->where($apix_where)->save($save);

			            if($res){
			            	
		          			$tel_record_where['user_name']=$value['user_name'];
		          			$tel_record_save['is_collect']=1;
			            	$tel_record_res=$tel_record->where($tel_record_where)->save($tel_record_save);
			            	if($tel_record_res){

			            	}else{
			            		file_put_contents("tel_apix_callback.txt", "tel_record表更改状态失败:手机号:".$value['user_name']."token:".$value['token']."时间:".date("Y-m-d H:i:s",time())."*".PHP_EOL,FILE_APPEND);  
			            	}
			            }else{
			            	file_put_contents("tel_apix_callback.txt", "查询内容插入失败:手机号:".$value['user_name']."token:".$value['token']."时间:".date("Y-m-d H:i:s",time())."*".PHP_EOL,FILE_APPEND);  
			            }
				}

	        }else{
	        	$hour=time()-$value['create_time'];
	        	if($hour>3600){
	        		$apix_fail_where['id']=$value['id'];
					$apix_fail_save['state']=3;
					$apix->where($apix_fail_where)->save($apix_fail_save);
					$tel_record_fail_where['user_name']=$value['user_name'];
					$tel_record_fail_save['is_collect']=3;
					$tel_record->where($tel_record_fail_where)->save($tel_record_fail_save);
	        	}
	        }

		}
    	



    }

}