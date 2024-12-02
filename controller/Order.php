<?php
namespace app\admin\controller;

use think\Db;
use think\facade\Request;
use think\facade\Session;
use think\facade\Env;
class Order extends Common{
    //客户列表
    public function index(){
        if(request()->isPost()){
            $key=input('post.key');
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $list = db('crm_client_order')
                ->order('create_time desc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }

        //查询所有管理员（去除admin）
        $adminResult = Db::name('admin')->where('group_id','<>', 1)->field('admin_id,username')->select();
        $this -> assign('adminResult',$adminResult);

        return $this->fetch();
    }

    //（我的客户）列表
    public function personindex(){

        if(request()->isPost()){
            $key=input('post.key');
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $list = db('crm_client_order')
                ->where(['pr_user'=> Session::get('username')])
                ->order('create_time desc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }
        return $this->fetch();
    }


 
    //新建客户
    public function add(){
        if(request()->isPost()){
            $data['cphone'] = Request::param('cphone');
            $data['cname'] = Request::param('cname');
            
            if(Request::param('pr_user')){
               $data['pr_user'] = Request::param('pr_user');
            }else{
               $data['pr_user'] = Session::get('username');
            }
            $data['money'] = Request::param('money');
            $data['ticheng'] = Request::param('ticheng');
            $data['remark'] = Request::param('remark');
            $data['create_time'] = date("Y-m-d H:i:s",time());
            $data['status'] = '待审核';

            // $userExist = db('crm_leads')->where('phone', $data['phone'])->find();
            // if ($userExist){
            //     $msg = ['code' => -200,'msg'=>'抱歉，重复号码不可添加！','data'=>[]];
            //     return json($msg);
            // }

            $result = Db::table('crm_client_order')->insert($data);
            if ($result){
                $msg = ['code' => 0,'msg'=>'添加成功！','data'=>[]];
                return json($msg);
            }else{
                $msg = ['code' => -200,'msg'=>'添加失败！','data'=>[]];
                return json($msg);
            }
        }

        $userlist = Db::name('admin')->where('group_id','<>', 1)->field('admin_id,username')->select();
        $this -> assign('userlist',$userlist);
        // $userlist = Db::name('admin')->select();
        // var_dump($userlist);
        $this->assign('username',Session::get('username'));

        return $this->fetch('order/add');
    }
    public function changeyewu(){
        $data  = Request::param();
        $custphone = $data['cphone'];
        $where=[];
        $where['phone'] = $custphone;
        $custinfo = Db::name('crm_leads')->where($where)->find();
        if ($custinfo) {
            if ($custinfo['issuccess'] == 1) {
                $res['code'] = 0; 
                $res['msg'] = "该客户已经成交了，请检查客户手机信息";
            }else{
                $res['code'] = 1; 
                $res['custname'] = $custinfo['kh_name']; 
                $res['kh_username'] = $custinfo['kh_username'];
                $res['pr_user'] = $custinfo['pr_user'];
                $res['msg'] = "客户名称:".$custinfo['kh_name'].",所属业务员:".$custinfo['pr_user'];
            }
        }else{
            $res['code'] = 0; 
            $res['msg'] = "该客户信息没用找到";
        }
        
        $this->success($res);
    }
    //编辑客户
    public function edit(){
        if (Request::isAjax()){
            $data  = Request::param();
            $data['ut_time'] = date("Y-m-d H:i:s",time());

            $result = Db::table('crm_client_order')->where(['id'=>$data['id']])->update($data);
            if ($result){
                $msg = ['code' => 0,'msg'=>'编辑成功！','data'=>[]];
                return json($msg);
            }else{
                $msg = ['code' => -200,'msg'=>'编辑失败！','data'=>[]];
                return json($msg);
            }
        }


        $result = Db::table('crm_client_order') ->where(['id' => Request::param('id')])->find();

        $this -> assign('result',$result);

        $userlist = Db::name('admin')->where('group_id','<>', 1)->field('admin_id,username')->select();
        $this -> assign('userlist',$userlist);

        return $this -> fetch('order/edit');
    }
    //删除客户
    public function del(){
        $id = Request::param('id');
         // 对应的客户修改状态
        $orderinfo = Db::table('crm_client_order')->where('id',$id)->find();
        $custphone = $orderinfo['cphone'];
        $updatearr = [];
        $updatearr['issuccess'] = -1;
        Db::table('crm_leads')->where('phone',$custphone)->update($updatearr);
        $result = Db::table('crm_client_order')->where('id',$id)->delete();
        if ($result){
            $msg = ['code' => 0,'msg'=>'删除成功！','data'=>[]];
            return json($msg);
        }else{
            $msg = ['code' => -200,'msg'=>'删除失败！','data'=>[]];
            return json($msg);
        }
    }
    public function shenhe(){
        $id = Request::param('id');
       
        $orderinfo = Db::table('crm_client_order')->where('id',$id)->find();
        $custphone = $orderinfo['cphone'];
        $custinfo = Db::table('crm_leads')->where('phone',$custphone)->find();
        if ($custinfo['issuccess'] == 1) {
            $msg = ['code' => -200,'msg'=>'该客户已成交,业绩请勿重复添加','data'=>[]];
            return json($msg);
        }
        $updatearr = [];
        $updatearr['issuccess'] = 1;
        Db::table('crm_leads')->where('id',$custinfo['id'])->update($updatearr);
        $result = Db::table('crm_client_order')->where('id',$id)->update(['status'=>'审核通过']);

        if ($result){
            $msg = ['code' => 0,'msg'=>'审核成功','data'=>[]];
            return json($msg);
        }else{
            $msg = ['code' => -200,'msg'=>'审核已成功','data'=>[]];
            return json($msg);
        }
    }
  
    //客户搜索
    public function clientSearch(){
        $page =input('page')?input('page'):1;
        $limit =input('limit')?input('limit'):config('pageSize');
        $keyword = Request::param('keyword');

        $mapAtTime = []; //添加时间
        $mapKhName = [];//客户名称
        $mapXsSource = [];//线索/客户来源
        $mapPrUser = [];//业务员/负责人
        if ($keyword['create_time']!= ''){
            $at = $keyword['create_time'];//日期
            $end_at =date('Y-m-d',strtotime("$at+1day"));
            $mapAtTime = [['create_time','between time',[strtotime($at),strtotime($end_at)]]];
        }
        if ($keyword['cname'] != ''){
            $mapKhName = [['cname','like','%'.$keyword['cname'].'%']];
        }

        if ($keyword['status']!= ''){

            $mapXsSource =  ['status' => $keyword['status']];
        }

        if ($keyword['pr_user'] != ''){
            $mapPrUser = [['pr_user','like','%'.$keyword['pr_user'].'%']];
        }
        $list  = Db::table('crm_client_order')
            ->where($mapKhName)
            ->where($mapXsSource)
            ->where($mapPrUser)
            ->where($mapAtTime)
            ->whereTime('create_time',$keyword['timebucket'] ? $keyword['timebucket'] : null)
            ->order('create_time desc')
            ->paginate(array('list_rows'=>$limit,'page'=>$page))
            ->toArray();
        //var_dump($list);
        return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];

    }
    //（我的客户）搜索
    public function personClientSearch(){
       $page =input('page')?input('page'):1;
        $limit =input('limit')?input('limit'):config('pageSize');
        $keyword = Request::param('keyword');

        $mapAtTime = []; //添加时间
        $mapKhName = [];//客户名称
        $mapXsSource = [];//线索/客户来源
        $mapPrUser = [];//业务员/负责人
        if ($keyword['create_time']!= ''){
            $at = $keyword['create_time'];//日期
            $end_at =date('Y-m-d',strtotime("$at+1day"));
            $mapAtTime = [['create_time','between time',[strtotime($at),strtotime($end_at)]]];
        }
        if ($keyword['cname'] != ''){
            $mapKhName = [['cname','like','%'.$keyword['cname'].'%']];
        }

        if ($keyword['status']!= ''){

            $mapXsSource =  ['status' => $keyword['status']];
        }

        // if ($keyword['uname'] != ''){
        //     $mapPrUser = [['uname','like','%'.$keyword['uname'].'%']];
        // }
        $mapPrUser['pr_user'] =  Session::get('username');
        $list  = Db::table('crm_client_order')
            ->where($mapKhName)
            ->where($mapXsSource)
            ->where($mapPrUser)
            ->where($mapAtTime)
            ->whereTime('create_time',$keyword['timebucket'] ? $keyword['timebucket'] : null)
            ->order('create_time desc')
            ->paginate(array('list_rows'=>$limit,'page'=>$page))
            ->toArray();
        //var_dump($list);
        return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];


    }

   


}