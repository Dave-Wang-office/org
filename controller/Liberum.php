<?php
namespace app\admin\controller;
use think\facade\Request;
use think\Db;
use think\facade\Session;

class Liberum extends Common{

    //公海列表
    public function index(){
        if(request()->isPost()){
            $key=input('post.key');
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $list = db('crm_leads')
                ->where(['status'=>2,'issuccess'=>-1])
                ->order('ut_time desc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }

        $ghTypeList = Db::table('crm_liberum_type')->select();



        $this -> assign('ghTypeList',$ghTypeList);

        return $this->fetch();
    }


    //公海类型
    public function libTypeList(){
        if(request()->isPost()){
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $list = db('crm_liberum_type')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }
        return $this->fetch('liberum/lib_type_list');
    }
    //添加公海类型
    public function libTypeAdd(){
        if(request()->isPost()){
            $data['type_name'] = Request::param('type_name');
            $data['add_time'] = time();
            $result = Db::table('crm_liberum_type')->insert($data);
            if ($result){
                $msg = ['code' => 0,'msg'=>'添加成功！','data'=>[]];
                return json($msg);
            }else{
                $msg = ['code' => -200,'msg'=>'添加失败！','data'=>[]];
                return json($msg);
            }
        }
        return $this->fetch('liberum/lib_type_add');
    }
    //编辑公海类型
    public function libTypeEdit(){
        if (Request::isAjax()){
            $data  = Request::param();
            $result = Db::table('crm_liberum_type')->where(['id'=>$data['id']])->update($data);
            if ($result){
                $msg = ['code' => 0,'msg'=>'编辑成功！','data'=>[]];
                return json($msg);
            }else{
                $msg = ['code' => -200,'msg'=>'编辑失败！','data'=>[]];
                return json($msg);
            }
        }


        $result = Db::table('crm_liberum_type') ->where(['id' => Request::param('id')])->find();
        $this -> assign('result',$result);
        return $this -> fetch('liberum/lib_type_edit');
    }
    //删除公海类型
    public function libTypeDel(){
        $id = Request::param('id');
        $result = Db::table('crm_liberum_type')->where('id',$id)->delete();
        if ($result){
            $msg = ['code' => 0,'msg'=>'删除成功！','data'=>[]];
            return json($msg);
        }else{
            $msg = ['code' => -200,'msg'=>'删除失败！','data'=>[]];
            return json($msg);
        }
    }


    //公海搜索
    public function liberumSearch(){
        $page =input('page')?input('page'):1;
        $limit =input('limit')?input('limit'):config('pageSize');
        $keyword = Request::param('keyword');
        $list= model('liberum') -> getLiberumSearchList($page,$limit,$keyword);
        return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];

    }

    //写跟进
    public function libdialog(){
        $result = Db::table('crm_leads')->where(['id'=>Request::param('id')])->find();

        $result['comment']= Db::table('crm_comment')->alias('com')->join('admin adm','com.user_id = adm.admin_id')->where(['leads_id'=>Request::param('id')])->field('com.*,adm.username,adm.avatar')->select();
        foreach ($result['comment'] as $k => $v){
            $result['comment'][$k]['reply'] = Db::table('crm_reply')->where(['comment_id'=>$v['id']])->select();
        }

        $this ->assign('result',$result);
        return $this -> fetch('liberum/libdialog');
    }
    //抢客户
    public function robClient(){
        $data['id'] = Request::param('id');
        //抢客户之前，先去判断是否可抢
        // 检测当前剩余抢的次数
        $curname = Session::get('username');
        $curget = Db::table('admin')->where(['username'=>$curname])->field('curgetnum')->find();
        $curgetnum = $curget['curgetnum'];
        $sysinfo = Db::table('system')->where(['id'=>1])->field('maxgetnum,custlimit')->find();
        $maxgetnum = $sysinfo['maxgetnum'];
        $custlimit = $sysinfo['custlimit'];
       
        if ($curgetnum>=$maxgetnum) {
            $msg = ['code' => -200,'msg'=>'抱歉，您当月抢的次数已经达到上限'.$maxgetnum.'次!','data'=>[]];
            return json($msg);
        }
        // 检测当前客户数最大数量
        $wherecust = [];
        $wherecust['pr_user'] = $curname;
        $wherecust['status'] = 1;
        $wherecust['ispublic'] = 2;
        $wherecust['issuccess'] = -1;
        $maxcustnum = Db::table('crm_leads')->where($wherecust)->count('id');
        if($maxcustnum>=$custlimit){
            $msg = ['code' => -200,'msg'=>'抱歉，您抢得的客户数量已达上限'.$custlimit.'!','data'=>[]];
            return json($msg);
        }

        $gh_client = Db::table('crm_leads')->where(['id' => $data['id']])->where(['status'=> 2])->find();
        if ($gh_client){
            $data['to_kh_time'] = date("Y-m-d H:i:s",time());
            $data['ut_time'] = date("Y-m-d H:i:s",time());
            
            $data['status'] = 1;//0-线索，1客户，2公海
            $data['pr_user_bef'] = $gh_client['pr_user'];
            $data['pr_user'] = Session::get('username');
             // 状态变化 设置私人公共变化
            $data['ispublic'] = 2;//1 公共 2私人抢夺 3 私人添加

            $result = Db::table('crm_leads')->where(['id'=>$data['id']])->update($data);
            if ($result){
                // 抢的次数增加1  公海不增加次数
                // $curgetnum = $curgetnum + 1;
                // $curgetnum = Db::table('admin')->where(['username'=>$curname])->update(['curgetnum'=>$curgetnum]);

                $msg = ['code' => 0,'msg'=>'抢客户成功！','data'=>[]];
                return json($msg);
            }else{
                $msg = ['code' => -200,'msg'=>'抢客户失败！','data'=>[]];
                return json($msg);
            }
        }else{
            $msg = ['code' => -200,'msg'=>'抱歉，该客户已被抢走！','data'=>[]];
            return json($msg);
        }
    }



}