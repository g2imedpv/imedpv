<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;

/**
 * WhoDra Controller
 *
 *
 * @method \App\Model\Entity\WhoDra[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class WhoDraController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $whoDra = $this->paginate($this->WhoDra);

        $this->set(compact('whoDra'));
    }

    /**
     * View method
     *
     * @param string|null $id Who Dra id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $whoDra = $this->WhoDra->get($id, [
            'contain' => []
        ]);

        $this->set('whoDra', $whoDra);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $whoDra = $this->WhoDra->newEntity();
        if ($this->request->is('post')) {
            $whoDra = $this->WhoDra->patchEntity($whoDra, $this->request->getData());
            if ($this->WhoDra->save($whoDra)) {
                $this->Flash->success(__('The who dra has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The who dra could not be saved. Please, try again.'));
        }
        $this->set(compact('whoDra'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Who Dra id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $whoDra = $this->WhoDra->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $whoDra = $this->WhoDra->patchEntity($whoDra, $this->request->getData());
            if ($this->WhoDra->save($whoDra)) {
                $this->Flash->success(__('The who dra has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The who dra could not be saved. Please, try again.'));
        }
        $this->set(compact('whoDra'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Who Dra id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $whoDra = $this->WhoDra->get($id);
        if ($this->WhoDra->delete($whoDra)) {
            $this->Flash->success(__('The who dra has been deleted.'));
        } else {
            $this->Flash->error(__('The who dra could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    /*
    * Search WhoDra Method
    */
    public function search(){
        $userinfo = $this->request->getSession()->read('Auth.user');
        if($this->request->is('POST')){
            $this->autoRender = false;
            $searchKey = $this->request->getData();
            $atcCode = trim($searchKey['atc-code']);
            $drugRecNo = trim($searchKey['drug-code']);
            $medProdID = trim($searchKey['medicinal-prod-id']);
            $tradeName = trim($searchKey['trade-name']);
            $ingredient = trim($searchKey['ingredient']);
            $formulation = trim($searchKey['formulation']);
            $strength = trim($searchKey['strength']);
            $country = trim($searchKey['country']);
            $conn = ConnectionManager::get('default');
            $qptb = $conn->newQuery();
            // $columns = [
            //     'trade_name' => 'mpdata.DrugName', 
            //     'atc_code' => 'dc.ATCCodes',
            //     'medicinal_product_id' => 'mpdata.MedProdID',
            //     'mpdata.DrugRecNo',
            //     'mpdata.Seq1',
            //     'mpdata.Seq2',
            //     'mpdata.Seq3',
            //     'mpdata.Seq4',
            //     'generic' => 'mpdata.Generic',
            //     'substance_ids' => 'group_concat(it.SubstanceID)',
            //     'ingredients' => 'group_concat(st.SubstanceName)',            
            //     'formulation' => 'pf.PharmFormText',
            //     'strength' => 'sr.StrengthText',
            //     'mah' => 'oz.OrgName',
            //     'country' => 'cc.countryName',
            //     'group_concat(DISTINCT atc.ATCText)'
            // ];

            try{
                $qptb->select(['test3.*, GROUP_CONCAT(DISTINCT atc.ATCText)'])->from("test3");
                // $qptb->select($columns);
                // $qptb->from("mpdata");
                $qptb->join([
                    'atc' =>[
                        'table' =>'atc',
                        'type'=>'LEFT',
                        'conditions'=>['FIND_IN_SET(atc.ATCCode, REPLACE(test3.atc_code,\' \',\'\'))'],
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

                if( $atcCode != '' ){
                    $qptb->where(['test3.atc_code LIKE \'%'.$atcCode.'%\'']);
                };
                if( $medProdID != '' ){
                    $qptb->where(['test3.medicinal_product_id LIKE \'%'.$MedProdID.'%\'']);
                };
                if( $drugRecNo != '' ){
                    $qptb->where(['test3.DrugRecNo LIKE \'%'.$drugRecNo.'%\'']);
                };
                if( $tradeName != ''){
                    $qptb->where(['test3.trade_name LIKE \'%'.$tradeName.'%\'']);
                };
                if( $country != 'null' ){
                    $qptb->where(['test3.ccode LIKE \''.$country.'\'']);
                };
                
                if( $ingredient != '' ){
                    $qptb->where(['test3.ingredients LIKE \'%'.$ingredient.'%\'']);
                };
                
                if( $formulation != '' ){
                    $qptb->where(['test3.formulation LIKE \'%'.$formulation.'%\'']);
                };
                if( $strength != '' ){
                    $qptb->where(['test3.strength LIKE \'%'.$strength.'%\'']);
                };
                $qptb->group('test3.DrugRecNo, test3.Seq1, test3.Seq2, test3.Seq3, test3.Seq4, test3.medicinal_product_id');
                $qptb->order(['test3.trade_name' => 'ASC']);
                $qptb->order(['test3.mah'=> 'ASC']);
                // print_r($qptb);
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
