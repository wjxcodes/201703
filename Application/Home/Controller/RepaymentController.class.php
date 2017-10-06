<?php
//还款记录表
namespace Home\Controller;
use Think\Controller;
use Home\Model\RepayModel as RepayModel;
use Home\Model\MoneyModel as MoneyModel;
header("content-type:text/html;charset=utf8");
class RepaymentController extends BaseController{
    protected $task_model;
    protected $record_model;
    protected $loan_model;
    public function __construct(){
        parent::__construct();
        $this->task_model = M('Task');
        $this->record_model = M('Record');
        $this->loan_model = M('Loan');
    }


//快钱还款页面
    public function refund(){
        $record=M('record');
        if($_POST){
            $post=I('post.');
            switch ($post['code']) {
                case '1':
                        $where['free_record.user_name']=$post['name'];
                    break;

                case '2':
                        $start_time=strtotime($post['start']);
                        $end = strtotime($post['end']);
                        $end_time=$end+86400;
                        $where['pay_time']=array(array('GT',$start_time),array('ELT',$end_time));
                        $where['is_kq']=1;
                    break;
                default:
                    
                    break;
            }
        }else{
            $where['is_kq']=1;
        }
        $sum=$record->field('pay_time,loan_time,xutime,pay_money,repayment_time,interest')->where($where)->select();
        foreach ($sum as $key => $value) {
            if($value['loan_time']==1){
                $loan_time=7;
            }else if($value['loan_time']==2){
                $loan_time=14;
            }
            $be_overdue=RepayModel::be_overdue($value['pay_time'],$loan_time,$value['xutime'],$value['pay_money'],$value['repayment_time']);
            $cuts_interest=!empty($value['cuts_interest']) ? $value['cuts_interest']:null;
            $shouxufei=MoneyModel::poundage($value['pay_money'],$value['interest'],$cuts_interest);
            $sum_data['pay_money']+=$value['pay_money'];
            $sum_data['shouxufei']+=$shouxufei;
            $sum_data['overdue_money']+=$be_overdue['overdue_money'];
        }
        $record_count = $record->where($where)->join('free_user ON free_user.user_id=free_record.user_id')->order('repayment_time desc')->count();
        $page=new \Think\Page($record_count,50);
        $record_data = $record->where($where)->join('free_user ON free_user.user_id=free_record.user_id')->order('repayment_time desc')->limit($page->firstRow.','.$page->listRows)->select();
        foreach ($record_data as $key => &$value) {
            if($value['loan_time']==1){
                $loan_time=7;
            }else if($value['loan_time']==2){
                $loan_time=14;
            }
            $be_overdue=RepayModel::be_overdue($value['pay_time'],$loan_time,$value['xutime'],$value['pay_money'],$value['repayment_time']);
            $value['be_time']=$be_overdue['time'];
            $value['over_charges']=$be_overdue['overdue_money'];
        }
        $page=$page->show();
        $this->page=$page;
        $this->sum_data=$sum_data;
        $this->count=$record_count;
        $this->record_data=$record_data;
        $this->display();
    }

    public function ll_pay(){
        $info = M()->table('free_user user,free_record record,free_loan loan')
                ->where('user.user_id=record.user_id AND user.user_id=loan.user_id AND record.is_kq=2')
                ->field('open_id,user.u_name,user.user_name,record.pay_time,record.xutime,record.repayment_time,record.pay_money,record.repayment_money,record.payment,record.loan_order,record.loan_time,loan.loan_num')
                ->order('repayment_time desc')
                ->select();
        $num = count($info);
        $dateStr = date('Y-m-d', time());
        foreach ($info as $k => &$v) {
            $v['time'] = strtotime($dateStr);
            if($v['loan_time']==1){
               $loan_time=7;
            }else if($v['loan_time']==2){
               $loan_time=14;
            }

            $be_overdue=RepayModel::be_overdue($v['pay_time'],$loan_time,$v['xutime'],$v['pay_money'],$v['repayment_time']);
            $v['be_time']=$be_overdue['time'];
            $v['over_charges']=$be_overdue['overdue_money'];
            $v['overdue_day']=$be_overdue['day'];
            if ($v['time'] < $v['be_time']) {
                $people+=1;
                $peopleMoney+=$v['repayment_money'];
            }
            $over_charges+=$v['over_charges'];
            $repayment_money+=$v['repayment_money'];
            $pay_money+=$v['pay_money'];
            $payment += $v['payment'];
        }
        $over_charges = $over_charges;
        $page_length=100;
        $num=count($info);
        $page_total=new \Think\Page($num,$page_length);
        $cart_data=array_slice($info,$page_total->firstRow,$page_total->listRows);
        $show=$page_total->show();
        $this->assign('people',$people);
        $this->assign('peopleMoney',$peopleMoney);
        $this->assign('over_charges',$over_charges);
        $this->assign('repayment_money',$repayment_money);
        $this->assign('pay_money',$pay_money);
        $this->assign('payment',$payment);
        $this->assign('count',$num);
        $this->assign('page',$show);
        $this->assign('info', $cart_data);
        $this->display();
    }
        //支付宝还款
    public function zfb() {
        $info = M()->table('free_user user,free_record record,free_loan loan')
                ->where('user.user_id=record.user_id AND user.user_id=loan.user_id AND record.is_kq=0')
                ->field('open_id,user.u_name,user.user_name,record.pay_time,record.xutime,record.repayment_time,record.pay_money,record.repayment_money,record.payment,record.loan_order,record.loan_time,record.record_id,loan.loan_num')
                ->order('repayment_time desc')
                ->select();
        $num = count($info);
        $dateStr = date('Y-m-d', time());
        foreach ($info as $k => &$v) {
            $v['time'] = strtotime($dateStr);
                if($v['loan_time']==1){
                   $loan_time=7;
                }else if($v['loan_time']==2){
                   $loan_time=14;
                }
            $be_overdue=RepayModel::be_overdue($v['pay_time'],$loan_time,$v['xutime'],$v['pay_money'],$v['repayment_time']);
            $v['be_time']=$be_overdue['time'];
            $v['overdue_day']=$be_overdue['day'];
            $v['over_charges']=$be_overdue['overdue_money'];

                if ($v['time'] < $v['be_time']) {
                    $people+=1;
                    $peopleMoney+=$v['repayment_money'];
                }
            $over_charges+=$v['over_charges'];
            $repayment_money+=$v['repayment_money'];
            $pay_money+=$v['pay_money'];
            $payment += $v['payment'];
        }
        $over_charges = $over_charges;
        $page_length=100;
        $num=count($info);
        $page_total=new \Think\Page($num,$page_length);
        $cart_data=array_slice($info,$page_total->firstRow,$page_total->listRows);
        $show=$page_total->show();
        $this->assign('people',$people);
        $this->assign('peopleMoney',$peopleMoney);
        $this->assign('over_charges',$over_charges);
        $this->assign('repayment_money',$repayment_money);
        $this->assign('pay_money',$pay_money);
        $this->assign('payment',$payment);
        $this->assign('count',$num);
        $this->assign('page',$show);
        $this->assign('info', $cart_data);
        $this->display();
    }
    
    public function alters(){
           if($_POST){
            $record=M('record');
            $user_name=I('get.record_id');

            $post=I('post.');
            if ($post['paytime']) {
                $save['repayment_time']=strtotime($post['paytime']);
            }
            $save['payment']=$post['paymoney']?$post['paymoney']:'';
            $where['record_id']=$user_name;
      
            $res=$record->where($where)->save($save);
            if($res){
               die("<script>alert('ok');window.location.href='zfb';</script>");
            }else{
               die("<script>alert('未知原因导致未成功');history.back();</script>");
            }
        }
        $this->display('Repayment/alters');
    }
     public function export(){
        $info = M()->table('free_user user,free_record record')
                ->where('user.user_id=record.user_id AND record.is_kq=0')
                ->order('repayment_time desc')
                ->select();
        foreach ($info as $k => &$v) {
               if($v['loan_time']==1){
                   $loan_time=7;
                }else if($v['loan_time']==2){
                   $loan_time=14;
                }

            $v['pay_time'] = date('Y/m/d H:i:s',$v['pay_time']);
            $v['loan_time'] = $loan_time;
            $v['repayment_time'] = date('Y/m/d H:i:s',$v['repayment_time']);
            $be_overdue=RepayModel::be_overdue($v['pay_time'],$loan_time,$v['xutime'],$v['pay_money'],$v['repayment_time']);
            $v['be_time']=$be_overdue['time'];
            $v['over_charges']=$be_overdue['overdue_money'];
        }
        $this->goods_export($info);

    }


     public function goods_export($goods_list){
        $goods_list = array_merge($goods_list);
        $data = array();
        foreach ($goods_list as $k=>$goods_info){
            $data[$k][u_name] = " $goods_info[u_name]";
            $data[$k][user_name] = " $goods_info[user_name]";
            $data[$k][bank_card] = " $goods_info[bank_card]";
            $data[$k][identity] = " $goods_info[identity]";
            $data[$k][loan_order] = " $goods_info[loan_order]";
            $data[$k][pay_time] = " $goods_info[pay_time]";
            $data[$k][be_time] = " $goods_info[be_time]";
            $data[$k][loan_time] = " $goods_info[loan_time]";
            $data[$k][pay_money] = " $goods_info[pay_money]";
            $data[$k][repayment_money] = " $goods_info[repayment_money]";
            $data[$k][repayment_time] = " $goods_info[repayment_time]";
            $data[$k][xutime] = " $goods_info[xutime]";
            $data[$k][over_charges] = " $goods_info[over_charges]";
        }

        foreach ($data as $field=>$v){
            if($field == 'u_name'){
                $headArr[]='姓名';
            }
           if($field == 'user_name'){
                $headArr[]='手机号';
            }
            if($field == 'bank_card'){
                $headArr[]='银行卡号';
            }
            if($field == 'identity'){
                $headArr[]='身份证号';
            }
            if($field == 'loan_order'){
                $headArr[]='订单号';
            }
            if($field == 'pay_time'){
                $headArr[]='打款时间';
            }
            if($field == 'be_time'){
                $headArr[]='应还款时间';
            }
            if($field == 'loan_time'){
                $headArr[]='借款期限';
            }
            if($field == 'pay_money'){
                $headArr[]='打款金额';
            }
            if($field == 'repayment_money'){
                $headArr[]='还款金额';
            }
            if($field == 'repayment_time'){
                $headArr[]='还款时间';
            }
            if($field == 'xutime'){
                $headArr[]='续期';
            }
            
            if($field == 'over_charges'){
                $headArr[]='逾期';
            }
            
//手机号(用户名)  持卡人姓名 银行卡绑定手机号  身份证号  银行卡号  借款金额  打款时间  借款期限  到期时间  逾期天数  本息  逾期费用


        }

        $filename="支付宝还款表".date('Y_m_d',time());

        $this->getExcel($filename,$headArr,$data);
    }


    public function getExcel($fileName,$headArr,$data){
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
                $objActSheet->setCellValue($j.$column, $value);
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

public function search() {
        $id=I('get.id');
        $start1 = strtotime(I('post.start1'));
        $end1 = strtotime(I('post.end1'))+86400;
        $info = M()->table('free_user user,free_record record,free_loan loan')
                ->where('user.user_id=record.user_id AND user.user_id=loan.user_id AND record.is_kq='.$id)
                ->field('open_id,user.u_name,user.user_name,user.bank_card,user.identity,record.pay_time,record.xutime,record.repayment_time,record.pay_money,record.repayment_money,record.payment,record.loan_order,record.loan_time,record.record_id,loan.loan_num')
                ->order('repayment_time desc')
                ->select();
        $num = count($info);
        foreach ($info as $k => $v) {
            if($v['loan_time']==1){
               $loan_time=7;
            }else if($v['loan_time']==2){
               $loan_time=14;
            }

            $be_overdue=RepayModel::be_overdue($v['pay_time'],$loan_time,$v['xutime'],$v['pay_money'],$v['repayment_time']);
            $be_time=$be_overdue['time'];
            if ($be_time>=$start1 && $be_time <= $end1) {
                $arr1[$k]['be_time'] = $be_time;
                $arr1[$k]['open_id']=$v['open_id'];
                $arr1[$k]['u_name'] = $v['u_name'];
                $arr1[$k]['user_name'] = $v['user_name'];
                $arr1[$k]['bank_card'] = $v['bank_card'];
                $arr1[$k]['loan_order'] = $v['loan_order'];
                $arr1[$k]['pay_time'] = $v['pay_time'];
                $arr1[$k]['loan_time'] = $v['loan_time'];
                $arr1[$k]['pay_money'] = $v['pay_money'];
                $arr1[$k]['payment'] = $v['payment'];
                $dateStr = date('Y-m-d', time());
                $arr1[$k]['time'] = strtotime($dateStr);
                $arr1[$k]['repayment_money'] = $v['repayment_money'];
                $arr1[$k]['repayment_time'] = $v['repayment_time'];
                $arr1[$k]['xutime'] = $v['xutime'];
                $arr1[$k]['loan_num'] = $v['loan_num'];
                $arr1[$k]['over_charges']=$be_overdue['overdue_money'];
                $arr1[$k]['day']=$be_overdue['day'];

                if ($arr1[$k]['time'] < $be_time) {
                    $people+=1;
                    $peopleMoney+=$arr1[$k]['repayment_money'];
                }
            $over_charges+=$arr1[$k]['over_charges'];
            $repayment_money+=$arr1[$k]['repayment_money'];
            $pay_money+=$arr1[$k]['pay_money'];
            }
            
        }
        $over_charges = $over_charges;
        $page_length=10000;
        $num=count($arr1);
        $page_total=new \Think\Page($num,$page_length);
        $cart_data=array_slice($arr1,$page_total->firstRow,$page_total->listRows);
        $show=$page_total->show();
        $this->assign('people',$people);
        $this->assign('peopleMoney',$peopleMoney);
        $this->assign('over_charges',$over_charges);
        $this->assign('repayment_money',$repayment_money);
        $this->assign('pay_money',$pay_money);
        $this->assign('count',$num);
        $this->assign('page',$show);
        $this->assign('arr', $cart_data);
        $this->display();
    }

    public function ll_search1(){
        $id=I('get.id');
        $start1 = strtotime(I('post.start1'));
        $end1 = strtotime(I('post.end1'))+86400;
        $info = M()->table('free_user user,free_record record')
                ->where('user.user_id=record.user_id AND pay_time>='.$start1.' AND pay_time<'.$end1.' AND record.is_kq='.$id)
                ->order('repayment_time desc')
                ->select();
                
        foreach ($info as $key => &$value) {
            if($v['loan_time']==1){
               $loan_time=7;
            }else if($v['loan_time']==2){
               $loan_time=14;
            }
            $be_overdue=RepayModel::be_overdue($v['pay_time'],$loan_time,$v['xutime'],$v['pay_money'],$v['repayment_time']);
            $be_time=$be_overdue['time'];
            $value['be_time']=$be_time;
            $dateStr = date('Y-m-d', time());
            $value['time'] = strtotime($dateStr);
            $value['over_charges']=$be_overdue['overdue_money'];
        }

        $this->assign('arr', $info);
        $this->display('Repayment/ll_search');
    } 


public function ll_search() {
        $id=I('get.id');
        $start1 = strtotime(I('post.start1'));
        $end1 = strtotime(I('post.end1'))+86400;
        $info = M()->table('free_user user,free_record record,free_loan loan')
                ->where('user.user_id=record.user_id AND user.user_id=loan.user_id AND record.is_kq='.$id)
                ->field('open_id,user.u_name,user.user_name,record.pay_time,record.xutime,record.repayment_time,record.pay_money,record.repayment_money,record.payment,record.loan_order,record.loan_time,record.record_id,loan.loan_num')
                ->order('repayment_time desc')
                ->select();
        $num = count($info);
        foreach ($info as $k => $v) {
            if($v['loan_time']==1){
               $loan_time=7;
            }else if($v['loan_time']==2){
               $loan_time=14;
            }

            $be_overdue=RepayModel::be_overdue($v['pay_time'],$loan_time,$v['xutime'],$v['pay_money'],$v['repayment_time']);
            $be_time=$be_overdue['time'];
            
            if ($be_time>=$start1 && $be_time <= $end1) {
                $arr1[$k]['be_time'] = $be_time;
                $arr1[$k]['open_id']=$v['open_id'];
                $arr1[$k]['u_name'] = $v['u_name'];
                $arr1[$k]['user_name'] = $v['user_name'];
                $arr1[$k]['loan_order'] = $v['loan_order'];
                $arr1[$k]['pay_time'] = $v['pay_time'];
                $arr1[$k]['loan_time'] = $v['loan_time'];
                $arr1[$k]['pay_money'] = $v['pay_money'];
                $arr1[$k]['payment'] = $v['payment'];
                $dateStr = date('Y-m-d', time());
                $arr1[$k]['time'] = strtotime($dateStr);
                $arr1[$k]['repayment_money'] = $v['repayment_money'];
                $arr1[$k]['repayment_time'] = $v['repayment_time'];
                $arr1[$k]['xutime'] = $v['xutime'];
                $arr1[$k]['loan_num'] = $v['loan_num'];
                $arr1[$k]['over_charges']=$be_overdue['overdue_money'];
                $arr1[$k]['day']=$be_overdue['day'];
                if ($arr1[$k]['time'] < $be_time) {
                    $people+=1;
                    $peopleMoney+=$arr1[$k]['repayment_money'];
                }
            $over_charges+=$arr1[$k]['over_charges'];
            $repayment_money+=$arr1[$k]['repayment_money'];
            $pay_money+=$arr1[$k]['pay_money'];
            }
            
        }
        $over_charges = $over_charges;
        $page_length=10000;
        $num=count($arr1);
        $page_total=new \Think\Page($num,$page_length);
        $cart_data=array_slice($arr1,$page_total->firstRow,$page_total->listRows);
        $show=$page_total->show();
        $this->assign('people',$people);
        $this->assign('peopleMoney',$peopleMoney);
        $this->assign('over_charges',$over_charges);
        $this->assign('repayment_money',$repayment_money);
        $this->assign('pay_money',$pay_money);
        $this->assign('count',$num);
        $this->assign('page',$show);
        $this->assign('arr', $cart_data);
        $this->display();
    }



    public function searchs() {
        $id=I('get.id');
        $start2 = strtotime(I('post.start2'));
        $end2 = strtotime(I('post.end2'))+86400;
        $info = M()->table('free_user user,free_record record')
                ->where('user.user_id=record.user_id AND record.is_kq='.$id)
                ->order('repayment_time desc')
                ->select();
        $num = count($info);
        foreach ($info as $k => $v) {
            $arr[$k]['repayment_time'] = $v['repayment_time'];
            $repayment_time = $arr[$k]['repayment_time'];
            if ($repayment_time>=$start2 && $repayment_time <= $end2) {
                $arr1[$k]['repayment_time'] = $repayment_time;
                $arr1[$k]['open_id']=$v['open_id'];
                $arr1[$k]['u_name'] = $v['u_name'];
                $arr1[$k]['user_name'] = $v['user_name'];
                $arr1[$k]['bank_card'] = $v['bank_card'];
                $arr1[$k]['identity'] = $v['identity'];
                $arr1[$k]['loan_order'] = $v['loan_order'];
                $arr1[$k]['pay_time'] = $v['pay_time'];
                $arr1[$k]['loan_time'] = $v['loan_time'];
                $arr1[$k]['pay_money'] = $v['pay_money'];
                $arr1[$k]['payment'] = $v['payment'];
                $dateStr = date('Y-m-d', time());
                $arr1[$k]['time'] = strtotime($dateStr);
                $arr1[$k]['repayment_money'] = $v['repayment_money'];
                $arr1[$k]['xutime'] = $v['xutime'];
                $arr1[$k]['zm_score'] = $v['zm_score'];
                if($v['loan_time']==1){
                    $loan_time=7;
                }else if($v['loan_time']==2){
                    $loan_time=14;
                }

                $be_overdue=RepayModel::be_overdue($v['pay_time'],$loan_time,$v['xutime'],$v['pay_money'],$v['repayment_time']);
                $arr1[$k]['be_time']=$be_overdue['time'];
                $arr1[$k]['over_charges']=$be_overdue['overdue_money'];

                if ($arr1[$k]['time'] < $be_overdue['time']) {
                    $people+=1;
                    $peopleMoney+=$arr1[$k]['repayment_money'];
                }
            $over_charges+=$arr1[$k]['over_charges'];
            $repayment_money+=$arr1[$k]['repayment_money'];
            $pay_money+=$arr1[$k]['pay_money'];
            }
            
        }
        $over_charges = $over_charges;
        $page_length=10000;
        $num=count($arr1);
        $page_total=new \Think\Page($num,$page_length);
        $cart_data=array_slice($arr1,$page_total->firstRow,$page_total->listRows);
        $show=$page_total->show();
        $this->assign('people',$people);
        $this->assign('peopleMoney',$peopleMoney);
        $this->assign('over_charges',$over_charges);
        $this->assign('repayment_money',$repayment_money);
        $this->assign('pay_money',$pay_money);
        $this->assign('count',$num);
        $this->assign('page',$show);
        $this->assign('arr', $cart_data);
        $this->display();
    }
    public function remarks(){
        $remarks=M('remarks');
        $where['type']=0;
        $remarks_data=$remarks->where($where)
        ->join('free_record ON free_record.record_id=free_remarks.record_id')
        ->join('free_user ON free_user.user_id=free_record.user_id')
        ->order('repayment_time desc')
        ->select();

        foreach ($remarks_data as $key => &$value) {
            if($value['loan_time']==1){
                $loan_time=7;
            }else if($value['loan_time']==2){
                $loan_time=14;
            }
            $be_overdue=RepayModel::be_overdue($value['pay_time'],$loan_time,$value['xutime'],$value['pay_money'],$value['repayment_time']);
            $value['be_time']=$be_overdue['time'];
            $money+=$value['money'];
            $overdue_money+=$value['payment']-$value['money'];

        }
        $this->money=$money;
        $this->overdue_money=$overdue_money;
        $this->remarks_data=$remarks_data;
        $this->display();
    }

    public function remarks_create(){
        if($_POST){
            $post=I('post.');
            if($post['code']==1){
                $record=M('record'); 
                $where['free_record.user_name']=$post['name'];
                $record_data = $record->where($where)->join('free_user ON free_user.user_id=free_record.user_id')->order('repayment_time desc')->select();
                $this->record_data=$record_data;
            }
            if($post['code']==2){
                $remarks=M('remarks');
                $save['record_id']=$post['record'];
                $save['money']=$post['name'];
                $save['create_time']=time();
                $save['remarks']=$post['remark'];
                $save['user']=session('aname');
                $res=$remarks->add($save);
                if($res){
                    die("<script>alert('ok！！！');window.location.href='remarks';</script>");
                }else{
                    die("<script>alert('网络缘故审核提交失败,请稍后再试！！！');window.location.href='remarks';</script>");
                }
            }
        }
        $this->display();
    }

    public function countnum(){
        $post=I('post.');
        switch ($post['code']) {
            case 'name':
                $page_length=100;
                if (is_numeric($post['name'])) {
                    $where['free_task.user_name']=$post['name'];
                }else{
                    $where['free_task.u_name']=$post['name'];
                }
                $where['complete']=array('gt',0);
                break;
            
            case 'expire':
                $page_length=100;
                $where['complete']=array('between',array($post['start'],$post['end']));

                break;
            default:
                $page_length=20;
                $where['complete']=array('gt',0);
                break;
        }

    
        
        $task_count = $this->task_model->where($where)->join('free_record on free_record.loan_order=free_task.no_order')->join('free_loan on free_loan.user_name=free_task.user_name')->order('complete desc')->count();
        $page_total=new \Think\Page($task_count,$page_length);

        $task_data = $this->task_model->field('free_task.u_name,free_task.user_name,free_record.loan_time,free_task.pay_time as loan_request,free_task.loan_amount,free_task.money,free_task.create,free_task.group,free_task.group,free_loan.loan_num,free_record.pay_time,free_record.xutime,free_record.pay_money,free_record.repayment_time,free_record.payment,free_record.is_kq,free_record.repayment_money,complete')->where($where)->join('free_record on free_record.loan_order=free_task.no_order')->join('free_loan on free_loan.user_name=free_task.user_name')->order('complete desc,free_task.id desc')->limit($page_total->firstRow.','.$page_total->listRows)->select();


        foreach ($task_data as $k => &$v) {
            $record_rs = $this->record_model->where($map)->order('record_id desc')->find();
            $loan_rs = $this->loan_model->where($mapl)->field('loan_num')->find();

            if($v['loan_time']==1){
                $loan_time=7;
            }else if($v['loan_time']==2){
                $loan_time=14;
            }
            $be_overdue = RepayModel::be_overdue($v['pay_time'],$loan_time,$v['xutime'],$v['pay_money'],$v['repayment_time']);
            $v['time'] = date('Y-m-d',strtotime($v['create'])-432000);
            $v['day'] = $be_overdue['day'];
            $v['ymoney'] = $be_overdue['overdue_money']-$v['payment'];

            if($v['is_kq'] == 0){
                $v['repayment_money'] = $v['repayment_money']+$be_overdue['overdue_money']-$v['payment'];
            }else{
                $v['repayment_money'] = $v['repayment_money'];
            }
            
        }
        
        $show=$page_total->show();
        $this->assign('task_data',$task_data);
        $this->assign('count',$task_count);
        $this->assign('page',$show);
        $this->display();
    }
}