<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\RepayModel as RepayModel;
use Home\Model\MoneyModel as MoneyModel;
use Home\Model\WeixinModel as WeixinModel;
header("Content-Type:text/html;charset=utf-8");
class PreController extends BaseController{
    public function index(){
            $data = M()
            ->table('free_user user,free_loan loan')
            ->where("user.user_id=loan.user_id and loan.is_pay>0")
            ->select();
                foreach ($data as $k => $v) {
                   // 手机号(用户名) 持卡人姓名   银行卡绑定手机号    身份证号    银行卡号    借款金额    打款金额         借款天数    打款时间    借款期限    到期时间 逾期天数
                      if($v['loan_time']==1){
                        $loan_time=7;
                        $return_arr=RepayModel::overdue_show($v['is_pay'],$loan_time,$v['renewal_days'],$v['loan_amount']);
                        $shouxufei=MoneyModel::poundage($v['loan_amount'],$v['interest'],$v['cuts_interest']);
                        if($return_arr['day']<=0 && $return_arr['day']>=(-2)){
                            $arr[$k]['shouxufei']=$shouxufei;
                            $arr[$k]['day']=$return_arr['day'];
                            $arr[$k]['loan_time'] = $loan_time;
                            $arr[$k]['due_time']=$return_arr['time'];
                            $arr[$k]['overdue_money']=$return_arr['overdue_money'];
                            $arr[$k]['user_id'] = $v['user_id'];
                            $arr[$k]['user_name'] = $v['user_name'];
                            $arr[$k]['bank_name'] = $v['bank_name'];
                            $arr[$k]['bank_tel'] = $v['bank_tel'];
                            $arr[$k]['query_message'] = $v['query_message'];
                            $arr[$k]['open_id'] = $v['open_id'];
                            $arr[$k]['identity'] = $v['identity'];
                            $arr[$k]['bank_card'] = $v['bank_card'];
                            $arr[$k]['loan_amount'] = $v['loan_amount'];
                            $arr[$k]['is_pay'] = $v['is_pay'];
                            $arr[$k]['renewal_days'] = $v['renewal_days'];
                            $sum+=1;
        
                        if($return_arr['day']>=(-2)&&$return_arr['day']<1){
                            $pre_sum+=1;
                        }
                        if($return_arr['day']>=1){
                            $heji_money+=$shouxufei;
                            $money_sum+=$v['loan_amount'];
                            $overdue_sum+=1;
                            $overdue_sum_money+=$return_arr['overdue_money'];
                        }
                        if($return_arr['day'] > 0 && $return_arr['day']<4){
                            $let_sum+=1;
                        }
                        }
                      }else if($v['loan_time']==2){
                        $loan_time=14;
                        $return_arr=RepayModel::overdue_show($v['is_pay'],$loan_time,$v['renewal_days'],$v['loan_amount']);
                        $shouxufei=MoneyModel::poundage($v['loan_amount'],$v['interest'],$v['cuts_interest']);
                        if($return_arr['day']<=0 && $return_arr['day']>=(-2)){
                            $arr[$k]['shouxufei']=$shouxufei;
                            $arr[$k]['day']=$return_arr['day'];
                            $arr[$k]['loan_time'] = $loan_time;
                            $arr[$k]['due_time']=$return_arr['time'];
                            $arr[$k]['overdue_money']=$return_arr['overdue_money'];
                            $arr[$k]['user_id'] = $v['user_id'];
                            $arr[$k]['user_name'] = $v['user_name'];
                            $arr[$k]['bank_name'] = $v['bank_name'];
                            $arr[$k]['bank_tel'] = $v['bank_tel'];
                            $arr[$k]['query_message'] = $v['query_message'];
                            $arr[$k]['open_id'] = $v['open_id'];
                            $arr[$k]['identity'] = $v['identity'];
                            $arr[$k]['bank_card'] = $v['bank_card'];
                            $arr[$k]['loan_amount'] = $v['loan_amount'];
                            $arr[$k]['is_pay'] = $v['is_pay'];
                            $arr[$k]['renewal_days'] = $v['renewal_days'];
                            $sum+=1;
        
                        if($return_arr['day']>=(-2)&&$return_arr['day']<1){
                            $pre_sum+=1;
                        }
                        if($return_arr['day']>=1){
                            $heji_money+=$shouxufei;
                            $money_sum+=$v['loan_amount'];
                            $overdue_sum+=1;
                            $overdue_sum_money+=$return_arr['overdue_money'];
                        }
                        if($return_arr['day'] > 0 && $return_arr['day']<4){
                            $let_sum+=1;
                        }
                        }
                      }
                }
                    $page_length=20;
                    $num=count($arr);
                    $page_total=new \Think\Page($num,$page_length);
                    $cart_data=array_slice($arr,$page_total->firstRow,$page_total->listRows);
                    $show=$page_total->show();
                    $this->assign('heji_money',$heji_money);
                    $this->assign('overdue_sum_money',$overdue_sum_money);
                    $this->assign('money_sum',$money_sum);
                    $this->assign('let_sum',$let_sum);
                    $this->assign('sum',$sum);
                    $this->assign('pre_sum',$pre_sum);
                    $this->assign('overdue_sum',$overdue_sum);
                    $this->assign('page',$show);
                    $this->assign('arr',$cart_data);
                    $this->assign('count',$count);
                    $this->display();
    }

    public function note_template(){
        $get=I('get.');
        $data = M()
            ->table('free_user user,free_loan loan')
            ->where("user.user_id=loan.user_id and loan.is_pay>0")
            ->select();
        foreach ($data as $k => $v) {
            if($v['loan_time']==1){
                $loan_time=7;
            }else if($v['loan_time']==2){
                $loan_time=14;
            }
            $return_arr=RepayModel::overdue_show($v['is_pay'],$loan_time,$v['renewal_days'],$v['loan_amount']);
            $shouxufei=MoneyModel::poundage($v['loan_amount'],$v['interest'],$v['cuts_interest']);
            $time=date('m月d日',$return_arr['time']);
            $money=$v['loan_amount']+$shouxufei;
            $all_money=$money+$return_arr['overdue_money'];
            switch ($get['code']) {
                case '1'://预催收
                    if($v['loan_time']==1){
                        if($return_arr['day']<=0 && $return_arr['day']>=(-1)){
                            $bool = WeixinModel::sex($v['identity']);
                            $sex = $bool?"先生":"女士";
                            $arr[$k]['note']='【蜻蜓卡】尊敬的'.$v['u_name'].$sex.'，您的还款到期时间为'.$time.'，本期应还'.$money.'元。请在微信公众号“蜻蜓白卡”中点开还款页面主动按时还款，以免逾期！如已还款请忽略此消息。';
                            $arr[$k]['tel']=$v['user_name'];
                        }
                    }else if($v['loan_time']==2){
                        if($return_arr['day']<=0 && $return_arr['day']>=(-2)){
                            $bool = WeixinModel::sex($v['identity']);
                            $sex = $bool?"先生":"女士";
                            $arr[$k]['note']='【蜻蜓卡】尊敬的'.$v['u_name'].$sex.'，您的还款到期时间为'.$time.'，本期应还'.$money.'元。请在微信公众号“蜻蜓白卡”中点开还款页面主动按时还款，以免逾期！如已还款请忽略此消息。';
                            $arr[$k]['tel']=$v['user_name'];
                        }
                    }
                break;

                case '2';//容时期
                    if($return_arr['day']>=1 && $return_arr['day']<=3){
                        $arr[$k]['note']='【蜻蜓卡】 '.$v['u_name'].'，身份证号：'.$v['identity'].'；你在我公司办理的借款'.$money.'元（'.$v['loan_amount'].'+'.$shouxufei.'）；截止今日你已逾期第'.$return_arr['day'].'天，逾期费用为'.$return_arr['overdue_money'].'元；应还金额为'.$all_money.'元。如您继续逾期，我们将在您逾期三天后请求您如下授权联系人的协助：';
                        $arr[$k]['tel']=$v['user_name'];
                    }
                break;

                case '3';//逾期第四-六天
                    if($return_arr['day']>=1 && $return_arr['day']<=3){
                        $identity=substr_replace($v['identity'],'****',6,8);
                        $arr[$k]['note']='【蜻蜓卡】请劝告还款：'.$v['u_name'].', 身份证号：'.$identity.'，在我公司的借款'.$money.'元（'.$v['loan_amount'].'+'.$shouxufei.'）；截止今日您已逾期'.$return_arr['day'].'天，逾期费用为'.$return_arr['overdue_money'].'元；应还金额'.$all_money.'元。如您继续逾期，我们将在今晚24点后请求您如下授权联系人！';
                        $arr[$k]['user_name']='u'.$v['user_name'];
                        $arr[$k]['mobile']='i'.$v['user_name'];
                        $arr[$k]['day']=$return_arr['day'];
                        $arr[$k]['tel']=$v['user_name'];
                    }
                break;

                case '4';//逾期5-7天紧急联系人
                    if($return_arr['day']>=2 && $return_arr['day']<=4){
                        $identity=substr_replace($v['identity'],'****',6,8);
                        $arr[$k]['note']='【蜻蜓卡】请劝告还款：'.$v['u_name'].', 身份证号：'.$identity.'，在我公司的借款'.$money.'元（'.$v['loan_amount'].'+'.$shouxufei.'）；已逾期'.$return_arr['day'].'天，逾期费用为'.$return_arr['overdue_money'].'元；应还金额为'.$all_money.'元。请尽快还款。相关联系人帮忙联系其本人转告24小时内及时处理借款或主动与我们取得联系。（家人朋友望转告到位）谢谢您的配合。如不认识此人，请忽略此消息。';
                        $arr[$k]['user_name']='u'.$v['user_name'];
                        $arr[$k]['mobile']='i'.$v['user_name'];
                        $arr[$k]['day']=$return_arr['day']-1;
                        $arr[$k]['tel']=$v['user_name'];
                    }
                break;
                case '5';//逾期7天
                    if($return_arr['day']>6 && $return_arr['day']<8){
                        $addres=str_replace('|','',$v['addres']);
                        $com_addres=str_replace('|','',$v['com_addres']);
                        $arr[$k]['note']='【蜻蜓卡】'.$v['u_name'].'，限你两日之内补足所欠款项，如你继续拖欠，我们将向信用部门反馈你的个人信用记录，你将被拉入信用黑名单，同时我们会在以下地址扩散你的欠款信息（姓名、身份证号、身份证照片、欠款时间、欠款金额）：'.$addres.'、'.$com_addres.'';
                        $arr[$k]['tel']=$v['user_name'];
                    }
                break;
                case '6';//逾期9天
                    if($return_arr['day']>8 && $return_arr['day']<10){
                        $identity=substr_replace($v['identity'],'****',6,8);
                        $arr[$k]['note']=''.$v['u_name'].'（'.$identity.'）由于你在《蜻蜓白卡》欠款一案时间已超限并有恶意拖欠嫌疑，我律所已经受蜻蜓白卡委托正式对你欠款一案进行立案，同时律师信函已经正式寄出，并提报你本人全国失信被执行人名单。依据《中华人民共和国<刑法>》第266条相关延伸司法定义，我部已准备以恶意欺诈向当地公安局申请立案调查，并向你户籍地人民法院报走相关程序，相关申请材料（申请表、账单、证明材料、民事诉讼状、应诉通知书、举证通知书、传达回证、传票）将在明日下午5点正式提交相关部门，烦请做好应诉传唤并提前准备足全额款项。 如非本人请如实转告当事人！';
                        $arr[$k]['tel']=$v['user_name'];
                    }
                break;
                case '7';//逾期10天

                $addres=explode('|',$v['addres']);
                if($addres[1]=='市辖区'||$addres[1]=='省直辖县级行政区划'){
                    $addre=$addres[0];
                }else{
                    $addre=$addres[1];
                }
                    if($return_arr['day']>9 && $return_arr['day']<11){
                        $identity=substr_replace($v['identity'],'****',6,8);
                        $court_time=date('Y年m月d日',strtotime('+3 day'));
                        $arr[$k]['note']=''.$v['u_name'].'（证件号：'.$identity.'）关于你在《蜻蜓白卡》贷款诈骗一案，将预定于'.$court_time.'下午14时于【'.$addre.'】人民法院第三审判庭开庭审判，请做应诉出庭准备。法院传票和起诉状副本以信件的形式已寄至你的户籍所在地。若缺席，法院将做缺席判决，判决结果由当地强制执行局强制执行，上报公安金融诈骗通缉犯名单。将对你进行全面追逃。一经逮捕你将面临监禁。（庭前调解杨主任：17188731205）如非本人请如实转告当事人！';
                        $arr[$k]['tel']=$v['user_name'];
                    }
                break;

                default:
                break;
            }
                
                
        }
        $this->arr=$arr;
        $this->display();
    }

    public function note_ajax(){
        $post=I('post.');
        $tel_record=M('tel_record');
        if($post['id']==null){
            exit;
        }
        $where['user_name']=substr($post['id'],1);
        $tel_record_data=$tel_record->where($where)->find();
        $length=strlen($tel_record_data['request_id']);
        if($length==36){
            $apix=M('apix');
            $apix_data=$apix->field('callrecords')->where($where)->find();
            $callrecords=json_decode($apix_data['callrecords'],1);
            $i=0;
            foreach ($callrecords as $key => $value) {
                preg_match('/^1[34578]\d{9}$/A',$value['phoneNo'],$match);
                if(!empty($match)){
                    if($post['day']==1){
                        if($i<5){
                            $mobile.=$value['phoneNo'].'、';
                        }
                    }
                    if($post['day']==2){
                        if($i>4 && $i<10){
                            $mobile.=$value['phoneNo'].'、';
                        }
                    }
                    if($post['day']==3){
                        if($i>9 && $i<15){
                            $mobile.=$value['phoneNo'].'、';
                        }
                    }
                    $i+=1;
                }
            }
        }else{
            $wdinfo=M('wdinfo');
            $wdinfo_data=$wdinfo->field('tel_info')->where($where)->find();
            $tel_info=json_decode($wdinfo_data['tel_info'],1);
            $tel_info = $this->order($tel_info,$lev='call_len');
            foreach ($tel_info as $key => $value) {
                preg_match('/^1[34578]\d{9}$/A',$value['phone_num'],$match);
                if(!empty($match)){
                    if($post['day']==1){
                        if($i<5){
                            $mobile.=$value['phone_num'].'、';
                        }
                    }
                    if($post['day']==2){
                        if($i>4 && $i<10){
                            $mobile.=$value['phone_num'].'、';
                        }
                    }
                    if($post['day']==3){
                        if($i>9 && $i<15){
                            $mobile.=$value['phone_num'].'、';
                        }
                    }
                    $i+=1;
                }
            }
        }
        $mobile=mb_substr($mobile,0,-1,"UTF-8").'。';
        $this->ajaxReturn($mobile);
    }

    public function export_pre(){
        $data = M()
            ->table('free_user user,free_loan loan')
            ->where("user.user_id = loan.user_id AND loan.is_pay !=0")
            ->select();
        foreach ($data as $k => $v) {
         // 手机号(用户名) 持卡人姓名   银行卡绑定手机号    身份证号    银行卡号    借款金额    打款金额         借款天数    打款时间    借款期限    到期时间 逾期天数
            if($v['loan_time']==1){
                 $loan_time=7;
            }else if($v['loan_time']==2){
                 $loan_time=14;
            }
                      
      $return_arr=RepayModel::overdue_show($v['is_pay'],$loan_time,$v['renewal_days'],$v['loan_amount']     );
      $shouxufei=MoneyModel::poundage($v['loan_amount'],$v['interest'],$v['cuts_interest']);
                      //手机号(用户名)  持卡人姓名 银行卡绑定手机号  身份证号  银行卡号  借款金额  打款时间  借款期限  到期时间  逾期天数  本息  逾期费用
            if($return_arr['day'] > (-3) && $return_arr['day'] < (1)){
                $arr[$k]['user_name']=$v['user_name']; 
                $arr[$k]['bank_name']=$v['bank_name'];

                $arr[$k]['linkman_name']=$v['linkman_name'];
                $arr[$k]['linkman_tel']=$v['linkman_tel'];

                $arr[$k]['bank_tel']=$v['bank_tel']; 
                $arr[$k]['identity']=$v['identity'];
                $arr[$k]['bank_card']=$v['bank_card'];
                $arr[$k]['loan_amount']=$v['loan_amount'];
                $arr[$k]['is_pay']=date('Y年m月d日',$v['is_pay']);
                $arr[$k]['loan_time']=$loan_time;
                $arr[$k]['time']=date('Y年m月d日',$return_arr['time']);
                $arr[$k]['day']=$return_arr['day'];
                $arr[$k]['loan_amounts']=$v['loan_amount']+$shouxufei;
                $arr[$k]['overdue_money']=$return_arr['overdue_money'];

            }
                }
        $this->goods_export($arr);

    }

    public function goods_export($goods_list){
        $goods_list = array_merge($goods_list);
        $data = array();
        foreach ($goods_list as $k=>$goods_info){
            $data[$k][user_name] = " $goods_info[user_name]";
            $data[$k][bank_name] = " $goods_info[bank_name]";
            $data[$k][bank_tel] = " $goods_info[bank_tel]";
            $data[$k][identity] = " $goods_info[identity]";
            $data[$k][bank_card] = " $goods_info[bank_card]";

            $data[$k][loan_amount] = " $goods_info[loan_amount]";
            $data[$k][is_pay] = " $goods_info[is_pay]";
            $data[$k][loan_time] = " $goods_info[loan_time]";
            $data[$k][time] = " $goods_info[time]";
            $data[$k][day] = " $goods_info[day]";

            $data[$k][loan_amounts] = " $goods_info[loan_amounts]";
            $data[$k][overdue_money] = " $goods_info[overdue_money]";

            $data[$k][linkman_name] = " $goods_info[linkman_name]";
            $data[$k][linkman_tel] = " $goods_info[linkman_tel]";
            
        }
        foreach ($data as $field=>$v){
            if($field == 'user_name'){
                $headArr[]='手机号(用户名)';
            }
            if($field == 'bank_name'){
                $headArr[]='持卡人姓名';
            }
            if($field == 'bank_tel'){
                $headArr[]='银行卡绑定手机号';
            }
            if($field == 'identity'){
                $headArr[]='身份证号';
            }
            if($field == 'bank_card'){
                $headArr[]='银行卡号';
            }
            if($field == 'loan_amount'){
                $headArr[]='借款金额';
            }
            if($field == 'is_pay'){
                $headArr[]='打款时间';
            }
            if($field == 'loan_time'){
                $headArr[]='借款期限';
            }
            if($field == 'time'){
                $headArr[]='到期时间';
            }
            if($field == 'day'){
                $headArr[]='逾期天数';
            }
            if($field == 'loan_amounts'){
                $headArr[]='本息';
            }
            if($field == 'overdue_money'){
                $headArr[]='逾期费用';
            }
            if ($field == 'linkman_name') {
                $headArr[]='紧急联系人姓名';
            }
            if ($field == 'linkman_tel') {
                $headArr[]='紧急联系人电话';
            }
        }
        $filename="逾期表".date('Y_m_d',time());
        WeixinModel::getExcel($filename,$headArr,$data);
    }
    public function credit(){
        $user_model = M('User');
        $where['user_name'] = I('get.id');   
        $open_id=I("get.open_id");
        if($open_id==''){
        }else{
            $access_token = WeixinModel::getToken();
            $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$open_id&lang=zh_CN";
            $data = WeixinModel::request_get($url);
            $token = json_decode(stripslashes($data));
            $array = json_decode(json_encode($token), true);
            $map['open_id'] = $open_id;
            $arr = $user_model->where($where)->find();
            $array['user_id'] = $arr['user_id'];
            $array['user_name'] = $arr['user_name'];
            $array['u_name'] = $arr['u_name'];
            $this->array=$array;
        }
   /*  淘宝信息 */
        $taobao_info=M('taobao_info');
        $taobao_info_data=$taobao_info->where($where)->find();
        if($taobao_info_data){
            $accountSafeInfo=json_decode($taobao_info_data['accountsafeinfo'],1);
            $addrs=json_decode($taobao_info_data['addrs'],1);
            $bindAccountInfo=json_decode($taobao_info_data['bindaccountinfo'],1);
            $personalInfo=json_decode($taobao_info_data['personalinfo'],1);
            $orderlist=json_decode($taobao_info_data['orderlist'],1);
            $this->mytime=$taobao_info_data['create_time'];
            $this->taobao_arr=$orderlist;
            $this->lost_money=$taobao_info_data['money'];
            $this->personalInfo=$personalInfo;
            $this->accountSafeInfo=$accountSafeInfo;
            $this->addrs=$addrs;
            $this->bindAccountInfo=$bindAccountInfo;
            $hint="淘宝的信息在万达下面";
        }else{
            $taobao=M('taobao');
            $taobao_data=$taobao->where($where)->find();
            if($taobao_data){
                $hint="淘宝的信息未采集到";
            }else{
                $hint="该用户没有淘宝信息，请查看时间是否在5月19日之前";
            }
        }
        $this->hint=$hint;
        /*  淘宝信息 */
        $tel_record=M('tel_record');
        $tel_record_data=$tel_record->where($where)->find();
        $request_id=strlen($tel_record_data['request_id']);
        if($request_id==36){
            $apix=M('apix');
            $apix_data=$apix->where($where)->find();
            $apix_data['basicInfo']=json_decode($apix_data['basic'],1);
            $apix_data['phoneInfo']=json_decode($apix_data['phone'],1);
            $apix_data['deceitRisk']=json_decode($apix_data['decei'],1);
            $apix_data['consumeInfo']=json_decode($apix_data['consume'],1);
            $apix_data['callRecordsInfo']=json_decode($apix_data['callrecords'],1);
            $apix_data['contactAreaInfo']=json_decode($apix_data['contactarea'],1);
            $apix_data['specialCallInfo']=json_decode($apix_data['specialcall'],1);
            $apix_data['phoneOffInfos']=json_decode($apix_data['phoneoff'],1);
            $this->hinti="Apix数据库读出数据";
            $this->apix_data=$apix_data;
            $this->display('Examine/apix');
        }else if($request_id==40){//万达信息
            $wdinfo_model = M('wdinfo');
            $result = $wdinfo_model->where($where)->find();
            $accounts = json_decode($result['accounts'],true);
            $casic = json_decode($result['casic'],true);
            $result = json_decode($result['tel_info'],true);
            $result = $this->order($result,$lev='call_len');
            foreach ($result as $k => $v) {
                preg_match('/^1[34578]\d{9}$/A',$v['phone_num'],$match);
                if (!empty($match)) {
                    $results[$k] = $v;
                }
            }
            $len = count($accounts[0]['behavior']);
            foreach ($accounts[0]['behavior'] as $k => $v) {
                $money +=$v['total_amount'];
            }
            $avg = $money/$len;
            $this->assign('avg',$avg);
            $this->assign('result',$results);
            $this->assign('accounts',$accounts[0]['behavior']);
            $this->assign('casic',$casic);
            $this->display();
        }else{   //request  未储存成功！
            $this->hit="未获取到通讯记录信息，请查看时间是否在5月3日之前";
             $this->display();
        }  
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