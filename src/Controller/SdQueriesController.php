<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * SdQueries Controller
 *
 * @property \App\Model\Table\SdQueriesTable $SdQueries
 *
 * @method \App\Model\Entity\SdQuery[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SdQueriesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $sdQueries = $this->paginate($this->SdQueries);

        $this->set(compact('sdQueries'));
    }

    /**
     * View method
     *
     * @param string|null $id Sd Query id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->viewBuilder()->setLayout('main_layout');
        $sdQuery = $this->SdQueries->find()
                ->select(['id','title','content','query_type','sender','receiver','send_date','read_date','query_status',
                        'senderEmail'=>'sender_info.email','senderCompany'=>'sender_info.company_name','senderfirstname'=>'sender_info.firstname','senderlastname'=>'sender_info.lastname',
                        'receiverEmail'=>'receiver_info.email','receiverCompany'=>'receiver_info.company_name','receiverfirstname'=>'receiver_info.firstname','receiverlastname'=>'receiver_info.lastname'])
                ->join([
                'sender_info'=>[
                    'table'=>'user_role_company',
                    'type'=>'LEFT',
                    'conditions'=>['sender_info.id = SdQueries.sender']
                ],
                'receiver_info'=>[
                    'table'=>'user_role_company',
                    'type'=>'LEFT',
                    'conditions'=>['receiver_info.id = SdQueries.receiver']
                ]
                ])->where(['SdQueries.id'=>$id])->first();
        $userinfo = $this->request->getSession()->read('Auth.User');
        if(($sdQuery['sender']!=$userinfo['id'])&&($sdQuery['receiver']!=$userinfo['id']))
        {
            $this->Flash->error(__('Cannot find this query.'));
            $this->redirect($this->referer());
        }

        if($sdQuery['receiver']==$userinfo['id']){
            if(empty($sdQuery['read_date'])){
                $sdQuery['read_date'] = date("Y-m-d H:i:s");
                $this->SdQueries->save($sdQuery);
            }
        }
        $this->set('sdQuery', $sdQuery);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $sdQuery = $this->SdQueries->newEntity();
        if ($this->request->is('post')) {
            $sdQuery = $this->SdQueries->patchEntity($sdQuery, $this->request->getData());
            if ($this->SdQueries->save($sdQuery)) {
                $this->Flash->success(__('The sd query has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sd query could not be saved. Please, try again.'));
        }
        $this->set(compact('sdQuery'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Sd Query id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sdQuery = $this->SdQueries->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $sdQuery = $this->SdQueries->patchEntity($sdQuery, $this->request->getData());
            if ($this->SdQueries->save($sdQuery)) {
                $this->Flash->success(__('The sd query has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sd query could not be saved. Please, try again.'));
        }
        $this->set(compact('sdQuery'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Sd Query id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sdQuery = $this->SdQueries->get($id);
        if ($this->SdQueries->delete($sdQuery)) {
            $this->Flash->success(__('The sd query has been deleted.'));
        } else {
            $this->Flash->error(__('The sd query could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    /**
     * Query Inbox
     *
     */
    public function queries($type = null){
        $userId = $this->request->getSession()->read('Auth.User.id');
        $this->viewBuilder()->setLayout('main_layout');
        switch ($type){
            case null:
                $sdQueries = $this->SdQueries->find()
                                ->select(['id','title','content','query_type','sender','receiver','send_date','read_date','query_status','senderfirstname'=>'sender_info.firstname','senderlastname'=>'sender_info.lastname'])
                                ->where(['receiver'=>$userId,'receiver_status'=>1])
                                ->join([
                                    'sender_info'=>[
                                        'table'=>'sd_users',
                                        'type'=>'LEFT',
                                        'conditions'=>['sender_info.id = SdQueries.sender']
                                    ]
                                ]);
                continue;
            case "unread":
                $sdQueries = $this->SdQueries->find()
                            ->select(['id','title','content','query_type','sender','receiver','send_date','read_date','query_status','senderfirstname'=>'sender_info.firstname','senderlastname'=>'sender_info.lastname'])
                            ->where(['receiver'=>$userId,'receiver_status'=>1,'read_date IS NULL'])
                            ->join([
                                'sender_info'=>[
                                    'table'=>'sd_users',
                                    'type'=>'LEFT',
                                    'conditions'=>['sender_info.id = SdQueries.sender']
                                ]
                            ]);
                continue;
            case "deleted";
                $sdQueries = $this->SdQueries->find()->where(['receiver'=>$userId,'receiver_status'=>0])
                            ->select(['id','title','content','query_type','sender','receiver','send_date','read_date','query_status','senderfirstname'=>'sender_info.firstname','senderlastname'=>'sender_info.lastname'])
                            ->join([
                                'sender_info'=>[
                                    'table'=>'sd_users',
                                    'type'=>'LEFT',
                                    'conditions'=>['sender_info.id = SdQueries.sender']
                                ]
                            ]);
                continue;
            case "flaged";
                $sdQueries = $this->SdQueries->find()->where(['receiver'=>$userId,'receiver_status'=>2])
                            ->select(['id','title','content','query_type','sender','receiver','send_date','read_date','query_status','senderfirstname'=>'sender_info.firstname','senderlastname'=>'sender_info.lastname'])
                            ->join([
                                'sender_info'=>[
                                    'table'=>'sd_users',
                                    'type'=>'LEFT',
                                    'conditions'=>['sender_info.id = SdQueries.sender']
                                ]
                            ]);
                continue;
            case "system":
                $sdQueries = $this->SdQueries->find()->where(['receiver'=>$userId,'receiver_status'=>1,'query_type'=>1])
                            ->select(['id','title','content','query_type','sender','receiver','send_date','read_date','query_status','senderfirstname'=>'sender_info.firstname','senderlastname'=>'sender_info.lastname'])
                            ->join([
                                'sender_info'=>[
                                    'table'=>'sd_users',
                                    'type'=>'LEFT',
                                    'conditions'=>['sender_info.id = SdQueries.sender']
                                ]
                            ]);
                continue;
            case "sent":
                $sdQueries = $this->SdQueries->find()->where(['sender'=>$userId,'sender_deleted'=>0])
                            ->select(['id','title','content','query_type','sender','receiver','send_date','read_date','query_status','senderfirstname'=>'sender_info.firstname','senderlastname'=>'sender_info.lastname'])
                            ->join([
                                'sender_info'=>[
                                    'table'=>'sd_users',
                                    'type'=>'LEFT',
                                    'conditions'=>['sender_info.id = SdQueries.sender']
                                ]
                            ]);
                continue;
        }
        $this->set(compact('sdQueries'));
    }
}
