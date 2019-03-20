<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;


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
        $this->request->getSession()->destroy();
        return $this->redirect($this->Auth->logout());
    }

    public function login() {
        $this->viewBuilder()->setLayout('login');
            if ($this->request->is('post')) {
                $sdUser = $this->Auth->identify();
                if ($sdUser) {
                    $this->Auth->setUser($sdUser);
                    $SdRoles = TableRegistry::get('SdRoles')->get($sdUser['sd_role_id']);
                    $session = $this->getRequest()->getgetSession();
                    $session->write('Auth.User.role_name', $SdRoles['description']);
                    return $this->redirect($this->Auth->redirectUrl(
                        // Set the first page after user logged in
                        ['controller' => 'SdCompanies','action' => 'selectCompany']
                    )
                );
                }else {
                    $this->Flash->error(__('Sorry, your username or password is wrong!'));
                }
            }
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
    public function searchNextAvailable($caseNo, $versionNo=1){
        if($this->request->is('POST')){
            $this->autoRender = false;
            $searchKey = $this->request->getData();
            $case = TableRegistry::get('SdCases')->find()->where(['caseNo'=>$caseNo,'version_no'=>$versionNo])->first();
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
                        ->where(['SdWorkflowActivities.order_no'=>$newtOrder])->first();
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
                        ->where(['sd_case_id'=>$case['id'],'sd_workflow_activity_id'=>$nextActivity['id']])
                        ->order(['close_time'=>'DESC'])->toArray();            
            $parceObj = [];
            $parceObj['previousUserOnNextActivity'] = $previousUserOnNextActivity;
            $parceObj['actvity'] = $nextActivity;
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
                                    'ua.sd_workflow_activity_id = '.$nextActivity['id'],'ua.sd_user_id = SdUsers.id']
                ]
            ])->toArray();
            $parceObj['users'] = $users;
            $parceObj['caseValidate'] = $this->request->getSession()->read('caseValidate.'.$case['id']);
            echo json_encode($parceObj);
            die();
        }
    }
    public function myaccount() {
        $this->viewBuilder()->setLayout('main_layout');

        $userID = $this->request->getSession()->read('Auth.User.id');
        $this->set(compact('userID'));
    }
}
