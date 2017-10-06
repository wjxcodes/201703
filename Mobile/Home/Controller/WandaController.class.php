<?php
/*
万达认证界面
*/
namespace Home\Controller;
use Think\Controller;
use Home\Model\WandaModel as WandaModel;
use Home\Model\MoneyModel as MoneyModel;
header("content-type:text/html;charset=utf8");
class WandaController extends Controller {
      public function index(){
      	if (empty($_SESSION['name'])) {
	      $this->redirect('user/login');
	    }
        $acct_id="zs-zyqt_user";

	    $tel_record=M('tel_record');
	    $user=M('user');
      	$where['user_name']=$_SESSION['name'];
        $data=$tel_record->where($where)->find();
        $this->tel_num=$_SESSION['name'];
        if($data['is_collect']==1){
          die("<script>alert('您已进行过认证');history.back();</script>");
        }

        if($_POST){
               $post=I('post.');
               $user_data=$user->where($where)->find();
	           $para = array(
			        'request_sn'=>date('YmdHis').rand(100,999),
		            'inf_id'=>'P_C_B252',
		            'prod_id'=>'sh_004',
		            'acct_id'=>"".$acct_id."",
		            'req_time'=>WandaModel::getMillisecond(),
		            'req_data'=>array(
		                'name'=>"".$user_data['u_name']."",
		                'cardNo'=>"".$user_data['identity']."",
		                'mobileNo'=>"".$_SESSION['name']."",
		                'ignoreMobile'=>0,
		                'otherInfo'=>'{}',
		                'websiteInfos'=>'[]',
		                'contacts'=>'[]')
		            );

	            $para=WandaModel::wandacurl($para);
	    
	           $next_datasource=json_decode($para['retdata']['next_datasource'],1);

	           if($para['retcode']=="000000"){
	    /*  数据插入记录表  */
		          $save['user_id']=$user_data['user_id'];
		          $save['request_id']=$para['retdata']['request_id'];
		          $save['user_tel']=$_SESSION['name'];
		          $save['user_name']=$user_data['user_name'];
		          $save['password']=$post['fw_password'];
		          $save['create_time']=time();
		          $save['website_en_name']=$next_datasource['website_en_name'];
            $res=$tel_record->where($where)->select();
	               if($res){
	               	$tel=$tel_record->where($where)->save($save);
	               }else{
	               	$tel=$tel_record->where($where)->add($save);
	               }

	        /*  第二次  请求验证码  拼接数组*/       
	               $para_code = array(
			        'request_sn'=>date('YmdHis').rand(100,999),
		            'inf_id'=>'P_C_B253',
		            'prod_id'=>'sh_004',
		            'acct_id'=>$acct_id,
		            'req_time'=>WandaModel::getMillisecond(),
		            'req_data'=>array(
		                'request_id'=>"".$para['retdata']['request_id']."",
		                'account'=>"".$post['mobile']."",
		                'password'=>"".$post['fw_password']."",
		                'website_en_name'=>"".$next_datasource['website_en_name']."",
		                'captcha'=>'',
		                'captcha_type'=>''
		            )
		            );
            /*  第二次  请求验证码 */
            if($next_datasource['website_en_name']=='chinaunicom'){
	               $para_code=WandaModel::wandacurl($para_code);      
	        }else{
	        	   $para_code=WandaModel::wandacurl($para_code);
	        }

	               if($para_code['retcode']=='000000'){
                      $save['is_collect']=1;
                      $res=$tel_record->where($where)->save($save);
                      if($res){
                      /*	$user_save['message']=1;

                      	$user->where($where)->save($user_save);*/
                      	//$xiane=MoneyModel::xiane(0);
                      	die("<script>alert('恭喜您！通过通过服务商认证！');window.location.href='/free/mobile.php/home/info/index';</script>");
	                  }else{
	                    die("<script>alert('出错了！');window.location.href='index';</script>");
	                  }
	               }
	               if($para_code['retcode']=='warn_105'){
                     die("<script>window.location.href='ver_code';</script>");
	               }
	               if($para_code['retcode']=='err_000'){
		               die("<script>alert('".$para_code['retmsg']."');window.location.href='index'</script>");
			        }
			       die("<script>alert('".$para_code['retmsg']."');window.location.href='index'</script>");
	            }else{
	             die("<script>alert('".$para['retmsg']."');history.back();</script>");
	            }

        }
      	$this->display('wanda/index');
      }
      public function ver_code(){
      	$where['user_name']=$_SESSION['name'];
        $tel_record=M('tel_record');
        $user=M('user');
        $acct_id="zs-zyqt_user";
        $data=$tel_record->where($where)->find();
        $user_data=$user->where($where)->find();
      	 if($_POST){
           $post=I('post.');
           $para_code = array(
			        'request_sn'=>date('YmdHis').rand(100,999),
		            'inf_id'=>'P_C_B253',
		            'prod_id'=>'sh_004',
		            'acct_id'=>$acct_id,
		            'req_time'=>WandaModel::getMillisecond(),
		            'req_data'=>array(
		                'request_id'=>"".$data['request_id']."",
		                'account'=>"".$data['user_tel']."",
		                'password'=>"".$data['password']."",
		                'website_en_name'=>"".$data['website_en_name']."",
		                'captcha'=>"".$post['code']."",
		                'captcha_type'=>'SUBMIT'
		            )
		            );
            $para_code=WandaModel::wandacurl($para_code);
        
            if($para_code['retcode']=='000000'){
                      $save['is_collect']=1;
                      $res=$tel_record->where($where)->save($save);
                      if($res){
                      	/*$user_save['message']=1;
                      	$user->where($where)->save($user_save);*/
                      	//$xiane=MoneyModel::xiane(0);
	                    die("<script>alert('恭喜您！通过服务商认证！');window.location.href='/free/mobile.php/home/info/index';</script>");
	                  }else{
	                    die("<script>alert('出错了！');window.location.href='index';</script>");
	                  }
	        }
	        if($para_code['retcode']=='warn_105'){
                     die("<script>window.location.href='ver_code';</script>");
	        }
	        if($para_code['retcode']=='err_000'){
               die("<script>alert('".$para_code['retmsg']."');window.location.href='index'</script>");
	        }
	        die("<script>alert('".$para_code['retmsg']."');window.location.href='index'</script>");
      	 }
      	$this->display('wanda/ver_code');
      }



      public function collect(){
      	$acct_id="zs-zyqt_user";
      	if($_POST){
      		$post=I('post.');
		      	    $para_code = array(
				        'request_sn'=>date('YmdHis').rand(100,999),
			            'inf_id'=>"".$post['inf_id']."",
			            'prod_id'=>'sh_004',
			            'acct_id'=>$acct_id,
			            'req_time'=>WandaModel::getMillisecond(),
			            'req_data'=>array(
			                'request_id'=>"".$post['request_id'].""
			            )
			            ); 
		      	    var_dump($post);
		      	    var_dump($para_code);
              $para_code=WandaModel::wandacurl($para_code);
              var_dump($para_code);
        }
        $this->display('wanda/collect');
      }

    public function photo(){
      	$where_user['user_name'] = session('name');
      	$user_model = M('User');
    	$user_data = $user_model->where($where_user)->find();
      	if ($_FILES) {
  			$upload = new \Think\Upload();// 实例化上传类
			$upload->maxSize   =     20971520;// 设置附件上传大小
			$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
			$upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
			$upload->savePath  =     ''; // 设置附件上传（子）目录
			// 上传文件 
			$info   =   $upload->upload();
			$identity_reverse= $info['identity_reverse']['savepath'].$info['identity_reverse']['savename'];
			$self_portrait= $info['self_portrait']['savepath'].$info['self_portrait']['savename'];
			$identity_front= $info['identity_front']['savepath'].$info['identity_front']['savename'];
			if(!$info) {
				die('<script>alert("上传图片失败");history.back()</script>');
			}else{// 上传成功
				if($identity_reverse!=''){
					unlink("./Uploads/".$user_data["identity_reverse"]."");
					$path['identity_reverse']=$identity_reverse;
				}
				if($self_portrait!=''){
					unlink("./Uploads/".$user_data["self_portrait"]."");
					$path['self_portrait']=$self_portrait;
				}
				if($identity_front!=''){
					unlink("./Uploads/".$user_data["identity_front"]."");
					$path['identity_front']=$identity_front;
				}
    			$res=$user_model->where($where_user)->save($path);
    				if($res){
    				 	foreach ($info as $key => $value) {
                        $path="./Uploads/".$value['savepath'].$value['savename'];
                        $mini="./Uploads/".$value['savepath'].$value['savename'];
					     	$image=new \Think\Image();
						    $image->open($path);
						    $image->thumb(1000,1000)->save($mini);
					    }
					    if($user_data['conven']==0){
					    	$this->redirect('info/photo');
					    }else{
	    				 	if($user_data['message']==1){
		    					$this->redirect('borrow/index');
		    				}else{
		    					$this->redirect('info/index');
		    				}
	    				}
	    			}else{
	    				die('<script>alert("上传图片失败");history.back()</script>');
	    			}    
      		}
      	}	
   } 
}
