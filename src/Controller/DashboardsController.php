<?php
namespace App\Controller;
use Cake\ORM\TableRegistry;

use App\Controller\AppController;
use App\Controller\SdSectionController;

class DashboardsController extends AppController {
    public function searchBtn(){

    }
    public function index (){
        $this->viewBuilder()->setLayout('main_layout');
        //TODO DB somewhere store the user's preferrence
        $preferrence_list = [
            '0'=>[
                'id'=>'1',
                'preferrence_name'=>'Death',
                'sd_field_id'=>'8',
                'value_at'=>'1',
                'value_length'=>'1',
                'match_value'=>'= 1'
            ],
            '1'=>[
                'id'=>'2',
                'preferrence_name'=>'Life Threaten',
                'sd_field_id'=>'8',
                'value_at'=>'2',
                'value_length'=>'1',
                'match_value'=>'= 1'
            ],
            '2'=>[
                'id'=>'3',
                'preferrence_name'=>'Disability',
                'sd_field_id'=>'8',
                'value_at'=>'3',
                'value_length'=>'1',
                'match_value'=>'= 1'
            ],
            '3'=>[
                'id'=>'4',
                'preferrence_name'=>'Prolonged',
                'sd_field_id'=>'8',
                'value_at'=>'4',
                'value_length'=>'1',
                'match_value'=>'= 1'
            ],
            '4'=>[
                'id'=>'5',
                'preferrence_name'=>'Anomaly',
                'sd_field_id'=>'8',
                'value_at'=>'5',
                'value_length'=>'1',
                'match_value'=>'= 1'
            ],
            '5'=>[
                'id'=>'6',
                'preferrence_name'=>'Other Serious',
                'sd_field_id'=>'8',
                'value_at'=>'6',
                'value_length'=>'1',
                'match_value'=>'= 1'
            ],
            '6'=>[
                'id'=>'7',
                'preferrence_name'=>'Serious Case',
                'sd_field_id'=>'8',
                'value_at'=>'1',
                'value_length'=>'6',
                'match_value'=>'>= 1'
            ]
        ];
        $userinfo = $this->request->getSession()->read('Auth.User');
        $sdCases = TableRegistry::get('SdCases');
        foreach($preferrence_list as $k => $preferrence_detail){
            $searchResult = $sdCases->find()->select(['caseNo','id']);
            if(array_key_exists('value_at',$preferrence_detail))
                $searchResult = $searchResult->join([
                    'sv' => [
                        'table' => 'sd_field_values',
                        'type' => 'INNER',
                        'conditions' => ['sv.sd_field_id = '.$preferrence_detail['sd_field_id'],'sv.sd_case_id = SdCases.id','SUBSTR(sv.field_value,'.$preferrence_detail['value_at'].','.$preferrence_detail['value_length'].') '.$preferrence_detail['match_value']],
                    ]
                ])->where(['SdCases.sd_workflow_activity_id !='=>'9999']);
            else  $searchResult = $searchResult->join([
                'sv' => [
                    'table' => 'sd_field_values',
                    'type' => 'INNER            ',
                    'conditions' => ['sv.field_value = '.$preferrence_detail['match_value'],'sv.sd_field_id = '.$preferrence_detail['sd_field_id'],'sv.sd_case_id = SdCases.id'],
                ]
            ])->where(['SdCases.sd_workflow_activity_id !='=>'9999']);
            // debug($searchResult);
            if($userinfo['sd_role_id']>2) {
                $searchResult = $searchResult->join([
                    'ua'=>[
                        'table' =>'sd_user_assignments',
                        'type'=>'RIGHT',
                        'conditions'=>['ua.sd_product_workflow_id = SdCases.sd_product_workflow_id','ua.sd_user_id = '.$userinfo['id']]
                    ]
                ]);
            }
            $preferrence_list[$k]['sql'] = $userinfo;
            $preferrence_list[$k]['count'] = $searchResult->distinct()->count();
        }
        $this->set(compact('preferrence_list'));
    }
}