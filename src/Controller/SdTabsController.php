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
            public function showdetails($caseNo, $version = 1,$tabid = 1)
            {

                $writePermission= 0;
                $userinfo = $this->request->session()->read('Auth.User');
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
                    $sdMedwatchPositions = TableRegistry::get('SdFieldValues');
                    foreach($requstData['sd_field_values'] as $sectionValueK => $sectionValue) {
                        foreach($sectionValue as $sectionFieldK =>$sectionFieldValue){
                            if($sectionFieldValue['id']!=null){//add judging whether updateing Using Validate
                                $sdFieldValueEntity = $sdMedwatchPositions->get($sectionFieldValue['id']);/**add last-updated time */
                                $sdMedwatchPositions->patchEntity($sdFieldValueEntity,$sectionFieldValue);
                                if(!$sdMedwatchPositions->save($sdFieldValueEntity)) echo "error in updating!" ;
                            }elseif(!empty($sectionFieldValue['field_value'])){
                                $sdFieldValueEntity = $sdMedwatchPositions->newEntity();
                                $dataSet = [
                                    'sd_case_id' => $caseId,
                                    'sd_field_id' => $sectionFieldValue['sd_field_id'],
                                    'set_number' => $sectionFieldValue['set_number'],
                                    'created_time' =>date("Y-m-d H:i:s"),
                                    'field_value' =>$sectionFieldValue['field_value'],
                                    'status' =>'1',
                                ];
                                $sdFieldValueEntity = $sdMedwatchPositions->patchEntity($sdFieldValueEntity, $dataSet);
                                if(!$sdMedwatchPositions->save($sdFieldValueEntity)) echo "error in adding!" ;
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
                                    'conditions'=>['ua.sd_product_workflow_id ='.$sdCases['sd_product_workflow_id'],'ua.sd_user_id ='.$userinfo['id'],'ua.sd_workflow_activity_id = SdActivitySectionPermissions.sd_workflow_activity_id']
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
                if($writePermission){
                    $caseValidation = $this->request->session()->read('caseValidate.'.$caseId);
                    if($caseValidation==null)
                    $this->request->session()->write('caseValidate.'.$caseId, $this->validateForm($caseId));
                }
                $this->set(compact('activitySectionPermissions'));
                //For readonly status, donot render layout
                $readonly = $this->request->getQuery('readonly');
                if ($readonly!=1) $this->viewBuilder()->layout('main_layout'); else $this->viewBuilder()->layout('readonly_layout');
                $case_versions = $sdCasesTable->find()->where(['caseNo'=>$caseNo])->select(['version_no']);
                $product_name = $sdCases['sd_product_workflow']['sd_product']['product_name'];

                //Fetch tab structures
                //TODO according to model
                $sdTab = TableRegistry::get('SdSections');
                $sdSections = $sdTab ->find()->where(['sd_tab_id'=>$tabid,'status'=>true])
                                    ->order(['SdSections.section_level'=>'DESC','SdSections.display_order'=>'ASC'])
                                    ->contain(['SdSectionStructures'=>function($q)use($caseId){
                                        return $q->order(['SdSectionStructures.row_no'=>'ASC','SdSectionStructures.field_start_at'=>'ASC'])
                                            ->contain(['SdFields'=>['SdFieldValueLookUps','SdFieldValues'=> function ($q)use($caseId) {
                                                return $q->where(['SdFieldValues.sd_case_id'=>$caseId, 'SdFieldValues.status'=>true]);
                                            }, 'SdElementTypes'=> function($q){
                                            return $q->select('type_name')->where(['SdElementTypes.status'=>true]);
                                                }]]);
                                    }])->toArray();
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

           
        }
    

            
