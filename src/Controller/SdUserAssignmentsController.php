<?php
namespace App\Controller;
use Cake\ORM\TableRegistry;

use App\Controller\AppController;

/**
 * SdUserAssignments Controller
 *
 * @property \App\Model\Table\SdUserAssignmentsTable $SdUserAssignments
 *
 * @method \App\Model\Entity\SdUserAssignment[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SdUserAssignmentsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['SdProductAssignments', 'SdUsers', 'SdActivities']
        ];
        $sdUserAssignments = $this->paginate($this->SdUserAssignments);

        $this->set(compact('sdUserAssignments'));
    }

    /**
     * View method
     *
     * @param string|null $id Sd User Assignment id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $sdUserAssignment = $this->SdUserAssignments->get($id, [
            'contain' => ['SdProductAssignments', 'SdUsers', 'SdActivities']
        ]);

        $this->set('sdUserAssignment', $sdUserAssignment);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $sdUserAssignment = $this->SdUserAssignments->newEntity();
        if ($this->request->is('post')) {
            $sdUserAssignment = $this->SdUserAssignments->patchEntity($sdUserAssignment, $this->request->getData());
            if ($this->SdUserAssignments->save($sdUserAssignment)) {
                $this->Flash->success(__('The sd user assignment has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sd user assignment could not be saved. Please, try again.'));
        }
        $sdProductAssignments = $this->SdUserAssignments->SdProductAssignments->find('list', ['limit' => 200]);
        $sdUsers = $this->SdUserAssignments->SdUsers->find('list', ['limit' => 200]);
        $sdActivities = $this->SdUserAssignments->SdActivities->find('list', ['limit' => 200]);
        $this->set(compact('sdUserAssignment', 'sdProductAssignments', 'sdUsers', 'sdActivities'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Sd User Assignment id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sdUserAssignment = $this->SdUserAssignments->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $sdUserAssignment = $this->SdUserAssignments->patchEntity($sdUserAssignment, $this->request->getData());
            if ($this->SdUserAssignments->save($sdUserAssignment)) {
                $this->Flash->success(__('The sd user assignment has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sd user assignment could not be saved. Please, try again.'));
        }
        $sdProductAssignments = $this->SdUserAssignments->SdProductAssignments->find('list', ['limit' => 200]);
        $sdUsers = $this->SdUserAssignments->SdUsers->find('list', ['limit' => 200]);
        $sdActivities = $this->SdUserAssignments->SdActivities->find('list', ['limit' => 200]);
        $this->set(compact('sdUserAssignment', 'sdProductAssignments', 'sdUsers', 'sdActivities'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Sd User Assignment id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sdUserAssignment = $this->SdUserAssignments->get($id);
        if ($this->SdUserAssignments->delete($sdUserAssignment)) {
            $this->Flash->success(__('The sd user assignment has been deleted.'));
        } else {
            $this->Flash->error(__('The sd user assignment could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    /**
     * Method for allocating teamresource
     * 
     * 
     */
    public function allocateTeam($productWorkflowId){
        if ($this->request->is('post')) {
            $this->autoRender = false;
            try{
                $searchKey = $this->request->getData();
                // delete all data of this workflow
                $sdUserAssignment = $this->SdUserAssignments->find()->where(['sd_workflow_activity_id !='=>'0','sd_product_workflow_id'=>$productWorkflowId])->first();
                // debug($sdUserAssignment);
                while($sdUserAssignment!=null){
                    if (!$deletedSet = $this->SdUserAssignments->delete($sdUserAssignment)) {
                        debug($deletedSet);
                    }
                    $sdUserAssignment = $this->SdUserAssignments->find()->where(['sd_workflow_activity_id !='=>'0','sd_product_workflow_id'=>$productWorkflowId])->first();
                };
                foreach($searchKey as $sdWorkflowActivtiyId =>$assignmentDetail){
                    foreach($assignmentDetail as $sdUserId){
                        $dataSet = [
                            'sd_user_id'=>$sdUserId,
                            'sd_product_workflow_id'=>$productWorkflowId,
                            'sd_workflow_activity_id' => $sdWorkflowActivtiyId
                        ];
                        $sdUserAssignment = $this->SdUserAssignments->newEntity();
                        $sdUserAssignment = $this->SdUserAssignments->patchEntity($sdUserAssignment, $dataSet);
                        // debug($sdUserAssignment);
                        if (!$this->SdUserAssignments->save($sdUserAssignment)) echo "error in saving";
                    }
                }
                $sdProductWorkflow = TableRegistry::get("SdProductWorkflows")->get($productWorkflowId);
                $sdProductWorkflow['status'] = 2;
                debug($sdProductWorkflow);
                if (!TableRegistry::get("SdProductWorkflows")->save($sdProductWorkflow)){
                    $this->Flash->error(__('The sdProductWorkflow could not be updated. Please, try again.'));
                }
            }catch (\PDOException $e){
                echo "cannot save into database";
            }
            // $this->set(compact('searchResult'));
            die();
        }
    }
}
