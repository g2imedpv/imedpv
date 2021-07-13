<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * SdActivityLogs Controller
 *
 * @property \App\Model\Table\SdActivityLogsTable $SdActivityLogs
 *
 * @method \App\Model\Entity\SdActivityLog[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SdActivityLogsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['SdUsers', 'SdSectionValues', 'SdActvities']
        ];
        $sdActivityLogs = $this->paginate($this->SdActivityLogs);

        $this->set(compact('sdActivityLogs'));
    }

    /**
     * View method
     *
     * @param string|null $id Sd Activity Log id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $sdActivityLog = $this->SdActivityLogs->get($id, [
            'contain' => ['SdUsers', 'SdSectionValues', 'SdActvities']
        ]);

        $this->set('sdActivityLog', $sdActivityLog);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $sdActivityLog = $this->SdActivityLogs->newEntity();
        if ($this->request->is('post')) {
            $sdActivityLog = $this->SdActivityLogs->patchEntity($sdActivityLog, $this->request->getData());
            if ($this->SdActivityLogs->save($sdActivityLog)) {
                $this->Flash->success(__('The sd activity log has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sd activity log could not be saved. Please, try again.'));
        }
        $sdUsers = $this->SdActivityLogs->SdUsers->find('list', ['limit' => 200]);
        $sdSectionValues = $this->SdActivityLogs->SdSectionValues->find('list', ['limit' => 200]);
        $sdActvities = $this->SdActivityLogs->SdActvities->find('list', ['limit' => 200]);
        $this->set(compact('sdActivityLog', 'sdUsers', 'sdSectionValues', 'sdActvities'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Sd Activity Log id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sdActivityLog = $this->SdActivityLogs->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $sdActivityLog = $this->SdActivityLogs->patchEntity($sdActivityLog, $this->request->getData());
            if ($this->SdActivityLogs->save($sdActivityLog)) {
                $this->Flash->success(__('The sd activity log has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sd activity log could not be saved. Please, try again.'));
        }
        $sdUsers = $this->SdActivityLogs->SdUsers->find('list', ['limit' => 200]);
        $sdSectionValues = $this->SdActivityLogs->SdSectionValues->find('list', ['limit' => 200]);
        $sdActvities = $this->SdActivityLogs->SdActvities->find('list', ['limit' => 200]);
        $this->set(compact('sdActivityLog', 'sdUsers', 'sdSectionValues', 'sdActvities'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Sd Activity Log id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sdActivityLog = $this->SdActivityLogs->get($id);
        if ($this->SdActivityLogs->delete($sdActivityLog)) {
            $this->Flash->success(__('The sd activity log has been deleted.'));
        } else {
            $this->Flash->error(__('The sd activity log could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
