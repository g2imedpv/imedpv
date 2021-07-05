<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Auth\DefaultPasswordHasher;


/**
 * SdUsers Controller
 *
 * @property \App\Model\Table\SdUsersTable $SdUsers
 *
 * @method \App\Model\Entity\SdUser[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SdUsersController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->viewBuilder()->setLayout('main_layout');
        $this->paginate = [
            'contain' => ['SdRoles', 'SdCompanies']
        ];
        $sdUsers = $this->paginate($this->SdUsers);

        $this->set(compact('sdUsers'));
    }

    /**
     * View method
     *
     * @param string|null $id Sd User id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {

        $sdUser = $this->SdUsers->get($id, [
            'contain' => ['SdRoles', 'SdCompanies', 'SdActivityLog']
        ]);

        $this->set('sdUser', $sdUser);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->viewBuilder()->setLayout('main_layout');
        $sdUser = $this->SdUsers->newEntity();
        if ($this->request->is('post')) {
            $sdUser = $this->SdUsers->patchEntity($sdUser, $this->request->getData());
            if ($this->SdUsers->save($sdUser)) {
                $this->Flash->success(__('The sd user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sd user could not be saved. Please, try again.'));
        }
        $sdRoles = $this->SdUsers->SdRoles->find('list', ['limit' => 200]);
        $sdCompanies = $this->SdUsers->SdCompanies->find('list', ['limit' => 200]);
        $this->set(compact('sdUser', 'sdRoles', 'sdCompanies'));
    }
    /**
     *
     * get user info from cro
     */
    public function searchResource()
    {
        $result = array();
        if($this->request->is('POST')){
            $this->autoRender = false;
            $searchKey = $this->request->getData();
            try{
                $query = $this->SdUsers->find()
                                ->select(['id','firstname', 'lastname'])
                                ->where(['sd_company_id'=>$searchKey['id']])
                                ->order(['id' => 'ASC'])->all();
            }catch (\PDOException $e){
                echo "cannot the case find in database";
            };
            echo json_encode($query);
            die();
        } else $this->autoRender = true;
    }
    /**
     * Edit method
     *
     * @param string|null $id Sd User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sdUser = $this->SdUsers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $sdUser = $this->SdUsers->patchEntity($sdUser, $this->request->getData());
            if ($this->SdUsers->save($sdUser)) {
                $this->Flash->success(__('The sd user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sd user could not be saved. Please, try again.'));
        }
        $sdRoles = $this->SdUsers->SdRoles->find('list', ['limit' => 200]);
        $sdCompanies = $this->SdUsers->SdCompanies->find('list', ['limit' => 200]);
        $this->set(compact('sdUser', 'sdRoles', 'sdCompanies'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Sd User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sdUser = $this->SdUsers->get($id);
        if ($this->SdUsers->delete($sdUser)) {
            $this->Flash->success(__('The sd user has been deleted.'));
        } else {
            $this->Flash->error(__('The sd user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    public function initialize() {
        parent::initialize();
        $this->Auth->allow(['logout']);
    }

    public function logout() {
        $this->Flash->success('You are now logged out.');
        $session = $this->getRequest()->getSession();
        $language = $session->read('Language');
        $session->destroy();
        $session->write('Language', $language);
        return $this->redirect($this->Auth->logout());
    }
    public function setLanguage($language){
        $session = $this->getRequest()->getSession();
        $session->write('Language', $language);
        return $this->redirect($this->referer());
    }
    // public function setVersion($version){
    //     $session = $this->getRequest()->getSession();
    //     $session->write('version', $version);
    //     return $this->redirect($this->referer());
    // }
    public function login() {
        $this->viewBuilder()->setLayout('login');
        $session = $this->getRequest()->getSession();
            if ($this->request->is('post')) {
                $sdUser = $this->Auth->identify();
                if ($sdUser) {
                    $this->Auth->setUser($sdUser);
                    $SdRoles = TableRegistry::get('SdRoles')->get($sdUser['sd_role_id']);

                    $request = $this->request->getData();
                    $sdUser['password'] = $request['password'];

                    $session->write([
                        'Auth.User.role_name' => $SdRoles['description'],
                        'Auth.User.password' => $sdUser['password']
                    ]);
                    // $session->write('Language', 'en_US');
                    if($session->read('Language')== null) $session->write('Language', 'en_US');;
                    return $this->redirect($this->Auth->redirectUrl(
                        // Set the first page after user logged in
                        ['controller' => 'SdCompanies','action' => 'selectCompany']
                    )
                );
                }else {
                    $this->Flash->error(__('Sorry, your username or password is wrong!'));
                }
            }
            if($session->read('Language')== null) $session->write('Language', 'en_US');;
    }

    public function searchPreviousAvailable($caseNo, $version=1){
        if($this->request->is('POST')){
            $this->autoRender = false;
            $searchKey = $this->request->getData();
            $case = TableRegistry::get('SdCases')->find()->where(['caseNo'=>$caseNo,'version_no'=>$version])->first();
            $currentActivity = TableRegistry::get('SdWorkflowActivities')->get($case['sd_workflow_activity_id']);
            $previousActivities = TableRegistry::get('SdWorkflowActivities')->find()
                        ->select(['SdWorkflowActivities.id','SdWorkflowActivities.activity_name','pw.id','SdWorkflowActivities.order_no'])
                        ->join([
                            'pw'=>[
                                'table'=>'sd_product_workflows',
                                'type'=>'INNER',
                                'conditions'=>['SdWorkflowActivities.sd_workflow_id = pw.sd_workflow_id','pw.id = '.$case['sd_product_workflow_id']]
                            ],
                            'curActivity'=>[
                                'table'=>'sd_workflow_activities',
                                'type'=>'INNER',
                                'conditions'=>['curActivity.id = '.$currentActivity['id'],'curActivity.sd_workflow_id = SdWorkflowActivities.sd_workflow_id',
                                    'SdWorkflowActivities.id >= curActivity.order_no - curActivity.step_backward','SdWorkflowActivities.id < curActivity.order_no']
                            ]
                        ])->toArray();
            $parceObj = [];
            foreach($previousActivities as $previousActivity){
                $previousUserOnPreviousActivity = TableRegistry::get('SdCaseHistories')->find()
                            ->select(['sd_user_id','user.firstname','user.lastname','company.company_name','close_time'])
                            ->join([
                                'user'=>[
                                    'table'=>'sd_users',
                                    'type'=>'INNER',
                                    'conditions'=>['user.id = SdCaseHistories.sd_user_id']
                                ],
                                'company'=>[
                                    'table'=>'sd_companies',
                                    'type'=>'INNER',
                                    'conditions'=>['company.id = user.sd_company_id']
                                ]
                            ])
                            ->where(['sd_case_id'=>$case['id'],'sd_workflow_activity_id'=>$previousActivity['id']])
                            ->order(['close_time'=>'DESC'])->toArray();
                $parceObj[$previousActivity['id']] = $previousActivity;
                $parceObj[$previousActivity['id']]['previousUserOnPreviousActivity'] = $previousUserOnPreviousActivity;
                $users = $this->SdUsers->find()
                ->select(['SdUsers.id','SdUsers.firstname','SdUsers.lastname'])
                ->contain(['SdCases'=>function($q){
                    return $q->select(['casesCount'=>$q->func()->count('SdCases.id'),'SdCases.sd_user_id']);
                }])
                ->join([
                    'ua'=>[
                        'table'=>'sd_user_assignments',
                        'type'=>'INNER',
                        'conditions'=>['ua.sd_product_workflow_id ='.$case['sd_product_workflow_id'],
                                        'ua.sd_workflow_activity_id = '.$previousActivity['id'],'ua.sd_user_id = SdUsers.id']
                    ]
                ])->toArray();

                $parceObj[$previousActivity['id']]['users'] = $users;
            }
            echo json_encode($parceObj);

            die();
        }
    }
    public function searchNextAvailable($caseNo, $versionNo=1, $caseDistributionId = null){
        if($this->request->is('POST')){
            if($caseDistributionId == null) $distribution_condition = "SdFieldValues.sd_case_distribution_id IS NULL";
            else $distribution_condition = "SdFieldValues.sd_case_distribution_id ='".$caseDistributionId."'";
            $this->autoRender = false;
            $LinksTable = TableRegistry::get('SdAssessmentDistributionLinks');
            $caseDistributionTable = TableRegistry::get('SdCaseDistributions');
            $case = TableRegistry::get('SdCases')->find()->where(['caseNo'=>$caseNo,'version_no'=>$versionNo])->first();
            if($caseDistributionId == "null"||$caseDistributionId == null) {
                $currentActivity = TableRegistry::get('SdWorkflowActivities')->get($case['sd_workflow_activity_id']);
                $newtOrder = $currentActivity['order_no']+1;
                $nextActivity = TableRegistry::get('SdWorkflowActivities')->find()
                            ->select(['SdWorkflowActivities.id','SdWorkflowActivities.activity_name','pw.id','SdWorkflowActivities.order_no'])
                            ->join([
                                'pw'=>[
                                    'table'=>'sd_product_workflows',
                                    'type'=>'INNER',
                                    'conditions'=>['SdWorkflowActivities.sd_workflow_id = pw.sd_workflow_id','pw.id ='.$case['sd_product_workflow_id']]
                                ]
                            ])
                            ->where(['SdWorkflowActivities.order_no'=>$newtOrder])->toArray();

            }else{
                $caseDistribution = $caseDistributionTable->get($caseDistributionId);
                $links = $LinksTable->get($caseDistribution['sd_assessment_distribution_link_id']);
                $currentActivity = TableRegistry::get('SdWorkflowActivities')->get($caseDistribution['sd_workflow_activity_id']);
                $nextActivity = TableRegistry::get('SdWorkflowActivities')->find()
                            ->select(['SdWorkflowActivities.id','SdWorkflowActivities.activity_name','SdWorkflowActivities.order_no'])
                            ->join([
                                'links'=>[
                                    'table'=>'sd_assessment_distribution_links',
                                    'type'=>'INNER',
                                    'conditions'=>['links.id'=>$links['id'], 'links.distribution = SdWorkflowActivities.sd_workflow_id']
                                ]
                            ])
                            ->where(['SdWorkflowActivities.order_no'=>($currentActivity['order_no']+1)])->toArray();
                            // debug($nextActivity);debug($currentActivity);
            }
            if(count($nextActivity)==0){
                //TODO
                $activity = [];
                $productWorkflow = TableRegistry::get('SdProductWorkflows')->get(['id'=>$case['sd_product_workflow_id']]);
                $workflow = TableRegistry::get('SdWorkflows')->get($productWorkflow['sd_workflow_id']);
                if($workflow['classification'] == 1) //end of distribution
                    $parceObj['actvity'] = "end";
                else{
                    $links_activities = TableRegistry::get("SdWorkflowActivities")->find()
                                    ->select(['SdWorkflowActivities.id','SdWorkflowActivities.order_no','SdWorkflowActivities.activity_name','links.distribution','links.id'])
                                    ->join([
                                        'links'=>[
                                            'table'=>'sd_assessment_distribution_links',
                                            'type'=>'INNER',
                                            'conditions'=>['SdWorkflowActivities.sd_workflow_id = links.distribution',
                                                        'links.sd_product_workflow_id'=>$case['sd_product_workflow_id']]
                                        ]
                                    ])
                                    ->where(['SdWorkflowActivities.order_no'=>'1']);
                    foreach($links_activities as $key => $activity_detail){
                        $activity = [];
                        $previousUserOnNextActivity = TableRegistry::get('SdCaseHistories')->find()
                                    ->select(['sd_user_id','user.firstname','user.lastname','company.company_name'])
                                    ->join([
                                        'user'=>[
                                            'table'=>'sd_users',
                                            'type'=>'INNER',
                                            'conditions'=>['user.id = SdCaseHistories.sd_user_id']
                                        ],
                                        'company'=>[
                                            'table'=>'sd_companies',
                                            'type'=>'INNER',
                                            'conditions'=>['company.id = user.sd_company_id']
                                        ]
                                    ])
                                    ->where(['sd_case_id'=>$case['id'],'sd_workflow_activity_id'=>$activity_detail['id']])
                                    ->order(['close_time'=>'DESC'])->toArray();
                        $activity[$key]['previousUserOnNextActivity'] = $previousUserOnNextActivity;
                        $activity[$key]['activity'] = $activity_detail;
                        $users = $this->SdUsers->find()
                        ->select(['SdUsers.id','SdUsers.firstname','SdUsers.lastname'])
                        ->contain(['SdCases'=>function($q){
                            return $q->select(['casesCount'=>$q->func()->count('SdCases.id'),'SdCases.sd_user_id']);
                        }])
                        ->join([
                            'ua'=>[
                                'table'=>'sd_user_assignments',
                                'type'=>'INNER',
                                'conditions'=>['ua.sd_product_workflow_id ='.$case['sd_product_workflow_id'],
                                                'ua.sd_workflow_activity_id = '.$activity_detail['id'],'ua.sd_user_id = SdUsers.id']
                            ]
                        ])->toArray();
                        $activity[$key]['users'] = $users;
                    }
                    $activity['caseValidate'] = $this->request->getSession()->read('caseValidate.'.$case['id']);
                    $parceObj = $activity;
                }
            }else{
                $activity = [];
                $previousUserOnNextActivity = TableRegistry::get('SdCaseHistories')->find()
                            ->select(['sd_user_id','user.firstname','user.lastname','company.company_name'])
                            ->join([
                                'user'=>[
                                    'table'=>'sd_users',
                                    'type'=>'INNER',
                                    'conditions'=>['user.id = SdCaseHistories.sd_user_id']
                                ],
                                'company'=>[
                                    'table'=>'sd_companies',
                                    'type'=>'INNER',
                                    'conditions'=>['company.id = user.sd_company_id']
                                ]
                            ])
                            ->where(['sd_case_id'=>$case['id'],'sd_workflow_activity_id'=>$nextActivity['0']['id']])
                            ->order(['close_time'=>'DESC'])->toArray();
                $activity['previousUserOnNextActivity'] = $previousUserOnNextActivity;
                $activity['actvity'] = $nextActivity['0'];
                $users = $this->SdUsers->find()
                ->select(['SdUsers.id','SdUsers.firstname','SdUsers.lastname'])
                ->contain(['SdCases'=>function($q){
                    return $q->select(['casesCount'=>$q->func()->count('SdCases.id'),'SdCases.sd_user_id']);
                }])
                ->join([
                    'ua'=>[
                        'table'=>'sd_user_assignments',
                        'type'=>'INNER',
                        'conditions'=>['ua.sd_product_workflow_id ='.$case['sd_product_workflow_id'],
                                        'ua.sd_workflow_activity_id = '.$nextActivity['0']['id'],'ua.sd_user_id = SdUsers.id']
                    ]
                ])->toArray();
                // debug($nextActivity['0']['id']);
                $activity['users'] = $users;
                $activity['caseValidate'] = $this->request->getSession()->read('caseValidate.'.$case['id']);
                $parceObj['one'] = $activity;
            }
            echo json_encode($parceObj);
            die();
        }
    }
    public function myaccount() {
        $this->viewBuilder()->setLayout('main_layout');
        $userID = $this->request->getSession()->read('Auth.User.id');
        if($this->request->is('POST')){
            $request = $this->request->getData();
            $sdUser = $this->SdUsers->get($userID);

            $sdUser['email'] = $request['email'];
            $sdUser['firstname'] = $request['fName'];
            $sdUser['lastname'] = $request['lName'];
            $sdUser['password'] = $request['pw'];

            $sdUser['password'] = (new DefaultPasswordHasher)->hash($request['pw']);
            //debug($sdUser['password']);

            if ($this->SdUsers->save($sdUser)) {
                $this->Flash->success(__('Your info has been saved.'));
                $session = $this->getRequest()->getSession()->write([
                    'Auth.User.email' => $request['email'],
                    'Auth.User.firstname' => $request['fName'],
                    'Auth.User.lastname' => $request['lName'],
                    'Auth.User.password' => $request['pw'],
                ]);
                return $this->redirect(['action' => 'myaccount']);
            }
            $this->Flash->error(__('Your info could not be saved. Please, try again.'));
        }

        $this->set(compact('userID'));
    }
}
