<?php
namespace app\admin\model;
use think\Model;
use think\Db;
class Clues extends Model
{
    //查询
    public function getCluesSearchList($page,$limit,$keyword){


        $mapAtTime = []; //添加时间
        $mapXsSource = []; //线索来源
        $mapPhone = []; //手机号模糊查询





        if ($keyword['at_time']!= ''){
            $at = $keyword['at_time'];//日期
            $end_at =date('Y-m-d',strtotime("$at+1day"));
            $mapAtTime = [['at_time','between time',[strtotime($at),strtotime($end_at)]]];
        }

        if ($keyword['xs_source']!= ''){

            $mapXsSource =  ['xs_source' => $keyword['xs_source']];
        }

        if ($keyword['phone'] != ''){
            $mapPhone = [['phone','like','%'.$keyword['phone'].'%']];
        }



        $result  = Db::table('crm_leads')
            ->where($mapPhone)
            ->where($mapXsSource)
            ->where($mapAtTime)
            ->where(['status'=>0])
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



    //个人查询
    public function getPersonCluesSearchList($page,$limit,$keyword){


        $mapAtTime = []; //添加时间
        $mapXsSource = []; //线索来源
        $mapPhone = []; //手机号模糊查询





        if ($keyword['at_time']!= ''){
            $at = $keyword['at_time'];//日期
            $end_at =date('Y-m-d',strtotime("$at+1day"));
            $mapAtTime = [['at_time','between time',[strtotime($at),strtotime($end_at)]]];
        }

        if ($keyword['xs_source']!= ''){

            $mapXsSource =  ['xs_source' => $keyword['xs_source']];
        }

        if ($keyword['phone'] != ''){
            $mapPhone = [['phone','like','%'.$keyword['phone'].'%']];
        }



        $result  = Db::table('crm_leads')
            ->where($mapPhone)
            ->where($mapXsSource)
            ->where($mapAtTime)
            ->where(['status'=>0]) //0 线索，1客户，2公海
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

}

