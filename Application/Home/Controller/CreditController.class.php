<?php
// 征信记录表
namespace Home\Controller;
use Think\Controller;
use Home\Model\CreditModel as CreditModel;
header('content-type:text/html;charset=utf-8');
class CreditController extends BaseController {

    public function credit() {
        $data = trim(I('get.id'));
        if (is_numeric($_GET['id'])) {
            $count = M()->table('free_user user,free_credit credit')
                        ->where("user.user_id = credit.user_id AND user.user_name = $data")
                        ->count();
            $Page = new \Think\Page($count, 100);
            $creditInfo = M()->table('free_user user,free_credit credit')
                             ->where("user.user_id = credit.user_id AND user.user_name = $data")
                             ->order('credit.create_time desc')
                             ->limit($Page->firstRow . ',' . $Page->listRows)
                             ->select();
        }elseif($_GET['id']){
            $count = M()->table('free_user user,free_credit credit')
                        ->where("user.user_id = credit.user_id AND user.u_name = '$data'")
                        ->count();
            $Page = new \Think\Page($count, 100);
            $creditInfo = M()->table('free_user user,free_credit credit')
                             ->where("user.user_id = credit.user_id AND user.u_name = '$data'")
                             ->order('credit.create_time desc')
                             ->limit($Page->firstRow . ',' . $Page->listRows)
                             ->select();
        }else{
            $count = M()->table('free_user user,free_credit credit')
                        ->where('user.user_id = credit.user_id')
                        ->count();
            $Page = new \Think\Page($count, 4);
            $creditInfo = M()->table('free_user user,free_credit credit')
                             ->where("user.user_id = credit.user_id")
                             ->order('credit.create_time desc')
                             ->limit($Page->firstRow . ',' . $Page->listRows)
                             ->select();
        }
        $show = $Page->show(); // 分页显示输出
        $this->assign('page', $show); // 赋值分页输出
        $this->assign('creditInfo', $creditInfo);
        $this->display();
    }

        //导出Excel
    public function export(){
        $start = strtotime($_POST['start']);
        $end = strtotime($_POST['end']);
        $credit_model  = M('Credit');
        $map['create_time'] = array(array('egt',$start),array('elt',$end));
        $result = $credit_model->where($map)->select();
        $this->goods_export($result);

    }
    public function goods_export($goods_list){
        $goods_list = $goods_list;
        $data = array();
        foreach ($goods_list as $k=>$goods_info){
            $data[$k][cuser_name] = " $goods_info[cuser_name]";
            $data[$k][cu_name] = " $goods_info[cu_name]";
            $data[$k][cidentity] = " $goods_info[cidentity]";
            $data[$k][cbank_card] = " $goods_info[cbank_card]";
            $data[$k][clinkman_name] = " $goods_info[clinkman_name]";

            $data[$k][clinkman_tel] = " $goods_info[clinkman_tel]";
            $data[$k][linkman_name_a] = " $goods_info[linkman_name_a]";
            $data[$k][linkman_tel_a] = " $goods_info[linkman_tel_a]";
            $data[$k][u_name_a1] = " $goods_info[u_name_a1]";
            $data[$k][identity_a1] = " $goods_info[identity_a1]";

            $data[$k][user_name_a] = " $goods_info[user_name_a]";
            $data[$k][identity_a] = " $goods_info[identity_a]";
            $data[$k][bank_card_a] = " $goods_info[bank_card_a]";
            $data[$k][u_name_a] = " $goods_info[u_name_a]";
            $data[$k][tel_a] = " $goods_info[tel_a]";

            $data[$k][ivs_score] = " $goods_info[ivs_score]";
            $data[$k][zm_score] = " $goods_info[zm_score]";
            $data[$k][is_matched] = $goods_info['is_matched']?'在':'不在';
            $data[$k][hit] = " $goods_info[hit]";
            $data[$k][create_time] = date('Y-m-d'," $goods_info[create_time]");
           //cuser_name,cu_name,cidentity,cbank_card,clinkman_name,
                //clinkman_tel,linkman_name_a,linkman_tel_a,u_name_a1,identity_a1,
                //user_name_a,identity_a,bank_card_a,u_name_a,tel_a,
                //ivs_score,zm_score,is_matched,hit,create_time

            if($goods_info['loan_time'] == 1){
                        $data[$k][money] = $goods_info['loan_amount']-$goods_info['loan_amount']*0.05;
                }elseif ($goods_info['loan_time'] == 2) {
                        $data[$k][money] = $goods_info['loan_amount']-$goods_info['loan_amount']*0.10;
                }

            $data[$k][loan_request] = " $goods_info[loan_request]";
            $data[$k][is_pay] = " $goods_info[is_pay]";
            if ($goods_info[loan_time] == 1) {
                $data[$k][loan_time] = "7天";
            }elseif ($goods_info[loan_time] == 2) {
                $data[$k][loan_time] = "14天";
            }

            if($goods_info['loan_time'] == 1){
                $data[$k][overday] = date('Y-m-d H:i',$goods_info['is_pay']+7*24*60*60);
            }elseif ($goods_info['loan_time'] == 2){
                $data[$k][overday] = date('Y-m-d H:i',$goods_info['is_pay']+7*24*60*60);
            }
            
        }

        foreach ($data as $field=>$v){
            if($field == 'cuser_name'){
                $headArr[]='注册手机号';
            }
            if($field == 'cu_name'){
                $headArr[]='注册用户姓名';
            }
            if($field == 'cidentity'){
                $headArr[]='注册人身份证号';
            }
            if($field == 'cbank_card'){
                $headArr[]='银行卡号';
            }
            if($field == 'clinkman_name'){
                $headArr[]='紧急联系人姓名';
            }
            if($field == 'clinkman_tel'){
                $headArr[]='紧急联系人电话';
                //cuser_name,cu_name,cidentity,cbank_card,clinkman_name,
                //clinkman_tel,linkman_name_a,linkman_tel_a,u_name_a1,identity_a1,
                //user_name_a,identity_a,bank_card_a,u_name_a,tel_a,
                //ivs_score,zm_score,is_matched,hit,create_time
            }


            if($field == 'linkman_name_a'){
                $headArr[]='紧急联系人二要素';
            }
            if($field == 'linkman_tel_a'){
                $headArr[]='紧急联系人二要素';
            }


            if($field == 'u_name_a1'){
                $headArr[]='个人三要素';
            }
            if($field == 'identity_a1'){
                $headArr[]='个人三要素';
            }
            if($field == ' user_name_a'){
                $headArr[]='个人三要素';
            }


            if($field == 'identity_a'){
                $headArr[]='银行卡四要素';
            }
            if($field == 'bank_card_a'){
                $headArr[]='银行卡四要素';
            }
            if($field == ' u_name_a'){
                $headArr[]='银行卡四要素';
            }
            if($field == 'tel_a'){
                $headArr[]='银行卡四要素';
            }


            if($field == 'ivs_score'){
                $headArr[]='欺诈评分';
            }
            if($field == 'zm_score'){
                $headArr[]='芝麻分';
            }
            if($field == 'is_matched'){
                $headArr[]='行业关注名单';
            }
            if($field == 'hit'){
                $headArr[]='欺诈关注清单';
            }
            if($field == 'create_time'){
                $headArr[]='审核时间';
            }
        }

        $filename="征信记录表".date('Y_m_d',time());

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

        //print_r($data);exit;
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


}
