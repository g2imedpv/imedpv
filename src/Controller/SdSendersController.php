<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * SdSenders Controller
 *
 * @property \App\Model\Table\SdSendersTable $SdSenders
 *
 * @method \App\Model\Entity\SdSender[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SdSendersController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['SdCompanies']
        ];
        $sdSenders = $this->paginate($this->SdSenders);

        $this->set(compact('sdSenders'));
    }

    /**
     * View method
     *
     * @param string|null $id Sd Sender id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $sdSender = $this->SdSenders->get($id, [
            'contain' => ['SdCompanies']
        ]);

        $this->set('sdSender', $sdSender);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $sdSender = $this->SdSenders->newEntity();
        if ($this->request->is('post')) {
            $sdSender = $this->SdSenders->patchEntity($sdSender, $this->request->getData());
            if ($this->SdSenders->save($sdSender)) {
                $this->Flash->success(__('The sd sender has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sd sender could not be saved. Please, try again.'));
        }
        $sdCompanies = $this->SdSenders->SdCompanies->find('list', ['limit' => 200]);
        $this->set(compact('sdSender', 'sdCompanies'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Sd Sender id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sdSender = $this->SdSenders->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $sdSender = $this->SdSenders->patchEntity($sdSender, $this->request->getData());
            if ($this->SdSenders->save($sdSender)) {
                $this->Flash->success(__('The sd sender has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sd sender could not be saved. Please, try again.'));
        }
        $sdCompanies = $this->SdSenders->SdCompanies->find('list', ['limit' => 200]);
        $this->set(compact('sdSender', 'sdCompanies'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Sd Sender id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sdSender = $this->SdSenders->get($id);
        if ($this->SdSenders->delete($sdSender)) {
            $this->Flash->success(__('The sd sender has been deleted.'));
        } else {
            $this->Flash->error(__('The sd sender could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }


    public function addsender()
    {
        // Only Admin user can access this page
        if($this->request->getSession()->read('Auth.User.sd_role_id') !== 2) {
            $this->Flash->error(__('You don\'t have permission to access this page'));
            return $this->redirect(['controller' => 'Dashboards', 'action' => 'index']);
        }

        $this->viewBuilder()->setLayout('main_layout');

        // Get current signed in company name
        $companyID = $this->request->getSession()->read('Auth.User.company_id');
        $company = TableRegistry::get('SdCompanies')->find()->where(['id'=>$companyID])->select(['company_name'])->first()->company_name;
        $sdSender = $this->SdSenders->find()->where(['sd_company_id'=>$companyID])->first();

        if ($this->request->is('post')) {
            if($sdSender == null){
                $sdSender = $this->SdSenders->newEntity();
            }
            $senderInfo = $this->request->getData()['sender'];
            $sdSender = $this->SdSenders->patchEntity( $sdSender,$senderInfo );
            //debug($senderInfo['sd_company_id']);die();

            if ($this->SdSenders->save($sdSender)) {
                $this->Flash->success(__('The sender has been saved.'));

                return $this->redirect(['action' => 'addsender']);
            }
            $this->Flash->error(__('The sender could not be saved. Please, try again.'));
        }

        $senderType = [
            [1,"Pharmaceutical Company"],
            [2,"Regulatory Authority"],
            [3,"Health Professional"],
            [4,"Regional Pharmacovigilance Centre"],
            [5,"WHO collaborating centres for international drug monitoring"],
            [6,"Other (e.g. distributor or other organisation)"],
            [7,"Patient / Consumer"]
        ];

        $country = [
            ['US','United States'],
            ['CN','China'],
            ['JP','Japan']
        ];

        $this->set(compact('company','sdSender','senderType','country'));
    }
}
