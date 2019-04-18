<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * SdSectionSummaries Controller
 *
 * @property \App\Model\Table\SdSectionSummariesTable $SdSectionSummaries
 *
 * @method \App\Model\Entity\SdSectionSummary[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SdSectionSummariesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['SdSections']
        ];
        $sdSectionSummaries = $this->paginate($this->SdSectionSummaries);

        $this->set(compact('sdSectionSummaries'));
    }

    /**
     * View method
     *
     * @param string|null $id Sd Section Summary id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $sdSectionSummary = $this->SdSectionSummaries->get($id, [
            'contain' => ['SdSections']
        ]);

        $this->set('sdSectionSummary', $sdSectionSummary);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $sdSectionSummary = $this->SdSectionSummaries->newEntity();
        if ($this->request->is('post')) {
            $sdSectionSummary = $this->SdSectionSummaries->patchEntity($sdSectionSummary, $this->request->getData());
            if ($this->SdSectionSummaries->save($sdSectionSummary)) {
                $this->Flash->success(__('The sd section summary has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sd section summary could not be saved. Please, try again.'));
        }
        $sdSections = $this->SdSectionSummaries->SdSections->find('list', ['limit' => 200]);
        $this->set(compact('sdSectionSummary', 'sdSections'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Sd Section Summary id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sdSectionSummary = $this->SdSectionSummaries->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $sdSectionSummary = $this->SdSectionSummaries->patchEntity($sdSectionSummary, $this->request->getData());
            if ($this->SdSectionSummaries->save($sdSectionSummary)) {
                $this->Flash->success(__('The sd section summary has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sd section summary could not be saved. Please, try again.'));
        }
        $sdSections = $this->SdSectionSummaries->SdSections->find('list', ['limit' => 200]);
        $this->set(compact('sdSectionSummary', 'sdSections'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Sd Section Summary id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sdSectionSummary = $this->SdSectionSummaries->get($id);
        if ($this->SdSectionSummaries->delete($sdSectionSummary)) {
            $this->Flash->success(__('The sd section summary has been deleted.'));
        } else {
            $this->Flash->error(__('The sd section summary could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
