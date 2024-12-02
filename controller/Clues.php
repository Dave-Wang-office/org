<?php
namespace app\admin\controller;
use think\facade\Request;
use think\facade\Env;
use think\Db;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use think\facade\Session;

class Clues extends Common{
    //线索列表
    public function index(){
        if(request()->isPost()){
            $key=input('post.key');
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $list = db('crm_leads')
                ->where(['status'=>0,'issuccess'=>-1])
                ->order('ut_time desc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
             // 手机号加密处理
            foreach ($list['data'] as $key => $value) {
               $value['phone'] = mb_substr($value['phone'], 0, 3).'****'. mb_substr($value['phone'], 7, 11);
               $list['data'][$key] = $value;
            }
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }

        $xsSourceList = Db::table('crm_clues_source')->select();
        $xsStatusList = Db::table('crm_clues_status')->select();

        $this -> assign('xsSourceList',$xsSourceList);
        $this -> assign('xsStatusList',$xsStatusList);


        return $this->fetch();
    }

    //(我的线索)列表
    public function perClulist(){
        if(request()->isPost()){
            $key=input('post.key');
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');

            $list = db('crm_leads')
                ->where(['status'=>0,'issuccess'=>-1])
                ->where(['pr_user'=> Session::get('username')])
                ->order('ut_time desc')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }

        $xsSourceList = Db::table('crm_clues_source')->select();
        $xsStatusList = Db::table('crm_clues_status')->select();

        $this -> assign('xsSourceList',$xsSourceList);
        $this -> assign('xsStatusList',$xsStatusList);


        return $this->fetch('personclues/index');
    }

    //批量导入，线索上传
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
                $userExist = db('crm_leads')->where('phone', $value['G'])->find();
                if ($userExist){
                   // array_push($userExists, $result[$key]['A']);
                    unset($result[$key]);
                }else{
                    //客户名称、地区、行业类别、联系人、联系号码、客户级别、客户状态、用户名、备注
                    $value['xs_name'] = $value['A'];//A线索名称
                    unset($value['A']);
                    $value['xs_status'] = $value['B'];//B线索状态
                    unset($value['B']);
                    $value['xs_source'] = $value['C'];//C线索来源
                    unset($value['C']);
                    $value['xs_area'] = $value['D'];//B 地区
                    unset($value['D']);
                    $value['kh_hangye'] = $value['E'];//C 行业类别
                    unset($value['E']);
                    $value['kh_contact'] = $value['F'];//D 联系人
                    unset($value['F']);
                    $value['phone'] = $value['G'];//E 联系号码
                    unset($value['G']);
             
                    // $value['kh_rank'] = $value['H'];//E 客户级别
                    // unset($value['H']);
                    // $value['kh_status'] = $value['I'];//G 客户状态
                    // unset($value['I']);
                    // $value['kh_username'] = $value['J'];//G 用户名
                    // unset($value['J']);
                    $value['remark'] = $value['H'];//G 备注
                    unset($value['H']);
                    $value['pr_user'] = Session::get('username');//H 负责人
                  
                    $value['ut_time'] =  date("Y-m-d H:i:s",time());//Q更新于
                    $value['at_time'] = date("Y-m-d H:i:s",time());//R创建时间
                    $value['at_user'] = Session::get('username');//T创建人
                    $value['status'] = 0; 
                    //A线索名称，B手机，C线索状态，D线索来源,E最新跟进记录,F实际跟进时间,G下次跟进时间
                    //H微信号,I未跟进天数,J地区来源,K备注,L负责人,M前所属部门,N所属部门
                    //O更新于,P创建时间,Q客户需求,R创建人,S前负责人

                    // $value['xs_name'] = $value['A'];//A线索名称
                    // unset($value['A']);
                    // $value['phone'] = $value['B'];//B手机
                    // unset($value['B']);
                    // $value['xs_status'] = $value['C'];//C线索状态
                    // unset($value['C']);
                    // $value['xs_source'] = $value['D'];//D客户来源/线索来源
                    // unset($value['D']);
                    // $value['last_up_records'] = $value['E'];//E最新跟进记录
                    // unset($value['E']);
                    // $value['last_up_time'] = $value['F'];//F实际跟进时间
                    // unset($value['F']);
                    // $value['next_up_time'] = $value['G'];//G下次跟进时间
                    // unset($value['G']);
                    // $value['wechat'] = $value['H'];//H微信号
                    // unset($value['H']);
                    // //$value['未跟进天数'] = $value['I'];//I未跟进天数(不入库，直接过滤)
                    // unset($value['I']);
                    // $value['xs_area'] = $value['J'];//J地区来源
                    // unset($value['J']);
                    // $value['remark'] = $value['K'];//K备注
                    // unset($value['K']);
                    // $value['pr_user'] = $value['L'] ? $value['L'] : Session::get('username');//L负责人
                    // unset($value['L']);
                    // $value['pr_dep_bef'] = $value['M'];//M前所属部门
                    // unset($value['M']);
                    // $value['pr_dep'] = $value['N'];//N所属部门
                    // unset($value['N']);
                    // $value['ut_time'] = $value['O'] ? $value['O'] : date("Y-m-d H:i:s",time());//O更新于
                    // unset($value['O']);
                    // $value['at_time'] = $value['P'] ? $value['P'] : date("Y-m-d H:i:s",time());//P创建时间
                    // unset($value['P']);
                    // $value['kh_need'] = $value['Q'];//Q客户需求
                    // unset($value['Q']);
                    // $value['at_user'] = $value['R'];//R创建人
                    // unset($value['R']);
                    // $value['pr_user_bef'] = $value['S'] ? $value['S'] : Session::get('username');//S前负责人
                    // unset($value['S']);
                    //$value['status'] = 0;//导入线索
                }
            }


            $failcount = count($result); //最终的总数
            $insertAll = Db::table('crm_leads')->insertAll($result);

            if ($insertAll){
                $msg = ['code' => 0,'msg'=>'导入'.$failcount.'条数据成功！','data'=>[]];
                return json($msg);
            }else{
                $msg = ['code' => -200,'msg'=>'线索导入失败,不可重复导入！','data'=>[]];
                return json($msg);
            }
        }

    }

    //新建线索
    public function add(){
        if(request()->isPost()){
            // <!-- 线索名称、地区、行业类别、线索来源、联系人、联系号码、用户名、线索状态、备注 -->
            $data['xs_name'] = Request::param('xs_name');
            $data['xs_area'] = Request::param('xs_area');
            $data['kh_hangye'] = Request::param('kh_hangye');
            $data['kh_contact'] = Request::param('kh_contact');
            // $data['kh_username'] = Request::param('kh_username');
            $data['phone'] = Request::param('phone');

            $data['xs_source'] = Request::param('xs_source');
            $data['xs_status'] = Request::param('xs_status');
            $data['remark'] = Request::param('remark');

            $data['at_user'] = Session::get('username');
            $data['at_time'] = date("Y-m-d H:i:s",time());
            $data['ut_time'] = date("Y-m-d H:i:s",time());
            $data['pr_user'] = Session::get('username');
            $data['pr_user_bef'] = Session::get('username');

            $userExist = db('crm_leads')->where('phone', $data['phone'])->find();
            if ($userExist){
                $msg = ['code' => -200,'msg'=>'抱歉，重复线索不可添加！','data'=>[]];
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


        $xsSourceList = Db::table('crm_clues_source')->select();
        $xsStatusList = Db::table('crm_clues_status')->select();
        $xsAreaList = Db::table('crm_clues_area')->select();
        $xsHangyeList = Db::table('crm_client_hangye')->select();
        $this -> assign('xsHangyeList',$xsHangyeList);
        $this -> assign('xsAreaList',$xsAreaList);

        $this -> assign('xsSourceList',$xsSourceList);
        $this -> assign('xsStatusList',$xsStatusList);

        return $this->fetch('clues/add');
    }
    //编辑线索
    public function edit(){
        if (Request::isAjax()){
            $data  = Request::param();
            $data['ut_time'] = date("Y-m-d H:i:s",time());

            $result = Db::table('crm_leads')->where(['id'=>$data['id']])->where('status',0)->update($data);
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

        $xsSourceList = Db::table('crm_clues_source')->select();
        $xsStatusList = Db::table('crm_clues_status')->select();
        $xsAreaList = Db::table('crm_clues_area')->select();
        $xsHangyeList = Db::table('crm_client_hangye')->select();
        $this -> assign('xsHangyeList',$xsHangyeList);
        $this -> assign('xsAreaList',$xsAreaList);
        $this -> assign('xsSourceList',$xsSourceList);
        $this -> assign('xsStatusList',$xsStatusList);

        return $this -> fetch('clues/edit');
    }
    //删除线索
    public function del(){
        $id = Request::param('id');
        $result = Db::table('crm_leads')->where('id',$id)->where('status',0)->delete();
        if ($result){
            $msg = ['code' => 0,'msg'=>'删除成功！','data'=>[]];
            return json($msg);
        }else{
            $msg = ['code' => -200,'msg'=>'删除失败！','data'=>[]];
            return json($msg);
        }
    }



    //线索状态
    public function statusList(){
        if(request()->isPost()){
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $list = db('crm_clues_status')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }
        return $this->fetch();
    }
    //添加线索状态
    public function statusAdd(){
        if(request()->isPost()){
            $data['status_name'] = Request::param('status_name');
            $data['add_time'] = time();
            $result = Db::table('crm_clues_status')->insert($data);
            if ($result){
                $msg = ['code' => 0,'msg'=>'添加成功！','data'=>[]];
                return json($msg);
            }else{
                $msg = ['code' => -200,'msg'=>'添加失败！','data'=>[]];
                return json($msg);
            }
        }
        return $this->fetch('clues/status_list_add');
    }
    //编辑线索状态
    public function statusEdit(){
        if (Request::isAjax()){
            $data  = Request::param();
            $result = Db::table('crm_clues_status')->where(['id'=>$data['id']])->update($data);
            if ($result){
                $msg = ['code' => 0,'msg'=>'编辑成功！','data'=>[]];
                return json($msg);
            }else{
                $msg = ['code' => -200,'msg'=>'编辑失败！','data'=>[]];
                return json($msg);
            }
        }


        $result = Db::table('crm_clues_status') ->where(['id' => Request::param('id')])->find();
        $this -> assign('result',$result);
        return $this -> fetch('clues/status_list_edit');
    }
    //删除线索状态
    public function statusDel(){
        $id = Request::param('id');
        $result = Db::table('crm_clues_status')->where('id',$id)->delete();
        if ($result){
            $msg = ['code' => 0,'msg'=>'删除成功！','data'=>[]];
            return json($msg);
        }else{
            $msg = ['code' => -200,'msg'=>'删除失败！','data'=>[]];
            return json($msg);
        }
    }





    //线索来源
    public function sourceList(){
        if(request()->isPost()){
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $list = db('crm_clues_source')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }
        return $this->fetch();
    }
    //添加线索来源
    public function sourceAdd(){
        if(request()->isPost()){
            $data['source_name'] = Request::param('source_name');
            $data['add_time'] = time();
            $result = Db::table('crm_clues_source')->insert($data);
            if ($result){
                $msg = ['code' => 0,'msg'=>'添加成功！','data'=>[]];
                return json($msg);
            }else{
                $msg = ['code' => -200,'msg'=>'添加失败！','data'=>[]];
                return json($msg);
            }
        }
        return $this->fetch('clues/source_list_add');
    }
    //编辑线索来源
    public function sourceEdit(){
        if (Request::isAjax()){
            $data  = Request::param();
            $result = Db::table('crm_clues_source')->where(['id'=>$data['id']])->update($data);
            if ($result){
                $msg = ['code' => 0,'msg'=>'编辑成功！','data'=>[]];
                return json($msg);
            }else{
                $msg = ['code' => -200,'msg'=>'编辑失败！','data'=>[]];
                return json($msg);
            }
        }


        $result = Db::table('crm_clues_source') ->where(['id' => Request::param('id')])->find();
        $this -> assign('result',$result);
        return $this -> fetch('clues/source_list_edit');
    }
    //删除线索来源
    public function sourceDel(){
        $id = Request::param('id');
        $result = Db::table('crm_clues_source')->where('id',$id)->delete();
        if ($result){
            $msg = ['code' => 0,'msg'=>'删除成功！','data'=>[]];
            return json($msg);
        }else{
            $msg = ['code' => -200,'msg'=>'删除失败！','data'=>[]];
            return json($msg);
        }
    }


    //地区来源
    public function areaList(){
        if(request()->isPost()){
            $page =input('page')?input('page'):1;
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $list = db('crm_clues_area')
                ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
                ->toArray();
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }
        return $this->fetch();
    }
    //添加地区来源
    public function areaAdd(){
        if(request()->isPost()){
            $data['area_name'] = Request::param('area_name');
            $data['add_time'] = time();
            $result = Db::table('crm_clues_area')->insert($data);
            if ($result){
                $msg = ['code' => 0,'msg'=>'添加成功！','data'=>[]];
                return json($msg);
            }else{
                $msg = ['code' => -200,'msg'=>'添加失败！','data'=>[]];
                return json($msg);
            }
        }
        return $this->fetch('clues/area_list_add');
    }
    //编辑地区来源
    public function areaEdit(){
        if (Request::isAjax()){
            $data  = Request::param();
            $result = Db::table('crm_clues_area')->where(['id'=>$data['id']])->update($data);
            if ($result){
                $msg = ['code' => 0,'msg'=>'编辑成功！','data'=>[]];
                return json($msg);
            }else{
                $msg = ['code' => -200,'msg'=>'编辑失败！','data'=>[]];
                return json($msg);
            }
        }


        $result = Db::table('crm_clues_area') ->where(['id' => Request::param('id')])->find();
        $this -> assign('result',$result);
        return $this -> fetch('clues/area_list_edit');
    }
    //删除地区来源
    public function areaDel(){
        $id = Request::param('id');
        $result = Db::table('crm_clues_area')->where('id',$id)->delete();
        if ($result){
            $msg = ['code' => 0,'msg'=>'删除成功！','data'=>[]];
            return json($msg);
        }else{
            $msg = ['code' => -200,'msg'=>'删除失败！','data'=>[]];
            return json($msg);
        }
    }




    //转成客户
    public function toTurnKh(){
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
        
        if (Request::isAjax()){
            $data['kh_name']  = Request::param('kh_name');
            $data['kh_rank']  = Request::param('kh_rank');
            $data['kh_status']  = Request::param('kh_status');
            $data['kh_need']  = Request::param('kh_need');
            $data['to_kh_time'] = date("Y-m-d H:i:s",time());
            $data['status'] = 1;//0-线索，1客户，2公海
            // 状态变化 设置私人公共变化
            // 抢到客户名称为自己
            $data['pr_user_bef'] = Db::table('crm_leads')->where(['id'=>$value])->field('pr_user')->find();
            $data['pr_user'] = Session::get('username');
            $data['ispublic'] = 2;//1 公共 2私人抢夺 3 私人添加
            $data['id']  = Request::param('id');
            $result = Db::table('crm_leads')->where(['id'=>$data['id']])->update($data);
            if ($result){
                // 抢的次数增加1
                $curgetnum = $curgetnum + 1;
                $curgetnum = Db::table('admin')->where(['username'=>$curname])->update(['curgetnum'=>$curgetnum]);

                $msg = ['code' => 0,'msg'=>'线上客户抢成功！','data'=>[]];
                return json($msg);
            }else{
                $msg = ['code' => -200,'msg'=>'抱歉，线索客户抢失败！','data'=>[]];
                return json($msg);
            }
        }

        $khRankList = Db::table('crm_client_rank')->select();
        $khStatusList = Db::table('crm_client_status')->select();

        $this -> assign('khRankList',$khRankList);
        $this -> assign('khStatusList',$khStatusList);

        $result = Db::table('crm_leads') ->where(['id' => Request::param('id')])->find();
        $this -> assign('result',$result);
        return $this -> fetch('clues/turn_kh');
    }


    //抢客户
    public function toTurnKh2(){
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

        $gh_client = Db::table('crm_leads')->where(['id' => $data['id']])->where(['status' => 0])->find();
        if ($gh_client){
            // $data['to_kh_time'] = date("Y-m-d H:i:s",time());
            // $data['status'] = 1;//0-线索，1客户，2公海
            // $data['pr_user_bef'] = $gh_client['pr_user'];
            // $data['pr_user'] = Session::get('username');
            //  // 状态变化 设置私人公共变化
            // $data['ispublic'] = 2;//1 公共 2私人抢夺 3 私人添加

            $data['kh_name']  = $gh_client['xs_name'];
            $data['kh_rank']  = '';
            $data['kh_status']  = '';
            $data['to_kh_time'] = date("Y-m-d H:i:s",time());
            $data['ut_time'] = date("Y-m-d H:i:s",time());
            $data['status'] = 1;//0-线索，1客户，2公海
            // 状态变化 设置私人公共变化
            // 抢到客户名称为自己
            $data['pr_user_bef'] = Db::table('crm_leads')->where(['id'=>$value])->field('pr_user')->find();
            $data['pr_user'] = Session::get('username');
            $data['ispublic'] = 2;//1 公共 2私人抢夺 3 私人添加
            $data['id']  = $data['id'];


            $result = Db::table('crm_leads')->where(['id'=>$data['id']])->update($data);
            if ($result){
                // 抢的次数增加1
                $curgetnum = $curgetnum + 1;
                $curgetnum = Db::table('admin')->where(['username'=>$curname])->update(['curgetnum'=>$curgetnum]);

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
    //线索搜索
    public function cluesSearch(){
        $page =input('page')?input('page'):1;
        $limit =input('limit')?input('limit'):config('pageSize');
        $keyword = Request::param('keyword');
        $list= model('clues') -> getCluesSearchList($page,$limit,$keyword);
        return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];

    }


    //（我的线索）搜索
    public function personCluesSearch(){
        $page =input('page')?input('page'):1;
        $limit =input('limit')?input('limit'):config('pageSize');
        $keyword = Request::param('keyword');
        $list= model('clues') -> getPersonCluesSearchList($page,$limit,$keyword);
        return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];

    }


    //线索转移，变更负责人
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
                $msg = ['code' => 0,'msg'=>'转移'.$count.'条线索成功！','data'=>[]];
                return json($msg);
            }else{
                $msg = ['code' => -200,'msg'=>'转移失败！','data'=>[]];
                return json($msg);
            }
        }

        return $this -> fetch('clues/alter_pr_user');
    }


    //线索转移，变更负责人（个人）
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
                $msg = ['code' => 0,'msg'=>'转移'.$count.'条线索成功！','data'=>[]];
                return json($msg);
            }else{
                $msg = ['code' => -200,'msg'=>'转移失败！','data'=>[]];
                return json($msg);
            }
        }

        return $this -> fetch('clues/alter_pr_user');
    }

}