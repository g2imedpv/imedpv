<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
/**
 * SdProducts Controller
 *
 * @property \App\Model\Table\SdProductsTable $SdProducts
 *
 * @method \App\Model\Entity\SdProduct[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SdProductsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['SdCompanies']
        ];
        $sdProducts = $this->paginate($this->SdProducts);

        $this->set(compact('sdProducts'));
    }

    /**
     * View method
     *
     * @param string|null $id Sd Product id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $sdProduct = $this->SdProducts->get($id, [
            'contain' => ['SdCompanies', 'SdProductWorkflows']
        ]);

        $this->set('sdProduct', $sdProduct);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $sdProduct = $this->SdProducts->newEntity();
        if ($this->request->is('post')) {
            $sdProduct = $this->SdProducts->patchEntity($sdProduct, $this->request->getData());
            if ($this->SdProducts->save($sdProduct)) {
                $this->Flash->success(__('The sd product has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sd product could not be saved. Please, try again.'));
        }
        $sdCompanies = $this->SdProducts->SdCompanies->find('list', ['limit' => 200]);
        $this->set(compact('sdProduct', 'sdCompanies'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Sd Product id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->viewBuilder()->setLayout('main_layout');
        $sdProduct = $this->SdProducts->get($id, [
            'contain' => []
        ]);
        //debug($sdProduct);die();
        if ($this->request->is(['patch', 'post', 'put'])) {
            $sdProduct = $this->SdProducts->patchEntity($sdProduct, $this->request->getData());
            if ($this->SdProducts->save($sdProduct)) {
                $this->Flash->success(__('The sd product has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sd product could not be saved. Please, try again.'));
        }
        $sdCompanies = $this->SdProducts->SdCompanies->find('list', ['limit' => 200]);
        $this->set(compact('sdProduct', 'sdCompanies'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Sd Product id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sdProduct = $this->SdProducts->get($id);
        if ($this->SdProducts->delete($sdProduct)) {
            $this->Flash->success(__('The sd product has been deleted.'));
        } else {
            $this->Flash->error(__('The sd product could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Create new product method
     *
     * @return \Cake\Http\Response|null Redirects on successful create, renders view otherwise.
     */
    public function create()
    {
        $this->autoRender = false;
        if ($this->request->is('post')) {
            //echo json_encode(array('result'=>1)); die();
            $sdProduct = $this->SdProducts->newEntity();
            $sdProduct = $this->SdProducts->patchEntity($sdProduct, $this->request->getData());
            if ($this->SdProducts->save($sdProduct)) {
                echo json_encode(array('result'=>1, 'product_id'=>$sdProduct->id));
            }
            else{
                echo json_encode(array('result'=>0));
            }
        }
    }

    public function search()
    {
        $this->viewBuilder()->setLayout('main_layout');
        if ($this->request->is('post')) {
            $this->autoRender = false;
            $searchKey = $this->request->getData();
            try{
                $searchResult =  $this->SdProducts->find();
                $user = TableRegistry::get('SdUsers')->get($searchKey['userId']);
                if(!empty($searchKey['productName'])) $searchResult = $searchResult->where(['product_name LIKE'=>'%'.$searchKey['productName'].'%']);
                if(!empty($searchKey['studyName'])) $searchResult = $searchResult->where(['study_no  LIKE'=>'%'.$searchKey['studyName'].'%']);
                if($user['sd_role_id']<=2) {
                    $searchResult->contain(['SdProductWorkflows.SdWorkflows'=>function($q){return $q->select(['name','country','id']);},
                    'SdCompanies'=>function($q){ return $q->select(['company_name']);}])->all();
                }else{
                    $searchResult->contain(['SdProductWorkflows'=>function($q)use($searchKey){
                        return $q->join([
                            'ua'=>[
                                'table' =>'sd_user_assignments',
                                'type'=>'INNER',
                                'conditions'=>['ua.sd_product_workflow_id = SdProductWorkflows.id','ua.sd_user_id = '.$searchKey['userId']]
                            ]
                        ])->distinct()->contain(['SdWorkflows'=>function($q){return $q->select(['name','country','id']);}]);
                    },
                    'SdCompanies'=>function($q){ return $q->select(['company_name']);}])->toArray();
                    $searchResult = $searchResult->toArray();
                    foreach($searchResult as $key => $productDetail){
                        if(empty($productDetail['sd_product_workflows']))
                        unset($searchResult[$key]);
                    }
                    // print_r($searchResult);
                }

            }catch (\PDOException $e){
                echo "cannot the case find in database";
            }
            echo json_encode($searchResult);
            die();
        }

    }

    public function addproduct()
    {
        $this->viewBuilder()->setLayout('main_layout');
        // $this->set('sdSponsors', $sponsors);
        $assessment_workflow_structure = $this->loadWorkflowsStructure(0);
        $this->set('assessment_workflow_structure', $assessment_workflow_structure);
        $distribution_workflow_structure = $this->loadWorkflowsStructure(1);
        $this->set('distribution_workflow_structure', $distribution_workflow_structure);
        //debug($sponsors);
        //debug($product_types);
        $saved_assessment_workflow = [];

        if ($this->request->is('post')) {
            //debug($this->request->getData());

            //add product detail
            $sdProduct = $this->SdProducts->newEntity();
            $sdProduct = $this->SdProducts->patchEntity($sdProduct, $this->request->getData()['product']);
            $sdProduct['sd_company_id'] = $this->request->getSession()->read('Auth.User.sd_company_id');
            //debug($sdProduct);die();
            $saved_product = $this->SdProducts->save($sdProduct);
            if (!$saved_product) {
                //debug($sdProduct);
                $this->Flash->error(__('error in product'));
                return;
            }
            // debug($sdProduct);

            //assessment workflow saving
            $workflows_table=TableRegistry::get("sd_workflows");
            foreach($this->request->getData()['assessment_workflow'] as $workflow_k => $workflow_detail){
                if(!empty($workflow_detail['id'])) continue;

            $sdWorkflowEntity = $workflows_table->newEntity();
                $patchedsdWorkflowEntity = $workflows_table->patchEntity($sdWorkflowEntity,$workflow_detail);
                $patchedsdWorkflowEntity['status'] = 1;
                $saved_assessment_workflow[$workflow_k] = $workflows_table->save($patchedsdWorkflowEntity);
                if (!($saved_assessment_workflow[$workflow_k])) {
                    debug($patchedsdWorkflowEntity);
                    $this->Flash->error(__('error in assessment workflow'));
                    return;
                }
            }

            //distribution workflow saving
            $workflows_table=TableRegistry::get("sd_workflows");
            foreach($this->request->getData()['distribution_workflow'] as $workflow_k => $workflow_detail){
                if(!empty($workflow_detail['id'])) continue;

            $sdWorkflowEntity = $workflows_table->newEntity();
                $patchedsdWorkflowEntity = $workflows_table->patchEntity($sdWorkflowEntity,$workflow_detail);
                $patchedsdWorkflowEntity['status'] = 1;
                $saved_distribution_workflow[$workflow_k] = $workflows_table->save($patchedsdWorkflowEntity);
                if (!($saved_distribution_workflow[$workflow_k])) {
                    debug($patchedsdWorkflowEntity);
                    $this->Flash->error(__('error in distribution workflow'));
                    return;
                }
            }

            //assessment activity saving
            $workflow_activities_table=TableRegistry::get("sd_workflow_activities");
            $permission_table = TableRegistry::get("sd_activity_section_permissions");
            if(!empty($this->request->getData()['assessment_workflow_activity'])){
                foreach($this->request->getData()['assessment_workflow_activity'] as $workflow_activity_k => $workflow_activities){
                    foreach($workflow_activities as $k => $workflow_activity_detail){
                        $workflow_activity_detail['sd_workflow_id']=$saved_assessment_workflow[$workflow_activity_k]['id'];
                        $sdWorkflowActivityEntity = $workflow_activities_table->newEntity();
                        $patchedsdWorkflowActivityEntity = $workflow_activities_table->patchEntity($sdWorkflowActivityEntity,$workflow_activity_detail);
                        // debug($patchedsdWorkflowActivityEntity);
                        $saved_activity = $workflow_activities_table->save($patchedsdWorkflowActivityEntity);
                        if (!$saved_activity) {
                            debug($patchedsdWorkflowActivityEntity);
                            $this->Flash->error(__('error in assessment activity'));
                            return;
                        }else{

                            foreach($this->request->getData()['assessment_permission'][$workflow_activity_k][$k+1] as $section_id => $action){
                                $dataSet = [
                                    'sd_workflow_activity_id' => $saved_activity['id'],
                                    'action' => $action['action'],
                                    'sd_section_id' => $section_id,
                                ];
                                $permissionEntity = $permission_table->newEntity();
                                $patchedpermissionEntity = $permission_table->patchEntity($permissionEntity, $dataSet);
                                $saved_permission = $permission_table->save($patchedpermissionEntity);
                                if (!$saved_permission) {
                                    debug($patchedpermissionEntity);
                                    $this->Flash->error(__('error in assessment permissions'));
                                    return;
                                }
                            }

                        }
                    }
                }
            }
            //distribution activity saving
            if(!empty($this->request->getData()['distribution_workflow_activity'])){
            foreach($this->request->getData()['distribution_workflow_activity'] as $workflow_activity_k => $workflow_activities){
                    foreach($workflow_activities as $k => $workflow_activity_detail){
                        $workflow_activity_detail['sd_workflow_id']=$saved_distribution_workflow[$workflow_activity_k]['id'];
                        $sdWorkflowActivityEntity = $workflow_activities_table->newEntity();
                        $patchedsdWorkflowActivityEntity = $workflow_activities_table->patchEntity($sdWorkflowActivityEntity,$workflow_activity_detail);
                        // debug($patchedsdWorkflowActivityEntity);
                        $saved_activity = $workflow_activities_table->save($patchedsdWorkflowActivityEntity);
                        if (!$saved_activity) {
                            debug($patchedsdWorkflowActivityEntity);
                            $this->Flash->error(__('error in distribution activity'));
                            return;
                        }else{
                            foreach($this->request->getData()['distribution_permission'][$workflow_activity_k][$k+1] as $section_id => $action){
                                $dataSet = [
                                    'sd_workflow_activity_id' => $saved_activity['id'],
                                    'action' => $action['action'],
                                    'sd_section_id' => $section_id,
                                ];
                                $permissionEntity = $permission_table->newEntity();
                                $patchedpermissionEntity = $permission_table->patchEntity($permissionEntity, $dataSet);
                                $saved_permission = $permission_table->save($patchedpermissionEntity);
                                if (!$saved_permission) {
                                    debug($patchedpermissionEntity);
                                    $this->Flash->error(__('error in distribution permissions'));
                                    return;
                                }
                            }
                        }
                    }
                }
            }

            //assessment product workflow saving
            $product_workflows_table = TableRegistry::get("sd_product_workflows");
            foreach($this->request->getData()['assessment_product_workflow'] as $product_workflow_k => $product_workflow_detail)
            {
                $product_workflow_detail['sd_product_id'] = $saved_product['id'];
                if(!empty($this->request->getData()['assessment_workflow'][$product_workflow_k]['id']))
                $product_workflow_detail['sd_workflow_id'] = $this->request->getData()['assessment_workflow'][$product_workflow_k]['id'];
                else{
                    $product_workflow_detail['sd_workflow_id'] = $saved_assessment_workflow[$product_workflow_k]['id'];
                };
                $sdProductWorkflowEntity = $product_workflows_table->newEntity();
                $patchedSdProductWorkflowEntity = $product_workflows_table->patchEntity($sdProductWorkflowEntity,$product_workflow_detail);
                $savedAssessmentProductWorkflow[$product_workflow_k] = $product_workflows_table->save($patchedSdProductWorkflowEntity);
                if (!($savedAssessmentProductWorkflow[$product_workflow_k])) {
                    debug($savedAssessmentProductWorkflow[$product_workflow_k]);
                    $this->Flash->error(__('error in product_workflow'));
                    return;
                }
                // debug($patchedSdProductWorkflowEntity);
            }

            //distribution product workflow saving
            $product_workflows_table = TableRegistry::get("sd_product_workflows");
            foreach($this->request->getData()['distribution_product_workflow'] as $product_workflow_k => $product_workflow_detail)
            {
                $product_workflow_detail['sd_product_id'] = $saved_product['id'];
                if(!empty($this->request->getData()['distribution_workflow'][$product_workflow_k]['id']))
                $product_workflow_detail['sd_workflow_id'] = $this->request->getData()['distribution_workflow'][$product_workflow_k]['id'];
                else{
                    $product_workflow_detail['sd_workflow_id'] = $saved_distribution_workflow[$product_workflow_k]['id'];
                };
                $sdProductWorkflowEntity = $product_workflows_table->newEntity();
                $patchedSdProductWorkflowEntity = $product_workflows_table->patchEntity($sdProductWorkflowEntity,$product_workflow_detail);
                $savedDistributionProductWorkflow[$product_workflow_k] = $product_workflows_table->save($patchedSdProductWorkflowEntity);
                if (!($savedDistributionProductWorkflow[$product_workflow_k])) {
                    debug($savedDistributionProductWorkflow[$product_workflow_k]);
                    $this->Flash->error(__('error in product_workflow'));
                    return;
                }
                // debug($patchedSdProductWorkflowEntity);
            }
            //assessment user_assignment saving
            $user_assignment_table = TableRegistry::get("sd_user_assignments");
            foreach($this->request->getData()['assessment_user_assignment'] as $user_assignment_k => $workflow_users)
            {
                foreach($workflow_users as $user_k => $user_detail)
                {
                    $user_detail['sd_product_workflow_id'] = $savedAssessmentProductWorkflow[$user_assignment_k]['id'];
                    if(!empty($this->request->getData()['assessment_workflow'][$user_assignment_k]['id']))
                    $user_detail['sd_workflow_id'] = $this->request->getData()['assessment_workflow'][$user_assignment_k]['id'];
                    else{
                        $user_detail['sd_workflow_id'] = $saved_assessment_workflow[$user_assignment_k]['id'];
                    };
                    $sd_user_assignmentsEntity = $user_assignment_table->newEntity();
                    $patchedsd_user_assignmentsEntity = $user_assignment_table->patchEntity($sd_user_assignmentsEntity,$user_detail);
                    if (!($user_assignment_table->save($patchedsd_user_assignmentsEntity))) {
                        debug($patchedsd_user_assignmentsEntity);
                        $this->Flash->error(__('error in user assignments'));
                        return;
                    }
                    // debug($patchedsd_user_assignmentsEntity);
                }
            }

            //distribution user_assignment saving
            $user_assignment_table = TableRegistry::get("sd_user_assignments");
            foreach($this->request->getData()['distribution_user_assignment'] as $user_assignment_k => $workflow_users)
            {
                foreach($workflow_users as $user_k => $user_detail)
                {
                    $user_detail['sd_product_workflow_id'] = $savedDistributionProductWorkflow[$user_assignment_k]['id'];
                    if(!empty($this->request->getData()['distribution_workflow'][$user_assignment_k]['id']))
                    $user_detail['sd_workflow_id'] = $this->request->getData()['distribution_workflow'][$user_assignment_k]['id'];
                    else{
                        $user_detail['sd_workflow_id'] = $saved_distribution_workflow[$user_assignment_k]['id'];
                    };
                    $sd_user_assignmentsEntity = $user_assignment_table->newEntity();
                    $patchedsd_user_assignmentsEntity = $user_assignment_table->patchEntity($sd_user_assignmentsEntity,$user_detail);
                    if (!($user_assignment_table->save($patchedsd_user_assignmentsEntity))) {
                        debug($patchedsd_user_assignmentsEntity);
                        $this->Flash->error(__('error in user assignments'));
                        return;
                    }
                    // debug($patchedsd_user_assignmentsEntity);
                }
            }
            //link assessment and distribution
            $links_table = TableRegistry::get("sd_assessment_distribution_links");
            foreach($this->request->getData()['assessment_distribution'] as $assessment_key => $assessment_links)
            {
                foreach($assessment_links as $distribution_key => $status){
                    $link_entity = $links_table->newEntity();
                    if(!empty($this->request->getData()['distribution_workflow'][$assessment_key]['id']))
                        $link_entity['distribution'] = $this->request->getData()['distribution_workflow'][$assessment_key]['id'];
                    else $link_entity['distribution'] = $saved_distribution_workflow[$distribution_key]['id'];
                    $link_entity['sd_product_workflow_id'] = $savedAssessmentProductWorkflow[$assessment_key]['id'];
                    if (!($links_table->save($link_entity))) {
                        debug($link_entity);
                        $this->Flash->error(__('error in links'));
                        return;
                    }
                }
            }
            $this->Flash->success(__('The sd product has been saved.'));
            return $this->redirect(['action' => 'search']);
        }
        $userinfo = $this->request->getSession()->read('Auth.User');
        $cro_companies = TableRegistry::get("SdSponsorCros");
        $query = $cro_companies->find()
        ->select(['SdCompanies.id', 'SdCompanies.company_name'])
        ->join([
            'SdCompanies' =>[
                'table' =>'sd_companies',
                'type'=>'LEFT',
                'conditions'=>['SdCompanies.id = SdSponsorCros.cro_company'],
            ]])->where(['SdSponsorCros.sponsor'=>$userinfo['company_id']]);
        foreach ($query as $company_info){
            $result[$company_info->SdCompanies['id']] = $company_info->SdCompanies['company_name'];
        }

        $this->set('cro_companies', $result);
        $call_ctr_companies = TableRegistry::get("SdSponsorCallcenters");
        $query = $call_ctr_companies->find()
        ->select(['SdCompanies.id', 'SdCompanies.company_name'])
        ->join([
            'SdCompanies' =>[
                'table' =>'sd_companies',
                'type'=>'LEFT',
                'conditions'=>['SdCompanies.id = SdSponsorCallcenters.call_center'],
            ]])->where(['SdSponsorCallcenters.sponsor'=>$userinfo['company_id']]);
        foreach ($query as $company_info){
            $calresult[$company_info->SdCompanies['id']] = $company_info->SdCompanies['company_name'];
        }
        $this->set('call_ctr_companies', $calresult);
        $loadTabs = $this->loadTabs();
        $this->set('loadTabs', $loadTabs);
    }

    public function loadProductTypes()
    {
        $result = array();
        $product_types = TableRegistry::get("sd_product_types");
        $query = $product_types->find()
                        ->order(['id' => 'ASC']);

        foreach ($query as $product_type){
            $result[] = array("id"=>$product_type->id, "type_name"=>$product_type->type_name);
        }

        return $result;
    }

    /**
     *
     *
     * for ajax use
     * fetch related Cro companies
     */
    public function searchCallcenterCompanies()
    {
        $result = array();
        if($this->request->is('POST')){
            $this->autoRender = false;
            $searchKey = $this->request->getData();
            $cro_ids = TableRegistry::get("sd_sponsor_callcenters");
            try{
                $query = $cro_ids->find()
                                ->select(['SdCompanies.id', 'SdCompanies.company_name'])
                                ->join([
                                    'SdCompanies' =>[
                                        'table' =>'sd_companies',
                                        'type'=>'LEFT',
                                        'conditions'=>['SdCompanies.id = sd_sponsor_callcenters.call_center'],
                                    ]]);
                                // ->order(['sponsor_id' => 'ASC'])
                foreach ($query as $company_info){
                    $result[$company_info->SdCompanies['id']] = $company_info->SdCompanies['company_name'];
                }
            }catch (\PDOException $e){
                echo "cannot the case find in database";
            };
            echo json_encode($result);
            die();
        } else $this->autoRender = true;
    }

    /**
     *
     * for add product add workflows
     */
    public function loadWorkflowsStructure($type)
    {
        $result = array();
        $searchKey = $this->request->getData();
        $default_workflows = TableRegistry::get("sd_workflows");
        $query = $default_workflows->find()
                        ->where(['workflow_type'=>'0','classification'=>$type])
                        ->contain('SdWorkflowActivities', function ($q) {
                            return $q->select(['SdWorkflowActivities.id','SdWorkflowActivities.step_backward','SdWorkflowActivities.sd_workflow_id','SdWorkflowActivities.activity_name','SdWorkflowActivities.description'])->order(['SdWorkflowActivities.id'=>'ASC']);
                        })
                        ->order(['sd_workflows.id' => 'ASC']);

        foreach ($query as $workflow_info){
            $result[$workflow_info->country] = $workflow_info;
        }
    return $result;
    }


    /**
     *
     * In addproduct page
     *
     */
    public function loadTabs()
    {
        $sd_tabs_table = TableRegistry::get('SdTabs');
        $sd_tabs = $sd_tabs_table->find()
                    ->contain(['SdSections'=>function($q){
                        return $q->order(['SdSections.section_level'=>'ASC','SdSections.display_order'=>'ASC'])
                                ->select(['SdSections.id','SdSections.sd_tab_id','SdSections.section_name','SdSections.section_level','SdSections.parent_section']);
                    }])->order(['SdTabs.display_order'=>'ASC']);
        $child_list = [];
        foreach($sd_tabs as $sdTab){
            foreach($sdTab->sd_sections as $sdSection){
                if($sdSection->parent_section!=0){
                    if(empty($child_list[$sdSection->parent_section])) $child_list[$sdSection->parent_section]="";
                    $child_list[$sdSection->parent_section] = $child_list[$sdSection->parent_section].$sdSection->id.",";
                }
            }
            foreach($sdTab->sd_sections as $sdSection){
                if(!empty($child_list[$sdSection->id]))  $sdSection->child_section = substr($child_list[$sdSection->id], 0, -1);
            }
        }
        return $sd_tabs->toList();
    }
}
