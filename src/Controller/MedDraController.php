<?php
namespace App\Controller;
use Cake\ORM\TableRegistry;
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
     * search SMQ options
     * 
     * 
     * 
     */
    public function searchSMQ($keyWord){
        $language = $this->request->getSession()->read('Language');
        if($language == "en_US"){
            $language = "";
        }else{
            $language = "_".$language;
        }
        $smq_listTable = TableRegistry::get('mdr_smq_list'.$language);
        $smq_list_d = $smq_listTable->find()->distinct()->select(['smq_code','smq_name'])
        ->join([
            'smq_content'=>[
                'table'=>'mdr_smq_content',
                'type'=>'INNER',
                'conditions'=>['smq_content.smq_code = mdr_smq_list.smq_code',('smq_content.term_scope != \'0\'')]
            ]
        ])
        ->where(['mdr_smq_list'.$language.'.smq_name LIKE \'%'.$keyWord.'%\'']);
        echo json_encode($smq_list_d);
        die();
    }



    /**
     * search Meddra
     * 
     * 
     * 
     */
    public function search(){
        $language = $this->request->getSession()->read('Language');
        if($language == "en_US"){
            $language = "";
        }else{
            $language = "_".$language;
        }
        
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
                        //search by single key
                        foreach($formats as $exisitKey => $exisitValue){
                                if(array_key_exists($exisitValue, $searchKey)){
                                    $qptb = $conn->newQuery();
                                    $qptb->select(['meddra'.$language.'.'.$exisitValue,'meddra'.$language.'.'.explode('_',$exisitValue)[0].'_code'])->from("meddra".$language);
                                    if($searchKey['full_text']) 
                                        $qptb->where(['meddra'.$language.'.'.$exisitValue.' LIKE \'%'.$searchKey[$exisitValue].'%\'']);
                                    else $qptb->where(['meddra'.$language.'.'.$exisitValue.' LIKE \''.$searchKey[$exisitValue].'%\'']);
                                    $exactField = $exisitValue;
                                    $drugList[$exactField]['codes'] = $conn->execute($qptb->distinct())->fetchAll();
                                    break;
                                }
                            }           
                    }else if($countTyped >1){
                        foreach($formats as $formatK => $formatV){
                            $qptb = $conn->newQuery();
                            $qptb->select(['meddra'.$language.'.'.$formatV, 'meddra'.$language.'.'.explode('_',$formatV)[0].'_code'])->from("meddra".$language);
                            foreach($formats as $exisitKey => $exisitValue){
                                if(array_key_exists($exisitValue, $searchKey)){
                                    if($searchKey['full_text']) 
                                        $qptb->where(['meddra'.$language.'.'.$exisitValue.' LIKE \'%'.$searchKey[$exisitValue].'%\'']);
                                    else $qptb->where(['meddra'.$language.'.'.$exisitValue.' LIKE \''.$searchKey[$exisitValue].'%\'']);
                                }
                            }
                            $drugList[$formatV]['codes'] = $conn->execute($qptb->distinct())->fetchAll();
                            // print_r($qptb);
                            // print_r($formatV);
                            // return;
                            $qptb = $conn->newQuery();
                            $qptb->select(['meddra'.$language.'.'.explode('_',$formatV)[0].'_code', 'primary_soc_name'=>'soc.soc_name',
                                        'primary_soc_code'=>'soc.soc_code'])->from("meddra".$language);
                            $qptb->join([
                                'pt' =>[
                                    'table' =>'mdr_pref_term'.$language,
                                    'type' =>'LEFT',
                                    'conditions'=>['meddra'.$language.'.pt_code = pt.pt_code'],
                                ],
                                'soc' =>[
                                    'table' =>'mdr_soc_term'.$language,
                                    'type'=>'LEFT',
                                    'conditions'=>['pt.pt_soc_code = soc.soc_code'],
                                    ]
                                ]);
                            foreach($formats as $exisitKey => $exisitValue){
                                if(array_key_exists($exisitValue, $searchKey)){
                                    if($searchKey['full_text']) 
                                        $qptb->where(['meddra'.$language.'.'.$exisitValue.' LIKE \'%'.$searchKey[$exisitValue].'%\'']);
                                    else $qptb->where(['meddra'.$language.'.'.$exisitValue.' LIKE \''.$searchKey[$exisitValue].'%\'']);
                                }
                            }
                            $drugList[$formatV]['primary'] = $conn->execute($qptb->distinct())->fetchAll();
                        }
                    }
                }else if($searchKey['type']==2){
                    foreach($formats as $formatK => $formatV){
                        $qptb = $conn->newQuery();
                        $qptb->select(['meddra'.$language.'.'.$formatV, 'meddra'.$language.'.'.explode('_',$formatV)[0].'_code'])->from("meddra".$language);
                        foreach($formats as $exisitKey => $exisitValue){
                            if(array_key_exists($exisitValue, $searchKey)) $qptb->where(['meddra'.$language.'.'.$exisitValue.' LIKE \''.$searchKey[$exisitValue].'\'']);
                        }
                        $drugList[$formatV]['codes'] = $conn->execute($qptb->distinct())->fetchAll();
                        // print_r($qptb);
                        // print_r($formatV);
                        // return;
                        $qptb = $conn->newQuery();
                        $qptb->select(['meddra'.$language.'.'.explode('_',$formatV)[0].'_code', 'primary_soc_name'=>'soc.soc_name',
                                    'primary_soc_code'=>'soc.soc_code'])->from("meddra".$language);
                        $qptb->join([
                            'pt' =>[
                                'table' =>'mdr_pref_term'.$language,
                                'type' =>'LEFT',
                                'conditions'=>['meddra'.$language.'.pt_code = pt.pt_code'],
                            ],
                            'soc' =>[
                                'table' =>'mdr_soc_term'.$language,
                                'type'=>'LEFT',
                                'conditions'=>['pt.pt_soc_code = soc.soc_code'],
                                ]
                            ]);
                        foreach($formats as $exisitKey => $exisitValue){
                            if(array_key_exists($exisitValue, $searchKey))$qptb->where(['meddra'.$language.'.'.$exisitValue.' LIKE \''.$searchKey[$exisitValue].'\'']);
                        }
                        $drugList[$formatV]['primary'] = $conn->execute($qptb->distinct())->fetchAll();
                    }
                }else{
                        $qptb = $conn->newQuery();
                            $qptb->select(['meddra'.$language.'.llt_name','meddra'.$language.'.llt_code','meddra'.$language.'.pt_name',
                                        'meddra'.$language.'.pt_code','meddra'.$language.'.hlt_name','meddra'.$language.'.hlt_code','meddra'.$language.'.hlgt_name','meddra'.$language.'.hlgt_code',
                                        'meddra'.$language.'.soc_name','meddra'.$language.'.soc_code'])->from("meddra".$language);
                            foreach($formats as $exisitKey => $exisitValue){
                                if(array_key_exists($exisitValue, $searchKey))$qptb->where(['meddra'.$language.'.llt_name LIKE \''.$searchKey['llt_name'].'\'']);
                            }
                            $drugList['primary'] = $conn->execute($qptb->distinct())->fetchAll()[0];
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
