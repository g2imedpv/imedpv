<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\Router;

/**
 * SdDocuments Controller
 *
 * @property \App\Model\Table\SdDocumentsTable $SdDocuments
 *
 * @method \App\Model\Entity\SdDocument[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SdDocumentsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['SdCases']
        ];
        $sdDocuments = $this->paginate($this->SdDocuments);

        $this->set(compact('sdDocuments'));
    }

    /**
     * View method
     *
     * @param string|null $id Sd Document id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $sdDocument = $this->SdDocuments->get($id, [
            'contain' => ['SdCases']
        ]);

        $this->set('sdDocument', $sdDocument);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $sdDocument = $this->SdDocuments->newEntity();
        if ($this->request->is('post')) {
            $sdDocument = $this->SdDocuments->patchEntity($sdDocument, $this->request->getData());
            if ($this->SdDocuments->save($sdDocument)) {
                $this->Flash->success(__('The sd document has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sd document could not be saved. Please, try again.'));
        }
        $sdCases = $this->SdDocuments->SdCases->find('list', ['limit' => 200]);
        $this->set(compact('sdDocument', 'sdCases'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Sd Document id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sdDocument = $this->SdDocuments->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $sdDocument = $this->SdDocuments->patchEntity($sdDocument, $this->request->getData());
            if ($this->SdDocuments->save($sdDocument)) {
                $this->Flash->success(__('The sd document has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sd document could not be saved. Please, try again.'));
        }
        $sdCases = $this->SdDocuments->SdCases->find('list', ['limit' => 200]);
        $this->set(compact('sdDocument', 'sdCases'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Sd Document id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sdDocument = $this->SdDocuments->get($id);
        if ($this->SdDocuments->delete($sdDocument)) {
            $this->Flash->success(__('The sd document has been deleted.'));
        } else {
            $this->Flash->error(__('The sd document could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function addDocuments($case_id)
    {
            $this->viewBuilder()->setLayout('main_layout');
            $docList = $this->SdDocuments->find()->where(['sd_case_id'=>$case_id]);
            $this->loadModel("SdUsers");
            $sdDocList = $docList->toArray();
            $this->set(compact('sdDocList', 'case_id'));
    }

    public function save($case_id)
    {
        if ($this->request->is(['patch', 'post', 'put'])) {
            $this->autoRender = false;
            $requested_data = $this->request->getData();
            $document_data = array();
            foreach ($requested_data as $key => $value)
            {
                preg_match("/^(doc_\S+)_(\d+)$/", $key, $matches);
                $field = $matches[1];
                $index = $matches[2];
                if (!in_array($value, $document_data[$index][$field]))
                {
                    $document_data[$index][$field] = $value;
                }
            }

            $userinfo = $this->request->getSession()->read('Auth.User');
            $file_saved = false;
            $new_row = array();
            foreach ($document_data as $document_details)
            {
                //debug($document_details);
                if (isset($document_details['doc_description']) && $document_details['doc_description'] != '')
                {
                    $file_uploaded = false;
                    if ($document_details['doc_source'] == 'File Attachment')
                    {
                        if(!empty($document_details['doc_attachment']['name'])){
                            $fileName = $document_details['doc_attachment']['name'];
                            $fileType = $document_details['doc_attachment']['type'];
                            $fileSize = $document_details['doc_attachment']['size'];
                            $rootPath = 'resources/';
                            $uploadPath = $rootPath.'uploads/files/';
                            //save files into webroot
                            $uploadRealPath = $uploadPath.$case_id;
                            //print $uploadRealPath; die();
                            if (!file_exists($uploadRealPath))
                            {
                                if (!mkdir($uploadRealPath, 0755, true))
                                {

                                    $this->Flash->error(__('Unable to create directory, please try again.'));
                                    return false;
                                }
                            }

                            $uploadFile = $uploadRealPath."/".$fileName;

                            if (file_exists($uploadFile))
                            {
                                $uploadFile = $uploadRealPath."/".time().$fileName;
                            }
                            if(move_uploaded_file($document_details['doc_attachment']['tmp_name'], $uploadFile))
                            {
                                $urlBase = Router::url('/', true);
                                $url = $urlBase.str_replace("webroot/","",$uploadFile);;
                                $file_uploaded = true;
                            }
                        }
                    }
                    elseif ($document_details['doc_source'] == 'URL Reference')
                    {

                        $file_uploaded = true;
                    }

                    if ($file_uploaded)
                    {
                        $newDocumentEntity = $this->SdDocuments->newEntity();
                        $newDocumentEntity->sd_case_id =  $case_id;
                        $newDocumentEntity->doc_classification = $document_details['doc_classification'];
                        $newDocumentEntity->doc_description = $document_details['doc_description'];
                        $newDocumentEntity->doc_source = $document_details['doc_source'];
                        if ($document_details['doc_source'] == 'URL Reference')
                        {
                            $newDocumentEntity->doc_path = $document_details['doc_path'];
                        }
                        elseif ($document_details['doc_source'] == 'File Attachment')
                        {
                            $newDocumentEntity->doc_name = $fileName;
                            $newDocumentEntity->doc_path = $url;
                            $newDocumentEntity->doc_type = $fileType;
                            $newDocumentEntity->doc_size = $fileSize;
                        }
                        $newDocumentEntity->is_deleted = 0;
                        $newDocumentEntity->created_dt = date("Y-m-d H:i:s");
                        $newDocumentEntity->updated_dt = date("Y-m-d H:i:s");
                        $newDocumentEntity->created_by = $userinfo['id'];

                        if ($this->SdDocuments->save($newDocumentEntity))
                        {
                            $file_saved = true;
                            $new_row = $document_details;
                        }
                        else
                        {
                            break;
                        }
                    }
                }

            }
            if ($file_saved)
            {
                $this->Flash->success(__('The sd document has been saved.'));

                return $this->redirect(['action' => 'add_documents', $case_id]);
            }
            $this->Flash->error(__('The document could not be saved. Please, try again.'));
            
                //echo json_encode(array("result"=>1, "new_row"=>$new_row));
        
                //echo json_encode(array("result"=>0));
        }

    }
}
