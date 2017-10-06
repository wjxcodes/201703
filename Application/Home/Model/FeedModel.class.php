<?php
namespace Home\Model;
use Think\Model;
class FeedModel extends Model{

//创建  一条  不通过记录	
        public function create_record(){
	       $feed=M('feed');
	       $save['user_id']=$user_info['user_id'];
                $save['u_name']=$user_info['u_name'];
                $save['identity']=$user_info['identity'];
                $save['loan_order']=$loan_info['loan_order'];
                $save['loan_amount']=$loan_info['loan_amount'];
                $save['reject_state']=1;
                $save['create_time']=time();
                $data=$feed->add($save);
                return $data;
	}

        public function user_select($user_id){
                $feed=M('feed');
                $where['user_id']=$user_id;
                $data=$feed->where($where)->select();
                return $data;
        }

}