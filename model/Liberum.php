<?php
namespace app\admin\model;
use think\Model;
use think\Db;
class Liberum extends Model
{
    //查询
    public function getLiberumSearchList($page,$limit,$keyword){


        $mapAtTime = []; //添加时间
        $mapXsSource = []; //线索来源
        $mapPhone = []; //手机号模糊查询
        $mapKhName = [];//客户名称
       



        if ($keyword['at_time']!= ''){
            $at = $keyword['at_time'];//日期
            $end_at =date('Y-m-d',strtotime("$at+1day"));
            $mapAtTime = [['to_gh_time','between time',[strtotime($at),strtotime($end_at)]]];
        }

        if ($keyword['pr_gh_type']!= ''){

            $mapXsSource =  ['pr_gh_type' => $keyword['pr_gh_type']];
        }
        
        if ($keyword['phone'] != ''){
            $mapPhone = [['phone','like','%'.$keyword['phone'].'%']];
        }
        if ($keyword['kh_name'] != ''){
            $mapKhName = [['kh_name','like','%'.$keyword['kh_name'].'%']];
        }


        $result  = Db::table('crm_leads')
            ->where($mapPhone)
            ->where($mapKhName)
            ->where($mapXsSource)
            ->where($mapAtTime)
            ->where(['status'=>2]) //0-线索，1-客户，2-公海
            ->whereTime('to_gh_time',$keyword['timebucket'] ? $keyword['timebucket'] : null)
            ->order('to_gh_time desc')
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

