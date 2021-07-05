<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * SdInputHistories Controller
 *
 * @property \App\Model\Table\SdInputHistoriesTable $SdInputHistories
 *
 * @method \App\Model\Entity\SdInputHistory[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SdInputHistoriesController extends AppController
{

    public function search($caseId)
    {
        $this->viewBuilder()->setLayout('main_layout');

            if($this->request->is('POST')){        
                $inputHistoryData = $this->SdInputHistories->find()
                    ->select(['SdInputHistories.time_changed','SdInputHistories.input','user.firstname','user.lastname','user.email','activity.activity_name','field.field_label'])
                    ->join([
                    'field_value'=>[
                        'table' =>'sd_field_values',
                        'type'=>'INNER',
                        'conditions'=>['field_value.id = SdInputHistories.sd_field_value_id','field_value.sd_case_id ='.$caseId]
                    ],
                    'field'=>[
                        'table' => 'sd_fields', 
                        'type' => 'INNER',
                        'conditions' =>['field_value.sd_field_id = field.id']
                    ],
                    'user' =>[
                        'table' => 'sd_users',
                        'type' => 'INNER',
                        'conditions' => ['SdInputHistories.sd_user_id = user.id']
                    ],
                    'activity'=>[
                        'table' => 'sd_workflow_activities',
                        'type' => 'INNER',
                        'conditions' => ['SdInputHistories.sd_workflow_activity_id = activity.id']
                    ],
                ]);
                $this->autoRender = false;
                echo json_encode($inputHistoryData);
                die();
            }
            $this->set(compact('caseId'));
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['SdFieldValues', 'SdUsers']
        ];
        $sdInputHistories = $this->paginate($this->SdInputHistories);
        $this->set(compact('sdInputHistories'));
    }

    /**
     * View method
     *
     * @param string|null $id Sd Input History id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $sdInputHistory = $this->SdInputHistories->get($id, [
            'contain' => ['SdFieldValues', 'SdUsers']
        ]);

        $this->set('sdInputHistory', $sdInputHistory);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $sdInputHistory = $this->SdInputHistories->newEntity();
        if ($this->request->is('post')) {
            $sdInputHistory = $this->SdInputHistories->patchEntity($sdInputHistory, $this->request->getData());
            if ($this->SdInputHistories->save($sdInputHistory)) {
                $this->Flash->success(__('The sd input history has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sd input history could not be saved. Please, try again.'));
        }
        $sdFieldValues = $this->SdInputHistories->SdFieldValues->find('list', ['limit' => 200]);
        $sdUsers = $this->SdInputHistories->SdUsers->find('list', ['limit' => 200]);
        $this->set(compact('sdInputHistory', 'sdFieldValues', 'sdUsers'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Sd Input History id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sdInputHistory = $this->SdInputHistories->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $sdInputHistory = $this->SdInputHistories->patchEntity($sdInputHistory, $this->request->getData());
            if ($this->SdInputHistories->save($sdInputHistory)) {
                $this->Flash->success(__('The sd input history has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sd input history could not be saved. Please, try again.'));
        }
        $sdFieldValues = $this->SdInputHistories->SdFieldValues->find('list', ['limit' => 200]);
        $sdUsers = $this->SdInputHistories->SdUsers->find('list', ['limit' => 200]);
        $this->set(compact('sdInputHistory', 'sdFieldValues', 'sdUsers'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Sd Input History id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sdInputHistory = $this->SdInputHistories->get($id);
        if ($this->SdInputHistories->delete($sdInputHistory)) {
            $this->Flash->success(__('The sd input history has been deleted.'));
        } else {
            $this->Flash->error(__('The sd input history could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
