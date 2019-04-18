<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * SdSectionSets Controller
 *
 * @property \App\Model\Table\SdSectionSetsTable $SdSectionSets
 *
 * @method \App\Model\Entity\SdSectionSet[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SdSectionSetsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['SdSections', 'SdFieldValues']
        ];
        $sdSectionSets = $this->paginate($this->SdSectionSets);

        $this->set(compact('sdSectionSets'));
    }

    /**
     * View method
     *
     * @param string|null $id Sd Section Set id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $sdSectionSet = $this->SdSectionSets->get($id, [
            'contain' => ['SdSections', 'SdFieldValues']
        ]);

        $this->set('sdSectionSet', $sdSectionSet);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $sdSectionSet = $this->SdSectionSets->newEntity();
        if ($this->request->is('post')) {
            $sdSectionSet = $this->SdSectionSets->patchEntity($sdSectionSet, $this->request->getData());
            if ($this->SdSectionSets->save($sdSectionSet)) {
                $this->Flash->success(__('The sd section set has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sd section set could not be saved. Please, try again.'));
        }
        $sdSections = $this->SdSectionSets->SdSections->find('list', ['limit' => 200]);
        $sdFieldValues = $this->SdSectionSets->SdFieldValues->find('list', ['limit' => 200]);
        $this->set(compact('sdSectionSet', 'sdSections', 'sdFieldValues'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Sd Section Set id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sdSectionSet = $this->SdSectionSets->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $sdSectionSet = $this->SdSectionSets->patchEntity($sdSectionSet, $this->request->getData());
            if ($this->SdSectionSets->save($sdSectionSet)) {
                $this->Flash->success(__('The sd section set has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sd section set could not be saved. Please, try again.'));
        }
        $sdSections = $this->SdSectionSets->SdSections->find('list', ['limit' => 200]);
        $sdFieldValues = $this->SdSectionSets->SdFieldValues->find('list', ['limit' => 200]);
        $this->set(compact('sdSectionSet', 'sdSections', 'sdFieldValues'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Sd Section Set id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sdSectionSet = $this->SdSectionSets->get($id);
        if ($this->SdSectionSets->delete($sdSectionSet)) {
            $this->Flash->success(__('The sd section set has been deleted.'));
        } else {
            $this->Flash->error(__('The sd section set could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
