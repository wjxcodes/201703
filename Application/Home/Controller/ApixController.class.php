<?php
// APIX   通话查询
namespace Home\Controller;
use Think\Controller;
use Home\Model\ApixModel as ApixModel;
header('content-type:text/html;charset=utf-8');
class ApixController extends Controller {

//////////////////////////////旧

	public function index(){
		$tel_record=M('tel_record');
		$get=I('get.');
		$where['user_name']=$get['id'];
		$tel_record_data=$tel_record->where($where)->find();
		if($tel_record_data){
        	if($tel_record_data['request_id']){
        		$apix=M('apix');
        		$apix_data=$apix->where($where)->find();
        		if($apix_data){
                	$apix_data['basicInfo']=json_decode($apix_data['basic'],1);
                	$apix_data['phoneInfo']=json_decode($apix_data['phone'],1);
                	$apix_data['deceitRisk']=json_decode($apix_data['decei'],1);
                	$apix_data['consumeInfo']=json_decode($apix_data['consume'],1);
                	$apix_data['callRecordsInfo']=json_decode($apix_data['callrecords'],1);
                	$apix_data['contactAreaInfo']=json_decode($apix_data['contactarea'],1);
                	$apix_data['specialCallInfo']=json_decode($apix_data['specialcall'],1);
                	$apix_data['phoneOffInfos']=json_decode($apix_data['phoneoff'],1);
                	$this->assign("hint","数据库读出数据");
                    $this->assign("apix_data",$apix_data);
        		}else{
        			
        		}

            	
        	}else{
        		die("<script>alert('还未获取到信息，请等待');history.back();</script>");
        	}
		}else{
			die("<script>alert('该用户未进行运营商认证！');history.back();</script>");
		}
		$this->display();
	}
//////////////////////////////旧
    public function tel(){
        $get=I('get.');
        $user_name=$get['id'];
        $apix_data=D('apix')->find_username($user_name);
        if($apix_data){

            $apix_data['basicInfo']=json_decode($apix_data['basic'],1);
            $apix_data['phoneInfo']=json_decode($apix_data['phone'],1);
            $apix_data['deceitRisk']=json_decode($apix_data['decei'],1);
            $apix_data['consumeInfo']=json_decode($apix_data['consume'],1);
            $apix_data['callRecordsInfo']=json_decode($apix_data['callrecords'],1);
            $apix_data['contactAreaInfo']=json_decode($apix_data['contactarea'],1);
            $apix_data['specialCallInfo']=json_decode($apix_data['specialcall'],1);
            $apix_data['phoneOffInfos']=json_decode($apix_data['phoneoff'],1);
            $this->assign("apix_data",$apix_data);
            $this->display('apix/tel');

        }else{

            $wdinfo_data=D('apix')->wanda_find($user_name);
            $accounts = json_decode($wdinfo_data['accounts'],true);
            $casic = json_decode($wdinfo_data['casic'],true);
            $tel_info = json_decode($wdinfo_data['tel_info'],true);

            $tel_info = D('apix','Logic')->rank_order($tel_info,$lev='call_len');
            $this->assign('result',$tel_info);
            $this->assign('accounts',$accounts[0]['behavior']);
            $this->assign('casic',$casic);
            $this->display('apix/wanda');

        }
        
    }

    public function taobao(){
        $get=I('get.');
        $user_name=$get['id'];
        $taobao_info_data=D('apix')->taobao_find($user_name);

        if($taobao_info_data){
            $accountSafeInfo=json_decode($taobao_info_data['accountsafeinfo'],1);
            $addrs=json_decode($taobao_info_data['addrs'],1);
            $bindAccountInfo=json_decode($taobao_info_data['bindaccountinfo'],1);
            $personalInfo=json_decode($taobao_info_data['personalinfo'],1);
            $orderlist=json_decode($taobao_info_data['orderlist'],1);

            $this->mytime=$taobao_info_data['create_time'];
            $this->arr=$orderlist;
            $this->lost_money=$taobao_info_data['money'];
            $this->personalInfo=$personalInfo;
            $this->accountSafeInfo=$accountSafeInfo;
            $this->addrs=$addrs;
            $this->bindAccountInfo=$bindAccountInfo;
            $hint="从数据库读出信息";
        }else{
            die("<script>alert('没有淘宝信息！');history.back();</script>");
        }
        $this->display('apix/taobao');
    }


    public function jd(){

        $get=I('get.');
        $user_name=$get['id'];

        $jd_data=D('apix')->jd_find($user_name);

        if($jd_data['token']){
            $response=D('apix','Logic')->jd_curl($jd_data['token']);

            foreach ($response['consumeHistroy']['record'] as $key => $value) {
                $all_money+=1;
            }
            $this->all_money=$all_money;
            $this->response=$response;
        }else{
            die("<script>alert('还未获取到信息！');history.back();</script>");
        }
        $this->display('apix/jd');
    }


}
