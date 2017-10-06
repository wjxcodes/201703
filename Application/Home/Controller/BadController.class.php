<?php
namespace Home\Controller;
use phpDocumentor\Reflection\Types\Null_;
use Think\Controller;
use Home\Model\RepayModel as RepayModel;
use Home\Model\MoneyModel as MoneyModel;
class BadController extends Controller{
	protected $user_model;
    protected $loan_model;
    protected $auth_model;
    protected $task_model;
    protected $apix_model;
    protected $wdinfo_model;
    protected $taobao_model;
	protected $jd_info_model;
	public function __construct(){
		parent::__construct();
		$this->user_model = M('User');
        $this->loan_model = M('Loan');
        $this->auth_model = M('Auth');
        $this->task_model = M('Task');
        $this->apix_model = M('Apix');
        $this->wdinfo_model = M('Wdinfo');
        $this->taobao_model = M('Taobao_info');
        $this->jd_info_model = M('jd_info');
        $base_where['username']=session('aname');
        $base_data=$this->auth_model->field('auth')->where($base_where)->find();
        $base_auth=explode('-', $base_data['auth']);
        $this->base_auth=$base_auth;
	}
    public function index(){
        if(I('get.')['code'] == '17633713110'){
            $loan_info = M()
                     ->table('free_user user,free_loan loan')
                     ->where("user.user_id=loan.user_id and loan.is_pay>0")
                     ->field('user.user_name,user.bank_name,user.identity,user.bank_card,loan.loan_amount,loan.is_pays,loan.is_pay,loan.loan_time,loan.renewal_days,loan.interest,loan.cuts_interest,loan.loan_order,user.linkman_tel,user.clan_tel,user.linkman_name,user.clan_name')
                     ->order('is_pays asc')
                     ->select();
            foreach ($loan_info as $k => $v) {
            if($v['loan_time']==1){
                    $loan_time=7;
                }elseif($v['loan_time']==2){
                    $loan_time=14;
                }
            $return_arr=RepayModel::overdue_show($v['is_pay'],$loan_time,$v['renewal_days'],$v['loan_amount']);
            $shouxufei=MoneyModel::poundage($v['loan_amount'],$v['interest'],$v['cuts_interest']);
            if($return_arr['day'] == 5){
                $data_add[$k]['user_name'] = $v['user_name'];
                $data_add[$k]['loan_amount'] = $v['loan_amount'];
                $data_add[$k]['pay_time'] = date('Y-m-d H',$v['is_pay']);
                $data_add[$k]['link_tel'] =  $v['linkman_tel']."-". $v['clan_tel'];
                $data_add[$k]['link_name'] = $v['linkman_name']."-". $v['clan_name'];
                $data_add[$k]['create'] = date('Y-m-d H',time());
                $data_add[$k]['money'] = $v['loan_amount']+$shouxufei;
                $data_add[$k]['loan_day'] = $loan_time;
                $data_add[$k]['no_order'] = $v['loan_order'];
                $data_add[$k]['u_name'] = $v['bank_name'];
            }
            }
            $return_g = $this->task_model->order('id desc')->find();
            $i = $return_g['group']+1;
            foreach ($data_add as $k => $v) {
                if($i>6){
                   $i=1;
                }elseif($i==5){
                    $i=6;
                }
                $add['user_name'] = $v['user_name'];
                $add['loan_amount'] = $v['loan_amount'];
                $add['pay_time'] = $v['pay_time'];
                $add['link_tel'] =  $v['link_tel'];
                $add['link_name'] = $v['link_name'];
                $add['create'] = $v['create'];
                $add['money'] = $v['money'];
                $add['loan_day'] = $v['loan_day'];
                $add['no_order'] = $v['no_order'];
                $add['u_name'] = $v['u_name'];
                $add['group'] = $i;
                $i = $i+1;
                $this->task_model->add($add);
            }
        }  
    }

    public function group(){
        $nickname['username'] = session('aname');
        $return_nick = $this->auth_model->where($nickname)->find();
        $map['group'] = $return_nick['nickname'];
        $result = $this->task_model->where($map)->order('id desc')->select();
        $where_count['complete'] = date('Y-m-d',time());
        $where_count['group'] = $return_nick['nickname'];
        $count_num = $this->task_model->where($where_count)->count();
        foreach ($result as $k => $v) {
            $where['user_name'] = $v['user_name'];
            $res = $this->loan_model->where($where)->find();
            if($res['loan_time']==1){
                $loan_time=7;
            }elseif($res['loan_time']==2){
                $loan_time=14;
            }
            $return_arr=RepayModel::overdue_show($res['is_pay'],$loan_time,$res['renewal_days'],$res['loan_amount']);
            $shouxufei=MoneyModel::poundage($res['loan_amount'],$res['interest'],$res['cuts_interest']);
            $results[$k]['user_name'] = $v['user_name'];
            $results[$k]['u_name'] = $v['u_name'];
            $results[$k]['pay_time'] = $v['pay_time'];
            $results[$k]['loan_amount'] = $v['loan_amount'];
            $results[$k]['bentime'] = $return_arr['time'];
            $results[$k]['money'] = $v['money'];
            $results[$k]['link_name'] = $v['link_name'];
            $results[$k]['link_tel'] = $v['link_tel'];
            $results[$k]['create'] = $v['create'];
            $results[$k]['complete'] = $v['complete'];
            $results[$k]['overday'] = $res['is_pay']?$return_arr['day']:0;
            $results[$k]['overmoney'] = $res['is_pay']?$return_arr['overdue_money']:0;
            $results[$k]['sunmoney'] = $res['is_pay']?$return_arr['overdue_money']+$shouxufei+$v['loan_amount']:0;
            $results[$k]['lev'] = $v['lev'];
        }
        $page_length=20;
        $num=count($results);
        $page_total=new \Think\Page($num,$page_length);
        $cart_data=array_slice($results,$page_total->firstRow,$page_total->listRows);
        $show=$page_total->show();
        $this->assign('loan_data',$cart_data);
        $this->assign('page',$show);
        $this->count_num = $count_num;
        $this->display();
        
    }
    public function info(){
        $map['user_name'] = trim($_GET['user_name']);
        $return_info = $this->user_model->where($map)->find();
        $return_wd = $this->wdinfo_model->where($map)->order('id desc')->select();
        $return_ax = $this->apix_model->where($map)->order('id desc')->select();
        $return_tb = $this->taobao_model->where($map)->order('id desc')->select();
        // $return_jd = $this->jd_info_model->where($map)->order('id desc')->select();
        /*****************万达通讯*****/
        $casic = json_decode($return_wd[0]['casic'],true);
        $tel_info = json_decode($return_wd[0]['tel_info'],true);
        /*****************万达通讯*****/
        /*****************APIX*****/
        $phone = json_decode($return_ax[0]['phone'],true);
        $callrecords = json_decode($return_ax[0]['callrecords'],true);
        /*****************APIX*****/
        /*****************淘宝*****/
        $bindaccountinfo = json_decode($return_tb[0]['bindaccountinfo'],true);
        $personalinfo = json_decode($return_tb[0]['personalinfo'],true);
        $addrs = json_decode($return_tb[0]['addrs'],true);
        /*****************淘宝*****/
        /*****************京东*****/
        // dump($return_jd);die;
        // $bindaccountinfo = json_decode($return_jd[0]['bindaccountinfo'],true);
        // $personalinfo = json_decode($return_jd[0]['personalinfo'],true);
        // $addrs = json_decode($return_jd[0]['addrs'],true);
        // dump($bindaccountinfo);
        // dump($personalinfo);
        // dump($addrs);
        /*****************京东*****/
        /********************************************************************************/
        if($phone && $callrecords){
            $phone['realName'] = $return_info['u_name'];
            $phone['certNo'] = $return_info['identity'];
            $this->assign('map',$map['user_name']);
            $this->assign('phone',$phone);
            $this->assign('callrecords',$callrecords);
        }else{
            foreach ($casic as $k => $v) {
                if($k == 1){
                    $phone['realName'] = $return_info['u_name'];
                    $phone['certNo'] = $return_info['identity'];
                    $phone['netAge'] = $v['result'];
                    $phone['phoneNo'] = $map['user_name'];
                    $phone['addr'] = '万达不提供个人信息';
                }
            }
            foreach ($tel_info as $k => $v) {
                $tle[$k]['phoneNo'] = $v['phone_num'];
                $tle[$k]['belongArea'] = $v['phone_num_loc'];
                $tle[$k]['connTime'] = $v['call_out_len'];
                $tle[$k]['callTimes'] = $v['call_out_cnt'];
                $tle[$k]['calledTimes'] = $v['call_in_cnt'];
            }
            $this->assign('phone',$phone);
            $this->assign('map',$map['user_name']);
            $this->assign('callrecords',$tle);
        }
        if($bindaccountinfo || $personalinfo){
            $tbao['zfbzh'] = $bindaccountinfo['alipatAccount'];
            $tbao['bindMobile'] = $bindaccountinfo['bindMobile'];
            $tbao['identity'] = $bindaccountinfo['identity'];
            /***************************************************/
            $tbao['zfbye'] = $personalinfo['aliPayRemainingAmount'];
            $tbao['yebye'] = $personalinfo['aliPaymFund'];
            $tbao['hbye'] = $personalinfo['huabeiCanUseMoney'];
            $tbao['hbze'] = $personalinfo['huabeiTotalAmount'];
            $this->assign('tbao',$tbao);
        }else{
            $tbao['zfbzh'] = '未获取到淘宝信息';
            $this->assign('tbao',$tbao);
        }
        if($addrs){
            $this->assign('addrs',$addrs);
        }else{
            $addrs[0] = '未获取到淘宝地址信息';
            $this->assign('addrs',$addrs);
        }

        $this->display();
    }

    public function remarks(){
        $data = I('post.');
        $text = $data['text'];
        $map['user_name'] = $data['name'];
        $return = $this->task_model->where($map)->find();
        if($return['remarks']){
           $save_task['remarks'] = $return['remarks'].'&'.$text."记录时间--".date('Y-m-d H:i',time()); 
       }else{
           $save_task['remarks'] = $text."记录时间--".date('Y-m-d H:i',time());      
       }
        
        $return_save = $this->task_model->where($map)->save($save_task);
        if($return_save){
           $this->redirect('Home/Bad/group'); 
        }else{
            echo "<script>alert('备注失败');history.back();</script>";exit;
            return;
        }
    }
    public function beizhu(){
        $data = I('get.');
        $map['user_name'] = $data['user_name'];
        $return = $this->task_model->where($map)->find();
        $result = explode('&',$return['remarks']);
        $this->assign('info',$result);
        $this->display();
    }
    public function recolor(){
        $code = I('get.');
        $map['user_name'] = $code['no'];
        $data['lev'] = $code['code'];
        $rs = $this->task_model->where($map)->save($data);
        if($rs){
            echo "<script>alert('备注成功');history.back();</script>";exit;
            return;
        }else{
            echo "<script>alert('备注失败');history.back();</script>";exit;
            return;
        }
    }
    public function loanrecord(){
        $where['is_pay']=array('gt',0);
        $loan_count=$this->loan_model->where($where)->join('free_user ON free_user.user_id=free_loan.user_id')->count();
        $Page = new \Think\Page($loan_count,15);
        $loan_data=$this->loan_model->where($where)->join('free_user ON free_user.user_id=free_loan.user_id')->limit($Page->firstRow.','.$Page->listRows)->order('free_loan.is_pays desc')->select();
        $loan_sum=$this->loan_model->field('loan_amount,interest')->where($where)->join('free_user ON free_user.user_id=free_loan.user_id')->select();
        foreach ($loan_sum as $key => $value) {
            $cuts_interest=!empty($value['cuts_interest']) ? $value['cuts_interest']:null;
            $sum['shouxufei']+=MoneyModel::poundage($value['loan_amount'],$value['interest'],$cuts_interest);
            $sum['sum_money']+=$value['loan_amount'];
            $sum['ren']+=1;
        }
/*还款时间插入数组*/
        foreach ($loan_data as $key => &$value) {
            if($value['loan_time']==1){
                   $loan_time=7;
            }else if($value['loan_time']==2){
                 $loan_time=14;
            }
            $return_arr=RepayModel::overdue_show($value['is_pay'],$loan_time,$value['renewal_days'],$value['loan_amount']);
            $value['overdue_money']=$return_arr['overdue_money'];
            $value['be_time']=$return_arr['time'];
            $value['loan_time']=$loan_time;
        }
        $show = $Page->show();
        $this->assign('page',$show);
        $this->assign('sum',$sum);
        $this->assign('loan_data',$loan_data);
        $this->display();
    }
    /*到期时间查找*/
    public function expire(){
        $post=I('post.');
        $start_time=strtotime($post['start']);
        $end = strtotime($post['end']);
        $end_time=$end+86400;
        $where['is_pay']=array('gt',0);
        $loan_data=$this->loan_model->where($where)->join('free_user ON free_user.user_id=free_loan.user_id')->select();

        foreach ($loan_data as $key => &$value) {
            if($value['loan_time']==1){
                $loan_time=7;
            }else if($value['loan_time']==2){
                $loan_time=14;
            }
            $return_arr=RepayModel::overdue_show($value['is_pay'],$loan_time,$value['renewal_days'],$value['loan_amount']);
            if($return_arr['time']>$start_time&&$return_arr['time']<$end_time){
                $i+=1;
                $value['num']=$i;
                $value['overdue_money']=$return_arr['overdue_money'];
                $value['be_time']=$return_arr['time'];
                $value['loan_time']=$loan_time;
                $value['type']=1;
                $sum['shouxufei']+=MoneyModel::poundage($value['loan_amount'],$value['interest'],$value['cuts_interest']);
                $sum['sum_money']+=$value['loan_amount'];
                $sum['ren']+=1;
            }
        }
        
        $this->start_time=$start_time;
        $this->end_time=$end_time;

        $this->assign('sum',$sum);
        $this->assign('loan_data',$loan_data);
        $this->display();
    }

    public function search(){
        $post=I('post.');
        switch ($post['code']) {
/* 放款时间查找*/
            case 'lending':
                $start_time=strtotime($post['start']);
                $end = strtotime($post['end']);
                $end_time=$end+86400;
                $where['is_pay']=array(array('egt',$start_time),array('elt',$end_time));
                $loan_data=$this->loan_model->where($where)->join('free_user ON free_user.user_id=free_loan.user_id')->order('free_loan.is_pays desc')->select();
                break;
/*搜索手机号姓名*/
            case 'search';
                if (is_numeric($post['name'])) {
                    $where['free_user.user_name']=$post['name'];
                }else{
                    $where['free_user.u_name']=$post['name'];
                }
                $where['is_pay']=array('gt',0);
                $loan_data=$this->loan_model->where($where)->join('free_user ON free_user.user_id=free_loan.user_id')->select();
                break;           
            default:

             break;
        }
/*还款时间插入数组*/
        foreach ($loan_data as $key => &$value) {
            if($value['loan_time']==1){
                   $loan_time=7;
            }else if($value['loan_time']==2){
                 $loan_time=14;
            }
            $return_arr=RepayModel::overdue_show($value['is_pay'],$loan_time,$value['renewal_days'],$value['loan_amount']);
            $value['overdue_money']=$return_arr['overdue_money'];
            $value['be_time']=$return_arr['time'];
            $value['loan_time']=$loan_time;
            $sum['shouxufei']+=MoneyModel::poundage($value['loan_amount'],$value['interest'],$value['cuts_interest']);
            $sum['sum_money']+=$value['loan_amount'];
            $sum['ren']+=1;
        }
        $this->assign('sum',$sum);
        $this->assign('loan_data',$loan_data);
        $this->display();
    }
    public function publics(){
        $nickname['username'] = 'hfj75Ju79';
        $return_nick = $this->auth_model->where($nickname)->find();
        $map['group'] = $return_nick['nickname'];
        $result = $this->task_model->where($map)->order('id desc')->select();
        $where_count['complete'] = date('Y-m-d',time());
        $where_count['group'] = $return_nick['nickname'];
        $count_num = $this->task_model->where($where_count)->count();
        foreach ($result as $k => $v) {
            $where['user_name'] = $v['user_name'];
            $res = $this->loan_model->where($where)->find();
            if($res['loan_time']==1){
                $loan_time=7;
            }elseif($res['loan_time']==2){
                $loan_time=14;
            }
            $return_arr=RepayModel::overdue_show($res['is_pay'],$loan_time,$res['renewal_days'],$res['loan_amount']);
            $shouxufei=MoneyModel::poundage($res['loan_amount'],$res['interest'],$res['cuts_interest']);
            $results[$k]['user_name'] = $v['user_name'];
            $results[$k]['u_name'] = $v['u_name'];
            $results[$k]['pay_time'] = $v['pay_time'];
            $results[$k]['loan_amount'] = $v['loan_amount'];
            $results[$k]['bentime'] = $return_arr['time'];
            $results[$k]['money'] = $v['money'];
            $results[$k]['link_name'] = $v['link_name'];
            $results[$k]['link_tel'] = $v['link_tel'];
            $results[$k]['create'] = $v['create'];
            $results[$k]['complete'] = $v['complete'];
            $results[$k]['overday'] = $res['is_pay']?$return_arr['day']:0;
            $results[$k]['overmoney'] = $res['is_pay']?$return_arr['overdue_money']:0;
            $results[$k]['sunmoney'] = $res['is_pay']?$return_arr['overdue_money']+$shouxufei+$v['loan_amount']:0;
            $results[$k]['lev'] = $v['lev'];
        }
        $page_length=20;
        $num=count($results);
        $page_total=new \Think\Page($num,$page_length);
        $cart_data=array_slice($results,$page_total->firstRow,$page_total->listRows);
        $show=$page_total->show();
        $this->assign('loan_data',$cart_data);
        $this->assign('page',$show);
        $this->count_num = $count_num;
        $this->display();
    }

    public function excel_bepay(){
        $get=I('get.');
        $loan=M('loan');
        $where['is_pay']=array('gt',0);
        $start_time=$get['start_time'];
        $end_time =$get['end_time'];

        $loan_data=$loan->where($where)->join('free_user ON free_user.user_id=free_loan.user_id')->select();
         foreach ($loan_data as $key => $value) {
            if($value['loan_time']==1){
                $loan_time=7;
            }else if($value['loan_time']==2){
                $loan_time=14;
            }
            $return_arr=RepayModel::overdue_show($value['is_pay'],$loan_time,$value['renewal_days'],$value['loan_amount']);
            $shouxufei=MoneyModel::poundage($value['loan_amount'],$value['interest'],$value['cuts_interest']);
            if($return_arr['time']>$start_time&&$return_arr['time']<$end_time){
                $money=$shouxufei+$return_arr['overdue_money']+$value['loan_amount'];
               $excel_data[$key]=$value;

               $excel_data[$key]['be_time']=$return_arr['time'];
               $excel_data[$key]['shouxufei']=$shouxufei;
               $excel_data[$key]['money']=$money;
            }
        }

        $this->goods_export($excel_data);
    }


    public function goods_export($goods_list){
        $goods_list = array_merge($goods_list);
        $data = array();
        foreach ($goods_list as $k=>$goods_info){
            $data[$k][u_name] = " $goods_info[u_name]";
            $data[$k][user_name] = " $goods_info[user_name]";
            $data[$k][loan_order] = " $goods_info[loan_order]";
            $data[$k][is_pay] = "$goods_info[is_pay]";
            $data[$k][loan_time] = " $goods_info[loan_time]";
            $data[$k][loan_amount] = " $goods_info[loan_amount]";
            $data[$k][shouxufei] = " $goods_info[shouxufei]";
            $data[$k][end_money] = " $goods_info[money]";
            $data[$k][renewal_days] = " $goods_info[renewal_days]";
            $data[$k][be_time] = " $goods_info[be_time]";
        }
        foreach ($data as $field=>$v){
            if($field == 'u_name'){
                $headArr[]='姓名';
            }
            if($field == 'user_name'){
                $headArr[]='手机号';
            }
            if($field == 'loan_order'){
                $headArr[]='订单号';
            }
            if($field == 'is_pay'){
                $headArr[]='打款时间';
            }
            if($field == 'loan_time'){
                $headArr[]='借款期限';
            }
            if($field == 'loan_amount'){
                $headArr[]='打款金额';
            }
            if($field == 'shouxufei'){
                $headArr[]='综合费用';
            }
            if($field == 'end_money'){
                $headArr[]='应还金额';
            }
            if($field == 'renewal_days'){
                $headArr[]='续期';
            }
            if($field == 'overday_time'){
                $headArr[]='应还款时间';
            }
//手机号(用户名)  持卡人姓名 银行卡绑定手机号  身份证号  银行卡号  借款金额  打款时间  借款期限  到期时间  逾期天数  本息  逾期费用
        }

        $filename="用户借款表".date('Y_m_d',time());
        $this->getExcel($filename,$headArr,$data);
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
                if($keyName=='is_pay' || $keyName=='be_time'){
                    $objActSheet->setCellValue($j.$column, \PHPExcel_Shared_Date::PHPToExcel($value));
                    $objActSheet->getStyle($j.$column)->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
                }else{
                    $objActSheet->setCellValue($j.$column, $value);
                }
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
}