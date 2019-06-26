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
        $time=date("Y-m-d-H-i-s");
        $xml = "$fileName-$time";
        header("Content-Type: text/html/force-download");
        header("Content-Disposition: attachment; filename=".$xml.".xml");

        $xml = new \XMLWriter();
        $xml->openUri("php://output");
        $xml->setIndentString("\t");
        $xml->setIndent(true);
        // create xml Document start
        $xml->startDocument('1.0', 'ISO-8859-1');//FDA supports only the ISO-8859-1 character set for encoding the submission.
            $xml->writeDtd('ichicsr','','https://www.accessdata.fda.gov/xml/icsr-xml-v2.1.dtd');
            //A first element ichicsr
                $xml->startElement("ichicsr");
                    // Attribute lang="en"
                    $xml->writeAttribute("lang","en");
                        $xml->startElement("ichicsrmessageheader");
                            $xml->writeElement('messagetype','ICSR');
                            $xml->writeElement('messageformatversion','2.1');
                            $xml->writeElement('messageformatrelease','1.0');
                            $xml->writeElement('receivedate',$this->getSingleTag($caseId,'receivedate'));
                            $xml->writeElement('messagesenderidentifier','Company G2');
                            $xml->writeElement('messagereceiveridentifier',$this->getSingleTag($caseId,'messagereceiveridentifier'));
                            $xml->writeElement('messagedateformat','102');
                            $xml->writeElement('messagedate',$this->getSingleTag($caseId,'messagedate'));
                        $xml->endElement();//ichicsrmessageheader

                        $xml->startElement("safetyreport");
                                //SafetyReport
                                $safetyreport=$this->getErgodicTag($caseId,3,11);
                                foreach($safetyreport as $SafetyReport){
                                    $xml->writeElement($SafetyReport['tag'],$SafetyReport['fv']['field_value']);
                                }
                                
                        /*
                            $xml->writeElement('safetyreportversion',$this->getSingleTag($caseId,'safetyreportversion'));
                            $xml->writeElement('safetyreportid',$this->getSingleTag($caseId,'safetyreportid'));
                            $xml->writeElement('primarysourcecountry',$this->getSingleTag($caseId,'primarysourcecountry'));
                            $xml->writeElement('occurcountry',$this->getSingleTag($caseId,'occurcountry'));
                            $xml->writeElement('transmissiondateformat','102');
                            $xml->writeElement('transmissiondate',$this->getSingleTag($caseId,'transmissiondate'));
                            $xml->writeElement('reporttype',$this->getSingleTag($caseId,'reporttype'));
                            $xml->writeElement('serious',$this->getSingleTag($caseId,'serious'));
                            $xml->writeElement('seriousnessdeath',$this->getSingleTag($caseId,'seriousnessdeath'));
                            $xml->writeElement('seriousnesslifethreatening',$this->getSingleTag($caseId,'seriousnesslifethreatening'));
                            $xml->writeElement('seriousnesshospitalization',$this->getSingleTag($caseId,'seriousnesshospitalization'));
                            $xml->writeElement('seriousnessdisabling',$this->getSingleTag($caseId,'seriousnessdisabling'));
                            $xml->writeElement('seriousnesscongenitalanomali',$this->getSingleTag($caseId,'seriousnesscongenitalanomali'));
                            $xml->writeElement('seriousnessother',$this->getSingleTag($caseId,'seriousnessother'));
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
                            */
                            //reportduplicate
                            $duplicateNumber=$this->getMaxSetNumber($caseId,4,38);
                            for($i=1;$i<=$duplicateNumber;$i++){
                                $xml->startElement("reportduplicate");
                                $reportduplicate=$this->getRepeatTag($caseId,4,38,$i);
                                    foreach($reportduplicate as $ReportDuplicate){   
                                        $xml->writeElement($ReportDuplicate['tag'],$ReportDuplicate['fv']['field_value']); 
                                    }       
                                $xml->endElement();
                            }//ReportDuplicate

                            //linkedreport
                            $linkedNumber=$this->getMaxSetNumber($caseId,4,41);
                            for($i=1;$i<=$linkedNumber;$i++){
                                $xml->startElement("linkedreport");
                                $linkedreport=$this->getRepeatTag($caseId,4,41,$i);
                                    foreach($linkedreport as $LinkedReport){   
                                        $xml->writeElement($LinkedReport['tag'],$LinkedReport['fv']['field_value']); 
                                    }       
                                $xml->endElement();
                            }

                            //primarysource
                            $primarysourceNumber=$this->getMaxSetNumber($caseId,4,43);
                            for($i=1;$i<=$primarysourceNumber;$i++){
                                $xml->startElement("primarysource");
                                $primarysource=$this->getRepeatTag($caseId,4,43,$i);
                                    foreach($primarysource as $PrimarySource){   
                                        $xml->writeElement($PrimarySource['tag'],$PrimarySource['fv']['field_value']); 
                                    }       
                                $xml->endElement();
                            } //primarysource

                            //sender
                            $xml->startElement("sender");
                            $sender=$this->getErgodicTag($caseId,4,60);
                            foreach($sender as $Sender){
                                $xml->writeElement($Sender['tag'],$Sender['fv']['field_value']);
                            }
                            $xml->endElement();//sender

                            //receiver
                            $xml->startElement("receiver");
                            $receiver=$this->getErgodicTag($caseId,4,80);
                            foreach($receiver as $Receiver){
                                $xml->writeElement($Receiver['tag'],$Receiver['fv']['field_value']);
                            }
                            $xml->endElement();//receiver

                            //patient
                            $xml->startElement("patient");
                                $xml->startElement("patient");
                                $patient=$this->getErgodicTag($caseId,4,100);
                                foreach($patient as $Patient){
                                    $xml->writeElement($Patient['tag'],$Patient['fv']['field_value']);
                                }
                                
                            /*
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
                                */

                                //medicalhistoryepisode
                                $medicalhistoryepisodeNumber=$this->getMaxSetNumber($caseId,5,120);
                                for($i=1;$i<=$medicalhistoryepisodeNumber;$i++){
                                    $xml->startElement("medicalhistoryepisode");
                                    $medicalhistoryepisode=$this->getRepeatTag($caseId,5,120,$i);
                                        foreach($medicalhistoryepisode as $MedicalHistoryEpisode){   
                                            $xml->writeElement($MedicalHistoryEpisode['tag'],$MedicalHistoryEpisode['fv']['field_value']); 
                                        }       
                                    $xml->endElement();
                                } //medicalhistoryepisode

                                //patientpastdrugtherapy
                                $patientpastdrugtherapyNumber=$this->getMaxSetNumber($caseId,5,129);
                                for($i=1;$i<=$patientpastdrugtherapyNumber;$i++){
                                    $xml->startElement("patientpastdrugtherapy");
                                    $patientpastdrugtherapy=$this->getRepeatTag($caseId,5,129,$i);
                                        foreach($patientpastdrugtherapy as $PatientPastDrugTherapy){   
                                            $xml->writeElement($PatientPastDrugTherapy['tag'],$PatientPastDrugTherapy['fv']['field_value']); 
                                        }       
                                    $xml->endElement();
                                } //patientpastdrugtherapy
                                //patientdeath
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
                                $xml->endElement();//patientdeath
                                //parent
                                $xml->startElement("parent");
                                    $patient=$this->getErgodicTag($caseId,5,149);
                                    $xml->writeElement('parentlastmenstrualdateformat','102');
                                    foreach($patient as $Patient){
                                        $xml->writeElement($Patient['tag'],$Patient['fv']['field_value']);
                                    }
                                /*
                                    $xml->writeElement('parentidentification',$this->getSingleTag($caseId,'parentidentification'));
                                    $xml->writeElement('parentage',$this->getSingleTag($caseId,'parentage'));
                                    $xml->writeElement('parentageunit',$this->getSingleTag($caseId,'parentageunit'));
                                    $xml->writeElement('parentlastmenstrualdateformat','102');
                                    $xml->writeElement('parentlastmenstrualdate',$this->getSingleTag($caseId,'parentlastmenstrualdate'));
                                    $xml->writeElement('parentweight',$this->getSingleTag($caseId,'parentweight'));
                                    $xml->writeElement('parentheight',$this->getSingleTag($caseId,'parentheight'));
                                    $xml->writeElement('parentsex',$this->getSingleTag($caseId,'parentsex'));
                                    $xml->writeElement('parentmedicalrelevanttext',$this->getSingleTag($caseId,'parentmedicalrelevanttext'));
                                   */
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
                                $xml->endElement();//parent

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
                                $xml->endElement();//summary 
                            $xml->endElement();//patient
                        $xml->endElement();//safetyreport
                    $xml->endAttribute();
                $xml->endElement();//rootelement"ichicsr"
            $xml->endDtd();
        $xml->endDocument();
        echo $xml->outputMemory(); 
   
    }
    
     
    public function genXMLThree($caseId){   
        $this->autoRender = false;
        //set file name with caseNo and create time
        $sdCases = TableRegistry::get('sdCases');
        $name=$sdCases->find()
                ->select(['caseNo'])
                ->where(['id='.$caseId,'status=1'])->first();
        $fileName=$name['caseNo'];
        $time=date("Y-m-d-H-i-s");
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
                $xml->writeAttribute('xsi:schemaLocation','urn:hl7-org:v3 multicacheschemas/.xsd');
                $xml->writeAttribute('xmlns','urn:hl7-org:v3');
                $xml->writeAttributeNS('xmlns','xsi', null,'http://www.w3.org/2001/XMLSchema-instance');
                    $xml->startElement("id");
                    $xml->writeAttribute('extension','XYZ-0123456');
                    $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.22');
                    $xml->endElement();
                    $xml->writeComment("N.1.2: Batch Number");
                    $xml->startElement("creationTime");
                    $xml->writeAttribute('value','20120821113456');
                    $xml->endElement();
                    $xml->writeComment("N.1.5: Date of Batch Transmission");
                    $xml->startElement("responseModeCode");
                    $xml->writeAttribute('code','D');
                    $xml->endElement();
                    $xml->startElement("interactionId");
                    $xml->writeAttribute('extension','MCCI_IN200100UV01');
                    $xml->writeAttribute('root','2.16.840.1.113883.1.6');
                    $xml->endElement();
                    $xml->startElement("name");
                    $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.1');
                    $xml->writeAttribute('code','1');
                    $xml->endElement();
                    $xml->writeComment("N.1.1: Type of Messages in Batch");
                    $xml->writeComment("Message #1");
                    $xml->startElement("PORR_IN049016UV");
                        $xml->startElement("id");
                        $xml->writeAttribute('extension','XYZ-0123456-887766');
                        $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.1');
                        $xml->endElement();
                        $xml->writeComment("N.2.r.1: Message Identifier");
                        $xml->startElement("creationTime");
                        $xml->writeAttribute('value','20120830120000');
                        $xml->endElement();
                        $xml->writeComment("N.2.r.4: Date of Message Creation ");
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
                                $xml->writeAttribute('extension','ABC-554');
                                $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.12');
                                $xml->endElement();
                                $xml->writeComment("N.2.r.3: Message Receiver Identifier");
                                $xml->endElement();
                        $xml->endElement();
                        $xml->startElement("sender");
                        $xml->writeAttribute('typeCode','SND');
                            $xml->startElement("device");
                            $xml->writeAttribute('classCode','DEV');
                            $xml->writeAttribute('determinerCode','INSTANCE');
                                $xml->startElement("id");
                                $xml->writeAttribute('extension','XYZ-222');
                                $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.11');
                                $xml->endElement();
                                $xml->writeComment("N.2.r.2: Message Sender Identifier");
                                $xml->endElement();
                        $xml->endElement();
                        $xml->startElement("controlActProcess");
                            $xml->writeAttribute('moodCode','EVN');
                            $xml->writeAttribute('classCode','CACT');
                            $xml->startElement("code");
                            $xml->writeAttribute('codeSystem','2.16.840.1.113883.1.18');
                            $xml->writeAttribute('code','PORR_TE049016UV');
                            $xml->endElement();
                            $xml->writeComment("HL7 Trigger Event ID");
                            $xml->startElement("effectiveTime");
                            $xml->writeAttribute('value','20120830120000');
                            $xml->endElement();
                            $xml->writeComment("C.1.2: Date of Creation");
                            $xml->startElement("subject");
                            $xml->writeAttribute('typeCode','SUBJ');
                                $xml->startElement("investigationEvent");
                                $xml->writeAttribute('moodCode','EVN');
                                $xml->writeAttribute('classCode','INCSTG');
                                    $xml->startElement("id");
                                    $xml->writeAttribute('extension','GB-XYZCOMPANY-123');
                                    $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.1');
                                    $xml->endElement();
                                    $xml->writeComment("C.1.1: Sender's (case) Safety Report Unique Identifier");
                                    $xml->startElement("id");
                                    $xml->writeAttribute('extension','GB-XYZCOMPANY-123');
                                    $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.2');
                                    $xml->endElement();
                                    $xml->writeComment("C.1.8.1: Worldwide Unique Case Identification Number");
                                    $xml->startElement("code");
                                    $xml->writeAttribute('codeSystem','2.16.840.1.113883.5.4');
                                    $xml->writeAttribute('code','PAT_ADV_EVNT');
                                    $xml->endElement();
                                    $xml->startElement("text","A 50 year-old female patient (patient ID 125-0871) was enrolled in trial . One week after the third cycle of chemotherapy treatment with intravenous Danthium 20 mg/kg once a week, the patient developed a fever (38° C) and diarrhoea (11th May 2003). The patient was hospitalised. Blood tests were performed and the patient was discovered to have neutropenia. This adverse event was considered as serious and unexpected");
                                    $xml->endElement();
                                    $xml->writeComment("H.1: Case Narrative Including Clinical Course, Therapeutic Measures, Outcome and Additional Relevant Information");
                                    $xml->startElement("statusCode");
                                    $xml->writeAttribute('code','active');
                                    $xml->endElement();
                                    $xml->startElement("effectiveTime");
                                        $xml->startElement("low");
                                        $xml->writeAttribute('value','20120815');
                                        $xml->endElement();
                                        $xml->writeComment("C.1.4: Date Report Was First Received from Source");
                                    $xml->endElement();
                                    $xml->startElement("availabilityTime");
                                    $xml->writeAttribute('value','20120815');
                                    $xml->endElement();
                                    $xml->writeComment("C.1.5: Date of Most Recent Information for This Report");
                                    $xml->startElement("reference");
                                    $xml->writeAttribute('typeCode','REFR');
                                        $xml->startElement("document");
                                        $xml->writeAttribute('moodCode','EVN');
                                        $xml->writeAttribute('classCode','DOC');
                                            $xml->startElement("code");
                                            $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.27');
                                            $xml->writeAttribute('code','1');
                                            $xml->endElement();
                                            $xml->writeComment("documentsHeldBySender");
                                            $xml->writeElement("title","discharge letter hospital");
                                            $xml->writeComment("C.1.6.1.r.1: Documents Held by Sender (repeat as necessary) #1");
                                        $xml->endElement();//DOCUMENT
                                    $xml->endElement();//reference
                                    $xml->startElement("component");
                                    $xml->writeAttribute('typeCode','COMP');
                                        $xml->startElement("adverseEventAssessment");
                                        $xml->writeAttribute('moodCode','EVN');
                                        $xml->writeAttribute('classCode','INVSTG');
                                            $xml->startElement("subject1");
                                            $xml->writeAttribute('typeCode','SBJ');
                                                $xml->startElement("primaryRole");
                                                $xml->writeAttribute('classCode','INVSBJ');
                                                    $xml->startElement("player1");
                                                    $xml->writeAttribute('classCode','PSN');
                                                    $xml->writeAttribute('determinerCode','INSTANCE');
                                                    $xml->startElement("player1");
                                                    $xml->writeAttribute('classCode','PSN');
                                                        $xml->startElement("name");
                                                        $xml->writeAttribute('nullFlavor','MSK');
                                                        $xml->endElement();
                                                        $xml->writeComment("D.1: Patient (name or initials)");
                                                        $xml->startElement("administrativeGenderCode");
                                                        $xml->writeAttribute('codeSystem','1.0.5218');
                                                        $xml->writeAttribute('code','1');
                                                        $xml->endElement();
                                                        $xml->writeComment("D.5 Sex");
                                                        $xml->startElement("asIdentifiedEntity");
                                                        $xml->writeAttribute('classCode','IDENT');
                                                            $xml->startElement("");
                                                            $xml->writeAttribute('extension','125-0871');
                                                            $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.10');
                                                            $xml->endElement();
                                                            $xml->writeComment("D.1.1.4: Patient Medical Record Number(s) and Source(s) of the Record Number (Investigation Number)");
                                                            $xml->startElement("code");
                                                            $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.4');
                                                            $xml->writeAttribute('code','4');
                                                            $xml->endElement();
                                                        $xml->endElement();
                                                    $xml->endElement();//player1
                                                    $xml->startElement("subjectOf1");
                                                    $xml->writeAttribute('typeCode','SBJ');
                                                        $xml->startElement("researchStudy");
                                                            $xml->writeAttribute('moodCode','EVN');
                                                            $xml->writeAttribute('classCode','CLINTRL');
                                                                $xml->startElement("id");
                                                                $xml->writeAttribute('extension','0105798/01');
                                                                $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.5');
                                                                $xml->endElement();
                                                                $xml->writeComment("C.5.3: Sponsor Study Number");
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.8');
                                                                $xml->writeAttribute('code','1');
                                                                $xml->endElement();
                                                                $xml->writeComment("C.5.4: Study Type Where Reaction(s) / Event(s) Were Observed");
                                                                $xml->writeElement("title","multi-centre trial to evaluate the efficacy and tolerability of Danthium in post-menopausal women with breast cancer hormone receptor positive tumours");
                                                                $xml->writeComment("C.5.2: Study Name");
                                                                $xml->startElement("authorization");
                                                                $xml->writeAttribute('typeCode','AUTH');
                                                                    $xml->startElement("studyRegistration");
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                    $xml->writeAttribute('classCode','ACT');
                                                                        $xml->startElement("id");
                                                                        $xml->writeAttribute('extension','2004-09102-03');
                                                                        $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.6');
                                                                        $xml->endElement();
                                                                        $xml->writeComment("C.5.1.r.1: Study Registration Number #1");
                                                                        $xml->startElement("author");
                                                                        $xml->writeAttribute('typeCode','AUT');
                                                                            $xml->startElement("territoriaAuthority");
                                                                            $xml->writeAttribute('classCode','TERR');
                                                                                $xml->startElement("governingAuthority");
                                                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                                                $xml->writeAttribute('classCode','COUNTRY');
                                                                                    $xml->startElement("code");
                                                                                    $xml->writeAttribute('codeSystem','1.0.3166.1.2.2');
                                                                                    $xml->writeAttribute('code','EU');
                                                                                    $xml->endElement();
                                                                                    $xml->writeComment("C.5.1.r.2: Study Registration Country #1");
                                                                                $xml->endElement();
                                                                            $xml->endElement();
                                                                        $xml->endElement();
                                                                    $xml->endElement();
                                                                $xml->endElement();
                                                                $xml->startElement("authorization");
                                                                $xml->writeAttribute('typeCode','AUTH');
                                                                    $xml->startElement("studyRegistration");
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                    $xml->writeAttribute('classCode','ACT');
                                                                        $xml->startElement("id");
                                                                        $xml->writeAttribute('extension','NL-CCMO-CC12345.001.002');
                                                                        $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.6');
                                                                        $xml->endElement();
                                                                        $xml->writeComment("C.5.1.r.1: Study Registration Number #2");
                                                                        $xml->startElement("author");
                                                                        $xml->writeAttribute('typeCode','AUT');
                                                                            $xml->startElement("territoriaAuthority");
                                                                            $xml->writeAttribute('classCode','TERR');
                                                                                $xml->startElement("governingAuthority");
                                                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                                                $xml->writeAttribute('classCode','COUNTRY');
                                                                                    $xml->startElement("code");
                                                                                    $xml->writeAttribute('codeSystem','1.0.3166.1.2.2');
                                                                                    $xml->writeAttribute('code','NL');
                                                                                    $xml->endElement();
                                                                                    $xml->writeComment("C.5.1.r.2: Study Registration Country #2");
                                                                                $xml->endElement();
                                                                            $xml->endElement();
                                                                        $xml->endElement();
                                                                    $xml->endElement();
                                                                $xml->endElement();
                                                                $xml->startElement("authorization");
                                                                $xml->writeAttribute('typeCode','AUTH');
                                                                    $xml->startElement("studyRegistration");
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                    $xml->writeAttribute('classCode','ACT');
                                                                        $xml->startElement("id");
                                                                        $xml->writeAttribute('extension','23456-22');
                                                                        $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.6');
                                                                        $xml->endElement();
                                                                        $xml->writeComment("C.5.1.r.1: Study Registration Number #3");
                                                                        $xml->startElement("author");
                                                                        $xml->writeAttribute('typeCode','AUT');
                                                                            $xml->startElement("territoriaAuthority");
                                                                            $xml->writeAttribute('classCode','TERR');
                                                                                $xml->startElement("governingAuthority");
                                                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                                                $xml->writeAttribute('classCode','COUNTRY');
                                                                                    $xml->startElement("code");
                                                                                    $xml->writeAttribute('codeSystem','1.0.3166.1.2.2');
                                                                                    $xml->writeAttribute('code','GB');
                                                                                    $xml->endElement();
                                                                                    $xml->writeComment("C.5.1.r.2: Study Registration Country #3");
                                                                                $xml->endElement();
                                                                            $xml->endElement();
                                                                        $xml->endElement();
                                                                    $xml->endElement();
                                                                $xml->endElement();
                                                            $xml->endElement();
                                                        $xml->endElement();//researchStudy
                                                    $xml->endElement();//subjectOf1
                                                    $xml->startElement("subjectOf2");
                                                    $xml->writeAttribute('typeCode','SBJ');
                                                        $xml->startElement("observation");
                                                        $xml->writeAttribute('moodCode','EVN');
                                                        $xml->writeAttribute('classCode','OBS');
                                                            $xml->startElement("code");
                                                            $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                            $xml->writeAttribute('code','3');
                                                            $xml->endElement();
                                                            $xml->writeComment("age");
                                                            $xml->startElement("value");
                                                            $xml->writeAttribute('xsi:type','PQ');
                                                            $xml->writeAttribute('unit','a');
                                                            $xml->writeAttribute('value','50');
                                                            $xml->endElement();
                                                            $xml->writeComment("D.2.2a: Age at Time of Onset of Reaction / Event (number)");
                                                            $xml->writeComment("D.2.2b: Age at Time of Onset of Reaction / Event (unit)");
                                                        $xml->endElement();//observation
                                                    $xml->endElement();//subjectOf2
                                                    $xml->startElement("subjectOf2");
                                                    $xml->writeAttribute('typeCode','SBJ');
                                                        $xml->startElement("observation");
                                                        $xml->writeAttribute('moodCode','EVN');
                                                        $xml->writeAttribute('classCode','OBS');
                                                            $xml->startElement("code");
                                                            $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                            $xml->writeAttribute('code','4');
                                                            $xml->endElement();
                                                            $xml->writeComment("ageGroup");
                                                            $xml->startElement("value");
                                                            $xml->writeAttribute('xsi:type','CE');
                                                            $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                            $xml->writeAttribute('code','5');
                                                            $xml->endElement();
                                                            $xml->writeComment("D.2.3: Patient Age Group (as per reporter)");
                                                        $xml->endElement();//observation
                                                    $xml->endElement();//subjectOf2
                                                    $xml->startElement("subjectOf2");
                                                    $xml->writeAttribute('typeCode','SBJ');
                                                        $xml->startElement("observation");
                                                        $xml->writeAttribute('moodCode','EVN');
                                                        $xml->writeAttribute('classCode','OBS');
                                                            $xml->startElement("code");
                                                            $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                            $xml->writeAttribute('code','7');
                                                            $xml->endElement();
                                                            $xml->writeComment("bodyweight");
                                                            $xml->startElement("value");
                                                            $xml->writeAttribute('xsi:type','PQ');
                                                            $xml->writeAttribute('unit','kg');
                                                            $xml->writeAttribute('value','80');
                                                            $xml->endElement();
                                                            $xml->writeComment("D.3: Body Weight (kg)");
                                                        $xml->endElement();//observation
                                                    $xml->endElement();//subjectOf2
                                                    $xml->startElement("subjectOf2");
                                                    $xml->writeAttribute('typeCode','SBJ');
                                                        $xml->startElement("observation");
                                                        $xml->writeAttribute('moodCode','EVN');
                                                        $xml->writeAttribute('classCode','OBS');
                                                            $xml->startElement("code");
                                                            $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                            $xml->writeAttribute('code','17');
                                                            $xml->endElement();
                                                            $xml->writeComment("height");
                                                            $xml->startElement("value");
                                                            $xml->writeAttribute('xsi:type','PQ');
                                                            $xml->writeAttribute('unit','cm');
                                                            $xml->writeAttribute('value','185');
                                                            $xml->endElement();
                                                            $xml->writeComment("D.4: Height (cm)");
                                                        $xml->endElement();//observation
                                                    $xml->endElement();//subjectOf2
                                                    $xml->startElement("subjectOf2");
                                                    $xml->writeAttribute('typeCode','SBJ');
                                                        $xml->startElement("organizer");
                                                        $xml->writeAttribute('moodCode','EVN');
                                                        $xml->writeAttribute('classCode','GATEGORY');
                                                            $xml->startElement("code");
                                                            $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.20');
                                                            $xml->writeAttribute('code','1');
                                                            $xml->writeComment("relevantMedicalHistoryAndConcurrentConditions");
                                                                $xml->startElement("component");
                                                                $xml->writeAttribute('typeCode','COMP');
                                                                    $xml->startElement("observation");
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('codeSystemVersion','15.0');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.6.163');
                                                                        $xml->writeAttribute('code','10048880');
                                                                        $xml->endElement();
                                                                        $xml->writeComment("D.7.1.r.1a: MedDRA Version for Medical History #1");
                                                                        $xml->writeComment("D.7.1.r.1b: Medical History (disease / surgical procedure / etc.) (MedDRA code) #1");
                                                                    $xml->endElement();
                                                                    $xml->startElement("effectiveTime");
                                                                    $xml->writeAttribute('xsi:type','IVL_TS');
                                                                        $xml->startElement("low");
                                                                        $xml->writeAttribute('value','19740101');
                                                                        $xml->endElement();
                                                                        $xml->writeComment("D.7.1.r.2: Start Date #1");
                                                                    $xml->endElement();
                                                                    $xml->startElement("inboundRelationship");
                                                                    $xml->writeAttribute('typeCode','REFR');
                                                                        $xml->startElement("observation");
                                                                        $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->writeAttribute('classCode','OBS');
                                                                            $xml->startElement("code");
                                                                            $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                            $xml->writeAttribute('code','13');
                                                                            $xml->endElement();
                                                                            $xml->writeComment("continuing");
                                                                            $xml->startElement("value");
                                                                            $xml->writeAttribute('xsi:type','BL');
                                                                            $xml->writeAttribute('value','true');
                                                                            $xml->endElement();
                                                                            $xml->writeComment("D.7.1.r.3: Continuing #1");
                                                                    $xml->endElement();//inboundRelationship
                                                                $xml->endElement();
                                                            $xml->endElement();
                                                        $xml->endElement();//observation
                                                    $xml->endElement();//subjectOf2
                                                    $xml->startElement("subjectOf2");
                                                    $xml->writeAttribute('typeCode','SBJ');
                                                        $xml->startElement("observation");
                                                        $xml->writeAttribute('moodCode','EVN');
                                                        $xml->writeAttribute('classCode','OBS');
                                                            $xml->startElement("id");
                                                            $xml->writeAttribute('root','rid-1');
                                                            $xml->endElement();
                                                            $xml->startElement("code");
                                                            $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                            $xml->writeAttribute('code','29');
                                                            $xml->endElement();
                                                            $xml->writeComment("reaction");
                                                            $xml->startElement("effectiveTime");
                                                            $xml->writeAttribute('xsi:type','SXPR_TS');
                                                                $xml->startElement("comp");
                                                                $xml->writeAttribute('xsi:type','IVL_TS');
                                                                    $xml->startElement("low");
                                                                    $xml->writeAttribute('value','20030511');
                                                                    $xml->endElement();
                                                                    $xml->writeComment("E.i.4: Date of Start of Reaction / Event #1");
                                                                    $xml->startElement("width");
                                                                    $xml->writeAttribute('unit','wk');
                                                                    $xml->writeAttribute('value','1');
                                                                    $xml->endElement();
                                                                    $xml->writeComment("E.i.6a: Duration of Reaction / Event (number) #1");
                                                                    $xml->writeComment("E.i.6b: Duration of Reaction / Event (unit) #1");
                                                                $xml->endElement();//comp
                                                                $xml->startElement("comp");
                                                                $xml->writeAttribute('operator','A');
                                                                $xml->writeAttribute('xsi:type','IVL_TS');
                                                                    $xml->startElement("high");
                                                                    $xml->writeAttribute('value','20030518');
                                                                    $xml->endElement();
                                                                    $xml->writeComment("E.i.5: Date of End of Reaction / Event #1");
                                                                $xml->endElement();//comp
                                                            $xml->endElement();//effectiveTime
                                                            $xml->startElement("value");
                                                            $xml->writeAttribute('codeSystemVersion','15.0');
                                                            $xml->writeAttribute('xsi:type','CE');
                                                            $xml->writeAttribute('codeSystem','2.16.840.1.113883.6.163');
                                                            $xml->writeAttribute('code','10016558');
                                                            $xml->writeComment("E.i.2.1a: MedDRA Version for Reaction / Event #1");
                                                            $xml->writeComment("E.i.2.1b: Reaction / Event (MedDRA code) #1");
                                                                $xml->StartElement("originalText"); 
                                                                $xml->WriteAttribute("language", "GB"); 
                                                                $xml->text("fever 38 C");
                                                                $xml->EndElement(); 
                                                                $xml->writeComment("E.i.1.1a: Reaction / Event as Reported by the Primary Source in Native Language #1");
                                                                $xml->writeComment("E.i.1.1b: Reaction / Event as Reported by the Primary Source Language #1");
                                                            $xml->endElement();//value
                                                            $xml->startElement("location");
                                                            $xml->writeAttribute('typeCode','LOC');
                                                                $xml->startElement("locatedEntity");
                                                                $xml->writeAttribute('classCode','LOCE');
                                                                    $xml->startElement("locatedPlace");
                                                                    $xml->writeAttribute('determinerCode','INSTANCE');
                                                                    $xml->writeAttribute('classCode','COUNTRY');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('codeSystem','1.0.3166.1.2.2');
                                                                        $xml->writeAttribute('code','GB');
                                                                        $xml->endElement();
                                                                        $xml->writeComment("E.i.9: Identification of the Country Where the Reaction / Event Occurred #1");
                                                                    $xml->endElement();
                                                                $xml->endElement();//locatedEntity
                                                            $xml->endElement();//location
                                                            $xml->startElement("outboundRelationship2");
                                                            $xml->writeAttribute('typeCode','REFR');
                                                                $xml->startElement("observation");
                                                                $xml->writeAttribute('moodCode','EVN');
                                                                $xml->writeAttribute('classCode','OBS');
                                                                    $xml->startElement("code");
                                                                    $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                    $xml->writeAttribute('code','34');
                                                                    $xml->endElement();
                                                                    $xml->writeComment("resultsInDeath");
                                                                    $xml->startElement("value");
                                                                    $xml->writeAttribute('xsi:type','BL');
                                                                    $xml->writeAttribute('nullFlavor','NI');
                                                                    $xml->endElement();
                                                                    $xml->writeComment("E.i.3.2a: Results in Death #1");
                                                                $xml->endElement();//observation
                                                            $xml->endElement();//outboundRelationship2
                                                            $xml->startElement("outboundRelationship2");
                                                            $xml->writeAttribute('typeCode','REFR');
                                                                $xml->startElement("observation");
                                                                $xml->writeAttribute('moodCode','EVN');
                                                                $xml->writeAttribute('classCode','OBS');
                                                                    $xml->startElement("code");
                                                                    $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                    $xml->writeAttribute('code','27');
                                                                    $xml->endElement();
                                                                    $xml->writeComment("outcome");
                                                                    $xml->startElement("value");
                                                                    $xml->writeAttribute('xsi:type','CE');
                                                                    $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.11');
                                                                    $xml->writeAttribute('code','1');
                                                                    $xml->endElement();
                                                                    $xml->writeComment("E.i.7: Outcome of Reaction / Event at the Time of Last Observation #1");
                                                                $xml->endElement();//observation
                                                            $xml->endElement();//outboundRelationship2
                                                            $xml->startElement("outboundRelationship2");
                                                            $xml->writeAttribute('typeCode','REFR');
                                                                $xml->startElement("observation");
                                                                $xml->writeAttribute('moodCode','EVN');
                                                                $xml->writeAttribute('classCode','OBS');
                                                                    $xml->startElement("code");
                                                                    $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                    $xml->writeAttribute('code','24');
                                                                    $xml->endElement();
                                                                    $xml->writeComment("medicalConfirmationByHealthProfessional");
                                                                    $xml->startElement("value");
                                                                    $xml->writeAttribute('xsi:type','BL');
                                                                    $xml->writeAttribute('value','true');
                                                                    $xml->endElement();
                                                                    $xml->writeComment("E.i.8: Medical Confirmation by Healthcare Professional #1");
                                                                $xml->endElement();//observation
                                                            $xml->endElement();//outboundRelationship2
                                                        $xml->endElement();//observation
                                                    $xml->endElement();//subjectOf2
                                                    $xml->startElement("subjectOf2");
                                                    $xml->writeAttribute('typeCode','SBJ');
                                                        $xml->startElement("observation");
                                                        $xml->writeAttribute('moodCode','EVN');
                                                        $xml->writeAttribute('classCode','OBS');
                                                            $xml->startElement("id");
                                                            $xml->writeAttribute('root','rid-2');
                                                            $xml->endElement();
                                                            $xml->startElement("code");
                                                            $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                            $xml->writeAttribute('code','29');
                                                            $xml->endElement();
                                                            $xml->writeComment("reaction");
                                                            $xml->startElement("effectiveTime");
                                                            $xml->writeAttribute('xsi:type','SXPR_TS');
                                                                $xml->startElement("comp");
                                                                $xml->writeAttribute('xsi:type','IVL_TS');
                                                                    $xml->startElement("low");
                                                                    $xml->writeAttribute('value','20030511');
                                                                    $xml->endElement();
                                                                    $xml->writeComment("E.i.4: Date of Start of Reaction / Event #2");
                                                                    $xml->startElement("width");
                                                                    $xml->writeAttribute('unit','wk');
                                                                    $xml->writeAttribute('value','1');
                                                                    $xml->endElement();
                                                                    $xml->writeComment("E.i.6a: Duration of Reaction / Event (number) #2");
                                                                    $xml->writeComment("E.i.6b: Duration of Reaction / Event (unit) #2");
                                                                $xml->endElement();//comp
                                                                $xml->startElement("comp");
                                                                $xml->writeAttribute('operator','A');
                                                                $xml->writeAttribute('xsi:type','IVL_TS');
                                                                    $xml->startElement("high");
                                                                    $xml->writeAttribute('value','20030518');
                                                                    $xml->endElement();
                                                                    $xml->writeComment("E.i.5: Date of End of Reaction / Event #1");
                                                                $xml->endElement();//comp
                                                            $xml->endElement();//effectiveTime
                                                            $xml->startElement("value");
                                                            $xml->writeAttribute('codeSystemVersion','15.0');
                                                            $xml->writeAttribute('xsi:type','CE');
                                                            $xml->writeAttribute('codeSystem','2.16.840.1.113883.6.163');
                                                            $xml->writeAttribute('code','10012735');
                                                            $xml->writeComment("E.i.2.1a: MedDRA Version for Reaction / Event #2");
                                                            $xml->writeComment("E.i.2.1b: Reaction / Event (MedDRA code) #2");
                                                                $xml->StartElement("originalText"); 
                                                                $xml->WriteAttribute("language", "GB"); 
                                                                $xml->text("diarrhoea");
                                                                $xml->EndElement(); 
                                                                $xml->writeComment("E.i.1.1a: Reaction / Event as Reported by the Primary Source in Native Language #2");
                                                                $xml->writeComment("E.i.1.1b: Reaction / Event as Reported by the Primary Source Language #2");
                                                            $xml->endElement();//value
                                                            $xml->startElement("location");
                                                            $xml->writeAttribute('typeCode','LOC');
                                                                $xml->startElement("locatedEntity");
                                                                $xml->writeAttribute('classCode','LOCE');
                                                                    $xml->startElement("locatedPlace");
                                                                    $xml->writeAttribute('determinerCode','INSTANCE');
                                                                    $xml->writeAttribute('classCode','COUNTRY');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('codeSystem','1.0.3166.1.2.2');
                                                                        $xml->writeAttribute('code','GB');
                                                                        $xml->endElement();
                                                                        $xml->writeComment("E.i.9: Identification of the Country Where the Reaction / Event Occurred #2");
                                                                    $xml->endElement();
                                                                $xml->endElement();//locatedEntity
                                                            $xml->endElement();//location
                                                            $xml->startElement("outboundRelationship2");
                                                            $xml->writeAttribute('typeCode','REFR');
                                                                $xml->startElement("observation");
                                                                $xml->writeAttribute('moodCode','EVN');
                                                                $xml->writeAttribute('classCode','OBS');
                                                                    $xml->startElement("code");
                                                                    $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                    $xml->writeAttribute('code','34');
                                                                    $xml->endElement();
                                                                    $xml->writeComment("resultsInDeath");
                                                                    $xml->startElement("value");
                                                                    $xml->writeAttribute('xsi:type','BL');
                                                                    $xml->writeAttribute('nullFlavor','NI');
                                                                    $xml->endElement();
                                                                    $xml->writeComment("E.i.3.2a: Results in Death #2");
                                                                $xml->endElement();//observation
                                                            $xml->endElement();//outboundRelationship2
                                                            $xml->startElement("outboundRelationship2");
                                                            $xml->writeAttribute('typeCode','REFR');
                                                                $xml->startElement("observation");
                                                                $xml->writeAttribute('moodCode','EVN');
                                                                $xml->writeAttribute('classCode','OBS');
                                                                    $xml->startElement("code");
                                                                    $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                    $xml->writeAttribute('code','27');
                                                                    $xml->endElement();
                                                                    $xml->writeComment("outcome");
                                                                    $xml->startElement("value");
                                                                    $xml->writeAttribute('xsi:type','CE');
                                                                    $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.11');
                                                                    $xml->writeAttribute('code','1');
                                                                    $xml->endElement();
                                                                    $xml->writeComment("E.i.7: Outcome of Reaction / Event at the Time of Last Observation #2");
                                                                $xml->endElement();//observation
                                                            $xml->endElement();//outboundRelationship2
                                                            $xml->startElement("outboundRelationship2");
                                                            $xml->writeAttribute('typeCode','REFR');
                                                                $xml->startElement("observation");
                                                                $xml->writeAttribute('moodCode','EVN');
                                                                $xml->writeAttribute('classCode','OBS');
                                                                    $xml->startElement("code");
                                                                    $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                    $xml->writeAttribute('code','24');
                                                                    $xml->endElement();
                                                                    $xml->writeComment("medicalConfirmationByHealthProfessional");
                                                                    $xml->startElement("value");
                                                                    $xml->writeAttribute('xsi:type','BL');
                                                                    $xml->writeAttribute('value','true');
                                                                    $xml->endElement();
                                                                    $xml->writeComment("E.i.8: Medical Confirmation by Healthcare Professional #2");
                                                                $xml->endElement();//observation
                                                            $xml->endElement();//outboundRelationship2
                                                        $xml->endElement();//observation
                                                    $xml->endElement();//subjectOf2
                                                    $xml->startElement("subjectOf2");
                                                    $xml->writeAttribute('typeCode','SBJ');
                                                        $xml->startElement("observation");
                                                        $xml->writeAttribute('moodCode','EVN');
                                                        $xml->writeAttribute('classCode','OBS');
                                                            $xml->startElement("id");
                                                            $xml->writeAttribute('root','rid-3');
                                                            $xml->endElement();
                                                            $xml->startElement("code");
                                                            $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                            $xml->writeAttribute('code','29');
                                                            $xml->endElement();
                                                            $xml->writeComment("reaction");
                                                            $xml->startElement("effectiveTime");
                                                            $xml->writeAttribute('xsi:type','SXPR_TS');
                                                                $xml->startElement("comp");
                                                                $xml->writeAttribute('xsi:type','IVL_TS');
                                                                    $xml->startElement("low");
                                                                    $xml->writeAttribute('value','20030511');
                                                                    $xml->endElement();
                                                                    $xml->writeComment("E.i.4: Date of Start of Reaction / Event #3");
                                                                    $xml->startElement("width");
                                                                    $xml->writeAttribute('unit','wk');
                                                                    $xml->writeAttribute('value','1');
                                                                    $xml->endElement();
                                                                    $xml->writeComment("E.i.6a: Duration of Reaction / Event (number) #3");
                                                                    $xml->writeComment("E.i.6b: Duration of Reaction / Event (unit) #3");
                                                                $xml->endElement();//comp
                                                                $xml->startElement("comp");
                                                                $xml->writeAttribute('operator','A');
                                                                $xml->writeAttribute('xsi:type','IVL_TS');
                                                                    $xml->startElement("high");
                                                                    $xml->writeAttribute('value','20030518');
                                                                    $xml->endElement();
                                                                    $xml->writeComment("E.i.5: Date of End of Reaction / Event #3");
                                                                $xml->endElement();//comp
                                                            $xml->endElement();//effectiveTime
                                                            $xml->startElement("value");
                                                            $xml->writeAttribute('codeSystemVersion','15.0');
                                                            $xml->writeAttribute('xsi:type','CE');
                                                            $xml->writeAttribute('codeSystem','2.16.840.1.113883.6.163');
                                                            $xml->writeAttribute('code','10029354');
                                                            $xml->writeComment("E.i.2.1a: MedDRA Version for Reaction / Event #3");
                                                            $xml->writeComment("E.i.2.1b: Reaction / Event (MedDRA code) #3");
                                                                $xml->StartElement("originalText"); 
                                                                $xml->WriteAttribute("language", "GB"); 
                                                                $xml->text("neutroprnia");
                                                                $xml->EndElement(); 
                                                                $xml->writeComment("E.i.1.1a: Reaction / Event as Reported by the Primary Source in Native Language #3");
                                                                $xml->writeComment("E.i.1.1b: Reaction / Event as Reported by the Primary Source Language #3");
                                                            $xml->endElement();//value
                                                            $xml->startElement("location");
                                                            $xml->writeAttribute('typeCode','LOC');
                                                                $xml->startElement("locatedEntity");
                                                                $xml->writeAttribute('classCode','LOCE');
                                                                    $xml->startElement("locatedPlace");
                                                                    $xml->writeAttribute('determinerCode','INSTANCE');
                                                                    $xml->writeAttribute('classCode','COUNTRY');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('codeSystem','1.0.3166.1.2.2');
                                                                        $xml->writeAttribute('code','GB');
                                                                        $xml->endElement();
                                                                        $xml->writeComment("E.i.9: Identification of the Country Where the Reaction / Event Occurred #3");
                                                                    $xml->endElement();
                                                                $xml->endElement();//locatedEntity
                                                            $xml->endElement();//location
                                                            $xml->startElement("outboundRelationship2");
                                                            $xml->writeAttribute('typeCode','REFR');
                                                                $xml->startElement("observation");
                                                                $xml->writeAttribute('moodCode','EVN');
                                                                $xml->writeAttribute('classCode','OBS');
                                                                    $xml->startElement("code");
                                                                    $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                    $xml->writeAttribute('code','34');
                                                                    $xml->endElement();
                                                                    $xml->writeComment("resultsInDeath");
                                                                    $xml->startElement("value");
                                                                    $xml->writeAttribute('xsi:type','BL');
                                                                    $xml->writeAttribute('value','true');
                                                                    $xml->endElement();
                                                                    $xml->writeComment("E.i.3.2a: Results in Death #3");
                                                                $xml->endElement();//observation
                                                            $xml->endElement();//outboundRelationship2
                                                            $xml->startElement("outboundRelationship2");
                                                            $xml->writeAttribute('typeCode','REFR');
                                                                $xml->startElement("observation");
                                                                $xml->writeAttribute('moodCode','EVN');
                                                                $xml->writeAttribute('classCode','OBS');
                                                                    $xml->startElement("code");
                                                                    $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                    $xml->writeAttribute('code','33');
                                                                    $xml->endElement();
                                                                    $xml->writeComment("requiresInpatientHospitalization");
                                                                    $xml->startElement("value");
                                                                    $xml->writeAttribute('xsi:type','BL');
                                                                    $xml->writeAttribute('value','true');
                                                                    $xml->endElement();
                                                                    $xml->writeComment("E.i.3.2c: Caused / Prolonged Hospitalisation #3");
                                                                $xml->endElement();//observation
                                                            $xml->endElement();//outboundRelationship2
                                                            $xml->startElement("outboundRelationship2");
                                                            $xml->writeAttribute('typeCode','REFR');
                                                                $xml->startElement("observation");
                                                                $xml->writeAttribute('moodCode','EVN');
                                                                $xml->writeAttribute('classCode','OBS');
                                                                    $xml->startElement("code");
                                                                    $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                    $xml->writeAttribute('code','27');
                                                                    $xml->endElement();
                                                                    $xml->writeComment("outcome");
                                                                    $xml->startElement("value");
                                                                    $xml->writeAttribute('xsi:type','CE');
                                                                    $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.11');
                                                                    $xml->writeAttribute('code','1');
                                                                    $xml->endElement();
                                                                    $xml->writeComment("E.i.7: Outcome of Reaction / Event at the Time of Last Observation #3");
                                                                $xml->endElement();//observation
                                                            $xml->endElement();//outboundRelationship2
                                                            $xml->startElement("outboundRelationship2");
                                                            $xml->writeAttribute('typeCode','REFR');
                                                                $xml->startElement("observation");
                                                                $xml->writeAttribute('moodCode','EVN');
                                                                $xml->writeAttribute('classCode','OBS');
                                                                    $xml->startElement("code");
                                                                    $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                    $xml->writeAttribute('code','24');
                                                                    $xml->endElement();
                                                                    $xml->writeComment("medicalConfirmationByHealthProfessional");
                                                                    $xml->startElement("value");
                                                                    $xml->writeAttribute('xsi:type','BL');
                                                                    $xml->writeAttribute('value','true');
                                                                    $xml->endElement();
                                                                    $xml->writeComment("E.i.8: Medical Confirmation by Healthcare Professional #3");
                                                                $xml->endElement();//observation
                                                            $xml->endElement();//outboundRelationship2
                                                        $xml->endElement();//observation
                                                    $xml->endElement();//subjectOf2
                                                    $xml->startElement("subjectOf2");
                                                    $xml->writeAttribute('typeCode','SBJ');
                                                        $xml->startElement("organizer");
                                                        $xml->writeAttribute('moodCode','EVN');
                                                        $xml->writeAttribute('classCode','GATEGORY');
                                                            $xml->startElement("code");
                                                            $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.20');
                                                            $xml->writeAttribute('code','4');
                                                            $xml->endElement();
                                                            $xml->writeComment("drugInformation");
                                                            $xml->writeComment("G.k Drug(s) Information (repeat as necessary) #1");
                                                            $xml->startElement("component");
                                                            $xml->writeAttribute('typeCode','COMP');
                                                                $xml->startElement("substanceAdministration");
                                                                $xml->writeAttribute('moodCode','EVN');
                                                                $xml->writeAttribute('classCode','SBADM');
                                                                    $xml->startElement("id");
                                                                    $xml->writeAttribute('root','d-id1');
                                                                    $xml->endElement();
                                                                    $xml->startElement("consumable");
                                                                    $xml->writeAttribute('type','CSM');
                                                                        $xml->startElement("instanceOfKind");
                                                                        $xml->writeAttribute('classCode','INST');
                                                                            $xml->startElement("kindOfProduct");
                                                                            $xml->writeAttribute('determinerCode','KIND');
                                                                            $xml->writeAttribute('classCode','MMAT');
                                                                                $xml->writeElement('name','dantium');
                                                                                $xml->writeComment("G.k.2.2: Medicinal Product Name as Reported by the Primary Source #1");
                                                                                $xml->startElement("ingredient");
                                                                                $xml->writeAttribute('classCode','ACTI');
                                                                                    $xml->startElement("quantity");
                                                                                        $xml->startElement("numerator");
                                                                                        $xml->writeAttribute('unit','mg');
                                                                                        $xml->writeAttribute('value','20');    
                                                                                        $xml->endElement();
                                                                                        $xml->writeComment("G.k.2.3.r.3a: Strength (number) #1-1");
                                                                                        $xml->writeComment("G.k.2.3.r.3b: Strength (unit) #1-1");
                                                                                        $xml->startElement("denominator");
                                                                                        $xml->writeAttribute('value','1');   
                                                                                        $xml->endElement();
                                                                                    $xml->endElement();
                                                                                    $xml->startElement("ingredientSubstance");
                                                                                    $xml->writeAttribute('determinerCode','KIND');
                                                                                    $xml->writeAttribute('classCode','MMAT');
                                                                                        $xml->writeElement('name','asmeprazole');
                                                                                        $xml->writeComment("G.k.2.3.r.1: Substance / Specified Substance Name #1-1");
                                                                                    $xml->endElement();
                                                                                $xml->endElement();
                                                                            $xml->endElement();//kindOfProduct
                                                                            $xml->startElement("subjectOf");
                                                                            $xml->writeAttribute('typeCode','SBJ');
                                                                                $xml->startElement("productEvent");
                                                                                $xml->writeAttribute('moodCode','EVN');
                                                                                $xml->writeAttribute('classCode','ACT');
                                                                                    $xml->startElement("code");
                                                                                    $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.18');
                                                                                    $xml->writeAttribute('code','1');
                                                                                    $xml->endElement();
                                                                                    $xml->writeComment("retailSupply");
                                                                                    $xml->startElement("performer");
                                                                                    $xml->writeAttribute('typeCode','PRF');
                                                                                        $xml->startElement("assignedEntity");
                                                                                        $xml->writeAttribute('classCode','ASSIGNED');
                                                                                            $xml->startElement("representedOrganization");
                                                                                            $xml->writeAttribute('determinerCode','INSTANCE');
                                                                                            $xml->writeAttribute('classCode','ORG');
                                                                                                $xml->startElement("addr");
                                                                                                    $xml->writeElement("country","GB");
                                                                                                    $xml->writeComment("G.k.2.4: Identification of the Country Where the Drug Was Obtained #1");
                                                                                                $xml->endElement();
                                                                                            $xml->endElement();
                                                                                        $xml->endElement();
                                                                                    $xml->endElement();
                                                                                $xml->endElement();
                                                                            $xml->endElement();//subjectOf
                                                                        $xml->endElement();//instanceOfKind
                                                                    $xml->endElement();//consumable
                                                                $xml->endElement();//substanceAdministration
                                                            $xml->endElement();//component
                                                            $xml->startElement("outboundRelationship2");
                                                            $xml->writeAttribute('typeCode','COMP');
                                                            $xml->writeComment("G.k.4: Dosage Information (repeat as necessary) #1-1");
                                                                $xml->startElement("substanceAdministration");
                                                                $xml->writeAttribute('moodCode','EVN');
                                                                $xml->writeAttribute('classCode','SBADM');
                                                                    $xml->startElement("effectiveTime");
                                                                    $xml->writeAttribute('xsi:type','SXPR_TS');
                                                                    $xml->startElement("comp");
                                                                    $xml->writeAttribute('xsi:type','IVL_TS');
                                                                        $xml->startElement("low");
                                                                        $xml->writeAttribute('value','20030511');
                                                                        $xml->endElement();
                                                                        $xml->writeComment("E.i.4: Date of Start of Reaction / Event #3");
                                                                        $xml->startElement("width");
                                                                        $xml->writeAttribute('unit','wk');
                                                                        $xml->writeAttribute('value','1');
                                                                        $xml->endElement();
                                                                        $xml->writeComment("E.i.6a: Duration of Reaction / Event (number) #3");
                                                                        $xml->writeComment("E.i.6b: Duration of Reaction / Event (unit) #3");
                                                                    $xml->endElement();//comp
                                                                $xml->endElement();
                                                            $xml->endElement();//outboundRelationship2
                                                            $xml->startElement("outboundRelationship2");
                                                            $xml->writeAttribute('typeCode','REFR');
                                                                $xml->startElement("observation");
                                                                $xml->writeAttribute('moodCode','EVN');
                                                                $xml->writeAttribute('classCode','OBS');
                                                                    $xml->startElement("code");
                                                                    $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                    $xml->writeAttribute('code','27');
                                                                    $xml->endElement();
                                                                    $xml->writeComment("outcome");
                                                                    $xml->startElement("value");
                                                                    $xml->writeAttribute('xsi:type','CE');
                                                                    $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.11');
                                                                    $xml->writeAttribute('code','1');
                                                                    $xml->endElement();
                                                                    $xml->writeComment("E.i.7: Outcome of Reaction / Event at the Time of Last Observation #2");
                                                                $xml->endElement();//observation
                                                            $xml->endElement();//outboundRelationship2
                                                            $xml->startElement("outboundRelationship2");
                                                            $xml->writeAttribute('typeCode','REFR');
                                                                $xml->startElement("observation");
                                                                $xml->writeAttribute('moodCode','EVN');
                                                                $xml->writeAttribute('classCode','OBS');
                                                                    $xml->startElement("code");
                                                                    $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                    $xml->writeAttribute('code','24');
                                                                    $xml->endElement();
                                                                    $xml->writeComment("medicalConfirmationByHealthProfessional");
                                                                    $xml->startElement("value");
                                                                    $xml->writeAttribute('xsi:type','BL');
                                                                    $xml->writeAttribute('value','true');
                                                                    $xml->endElement();
                                                                    $xml->writeComment("E.i.8: Medical Confirmation by Healthcare Professional #2");
                                                                $xml->endElement();//observation
                                                            $xml->endElement();//outboundRelationship2
                                                        $xml->endElement();//organizer
                                                    $xml->endElement();//subjectOf2
                                                $xml->endElement();//primaryRole
                                            $xml->endElement();//SUBJECT1
                                        $xml->endElement();//adverseEventAssessment
                                    $xml->endElement();//component
                                
                                $xml->endElement();
                            $xml->endElement();//subject
                        $xml->endElement();//controlActProcess
                    $xml->endElement();
                $xml->endElement();
            $xml->endElement();
        $xml->endDocument();
        echo $xml->outputMemory();
    }
}
