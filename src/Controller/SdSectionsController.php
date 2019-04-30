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
    public function saveSection($caseId){
        if($this->request->is('POST')){
            $this->autoRender = false;
            $sdSectionSetsTable = TableRegistry::get('SdSectionSets');
            $SdSectionStructuresTable = TableRegistry::get('SdSectionStructures');
            $sdFieldValueTable = TableRegistry::get('SdFieldValues');
            $savedField = [];
            foreach($requstData = $this->request->getData() as $sectionFieldK =>$sectionFieldValue){     
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
                    $section_id = $sectionFieldValue['sd_section_id'];
                    unset($sectionFieldValue['set_array']);
                    unset($sectionFieldValue['sd_section_id']);
                    $dataSet = [
                        'sd_case_id' => $caseId,
                        'sd_field_id' => $sectionFieldValue['sd_field_id'],
                        'set_number' => "1",//TODO SET NUMBER REMOVE
                        'created_time' =>date("Y-m-d H:i:s"),
                        'field_value' =>$sectionFieldValue['field_value'],
                        'status' =>'1',
                    ];
                    $sdFieldValueEntity = $sdFieldValueTable->patchEntity($sdFieldValueEntity, $dataSet);
                    $savedFieldValue = $sdFieldValueTable->save($sdFieldValueEntity);
                    if(!$savedFieldValue){
                        echo "error in adding values!" ;
                        debug($savedFieldValue);
                    } 
                    $sdSectionSetsEntity = $sdSectionSetsTable->newEntity(); 
                    if(empty($set_array)) continue;
                    foreach($set_array as $setSectionId => $set_no){
                        $sdSectionSetsEntity['set_array'] = $set_no.",".$sdSectionSetsEntity['set_array']; 
                        $sdSectionSetsEntity['sd_section_id'] = $setSectionId;
                    }
                    if(strlen($sdSectionSetsEntity['set_array']) > 1)
                    $sdSectionSetsEntity['set_array'] = substr($sdSectionSetsEntity['set_array'], 0, -1);
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
                            'conditions'=>['sd_sections.id = SdSectionStructures.sd_section_id'],
                            ]])            
                        ->where(['sd_field_id'=>$sectionFieldValue['sd_field_id'],'sd_section_id !='=>$section_id,'sections.status'=>true]);
                    foreach($sections as $sectionDetail){
                        $sdSectionSetsEntityNew = $sdSectionSetsTable->newEntity();
                        $sdSectionSetsEntityNew = $sdSectionSetsTable->patchEntity($sdSectionSetsEntityNew, $setDataSet);
                        $sdSectionSetsEntityNew['sd_section_id'] = $sectionDetail['sd_section_id'];
                        if($sectionFieldValue['sd_field_id'] == '200'){
                            $sdSectionSetsEntityNew['set_array'] = substr($sdSectionSetsEntityNew['set_array'], 0, -1);
                            $sdSectionSetsEntityNew['set_array'] = $sdSectionSetsEntityNew['set_array'].'*';
                        }
                        $sdSectionSetsEntityNew['sd_field_value_id'] = $savedFieldValue['id'];                           
                        if(!$sdSectionSetsTable->save($sdSectionSetsEntityNew)){
                            echo "error in adding NEW sets!" ;// ADD INTO ANOTHER SET
                            debug($sdSectionSetsEntityNew);
                        } 
                    }

                }
                $savedField[$sectionFieldValue['sd_field_id']] = $sdFieldValueTable->find()->where(['sd_field_id'=>$sectionFieldValue['sd_field_id'],'status'=>1,'sd_case_id'=>$caseId]);

            }
        }else $this->autoRender = true;
        echo json_encode($savedField);
    }    
    /*
    delete Single section
    */
    public function deleteSection($caseId){
        if($this->request->is('POST')){
            $this->autoRender = false;
            $sdFieldValues = TableRegistry::get('SdFieldValues');
            $savedField = [];
            foreach($requstData = $this->request->getData() as $sectionFieldK =>$sectionFieldValue){                
                if($sectionFieldValue['id']!=""){
                    $sectionFieldValue['status'] = 0;
                    $sdFieldValueEntity = $sdFieldValues->get($sectionFieldValue['id']);/**add last-updated time */                            
                    $sdFieldValues->patchEntity($sdFieldValueEntity,$sectionFieldValue);
                    if(!$sdFieldValues->save($sdFieldValueEntity)) echo "error in updating!" ;
                    }
                $followingValues = $sdFieldValues->find()->where(['sd_field_id'=>$sectionFieldValue['sd_field_id'],'status'=>1,'sd_case_id'=>$caseId]);
                foreach($followingValues as $k => $field_value_detail){
                    if($field_value_detail['set_number']>$sectionFieldValue['set_number'])
                        $field_value_detail['set_number'] = $field_value_detail['set_number'] - 1;
                    if(!$sdFieldValues->save($field_value_detail)) echo "error in updating latter set!" ;
                }                
                $savedField[$sectionFieldValue['sd_field_id']] = $sdFieldValues->find()->where(['sd_field_id'=>$sectionFieldValue['sd_field_id'],'status'=>1,'sd_case_id'=>$caseId]);
            }
        }else $this->autoRender = true;
        echo json_encode($savedField);
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
