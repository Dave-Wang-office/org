<?php
namespace app\admin\controller;

use think\Db;
use think\facade\Request;
use think\facade\Session;
use think\facade\Env;
class Client extends Common{
    //客户列表
    public function index(){
        if(request()->isPost()){
            $key=input('post.key');
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $list = db('crm_leads')
                ->where(['status'=>1,'issuccess'=>-1])
                ->order('ut_time desc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }

        $khRankList = Db::table('crm_client_rank')->select();
        $khStatusList = Db::table('crm_client_status')->select();
        $xsSourceList = Db::table('crm_clues_source')->select();

        //查询所有管理员（去除admin）
        $adminResult = Db::name('admin')->where('group_id','<>', 1)->field('admin_id,username')->select();
        $this -> assign('adminResult',$adminResult);

        $this -> assign('khRankList',$khRankList);
        $this -> assign('khStatusList',$khStatusList);
        $this -> assign('xsSourceList',$xsSourceList);  //线索/客户来源

        return $this->fetch();
    }

    //（我的客户）列表
    public function perCliList(){

        if(request()->isPost()){
            $key=input('post.key');
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $list = db('crm_leads')
                ->where(['status'=>1,'issuccess'=>-1])
                ->where(['pr_user'=> Session::get('username')])
                ->order('ut_time desc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }

        $khRankList = Db::table('crm_client_rank')->select();
        $khStatusList = Db::table('crm_client_status')->select();
        $xsSourceList = Db::table('crm_clues_source')->select();

        $this -> assign('khRankList',$khRankList);
        $this -> assign('khStatusList',$khStatusList);
        $this -> assign('xsSourceList',$xsSourceList);  //线索/客户来源

        return $this->fetch('personclient/index');
    }

     //成交客户列表
    public function successCliList(){

        if(request()->isPost()){
            $where = [];
            $where['issuccess'] = 1;
            if(session('aid')!=1){
                 $where['pr_user'] = Session::get('username');
            }
            $key=input('post.key');
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $list = db('crm_leads')
                ->where($where)
                ->order('ut_time desc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }

        $khRankList = Db::table('crm_client_rank')->select();
        $khStatusList = Db::table('crm_client_status')->select();
        $xsSourceList = Db::table('crm_clues_source')->select();

        $this -> assign('khRankList',$khRankList);
        $this -> assign('khStatusList',$khStatusList);
        $this -> assign('xsSourceList',$xsSourceList);  //线索/客户来源
         //查询所有管理员（去除admin）
        $adminResult = Db::name('admin')->where('group_id','<>', 1)->field('admin_id,username')->select();
        $this -> assign('adminResult',$adminResult);
        return $this->fetch('client/chengjiao');
    }
    //批量导入，客户上传
    public function xlsUpload(){

        $xlsFile = Request::file('xlsFile');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $xlsFile -> move(Env::get('root_path'). 'public' .DIRECTORY_SEPARATOR.'uploads');
        if ($info) {
            $result = importExecl(Env::get('root_path'). 'public' .DIRECTORY_SEPARATOR.'uploads/'.$info -> getSaveName());


            $count = count($result); //统计总数据
            if ($count>1000){
                $msg = ['code' => -200,'msg'=>'数据量过大，请分批导入！','data'=>[]];
                return json($msg);
            }
            unset($result[1]); //移除标题





            //$userExists = []; //已存在的线索
            foreach ($result as $key =>&$value){

                //看下手机号是否存在。将存在的手机号保存在数组里。
                $userExist = db('crm_leads')->where('phone', $value['E'])->find();
                if ($userExist){
                    // array_push($userExists, $result[$key]['A']);
                    unset($result[$key]);
                }else{
                    //客户名称、地区、行业类别、联系人、联系号码、客户级别、客户状态、用户名、备注
                    $value['kh_name'] = $value['A'];//A客户名称
                    unset($value['A']);
                    $value['xs_area'] = $value['B'];//B 地区
                    unset($value['B']);
                    $value['kh_hangye'] = $value['C'];//C 行业类别
                    unset($value['C']);
                    $value['kh_contact'] = $value['D'];//D 联系人
                    unset($value['D']);
                    $value['phone'] = $value['E'];//E 联系号码
                    unset($value['E']);
             
                    $value['kh_rank'] = $value['F'];//E 客户级别
                    unset($value['F']);
                    $value['kh_status'] = $value['G'];//G 客户状态
                    unset($value['G']);
                    $value['kh_username'] = $value['H'];//G 用户名
                    unset($value['H']);
                    $value['remark'] = $value['I'];//G 备注
                    unset($value['I']);
                    $value['pr_user'] = Session::get('username');//H 负责人
                  
                    $value['ut_time'] =  date("Y-m-d H:i:s",time());//Q更新于
                    $value['at_time'] = date("Y-m-d H:i:s",time());//R创建时间
                    $value['at_user'] = Session::get('username');//T创建人
                    $value['status'] = 1; 
                    //导入客户
                    //A客户名称，B客户级别，C客户状态，D最新跟进记录,E实际跟进时间,F下次跟进时间,G手机
                    //H微信号,I未跟进天数,J客户来源,K备注,L负责人,M所属公海,N划入公海时间
                    //O前所属部门,P所属部门,Q更新于,R创建时间,S客户需求,T创建人,U前负责人

                    // $value['kh_name'] = $value['A'];//A客户名称
                    // unset($value['A']);
                    // $value['kh_rank'] = $value['B'];//B客户级别
                    // unset($value['B']);
                    // $value['kh_status'] = $value['C'];//C客户状态
                    // unset($value['C']);
                    // $value['last_up_records'] = $value['D'];//D最新跟进记录
                    // unset($value['D']);
                    // $value['last_up_time'] = $value['E'];//E实际跟进时间
                    // unset($value['E']);
                    // $value['next_up_time'] = $value['F'];//F下次跟进时间
                    // unset($value['F']);
                    // $value['phone'] = $value['G'];//G手机
                    // unset($value['G']);
                    // $value['wechat'] = $value['H'];//H微信号
                    // unset($value['H']);
                    // //$value['未跟进天数'] = $value['I'];//I未跟进天数(不入库，直接过滤)
                    // unset($value['I']);
                    // $value['xs_source'] = $value['J'];//J客户来源/线索来源
                    // unset($value['J']);
                    // $value['remark'] = $value['K'];//K备注
                    // unset($value['K']);
                    // $value['pr_user'] = $value['L'] ? $value['L']: Session::get('username');//L负责人
                    // unset($value['L']);
                    // $value['pr_gh_type'] = $value['M'];//M所属公海
                    // unset($value['M']);
                    // $value['to_gh_time'] = $value['N'];//N划入公海时间
                    // unset($value['N']);
                    // $value['pr_dep_bef'] = $value['O'];//O前所属部门
                    // unset($value['O']);
                    // $value['pr_dep'] = $value['P'];//P所属部门
                    // unset($value['P']);
                    // $value['ut_time'] = $value['Q'] ? $value['Q'] : date("Y-m-d H:i:s",time());//Q更新于
                    // unset($value['Q']);
                    // $value['at_time'] = $value['R'] ? $value['R'] : date("Y-m-d H:i:s",time());//R创建时间
                    // unset($value['R']);
                    // $value['kh_need'] = $value['S'];//S客户需求
                    // unset($value['S']);
                    // $value['at_user'] = $value['T'] ? $value['T']: Session::get('username');//T创建人
                    // unset($value['T']);
                    // $value['pr_user_bef'] = $value['U'] ? $value['U']: Session::get('username'); //U前负责人
                    // unset($value['U']);
                    //$value['status'] = 1; //导入客户
                }
            }


            $failcount = count($result); //最终的总数
            $insertAll = Db::table('crm_leads')->insertAll($result);

            if ($insertAll){
                $msg = ['code' => 0,'msg'=>'导入'.$failcount.'条数据成功！','data'=>[]];
                return json($msg);
            }else{
                $msg = ['code' => -200,'msg'=>'客户导入失败,不可重复导入！','data'=>[]];
                return json($msg);
            }
        }

    }

    //新建客户
    public function add(){
        if(request()->isPost()){
            // <!-- 客户名称、地区、行业类别、联系人、联系号码、客户级别、客户状态、用户名、备注 -->
            $data['phone'] = Request::param('phone');
            $data['kh_name'] = Request::param('kh_name');
            $data['xs_area'] = Request::param('xs_area');
            $data['kh_hangye'] = Request::param('kh_hangye');
            $data['kh_contact'] = Request::param('kh_contact');
            $data['kh_rank'] = Request::param('kh_rank');
            $data['kh_status'] = Request::param('kh_status');
            // $data['kh_username'] = Request::param('kh_username');
            $data['remark'] = Request::param('remark');

            // $data['kh_need'] = Request::param('kh_need');
            $data['at_user'] = Session::get('username');
            $data['pr_user'] = Session::get('username');
            $data['pr_user_bef'] = Session::get('username');
            $data['ut_time'] = date("Y-m-d H:i:s",time());
            $data['at_time'] = date("Y-m-d H:i:s",time());
            $data['status'] = 1;
            $data['ispublic'] = 3;
            $userExist = db('crm_leads')->where('phone', $data['phone'])->find();
            if ($userExist){
                $msg = ['code' => -200,'msg'=>'抱歉，重复号码不可添加！','data'=>[]];
                return json($msg);
            }

            $result = Db::table('crm_leads')->insert($data);
            if ($result){
                $msg = ['code' => 0,'msg'=>'添加成功！','data'=>[]];
                return json($msg);
            }else{
                $msg = ['code' => -200,'msg'=>'添加失败！','data'=>[]];
                return json($msg);
            }
        }


        // $xsSourceList = Db::table('crm_clues_source')->select();
        $khRankList = Db::table('crm_client_rank')->select();
        $khStatusList = Db::table('crm_client_status')->select();
        $xsAreaList = Db::table('crm_clues_area')->select();
        $xsHangyeList = Db::table('crm_client_hangye')->select();
        $this -> assign('xsHangyeList',$xsHangyeList);
        $this -> assign('xsAreaList',$xsAreaList);
        $this -> assign('khRankList',$khRankList);
        $this -> assign('khStatusList',$khStatusList);

        return $this->fetch('client/add');
    }
    //编辑客户
    public function edit(){
        if (Request::isAjax()){
            $data  = Request::param();
            $data['ut_time'] = date("Y-m-d H:i:s",time());

            $result = Db::table('crm_leads')->where(['id'=>$data['id']])->where('status',1)->update($data);
            if ($result){
                $msg = ['code' => 0,'msg'=>'编辑成功！','data'=>[]];
                return json($msg);
            }else{
                $msg = ['code' => -200,'msg'=>'编辑失败！','data'=>[]];
                return json($msg);
            }
        }


        $result = Db::table('crm_leads') ->where(['id' => Request::param('id')])->find();

        $this -> assign('result',$result);

        // $xsSourceList = Db::table('crm_clues_source')->select();
        $khRankList = Db::table('crm_client_rank')->select();
        $khStatusList = Db::table('crm_client_status')->select();
        $xsAreaList = Db::table('crm_clues_area')->select();
        $xsHangyeList = Db::table('crm_client_hangye')->select();
        $this -> assign('xsHangyeList',$xsHangyeList);
        $this -> assign('xsAreaList',$xsAreaList);
        // $this -> assign('xsSourceList',$xsSourceList);
        $this -> assign('khRankList',$khRankList);
        $this -> assign('khStatusList',$khStatusList);

        return $this -> fetch('client/edit');
    }
    //删除客户
    public function del(){
        $id = Request::param('id');
        $result = Db::table('crm_leads')->where('id',$id)->where('status',1)->delete();
        if ($result){
            $msg = ['code' => 0,'msg'=>'删除成功！','data'=>[]];
            return json($msg);
        }else{
            $msg = ['code' => -200,'msg'=>'删除失败！','data'=>[]];
            return json($msg);
        }
    }

    //客户级别
    public function rankList(){
        if(request()->isPost()){
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $list = db('crm_client_rank')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }
        return $this->fetch();
    }
    //添加客户级别
    public function rankAdd(){
        if(request()->isPost()){
            $data['rank_name'] = Request::param('rank_name');
            $data['add_time'] = time();
            $result = Db::table('crm_client_rank')->insert($data);
            if ($result){
                $msg = ['code' => 0,'msg'=>'添加成功！','data'=>[]];
                return json($msg);
            }else{
                $msg = ['code' => -200,'msg'=>'添加失败！','data'=>[]];
                return json($msg);
            }
        }
        return $this->fetch('client/rank_list_add');
    }
    //编辑客户级别
    public function rankEdit(){
        if (Request::isAjax()){
            $data  = Request::param();
             // 获取原状态
            $oldstatus = Db::table('crm_client_rank')->where(['id'=>$data['id']])->find();
            $oldstatusname = $oldstatus['rank_name'];
            $ischange = false;
            if ($oldstatusname == $data['rank_name']) {
                $msg = ['code' => -200,'msg'=>'没有变化无需修改','data'=>[]];
                return json($msg);
            }else{
                $ischange = true;
            }

            $result = Db::table('crm_client_rank')->where(['id'=>$data['id']])->update($data);
            if ($result){
                // 状态修改后 客户编辑的原来状态都必须修改
                if ($ischange) {
                    // 所有的客户状态全部膝盖
                    $result2 = Db::table('crm_leads')->where(['kh_rank'=>$oldstatusname])->update(['kh_rank'=>$data['rank_name']]);
                }
                $msg = ['code' => 0,'msg'=>'编辑成功！','data'=>[]];
                return json($msg);
            }else{
                $msg = ['code' => -200,'msg'=>'编辑失败！','data'=>[]];
                return json($msg);
            }
        }

        $result = Db::table('crm_client_rank') ->where(['id' => Request::param('id')])->find();
        $this -> assign('result',$result);
        return $this -> fetch('client/rank_list_edit');
    }
    //删除客户级别
    public function rankDel(){
        $id = Request::param('id');
        // 获取原状态
        $oldstatus = Db::table('crm_client_rank')->where(['id'=>$data['id']])->find();
        $oldstatusname = $oldstatus['rank_name'];

        $result = Db::table('crm_client_rank')->where('id',$id)->delete();
        if ($result){
            // 所有的客户状态全部膝盖
            $result2 = Db::table('crm_leads')->where(['kh_rank'=>$oldstatusname])->update(['kh_rank'=>'']);
            $msg = ['code' => 0,'msg'=>'删除成功！','data'=>[]];
            return json($msg);
        }else{
            $msg = ['code' => -200,'msg'=>'删除失败！','data'=>[]];
            return json($msg);
        }
    }


    //客户状态
    public function statusList(){
        if(request()->isPost()){
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $list = db('crm_client_status')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }
        return $this->fetch();
    }
    //添加客户状态
    public function statusAdd(){
        if(request()->isPost()){
            $data['status_name'] = Request::param('status_name');
            $data['add_time'] = time();
            $result = Db::table('crm_client_status')->insert($data);
            if ($result){
                $msg = ['code' => 0,'msg'=>'添加成功！','data'=>[]];
                return json($msg);
            }else{
                $msg = ['code' => -200,'msg'=>'添加失败！','data'=>[]];
                return json($msg);
            }
        }
        return $this->fetch('client/status_list_add');
    }
    //编辑客户状态
    public function statusEdit(){
        if (Request::isAjax()){
            $data  = Request::param();
            // 获取原状态
            $oldstatus = Db::table('crm_client_status')->where(['id'=>$data['id']])->find();
            $oldstatusname = $oldstatus['status_name'];
            $newstatusname = $data['status_name'];
            $ischange = false;
            if ($oldstatusname == $newstatusname) {
                $msg = ['code' => -200,'msg'=>'状态没有变化无需修改','data'=>[]];
                return json($msg);
            }else{
                $ischange = true;
            }
            $result = Db::table('crm_client_status')->where(['id'=>$data['id']])->update($data);
            if ($result){
                // 状态修改后 客户编辑的原来状态都必须修改
                if ($ischange) {
                    // 所有的客户状态全部膝盖
                    $result2 = Db::table('crm_leads')->where(['kh_status'=>$oldstatusname])->update(['kh_status'=>$newstatusname]);
                }
                $msg = ['code' => 0,'msg'=>'编辑成功！','data'=>[]];
                return json($msg);
            }else{
                $msg = ['code' => -200,'msg'=>'编辑失败！','data'=>[]];
                return json($msg);
            }
        }


        $result = Db::table('crm_client_status') ->where(['id' => Request::param('id')])->find();
        $this -> assign('result',$result);
        return $this -> fetch('client/status_list_edit');
    }
    //删除客户状态
    public function statusDel(){
        $id = Request::param('id');
        // 获取原状态
        $oldstatus = Db::table('crm_client_status')->where(['id'=>$data['id']])->find();
        $oldstatusname = $oldstatus['status_name'];
        // $ischange = false;
        // if ($oldstatusname == $data['status_name']) {
        //     $msg = ['code' => -200,'msg'=>'状态没有变化无需修改','data'=>[]];
        //     return json($msg);
        // }else{
        //     $ischange = true;
        // }
        $result = Db::table('crm_client_status')->where('id',$id)->delete();
        if ($result){
            // 所有的客户状态全部膝盖
            $result2 = Db::table('crm_leads')->where(['kh_status'=>$oldstatusname])->update(['kh_status'=>'']);
            $msg = ['code' => 0,'msg'=>'删除成功！','data'=>[]];
            return json($msg);
        }else{
            $msg = ['code' => -200,'msg'=>'删除失败！','data'=>[]];
            return json($msg);
        }
    }


    //移入公海
    public function toMoveGh(){
         //1，获取提交的线索ID 【1,2,3,4,】
        $ids = Request::param('ids');
        $this -> assign('ids',$ids);
        if (Request::isAjax()){
            $pr_gh_type = Request::param('pr_gh_type');
            $idsArr = explode(",",$ids);


            $count = 0;
            foreach ($idsArr as $key => $value){
                // $data['pr_user_bef'] = Db::table('crm_leads')->where(['id'=>$value])->field('pr_user')->find();
                // $data['pr_user'] = $username;
                // $data['id'] = $value;
                // $insertAll = Db::name('crm_leads')->update($data);
                $data['pr_gh_type'] = $pr_gh_type;
                $data['to_gh_time'] = date("Y-m-d H:i:s",time());
                $data['status'] = 2;//0-线索，1客户，2公海
                $data['id']  = $value;
                $result = Db::table('crm_leads')->where(['id'=>$value])->update($data);
                if ($result){
                    $count ++;
                }
            }
            if ($count > 0){
                $msg = ['code' => 0,'msg'=>$count.'个客户移入公海成功！','data'=>[]];
                return json($msg);
            }else{
                $msg = ['code' => -200,'msg'=>'转入公海失败！','data'=>[]];
                return json($msg);
            }
            // $data['pr_gh_type'] = Request::param('pr_gh_type');
            // $data['to_gh_time'] = date("Y-m-d H:i:s",time());
            // $data['status'] = 2;//0-线索，1客户，2公海
            // $data['id']  = Request::param('id');
            // $result = Db::table('crm_leads')->where(['id'=>$data['id']])->update($data);
            // if ($result){
            //     $msg = ['code' => 0,'msg'=>'移入公海成功！','data'=>[]];
            //     return json($msg);
            // }else{
            //     $msg = ['code' => -200,'msg'=>'抱歉，移入公海失败！','data'=>[]];
            //     return json($msg);
            // }
        }

        
        $libTypeList = Db::table('crm_liberum_type')->select();

        $this -> assign('libTypeList',$libTypeList);

        // $result = Db::table('crm_leads') ->where(['id' => Request::param('id')])->find();
        // $this -> assign('result',$result);
        return $this -> fetch('client/move_gh');
    }
    //客户搜索
    public function clientSearch(){
        $page =input('page')?input('page'):1;
        $limit =input('limit')?input('limit'):config('pageSize');
        $keyword = Request::param('keyword');
        $list= model('client') -> getClientSearchList($page,$limit,$keyword);
        return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];

    }
    //（我的客户）搜索
    public function personClientSearch(){
        $page =input('page')?input('page'):1;
        $limit =input('limit')?input('limit'):config('pageSize');
        $keyword = Request::param('keyword');
        $list= model('client') -> getPersonClientSearchList($page,$limit,$keyword);
        return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];

    }
    //（我的客户）搜索
    public function chengjiaoClientSearch(){
        $page =input('page')?input('page'):1;
        $limit =input('limit')?input('limit'):config('pageSize');
        $keyword = Request::param('keyword');
        $list= model('client') -> getChengjiaoClientSearchList($page,$limit,$keyword);
        return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];

    }
    //写跟进
    public function dialogue(){
        $result = Db::table('crm_leads')->where(['id'=>Request::param('id')])->find();

        $result['comment']= Db::table('crm_comment')->alias('com')->join('admin adm','com.user_id = adm.admin_id')->where(['leads_id'=>Request::param('id')])->field('com.*,adm.username,adm.avatar')->select();
        foreach ($result['comment'] as $k => $v){
            $result['comment'][$k]['reply'] = Db::table('crm_reply')->where(['comment_id'=>$v['id']])->select();
        }

        $this ->assign('result',$result);
        return $this -> fetch('client/dialogue');
    }

    //评论
    public function comment(){

        $data['leads_id'] = Request::param('leads_id');
        $data['user_id'] = Session::get('aid');
        $data['reply_msg'] = Request::param('reply_msg');
        $data['create_date'] = time();

        //更新跟进记录
        $genjin['last_up_records'] = $data['reply_msg'];
        $genjin['last_up_time'] = date("Y-m-d H:i:s",$data['create_date']);
        $genjin['ut_time'] = date("Y-m-d H:i:s",time());

        Db::table('crm_leads')->where(['id'=>$data['leads_id']])->update($genjin);

        $result = Db::table('crm_comment')->insert($data);
        $data['create_date'] = date("Y年m月d日 H:i",$data['create_date']);

        if ($result){
            return json(['code'=> 0,'msg'=>'评论成功！','data'=>$data]);
        }else{
            return json(['code'=>1,'msg'=>'评论失败！']);
        }
    }

    //回复
    public function reply(){

        $data['comment_id'] = Request::param('cid');
        $data['from_user_id'] = Session::get('user.id');
        $data['to_user_id'] = Request::param('to_uid');
        $data['reply_msg'] = Request::param('reply_msg');
        $data['create_date'] = time();

        $result = Db::table('crm_reply')->insert($data);
        $data['create_date'] = date("Y年m月d日 H:i",$data['create_date']);
        if ($result){
            return json(['code'=> 0,'msg'=>'回复成功！','data'=>$data]);
        }else{

            return json(['code'=>1,'msg'=>'回复失败！']);

        }
    }


    //客户转移，变更负责人
    public function alterPrUser(){
        //1，获取提交的线索ID 【1,2,3,4,】
        $ids = Request::param('ids');
        $this -> assign('ids',$ids);


        //查询所有管理员（去除admin）
        $adminResult = Db::name('admin')->where('group_id','<>', 1)->field('admin_id,username')->select();
        $this -> assign('adminResult',$adminResult);

        if (Request::isAjax()){
            $username = Request::param('username');
            $idsArr = explode(",",$ids);


            $count = 0;
            foreach ($idsArr as $key => $value){
                $data['pr_user_bef'] = Db::table('crm_leads')->where(['id'=>$value])->field('pr_user')->find();
                $data['pr_user'] = $username;
                $data['id'] = $value;
                $insertAll = Db::name('crm_leads')->update($data);
                if ($insertAll){
                    $count ++;
                }
            }




            if ($count > 0){
                $msg = ['code' => 0,'msg'=>'转移'.$count.'个客户成功！','data'=>[]];
                return json($msg);
            }else{
                $msg = ['code' => -200,'msg'=>'转移失败！','data'=>[]];
                return json($msg);
            }
        }

        return $this -> fetch('client/alter_pr_user');
    }


    //客户转移，变更负责人(个人)
    public function alterPrUserPri(){
        //1，获取提交的线索ID 【1,2,3,4,】
        $ids = Request::param('ids');
        $this -> assign('ids',$ids);


        //查询所有管理员（去除admin）
        $adminResult = Db::name('admin')->where('group_id','<>', 1)->field('admin_id,username')->select();
        $this -> assign('adminResult',$adminResult);

        if (Request::isAjax()){
            $username = Request::param('username');
            $idsArr = explode(",",$ids);


            $count = 0;
            foreach ($idsArr as $key => $value){
                $data['pr_user_bef'] = Db::table('crm_leads')->where(['id'=>$value])->field('pr_user')->find();
                $data['pr_user'] = $username;
                $data['id'] = $value;
                $insertAll = Db::name('crm_leads')->update($data);
                if ($insertAll){
                    $count ++;
                }
            }




            if ($count > 0){
                $msg = ['code' => 0,'msg'=>'转移'.$count.'个客户成功！','data'=>[]];
                return json($msg);
            }else{
                $msg = ['code' => -200,'msg'=>'转移失败！','data'=>[]];
                return json($msg);
            }
        }

        return $this -> fetch('personclient/alter_pr_user');
    }

     //客户行业
    public function hangyeList(){
        if(request()->isPost()){
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $list = db('crm_client_hangye')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }
        return $this->fetch();
    }
    //添加客户级别
    public function hangyeAdd(){
        if(request()->isPost()){
            $data['hy_name'] = Request::param('hy_name');
            $data['add_time'] = time();
            $result = Db::table('crm_client_hangye')->insert($data);
            if ($result){
                $msg = ['code' => 0,'msg'=>'添加成功！','data'=>[]];
                return json($msg);
            }else{
                $msg = ['code' => -200,'msg'=>'添加失败！','data'=>[]];
                return json($msg);
            }
        }
        return $this->fetch('client/hangye_list_add');
    }
    //编辑客户级别
    public function hangyeEdit(){
        if (Request::isAjax()){
            $data  = Request::param();
             // 获取原状态
            $oldstatus = Db::table('crm_client_hangye')->where(['id'=>$data['id']])->find();
            $oldstatusname = $oldstatus['hy_name'];
            $ischange = false;
            if ($oldstatusname == $data['hy_name']) {
                $msg = ['code' => -200,'msg'=>'没有变化无需修改','data'=>[]];
                return json($msg);
            }else{
                $ischange = true;
            }

            $result = Db::table('crm_client_hangye')->where(['id'=>$data['id']])->update($data);
            if ($result){
                // 状态修改后 客户编辑的原来状态都必须修改
                if ($ischange) {
                    // 所有的客户状态全部膝盖
                    $result2 = Db::table('crm_leads')->where(['kh_hangye'=>$oldstatusname])->update(['kh_hangye'=>$data['hy_name']]);
                }
                $msg = ['code' => 0,'msg'=>'编辑成功！','data'=>[]];
                return json($msg);
            }else{
                $msg = ['code' => -200,'msg'=>'编辑失败！','data'=>[]];
                return json($msg);
            }
        }

        $result = Db::table('crm_client_hangye') ->where(['id' => Request::param('id')])->find();
        $this -> assign('result',$result);
        return $this -> fetch('client/hangye_list_edit');
    }
    //删除客户级别
    public function hangyeDel(){
        $id = Request::param('id');
        // 获取原状态
        $oldstatus = Db::table('crm_client_hangye')->where(['id'=>$data['id']])->find();
        $oldstatusname = $oldstatus['hy_name'];

        $result = Db::table('crm_client_hangye')->where('id',$id)->delete();
        if ($result){
            // 所有的客户状态全部膝盖
            $result2 = Db::table('crm_leads')->where(['kh_hangye'=>$oldstatusname])->update(['kh_hangye'=>'']);
            $msg = ['code' => 0,'msg'=>'删除成功！','data'=>[]];
            return json($msg);
        }else{
            $msg = ['code' => -200,'msg'=>'删除失败！','data'=>[]];
            return json($msg);
        }
    }

}