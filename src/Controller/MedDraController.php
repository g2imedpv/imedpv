<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;


/**
 * MedDra Controller
 *
 *
 * @method \App\Model\Entity\MedDra[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MedDraController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $medDra = $this->paginate($this->MedDra);

        $this->set(compact('medDra'));
    }

    /**
     * View method
     *
     * @param string|null $id Med Dra id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $medDra = $this->MedDra->get($id, [
            'contain' => []
        ]);

        $this->set('medDra', $medDra);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $medDra = $this->MedDra->newEntity();
        if ($this->request->is('post')) {
            $medDra = $this->MedDra->patchEntity($medDra, $this->request->getData());
            if ($this->MedDra->save($medDra)) {
                $this->Flash->success(__('The med dra has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The med dra could not be saved. Please, try again.'));
        }
        $this->set(compact('medDra'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Med Dra id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $medDra = $this->MedDra->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $medDra = $this->MedDra->patchEntity($medDra, $this->request->getData());
            if ($this->MedDra->save($medDra)) {
                $this->Flash->success(__('The med dra has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The med dra could not be saved. Please, try again.'));
        }
        $this->set(compact('medDra'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Med Dra id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $medDra = $this->MedDra->get($id);
        if ($this->MedDra->delete($medDra)) {
            $this->Flash->success(__('The med dra has been deleted.'));
        } else {
            $this->Flash->error(__('The med dra could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    /**
     * search Meddra
     * 
     * 
     * 
     */
    public function search(){
        
        $userinfo = $this->request->getSession()->read('Auth.user');
        if($this->request->is('POST')){
            $this->autoRender = false;
            $searchKey = $this->request->getData();
            $wildCardKey = trim($searchKey['wildcard_search']);
            $lltTerm = trim($searchKey['llt_term']);
            $ptTerm = trim($searchKey['pt_term']);
            $hltTerm = trim($searchKey['hlt_term']);
            $hlgtTerm = trim($searchKey['hlgt_term']);
            $socTerm = trim($searchKey['soc_term']);
            $conn = ConnectionManager::get('default');
            $qptb = $conn->newQuery();

            try{
                $qptb->select(['meddra.*', 
                            'primary_soc_name'=>'soc.soc_name',
                            'primary_soc_code'=>'soc.soc_code'])->from("meddra");
                $qptb->join([
                    'pt' =>[
                        'table' =>'mdr_pref_term',
                        'type' =>'LEFT',
                        'conditions'=>['meddra.pt_code = pt.pt_code'],
                    ],
                    'soc' =>[
                        'table' =>'mdr_soc_term',
                        'type'=>'LEFT',
                        'conditions'=>['pt.pt_soc_code = soc.soc_code'],
                    ],
                    // 'dc' => [
                    //     'table' => 'denormcomposition',
                    //     'type' => 'LEFT',
                    //     'conditions' => ['mpdata.DrugRecNo = dc.DrugRecNo','mpdata.Seq1 = dc.Seq1','mpdata.Seq2 = dc.Seq2'],
                    // ],
                    // 'it' => [
                    //     'table' => 'ingredient',
                    //     'type' => 'LEFT',
                    //     'conditions' => ['mpdata.DrugRecNo = it.DrugRecNo','mpdata.Seq1 = it.Seq1', 'mpdata.Seq2 = it.Seq2'],
                    // ],
                    // 'cc' =>[
                    //     'table' => 'ccode',
                    //     'type' => 'LEFT',
                    //     'conditions' => 'mpdata.Country = cc.CountryCode'
                    // ],
                    // 'oz' =>[
                    //     'table' => 'organization',
                    //     'type' => 'LEFT',
                    //     'conditions' => 'mpdata.MAH = oz.OrganizationID'
                    // ],
                    // 'pf' =>[
                    //     'table' => 'pharmform',
                    //     'type' => 'LEFT',
                    //     'conditions' => 'mpdata.Seq3 = pf.PharmFormID'
                    // ],
                    // 'st'=>[
                    //     'table'=>'substance',
                    //     'type' => 'LEFT',
                    //     'conditions' => 'it.SubstanceID = st.SubstanceID'
                    // ],
                    // 'sr' =>[
                    //     'table' => 'strength',
                    //     'type' => 'LEFT',
                    //     'conditions' => 'mpdata.Seq4 = sr.StrengthID'
                    // ]
                ]);

                if( $lltTerm != '' ){
                    $qptb->where(['meddra.llt_name LIKE \'%'.$lltTerm.'%\'']);
                };
                if( $ptTerm != '' ){
                    $qptb->where(['meddra.pt_name LIKE \'%'.$ptTerm.'%\'']);
                };
                if( $hltTerm != '' ){
                    $qptb->where(['meddra.hlt_name LIKE \'%'.$hltTerm.'%\'']);
                };
                if( $hlgtTerm != '' ){
                    $qptb->where(['meddra.hlgt_name LIKE \'%'.$hlgtTerm.'%\'']);
                };
                if( $socTerm != '' ){
                    $qptb->where(['meddra.soc_name LIKE \'%'.$socTerm.'%\'']);
                };         

                $drugList = $conn->execute($qptb->distinct())->fetchAll();
                echo json_encode($drugList);
            }catch (\PDOException $e){
                echo $qptb;
                echo "cannot the case find in database";
            }
            // $this->set(compact('searchResult'));
            die();
        } else $this->autoRender = true;
    }
}
