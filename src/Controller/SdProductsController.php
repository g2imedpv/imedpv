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
            'contain' => ['SdProductTypes']
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
            'contain' => ['SdProductTypes', 'SdProductWorkflows']
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
        $sdProductTypes = $this->SdProducts->SdProductTypes->find('list', ['limit' => 200]);
        // $sdSponsorCompanies = $this->SdProducts->SdSponsorCompanies->find('list', ['limit' => 200]);
        $this->set(compact('sdProduct', 'sdProductTypes', 'sdSponsorCompanies'));
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

    /**
     * Edit method
     *
     * @param string|null $id Sd Product id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sdProduct = $this->SdProducts->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $sdProduct = $this->SdProducts->patchEntity($sdProduct, $this->request->getData());
            if ($this->SdProducts->save($sdProduct)) {
                $this->Flash->success(__('The sd product has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sd product could not be saved. Please, try again.'));
        }
        $sdProductTypes = $this->SdProducts->SdProductTypes->find('list', ['limit' => 200]);
        // $sdSponsorCompanies = $this->SdProducts->SdSponsorCompanies->find('list', ['limit' => 200]);
        $this->set(compact('sdProduct', 'sdProductTypes', 'sdSponsorCompanies'));
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


    public function search()
    {
        $this->viewBuilder()->setLayout('main_layout');
        $product_types = $this->loadProductTypes();
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
            // $this->set(compact('searchResult'));
            die();
        }
        $this->set('sdProductTypes', $product_types);
        // $this->set('sdSponsors', $sponsors);

    }

    public function addproduct()
    {
        $this->viewBuilder()->setLayout('main_layout');
        // $this->set('sdSponsors', $sponsors);
        $accessment_workflow_structure = $this->loadWorkflowsStructure(0);
        $this->set('accessment_workflow_structure', $accessment_workflow_structure);
        $distribution_workflow_structure = $this->loadWorkflowsStructure(1);
        $this->set('distribution_workflow_structure', $distribution_workflow_structure);
        //debug($sponsors);
        //debug($product_types);
        $saved_accessment_workflow = [];
        
        if ($this->request->is('post')) {
            // debug($this->request->getData()); die();
            $sdProduct = $this->SdProducts->newEntity();
            $sdProduct = $this->SdProducts->patchEntity($sdProduct, $this->request->getData()['product']);
            $sdProduct['sd_company_id'] = $this->request->getSession()->read('Auth.User.sd_company_id');
            $saved_product = $this->SdProducts->save($sdProduct);
            if (!$saved_product) {
                debug($sdProduct);
                $this->Flash->error(__('erro in product'));
                return false;
            }
            // debug($sdProduct);
            //accessment workflow saving
            $workflows_table=TableRegistry::get("sd_workflows");
            foreach($this->request->getData()['accessment_workflow'] as $workflow_k => $workflow_detail){
                if(!empty($workflow_detail['id'])) continue;

            $sdWorkflowEntity = $workflows_table->newEntity();
                $patchedsdWorkflowEntity = $workflows_table->patchEntity($sdWorkflowEntity,$workflow_detail);
                
                $saved_accessment_workflow[$workflow_k] = $workflows_table->save($patchedsdWorkflowEntity);
                if (!($saved_accessment_workflow[$workflow_k])) {
                    debug($saved_accessment_workflow);
                    $this->Flash->error(__('error in accessment workflow'));
                    return false;
                }
            }

            //distribution workflow saving
            $workflows_table=TableRegistry::get("sd_workflows");
            foreach($this->request->getData()['distribution_workflow'] as $workflow_k => $workflow_detail){
                if(!empty($workflow_detail['id'])) continue;

            $sdWorkflowEntity = $workflows_table->newEntity();
                $patchedsdWorkflowEntity = $workflows_table->patchEntity($sdWorkflowEntity,$workflow_detail);
                
                $saved_distribution_workflow[$workflow_k] = $workflows_table->save($patchedsdWorkflowEntity);
                if (!($saved_distribution_workflow[$workflow_k])) {
                    debug($saved_distribution_workflow);
                    $this->Flash->error(__('error in distribution workflow'));
                    return false;
                }
            }

            //accessment activity saving
            $workflow_activities_table=TableRegistry::get("sd_workflow_activities");
            if(!empty($this->request->getData()['accessment_workflow_activity'])){
            foreach($this->request->getData()['workflow_activity'] as $workflow_activity_k => $workflow_activities){
                    foreach($workflow_activities as $k => $workflow_activity_detail){
                        $workflow_activity_detail['sd_workflow_id']=$saved_accessment_workflow[$workflow_activity_k]['id'];
                        $sdWorkflowActivityEntity = $workflow_activities_table->newEntity();
                        $patchedsdWorkflowActivityEntity = $workflow_activities_table->patchEntity($sdWorkflowActivityEntity,$workflow_activity_detail);
                        // debug($patchedsdWorkflowActivityEntity);
                        if (!($workflow_activities_table->save($patchedsdWorkflowActivityEntity))) {
                            debug($patchedsdWorkflowActivityEntity);
                            $this->Flash->error(__('error in accessment activity'));
                            return false;
                        }
                    }
                }
            }
            //distribution activity saving
            $workflow_activities_table=TableRegistry::get("sd_workflow_activities");
            if(!empty($this->request->getData()['distribution_workflow_activity'])){
            foreach($this->request->getData()['workflow_activity'] as $workflow_activity_k => $workflow_activities){
                    foreach($workflow_activities as $k => $workflow_activity_detail){
                        $workflow_activity_detail['sd_workflow_id']=$saved_distribution_workflow[$workflow_activity_k]['id'];
                        $sdWorkflowActivityEntity = $workflow_activities_table->newEntity();
                        $patchedsdWorkflowActivityEntity = $workflow_activities_table->patchEntity($sdWorkflowActivityEntity,$workflow_activity_detail);
                        // debug($patchedsdWorkflowActivityEntity);
                        if (!($workflow_activities_table->save($patchedsdWorkflowActivityEntity))) {
                            debug($patchedsdWorkflowActivityEntity);
                            $this->Flash->error(__('error in distribution activity'));
                            return false;
                        }
                    }
                }
            }

            //accessment product workflow saving
            $product_workflows_table = TableRegistry::get("sd_product_workflows");
            foreach($this->request->getData()['accessment_product_workflow'] as $product_workflow_k => $product_workflow_detail)
            {
                $product_workflow_detail['sd_product_id'] = $saved_product['id'];
                if(!empty($this->request->getData()['accessment_workflow'][$product_workflow_k]['id']))
                $product_workflow_detail['sd_workflow_id'] = $this->request->getData()['accessment_workflow'][$product_workflow_k]['id'];
                else{
                    $product_workflow_detail['sd_workflow_id'] = $saved_accessment_workflow[$product_workflow_k]['id'];
                };
                $sdProductWorkflowEntity = $product_workflows_table->newEntity();
                $patchedSdProductWorkflowEntity = $product_workflows_table->patchEntity($sdProductWorkflowEntity,$product_workflow_detail);
                $savedAccessmentProductWorkflow[$product_workflow_k] = $product_workflows_table->save($patchedSdProductWorkflowEntity);
                if (!($savedAccessmentProductWorkflow[$product_workflow_k])) {
                    debug($savedAccessmentProductWorkflow[$product_workflow_k]);
                    $this->Flash->error(__('error in product_workflow'));
                    return false;
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
                    return false;
                }
                // debug($patchedSdProductWorkflowEntity);
            }
            
            //accessment user_assignment saving
            $user_assignment_table = TableRegistry::get("sd_user_assignments");
            foreach($this->request->getData()['accessment_user_assignment'] as $user_assignment_k => $workflow_users)
            {
                foreach($workflow_users as $user_k => $user_detail)
                {
                    $user_detail['sd_product_workflow_id'] = $savedAccessmentProductWorkflow[$user_assignment_k]['id'];
                    if(!empty($this->request->getData()['accessment_workflow'][$product_workflow_k]['id']))
                    $user_detail['sd_workflow_id'] = $this->request->getData()['accessment_workflow'][$product_workflow_k]['id'];
                    else{
                        $user_detail['sd_workflow_id'] = $saved_accessment_workflow[$product_workflow_k]['id'];
                    };
                    $sd_user_assignmentsEntity = $user_assignment_table->newEntity();
                    $patchedsd_user_assignmentsEntity = $user_assignment_table->patchEntity($sd_user_assignmentsEntity,$user_detail);
                    if (!($user_assignment_table->save($patchedsd_user_assignmentsEntity))) {
                        debug($patchedsd_user_assignmentsEntity);
                        $this->Flash->error(__('error in user assignments'));
                        return false;
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
                    if(!empty($this->request->getData()['distribution_workflow'][$product_workflow_k]['id']))
                    $user_detail['sd_workflow_id'] = $this->request->getData()['distribution_workflow'][$product_workflow_k]['id'];
                    else{
                        $user_detail['sd_workflow_id'] = $saved_distribution_workflow[$product_workflow_k]['id'];
                    };
                    $sd_user_assignmentsEntity = $user_assignment_table->newEntity();
                    $patchedsd_user_assignmentsEntity = $user_assignment_table->patchEntity($sd_user_assignmentsEntity,$user_detail);
                    if (!($user_assignment_table->save($patchedsd_user_assignmentsEntity))) {
                        debug($patchedsd_user_assignmentsEntity);
                        $this->Flash->error(__('error in user assignments'));
                        return false;
                    }    
                    // debug($patchedsd_user_assignmentsEntity);                
                }
            }

            //link accessment and distribution
            $links_table = TableRegistry::get("sd_accessment_distribution_links");
            foreach($this->request->getData()['accessment_distribution'] as $accessment_key => $accessment_links)
            {
                foreach($accessment_links as $distribution_key => $status){
                    $link_entity = $links_table->newEntity();
                    if(!empty($this->request->getData()['distribution_workflow'][$product_workflow_k]['id']))
                        $link_entity['distribution'] = $this->request->getData()['distribution_workflow'][$product_workflow_k]['id'];
                    else $link_entity['distribution'] = $saved_distribution_workflow[$distribution_key]['id'];
                    if(!empty($this->request->getData()['accessment_workflow'][$product_workflow_k]['id']))
                        $link_entity['accessment'] = $this->request->getData()['accessment_workflow'][$product_workflow_k]['id'];
                    else $link_entity['accessment'] = $saved_accessment_workflow[$accessment_key]['id'];
                    if (!($links_table->save($link_entity))) {
                        debug($link_entity);
                        $this->Flash->error(__('error in links'));
                        return false;
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


    // public function loadSponsorCompanies()
    // {
    //     $result = array();
    //     $sponsor_companies = TableRegistry::get("sd_sponsor_companies");
    //     $query = $sponsor_companies->find()
    //                     ->order(['company_name' => 'ASC']);

    //     foreach ($query as $sponsor_company){
    //         $result[] = array("id"=>$sponsor_company->id, "company_name"=>$sponsor_company->company_name, "country"=>$sponsor_company->country);
    //     }

    //     return $result;
    // }


    /**
     * 
     * In addproduct page 
     * 
     */
    public function loadTabs()
    {
        $activity_section_permission_table = TableRegistry::get('SdActivitySectionPermissions');
        $activity_section_permissions = $activity_section_permission_table->find();
        $sd_tabs_table = TableRegistry::get('SdTabs');
        $sd_tabs = $sd_tabs_table->find()
                    ->contain(['SdSections'=>function($q){
                        return $q->order(['SdSections.section_level'=>'DESC','SdSections.display_order'=>'ASC'])
                                ->select(['SdSections.id','SdSections.sd_tab_id','SdSections.section_name','SdSections.section_level','SdSections.child_section']);
                    }])->order(['SdTabs.display_order'=>'ASC']);
        return $sd_tabs->toList();
    }
}
