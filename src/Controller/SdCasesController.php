<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
/**
 * SdCases Controller
 *
 * @property \App\Model\Table\SdCasesTable $SdCases
 *
 * @method \App\Model\Entity\SdCase[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SdCasesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['SdProductWorkflows', 'SdWorkflowActivities', 'SdUsers']
        ];
        $sdCases = $this->paginate($this->SdCases);

        $this->set(compact('sdCases'));
    }

    /**
     * View method
     *
     * @param string|null $id Sd Case id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $sdCase = $this->SdCases->get($id, [
            'contain' => ['SdProductWorkflows', 'SdWorkflowActivities', 'SdUsers', 'SdCaseGeneralInfos', 'SdFieldValues']
        ]);

        $this->set('sdCase', $sdCase);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $sdCase = $this->SdCases->newEntity();
        if ($this->request->is('post')) {
            $sdCase = $this->SdCases->patchEntity($sdCase, $this->request->getData());
            if ($this->SdCases->save($sdCase)) {
                $this->Flash->success(__('The sd case has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sd case could not be saved. Please, try again.'));
        }
        $sdProductWorkflows = $this->SdCases->SdProductWorkflows->find('list', ['limit' => 200]);
        $sdWorkflowActivities = $this->SdCases->SdWorkflowActivities->find('list', ['limit' => 200]);
        $sdUsers = $this->SdCases->SdUsers->find('list', ['limit' => 200]);
        $this->set(compact('sdCase', 'sdProductWorkflows', 'sdWorkflowActivities', 'sdUsers'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Sd Case id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sdCase = $this->SdCases->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $sdCase = $this->SdCases->patchEntity($sdCase, $this->request->getData());
            if ($this->SdCases->save($sdCase)) {
                $this->Flash->success(__('The sd case has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sd case could not be saved. Please, try again.'));
        }
        $sdProductWorkflows = $this->SdCases->SdProductWorkflows->find('list', ['limit' => 200]);
        $sdWorkflowActivities = $this->SdCases->SdWorkflowActivities->find('list', ['limit' => 200]);
        $sdUsers = $this->SdCases->SdUsers->find('list', ['limit' => 200]);
        $this->set(compact('sdCase', 'sdProductWorkflows', 'sdWorkflowActivities', 'sdUsers'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Sd Case id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sdCase = $this->SdCases->get($id);
        if ($this->SdCases->delete($sdCase)) {
            $this->Flash->success(__('The sd case has been deleted.'));
        } else {
            $this->Flash->error(__('The sd case could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Duplicate detection
     *
     *
     */
    public function duplicateDetection()
    {
        if ($this->request->is('post')) {
            $this->autoRender = false;
            try{
                $searchKey = $this->request->getData();
                $user = TableRegistry::get('SdUsers')->get($searchKey['userId']);
                $searchResult =  $this->SdCases->find()
                                    ->select([
                                        'versions'=>'SdCases.version_no',
                                        'SdCases.id',
                                        'SdCases.caseNo',
                                        'patient_initial'=>'pi.field_value',
                                        'pi.set_number',
                                        'patient_age'=>'pa.field_value',
                                        'pa.set_number',
                                        'patient_age_unit'=>'pau.field_value',
                                        'pau.set_number',
                                        'patient_gender'=>'pg.field_value',
                                        'pg.set_number',
                                        'patient_dob'=>'pdob.field_value',
                                        'pdob.set_number',
                                        'reporter_firstname'=>'rfn.field_value',
                                        'rfn.set_number',
                                        'reporter_lastname'=>'rln.field_value',
                                        'rln.set_number',
                                        'event_report_term' =>'ert.field_value',
                                        'ert.set_number',
                                        'event_onset_date'=>'eod.field_value',
                                        'eod.set_number',
                                        'patient_ethnic_origin' => 'peo.field_value',
                                        'peo.set_number',
                                        'patient_age_group'=>'pag.field_value',
                                        'pag.set_number',
                                        'meddraResult.field_value',
                                        'product_name'=>'pd.product_name',
                                        'country'=>'wf.country'
                                    ])
                                    ->join([
                                        'pi' =>[
                                            'table' =>'sd_field_values',
                                            'type'=>'LEFT',
                                            'conditions' => ['pi.sd_field_id = 79','pi.status = 1','pi.sd_case_id = SdCases.id'
                                            ]
                                        ],
                                        'pa' =>[
                                            'table' =>'sd_field_values',
                                            'type'=>'LEFT',
                                            'conditions' => ['pa.sd_field_id = 86','pa.status = 1','pa.sd_case_id = SdCases.id'
                                            ]
                                        ],
                                        'pau'=>[
                                            'table' =>'sd_field_values',
                                            'type'=>'LEFT',
                                            'conditions' => ['pau.sd_field_id = 87','pau.status = 1','pau.sd_case_id = SdCases.id','pau.set_number=pa.set_number'
                                            ]
                                        ],
                                        'pg' =>[
                                            'table' =>'sd_field_values',
                                            'type'=>'LEFT',
                                            'conditions' => ['pg.sd_field_id = 93','pg.status = 1','pg.sd_case_id = SdCases.id'
                                            ]
                                        ],
                                        'pdob' =>[
                                            'table' =>'sd_field_values',
                                            'type'=>'LEFT',
                                            'conditions' => ['pdob.sd_field_id = 85','pdob.status = 1','pdob.sd_case_id = SdCases.id'
                                            ]
                                        ],
                                        'rfn' =>[
                                            'table' =>'sd_field_values',
                                            'type'=>'LEFT',
                                            'conditions' => ['rfn.sd_field_id = 26','rfn.status = 1','rfn.sd_case_id = SdCases.id'
                                            ]
                                        ],
                                        'rln' =>[
                                            'table' =>'sd_field_values',
                                            'type'=>'LEFT',
                                            'conditions' => ['rln.sd_field_id = 28','rln.status = 1','rln.sd_case_id = SdCases.id'
                                            ]
                                        ],
                                        'ert' =>[
                                            'table' =>'sd_field_values',
                                            'type'=>'LEFT',
                                            'conditions' => ['ert.sd_field_id = 149','ert.status = 1','ert.sd_case_id = SdCases.id'
                                            ]
                                        ],
                                        'peo' =>[
                                            'table' =>'sd_field_values',
                                            'type'=>'LEFT',
                                            'conditions' => ['peo.sd_field_id = 235','peo.status = 1','peo.sd_case_id = SdCases.id'
                                            ]
                                        ],
                                        'pag' =>[
                                            'table' =>'sd_field_values',
                                            'type'=>'LEFT',
                                            'conditions' => ['pag.sd_field_id = 90','pag.status = 1','pag.sd_case_id = SdCases.id'
                                            ]
                                        ],
                                        'eod' =>[
                                            'table' =>'sd_field_values',
                                            'type'=>'LEFT',
                                            'conditions' => ['eod.sd_field_id = 156','eod.status = 1','eod.sd_case_id = SdCases.id'
                                            ]
                                        ],
                                        'meddraResult' =>[
                                            'table' =>'sd_field_values',
                                            'type'=>'LEFT',
                                            'conditions' => ['meddraResult.sd_field_id = 496','meddraResult.status = 1', 'meddraResult.sd_case_id = SdCases.id'
                                            ]
                                        ],
                                        'pdw' =>[
                                            'table' =>'sd_product_workflows',
                                            'type'=>'INNER',
                                            'conditions' => ['pdw.id = SdCases.sd_product_workflow_id','pdw.id = '.$searchKey['sd_product_workflow_id']
                                            ]
                                        ],
                                        'pd' =>[
                                            'table' =>'sd_products',
                                            'type'=>'INNER',
                                            'conditions' => ['pdw.sd_product_id = pd.id']
                                        ],
                                        'wf' =>[
                                            'table' =>'sd_workflows',
                                            'type'=>'INNER',
                                            'conditions' => ['wf.id = pdw.sd_workflow_id']
                                        ]
                                    ])->group('SdCases.id');
                if($user['sd_role_id']>2) {
                    $searchResult = $searchResult->join([
                        'ua'=>[
                            'table' =>'sd_user_assignments',
                            'type'=>'INNER',
                            'conditions'=>['ua.sd_product_workflow_id = SdCases.sd_product_workflow_id','ua.sd_user_id = '.$user['id']]
                        ]
                    ]);}
                
                if(!empty($searchKey['product_id'])) $searchResult = $searchResult->where(['pd.id '=>$searchKey['product_id']]);
                if(!empty($searchKey['country'])) $searchResult = $searchResult->where(['wf.country'=>$searchKey['country']]);
                if(!empty($searchKey['patient_initial'])) $searchResult = $searchResult->where(['pi.field_value LIKE'=>'%'.trim($searchKey['patient_initial']).'%']);
                if(!empty($searchKey['patient_age'])) $searchResult = $searchResult->where(['pa.field_value  LIKE'=>'%'.trim($searchKey['patient_age']).'%']);
                if(!empty($searchKey['patient_age_unit'])) $searchResult = $searchResult->where(['pau.field_value  LIKE'=>'%'.trim($searchKey['patient_age_unit']).'%']);
                if(!empty($searchKey['patient_dob'])) $searchResult = $searchResult->where(['pdob.field_value  LIKE'=>'%'.trim($searchKey['patient_dob']).'%']);
                if(!empty($searchKey['patient_gender'])) $searchResult = $searchResult->where(['pg.field_value  LIKE'=>'%'.trim($searchKey['patient_gender']).'%']);
                if(!empty($searchKey['reporter_firstname'])) $searchResult = $searchResult->where(['rfn.field_value  LIKE'=>'%'.trim($searchKey['reporter_firstname']).'%']);
                if(!empty($searchKey['reporter_lastname'])) $searchResult = $searchResult->where(['rln.field_value  LIKE'=>'%'.trim($searchKey['reporter_lastname']).'%']);
                if(!empty($searchKey['event_report_term'])) $searchResult = $searchResult->where(['ert.field_value  LIKE'=>'%'.trim($searchKey['event_report_term']).'%']);
                if(!empty($searchKey['patient_ethnic_origin'])) $searchResult = $searchResult->where(['peo.field_value  LIKE'=>'%'.trim($searchKey['patient_ethnic_origin']).'%']);
                if(!empty($searchKey['patient_age_group'])) $searchResult = $searchResult->where(['pag.field_value  LIKE'=>'%'.trim($searchKey['patient_age_group']).'%']);
                if(!empty($searchKey['event_onset_date'])) $searchResult = $searchResult->where(['eod.field_value  LIKE'=>'%'.trim($searchKey['event_onset_date']).'%']);
                if(!empty($searchKey['meddraResult-496'])) $searchResult = $searchResult->where(['meddraResult.field_value  LIKE'=>'%'.trim($searchKey['meddraResult-496']).'%']);
                // debug($searchResult);// print_r($searchResult);
            }catch (\PDOException $e){
                echo "cannot the case find in database";
            }
            echo json_encode($searchResult);
            // $this->set(compact('searchResult'));
            die();
        }
    }
    /**
     *
     *
     * Use when case list
     */
    public function search(){
        $userinfo = $this->request->getSession()->read('Auth.User');
        if($this->request->is('POST')){
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
                    'preferrence_name'=>'Life threaten',
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
                    'preferrence_name'=>'Hospitalization',
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
            $this->autoRender = false;
            $searchKey = $this->request->getData();
            $sdCases = TableRegistry::get('SdCases');
            $sdFieldValues = TableRegistry::get('SdFieldValues');
            try{
                $user = TableRegistry::get('SdUsers')->get($searchKey['userId']);
                $searchResult = $sdCases->find()->select([
                    'SdCases.id',
                    'versions'=>'SdCases.version_no',
                    'pw.sd_product_id',
                    'submission_due_date'=>'submission_due_date.field_value',
                    'activity_due_date'=>'activity_due_date.field_value',
                    'caseNo',
                    'patient_id_v'=>'patient_id.field_value',
                    'patient_dob_v'=>'patient_dob.field_value',
                    'patient_gender_v'=>'patient_gender.field_value',
                    'SdCases.status',
                    'sd_workflow_activity_id',
                    'pd.product_name',
                    'country'=>'wf.country',
                    'wa.activity_name',
                    'pt.field_value',
                    'llt.field_Value',
                    'SdCases.sd_user_id',
                    'serious_case.id',
                    'product_type_label'=>'product_type_look_up.caption',
                    'product_type.field_value',
                    'clinical_trial.id'])
                    ->distinct()
                    ->join([
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
                        'wf'=>[
                            'table' => 'sd_workflows',
                            'type'=>'LEFT',
                            'conditions' => ['wf.id = pw.sd_workflow_id'],
                        ],
                        'wa' => [
                            'table' => 'sd_workflow_activities',
                            'type' => 'LEFT',
                            'conditions' => ['wa.id = SdCases.sd_workflow_activity_id'],
                        ],
                        'submission_due_date'=>[
                            'table'=>'sd_field_values',
                            'type'=>'LEFT',
                            'conditions'=>['submission_due_date.sd_field_id = 415','submission_due_date.sd_case_id = SdCases.id','submission_due_date.status = 1']
                        ],
                        'patient_id'=>[
                            'table'=>'sd_field_values',
                            'type'=>'LEFT',
                            'conditions'=>['patient_id.sd_field_id = 79','patient_id.sd_case_id = SdCases.id','patient_id.status = 1']
                        ],
                        'patient_dob'=>[
                            'table'=>'sd_field_values',
                            'type'=>'LEFT',
                            'conditions'=>['patient_dob.sd_field_id = 85','patient_dob.sd_case_id = SdCases.id','patient_dob.status = 1']
                        ],
                        'patient_gender'=>[
                            'table'=>'sd_field_values',
                            'type'=>'LEFT',
                            'conditions'=>['patient_gender.sd_field_id = 93','patient_gender.sd_case_id = SdCases.id','patient_gender.status = 1']
                        ],
                        'activity_due_date'=>[
                            'table'=>'sd_field_values',
                            'type'=>'LEFT',
                            'conditions'=>['activity_due_date.sd_field_id = 414','activity_due_date.sd_case_id = SdCases.id','activity_due_date.status = 1']
                        ],
                        'serious_case'=>[
                            'table'=>'sd_field_values',
                            'type'=>'LEFT',
                            'conditions' => ['serious_case.sd_field_id = 8','serious_case.sd_case_id = SdCases.id','SUBSTR(serious_case.field_value,1,6) >= 1'],
                        ],
                        'pt'=>[
                            'table'=>'sd_field_values',
                            'type'=>'LEFT',
                            'conditions'=>['pt.sd_field_id = 394','pt.sd_case_id = SdCases.id']
                        ],
                        'llt'=>[
                            'table'=>'sd_field_values',
                            'type'=>'LEFT',
                            'conditions'=>['llt.sd_field_id = 392','llt.sd_case_id = SdCases.id']
                        ],
                        'product_type'=>[
                            'table'=>'sd_field_values',
                            'type'=>'LEFT',
                            'conditions'=>['product_type.sd_field_id = 282','product_type.sd_case_id = SdCases.id'],
                        ],
                        'product_type_look_up'=>[
                            'table'=>'sd_field_value_look_ups',
                            'type'=>'LEFT',
                            'conditions'=>['product_type_look_up.sd_field_id = 282','product_type_look_up.value = product_type.field_value'],
                        ],
                        'clinical_trial'=>[
                            'table'=>'sd_field_values',
                            'type'=>'LEFT',
                            'conditions' => ['clinical_trial.sd_field_id = 40','clinical_trial.sd_case_id = SdCases.id','clinical_trial.field_value = 1'],
                        ]
                    ])->order(['caseNo'=>'ASC','versions'=>'DESC'])->group(['SdCases.id']);
                if(array_key_exists('preferrenceId',$searchKey) ) {
                    $preferrence_detail = $preferrence_list[$searchKey['preferrenceId']-1];
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
                            'type' => 'INNER',
                            'conditions' => ['sv.field_value = '.$preferrence_detail['match_value'],'sv.sd_field_id '.$preferrence_detail['sd_field_id'],'sv.sd_case_id = SdCases.id'],
                        ]
                    ])->where(['SdCases.sd_workflow_activity_id !='=>'9999']);
                }
                if($user['sd_role_id']>2) {
                    $searchResult = $searchResult->join([
                        'ua'=>[
                            'table' =>'sd_user_assignments',
                            'type'=>'INNER',
                            'conditions'=>['ua.sd_product_workflow_id = SdCases.sd_product_workflow_id','ua.sd_user_id = '.$searchKey['userId']]
                        ]
                    ]);}
                if(!empty($searchKey['caseNo'])) $searchResult = $searchResult->where(['caseNo LIKE'=>'%'.$searchKey['caseNo'].'%']);
                if(!empty($searchKey['caseStatus'])) {
                    if($searchKey['caseStatus']=='1') $searchResult = $searchResult->where(['SdCases.status'=>'1']);
                    else if($searchKey['caseStatus']=='2') $searchResult = $searchResult->where(['SdCases.status'=>'0']);
                }
                // $searchKey['meddra_smq'] = '20000001';
                // $searchKey['meddra_smq_scope'] = '2';
                if(!empty($searchKey['meddra_smq'])){ 
                    $SMQsearch = '';
                    if($searchKey['meddra_smq_scope'] == '2')
                    $SMQboardSearch = 'smq.term_scope = 2 AND';
                    $searchResult = $searchResult
                        ->join([
                        // 'smq_pt'=>[
                        //     'table' => 'smq_pt_1',
                        //     'type' => 'LEFT',
                        //     'conditions' => ['smq_pt.smq_code'=>$searchKey['meddra_smq'], 'smq_pt.pt_code'=>'pt.field_value']//TODO narrow/broad search
                        // ],
                        'smq'=>[
                            'table' => 'mdr_smq_content',
                            'type' => 'INNER',
                            'conditions' => [$SMQsearch.'smq.smq_code = '.$searchKey['meddra_smq'].' AND 
                                        ((smq.term_level = 4 AND smq.term_code = llt.field_value)OR(smq.term_level = 5 AND smq.term_code = pt.field_value))']//TODO narrow/broad search
                        ]
                    ]);
                }
                // die();
                if(!empty($searchKey['patient_dob'])) $searchResult = $searchResult->where(['patient_dob.field_value'=>$searchKey['patient_dob']]);
                if(!empty($searchKey['patient_gender'])) $searchResult = $searchResult->where(['patient_gender.field_value'=>$searchKey['patient_gender']]);
                if(!empty($searchKey['patient_id'])) $searchResult = $searchResult->where(['patient_id.field_value LIKE'=>'%'.$searchKey['patient_id'].'%']);
                if(!empty($searchKey['searchProductName'])) $searchResult = $searchResult->where(['product_name  LIKE'=>'%'.$searchKey['searchProductName'].'%']);
                $output = $searchResult->all()->toArray();  
                foreach($output as $key => $caseDetail){
                    if($caseDetail['submission_due_date']==null&&$caseDetail['activity_due_date']==null) continue;
                    $scaseTime = Intval(substr($caseDetail['submission_due_date'],4))*10000+Intval(substr($caseDetail['submission_due_date'],2,2))*100+Intval(substr($caseDetail['submission_due_date'],0,2));
                    $acaseTime = Intval(substr($caseDetail['activity_due_date'],4))*10000+Intval(substr($caseDetail['activity_due_date'],2,2))*100+Intval(substr($caseDetail['activity_due_date'],0,2));
                    // debug($acaseTime);
                    if(!empty($searchKey['submission_due_date_end']))
                        if($scaseTime > Intval(substr($searchKey['submission_due_date_end'],4))*10000+Intval(substr($searchKey['submission_due_date_end'],2,2))*100+Intval(substr($searchKey['submission_due_date_end'],0,2))){
                            unset($output[$key]);
                            continue;
                        }         
                    if(!empty($searchKey['submission_due_date_start']))
                        if($scaseTime < Intval(substr($searchKey['submission_due_date_start'],4))*10000+Intval(substr($searchKey['submission_due_date_start'],2,2))+Intval(substr($searchKey['submission_due_date_start'],0,2))){
                            unset($output[$key]);
                            continue;
                        }    
                    if(!empty($searchKey['activity_due_date_end']))
                        if($acaseTime > Intval(substr($searchKey['activity_due_date_end'],4))*10000+Intval(substr($searchKey['activity_due_date_end'],2,2))*100+Intval(substr($searchKey['activity_due_date_end'],0,2))){
                            unset($output[$key]);
                            continue;
                        }         
                    if(!empty($searchKey['activity_due_date_start']))
                        if($acaseTime < Intval(substr($searchKey['activity_due_date_start'],4))*10000+Intval(substr($searchKey['activity_due_date_start'],2,2))*100+Intval(substr($searchKey['activity_due_date_start'],0,2))){
                            unset($output[$key]);
                            continue;
                        }    
                    // debug($acaseTime);                        
                }              
            }catch (\PDOException $e){
                echo "Internal Error";
            }
            if($searchResult->count()>0)
                echo json_encode($output);
            else echo 0;
            // $this->set(compact('searchResult'));
            die();
        } else $this->autoRender = true;
    }
    /**
     * Register SAE method / Add case
     *
     *
     */
    public function caseregistration()
    {
        $this->viewBuilder()->setLayout('main_layout');
        $userinfo = $this->request->getSession()->read('Auth.User');
        //TODO Check whether this user has permission to create case
        //TODO fetch product_workflow only this user can access
        $productInfo = TableRegistry::get('SdProducts')
            ->find()
            ->select(['id','product_name'])
            ->contain(['SdProductWorkflows.SdWorkflows'=>['fields'=>['SdWorkflows.country']]])
            ->where(['sd_company_id '=>$userinfo['company_id']])
            ->group(['SdProducts.id']);
        // debug($userinfo);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $requestData = $this->request->getData();
            $product_data = TableRegistry::get('SdProducts')->get($requestData['product_id']);
            $sdFieldValueTable = TableRegistry::get('SdFieldValues');
            $sdSectionSetTable = TableRegistry::get('SdSectionSets');
            $sdCompaniesTable = TableRegistry::get('SdCompanies');
            $companyDetail = $sdCompaniesTable->find()->select(['company_abbreviation'])->where(['id'=>$userinfo['sd_company_id']])->first();
            // $requestDataField = $requestData['field_value'];
            /**
             * save case
             */

            $sdWorkflowActivities = TableRegistry::get('SdWorkflowActivities')
                ->find()
                ->select([
                    'SdWorkflowActivities.id',
                    'SdWorkflowActivities.sd_workflow_id',
                    'wf.id',
                    'pwf.id'
                ])->join([
                    'wf' =>[
                        'table' =>'sd_workflows',
                        'type'=>'LEFT',
                        'conditions'=>['wf.id = SdWorkflowActivities.sd_workflow_id']
                    ],
                    'pwf'=>[
                        'table'=>'sd_product_workflows',
                        'type'=>'LEFT',
                        'conditions'=>['pwf.sd_workflow_id = wf.id']
                    ]
                ])->where(['pwf.id'=>$requestData['sd_product_workflow_id'],'SdWorkflowActivities.order_no'=>'1'])->first();
                $sdCase = $this->SdCases->newEntity();
                $savedData['sd_product_workflow_id'] = $requestData['sd_product_workflow_id'];
                $savedData['status'] = "1";
                $companyDetail['order'] = $product_data['caseNo_convention'];//TODO
                $order = explode(",",$companyDetail['order']);
                $date_str = $this->caseNoGenerator($order,$sdWorkflowActivities['id'], $requestData['sd_product_workflow_id'], $companyDetail['company_abbreviation'],$product_data['product_abbreviation']);
                $savedData['caseNo'] = $date_str;
                $savedData['version_no'] = "1";
                //TODO VERSION UP MIGHT BE INCLUDED
                $savedData['sd_user_id'] = $userinfo['id'];
                $savedData['sd_workflow_activity_id'] = $sdWorkflowActivities['id'];
                $sdCase = $this->SdCases->patchEntity($sdCase, $savedData);
                // debug($sdCase);
                $savedCase=$this->SdCases->save($sdCase);
                if (!$savedCase) {
                    debug($sdCase);
                    echo"problem in saving sdCase";
                    return null;
                }

                /**
                 *
                 * save field into these cases
                 */
                //data on product
                $fieldId_product_set = [
                    '39'=>'study_no',
                    '367'=>'product_name',
                    '368'=>'short_desc',
                    '176'=>'product_desc', 
                    '40'=>'study_type',
                    '175'=>'sd_product_flag',
                    '283'=>'WHODD_decode',
                    '344'=>'WHODD_code',
                    '389'=>'WHODD_name',
                    '284'=>'mfr_name'
                ];
                foreach($fieldId_product_set as $fieldId =>$product_info)
                {
                    if(empty($product_data[$product_info])) continue;
                    $sdFieldValueEntity = $sdFieldValueTable->newEntity();
                    $dataSet = [
                        'sd_case_id' => $savedCase->id,
                        'sd_field_id' => $fieldId,
                        'set_number' => '1',
                        'created_time' =>date("Y-m-d H:i:s"),
                        'field_value' =>$product_data[$product_info],
                        'status' =>'1',
                    ];
                    $savedFieldValueEntity = $sdFieldValueTable->patchEntity($sdFieldValueEntity, $dataSet);
                    $savedFieldValue = $sdFieldValueTable->save($savedFieldValueEntity);
                    if(!$savedFieldValue){
                        echo "problem in saving".$product_info."sdfields";
                        debug($savedFieldValueEntity);
                        return null;
                    }
                    $sdSectionSetEntity = $sdSectionSetTable->newEntity();
                    $dataSet = [
                        'sd_field_value_id' => $savedFieldValue['id'],
                        'sd_section_id' => 21,
                        'set_array'=>1
                    ];
                    $savedSectionSetEntity = $sdSectionSetTable->patchEntity($sdSectionSetEntity, $dataSet);
                    if(!$sdSectionSetTable->save($savedSectionSetEntity)){
                        echo "problem in saving".$product_info."sets";
                        debug($savedSectionSetEntity);
                        return null;
                    }
                    if($fieldId == 176){
                        $sdSectionSetEntity = $sdSectionSetTable->newEntity();
                        $dataSet = [
                            'sd_field_value_id' => $savedFieldValue['id'],
                            'sd_section_id' => 65,
                            'set_array'=>1
                        ];
                        $savedSectionSetEntity = $sdSectionSetTable->patchEntity($sdSectionSetEntity, $dataSet);
                        if(!$sdSectionSetTable->save($savedSectionSetEntity)){
                            echo "problem in saving".$product_info." casulity";
                            debug($savedSectionSetEntity);
                            return null;
                        }
                        $sdSectionSetEntity = $sdSectionSetTable->newEntity();
                        $dataSet = [
                            'sd_field_value_id' => $savedFieldValue['id'],
                            'sd_section_id' => 47,
                            'set_array'=>1
                        ];
                        $savedSectionSetEntity = $sdSectionSetTable->patchEntity($sdSectionSetEntity, $dataSet);
                        if(!$sdSectionSetTable->save($savedSectionSetEntity)){
                            echo "problem in saving".$product_info." labeling";
                            debug($savedSectionSetEntity);
                            return null;
                        }
                    }
                }

                //save latest Recieved date
                //TODO logic according to versiiiion
                $sdFieldValueEntity = $sdFieldValueTable->newEntity();
                $dataSet = [
                    'sd_case_id' => $savedCase->id,
                    'sd_field_id' => '12',
                    'set_number' => '1',
                    'created_time' =>date("Y-m-d H:i:s"),
                    'field_value' =>date("dmY"),
                    'status' =>'1',
                ];
                $savedFieldValueEntity = $sdFieldValueTable->patchEntity($sdFieldValueEntity, $dataSet);
                if(!$sdFieldValueTable->save($savedFieldValueEntity)){
                    echo "problem in saving latest received date sdfields";
                    return null;
                }
                $sdFieldValueEntity = $sdFieldValueTable->newEntity();
                $dataSet = [
                    'sd_case_id' => $savedCase->id,
                    'sd_field_id' => '225',
                    'set_number' => '1',
                    'created_time' =>date("Y-m-d H:i:s"),
                    'field_value' =>date("dmY"),
                    'status' =>'1',
                ];
                $savedFieldValueEntity = $sdFieldValueTable->patchEntity($sdFieldValueEntity, $dataSet);
                if(!$sdFieldValueTable->save($savedFieldValueEntity)){
                    echo "problem in saving regulatory clock start date sdfields";
                    return null;
                }
                $sdFieldValueEntity = $sdFieldValueTable->newEntity();
                $dataSet = [
                    'sd_case_id' => $savedCase->id,
                    'sd_field_id' => '10',
                    'set_number' => '1',
                    'created_time' =>date("Y-m-d H:i:s"),
                    'field_value' =>date("dmY"),
                    'status' =>'1',
                ];
                $savedFieldValueEntity = $sdFieldValueTable->patchEntity($sdFieldValueEntity, $dataSet);
                if(!$sdFieldValueTable->save($savedFieldValueEntity)){
                    echo "problem in saving initial received date sdfields";
                    return null;
                }
                foreach($requestData['field_value'] as $field_id => $detail_data){
                    if($detail_data!=""){
                        $sdFieldValueEntity = $sdFieldValueTable->newEntity();
                        $dataSet = [
                            'sd_case_id' => $savedCase->id,
                            'sd_field_id' => $field_id,
                            'set_number' => '1',
                            'created_time' =>date("Y-m-d H:i:s"),
                            'field_value' =>$detail_data,
                            'status' =>'1',
                        ];
                        $savedFieldValueEntity = $sdFieldValueTable->patchEntity($sdFieldValueEntity, $dataSet);
                        // debug($sdFieldValueEntity);
                        if(!$sdFieldValueTable->save($savedFieldValueEntity)) {
                            echo "problem in saving".$field_id."sdfields";
                            return null;
                        }
                    }
                }
                //update caseHistory
                $caseHistoriesTable = TableRegistry::get('SdCaseHistories');
                $newCaseHistory = $caseHistoriesTable->newEntity();
                $dataSet =[
                    'sd_workflow_activity_id'=> $sdWorkflowActivities['id'],
                    'sd_user_id' => $userinfo['id'],
                    'sd_case_id' => $savedCase['id'],
                    'enter_time' => date("Y-m-d H:i:s"),
                ];
                $newCaseHistory = $caseHistoriesTable ->patchEntity($newCaseHistory, $dataSet);
                if(!$caseHistoriesTable->save($newCaseHistory)){
                    echo "problem in saving case history";
                    debug($newCaseHistory);
                    return null;
                }

            //$this->Flash->success(__('The case number is'.$date_str));
            return $this->redirect('/sd-cases/triage/'.$date_str);
        }
        $this->set(compact('productInfo','date_str'));
    }

     /**
     * Case number generator function
     *
     * @return string case number
     *
     */
    private function caseNoGenerator($order, $sdWorkflowActivitiesId, $sd_product_workflow_id, $product_prefix, $company_prefix=null){
        $count = $this->SdCases->find()->select(['startDate.enter_time'])
        ->join([
            'startDate'=>[
                'table'=>'sd_case_histories',
                'type'=>'INNER',
                'conditions'=>['SdCases.id = startDate.sd_case_id','startDate.sd_workflow_activity_id = '.$sdWorkflowActivitiesId]
            ]
        ])->where(['SdCases.sd_product_workflow_id'=>$sd_product_workflow_id, 'MONTH(startDate.enter_time)'=>date('m'), 'YEAR(startDate.enter_time)'=>date('Y')])->count();
        $count = $count.str_pad($count, 5, "0", STR_PAD_LEFT);
        do{
            $rand_str = rand(0, 99999);
            $rand_str = str_pad($rand_str, 5, "0", STR_PAD_LEFT);
            $date_str = "";
            foreach($order as $item){
                if($item == "product")
                    $date_str = $date_str.$product_prefix;
                else if($item == "date")
                    $date_str = $date_str.date("ym");
                else if($item == "company")
                    $date_str = $date_str.$company_prefix;
                else if($item == "random")
                    $date_str = $date_str.$rand_str;
                else $date_str = $date_str.$count;
            }
            // $date_str = "ICSR".date("ym").$rand_str;
        }while($this->SdCases->find()->where(['caseNo LIKE '=>'%'.$date_str.'%'])->first()!=null);
        debug($date_str);
        return (String)$date_str;
    }

    public function caselist(){
        $this->viewBuilder()->setLayout('main_layout');

    }
    /**
     * Version Up cases
     *
     *
     *
     */
    public function versionUp(){
        if($this->request->is('POST')){
            $this->autoRender = false;

            $requstData = $this->request->getData();
            $case = $this->SdCases->find()->where(['caseNo'=>$requstData['caseNo']])->order(['SdCases.version_no' => 'DESC'])->first()->toArray();
            $newCase = $this->SdCases->newEntity();
            $sdWorkflowActivity = TableRegistry::get('SdWorkflowActivities')
                                ->find()
                                ->select(['id', 'wf.id'])
                                ->join([
                                        'wf' =>[
                                            'table' =>'sd_workflows',
                                            'type'=>'LEFT',
                                            'conditions'=>['wf.id = SdWorkflowActivities.sd_workflow_id'],
                                        ],
                                    ])
                                ->where(['order_no = 1'])->first()->toArray();
            $case['sd_workflow_activity_id'] = $sdWorkflowActivity['id'];
            $case['version_no'] = (int)$case['version_no'] + 1;
            $case['sd_user_id'] = $requstData['userId'];
            $patchedCase = $this->SdCases->patchEntity($newCase, $case);
            $savedCase = $this->SdCases->save($patchedCase);
            if ($savedCase) echo "success"; else echo "error";
            $sdFieldValuesTable = TableRegistry::get('SdFieldValues');
            $sdFieldValues = $sdFieldValuesTable ->find()->where(['status'=>true,'sd_case_id'=>$case['id']]);
            foreach( $sdFieldValues as $sdFieldValueDetail){
                print_r($sdFieldValueDetail);
                $newFieldValue = $sdFieldValuesTable->newEntity();
                $newFieldValue['sd_case_id'] = $savedCase['id'];
                $newFieldValue['sd_field_id']=$sdFieldValueDetail['sd_field_id'];
                $newFieldValue['set_number']=$sdFieldValueDetail['set_number'];
                $newFieldValue['field_value']=$sdFieldValueDetail['field_value'];
                $newFieldValue['created_time']=date("Y-m-d H:i:s");
                $newFieldValue['status']="1";
                $patchedFieldValue = $sdFieldValuesTable->save($newFieldValue);
                if(!$patchedFieldValue) {print_r($patchedFieldValue);return;}
            }
            $caseHistoriesTable = TableRegistry::get('SdCaseHistories');
            $newCaseHistory = $caseHistoriesTable->newEntity();
            $dataSet =[
                'sd_workflow_activity_id'=> $sdWorkflowActivity['id'],
                'sd_user_id' => $requstData['userId'],
                'sd_case_id' => $savedCase['id'],
                'enter_time' => date("Y-m-d H:i:s"),
            ];
            $newCaseHistory = $caseHistoriesTable ->patchEntity($newCaseHistory, $dataSet);
            if(!$caseHistoriesTable->save($newCaseHistory)){
                echo "problem in saving case history";
                debug($newCaseHistory);
                return null;
            }
            die();
        }
    }
    public function distribute($caseNo, $version){
        if($this->request->is('POST')){
            $this->autoRender = false;
            $requstData = $this->request->getData();
            //save new activity into case
            $case = $this->SdCases->find()->where(['caseNo'=>$caseNo,'version_no'=>$version])
                                ->select(['id','SdCases.sd_product_workflow_id','pd.product_name','sd_workflow_activity_id','sd_user_id'])
                                ->join([
                                    'pw'=>[
                                        'table'=>'sd_product_workflows',
                                        'type'=>'INNER',
                                        'conditions'=>['pw.id = SdCases.sd_product_workflow_id']
                                    ],
                                    'pd'=>[
                                        'table'=>'sd_products',
                                        'type'=>'INNER',
                                        'conditions'=>['pw.sd_product_id = pd.id']
                                    ]
                                ])->first();
            //Save current user sign off history
            $caseCurrentHistoryTable =TableRegistry::get('SdCaseHistories');
            $caseCurrentHistory = $caseCurrentHistoryTable->find()
                                            ->where(['sd_case_id'=>$case['id'],'sd_workflow_activity_id'=>$case['sd_workflow_activity_id'],
                                            // 'sd_user_id'=>$case['sd_user_id'],
                                            'close_time IS NULL'])
                                            ->order(['enter_time'=>'DESC'])->first();
            $caseCurrentHistory['close_time'] = date("Y-m-d H:i:s");
            if(!$caseCurrentHistoryTable->save($caseCurrentHistory)){
                echo "error in saving history";
                return;
            };

            if($requstData['activityList'] == ""){
                //not to destribute, then close this case
                $case['status'] = 3;
                if(!$this->SdCases->save($case)){
                     echo "error in saving new activity";
                     return;
                }
            }
            else{
                $case['status'] = 2;
                if(!$this->SdCases->save($case)){
                     echo "error in saving new activity";
                     return;
                }
                
                //distribute to every activity selected
                $caseDistributionsTable = TableRegistry::get('SdCaseDistributions');
                $queryTable = TableRegistry::get('SdQueries');
                $caseNextHistory = TableRegistry::get('SdCaseHistories')->newEntity();
                $sdFieldValuesTable =TableRegistry::get('SdFieldValues');
                $sdWorkflowActivitiesTable = TableRegistry::get('SdWorkflowActivities');
                foreach ($requstData['activityList'] as $key => $activityData) {
                    $caseDistributionEntity = $caseDistributionsTable -> newEntity();
                    $caseDistributionEntity['sd_case_id'] = $case['id'];
                    $caseDistributionEntity['sd_workflow_activity_id'] = $activityData['next-activity-id'];
                    $caseDistributionEntity['sd_assessment_distribution_link_id'] = $activityData['linkId'];
                    $savedLink = $caseDistributionsTable->save($caseDistributionEntity);
                    if(!$savedLink){
                        echo "error in saving next history";
                        debug($caseDistributionEntity);
                        return;
                    };

                    //change Activity Due Date according case type and its due date
                    $sdWorkflowActivity = $sdWorkflowActivitiesTable->get($activityData['next-activity-id']);
                    $casetype = $sdFieldValuesTable->find()->where(['sd_field_id'=>500, 'sd_case_id'=>$case['id']])->first();
                    $latestReceiveDateEntity = $sdFieldValuesTable->find()->where(['sd_field_id'=>12, 'sd_case_id'=>$case['id']])->first();
                    $date = date_create_from_format("dmY", $latestReceiveDateEntity['field_value']);
                    $latestReceiveDateEntity['field_value'] = $date->format('dmY');
                    $previsouActivity = $sdWorkflowActivitiesTable->find()->where(['sd_workflow_id'=>$sdWorkflowActivity['sd_workflow_id'], 'order_no'=>1])->first();
                    date_add($date, date_interval_create_from_date_string(explode(',',$sdWorkflowActivity['due_day'])[$casetype['field_value']]+(int)$previsouActivity['due_day'][$casetype['field_value']].' days'));
                    $activityDueDateEntity = $sdFieldValuesTable->newEntity();            
                    $dataSet = [
                        'sd_case_id' => $case->id,
                        'sd_field_id' => 414,
                        'set_number' => 1,//TODO SET NUMBER REMOVE
                        'created_time' =>date("Y-m-d H:i:s"),
                        'field_value' =>$date->format('dmY'),
                        'status' =>'1',
                        'sd_case_distribution_id' =>$savedLink['id']
                    ];
                    $activityDueDateEntity = $sdFieldValuesTable->patchEntity($activityDueDateEntity, $dataSet);
                    if(!$sdFieldValuesTable->save($activityDueDateEntity)){
                        echo "error in saving date entity";
                        debug($activityDueDateEntity);
                        return;
                    };

                    //save next user enter history
                    $caseNextHistory['comment'] = $activityData['content'];
                    $caseNextHistory['sd_case_id'] = $case['id'];
                    $caseNextHistory['sd_workflow_activity_id'] = $activityData['next-activity-id'];
                    $caseNextHistory['sd_user_id'] = $activityData['receiverId'];
                    $caseNextHistory['enter_time'] = date("Y-m-d H:i:s");
                                   
                    if(!$caseCurrentHistoryTable->save($caseNextHistory)){
                        echo "error in saving next history";
                        return;
                    };


                    //Save Comment To next person
                    $title = "A new case has been distributed to you";     
                    $content = $activityData['content']."  Case Number:".$caseNo."    Version:".$version;
                    $sdQuery = $queryTable->newEntity();
                    $dataSet = [
                        'title'=>$title,
                        'content'=>$content,
                        'query_type'=>1,
                        'sender'=>$requstData['senderId'],
                        'receiver'=>$activityData['receiverId'],
                        'sender_deleted'=>0,
                        'query_status'=>0,
                        'receiver_status'=>1,
                        'send_date'=>date("Y-m-d H:i:s"),
                    ];
                    $patchedQuery = $queryTable->patchEntity($sdQuery, $dataSet);
                    if(!$queryTable->save($patchedQuery)){
                        debug($patchedQuery);
                        echo "error in saving query";
                        return;
                    }
                }
            }
            $this->request->getSession()->delete('caseValidate.'.$case['id']);
            echo "success";
            $this->Flash->success(__('You\'ve successfully Signed-Off.'));
        }
    }



    private function updateActivityHistory($caseId, $wfaId, $nextActivityId = null, $content = null, $receiverId = null){
        $caseCurrentHistoryTable =TableRegistry::get('SdCaseHistories');
        try{
            $caseCurrentHistory = $caseCurrentHistoryTable->find()
                ->where(['sd_case_id'=>$caseId,'sd_workflow_activity_id'=>$wfaId,
                // 'sd_user_id'=>$case['sd_user_id'],
                'close_time IS NULL'])
                ->order(['enter_time'=>'DESC'])->first();
            $caseCurrentHistory['comment'] = $content;
            $caseCurrentHistory['close_time'] = date("Y-m-d H:i:s");
            if(!$caseCurrentHistoryTable->save($caseCurrentHistory)){
                echo "error in saving history";
                return;
            };
            //save next user enter history
            if($nextActivityId != null){
                $caseNextHistory = TableRegistry::get('SdCaseHistories')->newEntity();
                $caseNextHistory['sd_case_id'] = $caseId;
                $caseNextHistory['sd_workflow_activity_id'] = $nextActivityId;
                $caseNextHistory['sd_user_id'] = $receiverId;
                $caseNextHistory['enter_time'] = date("Y-m-d H:i:s");
                if(!$caseCurrentHistoryTable->save($caseNextHistory)){
                    echo "error in saving next history";
                    return;
                };
            }
        }
        finally{
            return;
        }
    }


    /**
     *
     *
     *Push forward or push backward
     * $operator 1 backward 0 forward
     *
     */
    public function forward($caseNo, $version, $operator, $distribution_id = 'null'){
        if($this->request->is('POST')){
            $this->autoRender = false;
            $requstData = $this->request->getData();
            if($distribution_id == 'null') $distribution_condition = "SdFieldValues.sd_case_distribution_id IS NULL";
            else $distribution_condition = "SdFieldValues.sd_case_distribution_id ='".$distribution_id."'";
            //save new activity into case
            $case = $this->SdCases->find()->where(['caseNo'=>$caseNo,'version_no'=>$version])
                                ->select(['id','SdCases.sd_product_workflow_id','pd.product_name','sd_workflow_activity_id','sd_user_id'])
                                ->join([
                                    'pw'=>[
                                        'table'=>'sd_product_workflows',
                                        'type'=>'INNER',
                                        'conditions'=>['pw.id = SdCases.sd_product_workflow_id']
                                    ],
                                    'pd'=>[
                                        'table'=>'sd_products',
                                        'type'=>'INNER',
                                        'conditions'=>['pw.sd_product_id = pd.id']
                                    ]
                                ])->first();
            //Save current user sign off history
            $caseCurrentHistoryTable =TableRegistry::get('SdCaseHistories');
            if($distribution_id == 'null') $wfaId = $case['sd_workflow_activity_id'];
            else{
                $caseDistributionEntity =  TableRegistry::get('SdCaseDistributions')->get($distribution_id);
                $wfaId = $caseDistributionEntity['sd_workflow_activity_id'];
            }
            $caseCurrentHistory = $caseCurrentHistoryTable->find()
                                            ->where(['sd_case_id'=>$case['id'],'sd_workflow_activity_id'=>$wfaId,
                                            // 'sd_user_id'=>$case['sd_user_id'],
                                            'close_time IS NULL'])
                                            ->order(['enter_time'=>'DESC'])->first();
            $caseCurrentHistory['comment'] = $requstData['content'];
            $caseCurrentHistory['close_time'] = date("Y-m-d H:i:s");
            //save next user enter history
            $caseNextHistory = TableRegistry::get('SdCaseHistories')->newEntity();
            $caseNextHistory['sd_case_id'] = $case['id'];
            $caseNextHistory['sd_workflow_activity_id'] = $requstData['next-activity-id'];
            $caseNextHistory['sd_user_id'] = $requstData['receiverId'];
            $caseNextHistory['enter_time'] = date("Y-m-d H:i:s");
            //change Activity Due Date according case type and its due date
            $sdFieldValuesTable =TableRegistry::get('SdFieldValues');
            $sdWorkflowActivity = TableRegistry::get('SdWorkflowActivities')->get($requstData['next-activity-id']);
            $casetype = $sdFieldValuesTable->find()->where(['sd_field_id'=>500, 'sd_case_id'=>$case['id']])->first();
            $latestReceiveDateEntity = $sdFieldValuesTable->find()->where(['sd_field_id'=>12, 'sd_case_id'=>$case['id']])->first();
            $date = date_create_from_format("dmY", $latestReceiveDateEntity['field_value']);
            $latestReceiveDateEntity['field_value'] = $date->format('dmY');
            // filed 414 activity due_Date
            $activityDueDateEntity = $sdFieldValuesTable->find()->where(['sd_field_id'=>414, 'sd_case_id'=>$case['id'],$distribution_condition])->first();
            if($activityDueDateEntity == null){
                $previsouActivity = TableRegistry::get('SdWorkflowActivities')->find()->where(['sd_workflow_id'=>$sdWorkflowActivity['sd_workflow_id'], 'order_no'=>1])->first();
                date_add($date, date_interval_create_from_date_string(explode(',',$sdWorkflowActivity['due_day'])[$casetype['field_value']]+(int)$previsouActivity['due_day'][$casetype['field_value']].' days'));
                $activityDueDateEntity = $sdFieldValuesTable->newEntity();            
                $dataSet = [
                    'sd_case_id' => $case->id,
                    'sd_field_id' => 414,
                    'set_number' => 1,//TODO SET NUMBER REMOVE
                    'created_time' =>date("Y-m-d H:i:s"),
                    'field_value' =>$date->format('dmY'),
                    'status' =>'1',
                ];            
                if($distribution_id!=null){
                    $dataSet['sd_case_distribution_id'] = $distribution_id;
                    $dataSet['set_number'] = 2;
                } 
                $activityDueDateEntity = $sdFieldValuesTable->patchEntity($activityDueDateEntity, $dataSet);
            }else{
                date_add($date, date_interval_create_from_date_string(explode(',',$sdWorkflowActivity['due_day'])[$casetype['field_value']].' days'));
                $activityDueDateEntity['field_value'] = $date->format('dmY');
            }
            if(!$sdFieldValuesTable->save($activityDueDateEntity)){
                echo "error in saving date entity";
                debug($activityDueDateEntity);
                return;
            };
            if($distribution_id == 'null'){
                //update sd_Case with the next activity id
                $case['sd_user_id'] = $requstData['receiverId'];
                $case['sd_workflow_activity_id'] = $requstData['next-activity-id'];
                if(!$this->SdCases->save($case)){
                    echo "error in saving new activity";
                    return;
                }
            }else{
                $caseDistributionEntity['sd_use_id'] = $requstData['receiverId'];
                $caseDistributionEntity['sd_workflow_activity_id'] = $requstData['next-activity-id'];
                if(!TableRegistry::get('SdCaseDistributions')->save($caseDistributionEntity)){
                    echo "error in saving user";
                    return;
                }
            }
            if($operator == false)
                $title = "A new case has been pushed to you";
            else $title = "A case has been sent back to you";
            //Save Comment To next person
            if(!$caseCurrentHistoryTable->save($caseCurrentHistory)){
                echo "error in saving history";
                return;
            };
            if(!$caseCurrentHistoryTable->save($caseNextHistory)){
                echo "error in saving next history";
                return;
            };
            $queryTable = TableRegistry::get('SdQueries');
            $content = $requstData['content']."  Case Number:".$caseNo."    Version:".$version." dev.g2-mds.com/sd-tabs/showdetails/".$caseNo;
            $sdQuery = $queryTable->newEntity();
            $dataSet = [
                'title'=>$title,
                'content'=>$content,
                'query_type'=>1,
                'sender'=>$requstData['senderId'],
                'receiver'=>$requstData['receiverId'],
                'sender_deleted'=>0,
                'query_status'=>0,
                'receiver_status'=>1,
                'send_date'=>date("Y-m-d H:i:s"),
            ];
            $patchedQuery = $queryTable->patchEntity($sdQuery, $dataSet);
            if(!$queryTable->save($patchedQuery)){
                 echo "error in saving query";
                 return;
            }
            $this->request->getSession()->delete('caseValidate.'.$case['id']);
            echo "success";
            $this->Flash->success(__('You\'ve successfully Signed-Off.'));
        }
    }

    /**
    * Close cases
    *
    *
    *
    */
   public function closeCase(){
       if($this->request->is('POST')){
           $this->autoRender = false;
           $requstData = $this->request->getData();
           $case = $this->SdCases->find()->where(['caseNo'=>$requstData['caseNo'],'version_no'=>$requstData['version_no']])->first();
           $this->updateActivityHistory($case['id'], $case['sd_workflow_activity_id']);
           $case['sd_workflow_activity_id'] = 9999;
           $case['status'] = 3;
           //TODO add into acitvity history
           if ($this->SdCases->save($case)) echo "success"; else echo "error";
           print_r($case);
           die();
       }
   }

   public function deleteTriageEvent($caseNo, $versionNo=1){
    if ($this->request->is(['patch', 'post', 'put'])) {
        $this->autoRender = false;
        $case = $this->SdCases->find()->where(['caseNo'=>$caseNo,'version_no'=>$versionNo])->first();
        $requestData = $this->request->getData();
        $sdFieldValueTable = TableRegistry::get('SdFieldValues');
        $sdSectionSets = TableRegistry::get('SdSectionSets');
        foreach($requestData as $setNo => $setDetail){
            foreach($setDetail as $field_id => $detail_data){
                if($detail_data['id']!=null) {
                        $previous_field_value = $sdFieldValueTable->get($detail_data['id']);
                        $previous_field_value['status'] = 0;
                    $savedFieldValue = $sdFieldValueTable->save($previous_field_value);
                    if(!$savedFieldValue) {
                        echo "problem in saving".$field_id."sdfields";
                        debug($savedFieldValueEntity);
                        return null;
                    }            
                }
                $largerset_field_values = $sdFieldValueTable->find()->where(['sd_case_id'=>$case['id'],'sd_field_id'=>$field_id,'status'=>'1', 'set_number >'=>(int)$setNo]);
                // debug($largerset_field_values->toArray());
                foreach($largerset_field_values as $key => $field_value ){
                    $field_value['set_number'] = (int)$field_value['set_number'] - 1;
                    $savedFieldValue = null;
                    $savedFieldValue = $sdFieldValueTable->save($field_value);
                    if(!$savedFieldValue) {
                        echo "problem in saving".$field_id."sdfields";
                        debug($savedFieldValueEntity);
                        return null;
                    }
                }  
            }
        }
        $event_set = [];    
        $event_ids = ['149','496','392','457','394','458'];
        foreach($event_ids as $field_id){
            try{
            $event_field_values = $sdFieldValueTable->find()->where(['sd_case_id'=>$case['id'],'sd_field_id'=>$field_id,'status'=>'1']);
            foreach($event_field_values as $event_field){
                $event_set[$event_field['set_number']][$field_id]['field_value'] = $event_field['field_value'];
                $event_set[$event_field['set_number']][$field_id]['id'] = $event_field['id'];
            }
            }catch (\PDOException $e){
                $event_set = null;
            }
        } 
        echo json_encode($event_set);
        die();
   }
}
    /**
     * Create cases
     * Save triage info and event info
     *
     *
     */
    public function triage($caseNo, $versionNo=1){
        // Prior to 3.5 use I18n::locale()
        if ($this->request->is(['patch', 'post', 'put'])) {
            $case = $this->SdCases->find()->where(['caseNo'=>$caseNo,'version_no'=>$versionNo])->first();
            $requestData = $this->request->getData();
            $sdFieldValueTable = TableRegistry::get('SdFieldValues');
            $sdSectionSets = TableRegistry::get('SdSectionSets');
            foreach($requestData['event'] as $setNo => $event_detail){
                foreach($event_detail as $field_id => $detail_data){
                    if($detail_data['id']!="") {
                        $previous_field_value = $sdFieldValueTable->get($detail_data['id']);
                        if($detail_data['value']==$previous_field_value['field_value']) continue;
                        $savedFieldValueEntity = $previous_field_value;
                        $savedFieldValueEntity['field_value'] = $detail_data['value'];
                    }
                    else {
                        if($detail_data['value']=="") continue;
                        $sdFieldValueEntity = $sdFieldValueTable->newEntity();
                        $dataSet = [
                            'sd_case_id' => $case->id,
                            'sd_field_id' => $field_id,
                            'set_number' => $setNo,
                            'created_time' =>date("Y-m-d H:i:s"),
                            'field_value' =>$detail_data['value'],
                            'status' =>'1',
                        ];
                        $savedFieldValueEntity = $sdFieldValueTable->patchEntity($sdFieldValueEntity, $dataSet);
                    }
                    $savedFieldValue = $sdFieldValueTable->save($savedFieldValueEntity);
                    if(!$savedFieldValue) {
                        echo "problem in saving".$field_id."sdfields";
                        debug($savedFieldValueEntity);
                        return null;
                    }
                }
            }
            if(array_key_exists('field_value', $requestData)){
                foreach($requestData['field_value'] as $field_id => $detail_data){
                    if($detail_data['id']!=null) {
                        $previous_field_value = $sdFieldValueTable->get($detail_data['id']);
                        if($detail_data['value']==$previous_field_value['field_value']) continue;
                        $savedFieldValueEntity = $previous_field_value;
                        $savedFieldValueEntity['field_value'] = $detail_data['value'];
                    }
                    else {
                        if($detail_data['value']==null) continue;
                        $sdFieldValueEntity = $sdFieldValueTable->newEntity();
                        $dataSet = [
                            'sd_case_id' => $case->id,
                            'sd_field_id' => $field_id,
                            'set_number' => '1',
                            'created_time' =>date("Y-m-d H:i:s"),
                            'field_value' =>$detail_data['value'],
                            'status' =>'1',
                        ];
                        $savedFieldValueEntity = $sdFieldValueTable->patchEntity($sdFieldValueEntity, $dataSet);
                    }
                    
                    $savedFieldValue = $sdFieldValueTable->save($savedFieldValueEntity);
                    if(!$savedFieldValue) {
                        echo "problem in saving".$field_id."sdfields";
                        debug($savedFieldValueEntity);
                        return null;
                    }
                }
            }
            if(array_key_exists('document', $requestData)){
                if (!$this->is_empty($requestData['document']))
                {
                    if (!$this->saveDocuments($requestData['document'], $case->id))
                    {
                        $this->Flash->error(__('Problem in saving documents.'));
                    }
                    else
                    {
                        $this->Flash->success(__("Documents have been uploaded successfully."));
                    }
                }
            }
        }
        $case = $this->SdCases->find()->where(['caseNo'=>$caseNo,'version_no'=>$versionNo])
            ->select(['pwf.id','pd.id','SdCases.id'])
            ->join([
                'pwf'=>[
                    'table'=>'sd_product_workflows',
                    'type'=>'LEFT',
                    'conditions'=>['pwf.id = SdCases.sd_product_workflow_id']
                ],
                'pd'=>[
                    'table'=>'sd_products',
                    'type'=>'LEFT',
                    'conditions'=>['pd.id = pwf.sd_product_id']
                ]
            ])->first();
        $event_ids = ['149','496','392','457','394','458'];
        $fieldValue_Table = TableRegistry::get('SdFieldValues');
        $event_set = [];
        foreach($event_ids as $field_id){
            try{
            $event_field_values = $fieldValue_Table->find()->where(['sd_case_id'=>$case['id'],'sd_field_id'=>$field_id,'status'=>'1']);
            foreach($event_field_values as $event_field){
                $event_set[$event_field['set_number']][$field_id]['field_value'] = $event_field['field_value'];
                $event_set[$event_field['set_number']][$field_id]['id'] = $event_field['id'];
            }
            }catch (\PDOException $e){
                $event_set = null;
            }
        }
        $field_ids = ['10','176','85','79','93','86','87','12','26','28','395','417','420','421','422','423','223','415','500', '225'];
        $versionup_fields = [''];
        
        
        $field_value_set = [];
        foreach($field_ids as $field_id){
            try{
            $field_value = $fieldValue_Table->find()->where(['sd_case_id'=>$case['id'],'sd_field_id'=>$field_id,'set_number'=>'1','status'=>'1'])->first();
            $field_value_set[$field_id]['field_value'] = $field_value['field_value'];
            $field_value_set[$field_id]['id'] = $field_value['id'];
            }catch (\PDOException $e){
                $field_value_set[$field_id] = null;
            }
        }
        if($versionNo>1) 
        {
            $version_up_set =[''];
            foreach($versionup_fields as $field_id){
                try{
                $field_value = $fieldValue_Table->find()->where(['sd_case_id'=>$case['id'],'sd_field_id'=>$field_id,'set_number'=>'1','status'=>'1'])->first();
                $version_up_set[$field_id]['field_value'] = $field_value['field_value'];
                $version_up_set[$field_id]['id'] = $field_value['id'];
                }catch (\PDOException $e){
                    $version_up_set[$field_id] = null;
                }
            }
            $this->set('version_up_set',$version_up_set);
        }
        if($this->request->is(['patch', 'post', 'put'])&&array_key_exists('endTriage',$requestData))
        {
            $response = [];
            $response['event_set'] = $event_set;
            $response['field_value_set'] = $field_value_set;
            echo json_encode($response);
            die();
        }else if($this->request->is(['patch', 'post', 'put'])&&array_key_exists('saveEvent',$requestData)){
            echo json_encode($event_set);
            die();
        }else $this->Flash->success(__('This page has been saved.'));
        $this->viewBuilder()->setLayout('main_layout');
        // Load document list if there is any. 
        // Chloe Wang @ Mar 31, 2019
        $this->loadModel("SdDocuments");
        $docList = $this->SdDocuments->find()->where(['sd_case_id'=>$case['id']]);
        $sdDocList = $docList->toArray();
        $this->set(compact('sdDocList'));
        // end of document list
        $this->set(compact('case','caseNo','versionNo','field_value_set','event_set'));
    }

    /**
     * 
     * Deactivate Case While triage
     */
    public function deactivate($caseNo, $versionNo=1){
        $case = $this->SdCases->find()->where(['caseNo'=>$caseNo,'version_no'=>$versionNo])->first();
        $case['status'] = 0;
        if (!$this->SdCases->save($case)) echo "problem in saving cases";
        // print_r($this->request->getData());
        if ($this->request->is(['patch', 'post', 'put'])) {
            $this->autoRender = false;
            $requestData = $this->request->getData();
            $sdFieldValueTable = TableRegistry::get('SdFieldValues');
            foreach($requestData['field_value'] as $field_id => $detail_data){
                if((array_key_exists('id',$detail_data))&&($detail_data['id']!=null)) {
                    $previous_field_value = $sdFieldValueTable->get($detail_data['id']);
                    if($detail_data['value']==$previous_field_value['field_value']) continue;
                    $savedFieldValueEntity = $previous_field_value;
                    $savedFieldValueEntity['field_value'] = $detail_data['value'];
                }
                else {
                    if($detail_data['value']==null) continue;
                    $sdFieldValueEntity = $sdFieldValueTable->newEntity();
                    $dataSet = [
                        'sd_case_id' => $case->id,
                        'sd_field_id' => $field_id,
                        'set_number' => '1',
                        'created_time' =>date("Y-m-d H:i:s"),
                        'field_value' =>$detail_data['value'],
                        'status' =>'1',
                    ];
                    $savedFieldValueEntity = $sdFieldValueTable->patchEntity($sdFieldValueEntity, $dataSet);
                }
                if(!$sdFieldValueTable->save($savedFieldValueEntity)) {
                    echo "problem in saving".$field_id."sdfields";
                    return null;
                }
            }
            
            if (!$this->saveDocuments($requestData['document'], $case->id))
            {
                echo "problem in saving document!";
                return null;
            }
            echo "deactivated success";
        }
    }

    public function getDocumentParams($data_arr=array())
    {
        $document_data = array();
        foreach ($data_arr as $key => $value)
        {
            if ($key == 'field_value')
                continue; 
            preg_match("/^(doc_\S+)_(\d+)$/", $key, $matches);

            $field = $matches[1];
            $index = $matches[2];
            if ((!in_array($index, $document_data))||(!in_array($field,$document_data[$index]))||(!in_array($value, $document_data[$index][$field])))
            {
                $document_data[$index][$field] = $value;
            }
                
        }
        //debug($document_data);
        return $document_data;

    }

    public function is_empty($document_array)
    {
        foreach ($document_array as $doc_details)
        {
            if ($doc_details['doc_source'] == 'File Attachment' && $doc_details['doc_attachment']['tmp_name'] != ''
            || $doc_details['doc_source'] == 'URL Reference' && $doc_details['doc_path'] != '')
            { 
                return false;
            }
        }
        return true;
    }
    public function saveDocuments($requested_data,$case_id)
    {
        $userinfo = $this->request->getSession()->read('Auth.User');
        $document_array = $requested_data;
        //debug($document_array); die();
        $this->loadModel('SdDocuments');
        $file_saved = false;
        foreach ($document_array as $document_details)
        {
            //debug($document_details);
            if (isset($document_details['doc_description']) && $document_details['doc_description'] != '')
            {
                $file_uploaded = false;
                if ($document_details['doc_source'] == 'File Attachment')
                {                       
                    if(!empty($document_details['doc_attachment']['name'])){
                        $fileName = $document_details['doc_attachment']['name'];
                        $fileType = $document_details['doc_attachment']['type'];
                        $fileSize = $document_details['doc_attachment']['size'];
                        $rootPath = 'webroot/';
                        $uploadPath = $rootPath.'uploads/files/';
                        //save files into webroot
                        $uploadRealPath = $uploadPath.$case_id;
                        //print $uploadRealPath; die();
                        if (!file_exists($uploadRealPath))
                        {
                            if (!mkdir($uploadRealPath, 0755, true))
                            {

                                $this->Flash->error(__('Unable to create directory, please try again.'));
                                return false;
                            }
                        }
                        
                        $uploadFile = $uploadRealPath."/".$fileName;

                        if (file_exists($uploadFile))
                        {
                            $uploadFile = $uploadRealPath."/".time().$fileName;
                        }
                        if(move_uploaded_file($document_details['doc_attachment']['tmp_name'], $uploadFile))
                        {
                            $urlBase = Router::url('/', true);
                            $url = $urlBase.str_replace("webroot/","",$uploadFile);;
                            $file_uploaded = true;
                        }
                    }
                }
                elseif ($document_details['doc_source'] == 'URL Reference')
                {

                    $file_uploaded = true;
                }

                if ($file_uploaded)
                {
                    $newDocumentEntity = $this->SdDocuments->newEntity();
                    $newDocumentEntity->sd_case_id =  $case_id;
                    $newDocumentEntity->doc_classification = $document_details['doc_classification'];
                    $newDocumentEntity->doc_description = $document_details['doc_description'];
                    $newDocumentEntity->doc_source = $document_details['doc_source'];
                    if ($document_details['doc_source'] == 'URL Reference')
                    {
                        $newDocumentEntity->doc_path = $document_details['doc_path'];
                    }
                    elseif ($document_details['doc_source'] == 'File Attachment')
                    {
                        $newDocumentEntity->doc_name = $fileName;
                        $newDocumentEntity->doc_path = $url;
                        $newDocumentEntity->doc_type = $fileType;
                        $newDocumentEntity->doc_size = $fileSize;
                    }
                    $newDocumentEntity->is_deleted = 0;
                    $newDocumentEntity->created_dt = date("Y-m-d H:i:s");
                    $newDocumentEntity->updated_dt = date("Y-m-d H:i:s");
                    $newDocumentEntity->created_by = $userinfo['id'];
                    if ($this->SdDocuments->save($newDocumentEntity))
                    {
                        $file_saved = true;
                    }
                    else
                    {
                        break;
                    }
                }
            }
            
        }
        if ($file_saved)
            return true;
        else
            return false;
    }
}
