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
                'match_value'=>'= 1',
                'icon'=>'fas fa-times'
            ],
            '1'=>[
                'id'=>'2',
                'preferrence_name'=>'Life Threaten',
                'sd_field_id'=>'8',
                'value_at'=>'2',
                'value_length'=>'1',
                'match_value'=>'= 1',
                'icon'=>'fas fa-exclamation'
            ],
            '2'=>[
                'id'=>'3',
                'preferrence_name'=>'Disability',
                'sd_field_id'=>'8',
                'value_at'=>'3',
                'value_length'=>'1',
                'match_value'=>'= 1',
                'icon'=>'fas fa-user-injured'
            ],
            '3'=>[
                'id'=>'4',
                'preferrence_name'=>'Hospitalization',
                'sd_field_id'=>'8',
                'value_at'=>'4',
                'value_length'=>'1',
                'match_value'=>'= 1',
                'icon'=>'far fa-hospital'
            ],
            '4'=>[
                'id'=>'5',
                'preferrence_name'=>'Anomaly',
                'sd_field_id'=>'8',
                'value_at'=>'5',
                'value_length'=>'1',
                'match_value'=>'= 1',
                'icon'=>'fas fa-stethoscope'
            ],
            '5'=>[
                'id'=>'6',
                'preferrence_name'=>'Other Serious',
                'sd_field_id'=>'8',
                'value_at'=>'6',
                'value_length'=>'1',
                'match_value'=>'= 1',
                'icon'=>'fas fa-heartbeat'
            ],
            '6'=>[
                'id'=>'7',
                'preferrence_name'=>'Serious Case',
                'sd_field_id'=>'8',
                'value_at'=>'1',
                'value_length'=>'6',
                'match_value'=>'>= 1',
                'icon'=>'fas fa-skull-crossbones'
            ]
        ];
        $userinfo = $this->request->getSession()->read('Auth.User');
        $sdCases = TableRegistry::get('SdCases');
        foreach($preferrence_list as $k => $preferrence_detail){
            $searchResult = $sdCases->find()->select(['caseNo','id']);
            if(array_key_exists('value_at',$preferrence_detail))
                $searchResult = $searchResult->join([
                    'pw' => [
                        'table' => 'sd_product_workflows',
                        'type' => 'LEFT',
                        'conditions' => ['SdCases.sd_product_workflow_id = pw.id'],
                    ],
                    'pd' => [
                        'table' => 'sd_products',
                        'type' => 'INNER',
                        'conditions' => ['pw.sd_product_id = pd.id','pd.sd_company_id ='.$userinfo['company_id']],
                    ],
                    'sv' => [
                        'table' => 'sd_field_values',
                        'type' => 'INNER',
                        'conditions' => ['sv.sd_field_id = '.$preferrence_detail['sd_field_id'],'sv.sd_case_id = SdCases.id','SUBSTR(sv.field_value,'.$preferrence_detail['value_at'].','.$preferrence_detail['value_length'].') '.$preferrence_detail['match_value']],
                    ]
                ])->where(['SdCases.sd_workflow_activity_id !='=>'9999']);
            else  $searchResult = $searchResult->join([
                'pw' => [
                    'table' => 'sd_product_workflows',
                    'type' => 'LEFT',
                    'conditions' => ['SdCases.sd_product_workflow_id = pw.id'],
                ],
                'pd' => [
                    'table' => 'sd_products',
                    'type' => 'INNER',
                    'conditions' => ['pw.sd_product_id = pd.id','pd.sd_company_id ='.$userinfo['company_id']],
                ],
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
        $smq_listTable = TableRegistry::get('mdr_smq_list');
        $smq_list_d = $smq_listTable->find()->select(['smq_code','smq_name','smq_content.term_scope'])
        ->join([
            'smq_content'=>[
                'table'=>'mdr_smq_content',
                'type'=>'INNER',
                'conditions'=>['smq_content.smq_code = mdr_smq_list.smq_code',('smq_content.term_scope != \'0\'')]
            ]
        ])
        // ->where(['smq_content.term_scope IS NOT'=>'0'])
        ;
        $smq_list = array();
        foreach($smq_list_d as $k => $detail)
            $smq_list[$detail['smq_code']] = $detail['smq_name'];
        $this->set(compact('preferrence_list','smq_list'));
    }
}