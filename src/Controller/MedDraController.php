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
            $formats = ['llt_name','pt_name','hlt_name','hlgt_name','soc_name'];
            $countTyped = 0;
            $conn = ConnectionManager::get('default');
            $qptb = $conn->newQuery();

            try{
                if($searchKey['type']==1||$searchKey['type']==3){
                    foreach($formats as $testk => $testv){
                        if(array_key_exists($testv, $searchKey)){
                            $countTyped = $countTyped+1;
                        }
                    }
                    if($countTyped == 0) return;
                    else if($countTyped ==1){                            
                        foreach($formats as $exisitKey => $exisitValue){
                                if(array_key_exists($exisitValue, $searchKey)){
                                    $qptb = $conn->newQuery();
                                    $qptb->select(['meddra.'.$exisitValue,'meddra.'.explode('_',$exisitValue)[0].'_code'])->from("meddra");
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
                                            ]
                                        ]);
                                    if($searchKey['full_text']) 
                                        $qptb->where(['meddra.'.$exisitValue.' LIKE \'%'.$searchKey[$exisitValue].'%\'']);
                                    else $qptb->where(['meddra.'.$exisitValue.' LIKE \''.$searchKey[$exisitValue].'%\'']);
                                    $exactField = $exisitValue;
                                    $drugList[$exactField]['codes'] = $conn->execute($qptb->distinct())->fetchAll();
                                    break;
                                }
                            }           
                    }else if($countTyped >1){
                        foreach($formats as $formatK => $formatV){
                            $qptb = $conn->newQuery();
                            $qptb->select(['meddra.'.$formatV, 'meddra.'.explode('_',$formatV)[0].'_code'])->from("meddra");
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
                                    ]
                                ]);
                            foreach($formats as $exisitKey => $exisitValue){
                                if(array_key_exists($exisitValue, $searchKey)){
                                    if($searchKey['full_text']) 
                                        $qptb->where(['meddra.'.$exisitValue.' LIKE \'%'.$searchKey[$exisitValue].'%\'']);
                                    else $qptb->where(['meddra.'.$exisitValue.' LIKE \''.$searchKey[$exisitValue].'%\'']);
                                }
                            }
                            $drugList[$formatV]['codes'] = $conn->execute($qptb->distinct())->fetchAll();
                            // print_r($qptb);
                            // print_r($formatV);
                            // return;
                            $qptb = $conn->newQuery();
                            $qptb->select(['meddra.'.explode('_',$formatV)[0].'_code', 'primary_soc_name'=>'soc.soc_name',
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
                                    ]
                                ]);
                            foreach($formats as $exisitKey => $exisitValue){
                                if(array_key_exists($exisitValue, $searchKey)){
                                    if($searchKey['full_text']) 
                                        $qptb->where(['meddra.'.$exisitValue.' LIKE \'%'.$searchKey[$exisitValue].'%\'']);
                                    else $qptb->where(['meddra.'.$exisitValue.' LIKE \''.$searchKey[$exisitValue].'%\'']);
                                }
                            }
                            $drugList[$formatV]['primary'] = $conn->execute($qptb->distinct())->fetchAll();
                        }
                    }
                }else if($searchKey['type']==2){
                    foreach($formats as $formatK => $formatV){
                        $qptb = $conn->newQuery();
                        $qptb->select(['meddra.'.$formatV, 'meddra.'.explode('_',$formatV)[0].'_code'])->from("meddra");
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
                                ]
                            ]);
                        foreach($formats as $exisitKey => $exisitValue){
                            if(array_key_exists($exisitValue, $searchKey)) $qptb->where(['meddra.'.$exisitValue.' LIKE \''.$searchKey[$exisitValue].'\'']);
                        }
                        $drugList[$formatV]['codes'] = $conn->execute($qptb->distinct())->fetchAll();
                        // print_r($qptb);
                        // print_r($formatV);
                        // return;
                        $qptb = $conn->newQuery();
                        $qptb->select(['meddra.'.explode('_',$formatV)[0].'_code', 'primary_soc_name'=>'soc.soc_name',
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
                                ]
                            ]);
                        foreach($formats as $exisitKey => $exisitValue){
                            if(array_key_exists($exisitValue, $searchKey))$qptb->where(['meddra.'.$exisitValue.' LIKE \''.$searchKey[$exisitValue].'\'']);
                        }
                        $drugList[$formatV]['primary'] = $conn->execute($qptb->distinct())->fetchAll();
                    }
                }else{
                        $qptb = $conn->newQuery();
                            $qptb->select(['meddra.llt_name','meddra.llt_code','meddra.pt_name',
                                        'meddra.pt_code','meddra.hlt_name','meddra.hlt_code','meddra.hlgt_name','meddra.hlgt_name',
                                        'meddra.soc_name','meddra.soc_code'])->from("meddra");
                            $qptb->join([
                                'pt' =>[
                                    'table' =>'mdr_pref_term',
                                    'type' =>'INNER',
                                    'conditions'=>['meddra.pt_code = pt.pt_code'],
                                ],
                                'soc' =>[
                                    'table' =>'mdr_soc_term',
                                    'type'=>'INNER',
                                    'conditions'=>['pt.pt_soc_code = soc.soc_code'],
                                    ]
                                ]);
                            foreach($formats as $exisitKey => $exisitValue){
                                if(array_key_exists($exisitValue, $searchKey))$qptb->where(['meddra.llt_name LIKE \''.$searchKey['llt_name'].'\'']);
                            }
                            $drugList['primary'] = $conn->execute($qptb->distinct())->fetchAll();
                    }
                    $drugList['type']=$searchKey['type'];
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
