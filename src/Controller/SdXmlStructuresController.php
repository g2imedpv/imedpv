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
    

    public function genXML($caseId){   
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
}
