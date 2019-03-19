<?php
namespace App\Controller;
use Cake\ORM\TableRegistry;
use App\Controller\AppController;

/**
 * SdFieldValues Controller
 *
 * @property \App\Model\Table\SdFieldValuesTable $SdFieldValues
 *
 * @method \App\Model\Entity\SdFieldValue[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SdFieldValuesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['SdCases', 'SdFields']
        ];
        $sdFieldValues = $this->paginate($this->SdFieldValues);

        $this->set(compact('sdFieldValues'));
    }

    /**
     * View method
     *
     * @param string|null $id Sd Field Value id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $sdFieldValue = $this->SdFieldValues->get($id, [
            'contain' => ['SdCases', 'SdFields']
        ]);

        $this->set('sdFieldValue', $sdFieldValue);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $sdFieldValue = $this->SdFieldValues->newEntity();
        if ($this->request->is('post')) {
            $sdFieldValue = $this->SdFieldValues->patchEntity($sdFieldValue, $this->request->getData());
            if ($this->SdFieldValues->save($sdFieldValue)) {
                $this->Flash->success(__('The sd field value has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sd field value could not be saved. Please, try again.'));
        }
        $sdCases = $this->SdFieldValues->SdCases->find('list', ['limit' => 200]);
        $sdFields = $this->SdFieldValues->SdFields->find('list', ['limit' => 200]);
        $this->set(compact('sdFieldValue', 'sdCases', 'sdFields'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Sd Field Value id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sdFieldValue = $this->SdFieldValues->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $sdFieldValue = $this->SdFieldValues->patchEntity($sdFieldValue, $this->request->getData());
            if ($this->SdFieldValues->save($sdFieldValue)) {
                $this->Flash->success(__('The sd field value has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sd field value could not be saved. Please, try again.'));
        }
        $sdCases = $this->SdFieldValues->SdCases->find('list', ['limit' => 200]);
        $sdFields = $this->SdFieldValues->SdFields->find('list', ['limit' => 200]);
        $this->set(compact('sdFieldValue', 'sdCases', 'sdFields'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Sd Field Value id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sdFieldValue = $this->SdFieldValues->get($id);
        if ($this->SdFieldValues->delete($sdFieldValue)) {
            $this->Flash->success(__('The sd field value has been deleted.'));
        } else {
            $this->Flash->error(__('The sd field value could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
