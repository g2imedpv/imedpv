<?php
namespace App\Controller;
use Cake\ORM\TableRegistry;
use App\Controller\AppController;

/**
 * SdSections Controller
 *
 * @property \App\Model\Table\SdSectionsTable $SdSections
 *
 * @method \App\Model\Entity\SdSection[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SdSectionsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['SdTabs']
        ];
        $sdSections = $this->paginate($this->SdSections);

        $this->set(compact('sdSections'));
    }

    /**
     * View method
     *
     * @param string|null $id Sd Section id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $sdSection = $this->SdSections->get($id, [
            'contain' => ['SdTabs', 'SdSectionStructures']
        ]);

        $this->set('sdSection', $sdSection);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $sdSection = $this->SdSections->newEntity();
        if ($this->request->is('post')) {
            $sdSection = $this->SdSections->patchEntity($sdSection, $this->request->getData());
            if ($this->SdSections->save($sdSection)) {
                $this->Flash->success(__('The sd section has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sd section could not be saved. Please, try again.'));
        }
        $sdTabs = $this->SdSections->SdTabs->find('list', ['limit' => 200]);
        $this->set(compact('sdSection', 'sdTabs'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Sd Section id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sdSection = $this->SdSections->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $sdSection = $this->SdSections->patchEntity($sdSection, $this->request->getData());
            if ($this->SdSections->save($sdSection)) {
                $this->Flash->success(__('The sd section has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sd section could not be saved. Please, try again.'));
        }
        $sdTabs = $this->SdSections->SdTabs->find('list', ['limit' => 200]);
        $this->set(compact('sdSection', 'sdTabs'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Sd Section id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sdSection = $this->SdSections->get($id);
        if ($this->SdSections->delete($sdSection)) {
            $this->Flash->success(__('The sd section has been deleted.'));
        } else {
            $this->Flash->error(__('The sd section could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    /*
    save Single section
    */
    public function saveSection($tabid, $caseId, $distribution_id = "null"){  
        if($distribution_id == "null")
         $distribution_condition = "SdFieldValues.sd_case_distribution_id IS NULL";
        else $distribution_condition = "SdFieldValues.sd_case_distribution_id ='".$distribution_id."'";                  
        $writePermission= 0;
        $userinfo = $this->request->getSession()->read('Auth.User');
        $sdCasesTable = TableRegistry::get('SdCases');
        if ($this->request->is(['patch', 'post', 'put'])) {
            $error =[];
            $requstData = $this->request->getData()['sd_field_values'];
            $SdSectionStructuresTable = TableRegistry::get('SdSectionStructures');
            $sdFieldValueTable = TableRegistry::get('SdFieldValues');
            if(array_key_exists('sectionArray',$this->request->getData()))
                $requestSectionArray = $this->request->getData()['sectionArray'];
            else $requestSectionArray =[];
            foreach($requstData as $sectionValueK => $sectionValue) {
                $section_id = $sectionValueK;
                $set_number = "1";
                if(!empty($requestSectionArray)){
                    $set_number = "";
                    $sectionArray = explode(',',$requestSectionArray[$section_id]);
                    for($i = 0;$i<sizeof($sectionArray);$i++){
                        $set_number = $set_number.explode(':',$sectionArray[$i])[1].","; 
                    }
                    $set_number = substr($set_number, 0, -1);
                }
                foreach($sectionValue as $sectionFieldK =>$sectionFieldValue){
                    if($sectionFieldValue['id']!=''){
                        $sdFieldValueEntity = $sdFieldValueTable->get($sectionFieldValue['id']);/**add last-updated time */
                        $sdFieldValueTable->patchEntity($sdFieldValueEntity,$sectionFieldValue);
                        if(!$sdFieldValueTable->save($sdFieldValueEntity)) {
                            echo "error in updating!" ;
                            debug($sdFieldValueEntity);
                        }
                    }elseif(!empty($sectionFieldValue['field_value'])){
                        $sdFieldValueEntity = $sdFieldValueTable->newEntity();
                        if(key_exists('set_array',$sectionFieldValue))
                            $set_array = $sectionFieldValue['set_array'];
                        unset($sectionFieldValue['set_array']);
                        $dataSet = [
                            'sd_case_id' => $caseId,
                            'sd_field_id' => $sectionFieldValue['sd_field_id'],
                            'set_number' => $set_number,//TODO SET NUMBER REMOVE
                            'created_time' =>date("Y-m-d H:i:s"),
                            'field_value' =>$sectionFieldValue['field_value'],
                            'status' =>'1',
                        ];
                        if($distribution_id!=null) $dataSet['sd_case_distribution_id'] = $distribution_id;
                        $sdFieldValueEntity = $sdFieldValueTable->patchEntity($sdFieldValueEntity, $dataSet);
                        $savedFieldValue = $sdFieldValueTable->save($sdFieldValueEntity);
                        if(!$savedFieldValue){
                            echo "error in adding values!" ;
                            debug($savedFieldValue);
                        } 
                        // $sdSectionSetsEntity = $sdSectionSetsTable->newEntity(); 

                    }
                }
            };
        }
        $sdFieldTable = TableRegistry::get('SdFields');
        $sdTab = TableRegistry::get('SdSections');
        $sdSections = $sdTab ->find()->where(['sd_tab_id'=>$tabid,'status'=>true])
                            ->order(['SdSections.section_level'=>'ASC','SdSections.display_order'=>'ASC'])
                            ->contain(['SdSectionSummaries','SdSectionStructures'=>function($q)use($caseId,$distribution_condition){
                                return $q->order(['SdSectionStructures.row_no'=>'ASC','SdSectionStructures.field_start_at'=>'ASC'])
                                    ->contain(['SdFields'=>['SdFieldValueLookUps','SdFieldValues'=> function ($q)use($caseId,$distribution_condition) {
                                        return $q->where(['SdFieldValues.sd_case_id'=>$caseId, $distribution_condition,
                                                            'SdFieldValues.status'=>true]);
                                    }, 'SdElementTypes'=> function($q){
                                    return $q->select('type_name')->where(['SdElementTypes.status'=>true]);
                                        }]]);
                            }])->toArray();
        $child_list = [];
        
        foreach($sdSections as $sdSection){
            $sdSection['section_name'] = __($sdSection['section_name']);
            if($sdSection->parent_section!=0){
                if(empty($child_list[$sdSection->parent_section])) $child_list[$sdSection->parent_section]="";
                $child_list[$sdSection->parent_section] = $child_list[$sdSection->parent_section].$sdSection->id.",";
            }
            foreach($sdSection['sd_section_structures'] as $sd_section_structure){
                $sd_section_structure['sd_field']['field_label'] = __($sd_section_structure['sd_field']['field_label']);
                $sd_section_structure['sd_field']['comment'] = __($sd_section_structure['sd_field']['comment']);
                foreach($sd_section_structure['sd_field']['sd_field_value_look_ups'] as $sd_field_value_look_up){
                    $sd_field_value_look_up['caption'] = __($sd_field_value_look_up['caption']);
                }
            }
        }
        foreach($sdSections as $sdSection){
            if(!empty($child_list[$sdSection->id]))  $sdSection->child_section = substr($child_list[$sdSection->id], 0, -1);
        }
        foreach($sdSections as $sdSection){
            if(empty($sdSection->sd_section_summary)) continue;
            $fields = explode(',',$sdSection->sd_section_summary->fields);
            $sdFields = [];
            foreach($fields as $sdField){
                $foundField = $sdFieldTable->find()->where(['SdFields.id'=>$sdField])
                    ->contain(['SdFieldValueLookUps','SdFieldValues'=> function ($q)use($caseId,$distribution_condition) {
                                return $q->where(['SdFieldValues.sd_case_id'=>$caseId, $distribution_condition,
                                        'SdFieldValues.status'=>true]);
                }, 'SdElementTypes'=> function($q){
                return $q->select('type_name')->where(['SdElementTypes.status'=>true]);
                    }])->first(); 
                $foundField['field_label'] = __($foundField['field_label']);
                foreach($foundField['sd_field_value_look_ups'] as $sd_field_value_look_ups){
                    $sd_field_value_look_ups['caption'] = __($sd_field_value_look_ups['caption']);
                }
                array_push($sdFields,$foundField);
            }
            $sdSection->sd_section_summary['sdFields'] = $sdFields;
        }
        echo json_encode($sdSections);
        die();
    }    
    /*
    delete Single section
    */
    public function deleteSection($tabid, $caseId, $sectionId, $setNo, $distribution_id = "null"){
        $userinfo = $this->request->getSession()->read('Auth.User');
        $sections = explode(',',$this->request->getData()['child_section']);
        array_push($sections, $sectionId);
        if($distribution_id == "null") $distribution_condition = "SdFieldValues.sd_case_distribution_id IS NULL";
        else $distribution_condition = "SdFieldValues.sd_case_distribution_id ='".$distribution_id."'";     
        if($this->request->is('POST')){
            $this->autoRender = false;
            $sdFieldValuesTable = TableRegistry::get('SdFieldValues');
            $sdSectionSetsTable = TableRegistry::get('SdSectionSets');
            foreach($sections as $changeSectionId){
                $field_values = $sdFieldValuesTable->find()->join([
                    'structure'=>[
                        'table'=>'sd_section_structures',
                        'type'=>'INNER',
                        'conditions'=>['structure.sd_field_id = SdFieldValues.sd_field_id']
                    ],
                ])->select(['created_time','field_value','sd_field_id','set_number','sd_case_id','id','status','structure.sd_section_id'])->where(['SdFieldValues.sd_case_id'=>$caseId,$distribution_condition,'structure.sd_section_id '=>$changeSectionId, 'status'=>1]);
                // debug($field_values->toArray());
                foreach($field_values as $field_value){
                    if($sectionId =='44'&&$field_value['sd_field_id'] == '149') continue;
                    if(explode(',',$field_value['set_number'])[0] < $setNo) continue;
                    else
                    { 
                        $field_valueEntity = $sdFieldValuesTable->get($field_value->toArray()['id']);
                        if(explode(',',$field_value['set_number'])[0] == $setNo){
                            $field_valueEntity['status'] = 0;
                        }else{
                            $newNum = (string)(intval(explode(',',$field_value['set_number'])[0]) - 1);
                            foreach(explode(',',$field_value['set_number']) as $key => $set_no){
                                if($key == 0) {$field_value['set_number'] = $newNum; continue;}
                                $field_value['set_number'] = $field_value['set_number'].",".$set_no;
                            }
                            $field_valueEntity['set_number'] = $field_value['set_number'];
                        }
                        
                        if(!$sdFieldValuesTable->save($field_valueEntity)) {
                            echo "error in updating!" ;
                            debug($field_valueEntity);
                        }
                    }
                }
            }
            $sdCasesTable = TableRegistry::get('SdCases');
            $sdCases = $sdCasesTable->find()->where(['SdCases.id'=>$caseId])->contain(['SdProductWorkflows.SdProducts'])->first();
            $currentActivityId = $sdCases['sd_workflow_activity_id'];

            //User not allow to this activity
            if($userinfo['sd_role_id']>2){
                $assignments = TableRegistry::get('SdUserAssignments')
                        ->find()->select(['sd_workflow_activity_id'])
                        ->where(['sd_user_id'=>$userinfo['id'],'sd_product_workflow_id'=>$sdCases['sd_product_workflow_id']])->toArray();
                $activitySectionPermissions = TableRegistry::get('SdActivitySectionPermissions')
                        ->find('list',[
                            'keyField' => 'sd_section_id',
                            'valueField' => 'action'
                        ])
                        ->join([
                            'sections' =>[
                                'table' =>'sd_sections',
                                'type'=>'INNER',
                                'conditions'=>['sections.id = SdActivitySectionPermissions.sd_section_id','sections.sd_tab_id = '.$tabid],
                            ],
                            'ua'=>[
                                'table'=>'sd_user_assignments',
                                'type'=>'INNER',
                                'conditions'=>['ua.sd_product_workflow_id ='.$sdCases['sd_product_workflow_id'],
                                            'ua.sd_user_id ='.$userinfo['id'],'ua.sd_workflow_activity_id = SdActivitySectionPermissions.sd_workflow_activity_id']
                            ]
                        ])->toArray();
                if($sdCases['sd_user_id'] != $userinfo['id']){
                    $writePermission = 0;
                }else{
                    $writePermission = 1;
                }
                if(!$writePermission){
                    foreach($activitySectionPermissions as $key => $activitySectionPermission){
                        $activitySectionPermissions[$key] = 2;
                    }
                }
            }else {
                $activitySectionPermissions = null;
                $writePermission =1;
            }

            //Fetch tab structures
            //TODO according to $sdFieldTable = TableRegistry::get('SdFields');
            $sdFieldTable = TableRegistry::get('SdFields');
            $sdTab = TableRegistry::get('SdSections');
            $sdSections = $sdTab ->find()->where(['sd_tab_id'=>$tabid,'status'=>true])
                                ->order(['SdSections.section_level'=>'ASC','SdSections.display_order'=>'ASC'])
                                ->contain(['SdSectionSummaries','SdSectionStructures'=>function($q)use($caseId,$distribution_condition){
                                    return $q->order(['SdSectionStructures.row_no'=>'ASC','SdSectionStructures.field_start_at'=>'ASC'])
                                        ->contain(['SdFields'=>['SdFieldValueLookUps','SdFieldValues'=> function ($q)use($caseId,$distribution_condition) {
                                            return $q->where(['SdFieldValues.sd_case_id'=>$caseId, $distribution_condition,
                                                                'SdFieldValues.status'=>true]);
                                        }, 'SdElementTypes'=> function($q){
                                        return $q->select('type_name')->where(['SdElementTypes.status'=>true]);
                                            }]]);
                                }])->toArray();
            $child_list = [];
            
            foreach($sdSections as $sdSection){
                $sdSection['section_name'] = __($sdSection['section_name']);
                if($sdSection->parent_section!=0){
                    if(empty($child_list[$sdSection->parent_section])) $child_list[$sdSection->parent_section]="";
                    $child_list[$sdSection->parent_section] = $child_list[$sdSection->parent_section].$sdSection->id.",";
                }
                foreach($sdSection['sd_section_structures'] as $sd_section_structure){
                    $sd_section_structure['sd_field']['field_label'] = __($sd_section_structure['sd_field']['field_label']);
                    $sd_section_structure['sd_field']['comment'] = __($sd_section_structure['sd_field']['comment']);
                    foreach($sd_section_structure['sd_field']['sd_field_value_look_ups'] as $sd_field_value_look_up){
                        $sd_field_value_look_up['caption'] = __($sd_field_value_look_up['caption']);
                    }
                }
            }
            foreach($sdSections as $sdSection){
                if(!empty($child_list[$sdSection->id]))  $sdSection->child_section = substr($child_list[$sdSection->id], 0, -1);
            }
            foreach($sdSections as $sdSection){
                if(empty($sdSection->sd_section_summary)) continue;
                $fields = explode(',',$sdSection->sd_section_summary->fields);
                $sdFields = [];
                foreach($fields as $sdField){
                    $foundField = $sdFieldTable->find()->where(['SdFields.id'=>$sdField])
                        ->contain(['SdFieldValueLookUps','SdFieldValues'=> function ($q)use($caseId,$distribution_condition) {
                                    return $q->where(['SdFieldValues.sd_case_id'=>$caseId, $distribution_condition,
                                            'SdFieldValues.status'=>true]);
                    }, 'SdElementTypes'=> function($q){
                    return $q->select('type_name')->where(['SdElementTypes.status'=>true]);
                        }])->first(); 
                    $foundField['field_label'] = __($foundField['field_label']);
                    foreach($foundField['sd_field_value_look_ups'] as $sd_field_value_look_ups){
                        $sd_field_value_look_ups['caption'] = __($sd_field_value_look_ups['caption']);
                    }
                    array_push($sdFields,$foundField);
                }
                $sdSection->sd_section_summary['sdFields'] = $sdFields;
            }
        }else $this->autoRender = true;
        echo json_encode($sdSections);
        die();
    }
    /**
     * 
     * Search Structure
     * 
     * 
     */
    public function search(){
        if($this->request->is('POST')){
            $this->autoRender = false;
            $requstData = $this->request->getData();
            $case = TableRegistry::get('SdCases')->get($requstData['caseId']);
            $sections = $this->SdSections->find()
            ->select(['tab.id','tab.tab_name','section_name','id','section_level','field.id','field.field_label','asp.sd_workflow_activity_id'])
            ->join([
                'tab'=>[
                    'table'=>'sd_tabs',
                    'type'=>'INNER',
                    'conditions'=>['SdSections.sd_tab_id = tab.id']
                ],
                'ss'=>[
                    'table'=>'sd_section_structures',
                    'type'=>'INNER',
                    'conditions'=>['ss.sd_section_id = SdSections.id']
                ],
                'field'=>[
                    'table'=>'sd_fields',
                    'type'=>'INNER',
                    'conditions'=>['field.id = ss.sd_field_id']
                ],
                'asp'=>[
                    'table'=>'sd_activity_section_permissions',
                    'type'=>'INNER',
                    'conditions'=>['asp.sd_section_id = SdSections.id']
                ],
                'ua'=>[
                    'table'=>'sd_user_assignments',
                    'type'=>'INNER',
                    'conditions'=>['ua.sd_workflow_activity_id = asp.sd_workflow_activity_id','ua.sd_product_workflow_id ='.$case['sd_product_workflow_id'],'ua.sd_user_id ='.$requstData['userId']]
                ]
            ])->where([
                'OR' =>[['field.field_label LIKE \'%'.$requstData['key'].'%\''],['section_name LIKE \'%'.$requstData['key'].'%\''],
                        ['tab.tab_name LIKE \'%'.$requstData['key'].'%\'']],
            ]);
            echo json_encode($sections);
            die();
        }
    }
}
