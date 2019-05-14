<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * SdContacts Controller
 *
 * @property \App\Model\Table\SdContactsTable $SdContacts
 *
 * @method \App\Model\Entity\SdContact[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SdContactsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $sdContacts = $this->paginate($this->SdContacts);

        $this->set(compact('sdContacts'));
    }

    /**
     * View method
     *
     * @param string|null $id Sd Contact id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $sdContact = $this->SdContacts->get($id, [
            'contain' => []
        ]);

        $this->set('sdContact', $sdContact);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $sdContact = $this->SdContacts->newEntity();
        if ($this->request->is('post')) {
            $sdContact = $this->SdContacts->patchEntity($sdContact, $this->request->getData());
            if ($this->SdContacts->save($sdContact)) {
                $this->Flash->success(__('The sd contact has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sd contact could not be saved. Please, try again.'));
        }
        $this->set(compact('sdContact'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Sd Contact id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sdContact = $this->SdContacts->get($id, [
            'contain' => []
        ]);
    
        if ($this->request->is(['patch', 'post', 'put'])) {
            $sdContact = $this->SdContacts->patchEntity($sdContact, $this->request->getData());
            if ($this->SdContacts->save($sdContact)) {
                $this->Flash->success(__('The sd contact has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sd contact could not be saved. Please, try again.'));
        }
        $this->set(compact('sdContact'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Sd Contact id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sdContact = $this->SdContacts->get($id);
        if ($this->SdContacts->delete($sdContact)) {
            $this->Flash->success(__('The sd contact has been deleted.'));
        } else {
            $this->Flash->error(__('The sd contact could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    public function addcontact(){
        $this->viewBuilder()->setLayout('main_layout');
        $sdContact = $this->SdContacts->newEntity();
        if ($this->request->is('post')) {
            $sdContact = $this->SdContacts->patchEntity($sdContact, $this->request->getData());
            if ($this->SdContacts->save($sdContact)) {
                $this->Flash->success(__('The new contact has been saved.'));
            }else
            {$this->Flash->error(__('The new contact could not be saved. Please, try again.'));}
        }
        $this->set(compact('sdContact'));
    }
    public function search()
    {
        $this->viewBuilder()->setLayout('main_layout');

    }
    
   
}
