<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * SdXmlStructures Controller
 *
 * @property \App\Model\Table\SdXmlStructuresTable $SdXmlStructures
 *
 * @method \App\Model\Entity\SdXmlStructure[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SdXmlStructuresController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['LastTags', 'SdFields']
        ];
        $sdXmlStructures = $this->paginate($this->SdXmlStructures);

        $this->set(compact('sdXmlStructures'));
    }

    /**
     * View method
     *
     * @param string|null $id Sd Xml Structure id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $sdXmlStructure = $this->SdXmlStructures->get($id, [
            'contain' => ['LastTags', 'SdFields']
        ]);

        $this->set('sdXmlStructure', $sdXmlStructure);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $sdXmlStructure = $this->SdXmlStructures->newEntity();
        if ($this->request->is('post')) {
            $sdXmlStructure = $this->SdXmlStructures->patchEntity($sdXmlStructure, $this->request->getData());
            if ($this->SdXmlStructures->save($sdXmlStructure)) {
                $this->Flash->success(__('The sd xml structure has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sd xml structure could not be saved. Please, try again.'));
        }
        $lastTags = $this->SdXmlStructures->LastTags->find('list', ['limit' => 200]);
        $sdFields = $this->SdXmlStructures->SdFields->find('list', ['limit' => 200]);
        $this->set(compact('sdXmlStructure', 'lastTags', 'sdFields'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Sd Xml Structure id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sdXmlStructure = $this->SdXmlStructures->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $sdXmlStructure = $this->SdXmlStructures->patchEntity($sdXmlStructure, $this->request->getData());
            if ($this->SdXmlStructures->save($sdXmlStructure)) {
                $this->Flash->success(__('The sd xml structure has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sd xml structure could not be saved. Please, try again.'));
        }
        $lastTags = $this->SdXmlStructures->LastTags->find('list', ['limit' => 200]);
        $sdFields = $this->SdXmlStructures->SdFields->find('list', ['limit' => 200]);
        $this->set(compact('sdXmlStructure', 'lastTags', 'sdFields'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Sd Xml Structure id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sdXmlStructure = $this->SdXmlStructures->get($id);
        if ($this->SdXmlStructures->delete($sdXmlStructure)) {
            $this->Flash->success(__('The sd xml structure has been deleted.'));
        } else {
            $this->Flash->error(__('The sd xml structure could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    
    /**
    *  Generate XML files
    *
    */
    //create ergodic function
    public function getErgodicTag($caseId,$level,$lastId){
        $sdXmlStructures = TableRegistry::get('sdXmlStructures');
        $ICSR = $sdXmlStructures ->find()
        ->select(['sdXmlStructures.tag','fv.field_value','sdXmlStructures.level','sdXmlStructures.last_tag_id','sdXmlStructures.sd_field_id','sdXmlStructures.multiple','fv.set_number'])
        ->join([
            'fv' =>[
                'table' =>'sd_field_values',
                'type'=>'INNER',
                'where'=>'fv.field_value is not null',
                'conditions'=>['sdXmlStructures.sd_field_id= fv.sd_field_id','fv.sd_case_id='.$caseId,'fv.status=1','sdXmlStructures.level='.$level,
                                'sdXmlStructures.last_tag_id='.$lastId]
                
            ]
        ]);
        return $ICSR; 
    }

    public function getRepeatTag($caseId,$level,$lastId,$set_number){
        $sdXmlStructures = TableRegistry::get('sdXmlStructures');
        $Repeat = $sdXmlStructures ->find()
        ->select(['sdXmlStructures.tag','fv.field_value','sdXmlStructures.level','sdXmlStructures.last_tag_id','sdXmlStructures.sd_field_id','sdXmlStructures.multiple','fv.set_number'])
        ->join([
            'fv' =>[
                'table' =>'sd_field_values',
                'type'=>'INNER',
                'conditions'=>['sdXmlStructures.sd_field_id= fv.sd_field_id','fv.sd_case_id='.$caseId,'fv.status=1','sdXmlStructures.level='.$level,
                                'sdXmlStructures.last_tag_id='.$lastId,'fv.set_number='.$set_number]
            ]
        ]);
        return $Repeat; 
    }

    public function getMaxSetNumber($caseId,$level,$lastId){
        $sdXmlStructures = TableRegistry::get('sdXmlStructures');
        $ICSR = $sdXmlStructures ->find()
        ->select(['fv.set_number'])
        ->join([
            'fv' =>[
                'table' =>'sd_field_values',
                'type'=>'INNER',
                'conditions'=>['sdXmlStructures.sd_field_id= fv.sd_field_id','fv.sd_case_id='.$caseId,'fv.status=1','sdXmlStructures.level='.$level,
                                'sdXmlStructures.last_tag_id='.$lastId]
            ]
        ])->toArray();
        $maxSet=max($ICSR);
        return $maxSet['fv']['set_number']; 
    }


    public function getSingleTag($caseId,$descriptor){
        $sdFields = TableRegistry::get('sdFields');
        $ICSR = $sdFields ->find()
        ->select(['fv.field_value'])
        ->join([
            'fv' =>[
                'table' =>'sd_field_values',
                'type'=>'INNER',
                'conditions'=>['sdFields.id = fv.sd_field_id','fv.sd_case_id='.$caseId, 'sdFields.descriptor = \''.$descriptor.'\'']         
            ]
        ])->first();
        $value=$ICSR['fv']['field_value'];
        $value= str_replace('>', '&gt', $value);
        $value= str_replace('<', '&lt', $value);
        $value= str_replace('&', '&amp', $value);//https://www.php.net/manual/en/book.xmlwriter.php "The single quote predefined entity is not escaped.The other predefined entities are but not the single quote."
        return $value; 
    }

    
    public function genXMLTwo($caseId){   
        $this->autoRender = false;
        //set file name with caseNo and create time
        $sdCases = TableRegistry::get('sdCases');
        $name=$sdCases->find()
                ->select(['caseNo'])
                ->where(['id='.$caseId,'status=1'])->first();
        $fileName=$name['caseNo'];
        $time=date("YmdHis");
        $xml = $fileName.$time;
        header("Content-Type: text/html/force-download");
        header("Content-Disposition: attachment; filename=".$xml.".xml");
        $xml = new \XMLWriter();
        $xml->openUri("php://output");
        $xml->setIndentString("\t");
        $xml->setIndent(true);
        $lang=$this->getSingleTag($caseId,'primarysourcecountry');
        //Determine the language
        if($lang=='CN'){
            $xml->startDocument('1.0', 'UTF-8');//FDA supports only the ISO-8859-1 character set for encoding the submission.
            $xml->writeDtd('ichicsr','','http://www.cde.org.cn/DTD/icsr21xml.dtd');
            //TEST:$xml->writeDtd('ichicsr','','http://www.cde.org.cn/DTD/ichicsrack11xml.dtd');
            //A first element ichicsr
                $xml->startElement("ichicsr");
                    // Attribute lang="en"
                    $xml->writeAttribute("lang","zh");
                        $xml->writeComment(" M.1 ICH ICSR Message Header Information");
                        $xml->startElement("ichicsrmessageheader");
                            $xml->writeComment(" M.1.1 Message Type");
                            $xml->writeElement('messagetype','ichicsrack');
                            $xml->writeComment(" M.1.2 Message Format Version");
                            $xml->writeElement('messageformatversion','1.1');
                            $xml->writeComment(" M.1.3 Message Format Release");
                            $xml->writeElement('messageformatrelease','1.0');
                            $xml->writeComment(" M.1.4 Message Number");
                            $xml->writeElement('messagenumb',$fileName);
                            $xml->writeComment(" M.1.5 Message Sender Identifier");
                            $xml->writeElement('messagesenderidentifier','G2-BioPharma-Services INC.');
                            $xml->writeComment(" M.1.6 Message Receiver Identifier");
                            $xml->writeElement('messagereceiveridentifier','CDEE2B');// test value="CDETEST"
                            $xml->writeComment("M.1.7.a Message Date Format");
                            $xml->writeElement('messagedateformat','204');
                            $xml->writeComment("M.1.7.b Message Date");
                            $xml->writeElement('messagedate',date("YmdHis"));
                        $xml->endElement();//ichicsrmessageheader
        }else {
            // create xml Document start
            $xml->startDocument('1.0', 'ISO-8859-1');//FDA supports only the ISO-8859-1 character set for encoding the submission.
            $xml->writeDtd('ichicsr','','https://www.accessdata.fda.gov/xml/icsr-xmlv2.1.dtd');
            //A first element ichicsr
                $xml->startElement("ichicsr");
                    // Attribute lang="en"
                    $xml->writeAttribute("lang","en");
                        $xml->startElement("ichicsrmessageheader");
                            $xml->writeComment("M.1.1 Message Type");
                            $xml->writeElement('messagetype','ICSR');
                            $xml->writeComment("M.1.2 Message Format Version");
                            $xml->writeElement('messageformatversion','2');
                            $xml->writeComment("M.1.3 Message Format Release");
                            $xml->writeElement('messageformatrelease','1');
                            $xml->writeComment("M.1.4 Message Number");
                            $xml->writeElement('messagenumb',$fileName);
                            $xml->writeComment("M.1.5 Message Sender Identifier");
                            $xml->writeElement('messagesenderidentifier','G2-BioPharma-Services INC.');
                            $xml->writeComment("M.1.6 Message Receiver Identifier");
                            $xml->writeElement('messagereceiveridentifier','ZZFDA');
                            $xml->writeComment("M.1.7.a Message Date Format");
                            $xml->writeElement('messagedateformat','204');
                            $xml->writeComment("M.1.7.b Message Date");
                            $xml->writeElement('messagedate',$this->getSingleTag($caseId,'messagedate'));
                        $xml->endElement();//ichicsrmessageheader
            // create xml Document start
        }
                        $xml->writeComment("A.1 SAFETY REPORT");
                        $xml->startElement("safetyreport");
                            //SafetyReport
                            $xml->writeElement('safetyreportversion',$this->getSingleTag($caseId,'safetyreportversion'));
                            $xml->writeElement('safetyreportid',$this->getSingleTag($caseId,'safetyreportid'));
                            $xml->writeElement('primarysourcecountry',$this->getSingleTag($caseId,'primarysourcecountry'));
                            $xml->writeElement('occurcountry',$this->getSingleTag($caseId,'occurcountry'));
                            $xml->writeElement('transmissiondateformat','102');
                            $xml->writeElement('transmissiondate',$this->getSingleTag($caseId,'transmissiondate'));
                            $xml->writeElement('reporttype',$this->getSingleTag($caseId,'reporttype'));
                            $xml->writeElement('serious',$this->getSingleTag($caseId,'serious'));
                            $serious=$this->getSingleTag($caseId,'serious');
                            if ($serious=='2'){
                            $xml->writeElement('seriousnessdeath','2');
                            $xml->writeElement('seriousnesslifethreatening','2');
                            $xml->writeElement('seriousnesshospitalization','2');
                            $xml->writeElement('seriousnessdisabling','2');
                            $xml->writeElement('seriousnesscongenitalanomali','2');
                            $xml->writeElement('seriousnessother','2');
                            }else{
                            $xml->writeElement('seriousnessdeath',$this->getSingleTag($caseId,'seriousnessdeath'));
                            $xml->writeElement('seriousnesslifethreatening',$this->getSingleTag($caseId,'seriousnesslifethreatening'));
                            $xml->writeElement('seriousnesshospitalization',$this->getSingleTag($caseId,'seriousnesshospitalization'));
                            $xml->writeElement('seriousnessdisabling',$this->getSingleTag($caseId,'seriousnessdisabling'));
                            $xml->writeElement('seriousnesscongenitalanomali',$this->getSingleTag($caseId,'seriousnesscongenitalanomali'));
                            $xml->writeElement('seriousnessother',$this->getSingleTag($caseId,'seriousnessother'));
                            }
                            $xml->writeElement('receivedateformat','102');
                            $xml->writeElement('receivedate',$this->getSingleTag($caseId,'receivedate'));
                            $xml->writeElement('receiptdateformat','102');
                            $xml->writeElement('receiptdate',$this->getSingleTag($caseId,'receiptdate'));
                            $xml->writeElement('additionaldocument',$this->getSingleTag($caseId,'additionaldocument'));
                            $xml->writeElement('documentlist',$this->getSingleTag($caseId,'documentlist'));
                            $xml->writeElement('fulfillexpeditecriteria',$this->getSingleTag($caseId,'fulfillexpeditecriteria'));
                            $xml->writeElement('companynumb',$this->getSingleTag($caseId,'companynumb'));
                            $xml->writeElement('duplicate',$this->getSingleTag($caseId,'duplicate'));
                            $xml->writeElement('casenullification',$this->getSingleTag($caseId,'casenullification'));
                            $xml->writeElement('nullificationreason',$this->getSingleTag($caseId,'nullificationreason'));
                            $xml->writeElement('medicallyconfirm',$this->getSingleTag($caseId,'medicallyconfirm'));
                            //reportduplicate
                            $xml->startElement("reportduplicate");
                                $xml->writeElement('duplicatesource',$this->getSingleTag($caseId,'duplicatesource'));
                                $xml->writeElement('duplicatenumb',$this->getSingleTag($caseId,'duplicatenumb'));
                            $xml->endElement();
                            //linkedreport
                            $xml->startElement("linkedreport");
                                $xml->writeElement('linkreportnumb',$this->getSingleTag($caseId,'linkreportnumb'));
                            $xml->endElement();
                            //primarysource
                            $xml->writeComment("A.2 Primary source(s) of information");
                            $xml->startElement("primarysource");
                                $xml->writeElement('reportertitle',$this->getSingleTag($caseId,'reportertitle'));
                                $xml->writeElement('reportergivename',$this->getSingleTag($caseId,'reportergivename'));
                                $xml->writeElement('reportermiddlename',$this->getSingleTag($caseId,'reportermiddlename'));
                                $xml->writeElement('reporterfamilyname',$this->getSingleTag($caseId,'reporterfamilyname'));
                                $xml->writeElement('reporterorganization',$this->getSingleTag($caseId,'reporterorganization'));
                                $xml->writeElement('reporterdepartment',$this->getSingleTag($caseId,'reporterdepartment'));
                                $xml->writeElement('reporterstreet',$this->getSingleTag($caseId,'reporterstreet'));
                                $xml->writeElement('reportercity',$this->getSingleTag($caseId,'reportercity'));
                                $xml->writeElement('reporterstate',$this->getSingleTag($caseId,'reporterstate'));
                                $xml->writeElement('reporterpostcode',$this->getSingleTag($caseId,'reporterpostcode'));
                                $xml->writeElement('reportercountry',$this->getSingleTag($caseId,'reportercountry'));
                                $xml->writeElement('qualification',$this->getSingleTag($caseId,'qualification'));
                                $xml->writeElement('literaturereference',$this->getSingleTag($caseId,'literaturereference'));
                                $xml->writeElement('studyname',$this->getSingleTag($caseId,'studyname'));
                                $xml->writeElement('sponsorstudynumb',$this->getSingleTag($caseId,'sponsorstudynumb'));
                                $xml->writeElement('observestudytype',$this->getSingleTag($caseId,'observestudytype'));
                            $xml->endElement();
                            //sender
                            $xml->writeComment("A.3.1 Sender");
                            $xml->startElement("sender");
                                $xml->writeElement('sendertype',$this->getSingleTag($caseId,'sendertype'));
                                $xml->writeElement('senderorganization',$this->getSingleTag($caseId,'senderorganization'));
                                $xml->writeElement('senderdepartment',$this->getSingleTag($caseId,'senderdepartment'));
                                $xml->writeElement('sendertitle',$this->getSingleTag($caseId,'sendertitle'));
                                $xml->writeElement('sendergivename',$this->getSingleTag($caseId,'sendergivename'));
                                $xml->writeElement('sendermiddlename',$this->getSingleTag($caseId,'sendermiddlename'));
                                $xml->writeElement('senderfamilyname',$this->getSingleTag($caseId,'senderfamilyname'));
                                $xml->writeElement('senderstreetaddress',$this->getSingleTag($caseId,'senderstreetaddress'));
                                $xml->writeElement('sendercity',$this->getSingleTag($caseId,'sendercity'));
                                $xml->writeElement('senderstate',$this->getSingleTag($caseId,'senderstate'));
                                $xml->writeElement('senderpostcode',$this->getSingleTag($caseId,'senderpostcode'));
                                $xml->writeElement('sendercountrycode',$this->getSingleTag($caseId,'sendercountrycode'));
                                $xml->writeElement('sendertel',$this->getSingleTag($caseId,'sendertel'));
                                $xml->writeElement('sendertelextension',$this->getSingleTag($caseId,'sendertelextension'));
                                $xml->writeElement('sendertelcountrycode',$this->getSingleTag($caseId,'sendertelcountrycode'));
                                $xml->writeElement('senderfax',$this->getSingleTag($caseId,'senderfax'));
                                $xml->writeElement('senderfaxextension',$this->getSingleTag($caseId,'senderfaxextension'));
                                $xml->writeElement('senderfaxcountrycode',$this->getSingleTag($caseId,'senderfaxcountrycode'));
                                $xml->writeElement('senderemailaddress',$this->getSingleTag($caseId,'senderemailaddress'));
                            $xml->endElement();
                            //receiver
                            $xml->writeComment("A.3.2 Receiver");
                            $xml->startElement("receiver");
                                $xml->writeElement('receivertype','2');
                                $xml->writeElement('receiverorganization','FDA');
                                $xml->writeElement('receiverdepartment','Office of Surveillance and Epidemiology');
                                $xml->writeElement('receivergivename','FAERS');
                                $xml->writeElement('receiverstreetaddress','10903 New Hampshire Avenue');
                                $xml->writeElement('receivercity','Silver Spring');
                                $xml->writeElement('receiverstate','MD');
                                $xml->writeElement('receiverpostcode','20993');
                                $xml->writeElement('receivercountrycode','US');
                                $xml->writeElement('receiveremailaddress','faersesub@fda.hhs.gov');
                                
                            $xml->endElement();//receiver
                            //patient
                            $xml->writeComment("B.1 Patient characteristics");
                            $xml->startElement("patient");
                                $xml->writeElement('patientinitial',$this->getSingleTag($caseId,'patientinitial'));
                                $xml->writeElement('patientgpmedicalrecordnumb',$this->getSingleTag($caseId,'patientgpmedicalrecordnumb'));
                                $xml->writeElement('patientspecialistrecordnumb',$this->getSingleTag($caseId,'patientspecialistrecordnumb'));
                                $xml->writeElement('patienthospitalrecordnumb',$this->getSingleTag($caseId,'patienthospitalrecordnumb'));
                                $xml->writeElement('patientinvestigationnumb',$this->getSingleTag($caseId,'patientinvestigationnumb'));
                                $xml->writeElement('patientbirthdateformat','102');
                                $xml->writeElement('patientbirthdate',$this->getSingleTag($caseId,'patientbirthdate'));
                                $xml->writeElement('patientonsetage',$this->getSingleTag($caseId,'patientonsetage'));
                                $xml->writeElement('patientonsetageunit',$this->getSingleTag($caseId,'patientonsetageunit'));
                                $xml->writeElement('gestationperiod',$this->getSingleTag($caseId,'gestationperiod'));
                                $xml->writeElement('gestationperiodunit',$this->getSingleTag($caseId,'gestationperiodunit'));
                                $xml->writeElement('patientagegroup',$this->getSingleTag($caseId,'patientagegroup'));
                                $xml->writeElement('patientweight',$this->getSingleTag($caseId,'patientweight'));
                                $xml->writeElement('patientheight',$this->getSingleTag($caseId,'patientheight'));
                                $xml->writeElement('patientsex',$this->getSingleTag($caseId,'patientsex'));
                                $xml->writeElement('lastmenstrualdateformat','102');
                                $xml->writeElement('patientlastmenstrualdate',$this->getSingleTag($caseId,'patientlastmenstrualdate'));
                                $xml->writeElement('patientmedicalhistorytext',$this->getSingleTag($caseId,'patientmedicalhistorytext'));
                                $xml->writeElement('resultstestsprocedures',$this->getSingleTag($caseId,'resultstestsprocedures'));
                               //medicalhistoryepisode
                               $xml->startElement("medicalhistoryepisode");
                                    $xml->writeElement('patientepisodenamemeddraversion',$this->getSingleTag($caseId,'patientepisodenamemeddraversion'));
                                    $xml->writeElement('patientepisodename',$this->getSingleTag($caseId,'patientepisodename'));
                                    $xml->writeElement('patientmedicalstartdateformat',$this->getSingleTag($caseId,'patientmedicalstartdateformat'));
                                    $xml->writeElement('patientmedicalstartdate',$this->getSingleTag($caseId,'patientmedicalstartdate'));
                                    $xml->writeElement('patientmedicalcontinue',$this->getSingleTag($caseId,'patientmedicalcontinue'));
                                    $xml->writeElement('patientmedicalenddateformat',$this->getSingleTag($caseId,'patientmedicalenddateformat'));
                                    $xml->writeElement('patientmedicalenddate',$this->getSingleTag($caseId,'patientmedicalenddate'));
                                    $xml->writeElement('patientmedicalcomment',$this->getSingleTag($caseId,'patientmedicalcomment'));
                                $xml->endElement();
                               
                                //patientpastdrugtherapy
                                $xml->startElement("patientpastdrugtherapy");
                                    $xml->writeElement('patientdrugname',$this->getSingleTag($caseId,'patientdrugname'));
                                    $xml->writeElement('patientdrugstartdateformat',$this->getSingleTag($caseId,'patientdrugstartdateformat'));
                                    $xml->writeElement('patientdrugstartdate',$this->getSingleTag($caseId,'patientdrugstartdate'));
                                    $xml->writeElement('patientdrugenddateformat',$this->getSingleTag($caseId,'patientdrugenddateformat'));
                                    $xml->writeElement('patientdrugenddate',$this->getSingleTag($caseId,'patientdrugenddate'));
                                    $xml->writeElement('patientindicationmeddraversion',$this->getSingleTag($caseId,'patientindicationmeddraversion'));
                                    $xml->writeElement('patientdrugindication',$this->getSingleTag($caseId,'patientdrugindication'));
                                    $xml->writeElement('patientdrgreactionmeddraversion',$this->getSingleTag($caseId,'patientdrgreactionmeddraversion'));
                                    $xml->writeElement('patientdrugreaction',$this->getSingleTag($caseId,'patientdrugreaction'));
                                $xml->endElement();
                                
                                //patientdeath
                                $xml->startElement("patientdeath");
                                    $xml->writeElement('patientdeathdateformat',$this->getSingleTag($caseId,'patientdeathdateformat'));
                                    $xml->writeElement('patientdeathdate',$this->getSingleTag($caseId,'patientdeathdate'));
                                    $xml->writeElement('patientautopsyyesno',$this->getSingleTag($caseId,'patientautopsyyesno'));
                                    $xml->startElement("patientdeathcause");
                                        $xml->writeElement('patientdeathreportmeddraversion',$this->getSingleTag($caseId,'patientdeathreportmeddraversion'));
                                        $xml->writeElement('patientdeathreport',$this->getSingleTag($caseId,'patientdeathreport'));
                                    $xml->endElement();
                                    $xml->startElement("patientautopsy");
                                        $xml->writeElement('patientdetermautopsmeddraversion',$this->getSingleTag($caseId,'patientdetermautopsmeddraversion'));
                                        $xml->writeElement('patientdetermineautopsy',$this->getSingleTag($caseId,'patientdetermineautopsy'));
                                    $xml->endElement();
                                $xml->endElement();
                                
                                //parent
                                $xml->writeComment("B.1.10 For a parent-child / fetus report, information concerning the parent:");
                                $xml->startElement("parent");
                                    $xml->writeElement('parentidentification',$this->getSingleTag($caseId,'parentidentification'));
                                    $xml->writeElement('parentbirthdateformat',$this->getSingleTag($caseId,'parentbirthdateformat'));
                                    $xml->writeElement('parentbirthdate',$this->getSingleTag($caseId,'parentbirthdate'));
                                    $xml->writeElement('parentage',$this->getSingleTag($caseId,'parentage'));
                                    $xml->writeElement('parentageunit',$this->getSingleTag($caseId,'parentageunit'));
                                    $xml->writeElement('parentlastmenstrualdateformat','102');
                                    $xml->writeElement('parentlastmenstrualdate',$this->getSingleTag($caseId,'parentlastmenstrualdate'));
                                    $xml->writeElement('parentweight',$this->getSingleTag($caseId,'parentweight'));
                                    $xml->writeElement('parentheight',$this->getSingleTag($caseId,'parentheight'));
                                    $xml->writeElement('parentsex',$this->getSingleTag($caseId,'parentsex'));
                                    $xml->writeElement('parentmedicalrelevanttext',$this->getSingleTag($caseId,'parentmedicalrelevanttext'));
                                    //parentmedicalhistoryepisode
                                    $xml->writeComment("B.1.10.7 Relevant medical history and concurrent conditions of parent");
                                    $xml->startElement("parentmedicalhistoryepisode");
                                        $xml->writeElement('parentmdepisodemeddraversion',$this->getSingleTag($caseId,'parentmdepisodemeddraversion'));
                                        $xml->writeElement('parentmedicalepisodename',$this->getSingleTag($caseId,'parentmedicalepisodename'));
                                        $xml->writeElement('parentmedicalstartdateformat',$this->getSingleTag($caseId,'parentmedicalstartdateformat'));
                                        $xml->writeElement('parentmedicalstartdate',$this->getSingleTag($caseId,'parentmedicalstartdate'));
                                        $xml->writeElement('parentmedicalcontinue',$this->getSingleTag($caseId,'parentmedicalcontinue'));
                                        $xml->writeElement('parentmedicalenddateformat',$this->getSingleTag($caseId,'parentmedicalenddateformat'));
                                        $xml->writeElement('parentmedicalenddate',$this->getSingleTag($caseId,'parentmedicalenddate'));
                                        $xml->writeElement('parentmedicalcomment',$this->getSingleTag($caseId,'parentmedicalcomment'));
                                    $xml->endElement();
                                    //parentpastdrugtherapy
                                    $xml->writeComment("B.1.10.8 Relevant past drug history");
                                    $xml->startElement("parentpastdrugtherapy");
                                        $xml->writeElement('parentdrugname',$this->getSingleTag($caseId,'parentdrugname'));
                                        $xml->writeElement('parentdrugstartdateformat',$this->getSingleTag($caseId,'parentdrugstartdateformat'));
                                        $xml->writeElement('parentdrugstartdate',$this->getSingleTag($caseId,'parentdrugstartdate'));
                                        $xml->writeElement('parentdrugenddateformat',$this->getSingleTag($caseId,'parentdrugenddateformat'));
                                        $xml->writeElement('parentdrugenddate',$this->getSingleTag($caseId,'parentdrugenddate'));
                                        $xml->writeElement('parentdrgindicationmeddraversion',$this->getSingleTag($caseId,'parentdrgindicationmeddraversion'));
                                        $xml->writeElement('parentdrugindication',$this->getSingleTag($caseId,'parentdrugindication'));
                                        $xml->writeElement('parentdrgreactionmeddraversion',$this->getSingleTag($caseId,'parentdrgreactionmeddraversion'));
                                        $xml->writeElement('parentdrugreaction',$this->getSingleTag($caseId,'parentdrugreaction'));
                                    $xml->endElement();
                                $xml->endElement();  
                                //reaction
                                $xml->writeComment("B.2 Reaction(s) / Event(s)");
                                $xml->startElement("reaction");  
                                    $xml->writeElement('primarysourcereaction',$this->getSingleTag($caseId,'primarysourcereaction'));
                                    $xml->writeElement('reactionmeddraversionllt',$this->getSingleTag($caseId,'reactionmeddraversionllt'));
                                    $xml->writeElement('reactionmeddrallt',$this->getSingleTag($caseId,'reactionmeddrallt'));
                                    $xml->writeElement('reactionmeddraversionpt',$this->getSingleTag($caseId,'reactionmeddraversionpt'));
                                    $xml->writeElement('reactionmeddrapt',$this->getSingleTag($caseId,'reactionmeddrapt'));
                                    $xml->writeElement('termhighlighted',$this->getSingleTag($caseId,'termhighlighted'));
                                    $xml->writeElement('reactionstartdateformat',$this->getSingleTag($caseId,'reactionstartdateformat'));
                                    $xml->writeElement('reactionstartdate',$this->getSingleTag($caseId,'reactionstartdate'));
                                    $xml->writeElement('reactionenddateformat',$this->getSingleTag($caseId,'reactionenddateformat'));
                                    $xml->writeElement('reactionenddate',$this->getSingleTag($caseId,'reactionenddate'));
                                    $xml->writeElement('reactionduration',$this->getSingleTag($caseId,'reactionduration'));
                                    $xml->writeElement('reactiondurationunit',$this->getSingleTag($caseId,'reactiondurationunit'));
                                    $xml->writeElement('reactionfirsttime',$this->getSingleTag($caseId,'reactionfirsttime'));
                                    $xml->writeElement('reactionfirsttimeunit',$this->getSingleTag($caseId,'reactionfirsttimeunit'));
                                    $xml->writeElement('reactionlasttime',$this->getSingleTag($caseId,'reactionlasttime'));
                                    $xml->writeElement('reactionlasttimeunit',$this->getSingleTag($caseId,'reactionlasttimeunit'));
                                    $xml->writeElement('reactionoutcome',$this->getSingleTag($caseId,'reactionoutcome'));
                                $xml->endElement(); 
                                //test
                                $xml->writeComment("B.3 Results of tests and procedures relevant to the investigation of the patient:");
                                $xml->startElement("test");  
                                    $xml->writeElement('testdateformat',$this->getSingleTag($caseId,'testdateformat'));
                                    $xml->writeElement('testdate',$this->getSingleTag($caseId,'testdate'));
                                    $xml->writeElement('testname',$this->getSingleTag($caseId,'testname'));
                                    $xml->writeElement('testresult',$this->getSingleTag($caseId,'testresult'));
                                    $xml->writeElement('testunit',$this->getSingleTag($caseId,'testunit'));
                                    $xml->writeElement('lowtestrange',$this->getSingleTag($caseId,'lowtestrange'));
                                    $xml->writeElement('hightestrange',$this->getSingleTag($caseId,'hightestrange'));
                                    $xml->writeElement('moreinformation',$this->getSingleTag($caseId,'moreinformation'));
                                $xml->endElement(); 
                                //drug
                                $xml->writeComment("B.4.k.2.1- Proprietary medicinal product name");
                                $xml->startElement("drug");  
                                    $xml->writeElement('drugcharacterization',$this->getSingleTag($caseId,'drugcharacterization'));
                                    $xml->writeElement('medicinalproduct',$this->getSingleTag($caseId,'medicinalproduct'));
                                    $xml->writeElement('obtaindrugcountry',$this->getSingleTag($caseId,'obtaindrugcountry'));
                                    $xml->writeElement('drugbatchnumb',$this->getSingleTag($caseId,'drugbatchnumb'));
                                    $xml->writeElement('drugauthorizationnumb',$this->getSingleTag($caseId,'drugauthorizationnumb'));
                                    $xml->writeElement('drugauthorizationcountry',$this->getSingleTag($caseId,'drugauthorizationcountry'));
                                    $xml->writeElement('drugauthorizationholder',$this->getSingleTag($caseId,'drugauthorizationholder'));
                                    $xml->writeElement('drugstructuredosagenumb',$this->getSingleTag($caseId,'drugstructuredosagenumb'));
                                    $xml->writeElement('drugstructuredosageunit',$this->getSingleTag($caseId,'drugstructuredosageunit'));
                                    $xml->writeElement('drugseparatedosagenumb',$this->getSingleTag($caseId,'drugseparatedosagenumb'));
                                    $xml->writeElement('drugintervaldosageunitnumb',$this->getSingleTag($caseId,'drugintervaldosageunitnumb'));
                                    $xml->writeElement('drugintervaldosagedefinition',$this->getSingleTag($caseId,'drugintervaldosagedefinition'));
                                    $xml->writeElement('drugcumulativedosagenumb',$this->getSingleTag($caseId,'drugcumulativedosagenumb'));
                                    $xml->writeElement('drugcumulativedosageunit',$this->getSingleTag($caseId,'drugcumulativedosageunit'));
                                    $xml->writeElement('drugdosagetext',$this->getSingleTag($caseId,'drugdosagetext'));
                                    $xml->writeElement('drugdosageform',$this->getSingleTag($caseId,'drugdosageform'));
                                    $xml->writeElement('drugadministrationroute',$this->getSingleTag($caseId,'drugadministrationroute'));
                                    $xml->writeElement('drugparadministration',$this->getSingleTag($caseId,'drugparadministration'));
                                    $xml->writeElement('reactiongestationperiod',$this->getSingleTag($caseId,'reactiongestationperiod'));
                                    $xml->writeElement('reactiongestationperiodunit',$this->getSingleTag($caseId,'reactiongestationperiodunit'));
                                    $xml->writeElement('drugindicationmeddraversion',$this->getSingleTag($caseId,'drugindicationmeddraversion'));
                                    $xml->writeElement('drugindication',$this->getSingleTag($caseId,'drugindication'));
                                    $xml->writeElement('drugstartdateformat',$this->getSingleTag($caseId,'drugstartdateformat'));
                                    $xml->writeElement('drugstartdate',$this->getSingleTag($caseId,'drugstartdate'));
                                    $xml->writeElement('drugstartperiod',$this->getSingleTag($caseId,'drugstartperiod'));
                                    $xml->writeElement('drugstartperiodunit',$this->getSingleTag($caseId,'drugstartperiodunit'));
                                    $xml->writeElement('druglastperiod',$this->getSingleTag($caseId,'druglastperiod'));
                                    $xml->writeElement('druglastperiodunit',$this->getSingleTag($caseId,'druglastperiodunit'));
                                    $xml->writeElement('drugenddateformat',$this->getSingleTag($caseId,'drugenddateformat'));
                                    $xml->writeElement('drugenddate',$this->getSingleTag($caseId,'drugenddate'));
                                    $xml->writeElement('drugtreatmentduration',$this->getSingleTag($caseId,'drugtreatmentduration'));
                                    $xml->writeElement('drugtreatmentdurationunit',$this->getSingleTag($caseId,'drugtreatmentdurationunit'));
                                    $xml->writeElement('actiondrug',$this->getSingleTag($caseId,'actiondrug'));
                                    $xml->writeElement('drugrecurreadministration',$this->getSingleTag($caseId,'drugrecurreadministration'));
                                    $xml->writeElement('drugadditional',$this->getSingleTag($caseId,'drugadditional'));
                                    //activesubstance
                                    $xml->startElement("activesubstance");  
                                        $xml->writeElement('activesubstancename',$this->getSingleTag($caseId,'activesubstancename'));
                                    $xml->endElement(); 
                                    //drugrecurrence
                                    $xml->startElement("drugrecurrence");  
                                        $xml->writeElement('drugrecuractionmeddraversion',$this->getSingleTag($caseId,'drugrecuractionmeddraversion'));
                                        $xml->writeElement('drugrecuraction',$this->getSingleTag($caseId,'drugrecuraction'));
                                    $xml->endElement(); 
                                    //drugreactionrelatedness
                                    $xml->writeComment("B.4.k.18 Relatedness of drug to reaction / event");
                                    $xml->startElement("drugreactionrelatedness");  
                                        $xml->writeElement('drugreactionassesmeddraversion',$this->getSingleTag($caseId,'drugreactionassesmeddraversion'));
                                        $xml->writeElement('drugreactionasses',$this->getSingleTag($caseId,'drugreactionasses'));
                                        $xml->writeElement('drugassessmentsource',$this->getSingleTag($caseId,'drugassessmentsource'));
                                        $xml->writeElement('drugassessmentmethod',$this->getSingleTag($caseId,'drugassessmentmethod'));
                                        $xml->writeElement('drugassessmentmethod',$this->getSingleTag($caseId,'drugassessmentmethod'));
                                    $xml->endElement(); 
                                $xml->endElement(); 
                                //summary
                                $xml->writeComment("B.5 Narrative case summary and further information:");
                                $xml->startElement("summary");  
                                    $xml->writeElement('narrativeincludeclinical',$this->getSingleTag($caseId,'narrativeincludeclinical'));
                                    $xml->writeElement('reportercomment',$this->getSingleTag($caseId,'reportercomment'));
                                    $xml->writeElement('senderdiagnosismeddraversion',$this->getSingleTag($caseId,'senderdiagnosismeddraversion'));
                                    $xml->writeElement('senderdiagnosis',$this->getSingleTag($caseId,'senderdiagnosis'));
                                    $xml->writeElement('sendercomment',$this->getSingleTag($caseId,'sendercomment'));
                                $xml->endElement(); 
                                /*$safetyreport=$this->getErgodicTag($caseId,3,11);
                                    foreach($safetyreport as $SafetyReport){
                                        $xml->writeElement($SafetyReport['tag'],$SafetyReport['fv']['field_value']);
                                    }$duplicateNumber=$this->getMaxSetNumber($caseId,4,38);
                                        for($i=1;$i<=$duplicateNumber;$i++){
                                        $xml->startElement("reportduplicate");
                                        $reportduplicate=$this->getRepeatTag($caseId,4,38,$i);
                                        foreach($reportduplicate as $ReportDuplicate){   
                                        $xml->writeElement($ReportDuplicate['tag'],$ReportDuplicate['fv']['field_value']); 
                                        }       
                                        $xml->endElement();
                                        $linkedNumber=$this->getMaxSetNumber($caseId,4,41);
                                        for($i=1;$i<=$linkedNumber;$i++){
                                        $xml->startElement("linkedreport");
                                        $linkedreport=$this->getRepeatTag($caseId,4,41,$i);
                                            foreach($linkedreport as $LinkedReport){   
                                                $xml->writeElement($LinkedReport['tag'],$LinkedReport['fv']['field_value']); 
                                            }       
                                        $xml->endElement();
                                    $primarysourceNumber=$this->getMaxSetNumber($caseId,4,43);
                                        for($i=1;$i<=$primarysourceNumber;$i++){
                                            $xml->startElement("primarysource");
                                            $primarysource=$this->getRepeatTag($caseId,4,43,$i);
                                                foreach($primarysource as $PrimarySource){   
                                                    $xml->writeElement($PrimarySource['tag'],$PrimarySource['fv']['field_value']); 
                                                }       
                                            $xml->endElement();
                                        }
                                    $xml->startElement("sender");
                                    $sender=$this->getErgodicTag($caseId,4,60);
                                    foreach($sender as $Sender){
                                        $xml->writeElement($Sender['tag'],$Sender['fv']['field_value']);
                                    }
                                    $xml->endElement();
                                    $receiver=$this->getErgodicTag($caseId,4,80);
                                    foreach($receiver as $Receiver){
                                    $xml->writeElement($Receiver['tag'],$Receiver['fv']['field_value']);
                                    }
                                    $xml->startElement("patient");
                                    $xml->startElement("patient");
                                    $patient=$this->getErgodicTag($caseId,4,100);
                                    foreach($patient as $Patient){
                                        $xml->writeElement($Patient['tag'],$Patient['fv']['field_value']);
                                    }
                                    $medicalhistoryepisodeNumber=$this->getMaxSetNumber($caseId,5,120);
                                    for($i=1;$i<=$medicalhistoryepisodeNumber;$i++){
                                        $xml->startElement("medicalhistoryepisode");
                                        $medicalhistoryepisode=$this->getRepeatTag($caseId,5,120,$i);
                                            foreach($medicalhistoryepisode as $MedicalHistoryEpisode){   
                                                $xml->writeElement($MedicalHistoryEpisode['tag'],$MedicalHistoryEpisode['fv']['field_value']); 
                                            }       
                                        $xml->endElement();
                                    } 
                                    $patientpastdrugtherapyNumber=$this->getMaxSetNumber($caseId,5,129);
                                        for($i=1;$i<=$patientpastdrugtherapyNumber;$i++){
                                            $xml->startElement("patientpastdrugtherapy");
                                            $patientpastdrugtherapy=$this->getRepeatTag($caseId,5,129,$i);
                                                foreach($patientpastdrugtherapy as $PatientPastDrugTherapy){   
                                                    $xml->writeElement($PatientPastDrugTherapy['tag'],$PatientPastDrugTherapy['fv']['field_value']); 
                                                }       
                                            $xml->endElement();
                                        } 
                                    $xml->startElement("patientdeath");
                                        $xml->writeElement('patientdeathdateformat','102');
                                        $xml->writeElement('patientdeathdate',$this->getSingleTag($caseId,'patientdeathdate'));
                                        $xml->writeElement('patientautopsyyesno',$this->getSingleTag($caseId,'patientautopsyyesno')); //patientpastdrugtherapy
                                        //patientdeathcause
                                        $patientdeathcauseNumber=$this->getMaxSetNumber($caseId,6,143);
                                        for($i=1;$i<=$patientdeathcauseNumber;$i++){
                                            $xml->startElement("patientdeathcause");
                                                $patientdeathcause=$this->getRepeatTag($caseId,6,143,$i);
                                                    foreach($patientdeathcause as $PatientDeathCause){   
                                                        $xml->writeElement($PatientDeathCause['tag'],$PatientDeathCause['fv']['field_value']); 
                                                    }       
                                            $xml->endElement();
                                        } //patientdeathcause
                                        //patientautopsy
                                        $patientautopsyNumber=$this->getMaxSetNumber($caseId,6,146);
                                        for($i=1;$i<=$patientautopsyNumber;$i++){
                                            $xml->startElement("patientautopsy");
                                            $patientautopsy=$this->getRepeatTag($caseId,6,146,$i);
                                                foreach($patientautopsy as $PatientAutopsy){   
                                                    $xml->writeElement($PatientAutopsy['tag'],$PatientAutopsy['fv']['field_value']); 
                                                }       
                                            $xml->endElement();
                                        } //patientautopsy
                                    $xml->endElement();
                                    $xml->startElement("parent");
                                    $patient=$this->getErgodicTag($caseId,5,149);
                                    $xml->writeElement('parentlastmenstrualdateformat','102');
                                    foreach($patient as $Patient){
                                        $xml->writeElement($Patient['tag'],$Patient['fv']['field_value']);
                                    }
                                    //parentmedicalhistoryepisode
                                    $parentmedicalhistoryepisodeNumber=$this->getMaxSetNumber($caseId,6,159);
                                    for($i=1;$i<=$parentmedicalhistoryepisodeNumber;$i++){
                                        $xml->startElement("parentmedicalhistoryepisode");
                                        $parentmedicalhistoryepisode=$this->getRepeatTag($caseId,6,159,$i);
                                            foreach($parentmedicalhistoryepisode as $ParentMedicalHistoryEpisode){   
                                                $xml->writeElement($ParentMedicalHistoryEpisode['tag'],$ParentMedicalHistoryEpisode['fv']['field_value']); 
                                            }       
                                        $xml->endElement();
                                    } //parentmedicalhistoryepisode
                                    //parentpastdrugtherapy
                                    $parentpastdrugtherapyNumber=$this->getMaxSetNumber($caseId,6,168);
                                    for($i=1;$i<=$parentpastdrugtherapyNumber;$i++){
                                        $xml->startElement("parentpastdrugtherapy");
                                        $parentpastdrugtherapy=$this->getRepeatTag($caseId,6,168,$i);
                                            foreach($parentpastdrugtherapy as $ParentPastDrugTherapy){   
                                                $xml->writeElement($ParentPastDrugTherapy['tag'],$ParentPastDrugTherapy['fv']['field_value']); 
                                            }       
                                        $xml->endElement();
                                    } //parentpastdrugtherapy 
                                $xml->endElement();
                                //reaction
                                $reactionNumber=$this->getMaxSetNumber($caseId,5,178);
                                for($i=1;$i<=$reactionNumber;$i++){
                                    $xml->startElement("reaction");
                                    $reaction=$this->getRepeatTag($caseId,5,178,$i);
                                        foreach($reaction as $Reaction){   
                                            $xml->writeElement($Reaction['tag'],$Reaction['fv']['field_value']); 
                                        }       
                                    $xml->endElement();
                                } //reaction 
                                //test
                                $testNumber=$this->getMaxSetNumber($caseId,5,196);
                                for($i=1;$i<=$testNumber;$i++){
                                    $xml->startElement("test");
                                    $test=$this->getRepeatTag($caseId,5,196,$i);
                                        foreach($test as $Test){   
                                            $xml->writeElement($Test['tag'],$Test['fv']['field_value']); 
                                        }       
                                    $xml->endElement();
                                } //test
                                //drug
                                $drugNumber=$this->getMaxSetNumber($caseId,5,205);
                                for($i=1;$i<=$drugNumber;$i++){
                                    $xml->startElement("drug");
                                    $drug=$this->getRepeatTag($caseId,5,205,$i);
                                        foreach($drug as $Drug){   
                                            $xml->writeElement($Drug['tag'],$Drug['fv']['field_value']); 
                                        }
                                        //activesubstance    
                                        $xml->startElement("activesubstance");
                                        $activesubstance=$this->getRepeatTag($caseId,6,241,$i);
                                        foreach($activesubstance as $ActiveSubstance){   
                                            $xml->writeElement($ActiveSubstance['tag'],$ActiveSubstance['fv']['field_value']); 
                                        }
                                        $xml->endElement();  //activesubstance
                                        //drugrecurrence
                                        $xml->startElement("drugrecurrence");
                                        $drugrecurrence=$this->getRepeatTag($caseId,6,243,$i);
                                        foreach($drugrecurrence as $DrugRecurrence){   
                                            $xml->writeElement($DrugRecurrence['tag'],$DrugRecurrence['fv']['field_value']); 
                                        }
                                        $xml->endElement();  //drugrecurrence 
                                        //drugreactionrelatedness
                                        $xml->startElement("drugreactionrelatedness");
                                        $drugreactionrelatedness=$this->getRepeatTag($caseId,6,246,$i);
                                        foreach($drugreactionrelatedness as $DrugReactionRelatedness){   
                                            $xml->writeElement($DrugReactionRelatedness['tag'],$DrugReactionRelatedness['fv']['field_value']); 
                                        } 
                                        $xml->endElement();//drugreactionrelatedness
                                    
                                    $xml->endElement();//drug
                                }
                                //summary
                                $xml->startElement("summary");
                                    $summary=$this->getErgodicTag($caseId,5,252);
                                    foreach($summary as $Summary){
                                        $xml->writeElement($Summary['tag'],$Summary['fv']['field_value']);
                                    }    
                                $xml->endElement();//summary*/ 
                            $xml->endElement();//patient
                        $xml->endElement();//safetyreport
                    $xml->endAttribute();
                $xml->endElement();//rootelement"ichicsr"
            $xml->endDtd();
            $xml->endDocument();
            echo $xml->outputMemory(); 
    }
    public function XMLvalue($caseId,$field_id,$set_num=null){
        $more_conditions = "";
        if (!is_null($set_num))
        { 
            $more_conditions = "set_number=".substr($set_num,0,1);
        }
        $sdFieldValues = TableRegistry::get('sdFieldValues');
        $direct =$sdFieldValues->find()
            ->select(['field_value'])
            ->where(['sd_case_id='.$caseId,'sd_field_id='.$field_id,'status=1',$more_conditions])->first();
        $directValue=$direct['field_value'];
        return $directValue;
    }

    public function doubleSetValue($caseId,$field_id,$set_one,$set_two){ 
            $sdFieldValues = TableRegistry::get('sdFieldValues');
            $direct =$sdFieldValues->find()
            ->select(['field_value'])
            ->where(['sd_case_id='.$caseId,'sd_field_id='.$field_id,'status=1','substr(set_number,-1)='.$set_one,'substr(set_number,1)='.$set_two])->first();
             $directValue=$direct['field_value'];
        return $directValue;
    }

    public function thirdSetValue($caseId,$field_id,$set_one,$set_two,$set_three){ 
        $sdFieldValues = TableRegistry::get('sdFieldValues');
        $direct =$sdFieldValues->find()
        ->select(['field_value'])
        ->where(['sd_case_id='.$caseId,'sd_field_id='.$field_id,'status=1','substr(set_number,-1)='.$set_one,'substr(set_number,-3,1)='.$set_two,'substr(set_number,1)='.$set_three])->first();
         $directValue=$direct['field_value'];
    return $directValue;
    }

    public function getMaxSet($caseId,$fieldId){
        $sdFieldValues = TableRegistry::get('sdFieldValues');
        $ICSR = $sdFieldValues ->find()
        ->select(['set_number'])
        ->where(['sd_case_id='.$caseId,'sd_field_id='.$fieldId])->toArray();
        $maxSet=max($ICSR);
        return $maxSet['set_number']; 
    }
    public function getRepeatSet($caseId,$fieldId){
        $sdFieldValues = TableRegistry::get('sdFieldValues')->find()
        ->select(['set_number','field_value'])
        ->where(['sd_case_id='.$caseId,'sd_field_id='.$fieldId,'status=1'])
        ->toArray();
        return $sdFieldValues; 
    }
    public function getSecondLevel($caseId,$fieldId,$mainSet){
        $sdFieldValues=TableRegistry::get('sdFieldValues')
        ->find() 
        ->select(['set_number','field_value'])
        ->where(['sd_case_id='.$caseId,'sd_field_id='.$fieldId,'substr(set_number,-1)='.$mainSet,'status=1'])
        ->toArray();
        return $sdFieldValues; 
    }
    public function getThirdLevel($caseId,$fieldId,$set_one,$set_two){
        $sdFieldValues=TableRegistry::get('sdFieldValues')
        ->find() 
        ->select(['set_number','field_value'])
        ->where(['sd_case_id='.$caseId,'sd_field_id='.$fieldId,'substr(set_number,-1)='.$set_one,'substr(set_number,-3,1)='.$set_two,'status=1'])
        ->toArray();
        return $sdFieldValues; 
    }

    public function genXMLThree($caseId){
        $this->autoRender = false;
        //set file name with caseNo and create time
        $sdCases = TableRegistry::get('sdCases');
        $senderCompany = $this->request->getSession()->read('Auth.User.company_id');
        $senderInfo = TableRegistry::get('sdSenders')->find()->where(['sd_company_id'=>$senderCompany])->first();
        $event= $this->getRepeatSet($caseId,149);   
        $docs= TableRegistry::get('sdDocuments')->find()->select(['doc_name','doc_type'])->where(['sd_case_id='.$caseId,'is_deleted=0'])->toArray();
        $medHistory = $this->getRepeatSet($caseId,132);
        $pastDrug = $this->getRepeatSet($caseId,140);
        $studyRegistration = $this->getRepeatSet($caseId,1003);
        $medHistoryPatient = $this->getRepeatSet($caseId,97);
        $pastDrugPatient = $this->getRepeatSet($caseId,105);
        $death= $this->getRepeatSet($caseId,117);
        $autopsy= $this->getRepeatSet($caseId,120);
        $test = $this->getRepeatSet($caseId,1028);
        $drugRole = $this->getRepeatSet($caseId,175);
        $senderDiagnosis=$this->getRepeatSet($caseId,221);
        $caseSummary=$this->getRepeatSet($caseId,1050);
        $caseIdentifier=$this->getRepeatSet($caseId,20);
        $linkedId =$this->getRepeatSet($caseId,21);
        $product = $this->getRepeatSet($caseId,176);
        $drugSet =$this->getMaxSet($caseId,176);
        $eventSet =$this->getMaxSet($caseId,149);
        //debug($this->thirdSetValue($caseId,216,2,4,1));
        //die();
        $name=$sdCases->find()->select(['caseNo'])->where(['id='.$caseId,'status=1'])->first();
        $fileName=$name['caseNo'];
        $time=date("Y-m-d-H-i-s");
        $transmissionTime=date("YmdHis");
        $xml = "$fileName-$time";
        header("Content-Type: text/html/force-download");
        header("Content-Disposition: attachment; filename=".$xml.".xml");
        $value="fieldvalue";
        $xml = new \XMLWriter();
        $xml->openUri("php://output");
        $xml->setIndentString("\t");
        $xml->setIndent(true);
        $xml->startDocument('1.0', 'UTF-8');//FDA supports only the ISO-8859-1 character set for encoding the submission.
            $xml->startElement("MCCI_IN200100UV01");
            $xml->writeAttribute('ITSVersion','XML_1.0');
            $xml->writeAttribute('xsi:schemaLocation','urn:hl7-org:v3 MCCI_IN200100UV01.xsd');
            $xml->writeAttribute('xmlns','urn:hl7-org:v3');
            $xml->writeAttributeNS('xmlns','xsi', null,'http://www.w3.org/2001/XMLSchema-instance');
                $xml->startElement("id");
                $xml->writeAttribute('extension','GSUN-B123456789');
                $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.22');
                $xml->endElement();  
                $xml->writeComment(" N.1.2: Batch Number ");
                $xml->startElement("creationTime");
                $xml->writeAttribute('value',$transmissionTime);
                $xml->endElement();
                $xml->writeComment(" N.1.5: Date of Batch Transmission ");
                $xml->startElement("responseModeCode");
                $xml->writeAttribute('code','D');
                $xml->endElement();
                $xml->startElement("interactionId");
                $xml->writeAttribute('extension','MCCI_IN200100UV01');
                $xml->writeAttribute('root','2.16.840.1.113883.1.6');
                $xml->endElement();
                $xml->startElement("name");
                $xml->writeAttribute('code','1');
                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.1');
                $xml->writeAttribute('codeSystemVersion','1.01');      
                $xml->endElement();
                $xml->writeComment(" N.1.1: Type of Messages in Batch ");
                $xml->startElement("PORR_IN049016UV");
                    $xml->startElement("id");
                        $xml->writeAttribute('extension',$fileName);
                        $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.1');
                    $xml->endElement();
                    $xml->writeComment(" N.2.r.1: Message Identifier ");
                    $xml->startElement("creationTime");
                        $xml->writeAttribute('value',$this->XMLvalue($caseId,5,1));
                    $xml->endElement();
                    $xml->writeComment(" N.2.r.4: Date of Message Creation ");
                    $xml->startElement("interactionId");
                    $xml->writeAttribute('extension','PORR_IN049016UV');
                    $xml->writeAttribute('root','2.16.840.1.113883.1.6');
                    $xml->endElement();
                    $xml->startElement("processingCode");
                    $xml->writeAttribute('code','P');
                    $xml->endElement();
                    $xml->startElement("processingModeCode");
                    $xml->writeAttribute('code','T');
                    $xml->endElement();
                    $xml->startElement("acceptAckCode");
                    $xml->writeAttribute('code','AL');
                    $xml->endElement();
                    $xml->startElement("receiver");
                    $xml->writeAttribute('typeCode','RCV');
                        $xml->startElement("device");
                        $xml->writeAttribute('classCode','DEV');
                        $xml->writeAttribute('determinerCode','INSTANCE');
                            $xml->startElement("id");
                                $xml->writeAttribute('extension','ICHTEST');
                                $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.12');
                            $xml->endElement();
                            $xml->writeComment(" N.2.r.3: Message Receiver Identifier ");
                        $xml->endElement();//device
                    $xml->endElement();//receiver
                    $xml->startElement("sender");
                    $xml->writeAttribute('typeCode','SND');
                        $xml->startElement("device");
                        $xml->writeAttribute('classCode','DEV');
                        $xml->writeAttribute('determinerCode','INSTANCE');
                            $xml->startElement("id");
                            $xml->writeAttribute('extension',$this->XMLvalue($caseId,1057,1));
                            $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.11');
                            $xml->endElement();
                            $xml->writeComment(" N.2.r.2: Message Sender Identifier ");
                        $xml->endElement();//device
                    $xml->endElement();//sender
                    $xml->startElement("controlActProcess");
                        $xml->writeAttribute('classCode','CACT');
                        $xml->writeAttribute('moodCode','EVN');
                        $xml->startElement("code");
                        $xml->writeAttribute('code','PORR_TE049016UV');
                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.1.18');
                        $xml->endElement();
                        $xml->writeComment(" HL7 Trigger Event ID ");
                        $xml->startElement("effectiveTime");
                        $xml->writeAttribute('value',$this->XMLvalue($caseId,5,1));
                        $xml->endElement();
                        $xml->writeComment(" C.1.2: Date of Creation ");
                        $xml->startElement("subject");
                        $xml->writeAttribute('typeCode','SUBJ');
                            $xml->startElement("investigationEvent");
                            $xml->writeAttribute('classCode','INVSTG');
                            $xml->writeAttribute('moodCode','EVN');
                                $xml->startElement("id");
                                $xml->writeAttribute('extension',$this->XMLvalue($caseId,1,1));
                                $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.1');
                                $xml->endElement();
                                $xml->writeComment(" C.1.1: Sender's (case) Safety Report Unique Identifier ");
                                $xml->startElement("id");
                                $xml->writeAttribute('extension',$this->XMLvalue($caseId,16,1));
                                $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.2');
                                $xml->endElement();
                                $xml->writeComment(" C.1.8.1: Worldwide Unique Case Identification Number ");
                                $xml->startElement("code");
                                $xml->writeAttribute('code','PAT_ADV_EVNT');
                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.5.4');
                                $xml->endElement();
                                $xml->writeElement("text",$this->XMLvalue($caseId,218,1));
                                $xml->writeComment(" H.1: Case Narrative Including Clinical Course, Therapeutic Measures, Outcome and Additional Relevant Information ");
                                $xml->startElement("statusCode");
                                $xml->writeAttribute('code','active');
                                $xml->endElement();
                                $xml->startElement("effectiveTime");
                                    $xml->startElement("low");
                                    $xml->writeAttribute('value',$this->XMLvalue($caseId,10,1));
                                    $xml->endElement();
                                    $xml->writeComment(" C.1.4: Date Report Was First Received from Source ");
                                $xml->endElement();
                                $xml->startElement("availabilityTime");
                                $xml->writeAttribute('value',$this->XMLvalue($caseId,12,1));
                                $xml->endElement();
                                $xml->writeComment(" C.1.5: Date of Most Recent Information for This Report ");
                            if($docs!=null){
                                $i=0;
                                foreach($docs as $docs_details){
                                    $i=$i+1;
                                $xml->startElement("reference");
                                $xml->writeAttribute('typeCode','REFR');
                                    $xml->startElement("document");
                                    $xml->writeAttribute('classCode','DOC');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','1');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.27');
                                        $xml->writeAttribute('codeSystemVersion','1.0');
                                        $xml->writeAttribute('displayName','documentsHeldBySender');
                                        $xml->endElement();
                                        $xml->startElement("title");
                                        $xml->text($docs_details['doc_name']);
                                        $xml->endElement();
                                        $xml->writeComment(' C.1.6.1.r.1: Documents Held by Sender (repeat as necessary) #'.$i);
                                    if($this->XMLvalue($caseId,1000,$i)!=null){
                                        $xml->startElement("text");
                                        $xml->writeAttribute('mediaType',$docs_details['doc_type']);
                                        $xml->writeAttribute('representation','B64');
                                        $xml->writeAttribute('compression','DF');
                                        $xml->text($this->XMLvalue($caseId,1000,$i));
                                        $xml->endElement();
                                        $xml->writeComment(' C.1.6.1.r.2: Included Documents #'.$i);
                                    }
                                    $xml->endElement();//document可重复
                                $xml->endElement();//reference
                                }
                            }
                            if($this->XMLvalue($caseId,37,1)!=null){
                                $xml->startElement("reference");
                                $xml->writeAttribute('typeCode','REFR');
                                    $xml->startElement("document");
                                    $xml->writeAttribute('classCode','DOC');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','2');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.27');
                                        $xml->writeAttribute('codeSystemVersion','1.0');
                                        $xml->writeAttribute('displayName','literatureReference');
                                        $xml->endElement();
                                        $xml->startElement("text");
                                        $xml->writeAttribute('mediaType',$this->XMLvalue($caseId,1002,1));
                                        $xml->writeAttribute('representation','B64');
                                        $xml->text($this->XMLvalue($caseId,1002,1));
                                        $xml->endElement();
                                        $xml->writeComment(' C.4.r.2: Included Documents #');
                                        $xml->writeElement("bibliographicDesignationText",$this->XMLvalue($caseId,37,1));
                                        $xml->writeComment(' C.4.r.1: Literature Reference(s) #');
                                    $xml->endElement();//document可重复
                                $xml->endElement();//reference
                            }
                                $xml->startElement("component");
                                $xml->writeAttribute('typeCode','COMP');
                                    $xml->startElement("adverseEventAssessment");//adverseEventAssessment
                                    $xml->writeAttribute('classCode','INVSTG');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("subject1");//subject1
                                        $xml->writeAttribute('typeCode','SBJ');
                                            $xml->startElement("primaryRole");//primaryRole
                                            $xml->writeAttribute('classCode','INVSBJ');
                                                $xml->startElement("player1");
                                                $xml->writeAttribute('classCode','PSN');
                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                    $xml->startElement("name");
                                                    $xml->text($this->XMLvalue($caseId,79,1));//$this->XMLvalue($caseId,1080,1)
                                                    $xml->endElement();
                                                    $xml->writeComment(" D.1: Patient (name or initials) ");
                                                if($this->XMLvalue($caseId,93,1)!=null){
                                                    $xml->startElement("administrativeGenderCode");
                                                    $xml->writeAttribute('code',$this->XMLvalue($caseId,93,1));
                                                    $xml->writeAttribute('codeSystem','1.0.5218');
                                                    $xml->endElement();
                                                    $xml->writeComment(" D.5 Sex ");
                                                }
                                                if($this->XMLvalue($caseId,85,1)!=null){
                                                    $xml->startElement("birthTime");
                                                    $xml->writeAttribute('value',$this->XMLvalue($caseId,85,1));
                                                    $xml->endElement();
                                                    $xml->writeComment(" D.2.1: Date of Birth ");
                                                }
                                                if($this->XMLvalue($caseId,115,1)!=null){
                                                    $xml->startElement("deceasedTime");
                                                    $xml->writeAttribute('value',$this->XMLvalue($caseId,115,1));
                                                    $xml->endElement();
                                                    $xml->writeComment(" D.9.1: Date of Death ");
                                                }
                                                if($this->XMLvalue($caseId,80,1)!=null){
                                                    $xml->startElement("asIdentifiedEntity");
                                                    $xml->writeAttribute('classCode','IDENT');
                                                        $xml->startElement("id");
                                                        $xml->writeAttribute('extension',$this->XMLvalue($caseId,80,1));
                                                        $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.7');
                                                        $xml->endElement();
                                                        $xml->writeComment(" D.1.1.1: Patient Medical Record Number(s) and Source(s) of the Record Number (GP Medical Record Number)");
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','1');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.4');
                                                        $xml->writeAttribute('codeSystemVersion','1.0');
                                                        $xml->writeAttribute('displayName','GP');
                                                        $xml->endElement();
                                                    $xml->endElement();//asIdentifiedEntity
                                                }
                                                if($this->XMLvalue($caseId,81,1)!=null){
                                                    $xml->startElement("asIdentifiedEntity");
                                                    $xml->writeAttribute('classCode','IDENT');
                                                        $xml->startElement("id");
                                                        $xml->writeAttribute('extension',$this->XMLvalue($caseId,81,1));
                                                        $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.8');
                                                        $xml->endElement();
                                                        $xml->writeComment("D.1.1.2: Patient Medical Record Number(s) and Source(s) of the Record Number (Specialist Record Number)");
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','2');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.4');
                                                        $xml->writeAttribute('codeSystemVersion','1.0');
                                                        $xml->writeAttribute('displayName','Specialist');
                                                        $xml->endElement();
                                                    $xml->endElement();//asIdentifiedEntity
                                                }
                                                if($this->XMLvalue($caseId,82,1)!=null){
                                                    $xml->startElement("asIdentifiedEntity");
                                                    $xml->writeAttribute('classCode','IDENT');
                                                        $xml->startElement("id");
                                                        $xml->writeAttribute('extension',$this->XMLvalue($caseId,82,1));
                                                        $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.9');
                                                        $xml->endElement();
                                                        $xml->writeComment("D.1.1.3: Patient Medical Record Number(s) and Source(s) of the Record Number (Hospital Record Number)");
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','3');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.4');
                                                        $xml->writeAttribute('codeSystemVersion','1.0');
                                                        $xml->writeAttribute('displayName','Hospital Record');
                                                        $xml->endElement();
                                                    $xml->endElement();//asIdentifiedEntity
                                                }
                                                if($this->XMLvalue($caseId,83,1)!=null){
                                                    $xml->startElement("asIdentifiedEntity");
                                                    $xml->writeAttribute('classCode','IDENT');
                                                        $xml->startElement("id");
                                                        $xml->writeAttribute('extension',$this->XMLvalue($caseId,83,1));
                                                        $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.10');
                                                        $xml->endElement();
                                                        $xml->writeComment("D.1.1.4: Patient Medical Record Number(s) and Source(s) of the Record Number (Investigation Number)");
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','4');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.4');
                                                        $xml->writeAttribute('codeSystemVersion','1.0');
                                                        $xml->writeAttribute('displayName','Investigation');
                                                        $xml->endElement();
                                                    $xml->endElement();//asIdentifiedEntity
                                                }
                                                    $xml->startElement("role");
                                                    $xml->writeAttribute('classCode','PRS');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','PRN');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.5.111');
                                                        $xml->endElement();
                                                    if($this->XMLvalue($caseId,121,1)!=null){
                                                        $xml->startElement("associatedPerson");
                                                        $xml->writeAttribute('classCode','PSN');
                                                        $xml->writeAttribute('determinerCode','INSTANCE');
                                                            $xml->startElement("name");
                                                            $xml->text($this->XMLvalue($caseId,121,1));
                                                            $xml->endElement();
                                                            $xml->writeComment("D.10.1: Parent Identification");
                                                        if($this->XMLvalue($caseId,130,1)!=null){
                                                            $xml->startElement("administrativeGenderCode");
                                                            $xml->writeAttribute('code',$this->XMLvalue($caseId,130,1));
                                                            $xml->writeAttribute('codeSystem','1.0.5218');
                                                            $xml->endElement();
                                                            $xml->writeComment("D.10.6: Sex of Parent");
                                                        }
                                                        if($this->XMLvalue($caseId,123,1)!=null){
                                                            $xml->startElement("birthTime");
                                                            $xml->writeAttribute('value',$this->XMLvalue($caseId,123,1));
                                                            $xml->endElement();
                                                            $xml->writeComment("D.10.2.1: Date of Birth of Parent");
                                                        }
                                                        $xml->endElement();//associatedPerson
                                                    }
                                                    if($this->XMLvalue($caseId,124,1)!=null){
                                                        $xml->startElement("subjectOf2");
                                                        $xml->writeAttribute('typeCode','SBJ');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','3');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->writeAttribute('codeSystemVersion','1.1');
                                                                $xml->writeAttribute('displayName','age');
                                                                $xml->endElement();//code
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','PQ');
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,124,1));
                                                                $xml->writeAttribute('unit',$this->XMLvalue($caseId,125,1));
                                                                $xml->endElement();//value
                                                                $xml->writeComment("D.10.2.2a: Age of Parent (number)");
                                                                $xml->writeComment("D.10.2.2b: Age of Parent (unit)");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//subjectOf2
                                                    }
                                                    if($this->XMLvalue($caseId,127,1)!=null){
                                                        $xml->startElement("subjectOf2");
                                                        $xml->writeAttribute('typeCode','SBJ');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','22');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->writeAttribute('codeSystemVersion','1.1');
                                                                $xml->writeAttribute('displayName','lastMenstrualPeriodDate');
                                                                $xml->endElement();//code
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','TS');
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,127,1));
                                                                $xml->endElement();//value
                                                                $xml->writeComment("D.10.3: Last Menstrual Period Date of Parent");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//subjectOf2
                                                    }
                                                    if($this->XMLvalue($caseId,128,1)!=null){
                                                        $xml->startElement("subjectOf2");
                                                        $xml->writeAttribute('typeCode','SBJ');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','7');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->writeAttribute('codeSystemVersion','1.1');
                                                                $xml->writeAttribute('displayName','bodyWeight');
                                                                $xml->endElement();//code
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','PQ');
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,128,1));
                                                                $xml->writeAttribute('unit','kg');
                                                                $xml->endElement();//value
                                                                $xml->writeComment("D.10.4: Body Weight (kg) of Parent");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//subjectOf2
                                                    }
                                                    if($this->XMLvalue($caseId,129,1)!=null){
                                                        $xml->startElement("subjectOf2");
                                                        $xml->writeAttribute('typeCode','SBJ');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','17');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->writeAttribute('codeSystemVersion','1.1');
                                                                $xml->writeAttribute('displayName','height');
                                                                $xml->endElement();//code
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','PQ');
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,129,1));
                                                                $xml->writeAttribute('unit','cm');
                                                                $xml->endElement();//value
                                                                $xml->writeComment("D.10.5: Height (cm) of Parent");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//subjectOf2
                                                    }
                                                    //Relevant Medical History and Concurrent Condition of Parents
                                                    if($medHistory!=null){
                                                        foreach($medHistory as $medHistory_details){
                                                        $xml->startElement("subjectOf2");
                                                        $xml->writeAttribute('typeCode','SBJ');
                                                            $xml->startElement("organizer");
                                                            $xml->writeAttribute('classCode','CATEGORY');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','1');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.20');
                                                                $xml->writeAttribute('codeSystemVersion','1.0');
                                                                $xml->writeAttribute('displayName','relevantMedicalHistoryAndConcurrentConditions');
                                                                $xml->endElement();//code
                                                                $xml->startElement("component");
                                                                $xml->writeAttribute('typeCode','COMP');
                                                                    $xml->startElement("observation");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,132,$medHistory_details['set_number']));
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.6.163');
                                                                        $xml->writeAttribute('codeSystemVersion',$this->XMLvalue($caseId,131,$medHistory_details['set_number']));
                                                                        $xml->endElement();//code
                                                                        $xml->writeComment("D.10.7.1.r.1a: MedDRA Version for Medical History #".$medHistory_details['set_number']);
                                                                        $xml->writeComment("D.10.7.1.r.1b: Medical History (disease / surgical procedure/ etc.) (MedDRA code) #".$medHistory_details['set_number']);
                                                                        $xml->startElement("effectiveTime");
                                                                        $xml->writeAttribute('xsi:type','IVL_TS');
                                                                            $xml->startElement("low");
                                                                            $xml->writeAttribute('value',$this->XMLvalue($caseId,134,$medHistory_details['set_number']));
                                                                            $xml->endElement();//low
                                                                            $xml->writeComment("D.10.7.1.r.2: Start Date #".$medHistory_details['set_number']);
                                                                            $xml->startElement("high");
                                                                            $xml->writeAttribute('value',$this->XMLvalue($caseId,137,$medHistory_details['set_number']));
                                                                            $xml->endElement();//high
                                                                            $xml->writeComment("D.10.7.1.r.4: End Date #".$medHistory_details['set_number']);
                                                                        $xml->endElement();//effectiveTime
                                                                        $xml->startElement("outboundRelationship2");
                                                                        $xml->writeAttribute('typeCode','COMP');
                                                                            $xml->startElement("observation");
                                                                            $xml->writeAttribute('classCode','OBS');
                                                                            $xml->writeAttribute('moodCode','EVN');
                                                                                $xml->startElement("code");
                                                                                $xml->writeAttribute('code','10');
                                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                                $xml->writeAttribute('codeSystemVersion','1.1');
                                                                                $xml->writeAttribute('displayName','comment');
                                                                                $xml->endElement();//code
                                                                                $xml->startElement("value");
                                                                                $xml->writeAttribute('xsi:type','ED');
                                                                                $xml->text($this->XMLvalue($caseId,138,$medHistory_details['set_number']));
                                                                                $xml->endElement();//value
                                                                                $xml->writeComment("D.10.7.1.r.5: Comments #".$medHistory_details['set_number']);
                                                                            $xml->endElement();//observation
                                                                        $xml->endElement();//outboundRelationship2
                                                                        $xml->startElement("inboundRelationship");
                                                                        $xml->writeAttribute('typeCode','REFR');
                                                                            $xml->startElement("observation");
                                                                            $xml->writeAttribute('classCode','OBS');
                                                                            $xml->writeAttribute('moodCode','EVN');
                                                                                $xml->startElement("code");
                                                                                $xml->writeAttribute('code','13');
                                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                                $xml->writeAttribute('codeSystemVersion','1.1');
                                                                                $xml->writeAttribute('displayName','continuing');
                                                                                $xml->endElement();//code
                                                                                $xml->startElement("value");
                                                                                $xml->writeAttribute('xsi:type','BL');
                                                                                $xml->text($this->XMLvalue($caseId,135,1));
                                                                                $xml->endElement();//value
                                                                                $xml->writeComment("D.10.7.1.r.3: Continuing #".$medHistory_details['set_number']);
                                                                            $xml->endElement();//observation
                                                                        $xml->endElement();//inboundRelationship
                                                                    $xml->endElement();//observation
                                                                $xml->endElement();//component
                                                                $xml->startElement("component");
                                                                $xml->writeAttribute('typeCode','COMP');
                                                                    $xml->startElement("observation");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code','18');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                        $xml->writeAttribute('codeSystemVersion','1.1');
                                                                        $xml->writeAttribute('displayName','historyAndConcurrentConditionText');
                                                                        $xml->endElement();//code
                                                                        $xml->startElement("value");
                                                                        $xml->writeAttribute('xsi:type','ED');
                                                                        $xml->text($this->XMLvalue($caseId,139,$medHistory_details['set_number']));
                                                                        $xml->endElement();//value
                                                                        $xml->writeComment("D.10.7.2: Text for Relevant Medical History and Concurrent Conditions of Parent #".$medHistory_details['set_number']);
                                                                    $xml->endElement();//observation
                                                                $xml->endElement();//component
                                                            $xml->endElement();//organizer
                                                        $xml->endElement();//subjectOf2
                                                        }
                                                    }
                                                    //Relevant Past Drug History of Parents
                                                    if($pastDrug!=null){
                                                        foreach($pastDrug as $pastDrug_details){
                                                        $xml->startElement("subjectOf2");
                                                        $xml->writeAttribute('typeCode','SBJ');
                                                            $xml->startElement("organizer");
                                                            $xml->writeAttribute('classCode','CATEGORY');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','2');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.20');
                                                                $xml->writeAttribute('codeSystemVersion','1.0');
                                                                $xml->writeAttribute('displayName','drugHistory');
                                                                $xml->endElement();//code
                                                                $xml->startElement("component");
                                                                $xml->writeAttribute('typeCode','COMP');
                                                                    $xml->startElement("substanceAdministration");
                                                                    $xml->writeAttribute('classCode','SBADM');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("effectiveTime");
                                                                            $xml->writeAttribute('xsi:type','IVL_TS');
                                                                            $xml->startElement("low");
                                                                            $xml->writeAttribute('value',$this->XMLvalue($caseId,142,$pastDrug_details['set_number']));
                                                                            $xml->endElement();
                                                                            $xml->writeComment("D.10.8.r.4: Start Date #".$pastDrug_details['set_number']); 
                                                                            $xml->startElement("high");
                                                                            $xml->writeAttribute('value',$this->XMLvalue($caseId,144,$pastDrug_details['set_number']));
                                                                            $xml->endElement();
                                                                            $xml->writeComment("D.10.8.r.5: End Date #".$pastDrug_details['set_number']); 
                                                                        $xml->endElement();//effectiveTime
                                                                        $xml->startElement("consumable");
                                                                            $xml->writeAttribute('typeCode','CSM');
                                                                            $xml->startElement("instanceOfKind");
                                                                            $xml->writeAttribute('classCode','INST');
                                                                                $xml->startElement("kindOfProduct");
                                                                                $xml->writeAttribute('classCode','MMAT');
                                                                                $xml->writeAttribute('determinerCode','KIND');
                                                                                    $xml->startElement("code");
                                                                                    $xml->writeAttribute('code',$this->XMLvalue($caseId,1014,$pastDrug_details['set_number']));
                                                                                    $xml->writeAttribute('codeSystem','TBD-MPID');
                                                                                    $xml->writeAttribute('codeSystemVersion',$this->XMLvalue($caseId,1013,$pastDrug_details['set_number']));
                                                                                    $xml->endElement();//code
                                                                                    $xml->writeComment("D.10.8.r.2a: MPID Version Date / Number #".$pastDrug_details['set_number']); 
                                                                                    $xml->writeComment("D.10.8.r.2b: Medicinal Product Identifier (MPID) #".$pastDrug_details['set_number']); 
                                                                                    $xml->startElement("name");
                                                                                    $xml->text($this->XMLvalue($caseId,140,$pastDrug_details['set_number']));
                                                                                    $xml->endElement();//name
                                                                                    $xml->writeComment("D.10.8.r.1: Name of Drug as Reported #".$pastDrug_details['set_number']); 
                                                                                $xml->endElement();//kindOfProduct
                                                                            $xml->endElement();//instanceOfKind
                                                                        $xml->endElement();//consumable
                                                                        $xml->startElement("outboundRelationship2");
                                                                        $xml->writeAttribute('typeCode','RSON');
                                                                            $xml->startElement("observation");
                                                                            $xml->writeAttribute('classCode','OBS');
                                                                            $xml->writeAttribute('moodCode','EVN');
                                                                                $xml->startElement("code");
                                                                                $xml->writeAttribute('code','19');
                                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                                $xml->writeAttribute('codeSystemVersion','1.1');
                                                                                $xml->writeAttribute('displayName','indication');
                                                                                $xml->endElement();//code
                                                                                $xml->startElement("value");
                                                                                $xml->writeAttribute('xsi:type','CE');
                                                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,146,$pastDrug_details['set_number']));
                                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.6.163');
                                                                                $xml->writeAttribute('codeSystemVersion',$this->XMLvalue($caseId,145,$pastDrug_details['set_number']));
                                                                                $xml->endElement();//value
                                                                                $xml->writeComment("D.10.8.r.6a: MedDRA Version for Indication #".$pastDrug_details['set_number']);
                                                                                $xml->writeComment("D.10.8.r.6b: Indication (MedDRA code) #".$pastDrug_details['set_number']);
                                                                            $xml->endElement();//observation
                                                                        $xml->endElement();//outboundRelationship2
                                                                        $xml->startElement("outboundRelationship2");
                                                                        $xml->writeAttribute('typeCode','CAUS');
                                                                            $xml->startElement("observation");
                                                                            $xml->writeAttribute('classCode','OBS');
                                                                            $xml->writeAttribute('moodCode','EVN');
                                                                                $xml->startElement("code");
                                                                                $xml->writeAttribute('code','29');
                                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                                $xml->writeAttribute('codeSystemVersion','1.1');
                                                                                $xml->writeAttribute('displayName','reaction');
                                                                                $xml->endElement();//code
                                                                                $xml->startElement("value");
                                                                                $xml->writeAttribute('xsi:type','CE');
                                                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,148,$pastDrug_details['set_number']));
                                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.6.163');
                                                                                $xml->writeAttribute('codeSystemVersion',$this->XMLvalue($caseId,147,$pastDrug_details['set_number']));
                                                                                $xml->endElement();//value
                                                                                $xml->writeComment("D.10.8.r.7a: MedDRA Version for Reaction #".$pastDrug_details['set_number']);
                                                                                $xml->writeComment("D.10.8.r.7b: Reactions (MedDRA code) #".$pastDrug_details['set_number']);
                                                                            $xml->endElement();//observation
                                                                        $xml->endElement();//outboundRelationship2
                                                                    $xml->endElement();//substanceAdministration
                                                                $xml->endElement();//component
                                                            $xml->endElement();//organizer
                                                        $xml->endElement();//subjectOf2
                                                        }
                                                    }
                                                    $xml->endElement();//role
                                                $xml->endElement();//player1
                                                $xml->startElement("subjectOf1");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("researchStudy");
                                                    $xml->writeAttribute('classCode','CLNTRL');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                    if($this->XMLvalue($caseId,39,1)!=null){
                                                        $xml->startElement("id");
                                                        $xml->writeAttribute('extension',$this->XMLvalue($caseId,39,1));
                                                        $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.5');
                                                        $xml->endElement();
                                                        $xml->writeComment(" C.5.3: Sponsor Study Number ");
                                                    }
                                                    if($this->XMLvalue($caseId,40,1)!=null){
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,40,1));
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.8');
                                                        $xml->writeAttribute('codeSystemVersion','1.0');
                                                        $xml->endElement();//code
                                                        $xml->writeComment(" C.5.4: Study Type Where Reaction(s) / Event(s) Were Observed ");
                                                    }
                                                    if($this->XMLvalue($caseId,38,1)!=null){
                                                        $xml->startElement("title");
                                                        $xml->text($this->XMLvalue($caseId,38,1));
                                                        $xml->endElement();//title
                                                        $xml->writeComment(" C.5.2: Study Name ");
                                                    }
                                                    if($studyRegistration!=null){
                                                        foreach($studyRegistration as $studyRegistration_details){
                                                        $xml->startElement("authorization");
                                                        $xml->writeAttribute('typeCode','AUTH');
                                                            $xml->startElement("studyRegistration");
                                                            $xml->writeAttribute('classCode','ACT');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("id");
                                                                $xml->writeAttribute('extension',$this->XMLvalue($caseId,1003,$studyRegistration_details['set_number']));
                                                                $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.6');
                                                                $xml->endElement();//id
                                                                $xml->writeComment(" C.5.1.r.1: Study Registration Number #".$studyRegistration_details['set_number']);
                                                                $xml->startElement("author");
                                                                $xml->writeAttribute('typeCode','AUT');
                                                                    $xml->startElement("territorialAuthority");
                                                                    $xml->writeAttribute('classCode','TERR');
                                                                        $xml->startElement("governingPlace");
                                                                        $xml->writeAttribute('classCode','COUNTRY');
                                                                        $xml->writeAttribute('determinerCode','INSTANCE');
                                                                            $xml->startElement("code");
                                                                            $xml->writeAttribute('code',$this->XMLvalue($caseId,1004,$studyRegistration_details['set_number']));
                                                                            $xml->writeAttribute('codeSystem','1.0.3166.1.2.2');
                                                                            $xml->endElement();//code
                                                                            $xml->writeComment(" C.5.1.r.2: Study Registration Country #".$studyRegistration_details['set_number']);
                                                                        $xml->endElement();//governingPlace
                                                                    $xml->endElement();//territorialAuthority
                                                                $xml->endElement();//author
                                                            $xml->endElement();//studyRegistration
                                                        $xml->endElement();//authorization
                                                        }
                                                    }
                                                    $xml->endElement();//researchStudy
                                                $xml->endElement();//subjectOf1
                                            if($this->XMLvalue($caseId,86,1)!=null){
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("observation");
                                                    $xml->writeAttribute('classCode','OBS');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','3');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                        $xml->writeAttribute('codeSystemVersion','1.1');
                                                        $xml->writeAttribute('displayName','age');
                                                        $xml->endElement();
                                                        $xml->startElement("value");
                                                        $xml->writeAttribute('xsi:type','PQ');
                                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,86,1));
                                                        $xml->writeAttribute('unit',$this->XMLvalue($caseId,87,1));
                                                        $xml->endElement();
                                                        $xml->writeComment(" D.2.2a: Age at Time of Onset of Reaction / Event (number) ");
                                                        $xml->writeComment(" D.2.2b: Age at Time of Onset of Reaction / Event (unit) ");
                                                    $xml->endElement();//observation
                                                $xml->endElement();//subjectOf2
                                            }
                                            if($this->XMLvalue($caseId,88,1)!=null){
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("observation");
                                                    $xml->writeAttribute('classCode','OBS');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','16');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                        $xml->writeAttribute('codeSystemVersion','1.1');
                                                        $xml->writeAttribute('displayName','gestationPeriod');
                                                        $xml->endElement();
                                                        $xml->startElement("value");
                                                        $xml->writeAttribute('xsi:type','PQ');
                                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,88,1));
                                                        $xml->writeAttribute('unit',$this->XMLvalue($caseId,89,1));
                                                        $xml->endElement();
                                                        $xml->writeComment(" D.2.2.1a: Gestation Period When Reaction / Event Was Observed in the Foetus (number) ");
                                                        $xml->writeComment(" D.2.2.1b: Gestation Period When Reaction / Event Was Observed in the Foetus (unit) ");
                                                    $xml->endElement();//observation
                                                $xml->endElement();//subjectOf2
                                            }
                                            if($this->XMLvalue($caseId,90,1)!=null){
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("observation");
                                                    $xml->writeAttribute('classCode','OBS');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','4');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                        $xml->writeAttribute('codeSystemVersion','1.1');
                                                        $xml->writeAttribute('displayName','ageGroup');
                                                        $xml->endElement();
                                                        $xml->startElement("value");
                                                        $xml->writeAttribute('xsi:type','CE');
                                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,90,1));
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                        $xml->writeAttribute('codeSystemVersion','1.0');
                                                        $xml->endElement();
                                                        $xml->writeComment(" D.2.3: Patient Age Group (as per reporter) ");
                                                    $xml->endElement();//observation
                                                $xml->endElement();//subjectOf2
                                            }
                                            if($this->XMLvalue($caseId,91,1)!=null){
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("observation");
                                                    $xml->writeAttribute('classCode','OBS');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','7');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                        $xml->writeAttribute('codeSystemVersion','1.1');
                                                        $xml->writeAttribute('displayName','bodyWeight');
                                                        $xml->endElement();
                                                        $xml->startElement("value");
                                                        $xml->writeAttribute('xsi:type','PQ');
                                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,91,1));
                                                        $xml->writeAttribute('unit','kg');
                                                        $xml->endElement();
                                                        $xml->writeComment(" D.3: Body Weight (kg) ");
                                                    $xml->endElement();//observation
                                                $xml->endElement();//subjectOf2
                                            }
                                                $xml->writeComment(" china extention start ");
                                            if($this->XMLvalue($caseId,1154,1)!=null){
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("observation");
                                                    $xml->writeAttribute('classCode','OBS');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','CN-1');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                        $xml->writeAttribute('codeSystemVersion','1.1');
                                                        $xml->writeAttribute('displayName','nation');
                                                        $xml->endElement();
                                                        $xml->startElement("value");
                                                        $xml->writeAttribute('xsi:type','TS');
                                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,1154,1));
                                                        $xml->endElement();
                                                        $xml->writeComment(" D.CN.1: nation ");
                                                    $xml->endElement();//observation
                                                $xml->endElement();//subjectOf2
                                            }
                                            if($this->XMLvalue($caseId,1155,1)!=null){
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("observation");
                                                    $xml->writeAttribute('classCode','OBS');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','CN-2');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                        $xml->writeAttribute('codeSystemVersion','1.1');
                                                        $xml->writeAttribute('displayName','race');
                                                        $xml->endElement();
                                                        $xml->startElement("value");
                                                        $xml->writeAttribute('xsi:type','TS');
                                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,1155,1));
                                                        $xml->endElement();
                                                        $xml->writeComment(" D.CN.2: race ");
                                                    $xml->endElement();//observation
                                                $xml->endElement();//subjectOf2
                                            }
                                            if($this->XMLvalue($caseId,1156,1)!=null){
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("observation");
                                                    $xml->writeAttribute('classCode','OBS');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','CN-3');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                        $xml->writeAttribute('codeSystemVersion','1.1');
                                                        $xml->writeAttribute('displayName','medicalOrgName');
                                                        $xml->endElement();
                                                        $xml->startElement("value");
                                                        $xml->writeAttribute('xsi:type','ED');
                                                        $xml->text($this->XMLvalue($caseId,1156,1));
                                                        $xml->endElement();
                                                        $xml->writeComment(" D.CN.3: Name of medical institution");
                                                    $xml->endElement();//observation
                                                $xml->endElement();//subjectOf2
                                            }
                                            if($this->XMLvalue($caseId,1157,1)!=null){
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("observation");
                                                    $xml->writeAttribute('classCode','OBS');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','CN-9');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                        $xml->writeAttribute('codeSystemVersion','1.1');
                                                        $xml->writeAttribute('displayName','nationality');
                                                        $xml->endElement();
                                                        $xml->startElement("value");
                                                        $xml->writeAttribute('xsi:type','CS');
                                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,1157,1));
                                                        $xml->endElement();
                                                        $xml->writeComment(" D.CN.4: 国籍");
                                                    $xml->endElement();//observation
                                                $xml->endElement();//subjectOf2
                                            }
                                            if($this->XMLvalue($caseId,1158,1)!=null){
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("observation");
                                                    $xml->writeAttribute('classCode','OBS');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','CN-10');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                        $xml->writeAttribute('codeSystemVersion','1.1');
                                                        $xml->writeAttribute('displayName','phone');
                                                        $xml->endElement();
                                                        $xml->startElement("value");
                                                        $xml->writeAttribute('xsi:type','TEL');
                                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,1158,1));
                                                        $xml->endElement();
                                                        $xml->writeComment(" D.CN.5: 联系电话");
                                                    $xml->endElement();//observation
                                                $xml->endElement();//subjectOf2
                                            }
                                            if($this->XMLvalue($caseId,1159,1)!=null){
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("observation");
                                                    $xml->writeAttribute('classCode','OBS');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','CN-13');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                        $xml->writeAttribute('codeSystemVersion','1.1');
                                                        $xml->writeAttribute('displayName','pregnancyOfDescription');
                                                        $xml->endElement();
                                                        $xml->startElement("value");
                                                        $xml->writeAttribute('xsi:type','ED');
                                                        $xml->text($this->XMLvalue($caseId,103,1));
                                                        $xml->endElement();
                                                        $xml->writeComment(" D.CN.6: 妊娠相关描述");
                                                    $xml->endElement();//observation
                                                $xml->endElement();//subjectOf2
                                            }
                                            if($this->XMLvalue($caseId,92,1)!=null){
                                                $xml->writeComment("china extention end");
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("observation");
                                                    $xml->writeAttribute('classCode','OBS');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','17');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                        $xml->writeAttribute('codeSystemVersion','1.1');
                                                        $xml->writeAttribute('displayName','height');
                                                        $xml->endElement();
                                                        $xml->startElement("value");
                                                        $xml->writeAttribute('xsi:type','PQ');
                                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,92,1));
                                                        $xml->writeAttribute('unit','cm');
                                                        $xml->endElement();
                                                        $xml->writeComment(" D.4: Height (cm) ");
                                                    $xml->endElement();//observation
                                                $xml->endElement();//subjectOf2
                                            }
                                            if($this->XMLvalue($caseId,95,1)!=null){
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("observation");
                                                    $xml->writeAttribute('classCode','OBS');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','22');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                        $xml->endElement();//code
                                                        $xml->startElement("value");
                                                        $xml->writeAttribute('xsi:type','TS');
                                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,95,1));
                                                        $xml->endElement();//value
                                                        $xml->writeComment(" D.6: Last Menstrual Period Date ");    
                                                    $xml->endElement();//observation
                                                $xml->endElement();//subjectOf2
                                            }
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("organizer");
                                                    $xml->writeAttribute('classCode','CATEGORY');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','1');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.20');
                                                        $xml->writeAttribute('codeSystemVersion','1.0');
                                                        $xml->writeAttribute('displayName','relevantMedicalHistoryAndConcurrentConditions');
                                                        $xml->endElement();
                                                    if($medHistoryPatient!=null){
                                                        foreach($medHistoryPatient as $medHistoryPatient_details){
                                                        $xml->startElement("component");
                                                        $xml->writeAttribute('typeCode','COMP');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,97,$medHistoryPatient_details['set_number']));
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.6.163');
                                                                $xml->writeAttribute('codeSystemVersion',$this->XMLvalue($caseId,96,$medHistoryPatient_details['set_number']));
                                                                $xml->endElement();
                                                                $xml->writeComment(" D.7.1.r.1a: MedDRA Version for Medical History #".$medHistoryPatient_details['set_number']);
                                                                $xml->writeComment(" D.7.1.r.1b: Medical History (disease / surgical procedure / etc.) (MedDRA code) #".$medHistoryPatient_details['set_number']);
                                                                $xml->startElement("effectiveTime");
                                                                $xml->writeAttribute('xsi:type','IVL_TS');
                                                                if($this->XMLvalue($caseId,99,$medHistoryPatient_details['set_number'])!=null){
                                                                    $xml->startElement("low");
                                                                    $xml->writeAttribute('value',$this->XMLvalue($caseId,99,$medHistoryPatient_details['set_number']));
                                                                    $xml->endElement();
                                                                    $xml->writeComment(" D.7.1.r.2: Start Date #".$medHistoryPatient_details['set_number']);
                                                                }
                                                                if($this->XMLvalue($caseId,102,$medHistoryPatient_details['set_number'])!=null){
                                                                    $xml->startElement("high");
                                                                    $xml->writeAttribute('value',$this->XMLvalue($caseId,102,$medHistoryPatient_details['set_number']));
                                                                    $xml->endElement();
                                                                    $xml->writeComment(" D.7.1.r.4: End Date #".$medHistoryPatient_details['set_number']);
                                                                }
                                                                $xml->endElement();//effectiveTime
                                                            if($this->XMLvalue($caseId,103,$medHistoryPatient_details['set_number'])!=null){
                                                                $xml->startElement("outboundRelationship2");
                                                                $xml->writeAttribute('typeCode','COMP');
                                                                    $xml->startElement("observation");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code','10');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                        $xml->writeAttribute('codeSystemVersion','1.1');
                                                                        $xml->writeAttribute('displayName','comment');
                                                                        $xml->endElement();
                                                                        $xml->startElement("value");
                                                                        $xml->writeAttribute('xsi:type','ED');
                                                                        $xml->text($this->XMLvalue($caseId,103,$medHistoryPatient_details['set_number']));
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" D.7.1.r.5: Comments #".$medHistoryPatient_details['set_number']);
                                                                    $xml->endElement();//observation
                                                                $xml->endElement();//outboundRelationship2
                                                            }
                                                            if($this->XMLvalue($caseId,1005,$medHistoryPatient_details['set_number'])!=null){
                                                                $xml->startElement("outboundRelationship2");
                                                                $xml->writeAttribute('typeCode','EXPL');
                                                                    $xml->startElement("observation");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code','38');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                        $xml->writeAttribute('codeSystemVersion','1.1');
                                                                        $xml->writeAttribute('displayName','familyHistory');
                                                                        $xml->endElement();
                                                                        $xml->startElement("value");
                                                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,1005,$medHistoryPatient_details['set_number']));
                                                                        $xml->writeAttribute('xsi:type','BL');
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" D.7.1.r.6: Family History #".$medHistoryPatient_details['set_number']);
                                                                    $xml->endElement();//observation
                                                                $xml->endElement();//outboundRelationship2
                                                            }
                                                            if($this->XMLvalue($caseId,100,$medHistoryPatient_details['set_number'])!=null){
                                                                $xml->startElement("inboundRelationship");
                                                                $xml->writeAttribute('typeCode','REFR');
                                                                    $xml->startElement("observation");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code','13');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                        $xml->writeAttribute('codeSystemVersion','1.1');
                                                                        $xml->writeAttribute('displayName','continuing');
                                                                        $xml->endElement();
                                                                        $xml->startElement("value");
                                                                        $xml->writeAttribute('xsi:type','BL');
                                                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,100,$medHistoryPatient_details['set_number']));
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" D.7.1.r.3: Continuing #".$medHistoryPatient_details['set_number']);
                                                                    $xml->endElement();//observation
                                                                $xml->endElement();//inboundRelationship
                                                            }
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//component
                                                        }
                                                    }
                                                    if($this->XMLvalue($caseId,104,1)!=null){
                                                        $xml->startElement("component");
                                                        $xml->writeAttribute('typeCode','COMP');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','18');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->writeAttribute('codeSystemVersion','1.1');
                                                                $xml->writeAttribute('displayName','historyAndConcurrentConditionText');
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','ED');
                                                                $xml->text($this->XMLvalue($caseId,104,1));
                                                                $xml->endElement();
                                                                $xml->writeComment(" D.7.2: Text for Relevant Medical History and Concurrent Conditions (not including reaction / event) ");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//component
                                                    }
                                                    if($this->XMLvalue($caseId,104,1)!=null){
                                                        $xml->startElement("component");
                                                        $xml->writeAttribute('typeCode','COMP');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','11');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->writeAttribute('codeSystemVersion','1.1');
                                                                $xml->writeAttribute('displayName','concomitantTherapy');
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','BL');
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,1006,1));
                                                                $xml->endElement();
                                                                $xml->writeComment(" D.7.3:  Concomitant Therapies ");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//component
                                                    }
                                                    $xml->endElement();//organizer
                                                $xml->endElement();//subjectOf2
                                                //D.8.r: Relevant Past Drug History (repeat as necessary)
                                            if($pastDrugPatient!=null){
                                                foreach($pastDrugPatient as $pastDrugPatient_details){
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("organizer");
                                                    $xml->writeAttribute('classCode','CATEGORY');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','2');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.20');
                                                        $xml->writeAttribute('codeSystemVersion','1.0');
                                                        $xml->writeAttribute('displayName','drugHistory');
                                                        $xml->endElement();
                                                        $xml->startElement("component");
                                                        $xml->writeAttribute('typeCode','COMP');
                                                            $xml->startElement("substanceAdministration");
                                                            $xml->writeAttribute('classCode','SBADM');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("effectiveTime");
                                                                $xml->writeAttribute('xsi:type','IVL_TS');
                                                                    $xml->startElement("low");
                                                                    $xml->writeAttribute('value',$this->XMLvalue($caseId,107,$pastDrugPatient_details['set_number']));
                                                                    $xml->endElement();
                                                                    $xml->writeComment(" D.8.r.4: Start Date #".$pastDrugPatient_details['set_number']);
                                                                    $xml->startElement("high");
                                                                    $xml->writeAttribute('value',$this->XMLvalue($caseId,109,$pastDrugPatient_details['set_number']));
                                                                    $xml->endElement();
                                                                    $xml->writeComment(" D.8.r.5: End Date #".$pastDrugPatient_details['set_number']);
                                                                $xml->endElement();//effectiveTime
                                                                $xml->startElement("consumable");
                                                                $xml->writeAttribute('type','CSM');
                                                                    $xml->startElement("instanceOfKind");
                                                                    $xml->writeAttribute('classCode','INST');
                                                                        $xml->startElement("kindOfProduct");
                                                                        $xml->writeAttribute('classCode','MMAT');
                                                                        $xml->writeAttribute('determinerCode','KIND');
                                                                        if($this->XMLvalue($caseId,1007,$pastDrugPatient_details['set_number'])!=null){
                                                                            $xml->startElement("code");
                                                                            $xml->writeAttribute('code',$this->XMLvalue($caseId,1008,$pastDrugPatient_details['set_number']));
                                                                            $xml->writeAttribute('codeSystem','TBD-MPID');
                                                                            $xml->writeAttribute('codeSystemVersion',$this->XMLvalue($caseId,1007,$pastDrugPatient_details['set_number']));
                                                                            $xml->endElement();
                                                                            $xml->writeComment("  D.8.r.2a: MPID Version Date / Number #".$pastDrugPatient_details['set_number']);
                                                                            $xml->writeComment("  D.8.r.2b: Medicinal Product Identifier (MPID) #".$pastDrugPatient_details['set_number']);
                                                                        }
                                                                            $xml->startElement("name");
                                                                            $xml->text($this->XMLvalue($caseId,105,$pastDrugPatient_details['set_number']));
                                                                            $xml->endElement();
                                                                            $xml->writeComment(" D.8.r.1: Name of Drug as Reported #".$pastDrugPatient_details['set_number']);
                                                                        $xml->endElement();//kindOfProduct
                                                                    $xml->endElement();//instanceOfKind
                                                                $xml->endElement();//consumable
                                                                $xml->startElement("outboundRelationship2");
                                                                $xml->writeAttribute('typeCode','RSON');
                                                                    $xml->startElement("observation");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code','19');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                        $xml->writeAttribute('codeSystemVersion','1.1');
                                                                        $xml->writeAttribute('displayName','indication');
                                                                        $xml->endElement();
                                                                        $xml->startElement("value");
                                                                        $xml->writeAttribute('xsi:type','CE');
                                                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,111,$pastDrugPatient_details['set_number']));
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.6.163');
                                                                        $xml->writeAttribute('codeSystemVersion',$this->XMLvalue($caseId,110,$pastDrugPatient_details['set_number']));
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" D.8.r.6a: MedDRA Version for Indication #".$pastDrugPatient_details['set_number']);
                                                                        $xml->writeComment(" D.8.r.6b: Indication (MedDRA code) #".$pastDrugPatient_details['set_number']);
                                                                    $xml->endElement();//observation
                                                                $xml->endElement();//outboundRelationship2
                                                                $xml->startElement("outboundRelationship2");
                                                                $xml->writeAttribute('typeCode','CAUS');
                                                                    $xml->startElement("observation");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code','29');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                        $xml->writeAttribute('codeSystemVersion','1.1');
                                                                        $xml->writeAttribute('displayName','reaction');
                                                                        $xml->endElement();
                                                                        $xml->startElement("value");
                                                                        $xml->writeAttribute('xsi:type','CE');
                                                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,113,$pastDrugPatient_details['set_number']));
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.6.163');
                                                                        $xml->writeAttribute('codeSystemVersion',$this->XMLvalue($caseId,112,$pastDrugPatient_details['set_number']));
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" D.8.r.7a: MedDRA Version for Reaction #".$pastDrugPatient_details['set_number']);
                                                                        $xml->writeComment(" D.8.r.7b: Reaction (MedDRA code) #".$pastDrugPatient_details['set_number']);
                                                                    $xml->endElement();//observation
                                                                $xml->endElement();//outboundRelationship2
                                                            $xml->endElement();//substanceAdministration
                                                        $xml->endElement();//component
                                                    $xml->endElement();//organizer
                                                $xml->endElement();//subjectOf2
                                                }
                                            }
                                            //Reported cause of Death
                                            if($death!=null){
                                                foreach($death as $death_details){
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("observation");
                                                    $xml->writeAttribute('classCode','OBS');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','32');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                        $xml->writeAttribute('codeSystemVersion','1.1');
                                                        $xml->writeAttribute('displayName','reportedCauseOfDeath');
                                                        $xml->endElement();
                                                        $xml->startElement("value");
                                                        $xml->writeAttribute('xsi:type','CE');
                                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,117,$death_details['set_number']));
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.6.163');
                                                        $xml->writeAttribute('codeSystemVersion',$this->XMLvalue($caseId,116,$death_details['set_number']));
                                                        $xml->endElement();
                                                        $xml->writeComment(" D.9.2.r.1a: MedDRA Version for Reported Cause(s) of Death #".$death_details['set_number']);
                                                        $xml->writeComment(" D.9.2.r.1b: Reported Cause(s) of Death (MedDRA code) #".$death_details['set_number']);
                                                    if($this->XMLvalue($caseId,1011,$death_details['set_number'])!=null){
                                                        $xml->startElement("originalText");
                                                        $xml->text($this->XMLvalue($caseId,1011,$death_details['set_number']));
                                                        $xml->writeComment(" D.9.2.r.2: Reported Cause(s) of Death (free text) #".$death_details['set_number']);
                                                        $xml->endElement();
                                                    }
                                                    $xml->endElement();//observation
                                                $xml->endElement();//subjectOf2
                                                }
                                            }
                                            if($this->XMLvalue($caseId,118,1)!=null){
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("observation");
                                                    $xml->writeAttribute('classCode','OBS');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','5');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                        $xml->writeAttribute('codeSystemVersion','1.1');
                                                        $xml->writeAttribute('displayName','autopsy');
                                                        $xml->endElement();
                                                        $xml->startElement("value");
                                                        $xml->writeAttribute('xsi:type','BL');
                                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,118,1));
                                                        $xml->endElement();
                                                        $xml->writeComment(" D.9.3  Was Autopsy Done?");
                                                    if($this->XMLvalue($caseId,118,1)!=='true'){
                                                        foreach($autopsy as $autopsy_detail){         
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','DRIV');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','8');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->writeAttribute('codeSystemVersion','1.1');
                                                                $xml->writeAttribute('displayName','causeOfDeath');
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','CE');
                                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,119,$autopsy_detail['set_number']));
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.6.163');
                                                                $xml->writeAttribute('codeSystemVersion',$this->XMLvalue($caseId,120,$this->XMLvalue($caseId,119,$autopsy_detail['set_number'])));
                                                                    $xml->writeComment(" D.9.4.r.1a: MedDRA Version for Autopsy-determined Cause(s) of Death #".$this->XMLvalue($caseId,119,$autopsy_detail['set_number']));
                                                                    $xml->writeComment(" D.9.4.r.1b Autopsy-determined Cause(s) of Death (MedDRA code) #".$this->XMLvalue($caseId,119,$autopsy_detail['set_number']));
                                                                    $xml->startElement("originalText");
                                                                    $xml->text($this->XMLvalue($caseId,1012,$this->XMLvalue($caseId,119,$autopsy_detail['set_number'])));
                                                                    $xml->endElement();
                                                                    $xml->writeComment(" D.9.4.r.2: Autopsy-determined Cause(s) of Death (free text) #".$this->XMLvalue($caseId,119,$autopsy_detail['set_number']));
                                                                $xml->endElement();//value        
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                        }
                                                    }
                                                    $xml->endElement();//observation
                                                $xml->endElement();//subjectOf2
                                            }
                                        if($event!= null){
                                            foreach($event as $event_detail){                 
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("observation");
                                                    $xml->writeAttribute('classCode','OBS');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("id");
                                                        $xml->writeAttribute('root','154eb889-958b-45f2-a02f-42d4d6f4657f');
                                                        $xml->endElement();
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','29');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                        $xml->writeAttribute('codeSystemVersion','1.1');
                                                        $xml->writeAttribute('displayName','reaction');
                                                        $xml->endElement();
                                                        $xml->startElement("effectiveTime");
                                                        $xml->writeAttribute('xsi:type','SXPR_TS');
                                                        if($this->XMLvalue($caseId,156,$event_detail['set_number'])!= null){
                                                            $xml->startElement("low");
                                                            $xml->writeAttribute('value',$this->XMLvalue($caseId,156,$event_detail['set_number']));
                                                            $xml->endElement();
                                                            $xml->writeComment(" E.i.4: Date of Start of Reaction / Event #".$event_detail['set_number']); 
                                                        }
                                                        if($this->XMLvalue($caseId,158,$event_detail['set_number'])!=null){
                                                            $xml->startElement("high");
                                                            $xml->writeAttribute('value',$this->XMLvalue($caseId,158,$event_detail['set_number']));
                                                            $xml->endElement();
                                                            $xml->writeComment(" E.i.5: Date of End of Reaction / Event #".$event_detail['set_number']); 
                                                        }
                                                        if($this->XMLvalue($caseId,159,$event_detail['set_number'])!=null){
                                                            $xml->startElement("width");
                                                            $xml->writeAttribute('value',$this->XMLvalue($caseId,159,$event_detail['set_number']));
                                                            $xml->writeAttribute('unit',$this->XMLvalue($caseId,160,$event_detail['set_number']));
                                                            $xml->endElement();
                                                            $xml->writeComment(" E.i.6a: Duration of Reaction / Event (number) #".$event_detail['set_number']); 
                                                            $xml->writeComment(" E.i.6b: Duration of Reaction / Event (unit)  #".$event_detail['set_number']); 
                                                        }
                                                        $xml->endElement();//effectiveTime
                                                    if($this->XMLvalue($caseId,392,$event_detail['set_number'])!=null){
                                                        $xml->startElement("value");
                                                        $xml->writeAttribute('xsi:type','CE');
                                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,392,$event_detail['set_number']));
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.6.163');
                                                        $xml->writeAttribute('codeSystemVersion',$this->XMLvalue($caseId,150,$event_detail['set_number']));
                                                        $xml->writeComment(" E.i.2.1a: MedDRA Version for Reaction / Event".$event_detail['set_number']);
                                                        $xml->writeComment(" E.i.2.1b: Reaction / Event (MedDRA code)".$event_detail['set_number']);
                                                        if($this->XMLvalue($caseId,1017,$event_detail['set_number'])!=null){
                                                            $xml->startElement("originalText");
                                                            $xml->writeAttribute('language',$this->XMLvalue($caseId,1017,$event_detail['set_number']));
                                                            $xml->text($event_detail['field_value']);
                                                            $xml->endElement();
                                                            $xml->writeComment(" E.i.1.1a: Reaction / Event as Reported by the Primary Source in Native Language #".$event_detail['set_number']);
                                                            $xml->writeComment(" E.i.1.1b: Reaction / Event as Reported by the Primary Source Language #".$event_detail['set_number']);
                                                        }
                                                        $xml->endElement();//value
                                                    }
                                                    if($this->XMLvalue($caseId,1026,$event_detail['set_number'])!=null){
                                                        $xml->startElement("location");
                                                        $xml->writeAttribute('typeCode','LOC');
                                                            $xml->startElement("locatedEntity");
                                                            $xml->writeAttribute('classCode','LOCE');
                                                                $xml->startElement("locatedPlace");
                                                                $xml->writeAttribute('classCode','COUNTRY');
                                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                                    $xml->startElement("code");
                                                                    $xml->writeAttribute('code',$this->XMLvalue($caseId,1026,$event_detail['set_number']));
                                                                    $xml->writeAttribute('codeSystem','1.0.3166.1.2.2');
                                                                    $xml->endElement();
                                                                    $xml->writeComment(" E.i.9: Identification of the Country Where the Reaction / Event Occurred #".$event_detail['set_number']);
                                                                $xml->endElement();
                                                            $xml->endElement();//locatedEntity
                                                        $xml->endElement();//location
                                                    }
                                                    if($this->XMLvalue($caseId,1018,$event_detail['set_number'])!=null){
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','PERT');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','30');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->writeAttribute('codeSystemVersion','1.1');
                                                                $xml->writeAttribute('displayName','reactionForTranslation');
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','ED');
                                                                $xml->text($this->XMLvalue($caseId,1018,$event_detail['set_number']));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.1.2: Reaction / Event as Reported by the Primary Source for Translation #".$event_detail['set_number']);
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                    }
                                                    if($this->XMLvalue($caseId,154,$event_detail['set_number'])!=null){
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','PERT');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','37');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->writeAttribute('codeSystemVersion','1.1');
                                                                $xml->writeAttribute('displayName','termHighlightedByReporter');
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','CE');
                                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,154,$event_detail['set_number']));
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.10');
                                                                $xml->writeAttribute('codeSystemVersion','1.0');
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.3.1: Term Highlighted by the Reporter #".$event_detail['set_number']);
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                    }
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','PERT');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','34');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->writeAttribute('codeSystemVersion','1.1');
                                                                $xml->writeAttribute('displayName','resultsInDeath');
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','BL');
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,1019,$event_detail['set_number']));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.3.2a: Results in Death #".$event_detail['set_number']);
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','PERT');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','21');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->writeAttribute('codeSystemVersion','1.1');
                                                                $xml->writeAttribute('displayName','isLifeThreatening');
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','BL');
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,1020,$event_detail['set_number']));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.3.2b: Life Threatening #".$event_detail['set_number']);
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','PERT');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','33');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->writeAttribute('codeSystemVersion','1.1');
                                                                $xml->writeAttribute('displayName','requiresInpatientHospitalization');
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','BL');
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,1021,$event_detail['set_number']));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.3.2c: Caused / Prolonged Hospitalisation #".$event_detail['set_number']);
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','PERT');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','35');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->writeAttribute('codeSystemVersion','1.1');
                                                                $xml->writeAttribute('displayName','resultsInPersistentOrSignificantDisability');
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','BL');
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,1022,$event_detail['set_number']));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.3.2d: Disabling / Incapacitating #".$event_detail['set_number']);
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','PERT');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','12');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->writeAttribute('codeSystemVersion','1.1');
                                                                $xml->writeAttribute('displayName','congenitalAnomalyBirthDefect');
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','BL');
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,1023,$event_detail['set_number']));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.3.2e: Congenital Anomaly / Birth Defect #".$event_detail['set_number']);
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','PERT');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','26');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->writeAttribute('codeSystemVersion','1.1');
                                                                $xml->writeAttribute('displayName','otherMedicallyImportantCondition');
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','BL');
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,1024,$event_detail['set_number']));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.3.2f: Other Medically Important Condition #".$event_detail['set_number']);
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                    if($this->XMLvalue($caseId,165,$event_detail['set_number'])!=null){
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','PERT');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','27');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->writeAttribute('codeSystemVersion','1.1');
                                                                $xml->writeAttribute('displayName','outcome');
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','CE');
                                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,165,$event_detail['set_number']));
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.11');
                                                                $xml->writeAttribute('codeSystemVersion','1.0');
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.7: Outcome of Reaction / Event at the Time of Last Observation #".$event_detail['set_number']);
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                    }
                                                    if($this->XMLvalue($caseId,1025,$event_detail['set_number'])!=null){
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','PERT');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','24');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->writeAttribute('codeSystemVersion','1.1');
                                                                $xml->writeAttribute('displayName','medicalConfirmationByHealthProfessional');
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','BL');
                                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,1025,$event_detail['set_number']));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.8: Medical Confirmation by Healthcare Professional #".$event_detail['set_number']);
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                    }
                                                    $xml->endElement();//observation
                                                $xml->endElement();//subjectOf2
                                            }
                                        }
                                            if($test!= null ){
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("organizer");
                                                    $xml->writeAttribute('classCode','CATEGORY');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','3');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.20');
                                                        $xml->writeAttribute('codeSystemVersion','1.0');
                                                        $xml->writeAttribute('displayName','testsAndProceduresRelevantToTheInvestigation');
                                                        $xml->endElement();
                                                    foreach($test as $test_details){
                                                        $xml->startElement("component");
                                                        $xml->writeAttribute('typeCode','COMP');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,1028,$test_details['set_number']));
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.6.163');
                                                                $xml->writeAttribute('codeSystemVersion',$this->XMLvalue($caseId,1027,$test_details['set_number']));
                                                                $xml->writeComment(" F.r.2.2a: MedDRA Version for Test Name #".$test_details['set_number']);
                                                                $xml->writeComment(" F.r.2.2b: Test Name (MedDRA code) #".$test_details['set_number']);
                                                                if($this->XMLvalue($caseId,168,$test_details['set_number'])!=null){
                                                                    $xml->writeElement('originalText',$this->XMLvalue($caseId,168,$test_details['set_number']));
                                                                    $xml->writeComment(" F.r.2.1: Test Name (free text) #".$test_details['set_number']);
                                                                }
                                                                $xml->endElement();//code
                                                            if($this->XMLvalue($caseId,167,$test_details['set_number'])!=null){
                                                                $xml->startElement("effectiveTime");
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,167,$test_details['set_number']));
                                                                $xml->endElement();//effectiveTime
                                                                $xml->writeComment(" F.r.1: Test Date #".$test_details['set_number']);
                                                            }
                                                            if($this->XMLvalue($caseId,169,$test_details['set_number'])!=null){
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','ED');
                                                                $xml->text($this->XMLvalue($caseId,169,$test_details['set_number']));
                                                                $xml->endElement();//value
                                                                $xml->writeComment(" F.r.3.4: Result Unstructured Data (free text) #".$test_details['set_number']);
                                                            }
                                                            if($this->XMLvalue($caseId,1030,$test_details['set_number'])!=null){
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','IVL_PQ');
                                                                    $xml->startElement("center");
                                                                    $xml->writeAttribute('value',$this->XMLvalue($caseId,1030,$test_details['set_number']));
                                                                    $xml->writeAttribute('unit',$this->XMLvalue($caseId,170,$test_details['set_number']));
                                                                    $xml->writeComment(" F.r.3.2: Test Result (value / qualifier) #".$test_details['set_number']);
                                                                    $xml->writeComment(" F.r.3.3: Test Result (unit) #".$test_details['set_number']);
                                                                    $xml->endElement();
                                                                $xml->endElement();
                                                            }
                                                            if($this->XMLvalue($caseId,1029,$test_details['set_number'])!=null){
                                                                $xml->startElement("interpretationCode");
                                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,1029,$test_details['set_number']));
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.12');
                                                                $xml->writeAttribute('codeSystemVersion','1.0');
                                                                $xml->endElement();//interpretationCode
                                                                $xml->writeComment(" F.r.3.1: Test Result (code) #".$test_details['set_number']);
                                                            }
                                                            if($this->XMLvalue($caseId,171,$test_details['set_number'])!=null){
                                                                $xml->startElement("referenceRange");
                                                                $xml->writeAttribute('typeCode','REFV');
                                                                    $xml->startElement("observationRange");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN.CRT');
                                                                        $xml->startElement("value");
                                                                        $xml->writeAttribute('xsi:type','PQ');
                                                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,171,$test_details['set_number']));
                                                                        $xml->writeAttribute('unit','mg/dl');
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" F.r.4: Normal Low Value #".$test_details['set_number']);
                                                                        $xml->startElement("interpretationCode");
                                                                        $xml->writeAttribute('code','L');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.5.83');
                                                                        $xml->endElement();
                                                                    $xml->endElement();//observationRange
                                                                $xml->endElement();//referenceRange
                                                            }
                                                            if($this->XMLvalue($caseId,172,$test_details['set_number'])!=null){
                                                                $xml->startElement("referenceRange");
                                                                $xml->writeAttribute('typeCode','REFV');
                                                                    $xml->startElement("observationRange");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN.CRT');
                                                                        $xml->startElement("value");
                                                                        $xml->writeAttribute('xsi:type','PQ');
                                                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,172,$test_details['set_number']));
                                                                        $xml->writeAttribute('unit','mg/dl');
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" F.r.5:  Normal High Value #".$test_details['set_number']);
                                                                        $xml->startElement("interpretationCode");
                                                                        $xml->writeAttribute('code','H');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.5.83');
                                                                        $xml->endElement();
                                                                    $xml->endElement();//observationRange
                                                                $xml->endElement();//referenceRange
                                                            }
                                                            if($this->XMLvalue($caseId,1031,$test_details['set_number'])!=null){
                                                                $xml->startElement("outboundRelationship2");
                                                                $xml->writeAttribute('typeCode','PERT');
                                                                    $xml->startElement("observation");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code','10');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                        $xml->writeAttribute('codeSystemVersion','1.1');
                                                                        $xml->writeAttribute('displayName','comment');
                                                                        $xml->endElement();
                                                                        $xml->startElement("value");
                                                                        $xml->writeAttribute('xsi:type','ED');
                                                                        $xml->text($this->XMLvalue($caseId,1031,$test_details['set_number']));
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" F.r.6: Comments (free text) #".$test_details['set_number']);
                                                                    $xml->endElement();//observation
                                                                $xml->endElement();//outboundRelationship2
                                                            }
                                                            if($this->XMLvalue($caseId,173,$test_details['set_number'])!=null){
                                                                $xml->startElement("outboundRelationship2");
                                                                $xml->writeAttribute('typeCode','REFR');
                                                                    $xml->startElement("observation");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code','25');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                        $xml->writeAttribute('codeSystemVersion','1.1');
                                                                        $xml->writeAttribute('displayName','moreInformationAvailable');
                                                                        $xml->endElement();
                                                                        $xml->startElement("value");
                                                                        $xml->writeAttribute('xsi:type','BL');
                                                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,173,$test_details['set_number']));
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" F.r.7: More Information Available #".$test_details['set_number']);
                                                                    $xml->endElement();//observation
                                                                $xml->endElement();//outboundRelationship2
                                                            }
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//component
                                                    }
                                                    $xml->endElement();//organizer
                                                $xml->endElement();//subjectOf2
                                            }
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("organizer");
                                                    $xml->writeAttribute('classCode','CATEGORY');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','4');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.20');
                                                        $xml->writeAttribute('codeSystemVersion','1.0');
                                                        $xml->writeAttribute('displayName','drugInformation');
                                                        $xml->endElement();
                                                if($product!=null){
                                                    foreach($product as $product_details){
                                                        $xml->writeComment(" G.k Drug(s) Information (repeat as necessary) #".$product_details['set_number']);
                                                        $xml->startElement("component");
                                                        $xml->writeAttribute('typeCode','COMP');
                                                            $xml->startElement("substanceAdministration");
                                                            $xml->writeAttribute('classCode','SBADM');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("id");
                                                                $xml->writeAttribute('root','3c91b4d5-e039-4a7a-9c30-67671b0ef9e4');//how to determine root???
                                                                $xml->endElement();
                                                                $xml->startElement("consumable");
                                                                $xml->writeAttribute('typeCode','CSM');
                                                                    $xml->startElement("instanceOfKind");
                                                                    $xml->writeAttribute('classCode','INST');
                                                                        $xml->startElement("kindOfProduct");
                                                                        $xml->writeAttribute('classCode','MMAT');
                                                                        $xml->writeAttribute('determinerCode','KIND');
                                                                        if($this->XMLvalue($caseId,1033,$product_details['set_number'])!=null){
                                                                            $xml->startElement("code");
                                                                            $xml->writeAttribute('code',$this->XMLvalue($caseId,1033,$product_details['set_number']));
                                                                            $xml->writeAttribute('codeSystem','TBD-MPID');
                                                                            $xml->writeAttribute('codeSystemVersion',$this->XMLvalue($caseId,1032,$product_details['set_number']));
                                                                            $xml->endElement();
                                                                            $xml->writeComment(" Example MPID and Version ");
                                                                            $xml->writeComment(" G.k.2.1.1a: MPID Version Date / Number #".$product_details['set_number']);
                                                                            $xml->writeComment(" G.k.2.1.1b: Medicinal Product Identifier (MPID) #".$product_details['set_number']);
                                                                        }
                                                                            $xml->writeElement('name',$this->XMLvalue($caseId,176,$product_details['set_number']));
                                                                            $xml->writeComment(" G.k.2.2: Medicinal Product Name as Reported by the Primary Source #".$product_details['set_number']);
                                                                        if($this->XMLvalue($caseId,180,$product_details['set_number'])!=null){
                                                                            $xml->startElement("asManufacturedProduct");
                                                                            $xml->writeAttribute('classCode','MANU');
                                                                                $xml->startElement("subjectOf");
                                                                                $xml->writeAttribute('typeCode','SBJ');
                                                                                    $xml->startElement("approval");
                                                                                    $xml->writeAttribute('classCode','CNTRCT');
                                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                                        $xml->startElement("id");
                                                                                        $xml->writeAttribute('extension',$this->XMLvalue($caseId,180,$product_details['set_number']));
                                                                                        $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.4');
                                                                                        $xml->endElement();
                                                                                        $xml->writeComment(" G.k.3.1: Authorisation / Application Number #".$product_details['set_number']);
                                                                                    if($this->XMLvalue($caseId,182,$product_details['set_number'])!=null){
                                                                                        $xml->startElement("holder");
                                                                                        $xml->writeAttribute('typeCode','HLD');
                                                                                            $xml->startElement("role");
                                                                                            $xml->writeAttribute('classCode','HLD');
                                                                                                $xml->startElement("playingOrganization");
                                                                                                $xml->writeAttribute('classCode','ORG');
                                                                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                                                                    $xml->writeElement('name',$this->XMLvalue($caseId,182,$product_details['set_number']));
                                                                                                    $xml->writeComment(" G.k.3.3: Name of Holder / Applicant #".$product_details['set_number']);
                                                                                                $xml->endElement();//playingOrganization
                                                                                            $xml->endElement();//role
                                                                                        $xml->endElement();//holder
                                                                                    }
                                                                                    if($this->XMLvalue($caseId,180,$product_details['set_number'])!=null){
                                                                                        $xml->startElement("author");
                                                                                        $xml->writeAttribute('typeCode','AUT');
                                                                                            $xml->startElement("territorialAuthority");
                                                                                            $xml->writeAttribute('classCode','TERR');
                                                                                                $xml->startElement("territory");
                                                                                                $xml->writeAttribute('classCode','NAT');
                                                                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                                                                    $xml->startElement("code");
                                                                                                    $xml->writeAttribute('code',$this->XMLvalue($caseId,181,$product_details['set_number']));
                                                                                                    $xml->writeAttribute('codeSystem','1.0.3166.1.2.2');
                                                                                                    $xml->endElement();
                                                                                                    $xml->writeComment("  G.k.3.2: Country of Authorisation / Application #".$product_details['set_number']);
                                                                                                $xml->endElement();//territory
                                                                                            $xml->endElement();//territorialAuthority
                                                                                        $xml->endElement();//author
                                                                                    }
                                                                                    $xml->endElement();//approval
                                                                                $xml->endElement();//subjectOf
                                                                            $xml->endElement();//asManufacturedProduct
                                                                        }
                                                                    if($this->getSecondLevel($caseId,1038,$product_details['set_number'])!=null){
                                                                        $strength=$this->getSecondLevel($caseId,1038,$product_details['set_number']);
                                                                        $i=1;
                                                                        foreach($strength as $strength_details){
                                                                            $xml->startElement("ingredient");
                                                                            $xml->writeAttribute('classCode','ACTI');
                                                                                $xml->startElement("quantity");
                                                                                    $xml->startElement("numerator");
                                                                                    $xml->writeAttribute('value',$this->doubleSetValue($caseId,1038,$product_details['set_number'],$i));
                                                                                    $xml->writeAttribute('unit',$this->doubleSetValue($caseId,1039,$product_details['set_number'],$i));
                                                                                    $xml->endElement();//numerator
                                                                                    $xml->writeComment(" G.k.2.3.r.3a: Strength (number) #".$product_details['set_number'].'-'.$i);
                                                                                    $xml->writeComment(" G.k.2.3.r.3b: Strength (unit) #".$product_details['set_number'].'-'.$i);
                                                                                    $xml->startElement("denominator");
                                                                                    $xml->writeAttribute('value','1');
                                                                                    $xml->endElement();
                                                                                $xml->endElement();//quantity
                                                                                $xml->startElement("ingredientSubstance");
                                                                                $xml->writeAttribute('classCode','MMAT');
                                                                                $xml->writeAttribute('determinerCode','KIND');
                                                                                    $xml->startElement("code");
                                                                                    $xml->writeAttribute('code',$this->doubleSetValue($caseId,1037,$product_details['set_number'],$i));
                                                                                    $xml->writeAttribute('codeSystem','TBD-Substance');
                                                                                    $xml->writeAttribute('codeSystemVersion',$this->doubleSetValue($caseId,1036,$product_details['set_number'],$i));
                                                                                    $xml->endElement();
                                                                                    $xml->writeComment(" Example Substance ID and Version ");
                                                                                    $xml->writeComment(" G.k.2.3.r.2a Substance / Specified Substance TermID Version Date/Number #".$product_details['set_number'].'-'.$i);
                                                                                    $xml->writeComment(" G.k.2.3.r.2b: Substance / Specified Substance TermID #".$product_details['set_number'].'-'.$i);
                                                                                    $xml->writeElement('name',$this->doubleSetValue($caseId,177,$product_details['set_number'],$i));
                                                                                    $xml->writeComment(" G.k.2.3.r.1: Substance / Specified Substance Name #".$product_details['set_number'].'-'.$i);
                                                                                $xml->endElement();//ingredientSubstance
                                                                            $xml->endElement();//ingredient
                                                                            $i=$i+1;
                                                                        }
                                                                    }
                                                                        $xml->endElement();//kindOfProduct
                                                                    if($this->XMLvalue($caseId,178,$product_details['set_number'])){
                                                                        $xml->startElement("subjectOf");
                                                                        $xml->writeAttribute('typeCode','SBJ');
                                                                            $xml->startElement("productEvent");
                                                                            $xml->writeAttribute('classCode','ACT');
                                                                            $xml->writeAttribute('moodCode','EVN');
                                                                                $xml->startElement("code");
                                                                                $xml->writeAttribute('code','1');
                                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.18');
                                                                                $xml->writeAttribute('codeSystemVersion','1.0');
                                                                                $xml->writeAttribute('displayName','retailSupply');
                                                                                $xml->endElement();
                                                                                $xml->startElement("performer");
                                                                                $xml->writeAttribute('typeCode','PRF');
                                                                                    $xml->startElement("assignedEntity");
                                                                                    $xml->writeAttribute('classCode','ASSIGNED');
                                                                                        $xml->startElement("representedOrganization");
                                                                                        $xml->writeAttribute('classCode','ORG');
                                                                                        $xml->writeAttribute('determinerCode','INSTANCE');
                                                                                            $xml->startElement("addr");
                                                                                                $xml->writeElement("country",$this->XMLvalue($caseId,178,$product_details['set_number']));
                                                                                                $xml->writeComment(" G.k.2.4: Identification of the Country Where the Drug Was Obtained #".$product_details['set_number']);
                                                                                            $xml->endElement();
                                                                                        $xml->endElement();
                                                                                    $xml->endElement();
                                                                                $xml->endElement();
                                                                            $xml->endElement();
                                                                        $xml->endElement();//subjectOf
                                                                    }
                                                                    $xml->endElement();//instanceOfKind
                                                                $xml->endElement();//consumable
                                                            if($this->doubleSetValue($caseId,200,$product_details['set_number'],1)!=null){
                                                                $xml->startElement("outboundRelationship1");
                                                                $xml->writeAttribute('typeCode','SAS');
                                                                    $xml->startElement("pauseQuantity");
                                                                    $xml->writeAttribute('value',$this->doubleSetValue($caseId,200,$product_details['set_number'],1));
                                                                    $xml->writeAttribute('unit',$this->doubleSetValue($caseId,201,$product_details['set_number'],1));
                                                                    $xml->endElement();//pauseQuantity
                                                                    $xml->writeComment(" G.k.9.i.3.1a: Time Interval between Beginning of Drug Administration and Start of Reaction / Event (number) Drug #".$product_details['set_number'].",Reaction #1");
                                                                    $xml->writeComment(" G.k.9.i.3.1b: Time Interval between Beginning of Drug Administration and Start of Reaction / Event (unit)  Drug #".$product_details['set_number'].", Reaction #1");
                                                                    $xml->startElement("actReference");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("id");
                                                                        $xml->writeAttribute('root','154eb889-958b-45f2-a02f-42d4d6f4657f');
                                                                        $xml->endElement();
                                                                    $xml->endElement();//actReference
                                                                $xml->endElement();//outboundRelationship1
                                                            }
                                                            if($this->doubleSetValue($caseId,202,$product_details['set_number'],1)!=null){
                                                                $xml->startElement("outboundRelationship1");
                                                                $xml->writeAttribute('typeCode','SAE');
                                                                    $xml->startElement("pauseQuantity");
                                                                    $xml->writeAttribute('value',$this->doubleSetValue($caseId,202,$product_details['set_number'],1));
                                                                    $xml->writeAttribute('unit',$this->doubleSetValue($caseId,203,$product_details['set_number'],1));
                                                                    $xml->endElement();//pauseQuantity
                                                                    $xml->writeComment(" G.k.9.i.3.2a: Time Interval between Last Dose of Drug and Start of Reaction / Event (number)  Drug #".$product_details['set_number'].",Reaction #1");
                                                                    $xml->writeComment(" G.k.9.i.3.2b: Time Interval between Last Dose of Drug and Start of Reaction / Event (unit)  Drug #".$product_details['set_number'].",Reaction #1");
                                                                    $xml->startElement("actReference");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("id");
                                                                        $xml->writeAttribute('root','154eb889-958b-45f2-a02f-42d4d6f4657f');
                                                                        $xml->endElement();
                                                                    $xml->endElement();//actReference
                                                                $xml->endElement();//outboundRelationship1
                                                            }
                                                            if($this->XMLvalue($caseId,1040,$product_details['set_number'])!=null){
                                                                $xml->startElement("outboundRelationship2");
                                                                $xml->writeAttribute('typeCode','PERT');
                                                                    $xml->startElement("observation");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code','6');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                        $xml->writeAttribute('codeSystemVersion','1.1');
                                                                        $xml->writeAttribute('displayName','blinded');
                                                                        $xml->endElement();//code
                                                                        $xml->startElement("value");
                                                                        $xml->writeAttribute('xsi:type','BL');
                                                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,1040,$product_details['set_number']));
                                                                        $xml->endElement();//value
                                                                        $xml->writeComment(" G.k.2.5: Investigational Product Blinded #".$product_details['set_number']);
                                                                    $xml->endElement();//observation
                                                                $xml->endElement();//outboundRelationship2
                                                            }
                                                                $xml->startElement("outboundRelationship2");
                                                                $xml->writeAttribute('typeCode','COMP');
                                                                $xml->writeComment(" G.k.4.r: Dosage and Relevant Information (repeat as necessary) #".$product_details['set_number']."-1");
                                                                    $xml->startElement("substanceAdministration");
                                                                    $xml->writeAttribute('classCode','SBADM');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                    if($this->doubleSetValue($caseId,190,$product_details['set_number'],1)!=null){
                                                                        $xml->writeElement("text",$this->doubleSetValue($caseId,190,$product_details['set_number'],1));
                                                                        $xml->writeComment(" G.k.4.r.8: Dosage Text #".$product_details['set_number']."-1");
                                                                    }
                                                                        $xml->startElement("effectiveTime");
                                                                        $xml->writeAttribute('xsi:type','SXPR_TS');
                                                                        if($this->doubleSetValue($caseId,186,$product_details['set_number'],1)!=null){
                                                                            $xml->startElement("comp");
                                                                            $xml->writeAttribute('xsi:type','PIVL_TS');
                                                                                $xml->startElement("period");
                                                                                $xml->writeAttribute('value',$this->doubleSetValue($caseId,186,$product_details['set_number'],1));
                                                                                $xml->writeAttribute('unit',$this->doubleSetValue($caseId,187,$product_details['set_number'],1));
                                                                                $xml->endElement();
                                                                                $xml->writeComment(" G.k.4.r.2: Number of Units in the Interval #".$product_details['set_number']."-1");
                                                                                $xml->writeComment(" G.k.4.r.3: Definition of the Time Interval Unit #".$product_details['set_number']."-1");
                                                                            $xml->endElement();//comp
                                                                        }
                                                                        if($this->doubleSetValue($caseId,199,$product_details['set_number'],1)!=null){
                                                                            $xml->startElement("comp");
                                                                            $xml->writeAttribute('xsi:type','IVL_TS');
                                                                            $xml->writeAttribute('operator','A');
                                                                                $xml->startElement("low");
                                                                                $xml->writeAttribute('value',$this->doubleSetValue($caseId,199,$product_details['set_number'],1));
                                                                                $xml->endElement();
                                                                                $xml->writeComment(" G.k.4.r.4: Date and Time of Start of Drug #".$product_details['set_number']."-1");
                                                                                $xml->startElement("high");
                                                                                $xml->writeAttribute('value',$this->doubleSetValue($caseId,205,$product_details['set_number'],1));
                                                                                $xml->endElement();
                                                                                $xml->writeComment(" G.k.4.r.5: Date and Time of Last Administration #".$product_details['set_number']."-1");
                                                                            $xml->endElement();//comp
                                                                        }
                                                                        if($this->doubleSetValue($caseId,206,$product_details['set_number'],1)!=null){
                                                                            $xml->startElement("comp");
                                                                            $xml->writeAttribute('xsi:type','IVL_TS');
                                                                            $xml->writeAttribute('operator','A');
                                                                                $xml->startElement("width");
                                                                                $xml->writeAttribute('value',$this->doubleSetValue($caseId,206,$product_details['set_number'],1));
                                                                                $xml->writeAttribute('unit',$this->doubleSetValue($caseId,207,$product_details['set_number'],1));
                                                                                $xml->endElement();
                                                                                $xml->writeComment(" G.k.4.r.6a: Duration of Drug Administration (number) #".$product_details['set_number']."-1");
                                                                                $xml->writeComment(" G.k.4.r.6b: Duration of Drug Administration (unit) #".$product_details['set_number']."-1");
                                                                            $xml->endElement();//comp
                                                                        }
                                                                        $xml->endElement();//effectiveTime
                                                                    if($this->doubleSetValue($caseId,1122,$product_details['set_number'],1)!=null){
                                                                        $xml->startElement("routeCode");
                                                                        $xml->writeAttribute('code',$this->doubleSetValue($caseId,1044,$product_details['set_number'],1));
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.14');
                                                                        $xml->writeAttribute('codeSystemVersion',$this->doubleSetValue($caseId,1043,$product_details['set_number'],1));
                                                                        $xml->writeComment(" G.k.4.r.10.2a: Route of Administration TermID Version Date / Number #1".$product_details['set_number']."-1");
                                                                        $xml->writeComment(" G.k.4.r.10.2b: Route of Administration TermID #".$product_details['set_number']."-1");
                                                                            $xml->writeElement("originalText",$this->doubleSetValue($caseId,1122,$product_details['set_number'],1));
                                                                            $xml->writeComment(" G.k.4.r.10.1: Route of Administration (free text) #".$product_details['set_number']."-1");
                                                                        $xml->endElement();//routeCode
                                                                    }
                                                                    if($this->doubleSetValue($caseId,183,$product_details['set_number'],1)!=null){
                                                                        $xml->startElement("doesQuantity");
                                                                        $xml->writeAttribute('value',$this->doubleSetValue($caseId,183,$product_details['set_number'],1));
                                                                            $xml->writeAttribute('unit',$this->doubleSetValue($caseId,184,$product_details['set_number'],1));
                                                                        $xml->endElement();//doesQuantity
                                                                        $xml->writeComment(" G.k.4.r.1a Dose (number) #".$product_details['set_number']."-1");
                                                                        $xml->writeComment(" G.k.4.r.1b: Dose (unit) #".$product_details['set_number']."-1");
                                                                    }
                                                                    if($this->doubleSetValue($caseId,191,$product_details['set_number'],1)!=null){
                                                                        $xml->startElement("consumable");
                                                                        $xml->writeAttribute('type','CSM');
                                                                            $xml->startElement("instanceOfKind");
                                                                            $xml->writeAttribute('classCode','INST');
                                                                            if($this->doubleSetValue($caseId,179,$product_details['set_number'],1)!=null){
                                                                                $xml->startElement("productInstanceInstance");
                                                                                $xml->writeAttribute('classCode','MMAT');
                                                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                                                    $xml->writeElement("lotNumberText",$this->doubleSetValue($caseId,179,$product_details['set_number'],1));
                                                                                    $xml->writeComment("  G.k.4.r.7: Batch / Lot Number #".$product_details['set_number']."-1");
                                                                                $xml->endElement();//productInstanceInstance
                                                                            }
                                                                            if($this->doubleSetValue($caseId,191,$product_details['set_number'],1)!=null){
                                                                                $xml->startElement("kindOfProduct");
                                                                                $xml->writeAttribute('classCode','MMAT');
                                                                                $xml->writeAttribute('determinerCode','KIND');
                                                                                    $xml->startElement("formCode");
                                                                                        $xml->writeAttribute('code',$this->doubleSetValue($caseId,1042,$product_details['set_number'],1));
                                                                                        $xml->writeAttribute('codeSystem','TBD-DoseForm');
                                                                                        $xml->writeAttribute('codeSystemVersion',$this->doubleSetValue($caseId,1041,$product_details['set_number'],1));
                                                                                        $xml->writeComment(" G.k.4.r.9.2a: Pharmaceutical Dose Form TermID Version Date / Number #".$product_details['set_number']."-1");
                                                                                        $xml->writeComment(" G.k.4.r.9.2b: Pharmaceutical Dose Form TermID #".$product_details['set_number']."-1");
                                                                                        $xml->writeElement('originalText',$this->doubleSetValue($caseId,191,$product_details['set_number'],1));
                                                                                        $xml->writeComment(" G.k.4.r.9.1: Pharmaceutical Dose Form (free text) #".$product_details['set_number']."-1");
                                                                                    $xml->endElement();//formCode
                                                                                $xml->endElement();//kindOfProduct
                                                                            }
                                                                            $xml->endElement();//instanceOfKind 
                                                                        $xml->endElement();//consumable
                                                                    }
                                                                    if($this->doubleSetValue($caseId,1046,$product_details['set_number'],1)!=null){
                                                                        $xml->startElement("inboundRelationship");
                                                                        $xml->writeAttribute('typeCode','REFR');
                                                                            $xml->startElement("observation");
                                                                            $xml->writeAttribute('classCode','OBS');
                                                                            $xml->writeAttribute('moodCode','EVN');
                                                                                $xml->startElement("code");
                                                                                $xml->writeAttribute('code','28');
                                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                                $xml->writeAttribute('codeSystemVersion','1.1');
                                                                                $xml->writeAttribute('displayName','parentRouteOfAdministration');
                                                                                $xml->endElement();
                                                                                $xml->startElement("value");
                                                                                $xml->writeAttribute('xsi:type','CE');
                                                                                $xml->writeAttribute('code',$this->doubleSetValue($caseId,1046,$product_details['set_number'],1));
                                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.14');
                                                                                $xml->writeAttribute('codeSystemVersion',$this->doubleSetValue($caseId,1045,$product_details['set_number'],1));
                                                                                $xml->writeComment(" G.k.4.r.11.2a: Parent Route of Administration TermID Version Date / Number #".$product_details['set_number']."-1");
                                                                                $xml->writeComment(" G.k.4.r.11.2b: Parent Route of Administration TermID #".$product_details['set_number']."-1");
                                                                                    $xml->writeElement('originalText',$this->doubleSetValue($caseId,193,$product_details['set_number'],1));
                                                                                    $xml->writeComment(" G.k.4.r.11.1: Parent Route of Administration (free text) #".$product_details['set_number']."-1");
                                                                                $xml->endElement();//value
                                                                            $xml->endElement();//observation       
                                                                        $xml->endElement();//inboundRelationship
                                                                    }
                                                                    if($this->doubleSetValue($caseId,1164,$product_details['set_number'],1)!=null){
                                                                        $xml->startElement("inboundRelationship");
                                                                        $xml->writeAttribute('typeCode','REFR');
                                                                            $xml->startElement("observation");
                                                                            $xml->writeAttribute('classCode','OBS');
                                                                            $xml->writeAttribute('moodCode','EVN');
                                                                                $xml->startElement("code");
                                                                                $xml->writeAttribute('code','CN-8');
                                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                                $xml->writeAttribute('codeSystemVersion','1.1');
                                                                                $xml->writeAttribute('displayName','drugExpiryDate');
                                                                                $xml->endElement();
                                                                                $xml->startElement("value");
                                                                                $xml->writeAttribute('xsi:type','TS');
                                                                                $xml->writeAttribute('value',$this->doubleSetValue($caseId,1164,$product_details['set_number'],1));
                                                                                $xml->endElement();//value
                                                                                $xml->writeComment(" G.k.4.r.CN.1 药品失效日期 #".$product_details['set_number']."-1");
                                                                            $xml->endElement();//observation       
                                                                        $xml->endElement();//inboundRelationship
                                                                    }
                                                                    $xml->endElement();//substanceAdministration
                                                                $xml->endElement();//outboundRelationship2
                                                            if($this->doubleSetValue($caseId,188,$product_details['set_number'],1)!=null){
                                                                $xml->startElement("outboundRelationship2");
                                                                $xml->writeAttribute('typeCode','SUMM');
                                                                    $xml->startElement("observation");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code','14');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                        $xml->writeAttribute('codeSystemVersion','1.1');
                                                                        $xml->writeAttribute('displayName','cumulativeDoseToReaction');
                                                                        $xml->endElement();
                                                                        $xml->startElement("value");
                                                                        $xml->writeAttribute('xsi:type','PQ');
                                                                        $xml->writeAttribute('value',$this->doubleSetValue($caseId,188,$product_details['set_number'],1));
                                                                        $xml->writeAttribute('unit',$this->doubleSetValue($caseId,189,$product_details['set_number'],1));
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" G.k.5a: Cumulative Dose to First Reaction (number) #".$product_details['set_number']);
                                                                        $xml->writeComment(" G.k.5b: Cumulative Dose to First Reaction (unit) #".$product_details['set_number']);
                                                                    $xml->endElement();//observation
                                                                $xml->endElement();//outboundRelationship2
                                                            }
                                                            if($this->doubleSetValue($caseId,194,$product_details['set_number'],1)!=null){
                                                                $xml->startElement("outboundRelationship2");
                                                                $xml->writeAttribute('typeCode','PERT');
                                                                    $xml->startElement("observation");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code','16');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                        $xml->writeAttribute('codeSystemVersion','1.1');
                                                                        $xml->writeAttribute('displayName','gestationPeriod');
                                                                        $xml->endElement();
                                                                        $xml->startElement("value");
                                                                        $xml->writeAttribute('xsi:type','PQ');
                                                                        $xml->writeAttribute('value',$this->doubleSetValue($caseId,194,$product_details['set_number'],1));
                                                                        $xml->writeAttribute('unit',$this->doubleSetValue($caseId,195,$product_details['set_number'],1));
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" G.k.6a: Gestation Period at Time of Exposure (number) #".$product_details['set_number']);
                                                                        $xml->writeComment(" G.k.6b: Gestation Period at Time of Exposure (unit) #".$product_details['set_number']);
                                                                    $xml->endElement();//observation
                                                                $xml->endElement();//outboundRelationship2
                                                            }
                                                                $xml->writeComment(" china extention start ");
                                                            if($this->doubleSetValue($caseId,1165,$product_details['set_number'],1)!=null){
                                                                $xml->startElement("outboundRelationship2");
                                                                $xml->writeAttribute('typeCode','PERT');
                                                                    $xml->startElement("observation");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code','CN-4');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                        $xml->writeAttribute('codeSystemVersion','1.1');
                                                                        $xml->writeAttribute('displayName','unexpected');
                                                                        $xml->endElement();
                                                                        $xml->startElement("value");
                                                                        $xml->writeAttribute('xsi:type','CE');
                                                                        $xml->writeAttribute('code',$this->doubleSetValue($caseId,1165,$product_details['set_number'],1));
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" G.k.9.i.CN.1: Is it unexpected? Drug #".$product_details['set_number'].", Reaction #1");
                                                                        $xml->startElement("outboundRelationship1");
                                                                        $xml->writeAttribute('typeCode','REFR');
                                                                            $xml->startElement("actReference");
                                                                            $xml->writeAttribute('classCode','OBS');
                                                                            $xml->writeAttribute('moodCode','EVN');
                                                                                $xml->startElement("id");
                                                                                $xml->writeAttribute('root','154eb889-958b-45f2-a02f-42d4d6f4657f');
                                                                                $xml->endElement();//id
                                                                            $xml->endElement();//actReference 
                                                                        $xml->endElement();//outboundRelationship1 
                                                                    $xml->endElement();//observation
                                                                $xml->endElement();//outboundRelationship2
                                                            }
                                                            if($this->doubleSetValue($caseId,1166,$product_details['set_number'],1)!=null){
                                                                $xml->startElement("outboundRelationship2");
                                                                $xml->writeAttribute('typeCode','PERT');
                                                                    $xml->startElement("observation");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code','CN-5');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                        $xml->writeAttribute('codeSystemVersion','1.1');
                                                                        $xml->writeAttribute('displayName','recurranceOfReaction');
                                                                        $xml->endElement();
                                                                        $xml->startElement("value");
                                                                        $xml->writeAttribute('xsi:type','CE');
                                                                        $xml->writeAttribute('code',$this->doubleSetValue($caseId,1166,$product_details['set_number'],1));
                                                                        $xml->endElement();
                                                                        $xml->writeComment("G.k.9.i.CN.2: Does the reaction disappear or decrease after withdrawal or reduction of dosage？ Drug #".$product_details['set_number'].", Reaction #1");
                                                                        $xml->startElement("outboundRelationship1");
                                                                        $xml->writeAttribute('typeCode','REFR');
                                                                            $xml->startElement("actReference");
                                                                            $xml->writeAttribute('classCode','OBS');
                                                                            $xml->writeAttribute('moodCode','EVN');
                                                                                $xml->startElement("id");
                                                                                $xml->writeAttribute('root','154eb889-958b-45f2-a02f-42d4d6f4657f');
                                                                                $xml->endElement();//id
                                                                            $xml->endElement();//actReference 
                                                                        $xml->endElement();//outboundRelationship1 
                                                                    $xml->endElement();//observation
                                                                $xml->endElement();//outboundRelationship2
                                                            }
                                                                $xml->writeComment(" china extention end ");
                                                            if($this->doubleSetValue($caseId,209,$product_details['set_number'],1)!=null){
                                                                $xml->startElement("outboundRelationship2");
                                                                $xml->writeAttribute('typeCode','PERT');
                                                                    $xml->startElement("observation");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code','31');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                        $xml->writeAttribute('codeSystemVersion','1.1');
                                                                        $xml->writeAttribute('displayName','recurranceOfReaction');
                                                                        $xml->endElement();
                                                                        $xml->startElement("value");
                                                                        $xml->writeAttribute('xsi:type','CE');
                                                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,209,1));
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.16');
                                                                        $xml->writeAttribute('codeSystemVersion','1.0');
                                                                        $xml->endElement();//value
                                                                        $xml->writeComment(" G.k.9.i.4: Did Reaction Recur on Re-administration? Drug #".$product_details['set_number'].", Reaction #1");
                                                                        $xml->startElement("outboundRelationship1");
                                                                        $xml->writeAttribute('typeCode','REFR');
                                                                            $xml->startElement("actReference");
                                                                            $xml->writeAttribute('classCode','OBS');
                                                                            $xml->writeAttribute('moodCode','EVN');
                                                                                $xml->startElement("id");
                                                                                $xml->writeAttribute('root','154eb889-958b-45f2-a02f-42d4d6f4657f');
                                                                                $xml->endElement();//id
                                                                            $xml->endElement();//actReference
                                                                        $xml->endElement();//outboundRelationship1
                                                                    $xml->endElement();//observation
                                                                $xml->endElement();//outboundRelationship2
                                                            }  
                                                            if($this->doubleSetValue($caseId,1048,$product_details['set_number'],1)!=null){
                                                                $xml->startElement("outboundRelationship2");
                                                                $xml->writeAttribute('typeCode','REFR');
                                                                    $xml->startElement("observation");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code','9');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                        $xml->writeAttribute('codeSystemVersion','1.1');
                                                                        $xml->writeAttribute('displayName','codedDrugInformation');
                                                                        $xml->endElement();
                                                                        $xml->startElement("value");
                                                                        $xml->writeAttribute('xsi:type','CE');
                                                                        $xml->writeAttribute('code',$this->doubleSetValue($caseId,1048,$product_details['set_number'],1));
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.17');
                                                                        $xml->writeAttribute('codeSystemVersion','1.0');
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" G.k.10.r: Additional Information on Drug (coded)(repeat as necessary) #1-1");
                                                                    $xml->endElement();//observation
                                                                $xml->endElement();//outboundRelationship2
                                                            }
                                                            if($this->XMLvalue($caseId,209,$product_details['set_number'])!=null){
                                                                $xml->startElement("outboundRelationship2");
                                                                $xml->writeAttribute('typeCode','PERT');
                                                                    $xml->startElement("observation");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code','2');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                        $xml->writeAttribute('codeSystemVersion','1.1');
                                                                        $xml->writeAttribute('displayName','additionalInformation');
                                                                        $xml->endElement();
                                                                        $xml->startElement("value");
                                                                        $xml->writeAttribute('xsi:type','ST');
                                                                        $xml->text($this->XMLvalue($caseId,209,$product_details['set_number']));
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" G.k.11: Additional Information on Drug (free text) #".$product_details['set_number']);
                                                                    $xml->endElement();//observation
                                                                $xml->endElement();//outboundRelationship2
                                                            }
                                                            if($this->XMLvalue($caseId,1160,$product_details['set_number'])!=null){
                                                                $xml->startElement("outboundRelationship2");
                                                                $xml->writeAttribute('typeCode','REFR');
                                                                    $xml->startElement("observation");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code','CN-7');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                        $xml->writeAttribute('codeSystemVersion','1.1');
                                                                        $xml->writeAttribute('displayName','drugProductName');
                                                                        $xml->endElement();
                                                                        $xml->startElement("value");
                                                                        $xml->writeAttribute('xsi:type','ST');
                                                                        $xml->text($this->XMLvalue($caseId,1160,$product_details['set_number']));
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" G.k.CN.1 药品通用名称 #".$product_details['set_number']);
                                                                    $xml->endElement();//observation
                                                                $xml->endElement();//outboundRelationship2
                                                            }
                                                            if($this->XMLvalue($caseId,1161,$product_details['set_number'])!=null){
                                                                $xml->startElement("outboundRelationship2");
                                                                $xml->writeAttribute('typeCode','REFR');
                                                                    $xml->startElement("observation");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code','CN-14');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                        $xml->writeAttribute('codeSystemVersion','1.1');
                                                                        $xml->writeAttribute('displayName','medicalEquipment');
                                                                        $xml->endElement();
                                                                        $xml->startElement("value");
                                                                        $xml->writeAttribute('xsi:type','ST');
                                                                        $xml->text($this->XMLvalue($caseId,1161,$product_details['set_number']));
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" G.k.CN.2 相关器械 #".$product_details['set_number']);
                                                                    $xml->endElement();//observation
                                                                $xml->endElement();//outboundRelationship2
                                                            }
                                                            if($this->XMLvalue($caseId,1162,$product_details['set_number'])!=null){
                                                                $xml->startElement("outboundRelationship2");
                                                                $xml->writeAttribute('typeCode','REFR');
                                                                    $xml->startElement("observation");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code','CN-15');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                        $xml->writeAttribute('codeSystemVersion','1.1');
                                                                        $xml->writeAttribute('displayName','selfDrugProduct');
                                                                        $xml->endElement();
                                                                        $xml->startElement("value");
                                                                        $xml->writeAttribute('xsi:type','BL');
                                                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,1162,$product_details['set_number']));
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" G.k.CN.3 是否为本持有人产品 #".$product_details['set_number']);
                                                                    $xml->endElement();//observation
                                                                $xml->endElement();//outboundRelationship2 
                                                            }
                                                            if($this->XMLvalue($caseId,1163,$product_details['set_number'])!=null){
                                                                $xml->startElement("outboundRelationship2");
                                                                $xml->writeAttribute('typeCode','REFR');
                                                                    $xml->startElement("observation");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code','CN-16');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                        $xml->writeAttribute('codeSystemVersion','1.1');
                                                                        $xml->writeAttribute('displayName','selfDrugProduct');
                                                                        $xml->endElement();
                                                                        $xml->startElement("value");
                                                                        $xml->writeAttribute('xsi:type','ST');
                                                                        $xml->text($this->XMLvalue($caseId,1163,$product_details['set_number']));
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" G.k.CN.4 批准文号/受理号 #".$product_details['set_number']);
                                                                    $xml->endElement();//observation
                                                                $xml->endElement();//outboundRelationship2   
                                                            } 
                                                        if($this->getSecondLevel($caseId,196,$product_details['set_number'])!=null){
                                                            $indicarion=$this->getSecondLevel($caseId,196,$product_details['set_number']);
                                                            $i=1;
                                                            foreach($indicarion as $indicarion_details){            
                                                                $xml->startElement("inboundRelationship");
                                                                $xml->writeAttribute('typeCode','RSON');
                                                                    $xml->startElement("observation");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code','19');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                        $xml->writeAttribute('codeSystemVersion','1.1');
                                                                        $xml->writeAttribute('displayName','indication');
                                                                        $xml->endElement();
                                                                        $xml->startElement("value");
                                                                        $xml->writeAttribute('xsi:type','CE');
                                                                        $xml->writeAttribute('code',$this->doubleSetValue($caseId,197,$product_details['set_number'],$i));
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.6.163');
                                                                        $xml->writeAttribute('codeSystemVersion',$this->doubleSetValue($caseId,196,$product_details['set_number'],$i));
                                                                        $xml->writeComment(" G.k.7.r.2a: MedDRA Version for Indication #".$product_details['set_number']."-".$i);
                                                                        $xml->writeComment("G.k.7.r.2b: Indication (MedDRA code)  #".$product_details['set_number']."-".$i);
                                                                            $xml->startElement("originalText");
                                                                            $xml->text($this->doubleSetValue($caseId,1047,$product_details['set_number'],$i));
                                                                            $xml->endElement();
                                                                            $xml->writeComment(" G.k.7.r.1: Indication as Reported by the Primary Source #".$product_details['set_number']."-".$i);
                                                                        $xml->endElement();
                                                                        $xml->startElement("performer");
                                                                        $xml->writeAttribute('typeCode','PRF');
                                                                            $xml->startElement("assignedEntity");
                                                                            $xml->writeAttribute('classCode','ASSIGNED');
                                                                                $xml->startElement("code");
                                                                                $xml->writeAttribute('code','3');
                                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.21');
                                                                                $xml->writeAttribute('codeSystemVersion','1.0');
                                                                                $xml->writeAttribute('displayName','sourceReporter');
                                                                                $xml->endElement();
                                                                            $xml->endElement();
                                                                        $xml->endElement();//performer
                                                                    $xml->endElement();//observation       
                                                                $xml->endElement();//inboundRelationship
                                                                $i=$i+1;
                                                            }
                                                        }
                                                        if($this->getSecondLevel($caseId,208,$product_details['set_number'])!=null){
                                                            $action=$this->getSecondLevel($caseId,208,$product_details['set_number']);
                                                            $i=1;
                                                            foreach($action as $action_details){     
                                                                $xml->startElement("inboundRelationship");
                                                                $xml->writeAttribute('typeCode','CAUS');
                                                                    $xml->startElement("act");
                                                                    $xml->writeAttribute('classCode','ACT');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code',$this->doubleSetValue($caseId,208,$product_details['set_number'],$i));
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.15');
                                                                        $xml->writeAttribute('codeSystemVersion','1.0');
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" G.k.8: Action(s) Taken with Drug #".$product_details['set_number'].'-'.$i);
                                                                    $xml->endElement();//act       
                                                                $xml->endElement();//inboundRelationship
                                                                $i=$i+1;
                                                            }
                                                        }
                                                            $xml->endElement();//substanceAdministration
                                                        $xml->endElement();//component
                                                    }
                                                }
                                                    $xml->endElement();//organizer
                                                $xml->endElement();//subjectOf2
                                            $xml->endElement();//primaryRole
                                        $xml->endElement();//SUBJECT1
                                if($drugRole!=null){ 
                                    foreach($drugRole as $drugRole_details){
                                        $xml->startElement("component");
                                        $xml->writeAttribute('typeCode','COMP');
                                            $xml->startElement("causalityAssessment");
                                            $xml->writeAttribute('classCode','OBS');
                                            $xml->writeAttribute('moodCode','EVN');
                                                $xml->startElement("code");
                                                $xml->writeAttribute('code','20');
                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                $xml->endElement();
                                                $xml->startElement("value");
                                                $xml->writeAttribute('xsi:type','CE');
                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,175,$drugRole_details['set_number']));
                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.13');
                                                $xml->endElement();
                                                $xml->writeComment(" G.k.1: Characterisation of Drug Role #".$drugRole_details['set_number']);
                                                $xml->startElement("subject2");
                                                $xml->writeAttribute('typeCode','SUBJ');
                                                    $xml->startElement("productUseReference");
                                                    $xml->writeAttribute('classCode','SBADM');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("id");
                                                        $xml->writeAttribute('root','drugroot'.$drugRole_details['set_number']);
                                                        $xml->endElement();
                                                    $xml->endElement();//productUseReference
                                                $xml->endElement();//subject2
                                            $xml->endElement();//causalityAssessment
                                        $xml->endElement();//component
                                    }
                                }
                                    if($this->XMLvalue($caseId,1167,$product_details['set_number'])!=null){
                                        $xml->startElement("component");
                                        $xml->writeAttribute('typeCode','COMP');
                                            $xml->startElement("causalityAssessment");
                                            $xml->writeAttribute('classCode','OBS');
                                            $xml->writeAttribute('moodCode','EVN');
                                                $xml->startElement("code");
                                                $xml->writeAttribute('code','39');
                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                $xml->writeAttribute('codeSystemVersion','1.1');
                                                $xml->writeAttribute('displayName','causality');
                                                $xml->endElement();
                                                $xml->startElement("value");
                                                $xml->writeAttribute('xsi:type','CE');
                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,1167,$product_details['set_number']));
                                                $xml->writeAttribute('codeSystem','CN-CSV');
                                                $xml->writeAttribute('codeSystemVersion','1.0');
                                                $xml->writeAttribute('displayName','Reasonable possibility');
                                                $xml->endElement();
                                                $xml->writeComment(" G.k.9.i.2.r.CN.2 评估结果 Drug#".$product_details['set_number']);
                                                $xml->startElement("methodCode");
                                                $xml->writeElement('originalText','WHO');
                                                $xml->endElement();//methodCode
                                                $xml->startElement("author");
                                                    $xml->writeAttribute('typeCode','AUT');
                                                    $xml->startElement("assignedEntity");
                                                    $xml->writeAttribute('classCode','ASSIGNED');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,1168,$product_details['set_number']));
                                                        $xml->writeAttribute('codeSystem','CN-CSV');
                                                        $xml->writeAttribute('codeSystemVersion','1.0');
                                                        $xml->writeAttribute('displayName','first reporter or mah');
                                                        $xml->endElement();//code
                                                        $xml->writeComment(" G.k.9.i.2.r.CN.1 评估来源 Drug#".$product_details['set_number']);
                                                    $xml->endElement();//assignedEntity
                                                $xml->endElement();//author
                                                $xml->startElement("subject1");
                                                $xml->writeAttribute('typeCode','SUBJ');
                                                    $xml->startElement("adverseEffectReference");
                                                    $xml->writeAttribute('classCode','OBS');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("id");
                                                        $xml->writeAttribute('root','154eb889-958b-45f2-a02f-42d4d6f4657f');
                                                        $xml->endElement();//id
                                                    $xml->endElement();//adverseEffectReference
                                                $xml->endElement();//subject1
                                                $xml->startElement("subject2");
                                                $xml->writeAttribute('typeCode','SUBJ');
                                                    $xml->startElement("productUseReference");
                                                    $xml->writeAttribute('classCode','SBADM');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("id");
                                                        $xml->writeAttribute('root','3c91b4d5-e039-4a7a-9c30-67671b0ef9e4');
                                                        $xml->endElement();
                                                    $xml->endElement();//productUseReference
                                                $xml->endElement();//subject2
                                            $xml->endElement();//causalityAssessment
                                        $xml->endElement();//component
                                    }
                        for($i=1;$i<=$drugSet;$i++){
                            for($j=1;$j<=$eventSet;$j++){
                                if($this->getThirdLevel($caseId,216,$i,$j)!=null){
                                    $resultAssessment=$this->getThirdLevel($caseId,216,$i,$j);
                                    $a=1;
                                    foreach($resultAssessment as $resultAssessment_details){         
                                        $xml->startElement("component");
                                        $xml->writeAttribute('typeCode','COMP');
                                            $xml->startElement("causalityAssessment");
                                            $xml->writeAttribute('classCode','OBS');
                                            $xml->writeAttribute('moodCode','EVN');
                                                $xml->startElement("code");
                                                $xml->writeAttribute('code','39');
                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                $xml->writeAttribute('codeSystemVersion','1.1');
                                                $xml->writeAttribute('displayName','causality');
                                                $xml->endElement();
                                                $xml->startElement("value");
                                                $xml->writeAttribute('xsi:type','ST');
                                                $xml->text($this->thirdSetValue($caseId,216,$i,$j,$a));
                                                $xml->endElement();
                                                $xml->writeComment(" G.k.9.i.2.r.3: Result of Assessment Drug #".$i.", Reaction #".$j.", Assessment #".$a);
                                                $xml->startElement("methodCode");
                                                $xml->writeElement('originalText',$this->thirdSetValue($caseId,215,$i,$j,$a));
                                                $xml->writeComment(" G.k.9.i.2.r.2: Method of Assessment Drug #".$i.", Reaction #".$j.", Assessment #".$a);
                                                $xml->endElement();//methodCode
                                                $xml->startElement("author");
                                                    $xml->writeAttribute('typeCode','AUT');
                                                    $xml->startElement("assignedEntity");
                                                    $xml->writeAttribute('classCode','ASSIGNED');
                                                        $xml->startElement("code");
                                                        $xml->writeElement('originalText',$this->thirdSetValue($caseId,214,$i,$j,$a));
                                                        $xml->writeComment(" G.k.9.i.2.r.1: Source of Assessment Drug #".$i.", Reaction #".$j.", Assessment #".$a);
                                                        $xml->endElement();//code
                                                    $xml->endElement();//assignedEntity
                                                $xml->endElement();//author
                                                $xml->startElement("subject1");
                                                $xml->writeAttribute('typeCode','SUBJ');
                                                    $xml->startElement("adverseEffectReference");
                                                    $xml->writeAttribute('classCode','OBS');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("id");
                                                        $xml->writeAttribute('root','154eb889-958b-45f2-a02f-42d4d6f4657f');
                                                        $xml->endElement();//id
                                                    $xml->endElement();//adverseEffectReference
                                                $xml->endElement();//subject1
                                                $xml->startElement("subject2");
                                                $xml->writeAttribute('typeCode','SUBJ');
                                                    $xml->startElement("productUseReference");
                                                    $xml->writeAttribute('classCode','SBADM');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("id");
                                                        $xml->writeAttribute('root','3c91b4d5-e039-4a7a-9c30-67671b0ef9e4');
                                                        $xml->endElement();
                                                    $xml->endElement();//productUseReference
                                                $xml->endElement();//subject2
                                            $xml->endElement();//causalityAssessment
                                        $xml->endElement();//component
                                        $a=$a+1;
                                    }
                                }
                            }
                        }
                                    if($this->XMLvalue($caseId,219,1)!=null){
                                        $xml->startElement("component1");
                                        $xml->writeAttribute('typeCode','COMP');
                                            $xml->startElement("observationEvent");
                                            $xml->writeAttribute('classCode','OBS');
                                            $xml->writeAttribute('moodCode','EVN');
                                                $xml->startElement("code");
                                                $xml->writeAttribute('code','10');
                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                $xml->writeAttribute('codeSystemVersion','1.1');
                                                $xml->writeAttribute('displayName','comment');
                                                $xml->endElement();
                                                $xml->startElement("value");
                                                $xml->writeAttribute('xsi:type','ED');
                                                $xml->text($this->XMLvalue($caseId,219,1));
                                                $xml->endElement();
                                                $xml->writeComment(" H.2: Reporter's Comments ");
                                                $xml->startElement("author");
                                                    $xml->writeAttribute('typeCode','AUT');
                                                    $xml->startElement("assignedEntity");
                                                    $xml->writeAttribute('classCode','ASSIGNED');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','3');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.21');
                                                        $xml->writeAttribute('codeSystemVersion','1.0');
                                                        $xml->writeAttribute('displayName','sourceReporter');
                                                        $xml->endElement();//code
                                                    $xml->endElement();//assignedEntity
                                                $xml->endElement();//author
                                            $xml->endElement();//observationEvent
                                        $xml->endElement();//component1
                                    }
                                if($senderDiagnosis!=null){
                                    foreach($senderDiagnosis as $senderDiagnosis_details){
                                        $xml->startElement("component1");
                                        $xml->writeAttribute('typeCode','COMP');
                                            $xml->startElement("observationEvent");
                                            $xml->writeAttribute('classCode','OBS');
                                            $xml->writeAttribute('moodCode','EVN');
                                                $xml->startElement("code");
                                                $xml->writeAttribute('code','15');
                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                $xml->writeAttribute('codeSystemVersion','1.1');
                                                $xml->writeAttribute('displayName','diagnosis');
                                                $xml->endElement();
                                                $xml->startElement("value");
                                                $xml->writeAttribute('xsi:type','CE');
                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,221,$senderDiagnosis_details['set_number']));
                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.6.163');
                                                $xml->writeAttribute('codeSystemVersion',$this->XMLvalue($caseId,220,$senderDiagnosis_details['set_number']));
                                                $xml->endElement();
                                                $xml->writeComment(" H.3.r.1a: MedDRA Version for Sender's Diagnosis / Syndrome and / or Reclassification of Reaction / Event #".$senderDiagnosis_details['set_number']);
                                                $xml->writeComment(" H.3.r.1b: Sender's Diagnosis / Syndrome and / or Reclassification of Reaction / Event  (MedDRA code) #".$senderDiagnosis_details['set_number']);
                                                $xml->startElement("author");
                                                    $xml->writeAttribute('typeCode','AUT');
                                                    $xml->startElement("assignedEntity");
                                                    $xml->writeAttribute('classCode','ASSIGNED');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','1');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.21');
                                                        $xml->writeAttribute('codeSystemVersion','1.0');
                                                        $xml->writeAttribute('displayName','sender');
                                                        $xml->endElement();//code
                                                    $xml->endElement();//assignedEntity
                                                $xml->endElement();//author
                                            $xml->endElement();//observationEvent
                                        $xml->endElement();//component1
                                    }
                                }
                                    if($this->XMLvalue($caseId,222,1)!=null){
                                        $xml->startElement("component1");
                                        $xml->writeAttribute('typeCode','COMP');
                                            $xml->startElement("observationEvent");
                                            $xml->writeAttribute('classCode','OBS');
                                            $xml->writeAttribute('moodCode','EVN');
                                                $xml->startElement("code");
                                                $xml->writeAttribute('code','10');
                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                $xml->writeAttribute('codeSystemVersion','1.1');
                                                $xml->writeAttribute('displayName','comment');
                                                $xml->endElement();
                                                $xml->startElement("value");
                                                $xml->writeAttribute('xsi:type','ED');
                                                $xml->text($this->XMLvalue($caseId,222,1));
                                                $xml->endElement();
                                                $xml->writeComment("  H.4: Sender's Comments ");
                                                $xml->startElement("author");
                                                    $xml->writeAttribute('typeCode','AUT');
                                                    $xml->startElement("assignedEntity");
                                                    $xml->writeAttribute('classCode','ASSIGNED');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','1');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.21');
                                                        $xml->writeAttribute('codeSystemVersion','1.0');
                                                        $xml->writeAttribute('displayName','sender');
                                                        $xml->endElement();//code
                                                    $xml->endElement();//assignedEntity
                                                $xml->endElement();//author
                                            $xml->endElement();//observationEvent
                                        $xml->endElement();//component1
                                    }
                                    $xml->endElement();//adverseEventAssessment
                                $xml->endElement();//component
                            if($this->XMLvalue($caseId,13,1)!=null){
                                $xml->startElement("component");
                                $xml->writeAttribute('typeCode','COMP');
                                    $xml->startElement("observationEvent");
                                    $xml->writeAttribute('classCode','OBS');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','1');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                        $xml->writeAttribute('codeSystemVersion','1.1');
                                        $xml->writeAttribute('displayName','additionalDocumentsAvailable');
                                        $xml->endElement();
                                        $xml->startElement("value");
                                        $xml->writeAttribute('xsi:type','BL');
                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,13,1));
                                        $xml->writeComment(' C.1.6.1: Are Additional Documents Available? ');
                                        $xml->endElement();//value
                                    $xml->endElement();//observationEvent
                                $xml->endElement();//component
                            }
                            if($this->XMLvalue($caseId,15,1)!=null){
                                $xml->startElement("component");
                                $xml->writeAttribute('typeCode','COMP');
                                    $xml->startElement("observationEvent");
                                    $xml->writeAttribute('classCode','OBS');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','23');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                        $xml->writeAttribute('codeSystemVersion','1.1');
                                        $xml->writeAttribute('displayName','localCriteriaForExpedited');
                                        $xml->endElement();
                                        $xml->startElement("value");
                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,15,1));
                                        $xml->writeAttribute('xsi:type','BL');
                                        $xml->writeComment(' C.1.7: Does This Case Fulfill the Local Criteria for an Expedited Report? ');
                                        $xml->endElement();
                                    $xml->endElement();//observationEvent
                                $xml->endElement();//component
                            }
                            if($this->XMLvalue($caseId,1152,1)!=null){
                                $xml->startElement("component");
                                $xml->writeAttribute('typeCode','COMP');
                                    $xml->startElement("observationEvent");
                                    $xml->writeAttribute('classCode','OBS');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','CN-6');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                        $xml->writeAttribute('codeSystemVersion','1.1');
                                        $xml->writeAttribute('displayName','reportSource');
                                        $xml->endElement();
                                        $xml->startElement("value");
                                        $xml->writeAttribute('xsi:type','CE');
                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,1152,1));
                                        $xml->endElement();
                                        $xml->writeComment("  C.1.CN.1 报告来源 ");
                                    $xml->endElement();//observationEvent
                                $xml->endElement();//component
                            }
                                $xml->startElement("component");
                                $xml->writeAttribute('typeCode','COMP');
                                    $xml->startElement("observationEvent");
                                    $xml->writeAttribute('classCode','OBS');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','CN-11');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                        $xml->writeAttribute('codeSystemVersion','1.1');
                                        $xml->writeAttribute('displayName','classifyOfReport');
                                        $xml->endElement();
                                        $xml->startElement("value");
                                        $xml->writeAttribute('xsi:type','CE');
                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,1153,1));
                                        $xml->endElement();
                                        $xml->writeComment(" C.1.CN.2 报告分类 ");
                                    $xml->endElement();//observationEvent
                                $xml->endElement();//component
                            if($senderInfo['cn_mark']!=null){
                                $xml->startElement("component");
                                $xml->writeAttribute('typeCode','COMP');
                                    $xml->startElement("observationEvent");
                                    $xml->writeAttribute('classCode','OBS');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','CN-12');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                        $xml->writeAttribute('codeSystemVersion','1.1');
                                        $xml->writeAttribute('displayName','mahID');
                                        $xml->endElement();
                                        $xml->startElement("value");
                                        $xml->writeAttribute('xsi:type','CE');
                                        $xml->writeAttribute('code',$senderInfo['cn_mark']);
                                        $xml->endElement();
                                        $xml->writeComment(" C.1.CN.3 持有人标识 ");
                                    $xml->endElement();//observationEvent
                                $xml->endElement();//component
                            }
                        if($caseSummary!=null){
                            foreach($caseSummary as $caseSummary_details){
                                $xml->startElement("component");
                                $xml->writeAttribute('typeCode','COMP');
                                    $xml->startElement("observationEvent");
                                    $xml->writeAttribute('classCode','OBS');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','36');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                        $xml->writeAttribute('codeSystemVersion','1.1');
                                        $xml->writeAttribute('displayName','summaryAndComment');
                                        $xml->endElement();
                                        $xml->startElement("value");
                                        $xml->writeAttribute('xsi:type','ED');
                                        $xml->writeAttribute('language',$this->XMLvalue($caseId,1050,$caseSummary_details['set_number']));
                                        $xml->text($this->XMLvalue($caseId,1049,1));
                                        $xml->writeComment(" H.5.r.1a: Case Summary and Reporter's Comments Text #".$caseSummary_details['set_number']);
                                        $xml->writeComment(" H.5.r.1b: Case Summary and Reporter's Comments Language #".$caseSummary_details['set_number']);
                                        $xml->endElement();
                                        $xml->startElement("author");
                                        $xml->writeAttribute('typeCode','AUT');
                                            $xml->startElement("assignedEntity");
                                            $xml->writeAttribute('classCode','ASSIGNED');
                                                $xml->startElement("code");
                                                $xml->writeAttribute('code','2');
                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.21'); 
                                                $xml->writeAttribute('codeSystemVersion','1.0');
                                                $xml->writeAttribute('displayName','reporter');
                                                $xml->endElement();
                                            $xml->endElement();//assignedEntity
                                        $xml->endElement();//author
                                    $xml->endElement();//observationEvent
                                $xml->endElement();//component
                            }
                        }
                                $xml->startElement("outboundRelationship");
                                $xml->writeAttribute('typeCode','SPRT');
                                    $xml->startElement("relatedInvestigation");
                                    $xml->writeAttribute('classCode','INVSTG');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','1');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.22');
                                        $xml->writeAttribute('codeSystemVersion','1.0');
                                        $xml->writeAttribute('displayName','initialReport');
                                        $xml->endElement();
                                        $xml->startElement("subjectOf2");
                                        $xml->writeAttribute('typeCode','SUBJ');
                                            $xml->startElement("controlActEvent");
                                            $xml->writeAttribute('classCode','CACT');
                                            $xml->writeAttribute('moodCode','EVN');
                                                $xml->startElement("author");
                                                $xml->writeAttribute('typeCode','AUT');
                                                    $xml->startElement("assignedEntity");
                                                    $xml->writeAttribute('classCode','ASSIGNED');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,17,1));
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.3');
                                                        $xml->writeAttribute('codeSystemVersion','1.0');
                                                        $xml->endElement();//code
                                                        $xml->writeComment(" C.1.8.2: First Sender of This Case ");
                                                    $xml->endElement();//assignedEntity
                                                $xml->endElement();//author
                                            $xml->endElement();//controlActEvent
                                        $xml->endElement();//subjectOf2
                                    $xml->endElement();//observation
                                $xml->endElement();//outboundRelationship
                        if($linkedId!=null){
                            foreach($linkedId as $linkedId_details){
                                $xml->startElement("outboundRelationship");
                                $xml->writeAttribute('typeCode','SPRT');
                                    $xml->startElement("relatedInvestigation");
                                    $xml->writeAttribute('classCode','INVSTG');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('nullFlavor','NA');
                                        $xml->endElement();
                                        $xml->startElement("subjectOf2");
                                        $xml->writeAttribute('typeCode','SUBJ');
                                            $xml->startElement("controlActEvent");
                                            $xml->writeAttribute('classCode','CACT');
                                            $xml->writeAttribute('moodCode','EVN');
                                                $xml->startElement("id");
                                                $xml->writeAttribute('extension',$this->XMLvalue($caseId,21,$linkedId_details['set_number']));
                                                $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.2');
                                                $xml->writeComment(" C.1.10.r: Identification Number of the Report Which Is Linked to This Report (repeat as necessary)  #".$linkedId_details['set_number']);
                                                $xml->endElement();//id
                                            $xml->endElement();//controlActEvent
                                        $xml->endElement();//subjectOf2
                                    $xml->endElement();//relatedInvestigation
                                $xml->endElement();//outboundRelationship
                            }
                        }
                                $xml->startElement("outboundRelationship");
                                $xml->writeAttribute('typeCode','SPRT');
                                    $xml->startElement("priorityNumber");
                                    $xml->writeAttribute('value',$this->XMLvalue($caseId,1001,1));
                                    $xml->endElement();
                                    $xml->writeComment(' C.2.r.5: Primary Source for Regulatory Purposes');
                                    $xml->startElement("relatedInvestigation");
                                    $xml->writeAttribute('classCode','INVSTG');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','2');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.22');
                                        $xml->writeAttribute('codeSystemVersion','1.0');
                                        $xml->writeAttribute('displayName','sourceReport');
                                        $xml->endElement();
                                        $xml->startElement("subjectOf2");
                                        $xml->writeAttribute('typeCode','SUBJ');
                                            $xml->startElement("controlActEvent");
                                            $xml->writeAttribute('classCode','CACT');
                                            $xml->writeAttribute('moodCode','EVN');
                                                $xml->startElement("author");
                                                $xml->writeAttribute('typeCode','AUT');
                                                    $xml->startElement("assignedEntity");
                                                    $xml->writeAttribute('classCode','ASSIGNED');
                                                        $xml->startElement("addr");
                                                        if($this->XMLvalue($caseId,31,1)!=null){
                                                            $xml->writeElement("streetAddressLine",$this->XMLvalue($caseId,31,1));
                                                            $xml->writeComment(" C.2.r.2.3: Reporter's Street #1 ");
                                                        }
                                                        if($this->XMLvalue($caseId,32,1)!=null){
                                                            $xml->writeElement("city",$this->XMLvalue($caseId,32,1));
                                                            $xml->writeComment(" C.2.r.2.4: Reporter's City #1 ");
                                                        }
                                                        if($this->XMLvalue($caseId,33,1)!=null){
                                                            $xml->writeElement("state",$this->XMLvalue($caseId,33,1));
                                                            $xml->writeComment(" C.2.r.2.5: Reporter's State or Province #1 ");
                                                        }
                                                        if($this->XMLvalue($caseId,34,1)!=null){
                                                            $xml->writeElement("postalCode",$this->XMLvalue($caseId,34,1));
                                                            $xml->writeComment(" C.2.r.2.6: Reporter's Postcode #1 ");
                                                        }
                                                        $xml->endElement();//addr
                                                    if($this->XMLvalue($caseId,1139,1)!=null){
                                                        $xml->startElement("telecom");
                                                        $xml->writeAttribute('vlaue',$this->XMLvalue($caseId,1139,1));
                                                        $xml->endElement();
                                                        $xml->writeComment(" C.2.r.2.7: Reporter's Telephone ");
                                                    }
                                                    if($this->XMLvalue($caseId,232,1)!=null){
                                                        $xml->startElement("telecom");
                                                        $xml->writeAttribute('vlaue','mailto:'.$this->XMLvalue($caseId,232,1));
                                                        $xml->endElement();
                                                        $xml->writeComment(" C.2.r.CN.1 电子邮箱 ");
                                                    }
                                                        $xml->startElement("assignedPerson");
                                                        $xml->writeAttribute('classCode','PSN');
                                                        $xml->writeAttribute('determinerCode','INSTANCE');
                                                            $xml->startElement("name");
                                                            if($this->XMLvalue($caseId,25,1)!=null){
                                                                $xml->writeElement("prefix",$this->XMLvalue($caseId,25,1));
                                                                $xml->writeComment(" C.2.r.1.1: Reporter's Title #1 ");
                                                            }
                                                            if($this->XMLvalue($caseId,26,1)!=null){
                                                                $xml->writeElement("given",$this->XMLvalue($caseId,26,1));
                                                                $xml->writeComment(" C.2.r.1.2: Reporter's Given Name #1 ");
                                                            }
                                                            if($this->XMLvalue($caseId,27,1)!=null){
                                                                $xml->writeElement("given",$this->XMLvalue($caseId,27,1));
                                                                $xml->writeComment(" C.2.r.1.3: Reporter's Middle Name #1 ");
                                                            }
                                                            if($this->XMLvalue($caseId,28,1)!=null){
                                                                $xml->writeElement("family",$this->XMLvalue($caseId,28,1));
                                                                $xml->writeComment(" C.2.r.1.4: Reporter's Family Name #1 ");
                                                            }
                                                            $xml->endElement();//name
                                                        if($this->XMLvalue($caseId,36,1)!=null){
                                                            $xml->startElement("asQualifiedEntity");
                                                            $xml->writeAttribute('classCode','QUAL');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,36,1));
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.6');
                                                                $xml->writeAttribute('codeSystemVersion','1.0');
                                                                $xml->endElement();
                                                                $xml->writeComment(" C.2.r.4: Qualification");
                                                            $xml->endElement();//asQualifiedEntity
                                                        }
                                                        if($this->XMLvalue($caseId,35,1)!=null){
                                                            $xml->startElement("asLocatedEntity");
                                                            $xml->writeAttribute('classCode','LOCE');
                                                                $xml->startElement("location");
                                                                $xml->writeAttribute('classCode','COUNTRY');
                                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                                    $xml->startElement("code");
                                                                    $xml->writeAttribute('code',$this->XMLvalue($caseId,35,1));
                                                                    $xml->writeAttribute('codeSystem','1.0.3166.1.2.2');
                                                                    $xml->endElement();
                                                                    $xml->writeComment(" C.2.r.3: Reporter's Country Code ");
                                                                $xml->endElement();
                                                            $xml->endElement();//asQualifiedEntity
                                                        }
                                                        $xml->endElement();//assignedPerson
                                                    if($this->XMLvalue($caseId,30,1)!=null){
                                                        $xml->startElement("representedOrganization");
                                                        $xml->writeAttribute('classCode','ORG');
                                                        $xml->writeAttribute('determinerCode','INSTANCE');
                                                            $xml->writeElement("name",$this->XMLvalue($caseId,30,1));
                                                            $xml->writeComment(" C.2.r.2.2: Reporter's Department ");
                                                        if($this->XMLvalue($caseId,29,1)!=null){
                                                            $xml->startElement("assignedEntity");
                                                            $xml->writeAttribute('classCode','ASSIGNED');
                                                                $xml->startElement("representedOrganization");
                                                                $xml->writeAttribute('classCode','ORG');
                                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                                    $xml->writeElement("name",$this->XMLvalue($caseId,29,1));
                                                                    $xml->writeComment(" C.2.r.2.1: Reporter's Organisation");
                                                                $xml->endElement();
                                                            $xml->endElement();//assignedEntity
                                                        }
                                                        $xml->endElement();//representedOrganization
                                                    }
                                                    $xml->endElement();//assignedEntity
                                                $xml->endElement();//author
                                            $xml->endElement();//controlActEvent
                                        $xml->endElement();//subjectOf2
                                    $xml->endElement();//relatedInvestigation
                                $xml->endElement();//outboundRelationship
                                $xml->startElement("subjectOf1");
                                $xml->writeAttribute('typeCode','SUBJ');
                                    $xml->startElement("controlActEvent");
                                    $xml->writeAttribute('classCode','CACT');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("author");
                                        $xml->writeAttribute('typeCode','AUT');
                                            $xml->startElement("assignedEntity");
                                            $xml->writeAttribute('classCode','ASSIGNED');
                                            if($senderInfo['type']!=null){
                                                $xml->startElement("code");
                                                $xml->writeAttribute('code',$senderInfo['type']);
                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.7');
                                                $xml->writeAttribute('codeSystemVersion','1.0');
                                                $xml->endElement();
                                                $xml->writeComment(" C.3.1: Sender Type ");
                                            }
                                                $xml->startElement("addr");
                                                if($senderInfo['street']!=null){
                                                    $xml->writeElement("streetAddressLine",$senderInfo['street']);
                                                    $xml->writeComment(" C.3.4.1: Sender's Street Address ");
                                                }
                                                if($senderInfo['city']!=null){
                                                    $xml->writeElement("city",$senderInfo['city']);
                                                    $xml->writeComment(" C.3.4.2: Sender's City ");
                                                }
                                                if($senderInfo['state']!=null){
                                                    $xml->writeElement("state",$senderInfo['state']);
                                                    $xml->writeComment(" C.3.4.3: Sender's State or Province ");
                                                }
                                                if($senderInfo['postcode']!=null){
                                                    $xml->writeElement("postalCode",$senderInfo['postcode']);
                                                    $xml->writeComment(" C.3.4.4: Sender's Postcode ");
                                                }
                                                $xml->endElement();//addr
                                            if($senderInfo['telephone']!=null){
                                                $xml->startElement("telecom");
                                                $xml->writeAttribute('vlaue','tel:'.$senderInfo['telephone']);
                                                $xml->endElement();
                                                $xml->writeComment(" C.3.4.6: Sender's Telephone ");
                                            }
                                            if($senderInfo['fax']!=null){
                                                $xml->startElement("telecom");
                                                $xml->writeAttribute('vlaue','fax:'.$senderInfo['fax']);
                                                $xml->endElement();
                                                $xml->writeComment(" C.3.4.7: Sender's Fax ");
                                            }
                                            if($senderInfo['email']!=null){
                                                $xml->startElement("telecom");
                                                $xml->writeAttribute('vlaue','mailto:'.$senderInfo['email']);
                                                $xml->endElement();
                                                $xml->writeComment(" C.3.4.8: Sender's E-mail Address ");
                                            }
                                                $xml->startElement("assignedPerson");
                                                $xml->writeAttribute('classCode','PSN');
                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                    $xml->startElement("name");
                                                    if($senderInfo['title']!=null){
                                                        $xml->writeElement("prefix",$senderInfo['title']);
                                                        $xml->writeComment(" C.3.3.2: Sender's Title ");
                                                    }
                                                    if($senderInfo['given_name']!=null){
                                                        $xml->writeElement("given",$senderInfo['given_name']);
                                                        $xml->writeComment(" C.3.3.3: Sender's Given Name ");
                                                    }
                                                    if($senderInfo['middle_name']!=null){
                                                        $xml->writeElement("given",$senderInfo['middle_name']);
                                                        $xml->writeComment(" C.3.3.4: Sender's Middle Name ");
                                                    }
                                                    if($senderInfo['family_name']!=null){
                                                        $xml->writeElement("family",$senderInfo['family_name']);
                                                        $xml->writeComment(" C.3.3.5: Sender's Family Name ");
                                                    }
                                                    $xml->endElement();//name
                                                if($senderInfo['country']!=null){
                                                    $xml->startElement("asLocatedEntity");
                                                    $xml->writeAttribute('classCode','LOCE');
                                                        $xml->startElement("location");
                                                        $xml->writeAttribute('classCode','COUNTRY');
                                                        $xml->writeAttribute('determinerCode','INSTANCE');
                                                            $xml->startElement("code");
                                                            $xml->writeAttribute('code',$senderInfo['country']);
                                                            $xml->writeAttribute('codeSystem','1.0.3166.1.2.2');
                                                            $xml->endElement();
                                                            $xml->writeComment(" C.3.4.5: Sender's Country Code ");
                                                        $xml->endElement();
                                                    $xml->endElement();//asLocatedEntity
                                                }
                                                $xml->endElement();//assignedPerson
                                                $xml->startElement("representedOrganization");
                                                $xml->writeAttribute('classCode','ORG');
                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                if($senderInfo['department']!=null){
                                                    $xml->writeElement("name",$senderInfo['department']);
                                                    $xml->writeComment(" C.3.3.1: Sender's Department ");
                                                }
                                                if($senderInfo['organisation']!=null){
                                                    $xml->startElement("assignedEntity");
                                                    $xml->writeAttribute('classCode','ASSIGNED');
                                                        $xml->startElement("representedOrganization");
                                                        $xml->writeAttribute('classCode','ORG');
                                                        $xml->writeAttribute('determinerCode','INSTANCE');
                                                            $xml->writeElement("name",$senderInfo['organisation']);
                                                            $xml->writeComment(" C.3.2: Sender's Organisation ");
                                                        $xml->endElement();//representedOrganization
                                                    $xml->endElement();//assignedEntity
                                                }
                                                $xml->endElement();//representedOrganization
                                            $xml->endElement();//assignedEntity
                                        $xml->endElement();//author
                                    $xml->endElement();//controlActEvent
                                $xml->endElement();//subjectOf1
                                $xml->startElement("subjectOf2");
                                $xml->writeAttribute('typeCode','SUBJ');
                                    $xml->startElement("investigationCharacteristic");
                                    $xml->writeAttribute('classCode','OBS');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','1');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.23');
                                        $xml->writeAttribute('codeSystemVersion','1.0');
                                        $xml->writeAttribute('displayName','ichReportType');
                                        $xml->endElement();
                                        $xml->startElement("value");
                                        $xml->writeAttribute('xsi:type','CE');
                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,6,1));
                                        $xml->writeAttribute('codeSystem','="2.16.840.1.113883.3.989.2.1.1.2');
                                        $xml->writeAttribute('codeSystemVersion','1.0');
                                        $xml->endElement();
                                        $xml->writeComment(" C.1.3 Type of Report ");
                                    $xml->endElement();//investigationCharacteristic
                                $xml->endElement();//subjectOf2
                        if($caseId!=null){
                            foreach($caseId as $caseId_details){
                                $xml->startElement("subjectOf1");
                                $xml->writeAttribute('typeCode','SUBJ');
                                    $xml->startElement("controlActEvent");
                                    $xml->writeAttribute('classCode','CACT');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("id");
                                        $xml->writeAttribute('code','2');
                                        $xml->writeAttribute('assigningAuthorityName',$this->XMLvalue($caseId,19,$caseId_details['set_number']));
                                        $xml->writeAttribute('extension',$this->XMLvalue($caseId,20,$caseId_details['set_number']));
                                        $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.3');
                                        $xml->endElement();//id
                                        $xml->writeComment(" C.1.9.1.r.1: Source(s) of the Case Identifier (repeat as necessary) #".$caseId_details['set_number']);
                                        $xml->writeComment(" C.1.9.1.r.2 Case Identifier(s) #".$caseId_details['set_number']);
                                    $xml->endElement();//controlActEvent
                                $xml->endElement();//subjectOf2
                            }
                        }
                            if($this->XMLvalue($caseId,18,1)!=null){
                                $xml->startElement("subjectOf2");
                                $xml->writeAttribute('typeCode','SUBJ');
                                    $xml->startElement("investigationCharacteristic");
                                    $xml->writeAttribute('classCode','OBS');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','2');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.23');
                                        $xml->writeAttribute('codeSystemVersion','1.0');
                                        $xml->writeAttribute('displayName','otherCaseIds');
                                        $xml->endElement();
                                        $xml->startElement("value");
                                        $xml->writeAttribute('xsi:type','BL');
                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,18,1));
                                        $xml->endElement();
                                        $xml->writeComment(" C.1.9.1 Other Case Identifiers in Previous Transmissions ");
                                    $xml->endElement();//investigationCharacteristic
                                $xml->endElement();//subjectOf2
                            }
                            if($this->XMLvalue($caseId,22,1)!=null){
                                $xml->startElement("subjectOf2");
                                $xml->writeAttribute('typeCode','SUBJ');
                                    $xml->startElement("investigationCharacteristic");
                                    $xml->writeAttribute('classCode','OBS');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','3');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.23');
                                        $xml->writeAttribute('codeSystemVersion','1.0');
                                        $xml->writeAttribute('displayName','nullificationAmendmentCode');
                                        $xml->endElement();
                                        $xml->startElement("value");
                                        $xml->writeAttribute('xsi:type','CE');
                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,22,1));
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.5');
                                        $xml->writeAttribute('codeSystemVersion','1.0');
                                        $xml->endElement();
                                        $xml->writeComment(" C.1.11.1: Report Nullification / Amendment ");
                                    $xml->endElement();//investigationCharacteristic
                                $xml->endElement();//subjectOf2
                                $xml->startElement("subjectOf2");
                                $xml->writeAttribute('typeCode','SUBJ');
                                    $xml->startElement("investigationCharacteristic");
                                    $xml->writeAttribute('classCode','OBS');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','4');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.23');
                                        $xml->writeAttribute('codeSystemVersion','1.0');
                                        $xml->writeAttribute('displayName','nullificationAmendmentReason');
                                        $xml->endElement();
                                        $xml->startElement("value");
                                        $xml->writeAttribute('xsi:type','CE');
                                            $xml->writeElement('originalText',$this->XMLvalue($caseId,23,1));
                                        $xml->endElement();
                                        $xml->writeComment(" C.1.11.2: Reason for Nullification / Amendment ");
                                    $xml->endElement();//investigationCharacteristic
                                $xml->endElement();//subjectOf2
                            }
                            $xml->endElement();//investigationEvent
                        $xml->endElement();//subject
                    $xml->endElement();//controlActProcess
                $xml->endElement();//PORR_IN049016UV
                $xml->startElement("receiver");
                $xml->writeAttribute('typeCode','RCV');
                    $xml->startElement("device");
                    $xml->writeAttribute('classCode','DEV');
                    $xml->writeAttribute('determinerCode','INSTANCE');
                        $xml->startElement("id");
                        $xml->writeAttribute('extension',$this->XMLvalue($caseId,555,1));
                        $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.14');
                        $xml->endElement();
                        $xml->writeComment("N.1.4: Batch Receiver Identifier ");
                    $xml->endElement();//device
                $xml->endElement();//receiver
                $xml->startElement("sender");
                $xml->writeAttribute('typeCode','SND');
                    $xml->startElement("device");
                    $xml->writeAttribute('classCode','DEV');
                    $xml->writeAttribute('determinerCode','INSTANCE');
                        $xml->startElement("id");
                        $xml->writeAttribute('extension',$this->XMLvalue($caseId,554,1));
                        $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.13');
                        $xml->endElement();
                        $xml->writeComment(" N.1.3: Batch Sender Identifier ");
                    $xml->endElement();//device
                $xml->endElement();//sender
            $xml->endElement();//MCCI_IN200100UV01
        $xml->endDocument();
        echo $xml->outputMemory();
    }  
}
