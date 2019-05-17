<?php

        namespace App\Controller;
        use Cake\ORM\TableRegistry;

        use App\Controller\AppController;
        use App\Controller\SdSectionController;

        /**
         * SdTabs Controller
         *
         * @property \App\Model\Table\SdTabsTable $SdTabs
         *
         * @method \App\Model\Entity\SdTab[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
         */
        class SdTabsController extends AppController
        {

            /**
             * Index method
             *
             * @return \Cake\Http\Response|void
             */
            public function index()
            {
                $sdTabs = $this->paginate($this->SdTabs);

                $this->set(compact('sdTabs'));
            }


            /**
             * ShowDetail method
             *
             * @return \Cake\Http\Response|void
             */
            public function showdetails($caseNo, $version = 1,$tabid = 1, $distribution_id = null)
            { 
                if($distribution_id == null) $distribution_condition = "SdFieldValues.sd_case_distribution_id IS NULL";
                else $distribution_condition = "SdFieldValues.sd_case_distribution_id ='".$distribution_id."'";                  
                $writePermission= 0;
                $userinfo = $this->request->getSession()->read('Auth.User');
                $sdCasesTable = TableRegistry::get('SdCases');
                $sdCases = $sdCasesTable->find()->where(['caseNo'=>$caseNo,'version_no'=>$version])->contain(['SdProductWorkflows.SdProducts'])->first();
                $caseId = $sdCases['id'];
                // if(empty($caseId)){
                //     $this->Flash->error(__('Cannot find this case.'));
                //     $this->redirect($this->referer());
                //     return;
                // }
                if ($this->request->is(['patch', 'post', 'put'])) {
                    $error =[];
                    $requstData = $this->request->getData();
                    $sdSectionSetsTable = TableRegistry::get('SdSectionSets');
                    $SdSectionStructuresTable = TableRegistry::get('SdSectionStructures');
                    $sdFieldValueTable = TableRegistry::get('SdFieldValues');
                    if(array_key_exists('section',$requstData))
                        $requestSectionArray = $this->request->getData()['section'];
                    else $requestSectionArray =[];
                    foreach($requstData['sd_field_values'] as $sectionValueK => $sectionValue) {
                        $section_id = $sectionValueK;
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
                                    'set_number' => "1",//TODO SET NUMBER REMOVE
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
                                $sdSectionSetsEntity = $sdSectionSetsTable->newEntity(); 
                                if(empty($requestSectionArray)) continue;
                                $sectionArray = explode(',',$requestSectionArray[$section_id]);
                                for($i = 0;$i<sizeof($sectionArray);$i++){
                                    $sdSectionSetsEntity['set_array'] = $sdSectionSetsEntity['set_array'].explode(':',$sectionArray[$i])[1].","; 
                                }
                                $sdSectionSetsEntity['set_array'] = substr($sdSectionSetsEntity['set_array'], 0, -1);
                                $sdSectionSetsEntity['sd_section_id'] = $section_id;
                                $setDataSet = [
                                    'set_array' =>$sdSectionSetsEntity['set_array'],
                                    'sd_field_value_id'=>$savedFieldValue['id'],
                                ];
                                $sdSectionSetsEntity['sd_field_value_id'] = $savedFieldValue['id'];
                                if(!$sdSectionSetsTable->save($sdSectionSetsEntity)){
                                    echo "error in adding sets!" ; 
                                    debug($sdSectionSetsEntity);
                                }                
                                $sections = $SdSectionStructuresTable->find()->select(['sd_section_id'])
                                    ->join(['sections' =>[
                                        'table' =>'sd_sections',
                                        'type'=>'INNER',
                                        'conditions'=>['sections.id = SdSectionStructures.sd_section_id'],
                                        ]])            
                                    ->where(['sd_field_id'=>$sectionFieldValue['sd_field_id'],'sd_section_id !='=>$section_id,'sections.status'=>true]);
                                foreach($sections as $sectionDetail){
                                    $sdSectionSetsEntityNew = $sdSectionSetsTable->newEntity();
                                    $sdSectionSetsEntityNew = $sdSectionSetsTable->patchEntity($sdSectionSetsEntityNew, $setDataSet);
                                    $sdSectionSetsEntityNew['sd_section_id'] = $sectionDetail['sd_section_id'];
                                    if($sectionFieldValue['sd_field_id'] == '149'){
                                        $sdSectionSetsEntityNew['set_array'] = $sdSectionSetsEntityNew['set_array'].',*';
                                    }
                                    $sdSectionSetsEntityNew['sd_field_value_id'] = $savedFieldValue['id'];                           
                                    if(!$sdSectionSetsTable->save($sdSectionSetsEntityNew)){
                                        echo "error in adding NEW sets!" ;// ADD INTO ANOTHER SET
                                        debug($sdSectionSetsEntityNew);
                                    } 
                                }

                            }
                        }
                    };
                }
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
                    if($activitySectionPermissions=="") return $this->redirect(['controller'=>'Dashboards','action' => 'index']);
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
                if($writePermission){
                    $caseValidation = $this->request->getSession()->read('caseValidate.'.$caseId);
                    if($caseValidation==null)
                    $this->request->getSession()->write('caseValidate.'.$caseId, $this->validateForm($caseId));
                }
                $this->set(compact('activitySectionPermissions'));
                //For readonly status, donot render layout
                $readonly = $this->request->getQuery('readonly');
                if ($readonly != 1) $this->viewBuilder()->setLayout('main_layout'); else $this->viewBuilder()->setLayout('readonly_layout');
                $case_versions = $sdCasesTable->find()->where(['caseNo'=>$caseNo])->select(['version_no']);
                $product_name = $sdCases['sd_product_workflow']['sd_product']['product_name'];

                //Fetch tab structures
                //TODO according to model
                $sdFieldTable = TableRegistry::get('SdFields');
                $sdTab = TableRegistry::get('SdSections');
                $sdSections = $sdTab ->find()->where(['sd_tab_id'=>$tabid,'status'=>true])
                                    ->order(['SdSections.section_level'=>'ASC','SdSections.display_order'=>'ASC'])
                                    ->contain(['SdSectionSummaries','SdSectionStructures'=>function($q)use($caseId,$distribution_condition){
                                        return $q->order(['SdSectionStructures.row_no'=>'ASC','SdSectionStructures.field_start_at'=>'ASC'])
                                            ->contain(['SdFields'=>['SdFieldValueLookUps','SdFieldValues'=> function ($q)use($caseId,$distribution_condition) {
                                                return $q->contain(['SdSectionSets'])->where(['SdFieldValues.sd_case_id'=>$caseId, $distribution_condition,
                                                                 'SdFieldValues.status'=>true]);
                                            }, 'SdElementTypes'=> function($q){
                                            return $q->select('type_name')->where(['SdElementTypes.status'=>true]);
                                                }]]);
                                    }])->toArray();
                $child_list = [];
                foreach($sdSections as $sdSection){
                    if($sdSection->parent_section!=0){
                        if(empty($child_list[$sdSection->parent_section])) $child_list[$sdSection->parent_section]="";
                        $child_list[$sdSection->parent_section] = $child_list[$sdSection->parent_section].$sdSection->id.",";
                    }
                    //select correct set
                    foreach($sdSection->sd_section_structures as $structures){
                        foreach($structures->sd_field->sd_field_values as $field_values){
                            $setMatch = 0;
                            if(empty($field_values->sd_section_sets)) continue;
                            foreach($field_values->sd_section_sets as $section_set){
                                if($section_set->sd_section_id == $sdSection->id) {
                                    $field_values->sd_section_sets = $section_set;
                                    $setMatch = 1;
                                    break;
                                }
                            }if(!$setMatch) $field_values->sd_section_sets = "";
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
                        $foundField = $sdFieldTable->find()->where(['SdFields.id'=>$sdField])->contain(['SdFieldValueLookUps','SdFieldValues'=> function ($q)use($caseId,$distribution_condition) {
                            return $q->contain(['SdSectionSets'])->where(['SdFieldValues.sd_case_id'=>$caseId, $distribution_condition,
                                             'SdFieldValues.status'=>true]);
                        }, 'SdElementTypes'=> function($q){
                        return $q->select('type_name')->where(['SdElementTypes.status'=>true]);
                            }])->first(); 
                        foreach($foundField->sd_field_values as $fvalue){
                            foreach($fvalue->sd_section_sets as $setDetail){
                                
                                if($setDetail->sd_section_id == $sdSection->id||in_array($setDetail->sd_section_id, explode(',',$child_list[$sdSection->id])))
                                {
                                    $fvalue->sd_section_sets = [0=>$setDetail];
                                }
                            }
                        }
                        array_push($sdFields,$foundField);
                    }       
                    $sdSection->sd_section_summary['sdFields'] = $sdFields;
                }
                if($userinfo['sd_role_id']>2){
                    foreach($sdSections as $sectionKey => $sdSection){
                        if(!array_key_exists($sdSection['id'],$activitySectionPermissions)) unset($sdSections[$sectionKey]);
                    }
                }
                $this->set(compact('validatedDatas','sdSections','caseNo','version','tabid','caseId','product_name','case_versions','writePermission'));
            }
            /**
             *
             * build_sorter method
             *
             *
             *
             */
            public function build_sorter($key) {
                return function ($a, $b) use ($key) {
                    return strnatcmp($a[$key], $b[$key]);
                };
            }

            /**
            * View method
            *
            * @param string|null $id Sd Tab id.
            * @return \Cake\Http\Response|void
            * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
            */
            public function view($id = null)
            {
                $sdTab = $this->SdTabs->get($id, [
                    'contain' => ['SdSections']
                ]);
                $this->set(compact('sdTab'));
            }

            /**
            * Add method
            *
            * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
            */
            public function add()
            {
                $sdTab = $this->SdTabs->newEntity();
                if ($this->request->is('post')) {
                    $sdTab = $this->SdTabs->patchEntity($sdTab, $this->request->getData());
                    if ($this->SdTabs->save($sdTab)) {
                        $this->Flash->success(__('The sd tab has been saved.'));

                        return $this->redirect(['action' => 'index']);
                    }
                    $this->Flash->error(__('The sd tab could not be saved. Please, try again.'));
                }
                $this->set(compact('sdTab'));
            }

            /**
            * Edit method
            *
            * @param string|null $id Sd Tab id.
            * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
            * @throws \Cake\Network\Exception\NotFoundException When record not found.
            */
            public function edit($id = null)
            {
                $sdTab = $this->SdTabs->get($id, [
                    'contain' => []
                ]);
                if ($this->request->is(['patch', 'post', 'put'])) {
                    $sdTab = $this->SdTabs->patchEntity($sdTab, $this->request->getData());
                    debug($sdTab);
                    if ($this->SdTabs->save($sdTab)) {
                        $this->Flash->success(__('The sd tab has been saved.'));
                        return $this->redirect(['action' => 'index']);
                    }
                    $this->Flash->error(__('The sd tab could not be saved. Please, try again.'));
                }
                $this->set(compact('sdTab'));
            }

            /**
            * Delete method
            *
            * @param string|null $id Sd Tab id.
            * @return \Cake\Http\Response|null Redirects to index.
            * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
            */
            public function delete($id = null)
            {
                $this->request->allowMethod(['post', 'delete']);
                $sdTab = $this->SdTabs->get($id);
                if ($this->SdTabs->delete($sdTab)) {
                    $this->Flash->success(__('The sd tab has been deleted.'));
                } else {
                    $this->Flash->error(__('The sd tab could not be deleted. Please, try again.'));
                }

                return $this->redirect(['action' => 'index']);
            }
            /**
            *
            *
            */
            public function tabnavbar(){
                $sdTabs = $this->SdTabs->find()->select(['tab_name','display_order'])->where(['status'=>1])->order(['display_order' => 'ASC']);
                $this->set(compact('sdTabs'));
            }


    /**
     *
     * validate each required field
     */
    public function validateForm($caseId, $sectionId=null, $tabId=null){
        $SdFieldValuesTable = TableRegistry::get('SdFieldValues');
        $allRequiredFieldValues = $SdFieldValuesTable->find()
        ->select(['SdFieldValues.id','SdFieldValues.set_number','SdFieldValues.sd_field_id','sections.id','tabs.id'])
        ->join([
            'ss'=>[
                'table'=>'sd_section_structures',
                'type'=>'INNER',
                'conditions'=>['ss.sd_field_id = SdFieldValues.sd_field_id']
            ],
            'sections'=>[
                'table'=>'sd_sections',
                'type'=>'INNER',
                'conditions'=>['sections.id = ss.sd_section_id']
            ],
            'tabs'=>[
                'table'=>'sd_tabs',
                'type'=>'INNER',
                'conditions'=>['sections.sd_tab_id = tabs.id']
            ]
        ])->distinct()->where(['SdFieldValues.status'=>1,'SdFieldValues.sd_case_id'=>$caseId])->order(['sections.id'=>'ASC', 'SdFieldValues.set_number'=>'ASC']);
        if($sectionId!=null) $allRequiredFieldValues->where(['sections.id'=>$sectionId]);
        $SdSectionStructuresTable = TableRegistry::get('SdSectionStructures');
        $allRequiredFields = $SdSectionStructuresTable->find()
        ->select(['sd_field_id','sd_section_id','sf.field_label','fv.field_value','sections.section_name','sections.sd_tab_id','tabs.tab_name'])->join([
            'sf'=>[
                'table'=>'sd_fields',
                'type'=>'INNER',
                'conditions'=>['sf.id = SdSectionStructures.sd_field_id']
            ],
            'sections'=>[
                'table'=>'sd_sections',
                'type'=>'INNER',
                'conditions'=>['sections.id = SdSectionStructures.sd_section_id']
            ],
            'tabs'=>[
                'table'=>'sd_tabs',
                'type'=>'INNER',
                'conditions'=>['sections.sd_tab_id = tabs.id']
            ],
            'fv'=>[
                'table'=>'sd_field_values',
                'type'=>'LEFT',
                'conditions'=>['fv.sd_field_id = SdSectionStructures.sd_field_id','fv.status = 1','fv.sd_case_id ='.$caseId]
            ]
        ])->distinct()->where(['SdSectionStructures.is_required'=>'1']);
        if($sectionId!=null) $allRequiredFields->where(['sd_section_id'=>$sectionId]);
        $exist_fields = [];
        $required_field_list = [];
        foreach($allRequiredFields as $requiredField){
            if($requiredField['fv']['field_value']==null)
            $required_field_list[$requiredField['sections']['sd_tab_id']][$requiredField['sd_section_id']][$requiredField['sd_field_id']] = [
                'sd_field_id'=>$requiredField['sd_field_id'],
                'set_number'=>'null',
                'tab_id'=>$requiredField['sections']['sd_tab_id'],
                'tab_name'=>$requiredField['tabs']['tab_name'],
                'field_label'=>$requiredField['sf']['field_label'],
                'section_name'=>$requiredField['sections']['section_name']
            ];
        }
        foreach($allRequiredFieldValues as $key => $allRequiredFieldValue){
            if(in_array($key,$exist_fields)) continue;
            array_push($exist_fields,$key);
            $set_number = $allRequiredFieldValue['set_number'];
            $section_Id = $allRequiredFieldValue['sections']['id'];
            $required_field_set = [];
            foreach($allRequiredFields as $allRequiredField){
                if(($allRequiredField['sd_section_id']!=$section_Id)||($allRequiredField['sd_field_id']==$allRequiredFieldValue['sd_field_id'])) continue;
                array_push($required_field_set,[
                    'sd_field_id'=>$allRequiredField['sd_field_id'],
                    'field_label'=>$allRequiredField['sf']['field_label'],
                    'section_id'=>$allRequiredField['sd_section_id'],
                    'section_name'=>$allRequiredField['sections']['section_name'],
                    'tab_id'=>$allRequiredField['sections']['sd_tab_id'],
                    'tab_name'=>$allRequiredField['tabs']['tab_name']
                    ]);
            }
            foreach($required_field_set as $required_field){
                $exist = 0;
                foreach($allRequiredFieldValues as $researchKey => $relatedSectionField){
                    if(in_array($researchKey,$exist_fields)) continue;
                    if(($relatedSectionField['sd_field_id']==$required_field['sd_field_id'])&&($relatedSectionField['set_number']==$set_number))
                    {
                        array_push($exist_fields,$researchKey);
                        $exist = 1;
                        break;
                    }
                }
                if($exist == 0){
                    if(!array_key_exists($required_field['sd_field_id'],$required_field_list[$required_field['tab_id']][$required_field['section_id']]))
                    $required_field_list[$required_field['tab_id']][$required_field['section_id']][$required_field['sd_field_id']]=[
                        'sd_field_id'=>$required_field['sd_field_id'],
                        'set_number'=>$set_number,
                        'section_id'=>$required_field['section_id'],
                        'section_name'=>$required_field['section_name'],
                        'tab_id'=>$required_field['tab_id'],
                        'tab_name'=>$required_field['tab_name'],
                        'field_label'=>$required_field['field_label']
                    ];
                }
            }
        }
        if($this->request->is('POST')){
            if($sectionId!=null)
            $this->request->getSession()->write('caseValidate.'.$caseId.'.'.$tabId.'.'.$sectionId, $required_field_list[$tabId][$sectionId]);
            $this->autoRender = false;
            echo json_encode($required_field_list);
            die();
        }
        // debug($required_field_list);
        return $required_field_list;
    }

        }
