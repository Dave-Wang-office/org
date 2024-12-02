<?php
namespace app\admin\controller;
use think\Db;
use think\facade\Env;
use think\facade\Request;
use think\facade\Session;
class Index extends Common
{
    public function initialize(){
        parent::initialize();
    }
    public function index(){
        //导航
        // 获取缓存数据
        $authRule = cache('authRule');
        if(!$authRule){
            $authRule = db('auth_rule')->where('menustatus=1')->order('sort')->select();
            cache('authRule', $authRule, 3600);
       }
        //声明数组
        $menus = array();
        foreach ($authRule as $key=>$val){
            $authRule[$key]['href'] = url($val['href']);
            if($val['pid']==0){
                if(session('aid')!=1){
                    if(in_array($val['id'],$this->adminRules)){
                        $menus[] = $val;
                    }
                }else{
                    $menus[] = $val;
                }
            }
        }
        foreach ($menus as $k=>$v){
            foreach ($authRule as $kk=>$vv){
                if($v['id']==$vv['pid']){
                    if(session('aid')!=1) {
                        if (in_array($vv['id'], $this->adminRules)) {
                            $menus[$k]['children'][] = $vv;
                        }
                    }else{
                        $menus[$k]['children'][] = $vv;
                    }
                }
            }
        }
        // 
        $curname = Session::get('username');
        $curget = Db::table('admin')->where(['username'=>$curname])->field('curgetnum')->find();
        $curgetnum = $curget['curgetnum'];
        $sysinfo = Db::table('system')->where(['id'=>1])->field('maxgetnum,custlimit')->find();
        $maxgetnum = $sysinfo['maxgetnum'];
        $this->assign('curgetnum',($maxgetnum - $curgetnum));
        $this->assign('menus',json_encode($menus,true));
        return $this->fetch();
    }
    public function main(){

        //0 线索，1客户，2公海
        $cluesCount = Db::table('crm_leads')->where(['status'=> 0])->count();
        $clientCount = Db::table('crm_leads')->where(['status'=> 1])->count();
        $liberumCount = Db::table('crm_leads')->where(['status'=> 2])->count();
        // 区别管理员和业务员
        
        $this->assign('cluesCount', $cluesCount);
        $this->assign('clientCount', $clientCount);
        $this->assign('liberumCount', $liberumCount);


        //获取本周线索 ->whereTime('at_time', 'week') 
        $cluesCount_week = Db::table('crm_leads')->where(['status'=> 0,'pr_user'=>Session::get('username')])->count();
        //获取本月转客户数据 ->whereTime('to_kh_time', 'month') 
        $clientCount_month = Db::table('crm_leads')->where(['status'=> 1,'issuccess'=> -1,'pr_user'=>Session::get('username')])->count();
        //获取今年公海数据 ->whereTime('to_gh_time', 'year') 
        $liberumCount_year = Db::table('crm_leads')->where(['status'=> 2])->count();
        //成交数 TODO
        $clientCount_cj = Db::table('crm_leads')->where(['status'=> 1,'issuccess'=> 1,'pr_user'=>Session::get('username')])->count();
        $this->assign('clientCount_cj', $clientCount_cj);

        //月度排名（名）、月目标（元）、已成交（元）、完成率（%）、已成交（单）、提成点（%），
        //管理员添加业绩设置权限。
        $userlist = Db::name('admin')->where('group_id','<>', 1)->field('admin_id,username,mubiao,ticheng')->select();
       
        //所有业务员
        foreach ($userlist as $key => $value) {
            $wheretoday = [];
            $wheretoday['pr_user'] = $value['username'];
            $wheretoday['status'] = '审核通过';
            $money_month = Db::name('crm_client_order')
                            ->where($wheretoday)
                            ->whereTime('create_time','month')
                            ->sum('money');
           
            $value['money_month'] = $money_month;
            if ($value['mubiao']>0) {
                $value['wanchenglv'] = round($money_month/$value['mubiao']*100,2);
            }else{
                $value['wanchenglv']=0;
            }
           
            $number_month = Db::table('crm_client_order')->where($wheretoday)->whereTime('create_time','month')->count('id');
            $value['number_month'] = $number_month;
            $userlist[$key] = $value;
        }
    
        // 数组排序
        array_multisort(array_column($userlist,'money_month'),SORT_DESC,$userlist);
   
        $this->assign('userlist', $userlist);

        //本人跟进动态
        //最近跟进动态  
        $result = Db::table('crm_leads')
            ->alias('l')
            ->join('crm_comment c','c.leads_id = l.id')
            ->join('admin a','c.user_id = a.admin_id')
            ->field('l.id,a.username,a.avatar,l.kh_name,c.reply_msg,c.create_date')
            ->order('c.create_date desc')
            ->where(['l.pr_user'=> Session::get('username')])
            ->limit(10)->select();
        $this->assign('result', $result);


      	$strTimeToString = "000111222334455556666667";
        $strWenhou = array('夜深了，','凌晨了，','早上好！','上午好！','中午好！','下午好！','晚上好！','夜深了，');
        //echo $strWenhou[(int)$strTimeToString[(int)date('G',time())]];
        $this->assign('wenhou', '尊敬的管理员'. $strWenhou[(int)$strTimeToString[(int)date('G',time())]]);
      
      

        $this->assign('cluesCount_week', $cluesCount_week);
        $this->assign('clientCount_month', $clientCount_month);
        $this->assign('liberumCount_year', $liberumCount_year);
        // 获取待办事项
        //今日已跟进客户*个，未跟进*个，跟进率*%
        //last_up_time

        $wheretoday = [];
        $wheretoday['pr_user'] = Session::get('username');
        $wheretoday['status'] = 1;
        $wheretoday['issuccess'] = -1;
        $all_count = Db::table('crm_leads')->where($wheretoday)->count();
        $today_count = Db::table('crm_leads')->where($wheretoday)->whereTime('last_up_time','today')->count();
        if ($all_count > 0) {
            $genjinlv = $today_count/$all_count*100;
        }else{
            $genjinlv = 0;
        }
        
        $this->assign('all_count', $all_count-$today_count);
        $this->assign('today_count', $today_count);
        $this->assign('genjinlv', round($genjinlv,2));

        $version = Db::query('SELECT VERSION() AS ver');
        $config  = [
            'url'             => $_SERVER['HTTP_HOST'],
            'document_root'   => $_SERVER['DOCUMENT_ROOT'],
            'server_os'       => PHP_OS,
            'server_port'     => $_SERVER['SERVER_PORT'],
            'server_ip'       => $_SERVER['SERVER_ADDR'],
            'server_soft'     => $_SERVER['SERVER_SOFTWARE'],
            'php_version'     => PHP_VERSION,
            'mysql_version'   => $version[0]['ver'],
            'max_upload_size' => ini_get('upload_max_filesize')
        ];
        $this->assign('config', $config);
        return $this->fetch();
    }
    public function navbar(){
        return $this->fetch();
    }
    public function nav(){
        return $this->fetch();
    }
    public function clear(){
        $R = Env::get('runtime_path');
        if ($this->_deleteDir($R)) {
            $result['info'] = '清除缓存成功!';
            $result['status'] = 1;
        } else {
            $result['info'] = '清除缓存失败!';
            $result['status'] = 0;
        }
        $result['url'] = url('admin/index/index');
        return $result;
    }
    private function _deleteDir($R)
    {
        $handle = opendir($R);
        while (($item = readdir($handle)) !== false) {
            if ($item != '.' and $item != '..') {
                if (is_dir($R . '/' . $item)) {
                    $this->_deleteDir($R . '/' . $item);
                } else {
                    if (!unlink($R . '/' . $item))
                        die('error!');
                }
            }
        }
        closedir($handle);
        return rmdir($R);
    }

    //退出登陆
    public function logout(){
        session(null);
        $this->redirect('login/index');
    }
    
}
