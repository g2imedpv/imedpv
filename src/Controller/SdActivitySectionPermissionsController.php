<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * SdActivitySectionPermissions Controller
 *
 * @property \App\Model\Table\SdActivitySectionPermissionsTable $SdActivitySectionPermissions
 *
 * @method \App\Model\Entity\SdActivitySectionPermission[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SdActivitySectionPermissionsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['SdWorkflowActivities', 'SdSections']
        ];
        $sdActivitySectionPermissions = $this->paginate($this->SdActivitySectionPermissions);

        $this->set(compact('sdActivitySectionPermissions'));
    }

    /**
     * View method
     *
     * @param string|null $id Sd Activity Section Permission id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $sdActivitySectionPermission = $this->SdActivitySectionPermissions->get($id, [
            'contain' => ['SdWorkflowActivities', 'SdSections']
        ]);

        $this->set('sdActivitySectionPermission', $sdActivitySectionPermission);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $sdActivitySectionPermission = $this->SdActivitySectionPermissions->newEntity();
        if ($this->request->is('post')) {
            $sdActivitySectionPermission = $this->SdActivitySectionPermissions->patchEntity($sdActivitySectionPermission, $this->request->getData());
            if ($this->SdActivitySectionPermissions->save($sdActivitySectionPermission)) {
                $this->Flash->success(__('The sd activity section permission has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sd activity section permission could not be saved. Please, try again.'));
        }
        $sdWorkflowActivities = $this->SdActivitySectionPermissions->SdWorkflowActivities->find('list', ['limit' => 200]);
        $sdSections = $this->SdActivitySectionPermissions->SdSections->find('list', ['limit' => 200]);
        $this->set(compact('sdActivitySectionPermission', 'sdWorkflowActivities', 'sdSections'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Sd Activity Section Permission id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sdActivitySectionPermission = $this->SdActivitySectionPermissions->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $sdActivitySectionPermission = $this->SdActivitySectionPermissions->patchEntity($sdActivitySectionPermission, $this->request->getData());
            if ($this->SdActivitySectionPermissions->save($sdActivitySectionPermission)) {
                $this->Flash->success(__('The sd activity section permission has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sd activity section permission could not be saved. Please, try again.'));
        }
        $sdWorkflowActivities = $this->SdActivitySectionPermissions->SdWorkflowActivities->find('list', ['limit' => 200]);
        $sdSections = $this->SdActivitySectionPermissions->SdSections->find('list', ['limit' => 200]);
        $this->set(compact('sdActivitySectionPermission', 'sdWorkflowActivities', 'sdSections'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Sd Activity Section Permission id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sdActivitySectionPermission = $this->SdActivitySectionPermissions->get($id);
        if ($this->SdActivitySectionPermissions->delete($sdActivitySectionPermission)) {
            $this->Flash->success(__('The sd activity section permission has been deleted.'));
        } else {
            $this->Flash->error(__('The sd activity section permission could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    public function searchActivityPermission($activity_id){
        if($this->request->is('POST')){
            $this->autoRender = false;
            $sdActivitySectionPermission = $this->SdActivitySectionPermissions
                                        ->find('list',[
                                            'keyField' => 'sd_section_id',
                                            'valueField' => 'action'
                                        ])->where(['sd_workflow_activity_id'=>$activity_id]);
            echo json_encode($sdActivitySectionPermission->all());
            die();
        }
    }
}
