<?php
namespace app\task\controller;
use think\Db;
use think\facade\Env;
use think\Controller;
class Task extends Controller{
     // 定时任务 每日监测
    public function autotask(){
        $daynum = date('d');
        $sysinfo = Db::table('system')->where(['id'=>1])->field('maxgetnum,autoday')->find();
        $maxgetnum = $sysinfo['maxgetnum'];
        $autoday = $sysinfo['autoday'];
        if ($daynum == 1) {
           // 循环所有用户 每月一日抢客户次数恢复30
            $udata = [];
            $udata['curgetnum'] = 0;
            var_dump('月次数恢复:'. $maxgetnum);
            Db::name('admin')->where('1=1')->update($udata);
        }
        // 所有客户跟进时间 7日未跟进自动划入公海
        // 查询所有未成交的客户
        $kehulist = Db::name('crm_leads')->where(['status'=>1,'issuccess'=>-1])->select();
        foreach ($kehulist as $key => $value) {
            $last_up_time = $value['last_up_time'];
            $currentTime=time();//当前时间
            if(!empty($last_up_time)){
            	$cnt=$currentTime-strtotime($last_up_time);//与已知时间的差值
	            $days = floor($cnt/(3600*24));//算出天数
	            
	            
	            if ($days >= $autoday && $days < 365) {
	                // 自动转入公海
	                //$data['pr_gh_type'] = $pr_gh_type;
	                var_dump($value['id'].'记录时间:'.$last_up_time . '  ---未跟进天数:'.$days.' ---间隔时间：'.$autoday);
	                $data['to_gh_time'] = date("Y-m-d H:i:s",time());
	                $data['status'] = 2;//0-线索，1客户，2公海
	                $data['id']  = $value['id'];
	                $result = Db::table('crm_leads')->where(['id'=>$data['id']])->update($data);
	            }
            }
            

        }

       
    }
}