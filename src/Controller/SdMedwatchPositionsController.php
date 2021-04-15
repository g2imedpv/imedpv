<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * SdMedwatchPositions Controller
 *
 * @property \App\Model\Table\SdMedwatchPositionsTable $SdMedwatchPositions
 *
 * @method \App\Model\Entity\SdMedwatchPosition[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SdMedwatchPositionsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['SdFields']
        ];
        $sdMedwatchPositions = $this->paginate($this->SdMedwatchPositions);

        $this->set(compact('sdMedwatchPositions'));
    }

    /**
     * View method
     *
     * @param string|null $id Sd Medwatch Position id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $sdMedwatchPosition = $this->SdMedwatchPositions->get($id, [
            'contain' => ['SdFields']
        ]);

        $this->set('sdMedwatchPosition', $sdMedwatchPosition);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $sdMedwatchPosition = $this->SdMedwatchPositions->newEntity();
        if ($this->request->is('post')) {
            $sdMedwatchPosition = $this->SdMedwatchPositions->patchEntity($sdMedwatchPosition, $this->request->getData());
            if ($this->SdMedwatchPositions->save($sdMedwatchPosition)) {
                $this->Flash->success(__('The sd medwatch position has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sd medwatch position could not be saved. Please, try again.'));
        }
        $sdFields = $this->SdMedwatchPositions->SdFields->find('list', ['limit' => 200]);
        $this->set(compact('sdMedwatchPosition', 'sdFields'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Sd Medwatch Position id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sdMedwatchPosition = $this->SdMedwatchPositions->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $sdMedwatchPosition = $this->SdMedwatchPositions->patchEntity($sdMedwatchPosition, $this->request->getData());
            if ($this->SdMedwatchPositions->save($sdMedwatchPosition)) {
                $this->Flash->success(__('The sd medwatch position has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sd medwatch position could not be saved. Please, try again.'));
        }
        $sdFields = $this->SdMedwatchPositions->SdFields->find('list', ['limit' => 200]);
        $this->set(compact('sdMedwatchPosition', 'sdFields'));
    } 

    /**
     * Delete method
     *
     * @param string|null $id Sd Medwatch Position id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sdMedwatchPosition = $this->SdMedwatchPositions->get($id);
        if ($this->SdMedwatchPositions->delete($sdMedwatchPosition)) {
            $this->Flash->success(__('The sd medwatch position has been deleted.'));
        } else {
            $this->Flash->error(__('The sd medwatch position could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
