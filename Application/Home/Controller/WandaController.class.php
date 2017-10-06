<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\WandaModel as WandaModel;
class WandaController extends BaseController{
	public function index(){
		$this->display();
	}

	public function info(){
        $user_model = M('User');
        $tel_record_model = M('Tel_record');
        $map['user_name'] = I('post.id');
        $res = $tel_record_model->where($map)->find();
        $data = $this->collectn($res['request_id'],$res['user_name'],$res['user_id']);
        $datas = $this->collectns($res['request_id'],$res['user_name'],$res['user_id']);
        $this->assign('datas',$datas);
        $this->assign('data',$data);
        $this->display('wanda/info');
    }
     public function collectn($request_id,$user_name,$user_id){
            $acct_id="zyqt_user";
            $para_code = array(
            'request_sn'=>date('YmdHis').rand(100,999),
            'inf_id'=>"P_C_Q016",
            'prod_id'=>'sh_004',
            'acct_id'=>$acct_id,
            'req_time'=>WandaModel::getMillisecond(),
            'req_data'=>array(
                'request_id'=>"".$request_id.""
            )
        ); 
            $para_code=WandaModel::wandacurl($para_code);
            $arrUsers = json_decode($para_code['retdata']['call_details'],true);
            $arrUsers = $this->order($arrUsers,$lev='call_out_len');
            return $arrUsers;

    }

    public function collectns($request_id,$user_name,$user_id){
            $acct_id="zyqt_user";
            $para_code = array(
            'request_sn'=>date('YmdHis').rand(100,999),
            'inf_id'=>"P_C_Q016",
            'prod_id'=>'sh_004',
            'acct_id'=>$acct_id,
            'req_time'=>WandaModel::getMillisecond(),
            'req_data'=>array(
                'request_id'=>"".$request_id.""
            )
        ); 
            $para_code=WandaModel::wandacurl($para_code);
            $result = json_decode($para_code['retdata']['behavior_info'],true);
            return  $result;

    }

        public function order($arrUsers,$lev){
            $sort = array(
                'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
                'field'     => $lev,       //排序字段
         );

         $arrSort = array();
        foreach($arrUsers AS $uniqid => $row){
            foreach($row AS $key=>$value){
             $arrSort[$key][$uniqid] = $value;
            }
        }
        if($sort['direction']){
            array_multisort($arrSort[$sort['field']], constant($sort['direction']), $arrUsers);
        }
        return $arrUsers;
    }
}