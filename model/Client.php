<?php
namespace app\admin\model;
use think\Model;
use think\Db;
class Client extends Model
{
    //查询
    public function getClientSearchList($page,$limit,$keyword){


        $mapAtTime = []; //添加时间
        $mapKhRank = []; //客户级别
        $mapKhStatus = []; //客户状态
        $mapPhone = []; //手机号模糊查询
        $mapKhName = [];//客户名称
        $mapXsSource = [];//线索/客户来源
        $mapPrUser = [];//业务员/负责人


        if ($keyword['at_time']!= ''){
            $at = $keyword['at_time'];//日期
            $end_at =date('Y-m-d',strtotime("$at+1day"));
            $mapAtTime = [['at_time','between time',[strtotime($at),strtotime($end_at)]]];
        }

        if ($keyword['kh_rank']!= ''){

            $mapKhRank =  ['kh_rank' => $keyword['kh_rank']];
        }

        if ($keyword['kh_status']!= ''){

            $mapKhStatus =  ['kh_status' => $keyword['kh_status']];
        }

        if ($keyword['phone'] != ''){
            $mapPhone = [['phone','like','%'.$keyword['phone'].'%']];
        }

        if ($keyword['kh_name'] != ''){
            $mapKhName = [['kh_name','like','%'.$keyword['kh_name'].'%']];
        }

        if ($keyword['xs_source']!= ''){

            $mapXsSource =  ['xs_source' => $keyword['xs_source']];
        }

        if ($keyword['pr_user'] != ''){
            $mapPrUser = [['pr_user','like','%'.$keyword['pr_user'].'%']];
        }


        $result  = Db::table('crm_leads')
            ->where($mapPhone)
            ->where($mapKhName)
            ->where($mapKhStatus)
            ->where($mapKhRank)
            ->where($mapXsSource)
            ->where($mapPrUser)
            ->where($mapAtTime)
            ->where(['status'=>1,'issuccess'=>-1])
            ->whereTime('at_time',$keyword['timebucket'] ? $keyword['timebucket'] : null)
            //->whereTime('at_time',$keyword['timebucket'] ? $keyword['timebucket'] : '')
            ->order('ut_time desc')
            ->paginate(array('list_rows'=>$limit,'page'=>$page))
            ->toArray();

        //数据集判断方式
        //if($result->isEmpty()){return null;}
        if($result['total'] == 0){
            return null;
        }else{
            return $result;
        }


    }



    //个人查询
    public function getPersonClientSearchList($page,$limit,$keyword){


        $mapAtTime = []; //添加时间
        $mapKhRank = []; //客户级别
        $mapKhStatus = []; //客户状态
        $mapPhone = []; //手机号模糊查询
        $mapKhName = [];//客户名称
        $mapXsSource = [];//线索/客户来源



        if ($keyword['at_time']!= ''){
            $at = $keyword['at_time'];//日期
            $end_at =date('Y-m-d',strtotime("$at+1day"));
            $mapAtTime = [['at_time','between time',[strtotime($at),strtotime($end_at)]]];
        }

        if ($keyword['kh_rank']!= ''){

            $mapKhRank =  ['kh_rank' => $keyword['kh_rank']];
        }

        if ($keyword['kh_status']!= ''){

            $mapKhStatus =  ['kh_status' => $keyword['kh_status']];
        }

        if ($keyword['phone'] != ''){
            $mapPhone = [['phone','like','%'.$keyword['phone'].'%']];
        }

        if ($keyword['kh_name'] != ''){
            $mapKhName = [['kh_name','like','%'.$keyword['kh_name'].'%']];
        }

        if ($keyword['xs_source']!= ''){

            $mapXsSource =  ['xs_source' => $keyword['xs_source']];
        }



        $result  = Db::table('crm_leads')
            ->where($mapPhone)
            ->where($mapKhName)
            ->where($mapKhStatus)
            ->where($mapKhRank)
            ->where($mapXsSource)
            ->where($mapAtTime)
            ->where(['status'=>1,'issuccess'=>-1]) //0 线索，1客户，2公海
            ->where(['pr_user' => session('username')]) //负责人
            ->whereTime('at_time',$keyword['timebucket'] ? $keyword['timebucket'] : null)
            ->order('ut_time desc')
            ->paginate(array('list_rows'=>$limit,'page'=>$page))
            ->toArray();

        //数据集判断方式
        //if($result->isEmpty()){return null;}
        if($result['total'] == 0){
            return null;
        }else{
            return $result;
        }


    }
    //成交客户查询
    public function getChengjiaoClientSearchList($page,$limit,$keyword){


        $mapAtTime = []; //添加时间
        $mapKhRank = []; //客户级别
        $mapKhStatus = []; //客户状态
        $mapPhone = []; //手机号模糊查询
        $mapKhName = [];//客户名称
        $mapXsSource = [];//线索/客户来源
        $mapPrUser = [];//业务员/负责人

        if ($keyword['pr_user'] != ''){
            $mapPrUser['pr_user'] = $keyword['pr_user'];
            //$mapPrUser = [['pr_user','like','%'.$keyword['pr_user'].'%']];
        }else{
            if (session('aid') == 1) {
                
            }else{
                $mapPrUser['pr_user'] =session('username');
            }
            
        }
        if ($keyword['at_time']!= ''){
            $at = $keyword['at_time'];//日期
            $end_at =date('Y-m-d',strtotime("$at+1day"));
            $mapAtTime = [['at_time','between time',[strtotime($at),strtotime($end_at)]]];
        }

        if ($keyword['kh_rank']!= ''){

            $mapKhRank =  ['kh_rank' => $keyword['kh_rank']];
        }

        if ($keyword['kh_status']!= ''){

            $mapKhStatus =  ['kh_status' => $keyword['kh_status']];
        }

        if ($keyword['phone'] != ''){
            $mapPhone = [['phone','like','%'.$keyword['phone'].'%']];
        }

        if ($keyword['kh_name'] != ''){
            $mapKhName = [['kh_name','like','%'.$keyword['kh_name'].'%']];
        }

        if ($keyword['xs_source']!= ''){

            $mapXsSource =  ['xs_source' => $keyword['xs_source']];
        }



        $result  = Db::table('crm_leads')
            ->where($mapPhone)
            ->where($mapKhName)
            ->where($mapKhStatus)
            ->where($mapKhRank)
            ->where($mapXsSource)
            ->where($mapAtTime)
            ->where($mapPrUser)
            ->where(['status'=>1,'issuccess'=>1]) //0 线索，1客户，2公海
            // ->where(['pr_user' => session('username')]) //负责人
            ->whereTime('at_time',$keyword['timebucket'] ? $keyword['timebucket'] : null)
            ->order('ut_time desc')
            ->paginate(array('list_rows'=>$limit,'page'=>$page))
            ->toArray();

        //数据集判断方式
        //if($result->isEmpty()){return null;}
        if($result['total'] == 0){
            return null;
        }else{
            return $result;
        }


    }

}

