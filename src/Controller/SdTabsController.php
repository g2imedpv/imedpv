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

                $this->set(compact('sdSections','caseNo','version','tabid','caseId','product_name','case_versions','writePermission'));
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
            *  Generate PDF files
            *
            */
            
            public function genFDApdf($caseId)
            {
                $sdMedwatchPositions = TableRegistry::get('sdMedwatchPositions');
                
                //value_type 1--direct
                $positions1= $sdMedwatchPositions ->find()
                    ->select(['sdMedwatchPositions.id','sdMedwatchPositions.position_top','sdMedwatchPositions.position_left',
                    'sdMedwatchPositions.position_width','sdMedwatchPositions.position_height','fv.field_value'])
                    ->join([
                        'fv' =>[
                        'table' =>'sd_field_values',
                        'type'=>'LEFT',
                        'conditions'=>['sdMedwatchPositions.sd_field_id = fv.sd_field_id','fv.status = 1','fv.sd_case_id='.$caseId,'sdMedwatchPositions.value_type=1']
                        ]
                    ]);
                    $text = $text." <style> p {position: absolute;}  </style>";
                    foreach($positions1 as $position_detail1){
                            $text=$text.'<p style="top: '.$position_detail1['position_top'].'px; left: '.$position_detail1['position_left']
                                  .'px; width: '.$position_detail1['position_width'].'px;  height: '.$position_detail1['position_height'].'px; color:red;">'.$position_detail1['fv']['field_value'].'</p>';
                    }
                //debug($text);die();
                
                //value_type 2--checkbox
                $positions2= $sdMedwatchPositions ->find()
                ->select(['sdMedwatchPositions.id','sdMedwatchPositions.position_top','sdMedwatchPositions.position_left',
                    'sdMedwatchPositions.position_width','sdMedwatchPositions.position_height','fv.field_value','sdMedwatchPositions.field_name'])
                ->join([
                    'fv' =>[
                        'table' =>'sd_field_values',
                        'type'=>'INNER',
                        'conditions'=>['sdMedwatchPositions.sd_field_id = fv.sd_field_id','fv.status = 1','fv.sd_case_id='.$caseId,'sdMedwatchPositions.value_type=2','substr(sdMedwatchPositions.field_name,-1)=fv.field_value']//'substr($sdMedwatchPositions.field_name,strpos(sdMedwatchPositions.field_name,_)+1)=fv.field_value']
                    ]
                ]);
                $text = $text." <style> p {position: absolute;}  </style>";
                foreach($positions2 as $position_detail2)
                {
                        $text =$text.'<p style="top: '.$position_detail2['position_top'].'px; left: '.$position_detail2['position_left']
                            .'px; width: '.$position_detail2['position_width'].'px;  height: '.$position_detail2['position_height'].'px; color:red;">'.'X'.'</p>';
                   
                }
                
                
                //value_type 3--text_sets
                $positions3= $sdMedwatchPositions ->find()
                    ->select(['sdMedwatchPositions.id','sdMedwatchPositions.position_top','sdMedwatchPositions.position_left',
                        'sdMedwatchPositions.position_width','sdMedwatchPositions.position_height','fv.field_value','sdMedwatchPositions.set_number','sdMedwatchPositions.sd_field_id'])
                    ->join([
                        'fv' =>[
                            'table' =>'sd_field_values',
                            'type'=>'RIGHT',
                            'conditions'=>['sdMedwatchPositions.sd_field_id = fv.sd_field_id','fv.status = 1','fv.sd_case_id='.$caseId,'sdMedwatchPositions.value_type=3','sdMedwatchPositions.set_number=fv.set_number']
                        ]
                    ]);
                    $text = $text." <style> p {position: absolute;}  </style>";
                    foreach($positions3 as $position_detail3)
                    {
                        
                            if(($position_detail3['sd_field_id']=='199' )||($position_detail3['sd_field_id']== '205')){//c4#set and date convert mixed
                                $startday=substr($position_detail3['fv']['field_value'],0,2);
                                $startmon=substr($position_detail3['fv']['field_value'],2,2);
                                    switch($startmon){
                                        case '01':
                                            $startmon="JAN-";
                                            continue;
                                        case '02':
                                            $startmon="FEB-";
                                            continue;
                                        case '03':
                                            $startmon="MAR-";
                                            continue;
                                        case '04':
                                            $startmon="APR-";
                                            continue;
                                        case '05':
                                            $startmon="MAY-";
                                            continue;
                                        case '06':
                                            $startmon="JUN-";
                                            continue;
                                        case '07':
                                            $startmon="JUL-";
                                            continue;
                                        case '10':
                                            $startmon="OCT-";
                                            continue;
                                        case '11':
                                            $startmon="NOV-";
                                            continue;
                                        case '12':
                                            $startmon="DEC-";
                                            continue;
                                        case '08':
                                            $startmon="AUG-";
                                            continue;
                                        case '09':
                                            $startmon="SEP-";
                                            continue;
                                        default:
                                        }
                                $startyear=substr($position_detail3['fv']['field_value'],4,4);
                                $text =$text.'<p style="top: '.$position_detail3['position_top'].'px; left: '.$position_detail3['position_left']
                                .'px; width: '.$position_detail3['position_width'].'px;  height: '.$position_detail3['position_height'].'px; color:red;">'.$startday.'-'.$startmon.$startyear.'</p>';
                                }
                            else if( $position_detail3['sd_field_id']='298'){ //c8# set and date convert mixed
                                $date=explode('_',$position_detail3['field_name']);
                                switch($date[1]){
                                    case "day":
                                        $text =$text.'<p style="top: '.$position_detail3['position_top'].'px; left: '.$position_detail3['position_left']
                                        .'px; width: '.$position_detail3['position_width'].'px;  height: '.$position_detail3['position_height'].'px; color:red;">'.substr($position_detail3['fv']['field_value'],0,2).'</p>';
                                        continue;
                                    case "month":  
                                        switch(substr($position_detail3['fv']['field_value'],2,2)){
                                        case '01':
                                                $text =$text.'<p style="top: '.$position_detail3['position_top'].'px; left: '.$position_detail3['position_left']
                                                .'px; width: '.$position_detail3['position_width'].'px;  height: '.$position_detail3['position_height'].'px; color:red;">'.'JAN'.'</p>';
                                                continue;
                                        case '02':
                                                $text =$text.'<p style="top: '.$position_detail3['position_top'].'px; left: '.$position_detail3['position_left']
                                                .'px; width: '.$position_detail3['position_width'].'px;  height: '.$position_detail3['position_height'].'px; color:red;">'.'FEB'.'</p>';
                                                continue;
                                        case '03':
                                                $text =$text.'<p style="top: '.$position_detail3['position_top'].'px; left: '.$position_detail3['position_left']
                                                .'px; width: '.$position_detail3['position_width'].'px;  height: '.$position_detail3['position_height'].'px; color:red;">'.'MAR'.'</p>';
                                                continue;
                                        case '04':
                                                $text =$text.'<p style="top: '.$position_detail3['position_top'].'px; left: '.$position_detail3['position_left']
                                                .'px; width: '.$position_detail3['position_width'].'px;  height: '.$position_detail3['position_height'].'px; color:red;">'.'APR'.'</p>';
                                                continue;
                                        case '05':
                                                $text =$text.'<p style="top: '.$position_detail3['position_top'].'px; left: '.$position_detail3['position_left']
                                                .'px; width: '.$position_detail3['position_width'].'px;  height: '.$position_detail3['position_height'].'px; color:red;">'.'MAY'.'</p>';
                                                continue;
                                        case '06':
                                                $text =$text.'<p style="top: '.$position_detail3['position_top'].'px; left: '.$position_detail3['position_left']
                                                .'px; width: '.$position_detail3['position_width'].'px;  height: '.$position_detail3['position_height'].'px; color:red;">'.'JUN'.'</p>';
                                                continue;
                                        case '07':
                                                $text =$text.'<p style="top: '.$position_detail3['position_top'].'px; left: '.$position_detail3['position_left']
                                                .'px; width: '.$position_detail3['position_width'].'px;  height: '.$position_detail3['position_height'].'px; color:red;">'.'JUL'.'</p>';
                                                continue;
                                        case '08':
                                                $text =$text.'<p style="top: '.$position_detail3['position_top'].'px; left: '.$position_detail3['position_left']
                                                .'px; width: '.$position_detail3['position_width'].'px;  height: '.$position_detail3['position_height'].'px; color:red;">'.'AUG'.'</p>';
                                                continue;
                                        case '09':
                                                $text =$text.'<p style="top: '.$position_detail3['position_top'].'px; left: '.$position_detail3['position_left']
                                                .'px; width: '.$position_detail3['position_width'].'px;  height: '.$position_detail3['position_height'].'px; color:red;">'.'SEP'.'</p>';
                                                continue;
                                        case '10':
                                                $text =$text.'<p style="top: '.$position_detail3['position_top'].'px; left: '.$position_detail3['position_left']
                                                .'px; width: '.$position_detail3['position_width'].'px;  height: '.$position_detail3['position_height'].'px; color:red;">'.'OCT'.'</p>';
                                                continue;
                                        case '11':
                                                $text =$text.'<p style="top: '.$position_detail3['position_top'].'px; left: '.$position_detail3['position_left']
                                                .'px; width: '.$position_detail3['position_width'].'px;  height: '.$position_detail3['position_height'].'px; color:red;">'.'NOV'.'</p>';
                                                continue;
                                        case '12':
                                                $text =$text.'<p style="top: '.$position_detail3['position_top'].'px; left: '.$position_detail3['position_left']
                                                .'px; width: '.$position_detail3['position_width'].'px;  height: '.$position_detail3['position_height'].'px; color:red;">'.'DEC'.'</p>';
                                                continue;
                                            default; 
                                        }
                                    continue;
                                    case "year":
                                        $text =$text.'<p style="top: '.$position_detail3['position_top'].'px; left: '.$position_detail3['position_left']
                                        .'px; width: '.$position_detail3['position_width'].'px;  height: '.$position_detail3['position_height'].'px; color:red;">'.substr($position_detail3['fv']['field_value'],4,4).'</p>';

                                }  
                            }
                            else if( $position_detail3['sd_field_id']='298'){ }//c6,7,9,10      
                            else
                            {$text =$text.'<p style="top: '.$position_detail3['position_top'].'px; left: '.$position_detail3['position_left']
                            .'px; width: '.$position_detail3['position_width'].'px;  height: '.$position_detail3['position_height'].'px; color:red;">'.$position_detail3['fv']['field_value'].'</p>';}
                    
                    // print "<pre>";
                   
                    // print $position_detail3['sd_field_id'];
                    // print "</pre>";
                    // debug($position_detail3['sd_field_id']);die();
                        
                        
                    }
               
                //value_type 4--date convert
                $positions4= $sdMedwatchPositions ->find()
                    ->select(['sdMedwatchPositions.id','sdMedwatchPositions.field_name','sdMedwatchPositions.position_top','sdMedwatchPositions.position_left',
                        'sdMedwatchPositions.position_width','sdMedwatchPositions.position_height','fv.field_value'])
                    ->join([
                        'fv' =>[
                            'table' =>'sd_field_values',
                            'type'=>'INNER',
                            'conditions'=>['sdMedwatchPositions.sd_field_id = fv.sd_field_id','fv.status = 1','fv.sd_case_id='.$caseId,'sdMedwatchPositions.value_type=4']
                        ]
                    ]);
                
                    $text = $text." <style> p {position: absolute;}  </style>";
                    foreach($positions4 as $position_detail4)
                    {
                        $date=explode('_',$position_detail4['field_name']);
                        switch($date[1]){
                            case "day":
                                $text =$text.'<p style="top: '.$position_detail4['position_top'].'px; left: '.$position_detail4['position_left']
                                .'px; width: '.$position_detail4['position_width'].'px;  height: '.$position_detail4['position_height'].'px; color:red;">'.substr($position_detail4['fv']['field_value'],0,2).'</p>';
                                continue;
                            case "month":  
                            switch(substr($position_detail4['fv']['field_value'],2,2)){
                                case '01':
                                        $text =$text.'<p style="top: '.$position_detail4['position_top'].'px; left: '.$position_detail4['position_left']
                                        .'px; width: '.$position_detail4['position_width'].'px;  height: '.$position_detail4['position_height'].'px; color:red;">'.'JAN'.'</p>';
                                        continue;
                                case '02':
                                        $text =$text.'<p style="top: '.$position_detail4['position_top'].'px; left: '.$position_detail4['position_left']
                                        .'px; width: '.$position_detail4['position_width'].'px;  height: '.$position_detail4['position_height'].'px; color:red;">'.'FEB'.'</p>';
                                        continue;
                                case '03':
                                        $text =$text.'<p style="top: '.$position_detail4['position_top'].'px; left: '.$position_detail4['position_left']
                                        .'px; width: '.$position_detail4['position_width'].'px;  height: '.$position_detail4['position_height'].'px; color:red;">'.'MAR'.'</p>';
                                        continue;
                                case '04':
                                        $text =$text.'<p style="top: '.$position_detail4['position_top'].'px; left: '.$position_detail4['position_left']
                                        .'px; width: '.$position_detail4['position_width'].'px;  height: '.$position_detail4['position_height'].'px; color:red;">'.'APR'.'</p>';
                                        continue;
                                case '05':
                                        $text =$text.'<p style="top: '.$position_detail4['position_top'].'px; left: '.$position_detail4['position_left']
                                        .'px; width: '.$position_detail4['position_width'].'px;  height: '.$position_detail4['position_height'].'px; color:red;">'.'MAY'.'</p>';
                                        continue;
                                case '06':
                                        $text =$text.'<p style="top: '.$position_detail4['position_top'].'px; left: '.$position_detail4['position_left']
                                        .'px; width: '.$position_detail4['position_width'].'px;  height: '.$position_detail4['position_height'].'px; color:red;">'.'JUN'.'</p>';
                                        continue;
                                case '07':
                                        $text =$text.'<p style="top: '.$position_detail4['position_top'].'px; left: '.$position_detail4['position_left']
                                        .'px; width: '.$position_detail4['position_width'].'px;  height: '.$position_detail4['position_height'].'px; color:red;">'.'JUL'.'</p>';
                                        continue;
                                case '08':
                                        $text =$text.'<p style="top: '.$position_detail4['position_top'].'px; left: '.$position_detail4['position_left']
                                        .'px; width: '.$position_detail4['position_width'].'px;  height: '.$position_detail4['position_height'].'px; color:red;">'.'AUG'.'</p>';
                                        continue;
                                case '09':
                                        $text =$text.'<p style="top: '.$position_detail4['position_top'].'px; left: '.$position_detail4['position_left']
                                        .'px; width: '.$position_detail4['position_width'].'px;  height: '.$position_detail4['position_height'].'px; color:red;">'.'SEP'.'</p>';
                                        continue;
                                case '10':
                                        $text =$text.'<p style="top: '.$position_detail4['position_top'].'px; left: '.$position_detail4['position_left']
                                        .'px; width: '.$position_detail4['position_width'].'px;  height: '.$position_detail4['position_height'].'px; color:red;">'.'OCT'.'</p>';
                                        continue;
                                case '11':
                                        $text =$text.'<p style="top: '.$position_detail4['position_top'].'px; left: '.$position_detail4['position_left']
                                        .'px; width: '.$position_detail4['position_width'].'px;  height: '.$position_detail4['position_height'].'px; color:red;">'.'NOV'.'</p>';
                                        continue;
                                case '12':
                                        $text =$text.'<p style="top: '.$position_detail4['position_top'].'px; left: '.$position_detail4['position_left']
                                        .'px; width: '.$position_detail4['position_width'].'px;  height: '.$position_detail4['position_height'].'px; color:red;">'.'DEC'.'</p>';
                                        continue;
                                    default; 
                                }
                            continue;
                            case "year":
                                $text =$text.'<p style="top: '.$position_detail4['position_top'].'px; left: '.$position_detail4['position_left']
                                .'px; width: '.$position_detail4['position_width'].'px;  height: '.$position_detail4['position_height'].'px; color:red;">'.substr($position_detail4['fv']['field_value'],4,4).'</p>';
                            default:        
                        }
                }          
                //value_type 5--narrative
                
                $positions5= $sdMedwatchPositions ->find()
                    ->select(['sdMedwatchPositions.id','sdMedwatchPositions.sd_field_id','sdMedwatchPositions.position_top','sdMedwatchPositions.position_left',
                        'sdMedwatchPositions.position_width','sdMedwatchPositions.position_height','fv.field_value'])
                    ->join([
                        'fv' =>[
                            'table' =>'sd_field_values',
                            'type'=>'LEFT',
                            'conditions'=>['sdMedwatchPositions.sd_field_id = fv.sd_field_id','fv.status = 1','fv.sd_case_id='.$caseId,'sdMedwatchPositions.value_type=5']
                        ]
                    ]);
                    $text = $text."<style> p {position: absolute;font-size:10px;}  </style>";
                    foreach($positions5 as $position_detail5)   
                    {
                        switch($position_detail5['sd_field_id']){
                            case 174:
                                $pageone=substr($position_detail5['fv']['field_value'],0,400);
                                $text =$text.'<p style="top: '.$position_detail5['position_top'].'px; left: '.$position_detail5['position_left']
                                .'px; width: '.$position_detail5['position_width'].'px;  height: '.$position_detail5['position_height'].'px; color:red;">'.$pageone.'</p>';
                                continue;
                            case 218:
                                $pageone=substr($position_detail5['fv']['field_value'],0,450);
                                $text =$text.'<p style="top: '.$position_detail5['position_top'].'px; left: '.$position_detail5['position_left']
                                .'px; width: '.$position_detail5['position_width'].'px;  height: '.$position_detail5['position_height'].'px; color:red;">'.$pageone.'</p>';
                                continue;
                            case 104:
                                $pageone=substr($position_detail5['fv']['field_value'],0,300);
                                $text =$text.'<p style="top: '.$position_detail5['position_top'].'px; left: '.$position_detail5['position_left']
                                .'px; width: '.$position_detail5['position_width'].'px;  height: '.$position_detail5['position_height'].'px; color:red;">'.$pageone.'</p>';
                                continue;
                        /* case c2:
                            $pageone=substr($position_detail5['fv']['field_value'],0,400);
                            $text =$text.'<p style="top: '.$position_detail5['position_top'].'px; left: '.$position_detail5['position_left']
                            .'px; width: '.$position_detail5['position_width'].'px;  height: '.$position_detail5['position_height'].'px; color:red;">'.$pageone.'</p>';
                            $pagethree=substr($position_detail5['fv']['field_value'],311);
                            $text =$text.'<p style="top: 1500px; left: 100px; width:100px;  height:100px; color:red;">'.$pagethree.'</p>';
                            continue;
                            case d11:
                            $pageone=substr($position_detail5['fv']['field_value'],0,400);
                            $text =$text.'<p style="top: '.$position_detail5['position_top'].'px; left: '.$position_detail5['position_left']
                            .'px; width: '.$position_detail5['position_width'].'px;  height: '.$position_detail5['position_height'].'px; color:red;">'.$pageone.'</p>';
                            $pagethree=substr($position_detail5['fv']['field_value'],311);
                            $text =$text.'<p style="top: 1500px; left: 100px; width:100px;  height:100px; color:red;">'.$pagethree.'</p>';
                            continue;
                            default:
                    */
                                
                        }
                }
               
                
                // Require composer autoload
                //require_once __DIR__ . '../vendor/autoload.php';
               // debug($positions);
                // Require composer autoload
                //require_once __DIR__ . '../vendor/autoload.php';

                $mpdf = new \Mpdf\Mpdf();

                $mpdf->CSSselectMedia='mpdf';
                $mpdf->SetTitle('FDA-MEDWATCH');

                $medwatchdata = $this->SdTabs->find();
                
                $mpdf->use_kwt = true;  

                $mpdf->SetImportUse();
                $mpdf->SetDocTemplate('export_template/MEDWATCH.pdf',true);

                // If needs to link external css file, uncomment the next 2 lines
                // $stylesheet = file_get_contents('css/genpdf.css');
                // $mpdf->WriteHTML($stylesheet,1);
                $mpdf->WriteHTML($text);

                $mpdf->AddPage();
                //$test2 = '<img src="img/pdficon.png" />';
                //$mpdf->WriteHTML("second page");


                
                $mpdf->AddPage();
                $positions5= $sdMedwatchPositions ->find()
                ->select(['sdMedwatchPositions.id','sdMedwatchPositions.sd_field_id','sdMedwatchPositions.position_top','sdMedwatchPositions.position_left',
                    'sdMedwatchPositions.position_width','sdMedwatchPositions.position_height','fv.field_value'])
                ->join([
                    'fv' =>[
                        'table' =>'sd_field_values',
                        'type'=>'LEFT',
                        'conditions'=>['sdMedwatchPositions.sd_field_id = fv.sd_field_id','fv.status = 1','fv.sd_case_id='.$caseId,'sdMedwatchPositions.value_type=5']
                    ]
                ]);
               
                $text = "<style> p {position: absolute;font-size:15px;}  </style>";
                foreach($positions5 as $position_detail5)   
                {
                    switch($position_detail5['sd_field_id']){
                        case 218:
                            $pagethree=substr($position_detail5['fv']['field_value'],450);
                            $text =$text.'<p style="top: 125px; left: 45px; width:720px;  height:275px; color:blue;">'.$pagethree.'</p>';
                            continue;
                        case 174:
                            $pagethree=substr($position_detail5['fv']['field_value'],400);
                            $text =$text.'<p style="top: 425px; left: 45px; width:720px;  height:130px; color:blue;">'.$pagethree.'</p>';
                            continue;
                        case 104:
                            $pagethree=substr($position_detail5['fv']['field_value'],300);
                            $text =$text.'<p style="top: 580px; left: 45px; width:720px;  height:100px; color:blue;">'.$pagethree.'</p>';
                            continue;
                        default:
                    }
                }    
                
                $mpdf->WriteHTML($text);
               

                $mpdf->Output();
                // Download a PDF file directly to LOCAL, uncomment while in real useage
                //$mpdf->Output('TEST.pdf', \Mpdf\Output\Destination::DOWNLOAD);


                $this->set(compact('positions'));

            }
            /**
            *  Generate XML files
            *
            */
            //create getValue function
            public function getValue($caseId,$descriptor,$setNumber){
                $sdFields = TableRegistry::get('sdFields');
                $ICSR = $sdFields ->find()
                ->select(['fv.field_value'])
                ->join([
                    'fv' =>[
                        'table' =>'sd_field_values',
                        'type'=>'INNER',
                        'conditions'=>['sdFields.id = fv.sd_field_id','fv.sd_case_id='.$caseId, 'sdFields.descriptor = \''.$descriptor.'\'',
                        'fv.set_number='.$setNumber ]         
                    ]
                ])->first();
                $value=$ICSR['fv']['field_value'];      
                return $value; 
              
            }
 
            public function genXML($caseId)
            {
                $this->autoRender = false;
                $xml = "xml/example";
                header("Content-Type: text/html/force-download");
                header("Content-Disposition: attachment; filename=".$xml.".xml");
                $xml = xmlwriter_open_memory();
                xmlwriter_set_indent($xml, 1);
                $res = xmlwriter_set_indent_string($xml, ' ');
                //create document
                xmlwriter_start_document($xml, '1.0','ISO-8859-1');//FDA supports only the ISO-8859-1 character set for encoding the submission.
                xmlwriter_write_comment($xml, 'edited with XML Spy v3.0.7 NT');
                    // A first element ichicsr
                    xmlwriter_start_element($xml, 'ichicsr');//ichicsr
                        // Attribute lang="en"
                        xmlwriter_start_attribute($xml, 'lang');
                        xmlwriter_text($xml, 'en');
                        xmlwriter_end_attribute($xml);

                        // Start a child element ichicsrmessageheader
                        xmlwriter_start_element($xml, 'ichicsrmessageheader');
                            xmlwriter_start_element($xml, 'messagetype');
                             //xmlwriter_write_cdata($xml, "This is cdata content");
                            xmlwriter_end_element($xml);
                            xmlwriter_start_element($xml, 'messageformatversion');
                                xmlwriter_text($xml,'2.2');//For submission of combination product reports use the value “2.2” for the DTD Descriptor
                            xmlwriter_end_element($xml);
                            xmlwriter_start_element($xml, 'messageformatrelease');xmlwriter_end_element($xml);
                            xmlwriter_start_element($xml, 'messagenumb');xmlwriter_end_element($xml); 
                            xmlwriter_start_element($xml, 'messagesenderidentifier');xmlwriter_end_element($xml); 
                            xmlwriter_start_element($xml, 'messagereceiveridentifier');xmlwriter_end_element($xml); 
                            xmlwriter_start_element($xml, 'messagedateformat');xmlwriter_end_element($xml); 
                            xmlwriter_start_element($xml, 'messagedate');xmlwriter_end_element($xml);
                        xmlwriter_end_element($xml); // ichicsrmessageheader
                        //Start a child element safetyreport
                        xmlwriter_start_element($xml, 'safetyreport');
                            xmlwriter_start_element($xml, 'safetyreportversion');
                                xmlwriter_text($xml,$this->getValue($caseId, "safetyreportversion",1));
                            xmlwriter_end_element($xml);
                            xmlwriter_start_element($xml, 'safetyreportid');//safetyreportid
                                xmlwriter_text($xml,$this->getValue($caseId, "safetyreportid",1));
                            xmlwriter_end_element($xml);//safetyreportid
                            xmlwriter_start_element($xml, 'primarysourcecountry');//primarysourcecountry
                                xmlwriter_text($xml,$this->getValue($caseId, "primarysourcecountry",1));
                            xmlwriter_end_element($xml);//primarysourcecountry
                            xmlwriter_start_element($xml, 'occurcountry');//occurcountry
                                xmlwriter_text($xml,$this->getValue($caseId, "occurcountry",1));
                            xmlwriter_end_element($xml); //occurcountry
                            xmlwriter_start_element($xml, 'transmissiondateformat');//transmissiondateformat
                                xmlwriter_text($xml,$this->getValue($caseId, "transmissiondateformat",1));
                            xmlwriter_end_element($xml); //transmissiondateformat
                            xmlwriter_start_element($xml, 'transmissiondate');//transmissiondate
                                xmlwriter_text($xml,$this->getValue($caseId, "transmissiondate",1));
                            xmlwriter_end_element($xml); //transmissiondate
                            xmlwriter_start_element($xml, 'reporttype');//reporttype
                                xmlwriter_text($xml,$this->getValue($caseId, "reporttype",1));
                            xmlwriter_end_element($xml); //reporttype
                            xmlwriter_start_element($xml, 'serious');
                                xmlwriter_text($xml,$this->getValue($caseId, "serious",1));
                            xmlwriter_end_element($xml);//serious
                            xmlwriter_start_element($xml, 'seriousnessdeath');//seriousnessdeath
                                xmlwriter_text($xml,$this->getValue($caseId, "seriousnessdeath",1));
                            xmlwriter_end_element($xml);//seriousnessdeath
                            xmlwriter_start_element($xml, 'seriousnesslifethreatening');//seriousnesslifethreatening
                                xmlwriter_text($xml,$this->getValue($caseId, "seriousnesslifethreatening",1));
                            xmlwriter_end_element($xml);//seriousnesslifethreatening
                            xmlwriter_start_element($xml, 'seriousnesshospitalization');//seriousnesshospitalization
                                xmlwriter_text($xml,$this->getValue($caseId, "seriousnesshospitalization",1));
                            xmlwriter_end_element($xml);//seriousnesshospitalization
                            xmlwriter_start_element($xml, 'seriousnessdisabling');//seriousnessdisabling
                                xmlwriter_text($xml,$this->getValue($caseId, "seriousnessdisabling",1));
                            xmlwriter_end_element($xml); //seriousnessdisabling
                            xmlwriter_start_element($xml, 'seriousnesscongenitalanomali');//seriousnesscongenitalanomali
                                xmlwriter_text($xml,$this->getValue($caseId, "seriousnesscongenitalanomali",1));
                            xmlwriter_end_element($xml); //seriousnesscongenitalanomali
                            xmlwriter_start_element($xml, 'seriousnessother');//seriousnessother
                                xmlwriter_text($xml,$this->getValue($caseId, "seriousnessother",1));
                            xmlwriter_end_element($xml); //seriousnessother
                            xmlwriter_start_element($xml, 'receivedateformat');//receivedateformat
                                xmlwriter_text($xml,$this->getValue($caseId, "receivedateformat",1));
                            xmlwriter_end_element($xml); //receivedateformat
                            xmlwriter_start_element($xml, 'receivedate');//receivedate
                                xmlwriter_text($xml,$this->getValue($caseId, "receivedate",1));
                            xmlwriter_end_element($xml);//receivedate
                            xmlwriter_start_element($xml, 'receiptdateformat');//receiptdateformat
                                xmlwriter_text($xml,$this->getValue($caseId, "receiptdateformat",1));
                            xmlwriter_end_element($xml);//receiptdateformat
                            xmlwriter_start_element($xml, 'receiptdate');//receiptdate
                                xmlwriter_text($xml,$this->getValue($caseId, "receiptdate",1));
                            xmlwriter_end_element($xml);//receiptdate
                            xmlwriter_start_element($xml, 'additionaldocument');//additionaldocument
                                xmlwriter_text($xml,$this->getValue($caseId, "additionaldocument",1));
                            xmlwriter_end_element($xml);//additionaldocument
                            xmlwriter_start_element($xml, 'documentlist');//documentlist
                                xmlwriter_text($xml,$this->getValue($caseId, "documentlist",1));
                            xmlwriter_end_element($xml); //documentlist
                            xmlwriter_start_element($xml, 'fulfillexpeditecriteria');//fulfillexpeditecriteria
                                xmlwriter_text($xml,$this->getValue($caseId, "fulfillexpeditecriteria",1));
                            xmlwriter_end_element($xml); //fulfillexpeditecriteria
                            xmlwriter_start_element($xml, 'companynumb');//companynumb
                                xmlwriter_text($xml,$this->getValue($caseId, "companynumb",1));
                            xmlwriter_end_element($xml); //companynumb
                            xmlwriter_start_element($xml, 'duplicate');//duplicate
                                xmlwriter_text($xml,$this->getValue($caseId, "duplicate",1));
                            xmlwriter_end_element($xml); //duplicate
                            xmlwriter_start_element($xml, 'casenullification');//casenullification
                                xmlwriter_text($xml,$this->getValue($caseId, "casenullification",1));
                            xmlwriter_end_element($xml);//casenullification
                            xmlwriter_start_element($xml, 'nullificationreason');//nullificationreason
                                xmlwriter_text($xml,$this->getValue($caseId, "nullificationreason",1));
                            xmlwriter_end_element($xml);//nullificationreason
                            xmlwriter_start_element($xml, 'medicallyconfirm');//medicallyconfirm
                                xmlwriter_text($xml,$this->getValue($caseId, "medicallyconfirm",1));
                            xmlwriter_end_element($xml);//medicallyconfirm
                            //reportduplicate
                            xmlwriter_start_element($xml, 'reportduplicate');//reportduplicate
                                xmlwriter_start_element($xml, 'duplicatesource');//duplicatesource
                                    xmlwriter_text($xml,$this->getValue($caseId, "duplicatesource",1));
                                xmlwriter_end_element($xml);//duplicatesource
                                xmlwriter_start_element($xml, 'duplicatenumb');//duplicatenumb
                                    xmlwriter_text($xml,$this->getValue($caseId, "duplicatenumb",1));
                                xmlwriter_end_element($xml);//duplicatenumb
                            xmlwriter_end_element($xml);//reportduplicate
                            //reportduplicate
                            xmlwriter_start_element($xml, 'reportduplicate');//reportduplicate
                                xmlwriter_start_element($xml, 'duplicatesource');//duplicatesource
                                    xmlwriter_text($xml,$this->getValue($caseId, "duplicatesource",2));
                                xmlwriter_end_element($xml);//duplicatesource
                                xmlwriter_start_element($xml, 'duplicatenumb');//duplicatenumb
                                    xmlwriter_text($xml,$this->getValue($caseId, "duplicatesource",2));
                                xmlwriter_end_element($xml);//duplicatenumb
                            xmlwriter_end_element($xml);//reportduplicate
                            //linkedreport
                            xmlwriter_start_element($xml, 'linkedreport');//linkedreport
                                xmlwriter_start_element($xml, 'linkedreportnumb');//linkedreportnumb
                                    xmlwriter_text($xml,$this->getValue($caseId, "linkedreportnumb",1));
                                xmlwriter_end_element($xml);//linkedreportnumb
                            xmlwriter_end_element($xml); //linkedreport
                            //linkedreport
                            xmlwriter_start_element($xml, 'linkedreport');
                                xmlwriter_start_element($xml, 'linkedreportnumb');
                                    xmlwriter_text($xml,$this->getValue($caseId, "linkedreportnumb",2));
                                xmlwriter_end_element($xml);
                            xmlwriter_end_element($xml); 
                            //primarysource
                            xmlwriter_start_element($xml, 'primarysource');//primarysource
                                xmlwriter_start_element($xml, 'reportertitle');//reportertitle
                                    xmlwriter_text($xml,$this->getValue($caseId, "reportertitle",1));
                                xmlwriter_end_element($xml);//reportertitle
                                xmlwriter_start_element($xml, 'reportergivename');//reportergivename
                                    xmlwriter_text($xml,$this->getValue($caseId, "reportergivename",1));
                                xmlwriter_end_element($xml);//reportergivename
                                xmlwriter_start_element($xml, 'reportermiddlename');//reportermiddlename
                                    xmlwriter_text($xml,$this->getValue($caseId, "reportermiddlename",1));
                                xmlwriter_end_element($xml);//reportermiddlename
                                xmlwriter_start_element($xml, 'reporterfamilyname');//reporterfamilyname
                                    xmlwriter_text($xml,$this->getValue($caseId, "reporterfamilyname",1));
                                xmlwriter_end_element($xml);//reporterfamilyname
                                xmlwriter_start_element($xml, 'reporterorganization');//reporterorganization
                                    xmlwriter_text($xml,$this->getValue($caseId, "reporterorganization",1));
                                xmlwriter_end_element($xml);//reporterorganization
                                xmlwriter_start_element($xml, 'reporterdepartment');//reporterdepartment
                                    xmlwriter_text($xml,$this->getValue($caseId, "reporterdepartment",1));
                                xmlwriter_end_element($xml);//reporterdepartment
                                xmlwriter_start_element($xml, 'reporterstreet');//reporterstreet
                                    xmlwriter_text($xml,$this->getValue($caseId, "reporterstreet",1));
                                xmlwriter_end_element($xml);//reporterstreet
                                xmlwriter_start_element($xml, 'reportercity');//reportercity  
                                    xmlwriter_text($xml,$this->getValue($caseId, "reportercity",1));
                                xmlwriter_end_element($xml);//reportercity
                                xmlwriter_start_element($xml, 'reporterstate');//reporterstate
                                    xmlwriter_text($xml,$this->getValue($caseId, "reporterstate",1));
                                xmlwriter_end_element($xml);//reporterstate
                                xmlwriter_start_element($xml, 'reporterpostcode');//reporterpostcode
                                    xmlwriter_text($xml,$this->getValue($caseId, "reporterpostcode",1));
                                xmlwriter_end_element($xml);//reporterpostcode
                                xmlwriter_start_element($xml, 'reportercountry');//reportercountry
                                    xmlwriter_text($xml,$this->getValue($caseId, "reportercountry",1));
                                xmlwriter_end_element($xml);//reportercountry
                                xmlwriter_start_element($xml, 'qualification');//qualification
                                    xmlwriter_text($xml,$this->getValue($caseId, "qualification",1));
                                xmlwriter_end_element($xml);//qualification
                                xmlwriter_start_element($xml, 'literaturereference');//literaturereference
                                    xmlwriter_text($xml,$this->getValue($caseId, "literaturereference",1));
                                xmlwriter_end_element($xml);//literaturereference
                                xmlwriter_start_element($xml, 'studyname');//studyname
                                    xmlwriter_text($xml,$this->getValue($caseId, "studyname",1));
                                xmlwriter_end_element($xml);//studyname
                                xmlwriter_start_element($xml, 'sponsorstudynumb');//sponsorstudynumb
                                    xmlwriter_text($xml,$this->getValue($caseId, "sponsorstudynumb",1));
                                xmlwriter_end_element($xml);//sponsorstudynumb
                                xmlwriter_start_element($xml, 'observestudytype');//observestudytype
                                    xmlwriter_text($xml,$this->getValue($caseId, "observestudytype",1));
                                xmlwriter_end_element($xml);//observestudytype
                            xmlwriter_end_element($xml); //primarysource
                            //primarysource
                            xmlwriter_start_element($xml, 'primarysource');//primarysource
                                xmlwriter_start_element($xml, 'reportertitle');//reportertitle
                                    xmlwriter_text($xml,$this->getValue($caseId, "reportertitle",2));
                                xmlwriter_end_element($xml);//reportertitle
                                xmlwriter_start_element($xml, 'reportergivename');//reportergivename
                                    xmlwriter_text($xml,$this->getValue($caseId, "reportergivename",2));
                                xmlwriter_end_element($xml);//reportergivename
                                xmlwriter_start_element($xml, 'reportermiddlename');//reportermiddlename
                                    xmlwriter_text($xml,$this->getValue($caseId, "reportermiddlename",2));
                                xmlwriter_end_element($xml);//reportermiddlename
                                xmlwriter_start_element($xml, 'reporterfamilyname');//reporterfamilyname
                                    xmlwriter_text($xml,$this->getValue($caseId, "reporterfamilyname",2));
                                xmlwriter_end_element($xml);//reporterfamilyname
                                xmlwriter_start_element($xml, 'reporterorganization');//reporterorganization
                                    xmlwriter_text($xml,$this->getValue($caseId, "reporterorganization",2));
                                xmlwriter_end_element($xml);//reporterorganization
                                xmlwriter_start_element($xml, 'reporterdepartment');//reporterdepartment
                                    xmlwriter_text($xml,$this->getValue($caseId, "reporterdepartment",2));
                                xmlwriter_end_element($xml);//reporterdepartment
                                xmlwriter_start_element($xml, 'reporterstreet');//reporterstreet
                                    xmlwriter_text($xml,$this->getValue($caseId, "reporterstreet",2));
                                xmlwriter_end_element($xml);//reporterstreet
                                xmlwriter_start_element($xml, 'reportercity');//reportercity  
                                    xmlwriter_text($xml,$this->getValue($caseId, "reportercity",2));
                                xmlwriter_end_element($xml);//reportercity
                                xmlwriter_start_element($xml, 'reporterstate');//reporterstate
                                    xmlwriter_text($xml,$this->getValue($caseId, "reporterstate",2));
                                xmlwriter_end_element($xml);//reporterstate
                                xmlwriter_start_element($xml, 'reporterpostcode');//reporterpostcode
                                    xmlwriter_text($xml,$this->getValue($caseId, "reporterpostcode",2));
                                xmlwriter_end_element($xml);//reporterpostcode
                                xmlwriter_start_element($xml, 'reportercountry');//reportercountry
                                    xmlwriter_text($xml,$this->getValue($caseId, "reportercountry",2));
                                xmlwriter_end_element($xml);//reportercountry
                                xmlwriter_start_element($xml, 'qualification');//qualification
                                    xmlwriter_text($xml,$this->getValue($caseId, "qualification",2));
                                xmlwriter_end_element($xml);//qualification
                                xmlwriter_start_element($xml, 'literaturereference');//literaturereference
                                    xmlwriter_text($xml,$this->getValue($caseId, "literaturereference",2));
                                xmlwriter_end_element($xml);//literaturereference
                                xmlwriter_start_element($xml, 'studyname');//studyname
                                    xmlwriter_text($xml,$this->getValue($caseId, "studyname",2));
                                xmlwriter_end_element($xml);//studyname
                                xmlwriter_start_element($xml, 'sponsorstudynumb');//sponsorstudynumb
                                    xmlwriter_text($xml,$this->getValue($caseId, "sponsorstudynumb",2));
                                xmlwriter_end_element($xml);//sponsorstudynumb
                                xmlwriter_start_element($xml, 'observestudytype');//observestudytype
                                    xmlwriter_text($xml,$this->getValue($caseId, "observestudytype",2));
                                xmlwriter_end_element($xml);//observestudytype
                            xmlwriter_end_element($xml); //primarysource
                            //sender
                            xmlwriter_start_element($xml, 'sender');//sender
                                xmlwriter_start_element($xml, 'sendertype');//sendertype
                                    xmlwriter_text($xml,$this->getValue($caseId, "sendertype",1));
                                xmlwriter_end_element($xml);//sendertype
                                xmlwriter_start_element($xml, 'senderorganization');//senderorganization
                                    xmlwriter_text($xml,$this->getValue($caseId, "senderorganization",1));
                                xmlwriter_end_element($xml);//senderorganization
                                xmlwriter_start_element($xml, 'senderdepartment');//senderdepartment
                                    xmlwriter_text($xml,$this->getValue($caseId, "senderdepartment",1));
                                xmlwriter_end_element($xml);//senderdepartment
                                xmlwriter_start_element($xml, 'sendertitle');//sendertitle
                                    xmlwriter_text($xml,$this->getValue($caseId, "sendertitle",1));
                                xmlwriter_end_element($xml);//sendertitle
                                xmlwriter_start_element($xml, 'sendergivename');//sendergivename
                                    xmlwriter_text($xml,$this->getValue($caseId, "sendergivename",1));
                                xmlwriter_end_element($xml);//sendergivename
                                xmlwriter_start_element($xml, 'sendermiddlename');//sendermiddlename
                                    xmlwriter_text($xml,$this->getValue($caseId, "sendermiddlename",1));
                                xmlwriter_end_element($xml);//sendermiddlename
                                xmlwriter_start_element($xml, 'senderfamilyname');//senderfamilyname
                                    xmlwriter_text($xml,$this->getValue($caseId, "senderfamilyname",1));
                                xmlwriter_end_element($xml);//senderfamilyname
                                xmlwriter_start_element($xml, 'senderstreetaddress');//senderstreetaddress
                                    xmlwriter_text($xml,$this->getValue($caseId, "senderstreetaddress",1));
                                xmlwriter_end_element($xml);//senderstreetaddress
                                xmlwriter_start_element($xml, 'sendercity');//sendercity
                                    xmlwriter_text($xml,$this->getValue($caseId, "sendercity",1));
                                xmlwriter_end_element($xml);//sendercity
                                xmlwriter_start_element($xml, 'senderstate');//senderstate
                                    xmlwriter_text($xml,$this->getValue($caseId, "senderstate",1));
                                xmlwriter_end_element($xml);//senderstate
                                xmlwriter_start_element($xml, 'senderpostcode');//senderpostcode
                                    xmlwriter_text($xml,$this->getValue($caseId, "senderpostcode",1));
                                xmlwriter_end_element($xml);//senderpostcode
                                xmlwriter_start_element($xml, 'sendercountrycode');//sendercountrycode
                                    xmlwriter_text($xml,$this->getValue($caseId, "sendercountrycode",1));
                                xmlwriter_end_element($xml);//sendercountrycode
                                xmlwriter_start_element($xml, 'sendertel');//sendertel
                                    xmlwriter_text($xml,$this->getValue($caseId, "sendertel",1));
                                xmlwriter_end_element($xml);//sendertel
                                xmlwriter_start_element($xml, 'sendertelextension');//sendertelextension
                                    xmlwriter_text($xml,$this->getValue($caseId, "sendertelextension",1));
                                xmlwriter_end_element($xml);//sendertelextension
                                xmlwriter_start_element($xml, 'sendertelcountrycode');//sendertelcountrycode
                                    xmlwriter_text($xml,$this->getValue($caseId, "sendertelcountrycode",1));
                                xmlwriter_end_element($xml);//sendertelcountrycode
                                xmlwriter_start_element($xml, 'senderfax');//senderfax
                                    xmlwriter_text($xml,$this->getValue($caseId, "senderfax",1));
                                xmlwriter_end_element($xml);//senderfax
                                xmlwriter_start_element($xml, 'senderfaxextension');//senderfaxextension
                                    xmlwriter_text($xml,$this->getValue($caseId, "senderfaxextension",1));
                                xmlwriter_end_element($xml);//senderfaxextension
                                xmlwriter_start_element($xml, 'senderfaxcountrycode');//senderfaxcountrycode
                                    xmlwriter_text($xml,$this->getValue($caseId, "senderfaxcountrycode",1));
                                xmlwriter_end_element($xml);//senderfaxcountrycode
                                xmlwriter_start_element($xml, 'senderemailaddress');//senderemailaddress
                                    xmlwriter_text($xml,$this->getValue($caseId, "senderemailaddress",1));
                                xmlwriter_end_element($xml);//senderemailaddress
                            xmlwriter_end_element($xml); //sender
                            //receiver
                            xmlwriter_start_element($xml, 'receiver');//receiver
                                xmlwriter_start_element($xml, 'receivertype');//receivertype
                                    xmlwriter_text($xml,$this->getValue($caseId, "receivertype",1));
                                xmlwriter_end_element($xml);//receivertype
                                xmlwriter_start_element($xml, 'receiverorganization');//receiverorganization
                                    xmlwriter_text($xml,$this->getValue($caseId, "receiverorganization",1));
                                xmlwriter_end_element($xml);//receiverorganization
                                xmlwriter_start_element($xml, 'receiverdepartment');//receiverdepartment
                                    xmlwriter_text($xml,$this->getValue($caseId, "receiverdepartment",1));
                                xmlwriter_end_element($xml);//receiverdepartment
                                xmlwriter_start_element($xml, 'receivertitle');//receivertitle
                                    xmlwriter_text($xml,$this->getValue($caseId, "receivertitle",1));
                                xmlwriter_end_element($xml);//receivertitle
                                xmlwriter_start_element($xml, 'receivergivename');//receivergivename
                                    xmlwriter_text($xml,$this->getValue($caseId, "receivergivename",1));
                                xmlwriter_end_element($xml);//receivergivename
                                xmlwriter_start_element($xml, 'receivermiddlename');//receivermiddlename
                                    xmlwriter_text($xml,$this->getValue($caseId, "receivermiddlename",1));
                                xmlwriter_end_element($xml);//receivermiddlename
                                xmlwriter_start_element($xml, 'receiverfamilyname');//receiverfamilyname
                                    xmlwriter_text($xml,$this->getValue($caseId, "receiverfamilyname",1));
                                xmlwriter_end_element($xml);//receiverfamilyname
                                xmlwriter_start_element($xml, 'receiverstreetaddress');//receiverstreetaddress
                                    xmlwriter_text($xml,$this->getValue($caseId, "receiverstreetaddress",1));
                                xmlwriter_end_element($xml);//receiverstreetaddress
                                xmlwriter_start_element($xml, 'receivercity');//receivercity
                                    xmlwriter_text($xml,$this->getValue($caseId, "receivercity",1));
                                xmlwriter_end_element($xml);//receivercity
                                xmlwriter_start_element($xml, 'receiverstate');//receiverstate
                                    xmlwriter_text($xml,$this->getValue($caseId, "receiverstate",1));
                                xmlwriter_end_element($xml);//receiverstate
                                xmlwriter_start_element($xml, 'receiverpostcode');//receiverpostcode
                                    xmlwriter_text($xml,$this->getValue($caseId, "receiverpostcode",1));
                                xmlwriter_end_element($xml);//receiverpostcode
                                xmlwriter_start_element($xml, 'receivercountrycode');//receivercountrycode
                                    xmlwriter_text($xml,$this->getValue($caseId, "receivercountrycode",1));
                                xmlwriter_end_element($xml);//receivercountrycode
                                xmlwriter_start_element($xml, 'receivertel');//receivertel
                                    xmlwriter_text($xml,$this->getValue($caseId, "receivertel",1));
                                xmlwriter_end_element($xml);//receivertel
                                xmlwriter_start_element($xml, 'receivertelextension');//receivertelextension
                                    xmlwriter_text($xml,$this->getValue($caseId, "receivertelextension",1));
                                xmlwriter_end_element($xml);//receivertelextension
                                xmlwriter_start_element($xml, 'receivertelcountrycode');//receivertelcountrycode
                                    xmlwriter_text($xml,$this->getValue($caseId, "receivertelcountrycode",1));
                                xmlwriter_end_element($xml);//receivertelcountrycode
                                xmlwriter_start_element($xml, 'receiverfax');//receiverfax
                                    xmlwriter_text($xml,$this->getValue($caseId, "receiverfax",1));
                                xmlwriter_end_element($xml);//receiverfax
                                xmlwriter_start_element($xml, 'receiverfaxextension');//receiverfaxextension
                                    xmlwriter_text($xml,$this->getValue($caseId, "receiverfaxextension",1));
                                xmlwriter_end_element($xml);//receiverfaxextension
                                xmlwriter_start_element($xml, 'receiverfaxcountrycode');//receiverfaxcountrycode
                                    xmlwriter_text($xml,$this->getValue($caseId, "receiverfaxcountrycode",1));
                                xmlwriter_end_element($xml);//receiverfaxcountrycode
                                xmlwriter_start_element($xml, 'receiveremailaddress');//receiveremailaddress
                                    xmlwriter_text($xml,$this->getValue($caseId, "receiveremailaddress",1));
                                xmlwriter_end_element($xml);//receiveremailaddress
                            xmlwriter_end_element($xml); //receiver
                            //patient
                            xmlwriter_start_element($xml, 'patient');//patient
                                xmlwriter_start_element($xml, 'patientinitial');//patientinitial
                                    xmlwriter_text($xml,$this->getValue($caseId, "patientinitial",1));
                                xmlwriter_end_element($xml);//patientinitial
                                xmlwriter_start_element($xml, 'patientgpmedicalrecordnumb');//patientgpmedicalrecordnumb
                                    xmlwriter_text($xml,$this->getValue($caseId, "patientgpmedicalrecordnumb",1));
                                xmlwriter_end_element($xml);//patientgpmedicalrecordnumb
                                xmlwriter_start_element($xml, 'patientspecialistrecordnumb');//patientspecialistrecordnumb
                                    xmlwriter_text($xml,$this->getValue($caseId, "patientspecialistrecordnumb",1));
                                xmlwriter_end_element($xml);//patientspecialistrecordnumb
                                xmlwriter_start_element($xml, 'patienthospitalrecordnumb');//patienthospitalrecordnumb
                                    xmlwriter_text($xml,$this->getValue($caseId, "patienthospitalrecordnumb",1));
                                xmlwriter_end_element($xml);//patienthospitalrecordnumb
                                xmlwriter_start_element($xml, 'patientinvestigationnumb');//patientinvestigationnumb
                                    xmlwriter_text($xml,$this->getValue($caseId, "patientinvestigationnumb",1));
                                xmlwriter_end_element($xml);//patientinvestigationnumb
                                xmlwriter_start_element($xml, 'patientbirthdateformat');//patientbirthdateformat
                                    xmlwriter_text($xml,$this->getValue($caseId, "patientbirthdateformat",1));
                                xmlwriter_end_element($xml);//patientbirthdateformat
                                xmlwriter_start_element($xml, 'patientbirthdate');//patientbirthdate
                                    xmlwriter_text($xml,$this->getValue($caseId, "patientbirthdate",1));
                                xmlwriter_end_element($xml);//patientbirthdate
                                xmlwriter_start_element($xml, 'patientonsetage');//patientonsetage
                                    xmlwriter_text($xml,$this->getValue($caseId, "patientonsetage",1));
                                xmlwriter_end_element($xml);//patientonsetage
                                xmlwriter_start_element($xml, 'patientonsetageunit');//patientonsetageunit
                                    xmlwriter_text($xml,$this->getValue($caseId, "patientonsetageunit",1));
                                xmlwriter_end_element($xml);//patientonsetageunit
                                xmlwriter_start_element($xml, 'gestationperiod');//gestationperiod
                                    xmlwriter_text($xml,$this->getValue($caseId, "gestationperiod",1));
                                xmlwriter_end_element($xml);//gestationperiod
                                xmlwriter_start_element($xml, 'gestationperiodunit');//gestationperiodunit
                                    xmlwriter_text($xml,$this->getValue($caseId, "gestationperiodunit",1));
                                xmlwriter_end_element($xml);//gestationperiodunit
                                xmlwriter_start_element($xml, 'patientagegroup');//patientagegroup
                                    xmlwriter_text($xml,$this->getValue($caseId, "patientagegroup",1));
                                xmlwriter_end_element($xml);//patientagegroup
                                xmlwriter_start_element($xml, 'patientweight');//patientweight
                                    xmlwriter_text($xml,$this->getValue($caseId, "patientweight",1));
                                xmlwriter_end_element($xml);//patientweight
                                xmlwriter_start_element($xml, 'patientheight');//patientheight
                                    xmlwriter_text($xml,$this->getValue($caseId, "patientheight",1));
                                xmlwriter_end_element($xml);//patientheight
                                xmlwriter_start_element($xml, 'patientsex');//patientsex
                                    xmlwriter_text($xml,$this->getValue($caseId, "patientsex",1));
                                xmlwriter_end_element($xml);//patientsex
                                xmlwriter_start_element($xml, 'lastmenstrualdateformat');//lastmenstrualdateformat
                                    xmlwriter_text($xml,$this->getValue($caseId, "lastmenstrualdateformat",1));
                                xmlwriter_end_element($xml);//lastmenstrualdateformat
                                xmlwriter_start_element($xml, 'patientlastmenstrualdate');//patientlastmenstrualdate
                                    xmlwriter_text($xml,$this->getValue($caseId, "patientlastmenstrualdate",1));
                                xmlwriter_end_element($xml);//patientlastmenstrualdate
                                xmlwriter_start_element($xml, 'patientmedicalhistorytext');//patientmedicalhistorytext
                                    xmlwriter_text($xml,$this->getValue($caseId, "patientmedicalhistorytext",1));
                                xmlwriter_end_element($xml);//patientmedicalhistorytext
                                xmlwriter_start_element($xml, 'resultstestsprocedures');//resultstestsprocedures
                                    xmlwriter_text($xml,$this->getValue($caseId, "resultstestsprocedures",1));
                                xmlwriter_end_element($xml);//resultstestsprocedures
                                //medicalhistoryepisode
                                xmlwriter_start_element($xml, 'medicalhistoryepisode');//medicalhistoryepisode
                                    xmlwriter_start_element($xml, 'patientepisodenamemeddraversion');//patientepisodenamemeddraversion
                                        xmlwriter_text($xml,$this->getValue($caseId, "patientepisodenamemeddraversion",1));
                                    xmlwriter_end_element($xml);//patientepisodenamemeddraversion
                                    xmlwriter_start_element($xml, 'patientepisodename');//patientepisodename
                                        xmlwriter_text($xml,$this->getValue($caseId, "patientepisodename",1));
                                    xmlwriter_end_element($xml);//patientepisodename
                                    xmlwriter_start_element($xml, 'patientmedicalstartdateformat');//patientmedicalstartdateformat
                                        xmlwriter_text($xml,$this->getValue($caseId, "patientmedicalstartdateformat",1));
                                    xmlwriter_end_element($xml);//patientmedicalstartdateformat
                                    xmlwriter_start_element($xml, 'patientmedicalstartdate');//patientmedicalstartdate
                                        xmlwriter_text($xml,$this->getValue($caseId, "patientmedicalstartdate",1));
                                    xmlwriter_end_element($xml);//patientmedicalstartdate
                                    xmlwriter_start_element($xml, 'patientmedicalcontinue');//patientmedicalcontinue
                                        xmlwriter_text($xml,$this->getValue($caseId, "patientmedicalcontinue",1));
                                    xmlwriter_end_element($xml);//patientmedicalcontinue
                                    xmlwriter_start_element($xml, 'patientmedicalenddateformat');//patientmedicalenddateformat
                                        xmlwriter_text($xml,$this->getValue($caseId, "patientmedicalenddateformat",1));
                                    xmlwriter_end_element($xml);//patientmedicalenddateformat
                                    xmlwriter_start_element($xml, 'patientmedicalenddate');//patientmedicalenddate
                                        xmlwriter_text($xml,$this->getValue($caseId, "patientmedicalenddate",1));
                                    xmlwriter_end_element($xml);//patientmedicalenddate
                                    xmlwriter_start_element($xml, 'patientmedicalcomment');//patientmedicalcomment
                                        xmlwriter_text($xml,$this->getValue($caseId, "patientmedicalcomment",1));
                                    xmlwriter_end_element($xml);//patientmedicalcomment
                                xmlwriter_end_element($xml);//medicalhistoryepisode
                                 //medicalhistoryepisode
                                xmlwriter_start_element($xml, 'medicalhistoryepisode');//medicalhistoryepisode
                                    xmlwriter_start_element($xml, 'patientepisodenamemeddraversion');//patientepisodenamemeddraversion
                                        xmlwriter_text($xml,$this->getValue($caseId, "patientepisodenamemeddraversion",2));
                                    xmlwriter_end_element($xml);//patientepisodenamemeddraversion
                                    xmlwriter_start_element($xml, 'patientepisodename');//patientepisodename
                                        xmlwriter_text($xml,$this->getValue($caseId, "patientepisodename",2));
                                    xmlwriter_end_element($xml);//patientepisodename
                                    xmlwriter_start_element($xml, 'patientmedicalstartdateformat');//patientmedicalstartdateformat
                                        xmlwriter_text($xml,$this->getValue($caseId, "patientmedicalstartdateformat",2));
                                    xmlwriter_end_element($xml);//patientmedicalstartdateformat
                                    xmlwriter_start_element($xml, 'patientmedicalstartdate');//patientmedicalstartdate
                                        xmlwriter_text($xml,$this->getValue($caseId, "patientmedicalstartdate",2));
                                    xmlwriter_end_element($xml);//patientmedicalstartdate
                                    xmlwriter_start_element($xml, 'patientmedicalcontinue');//patientmedicalcontinue
                                        xmlwriter_text($xml,$this->getValue($caseId, "patientmedicalcontinue",2));
                                    xmlwriter_end_element($xml);//patientmedicalcontinue
                                    xmlwriter_start_element($xml, 'patientmedicalenddateformat');//patientmedicalenddateformat
                                        xmlwriter_text($xml,$this->getValue($caseId, "patientmedicalenddateformat",2));
                                    xmlwriter_end_element($xml);//patientmedicalenddateformat
                                    xmlwriter_start_element($xml, 'patientmedicalenddate');//patientmedicalenddate
                                        xmlwriter_text($xml,$this->getValue($caseId, "patientmedicalenddate",2));
                                    xmlwriter_end_element($xml);//patientmedicalenddate
                                    xmlwriter_start_element($xml, 'patientmedicalcomment');//patientmedicalcomment
                                        xmlwriter_text($xml,$this->getValue($caseId, "patientmedicalcomment",2));
                                    xmlwriter_end_element($xml);//patientmedicalcomment
                                xmlwriter_end_element($xml);//medicalhistoryepisode
                                //patientpastdrugtherapy
                                xmlwriter_start_element($xml, 'patientpastdrugtherapy');//patientpastdrugtherapy
                                    xmlwriter_start_element($xml, 'patientdrugname');//patientdrugname
                                        xmlwriter_text($xml,$this->getValue($caseId, "patientdrugname",1));
                                    xmlwriter_end_element($xml);//patientdrugname
                                    xmlwriter_start_element($xml, 'patientdrugstartdateformat');//patientdrugstartdateformat
                                        xmlwriter_text($xml,$this->getValue($caseId, "patientdrugstartdateformat",1));
                                    xmlwriter_end_element($xml);//patientdrugstartdateformat
                                    xmlwriter_start_element($xml, 'patientdrugstartdate');//patientdrugstartdate
                                        xmlwriter_text($xml,$this->getValue($caseId, "patientdrugstartdate",1));
                                    xmlwriter_end_element($xml);//patientdrugstartdate
                                    xmlwriter_start_element($xml, 'patientdrugenddateformat');//patientdrugenddateformat
                                        xmlwriter_text($xml,$this->getValue($caseId, "patientdrugenddateformat",1));
                                    xmlwriter_end_element($xml);//patientdrugenddateformat
                                    xmlwriter_start_element($xml, 'patientdrugenddate');//patientdrugenddate
                                        xmlwriter_text($xml,$this->getValue($caseId, "patientdrugenddate",1));
                                    xmlwriter_end_element($xml);//patientdrugenddate
                                    xmlwriter_start_element($xml, 'patientindicationmeddraversion');//patientindicationmeddraversion
                                        xmlwriter_text($xml,$this->getValue($caseId, "patientindicationmeddraversion",1));
                                    xmlwriter_end_element($xml);//patientindicationmeddraversion
                                    xmlwriter_start_element($xml, 'patientdrugindication');//patientdrugindication
                                        xmlwriter_text($xml,$this->getValue($caseId, "patientdrugindication",1));
                                    xmlwriter_end_element($xml);//patientdrugindication
                                    xmlwriter_start_element($xml, 'patientdrgreactionmeddraversion');//patientdrgreactionmeddraversion
                                        xmlwriter_text($xml,$this->getValue($caseId, "patientdrgreactionmeddraversion",1));
                                    xmlwriter_end_element($xml);//patientdrgreactionmeddraversion
                                    xmlwriter_start_element($xml, 'patientdrugreaction');//patientdrugreaction
                                        xmlwriter_text($xml,$this->getValue($caseId, "patientdrugreaction",1));
                                    xmlwriter_end_element($xml);//patientdrugreaction
                                xmlwriter_end_element($xml);//patientpastdrugtherapy
                                //patientpastdrugtherapy
                                xmlwriter_start_element($xml, 'patientpastdrugtherapy');//patientpastdrugtherapy
                                    xmlwriter_start_element($xml, 'patientdrugname');//patientdrugname
                                        xmlwriter_text($xml,$this->getValue($caseId, "patientdrugname",2));
                                    xmlwriter_end_element($xml);//patientdrugname
                                    xmlwriter_start_element($xml, 'patientdrugstartdateformat');//patientdrugstartdateformat
                                        xmlwriter_text($xml,$this->getValue($caseId, "patientdrugstartdateformat",2));
                                    xmlwriter_end_element($xml);//patientdrugstartdateformat
                                    xmlwriter_start_element($xml, 'patientdrugstartdate');//patientdrugstartdate
                                        xmlwriter_text($xml,$this->getValue($caseId, "patientdrugstartdate",2));
                                    xmlwriter_end_element($xml);//patientdrugstartdate
                                    xmlwriter_start_element($xml, 'patientdrugenddateformat');//patientdrugenddateformat
                                        xmlwriter_text($xml,$this->getValue($caseId, "patientdrugenddateformat",2));
                                    xmlwriter_end_element($xml);//patientdrugenddateformat
                                    xmlwriter_start_element($xml, 'patientdrugenddate');//patientdrugenddate
                                        xmlwriter_text($xml,$this->getValue($caseId, "patientdrugenddate",2));
                                    xmlwriter_end_element($xml);//patientdrugenddate
                                    xmlwriter_start_element($xml, 'patientindicationmeddraversion');//patientindicationmeddraversion
                                        xmlwriter_text($xml,$this->getValue($caseId, "patientindicationmeddraversion",2));
                                    xmlwriter_end_element($xml);//patientindicationmeddraversion
                                    xmlwriter_start_element($xml, 'patientdrugindication');//patientdrugindication
                                        xmlwriter_text($xml,$this->getValue($caseId, "patientdrugindication",2));
                                    xmlwriter_end_element($xml);//patientdrugindication
                                    xmlwriter_start_element($xml, 'patientdrgreactionmeddraversion');//patientdrgreactionmeddraversion
                                        xmlwriter_text($xml,$this->getValue($caseId, "patientdrgreactionmeddraversion",2));
                                    xmlwriter_end_element($xml);//patientdrgreactionmeddraversion
                                    xmlwriter_start_element($xml, 'patientdrugreaction');//patientdrugreaction
                                        xmlwriter_text($xml,$this->getValue($caseId, "patientdrugreaction",2));
                                    xmlwriter_end_element($xml);//patientdrugreaction
                                xmlwriter_end_element($xml);//patientpastdrugtherapy
                                //patientdeath
                                xmlwriter_start_element($xml, 'patientdeath');//patientdeath
                                    xmlwriter_start_element($xml, 'patientdeathdateformat');//patientdeathdateformat
                                        xmlwriter_text($xml,$this->getValue($caseId, "patientdeathdateformat",1));
                                    xmlwriter_end_element($xml);//patientdeathdateformat
                                    xmlwriter_start_element($xml, 'patientdeathdate');//patientdeathdate
                                        xmlwriter_text($xml,$this->getValue($caseId, "patientdeathdate",1));
                                    xmlwriter_end_element($xml);//patientdeathdate
                                    xmlwriter_start_element($xml, 'patientautopsyyesno');//patientautopsyyesno
                                        xmlwriter_text($xml,$this->getValue($caseId, "patientautopsyyesno",1));
                                    xmlwriter_end_element($xml);//patientautopsyyesno
                                   //patientdeathcause
                                    xmlwriter_start_element($xml, 'patientdeathcause');//patientdeathcause
                                        xmlwriter_start_element($xml, 'patientdeathreportmeddraversion');//patientdeathreportmeddraversion
                                            xmlwriter_text($xml,$this->getValue($caseId, "patientdeathreportmeddraversion",1));
                                        xmlwriter_end_element($xml);//patientdeathreportmeddraversion
                                        xmlwriter_start_element($xml, 'patientdeathreport');//patientdeathreport
                                            xmlwriter_text($xml,$this->getValue($caseId, "patientdeathreport",1));
                                        xmlwriter_end_element($xml);//patientdeathreport
                                    xmlwriter_end_element($xml);//patientdeathcause
                                    //patientdeathcause
                                    xmlwriter_start_element($xml, 'patientdeathcause');//patientdeathcause
                                        xmlwriter_start_element($xml, 'patientdeathreportmeddraversion');//patientdeathreportmeddraversion
                                            xmlwriter_text($xml,$this->getValue($caseId, "patientdeathreportmeddraversion",2));
                                        xmlwriter_end_element($xml);//patientdeathreportmeddraversion
                                        xmlwriter_start_element($xml, 'patientdeathreport');//patientdeathreport
                                            xmlwriter_text($xml,$this->getValue($caseId, "patientdeathreport",2));
                                        xmlwriter_end_element($xml);//patientdeathreport
                                    xmlwriter_end_element($xml);//patientdeathcause
                                    //patientautospy
                                    xmlwriter_start_element($xml, 'patientautospy');//patientautospy
                                        xmlwriter_start_element($xml, 'patientdetermautopsmeddraversion');
                                            xmlwriter_text($xml,$this->getValue($caseId, "patientautopsyyesno",1));
                                        xmlwriter_end_element($xml);
                                        xmlwriter_start_element($xml, 'patientdetermineautopsy');
                                            xmlwriter_text($xml,$this->getValue($caseId, "patientautopsyyesno",1));
                                        xmlwriter_end_element($xml);
                                    xmlwriter_end_element($xml);//patientautospy
                                     //patientautospy
                                    xmlwriter_start_element($xml, 'patientautospy');//patientautospy
                                        xmlwriter_start_element($xml, 'patientdetermautopsmeddraversion');//patientdetermautopsmeddraversion
                                            xmlwriter_text($xml,$this->getValue($caseId, "patientdetermautopsmeddraversion",2));
                                        xmlwriter_end_element($xml);//patientdetermautopsmeddraversion
                                        xmlwriter_start_element($xml, 'patientdetermineautopsy');//patientdetermineautopsy
                                            xmlwriter_text($xml,$this->getValue($caseId, "patientdetermineautopsy",2));
                                        xmlwriter_end_element($xml);//patientdetermineautopsy 
                                    xmlwriter_end_element($xml);//patientautospy
                                xmlwriter_end_element($xml);//patientdeath
                                //parent
                                xmlwriter_start_element($xml, 'parent');//parent
                                    xmlwriter_start_element($xml, 'parentidentification');//parentidentification
                                        xmlwriter_text($xml,$this->getValue($caseId, "parentidentification",1));
                                    xmlwriter_end_element($xml);//parentidentification
                                    xmlwriter_start_element($xml, 'parentage');//parentage
                                        xmlwriter_text($xml,$this->getValue($caseId, "parentage",1));
                                    xmlwriter_end_element($xml);//parentage
                                    xmlwriter_start_element($xml, 'parentageunit');//parentageunit
                                        xmlwriter_text($xml,$this->getValue($caseId, "parentageunit",1));
                                        xmlwriter_end_element($xml);//parentageunit
                                    xmlwriter_start_element($xml, 'parentlastmenstrualdateformat');//parentlastmenstrualdateformat
                                        xmlwriter_text($xml,$this->getValue($caseId, "parentlastmenstrualdateformat",1));
                                    xmlwriter_end_element($xml);//parentlastmenstrualdateformat
                                    xmlwriter_start_element($xml, 'parentlastmenstrualdate');//parentlastmenstrualdate
                                        xmlwriter_text($xml,$this->getValue($caseId, "parentlastmenstrualdate",1));
                                    xmlwriter_end_element($xml);//parentlastmenstrualdate
                                    xmlwriter_start_element($xml, 'parentweight');//parentweight
                                        xmlwriter_text($xml,$this->getValue($caseId, "parentweight",1));
                                    xmlwriter_end_element($xml);//parentweight
                                    xmlwriter_start_element($xml, 'parentheight');//parentheight
                                        xmlwriter_text($xml,$this->getValue($caseId, "parentheight",1));
                                    xmlwriter_end_element($xml);//parentheight
                                    xmlwriter_start_element($xml, 'parentsex');//parentsex
                                        xmlwriter_text($xml,$this->getValue($caseId, "parentsex",1));
                                    xmlwriter_end_element($xml);//parentsex
                                    xmlwriter_start_element($xml, 'parentmedicalrelevanttext');//parentmedicalrelevanttext
                                        xmlwriter_text($xml,$this->getValue($caseId, "parentmedicalrelevanttext",1));
                                    xmlwriter_end_element($xml);//parentmedicalrelevanttext
                                    //parentmedicalhistoryepisode
                                    xmlwriter_start_element($xml, 'parentmedicalhistoryepisode');//parentmedicalhistoryepisode
                                        xmlwriter_start_element($xml, 'parentmdepisodemeddraversion');//parentmdepisodemeddraversion
                                            xmlwriter_text($xml,$this->getValue($caseId, "parentmdepisodemeddraversion",1));
                                        xmlwriter_end_element($xml);//parentmdepisodemeddraversion
                                        xmlwriter_start_element($xml, 'parentmedicalepisodename');//parentmedicalepisodename
                                            xmlwriter_text($xml,$this->getValue($caseId, "parentmedicalepisodename",1));
                                        xmlwriter_end_element($xml);//parentmedicalepisodename
                                        xmlwriter_start_element($xml, 'parentmedicalstartdateformat');//parentmedicalstartdateformat
                                            xmlwriter_text($xml,$this->getValue($caseId, "parentmedicalstartdateformat",1));
                                        xmlwriter_end_element($xml);//parentmedicalstartdateformat
                                        xmlwriter_start_element($xml, 'parentmedicalstartdate');//parentmedicalstartdate
                                            xmlwriter_text($xml,$this->getValue($caseId, "parentmedicalstartdate",1));
                                        xmlwriter_end_element($xml);//parentmedicalstartdate
                                        xmlwriter_start_element($xml, 'parentmedicalcontinue');//parentmedicalcontinue
                                            xmlwriter_text($xml,$this->getValue($caseId, "parentmedicalcontinue",1));
                                        xmlwriter_end_element($xml);//parentmedicalcontinue
                                        xmlwriter_start_element($xml, 'parentmedicalenddateformat');//parentmedicalenddateformat
                                            xmlwriter_text($xml,$this->getValue($caseId, "parentmedicalenddateformat",1));
                                        xmlwriter_end_element($xml);//parentmedicalenddateformat
                                        xmlwriter_start_element($xml, 'parentmedicalenddate');//parentmedicalenddate
                                            xmlwriter_text($xml,$this->getValue($caseId, "parentmedicalenddate",1));
                                        xmlwriter_end_element($xml);//parentmedicalenddate
                                        xmlwriter_start_element($xml, 'parentmedicalcomment');//parentmedicalcomment
                                            xmlwriter_text($xml,$this->getValue($caseId, "parentmedicalcomment",1));
                                        xmlwriter_end_element($xml);//parentmedicalcomment
                                    xmlwriter_end_element($xml);//parentmedicalhistoryepisode
                                    //parentmedicalhistoryepisode
                                    xmlwriter_start_element($xml, 'parentmedicalhistoryepisode');//parentmedicalhistoryepisode
                                        xmlwriter_start_element($xml, 'parentmdepisodemeddraversion');//parentmdepisodemeddraversion
                                            xmlwriter_text($xml,$this->getValue($caseId, "parentmdepisodemeddraversion",2));
                                        xmlwriter_end_element($xml);//parentmdepisodemeddraversion
                                        xmlwriter_start_element($xml, 'parentmedicalepisodename');//parentmedicalepisodename
                                            xmlwriter_text($xml,$this->getValue($caseId, "parentmedicalepisodename",2));
                                        xmlwriter_end_element($xml);//parentmedicalepisodename
                                        xmlwriter_start_element($xml, 'parentmedicalstartdateformat');//parentmedicalstartdateformat
                                            xmlwriter_text($xml,$this->getValue($caseId, "parentmedicalstartdateformat",2));
                                        xmlwriter_end_element($xml);//parentmedicalstartdateformat
                                        xmlwriter_start_element($xml, 'parentmedicalstartdate');//parentmedicalstartdate
                                            xmlwriter_text($xml,$this->getValue($caseId, "parentmedicalstartdate",2));
                                        xmlwriter_end_element($xml);//parentmedicalstartdate
                                        xmlwriter_start_element($xml, 'parentmedicalcontinue');//parentmedicalcontinue
                                            xmlwriter_text($xml,$this->getValue($caseId, "parentmedicalcontinue",2));
                                        xmlwriter_end_element($xml);//parentmedicalcontinue
                                        xmlwriter_start_element($xml, 'parentmedicalenddateformat');//parentmedicalenddateformat
                                            xmlwriter_text($xml,$this->getValue($caseId, "parentmedicalenddateformat",2));
                                        xmlwriter_end_element($xml);//parentmedicalenddateformat
                                        xmlwriter_start_element($xml, 'parentmedicalenddate');//parentmedicalenddate
                                            xmlwriter_text($xml,$this->getValue($caseId, "parentmedicalenddate",2));
                                        xmlwriter_end_element($xml);//parentmedicalenddate
                                        xmlwriter_start_element($xml, 'parentmedicalcomment');//parentmedicalcomment
                                            xmlwriter_text($xml,$this->getValue($caseId, "parentmedicalcomment",2));
                                        xmlwriter_end_element($xml);//parentmedicalcomment
                                    xmlwriter_end_element($xml);//parentmedicalhistoryepisode
                                    //parentpastdrugtherapy
                                    xmlwriter_start_element($xml, 'parentpastdrugtherapy');//parentpastdrugtherapy
                                        xmlwriter_start_element($xml, 'parentdrugname');//parentdrugname
                                            xmlwriter_text($xml,$this->getValue($caseId, "parentdrugname",1));
                                        xmlwriter_end_element($xml);//parentdrugname
                                        xmlwriter_start_element($xml, 'parentdrugstartdateformat');//parentdrugstartdateformat
                                            xmlwriter_text($xml,$this->getValue($caseId, "parentdrugstartdateformat",1));
                                        xmlwriter_end_element($xml);//parentdrugstartdateformat
                                        xmlwriter_start_element($xml, 'parentdrugstartdate');//parentdrugstartdate
                                            xmlwriter_text($xml,$this->getValue($caseId, "parentdrugstartdate",1));
                                        xmlwriter_end_element($xml);//parentdrugstartdate
                                        xmlwriter_start_element($xml, 'parentdrugenddateformat');//parentdrugenddateformat
                                            xmlwriter_text($xml,$this->getValue($caseId, "parentdrugenddateformat",1));
                                        xmlwriter_end_element($xml);//parentdrugenddateformat
                                        xmlwriter_start_element($xml, 'parentdrugenddate');//parentdrugenddate
                                            xmlwriter_text($xml,$this->getValue($caseId, "parentdrugenddate",1));
                                        xmlwriter_end_element($xml);//parentdrugenddate
                                        xmlwriter_start_element($xml, 'parentdrgindicationmeddraversion');//parentdrgindicationmeddraversion
                                            xmlwriter_text($xml,$this->getValue($caseId, "parentdrgindicationmeddraversion",1));
                                        xmlwriter_end_element($xml);//parentdrgindicationmeddraversion
                                        xmlwriter_start_element($xml, 'parentdrugindication');//parentdrugindication
                                            xmlwriter_text($xml,$this->getValue($caseId, "parentdrugindication",1));
                                        xmlwriter_end_element($xml);//parentdrugindication
                                        xmlwriter_start_element($xml, 'parentdrgreactionmeddraversion');//parentdrgreactionmeddraversion
                                            xmlwriter_text($xml,$this->getValue($caseId, "parentdrgreactionmeddraversion",1));
                                        xmlwriter_end_element($xml);//parentdrgreactionmeddraversion
                                        xmlwriter_start_element($xml, 'parentdrugreaction');//parentdrugreaction
                                            xmlwriter_text($xml,$this->getValue($caseId, "parentdrugreaction",1));
                                        xmlwriter_end_element($xml);//parentdrugreaction
                                    xmlwriter_end_element($xml);//parentpastdrugtherapy
                                    //parentpastdrugtherapy
                                    xmlwriter_start_element($xml, 'parentpastdrugtherapy');//parentpastdrugtherapy
                                        xmlwriter_start_element($xml, 'parentdrugname');//parentdrugname
                                            xmlwriter_text($xml,$this->getValue($caseId, "parentdrugname",2));
                                        xmlwriter_end_element($xml);//parentdrugname
                                        xmlwriter_start_element($xml, 'parentdrugstartdateformat');//parentdrugstartdateformat
                                            xmlwriter_text($xml,$this->getValue($caseId, "parentdrugstartdateformat",2));
                                        xmlwriter_end_element($xml);//parentdrugstartdateformat
                                        xmlwriter_start_element($xml, 'parentdrugstartdate');//parentdrugstartdate
                                            xmlwriter_text($xml,$this->getValue($caseId, "parentdrugstartdate",2));
                                        xmlwriter_end_element($xml);//parentdrugstartdate
                                        xmlwriter_start_element($xml, 'parentdrugenddateformat');//parentdrugenddateformat
                                            xmlwriter_text($xml,$this->getValue($caseId, "parentdrugenddateformat",2));
                                        xmlwriter_end_element($xml);//parentdrugenddateformat
                                        xmlwriter_start_element($xml, 'parentdrugenddate');//parentdrugenddate
                                            xmlwriter_text($xml,$this->getValue($caseId, "parentdrugenddate",2));
                                        xmlwriter_end_element($xml);//parentdrugenddate
                                        xmlwriter_start_element($xml, 'parentdrgindicationmeddraversion');//parentdrgindicationmeddraversion
                                            xmlwriter_text($xml,$this->getValue($caseId, "parentdrgindicationmeddraversion",2));
                                        xmlwriter_end_element($xml);//parentdrgindicationmeddraversion
                                        xmlwriter_start_element($xml, 'parentdrugindication');//parentdrugindication
                                            xmlwriter_text($xml,$this->getValue($caseId, "parentdrugindication",2));
                                        xmlwriter_end_element($xml);//parentdrugindication
                                        xmlwriter_start_element($xml, 'parentdrgreactionmeddraversion');//parentdrgreactionmeddraversion
                                            xmlwriter_text($xml,$this->getValue($caseId, "parentdrgreactionmeddraversion",2));
                                        xmlwriter_end_element($xml);//parentdrgreactionmeddraversion
                                        xmlwriter_start_element($xml, 'parentdrugreaction');//parentdrugreaction
                                            xmlwriter_text($xml,$this->getValue($caseId, "parentdrugreaction",2));
                                        xmlwriter_end_element($xml);//parentdrugreaction
                                    xmlwriter_end_element($xml);//parentpastdrugtherapy
                            xmlwriter_end_element($xml);//parent
                                //reaction
                                xmlwriter_start_element($xml, 'reaction');//reaction
                                    xmlwriter_start_element($xml, 'primarysourcereaction');//primarysourcereaction
                                        xmlwriter_text($xml,$this->getValue($caseId, "primarysourcereaction",1));
                                    xmlwriter_end_element($xml);//primarysourcereaction
                                    xmlwriter_start_element($xml, 'reactionmeddraversionllt');//reactionmeddraversionllt
                                        xmlwriter_text($xml,$this->getValue($caseId, "reactionmeddraversionllt",1));
                                    xmlwriter_end_element($xml);//reactionmeddraversionllt
                                    xmlwriter_start_element($xml, 'reactionmeddrallt');//reactionmeddrallt
                                        xmlwriter_text($xml,$this->getValue($caseId, "reactionmeddrallt",1));
                                    xmlwriter_end_element($xml);//reactionmeddrallt
                                    xmlwriter_start_element($xml, 'reactionmeddraversionpt');//reactionmeddraversionpt
                                        xmlwriter_text($xml,$this->getValue($caseId, "reactionmeddraversionpt",1));
                                    xmlwriter_end_element($xml);//reactionmeddraversionpt
                                    xmlwriter_start_element($xml, 'reactionmeddrapt');//reactionmeddrapt
                                        xmlwriter_text($xml,$this->getValue($caseId, "reactionmeddrapt",1));
                                    xmlwriter_end_element($xml);//reactionmeddrapt
                                    xmlwriter_start_element($xml, 'termhighlighted');//termhighlighted
                                        xmlwriter_text($xml,$this->getValue($caseId, "termhighlighted",1));
                                    xmlwriter_end_element($xml);//termhighlighted
                                    xmlwriter_start_element($xml, 'reactionstartdateformat');//reactionstartdateformat
                                        xmlwriter_text($xml,$this->getValue($caseId, "reactionstartdateformat",1));
                                    xmlwriter_end_element($xml);//reactionstartdateformat
                                    xmlwriter_start_element($xml, 'reactionstartdate');//reactionstartdate
                                        xmlwriter_text($xml,$this->getValue($caseId, "reactionstartdate",1));
                                    xmlwriter_end_element($xml);//reactionstartdate
                                    xmlwriter_start_element($xml, 'reactionenddateformat');//reactionenddateformat
                                        xmlwriter_text($xml,$this->getValue($caseId, "reactionenddateformat",1));
                                    xmlwriter_end_element($xml);//reactionenddateformat
                                    xmlwriter_start_element($xml, 'reactionenddate');//reactionenddate
                                        xmlwriter_text($xml,$this->getValue($caseId, "reactionenddate",1));
                                    xmlwriter_end_element($xml);//reactionenddate
                                    xmlwriter_start_element($xml, 'reactionduration');//reactionduration
                                        xmlwriter_text($xml,$this->getValue($caseId, "reactionduration",1));
                                    xmlwriter_end_element($xml);//reactionduration
                                    xmlwriter_start_element($xml, 'reactiondurationunit');//reactiondurationunit
                                        xmlwriter_text($xml,$this->getValue($caseId, "reactiondurationunit",1));
                                    xmlwriter_end_element($xml);//reactiondurationunit
                                    xmlwriter_start_element($xml, 'reactionfirsttime');//reactionfirsttime
                                        xmlwriter_text($xml,$this->getValue($caseId, "reactionfirsttime",1));
                                    xmlwriter_end_element($xml);//reactionfirsttime
                                    xmlwriter_start_element($xml, 'reactionfirsttimeunit');//reactionfirsttimeunit
                                        xmlwriter_text($xml,$this->getValue($caseId, "reactionfirsttimeunit",1));
                                    xmlwriter_end_element($xml);//reactionfirsttimeunit
                                    xmlwriter_start_element($xml, 'reactionlasttime');//reactionlasttime
                                        xmlwriter_text($xml,$this->getValue($caseId, "reactionlasttime",1));
                                    xmlwriter_end_element($xml);//reactionlasttime
                                    xmlwriter_start_element($xml, 'reactionlasttimeunit');//reactionlasttimeunit
                                        xmlwriter_text($xml,$this->getValue($caseId, "reactionlasttimeunit",1));
                                    xmlwriter_end_element($xml);//reactionlasttimeuni
                                    xmlwriter_start_element($xml, 'reactionoutcome');//reactionoutcome
                                        xmlwriter_text($xml,$this->getValue($caseId, "reactionoutcome",1));
                                    xmlwriter_end_element($xml);//reactionoutcome
                                xmlwriter_end_element($xml);//reaction
                                 //reaction
                                xmlwriter_start_element($xml, 'reaction');//reaction
                                    xmlwriter_start_element($xml, 'primarysourcereaction');//primarysourcereaction
                                        xmlwriter_text($xml,$this->getValue($caseId, "primarysourcereaction",2));
                                    xmlwriter_end_element($xml);//primarysourcereaction
                                    xmlwriter_start_element($xml, 'reactionmeddraversionllt');//reactionmeddraversionllt
                                        xmlwriter_text($xml,$this->getValue($caseId, "reactionmeddraversionllt",2));
                                    xmlwriter_end_element($xml);//reactionmeddraversionllt
                                    xmlwriter_start_element($xml, 'reactionmeddrallt');//reactionmeddrallt
                                        xmlwriter_text($xml,$this->getValue($caseId, "reactionmeddrallt",2));
                                    xmlwriter_end_element($xml);//reactionmeddrallt
                                    xmlwriter_start_element($xml, 'reactionmeddraversionpt');//reactionmeddraversionpt
                                        xmlwriter_text($xml,$this->getValue($caseId, "reactionmeddraversionpt",2));
                                    xmlwriter_end_element($xml);//reactionmeddraversionpt
                                    xmlwriter_start_element($xml, 'reactionmeddrapt');//reactionmeddrapt
                                        xmlwriter_text($xml,$this->getValue($caseId, "reactionmeddrapt",2));
                                    xmlwriter_end_element($xml);//reactionmeddrapt
                                    xmlwriter_start_element($xml, 'termhighlighted');//termhighlighted
                                        xmlwriter_text($xml,$this->getValue($caseId, "termhighlighted",2));
                                    xmlwriter_end_element($xml);//termhighlighted
                                    xmlwriter_start_element($xml, 'reactionstartdateformat');//reactionstartdateformat
                                        xmlwriter_text($xml,$this->getValue($caseId, "reactionstartdateformat",2));
                                    xmlwriter_end_element($xml);//reactionstartdateformat
                                    xmlwriter_start_element($xml, 'reactionstartdate');//reactionstartdate
                                        xmlwriter_text($xml,$this->getValue($caseId, "reactionstartdate",2));
                                    xmlwriter_end_element($xml);//reactionstartdate
                                    xmlwriter_start_element($xml, 'reactionenddateformat');//reactionenddateformat
                                        xmlwriter_text($xml,$this->getValue($caseId, "reactionenddateformat",2));
                                    xmlwriter_end_element($xml);//reactionenddateformat
                                    xmlwriter_start_element($xml, 'reactionenddate');//reactionenddate
                                        xmlwriter_text($xml,$this->getValue($caseId, "reactionenddate",2));
                                    xmlwriter_end_element($xml);//reactionenddate
                                    xmlwriter_start_element($xml, 'reactionduration');//reactionduration
                                        xmlwriter_text($xml,$this->getValue($caseId, "reactionduration",2));
                                    xmlwriter_end_element($xml);//reactionduration
                                    xmlwriter_start_element($xml, 'reactiondurationunit');//reactiondurationunit
                                        xmlwriter_text($xml,$this->getValue($caseId, "reactiondurationunit",2));
                                    xmlwriter_end_element($xml);//reactiondurationunit
                                    xmlwriter_start_element($xml, 'reactionfirsttime');//reactionfirsttime
                                        xmlwriter_text($xml,$this->getValue($caseId, "reactionfirsttime",2));
                                    xmlwriter_end_element($xml);//reactionfirsttime
                                    xmlwriter_start_element($xml, 'reactionfirsttimeunit');//reactionfirsttimeunit
                                        xmlwriter_text($xml,$this->getValue($caseId, "reactionfirsttimeunit",2));
                                    xmlwriter_end_element($xml);//reactionfirsttimeunit
                                    xmlwriter_start_element($xml, 'reactionlasttime');//reactionlasttime
                                        xmlwriter_text($xml,$this->getValue($caseId, "reactionlasttime",2));
                                    xmlwriter_end_element($xml);//reactionlasttime
                                    xmlwriter_start_element($xml, 'reactionlasttimeunit');//reactionlasttimeunit
                                        xmlwriter_text($xml,$this->getValue($caseId, "reactionlasttimeunit",2));
                                    xmlwriter_end_element($xml);//reactionlasttimeunit
                                    xmlwriter_start_element($xml, 'reactionoutcome');//reactionoutcome
                                        xmlwriter_text($xml,$this->getValue($caseId, "reactionoutcome",2));
                                    xmlwriter_end_element($xml);//reactionoutcome
                                xmlwriter_end_element($xml);//reaction
                                //test
                                xmlwriter_start_element($xml, 'test');//test
                                    xmlwriter_start_element($xml, 'testdateformat');//testdateformat
                                        xmlwriter_text($xml,$this->getValue($caseId, "testdateformat",1));
                                    xmlwriter_end_element($xml);//testdateformat
                                    xmlwriter_start_element($xml, 'testdate');//testdate
                                        xmlwriter_text($xml,$this->getValue($caseId, "testdate",1));
                                    xmlwriter_end_element($xml);//testdate
                                    xmlwriter_start_element($xml, 'testname');//testname
                                        xmlwriter_text($xml,$this->getValue($caseId, "testname",1));
                                    xmlwriter_end_element($xml);//testname
                                    xmlwriter_start_element($xml, 'testresult');//testresult
                                        xmlwriter_text($xml,$this->getValue($caseId, "testresult",1));
                                    xmlwriter_end_element($xml);//testresult
                                    xmlwriter_start_element($xml, 'testunit');//testunit
                                        xmlwriter_text($xml,$this->getValue($caseId, "testunit",1));
                                    xmlwriter_end_element($xml);//testunit
                                    xmlwriter_start_element($xml, 'lowtestrange');//lowtestrange
                                        xmlwriter_text($xml,$this->getValue($caseId, "lowtestrange",1));
                                    xmlwriter_end_element($xml);//lowtestrange
                                    xmlwriter_start_element($xml, 'hightestrange');//hightestrange
                                        xmlwriter_text($xml,$this->getValue($caseId, "hightestrange",1));
                                    xmlwriter_end_element($xml);//hightestrange
                                    xmlwriter_start_element($xml, 'moreinformation');//moreinformation
                                        xmlwriter_text($xml,$this->getValue($caseId, "moreinformation",1));
                                    xmlwriter_end_element($xml);//moreinformation
                                xmlwriter_end_element($xml);//test
                                 //test
                                xmlwriter_start_element($xml, 'test');//test
                                    xmlwriter_start_element($xml, 'testdateformat');//testdateformat
                                        xmlwriter_text($xml,$this->getValue($caseId, "testdateformat",2));
                                    xmlwriter_end_element($xml);//testdateformat
                                    xmlwriter_start_element($xml, 'testdate');//testdate
                                        xmlwriter_text($xml,$this->getValue($caseId, "testdate",2));
                                    xmlwriter_end_element($xml);//testdate
                                    xmlwriter_start_element($xml, 'testname');//testname
                                        xmlwriter_text($xml,$this->getValue($caseId, "testname",2));
                                    xmlwriter_end_element($xml);//testname
                                    xmlwriter_start_element($xml, 'testresult');//testresult
                                        xmlwriter_text($xml,$this->getValue($caseId, "testresult",2));
                                    xmlwriter_end_element($xml);//testresult
                                    xmlwriter_start_element($xml, 'testunit');//testunit
                                        xmlwriter_text($xml,$this->getValue($caseId, "testunit",2));
                                    xmlwriter_end_element($xml);//testunit
                                    xmlwriter_start_element($xml, 'lowtestrange');//lowtestrange
                                        xmlwriter_text($xml,$this->getValue($caseId, "lowtestrange",2));
                                    xmlwriter_end_element($xml);//lowtestrange
                                    xmlwriter_start_element($xml, 'hightestrange');//hightestrange
                                        xmlwriter_text($xml,$this->getValue($caseId, "hightestrange",2));
                                    xmlwriter_end_element($xml);//hightestrange
                                    xmlwriter_start_element($xml, 'moreinformation');//moreinformation
                                        xmlwriter_text($xml,$this->getValue($caseId, "moreinformation",2));
                                    xmlwriter_end_element($xml);//moreinformation
                                xmlwriter_end_element($xml);//test
                                //drug
                                xmlwriter_start_element($xml, 'drug');//drug
                                    xmlwriter_start_element($xml, 'drugcharacterization');//drugcharacterization
                                        xmlwriter_text($xml,$this->getValue($caseId, "drugcharacterization",1));
                                    xmlwriter_end_element($xml);//drugcharacterization
                                    xmlwriter_start_element($xml, 'medicinalproduct');//medicinalproduct
                                        xmlwriter_text($xml,$this->getValue($caseId, "medicinalproduct",1));
                                    xmlwriter_end_element($xml);//medicinalproduct
                                    xmlwriter_start_element($xml, 'obtaindrugcountry');//obtaindrugcountry
                                        xmlwriter_text($xml,$this->getValue($caseId, "obtaindrugcountry",1));
                                    xmlwriter_end_element($xml);//obtaindrugcountry
                                    xmlwriter_start_element($xml, 'drugbatchnumb');//drugbatchnumb
                                        xmlwriter_text($xml,$this->getValue($caseId, "drugbatchnumb",1));
                                    xmlwriter_end_element($xml);//drugbatchnumb
                                    xmlwriter_start_element($xml, 'drugauthorizationnumb');//drugauthorizationnumb
                                        xmlwriter_text($xml,$this->getValue($caseId, "drugauthorizationnumb",1));
                                    xmlwriter_end_element($xml);//drugauthorizationnumb
                                    xmlwriter_start_element($xml, 'drugauthorizationcountry');//drugauthorizationcountry
                                        xmlwriter_text($xml,$this->getValue($caseId, "drugauthorizationcountry",1));
                                    xmlwriter_end_element($xml);//drugauthorizationcountry
                                    xmlwriter_start_element($xml, 'drugauthorizationholder');//drugauthorizationholder
                                        xmlwriter_text($xml,$this->getValue($caseId, "drugauthorizationholder",1));
                                    xmlwriter_end_element($xml);//drugauthorizationholder
                                    xmlwriter_start_element($xml, 'drugstructuredosagenumb');//drugstructuredosagenumb
                                        xmlwriter_text($xml,$this->getValue($caseId, "drugstructuredosagenumb",1));
                                    xmlwriter_end_element($xml);//drugstructuredosagenumb
                                    xmlwriter_start_element($xml, 'drugstructuredosageunit');//drugstructuredosageunit
                                        xmlwriter_text($xml,$this->getValue($caseId, "drugstructuredosageunit",1));
                                    xmlwriter_end_element($xml);//drugstructuredosageunit
                                    xmlwriter_start_element($xml, 'drugseparatedosagenumb');//drugseparatedosagenumb
                                        xmlwriter_text($xml,$this->getValue($caseId, "drugseparatedosagenumb",1));
                                    xmlwriter_end_element($xml);//drugseparatedosagenumb
                                    xmlwriter_start_element($xml, 'drugintervaldosageunitnumb');//drugintervaldosageunitnumb
                                        xmlwriter_text($xml,$this->getValue($caseId, "drugintervaldosageunitnumb",1));
                                    xmlwriter_end_element($xml);//drugintervaldosageunitnumb
                                    xmlwriter_start_element($xml, 'drugintervaldosagedefinition');//drugintervaldosagedefinition
                                        xmlwriter_text($xml,$this->getValue($caseId, "drugintervaldosagedefinition",1));
                                    xmlwriter_end_element($xml);//drugintervaldosagedefinition
                                    xmlwriter_start_element($xml, 'drugcumulativedosagenumb');//drugcumulativedosagenumb
                                        xmlwriter_text($xml,$this->getValue($caseId, "drugcumulativedosagenumb",1));
                                    xmlwriter_end_element($xml);//drugcumulativedosagenumb
                                    xmlwriter_start_element($xml, 'drugcumulativedosageunit');//drugcumulativedosageunit
                                        xmlwriter_text($xml,$this->getValue($caseId, "drugcumulativedosageunit",1));
                                    xmlwriter_end_element($xml);//drugcumulativedosageunit
                                    xmlwriter_start_element($xml, 'drugdosagetext');//drugdosagetext
                                        xmlwriter_text($xml,$this->getValue($caseId, "drugdosagetext",1));
                                    xmlwriter_end_element($xml);//drugdosagetext
                                    xmlwriter_start_element($xml, 'drugdosageform');//drugdosageform
                                        xmlwriter_text($xml,$this->getValue($caseId, "drugdosageform",1));
                                    xmlwriter_end_element($xml);//drugdosageform
                                    xmlwriter_start_element($xml, 'drugadministrationroute');//drugadministrationroute
                                        xmlwriter_text($xml,$this->getValue($caseId, "drugadministrationroute",1));
                                    xmlwriter_end_element($xml);//drugadministrationroute
                                    xmlwriter_start_element($xml, 'drugparadministration');//drugparadministration
                                        xmlwriter_text($xml,$this->getValue($caseId, "drugparadministration",1));
                                    xmlwriter_end_element($xml);//drugparadministration
                                    xmlwriter_start_element($xml, 'reactiongestationperiod');//reactiongestationperiod
                                        xmlwriter_text($xml,$this->getValue($caseId, "reactiongestationperiod",1));
                                    xmlwriter_end_element($xml);//reactiongestationperiod
                                    xmlwriter_start_element($xml, 'reactiongestationperiodunit');//reactiongestationperiodunit
                                        xmlwriter_text($xml,$this->getValue($caseId, "reactiongestationperiodunit",1));
                                    xmlwriter_end_element($xml);//reactiongestationperiodunit
                                    xmlwriter_start_element($xml, 'drugindicationmeddraversion');//drugindicationmeddraversion
                                        xmlwriter_text($xml,$this->getValue($caseId, "drugindicationmeddraversion",1));
                                    xmlwriter_end_element($xml);//drugindicationmeddraversion
                                    xmlwriter_start_element($xml, 'drugindication');//drugindication
                                        xmlwriter_text($xml,$this->getValue($caseId, "drugindication",1));
                                    xmlwriter_end_element($xml);//drugindication
                                    xmlwriter_start_element($xml, 'drugstartdateformat');//drugstartdateformat
                                        xmlwriter_text($xml,$this->getValue($caseId, "drugstartdateformat",1));
                                    xmlwriter_end_element($xml);//drugstartdateformat
                                    xmlwriter_start_element($xml, 'drugstartdate');//drugstartdate
                                        xmlwriter_text($xml,$this->getValue($caseId, "drugstartdate",1));
                                    xmlwriter_end_element($xml);//drugstartdate
                                    xmlwriter_start_element($xml, 'drugstartperiod');//drugstartperiod
                                        xmlwriter_text($xml,$this->getValue($caseId, "drugstartperiod",1));
                                    xmlwriter_end_element($xml);//drugstartperiod
                                    xmlwriter_start_element($xml, 'drugstartperiodunit');//drugstartperiodunit
                                        xmlwriter_text($xml,$this->getValue($caseId, "drugstartperiodunit",1));
                                    xmlwriter_end_element($xml);//drugstartperiodunit
                                    xmlwriter_start_element($xml, 'druglastperiod');//druglastperiod
                                        xmlwriter_text($xml,$this->getValue($caseId, "druglastperiod",1));
                                    xmlwriter_end_element($xml);//druglastperiod
                                    xmlwriter_start_element($xml, 'druglastperiodunit');//druglastperiodunit
                                        xmlwriter_text($xml,$this->getValue($caseId, "druglastperiodunit",1));
                                    xmlwriter_end_element($xml);//druglastperiodunit
                                    xmlwriter_start_element($xml, 'drugenddateformat');//drugenddateformat
                                        xmlwriter_text($xml,$this->getValue($caseId, "drugenddateformat",1));
                                    xmlwriter_end_element($xml);//drugenddateformat
                                    xmlwriter_start_element($xml, 'drugenddate');//drugenddate
                                        xmlwriter_text($xml,$this->getValue($caseId, "drugenddate",1));
                                    xmlwriter_end_element($xml);//drugenddate
                                    xmlwriter_start_element($xml, 'drugtreatmentduration');//drugtreatmentduration
                                        xmlwriter_text($xml,$this->getValue($caseId, "drugtreatmentduration",1));
                                    xmlwriter_end_element($xml);//drugtreatmentduration
                                    xmlwriter_start_element($xml, 'drugtreatmentdurationunit');//drugtreatmentdurationunit
                                        xmlwriter_text($xml,$this->getValue($caseId, "drugtreatmentdurationunit",1));
                                    xmlwriter_end_element($xml);//drugtreatmentdurationunit
                                    xmlwriter_start_element($xml, 'actiondrug');//actiondrug
                                        xmlwriter_text($xml,$this->getValue($caseId, "actiondrug",1));
                                    xmlwriter_end_element($xml);//actiondrug
                                    xmlwriter_start_element($xml, 'drugrecurreadministration');//drugrecurreadministration
                                        xmlwriter_text($xml,$this->getValue($caseId, "drugrecurreadministration",1));
                                    xmlwriter_end_element($xml);//drugrecurreadministration
                                    xmlwriter_start_element($xml, 'drugadditional');//drugadditional
                                        xmlwriter_text($xml,$this->getValue($caseId, "drugadditional",1));
                                    xmlwriter_end_element($xml);//drugadditional
                                    //activesubstance
                                    xmlwriter_start_element($xml, 'activesubstance');//activesubstance
                                        xmlwriter_start_element($xml, 'activesubstancename');//activesubstancename
                                            xmlwriter_text($xml,$this->getValue($caseId, "activesubstancename",1));
                                        xmlwriter_end_element($xml);//activesubstancename
                                    xmlwriter_end_element($xml);//activesubstance
                                   //drugrecurrence
                                    xmlwriter_start_element($xml, 'drugrecurrence');//drugrecurrence
                                        xmlwriter_start_element($xml, 'drugrecuractionmeddraversion');//drugrecuractionmeddraversion
                                            xmlwriter_text($xml,$this->getValue($caseId, "drugrecuractionmeddraversion",1));
                                        xmlwriter_end_element($xml);//drugrecuractionmeddraversion
                                        xmlwriter_start_element($xml, 'drugrecuraction');//drugrecuraction
                                            xmlwriter_text($xml,$this->getValue($caseId, "drugrecuraction",1));
                                        xmlwriter_end_element($xml);//drugrecuraction
                                    xmlwriter_end_element($xml);//drugrecurrence
                                    //drugreactionrelatedness
                                    xmlwriter_start_element($xml, 'drugreactionrelatedness');//drugreactionrelatedness
                                        xmlwriter_start_element($xml, 'drugreactionassesmeddraversion');//drugreactionassesmeddraversion
                                            xmlwriter_text($xml,$this->getValue($caseId, "drugreactionassesmeddraversion",1));
                                        xmlwriter_end_element($xml);//drugreactionassesmeddraversion
                                        xmlwriter_start_element($xml, 'drugreactionasses');//drugreactionasses
                                            xmlwriter_text($xml,$this->getValue($caseId, "drugreactionasses",1));
                                        xmlwriter_end_element($xml);//drugreactionasses
                                        xmlwriter_start_element($xml, 'drugassessmentsource');//drugassessmentsource
                                            xmlwriter_text($xml,$this->getValue($caseId, "drugassessmentsource",1));
                                        xmlwriter_end_element($xml);//drugassessmentsource
                                        xmlwriter_start_element($xml, 'drugassessmentmethod');//drugassessmentmethod
                                            xmlwriter_text($xml,$this->getValue($caseId, "drugassessmentmethod",1));
                                        xmlwriter_end_element($xml);//drugassessmentmethod
                                        xmlwriter_start_element($xml, 'drugresult');//drugresult
                                            xmlwriter_text($xml,$this->getValue($caseId, "drugresult",1));
                                        xmlwriter_end_element($xml);//drugresult
                                    xmlwriter_end_element($xml);//drugreactionrelatedness
                                xmlwriter_end_element($xml);//drug*/
                                //summary
                                xmlwriter_start_element($xml, 'summary');//summary
                                    xmlwriter_start_element($xml, 'narrativeincludeclinical');//narrativeincludeclinical 
                                        xmlwriter_text($xml,$this->getValue($caseId, "narrativeincludeclinical",1));
                                    xmlwriter_end_element($xml);//narrativeincludeclinical
                                    xmlwriter_start_element($xml, 'reportercomment');//reportercomment
                                        xmlwriter_text($xml,$this->getValue($caseId, "reportercomment",1));
                                    xmlwriter_end_element($xml);//reportercomment
                                    xmlwriter_start_element($xml, 'senderdiagnosismeddraversion');//senderdiagnosismeddraversion
                                        xmlwriter_text($xml,$this->getValue($caseId, "senderdiagnosismeddraversion",1));
                                    xmlwriter_end_element($xml);//senderdiagnosismeddraversion
                                    xmlwriter_start_element($xml, 'senderdiagnosis');//senderdiagnosis
                                        xmlwriter_text($xml,$this->getValue($caseId, "senderdiagnosis",1));
                                    xmlwriter_end_element($xml);//senderdiagnosis
                                    xmlwriter_start_element($xml, 'sendercomment');//sendercomment
                                        xmlwriter_text($xml,$this->getValue($caseId, "sendercomment",1));
                                    xmlwriter_end_element($xml);//sendercomment
                                xmlwriter_end_element($xml);//summary
                            xmlwriter_end_element($xml); //patient
                        xmlwriter_end_element($xml); // safetyreport
                    
                    //the first element end
                    xmlwriter_end_element($xml); // ichicsr

                //document end
                xmlwriter_end_document($xml);
                echo xmlwriter_output_memory($xml);
                

            }   
        }
    

            