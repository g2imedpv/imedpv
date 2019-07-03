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
    public function genXMLThree($caseId){   
        $this->autoRender = false;
        //set file name with caseNo and create time
        $sdCases = TableRegistry::get('sdCases');
        $name=$sdCases->find()
                ->select(['caseNo'])
                ->where(['id='.$caseId,'status=1'])->first();
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
        if($this->XMLvalue($caseId,22,1)==1){
            $xml->startElement("MCCI_IN200100UV01");
            $xml->writeAttribute('ITSVersion','XML_1.0');
            $xml->writeAttribute('xsi:schemaLocation','urn:hl7-org:v3 MCCI_IN200100UV01.xsd');
            $xml->writeAttribute('xmlns','urn:hl7-org:v3');
            $xml->writeAttributeNS('xmlns','xsi', null,'http://www.w3.org/2001/XMLSchema-instance');
                $xml->startElement("id");
                    $xml->writeAttribute('extension',$this->XMLvalue($caseId,1052,1));
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
                $xml->endElement();
                $xml->writeComment(" N.1.1: Type of Messages in Batch ");
                $xml->writeComment(" Message #1 ");
                $xml->startElement("PORR_IN049016UV");
                    $xml->startElement("id");
                        $xml->writeAttribute('extension',$this->XMLvalue($caseId,1056,1));
                        $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.1');
                    $xml->endElement();
                    $xml->writeComment(" N.2.r.1: Message Identifier ");
                    $xml->startElement("creationTime");
                        $xml->writeAttribute('value',$this->XMLvalue($caseId,1059,1));
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
                            $xml->writeAttribute('extension',$this->XMLvalue($caseId,1058,1));
                            $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.12');
                            $xml->endElement();
                            $xml->writeComment(" N.2.r.3: Message Receiver Identifier ");
                        $xml->endElement();
                    $xml->endElement();
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
                        $xml->endElement();
                    $xml->endElement();
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
                                $xml->startElement("reference");
                                $xml->writeAttribute('typeCode','REFR');
                                    $xml->startElement("document");
                                    $xml->writeAttribute('classCode','DOC');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','2');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.27');
                                        $xml->endElement();
                                        $xml->startElement("text");
                                        $xml->writeAttribute('mediaType',$this->XMLvalue($caseId,1002,1));
                                        $xml->writeAttribute('representation','B64');
                                        $xml->text($this->XMLvalue($caseId,1002,1));
                                        $xml->endElement();
                                        $xml->writeComment(' C.4.r.2: Included Documents ');
                                        $xml->writeElement("bibliographicDesignationText",$this->XMLvalue($caseId,37,1));
                                        $xml->writeComment(' C.4.r.1: Literature Reference(s) ');
                                    $xml->endElement();//document
                                $xml->endElement();//reference
                                $xml->startElement("component");
                                $xml->writeAttribute('typeCode','COMP');
                                    $xml->startElement("adverseEventAssessment");
                                    $xml->writeAttribute('classCode','INVSTG');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("subject1");
                                        $xml->writeAttribute('typeCode','SBJ');
                                            $xml->startElement("primaryRole");
                                            $xml->writeAttribute('classCode','INVSBJ');
                                                $xml->startElement("player1");
                                                $xml->writeAttribute('classCode','PSN');
                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                    $xml->startElement("name");
                                                    $xml->writeAttribute('nullFlavor','MSK');//$this->XMLvalue($caseId,79,1)
                                                    $xml->endElement();
                                                    $xml->writeComment(" D.1: Patient (name or initials) ");
                                                    $xml->startElement("administrativeGenderCode");
                                                    $xml->writeAttribute('code',$this->XMLvalue($caseId,93,1));
                                                    $xml->writeAttribute('codeSystem','1.0.5218');
                                                    $xml->endElement();
                                                    $xml->writeComment(" D.5 Sex ");
                                                    $xml->startElement("birthTime");
                                                    $xml->writeAttribute('value',$this->XMLvalue($caseId,93,1));
                                                    $xml->endElement();
                                                    $xml->writeComment(" D.2.1: Date of Birth ");
                                                $xml->endElement();//player1
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("observation");
                                                    $xml->writeAttribute('classCode','OBS');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','3');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
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
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("observation");
                                                    $xml->writeAttribute('classCode','OBS');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','7');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                        $xml->endElement();
                                                        $xml->startElement("value");
                                                        $xml->writeAttribute('xsi:type','PQ');
                                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,91,1));
                                                        $xml->writeAttribute('unit','kg');
                                                        $xml->endElement();
                                                        $xml->writeComment(" D.3: Body Weight (kg) ");
                                                    $xml->endElement();//observation
                                                $xml->endElement();//subjectOf2
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("observation");
                                                    $xml->writeAttribute('classCode','OBS');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','17');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                        $xml->endElement();
                                                        $xml->startElement("value");
                                                        $xml->writeAttribute('xsi:type','PQ');
                                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,92,1));
                                                        $xml->writeAttribute('unit','cm');
                                                        $xml->endElement();
                                                        $xml->writeComment(" D.4: Height (cm) ");
                                                    $xml->endElement();//observation
                                                $xml->endElement();//subjectOf2
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
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("organizer");
                                                    $xml->writeAttribute('classCode','CATEGORY');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','1');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.20');
                                                        $xml->endElement();
                                                        $xml->startElement("component");
                                                        $xml->writeAttribute('typeCode','COMP');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,97,1));
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.6.163');
                                                                $xml->writeAttribute('codeSystemVersion','15.0');
                                                                $xml->endElement();
                                                                $xml->writeComment(" D.7.1.r.1a: MedDRA Version for Medical History ");
                                                                $xml->writeComment(" D.7.1.r.1b: Medical History (disease / surgical procedure / etc.) (MedDRA code) ");
                                                                $xml->startElement("effectiveTime");
                                                                $xml->writeAttribute('xsi:type','IVL_TS');
                                                                    $xml->startElement("low");
                                                                    $xml->writeAttribute('value',$this->XMLvalue($caseId,99,1));
                                                                    $xml->endElement();
                                                                    $xml->writeComment(" D.7.1.r.2: Start Date ");
                                                                $xml->endElement();
                                                                $xml->startElement("inboundRelationship");
                                                                $xml->writeAttribute('typeCode','REFR');
                                                                    $xml->startElement("observation");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code','13');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                        $xml->endElement();
                                                                        $xml->startElement("value");
                                                                        $xml->writeAttribute('xsi:type','BL');
                                                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,100,1));
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" D.7.1.r.3: Continuing ");
                                                                    $xml->endElement();//observation
                                                                $xml->endElement();//inboundRelationship
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//component 
                                                    $xml->endElement();//organizer
                                                $xml->endElement();//subjectOf2
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
                                                        $xml->endElement();
                                                        $xml->startElement("effectiveTime");
                                                        $xml->writeAttribute('xsi:type','SXPR_TS');
                                                            $xml->startElement("comp");
                                                            $xml->writeAttribute('xsi:type','IVL_TS');
                                                                $xml->startElement("low");
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,156,1));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.4: Date of Start of Reaction / Event "); 
                                                                $xml->startElement("high");
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,158,1));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.5: Date of End of Reaction / Event "); 
                                                            $xml->endElement();//comp
                                                            $xml->startElement("comp");
                                                            $xml->writeAttribute('xsi:type','IVL_TS');
                                                            $xml->writeAttribute('operator','A');
                                                                $xml->startElement("width");
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,159,1));
                                                                $xml->writeAttribute('unit',$this->XMLvalue($caseId,160,1));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.6a: Duration of Reaction / Event (number) "); 
                                                                $xml->writeComment(" E.i.6b: Duration of Reaction / Event (unit) "); 
                                                            $xml->endElement();//comp
                                                        $xml->endElement();//effectiveTime
                                                        $xml->startElement("value");
                                                        $xml->writeAttribute('xsi:type','CE');
                                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,151,1));
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.6.163');
                                                        $xml->writeAttribute('codeSystemVersion','15.0');
                                                        $xml->writeComment(" E.i.2.1a: MedDRA Version for Reaction / Event ");
                                                        $xml->writeComment(" E.i.2.1b: Reaction / Event (MedDRA code) ");
                                                            $xml->startElement("originalText");
                                                            $xml->writeAttribute('language',$this->XMLvalue($caseId,1017,1));
                                                            $xml->text($this->XMLvalue($caseId,149,1));
                                                            $xml->writeComment(" E.i.1.1a: Reaction / Event as Reported by the Primary Source in Native Language ");
                                                            $xml->writeComment(" E.i.1.1b: Reaction / Event as Reported by the Primary Source Language ");
                                                            $xml->endElement();
                                                        $xml->endElement();//value
                                                        $xml->startElement("location");
                                                        $xml->writeAttribute('typeCode','LOC');
                                                            $xml->startElement("locatedEntity");
                                                            $xml->writeAttribute('classCode','LOCE');
                                                                $xml->startElement("locatedPlace");
                                                                $xml->writeAttribute('classCode','COUNTRY');
                                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                                    $xml->startElement("code");
                                                                    $xml->writeAttribute('code',$this->XMLvalue($caseId,1026,1));
                                                                    $xml->writeAttribute('codeSystem','1.0.3166.1.2.2');
                                                                    $xml->endElement();
                                                                    $xml->writeComment(" E.i.9: Identification of the Country Where the Reaction / Event Occurred ");
                                                                $xml->endElement();
                                                            $xml->endElement();//locatedEntity
                                                        $xml->endElement();//location
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','PERT');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','30');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','ED');
                                                                $xml->text($this->XMLvalue($caseId,1018,1));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.1.2: Reaction / Event as Reported by the Primary Source for Translation ");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','PERT');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','37');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','CE');
                                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,154,1));
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.10');
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.3.1: Term Highlighted by the Reporter ");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','PERT');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','34');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','BL');
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,1019,1));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.3.2a: Results in Death ");
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
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','BL');
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,1020,1));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.3.2b: Life Threatening ");
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
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','BL');
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,1021,1));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.3.2c: Caused / Prolonged Hospitalisation ");
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
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','BL');
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,1022,1));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.3.2d: Disabling / Incapacitating ");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','REFR');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','12');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','BL');
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,1023,1));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.3.2e: Congenital Anomaly / Birth Defect ");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','REFR');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','26');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','BL');
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,1024,1));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.3.2f: Other Medically Important Condition ");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','REFR');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','27');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','CE');
                                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,165,1));
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.11');
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.7: Outcome of Reaction / Event at the Time of Last Observation ");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                    $xml->endElement();//observation
                                                $xml->endElement();//subjectOf2
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("organizer");
                                                    $xml->writeAttribute('classCode','CATEGORY');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','3');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.20');
                                                        $xml->endElement();
                                                        $xml->startElement("component");
                                                        $xml->writeAttribute('typeCode','COMP');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,1028,1));
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.6.163');
                                                                $xml->writeAttribute('codeSystemVersion','15.0');
                                                                $xml->writeComment(" F.r.2.2a: MedDRA Version for Test Name #1 ");
                                                                $xml->writeComment(" F.r.2.2b: Test Name (MedDRA code) #1 ");
                                                                    $xml->writeElement('originalText',$this->XMLvalue($caseId,168,1));
                                                                    $xml->writeComment(" F.r.2.1: Test Name (free text) #1 ");
                                                                $xml->endElement();//code
                                                                $xml->startElement("effectiveTime");
                                                                $xml->writeAttribute('value ',$this->XMLvalue($caseId,167,1));
                                                                $xml->endElement();//effectiveTime
                                                                $xml->writeComment(" F.r.1: Test Date #1 ");
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','IVL_PQ');
                                                                    $xml->startElement("center");
                                                                    $xml->writeAttribute('value',$this->XMLvalue($caseId,1030,1));
                                                                    $xml->writeAttribute('unit',$this->XMLvalue($caseId,170,1));
                                                                    $xml->writeComment(" F.r.3.2: Test Result (value / qualifier) #1 ");
                                                                    $xml->writeComment(" F.r.3.3: Test Result (unit) #1 ");
                                                                    $xml->endElement();
                                                                $xml->endElement();
                                                                $xml->startElement("referenceRange");
                                                                $xml->writeAttribute('typeCode','REFV');
                                                                    $xml->startElement("observationRange");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN.CRT');
                                                                        $xml->startElement("value");
                                                                        $xml->writeAttribute('xsi:type','PQ');
                                                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,172,1));
                                                                        $xml->writeAttribute('unit','mg/dl');
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" F.r.5:  Normal High Value #1 ");
                                                                        $xml->startElement("interpretationCode");
                                                                        $xml->writeAttribute('code','H');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.5.83');
                                                                        $xml->endElement();
                                                                    $xml->endElement();//observationRange
                                                                $xml->endElement();//referenceRange
                                                                $xml->startElement("outboundRelationship2");
                                                                $xml->writeAttribute('typeCode','REFR');
                                                                    $xml->startElement("observation");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code','25');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                        $xml->endElement();
                                                                        $xml->startElement("value");
                                                                        $xml->writeAttribute('xsi:type','BL');
                                                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,173,1));
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" F.r.7: More Information Available #1 ");
                                                                    $xml->endElement();//observation
                                                                $xml->endElement();//outboundRelationship2
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//component
                                                        $xml->startElement("component");
                                                        $xml->writeAttribute('typeCode','COMP');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.6.163');
                                                                    $xml->writeElement('originalText',$this->XMLvalue($caseId,168,1));
                                                                    $xml->writeComment(" F.r.2.1: Test Name (free text) #2 ");
                                                                $xml->endElement();
                                                                $xml->startElement("effectiveTime");
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,167,1));
                                                                $xml->endElement();//effectiveTime
                                                                $xml->writeComment(" F.r.1: Test Date #2 ");
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','ED');
                                                                $xml->text($this->XMLvalue($caseId,169,1));
                                                                $xml->endElement();//value
                                                                $xml->writeComment(" F.r.3.4: Result Unstructured Data (free text) #2 ");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//component
                                                    $xml->endElement();//organizer
                                                $xml->endElement();//subjectOf2
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("organizer");
                                                    $xml->writeAttribute('classCode','CATEGORY');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','4');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.20');
                                                        $xml->endElement();
                                                        $xml->writeComment(" G.k Drug(s) Information (repeat as necessary) ");
                                                        $xml->startElement("component");
                                                        $xml->writeAttribute('typeCode','COMP');
                                                            $xml->startElement("substanceAdministration");
                                                            $xml->writeAttribute('classCode','SBADM');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("id");
                                                                $xml->writeAttribute('root','3c91b4d5-e039-4a7a-9c30-67671b0ef9e4');
                                                                $xml->endElement();
                                                                $xml->startElement("consumable");
                                                                $xml->writeAttribute('typeCode','CSM');
                                                                    $xml->startElement("instanceOfKind");
                                                                    $xml->writeAttribute('classCode','INST');
                                                                        $xml->startElement("kindOfProduct");
                                                                        $xml->writeAttribute('classCode','MMAT');
                                                                        $xml->writeAttribute('determinerCode','KIND');
                                                                            $xml->startElement("code");
                                                                            $xml->writeAttribute('code',$this->XMLvalue($caseId,1033,1));
                                                                            $xml->writeAttribute('codeSystem','TBD-MPID');
                                                                            $xml->writeAttribute('codeSystemVersion',$this->XMLvalue($caseId,1032,1));
                                                                            $xml->endElement();
                                                                            $xml->writeComment(" Example MPID and Version ");
                                                                            $xml->writeComment(" G.k.2.1.1a: MPID Version Date / Number ");
                                                                            $xml->writeComment(" G.k.2.1.1b: Medicinal Product Identifier (MPID) ");
                                                                            $xml->writeElement('name',$this->XMLvalue($caseId,176,1));
                                                                            $xml->writeComment(" G.k.2.2: Medicinal Product Name as Reported by the Primary Source ");
                                                                            $xml->startElement("asManufacturedProduct");
                                                                            $xml->writeAttribute('classCode','MANU');
                                                                                $xml->startElement("subjectOf");
                                                                                $xml->writeAttribute('typeCode','SBJ');
                                                                                    $xml->startElement("approval");
                                                                                    $xml->writeAttribute('classCode','CNTRCT');
                                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                                        $xml->startElement("id");
                                                                                        $xml->writeAttribute('extension',$this->XMLvalue($caseId,180,1));
                                                                                        $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.4');
                                                                                        $xml->endElement();
                                                                                        $xml->writeComment(" G.k.3.1: Authorization / Application Number ");
                                                                                        $xml->startElement("holder");
                                                                                        $xml->writeAttribute('typeCode','HLD');
                                                                                            $xml->startElement("role");
                                                                                            $xml->writeAttribute('classCode','HLD');
                                                                                                $xml->startElement("playingOrganization");
                                                                                                $xml->writeAttribute('classCode','ORG');
                                                                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                                                                    $xml->writeElement('name',$this->XMLvalue($caseId,182,1));
                                                                                                    $xml->writeComment(" G.k.3.3: Name of Holder / Applicant ");
                                                                                                $xml->endElement();//playingOrganization
                                                                                            $xml->endElement();//role
                                                                                        $xml->endElement();//holder
                                                                                        $xml->startElement("author");
                                                                                        $xml->writeAttribute('typeCode','AUT');
                                                                                            $xml->startElement("territorialAuthority");
                                                                                            $xml->writeAttribute('classCode','TERR');
                                                                                                $xml->startElement("territory");
                                                                                                $xml->writeAttribute('classCode','NAT');
                                                                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                                                                    $xml->startElement("code");
                                                                                                    $xml->writeAttribute('code',$this->XMLvalue($caseId,181,1));
                                                                                                    $xml->writeAttribute('codeSystem','1.0.3166.1.2.2');
                                                                                                    $xml->endElement();
                                                                                                    $xml->writeComment(" G.k.3.2: Country of Authorization / Application ");
                                                                                                $xml->endElement();//territory
                                                                                            $xml->endElement();//territorialAuthority
                                                                                        $xml->endElement();//author
                                                                                    $xml->endElement();//approval
                                                                                $xml->endElement();//subjectOf
                                                                            $xml->endElement();//asManufacturedProduct
                                                                            $xml->startElement("ingredient");
                                                                            $xml->writeAttribute('classCode','ACTI');
                                                                                $xml->startElement("quantity");
                                                                                    $xml->startElement("numerator");
                                                                                    $xml->writeAttribute('value',$this->XMLvalue($caseId,1038,1));
                                                                                    $xml->writeAttribute('unit',$this->XMLvalue($caseId,1039,1));
                                                                                    $xml->writeComment(" G.k.2.3.r.3a: Strength (number) ");
                                                                                    $xml->writeComment(" G.k.2.3.r.3b: Strength (unit) ");
                                                                                    $xml->endElement();//numerator
                                                                                    $xml->startElement("denominator");
                                                                                    $xml->writeAttribute('value','1');
                                                                                    $xml->endElement();
                                                                                $xml->endElement();//quantity
                                                                                $xml->startElement("ingredientSubstance");
                                                                                $xml->writeAttribute('classCode','MMAT');
                                                                                $xml->writeAttribute('determinerCode','KIND');
                                                                                    $xml->startElement("code");
                                                                                    $xml->writeAttribute('code',$this->XMLvalue($caseId,1037,1));
                                                                                    $xml->writeAttribute('codeSystem','TBD-Substance');
                                                                                    $xml->writeAttribute('codeSystemVersion',$this->XMLvalue($caseId,1036,1));
                                                                                    $xml->endElement();
                                                                                    $xml->writeComment(" Example Substance ID and Version ");
                                                                                    $xml->writeComment(" G.k.2.3.r.2a Substance / Specified Substance TermID Version Date/Number ");
                                                                                    $xml->writeComment(" G.k.2.3.r.2b: Substance / Specified Substance TermID ");
                                                                                    $xml->writeElement('name',$this->XMLvalue($caseId,177,1));
                                                                                    $xml->writeComment(" G.k.2.3.r.1: Substance / Specified Substance Name ");
                                                                                $xml->endElement();//ingredientSubstance
                                                                            $xml->endElement();//ingredient
                                                                        $xml->endElement();//kindOfProduct
                                                                        $xml->startElement("subjectOf");
                                                                        $xml->writeAttribute('typeCode','SBJ');
                                                                            $xml->startElement("productEvent");
                                                                            $xml->writeAttribute('classCode','ACT');
                                                                            $xml->writeAttribute('moodCode','EVN');
                                                                                $xml->startElement("code");
                                                                                $xml->writeAttribute('code','1');
                                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.18');
                                                                                $xml->endElement();
                                                                                $xml->startElement("performer");
                                                                                $xml->writeAttribute('typeCode','PRF');
                                                                                    $xml->startElement("assignedEntity");
                                                                                    $xml->writeAttribute('classCode','ASSIGNED');
                                                                                        $xml->startElement("representedOrganization");
                                                                                        $xml->writeAttribute('classCode','ORG');
                                                                                        $xml->writeAttribute('determinerCode','INSTANCE');
                                                                                            $xml->startElement("addr");
                                                                                                $xml->writeElement("country",$this->XMLvalue($caseId,178,1));
                                                                                                $xml->writeComment(" G.k.2.4: Identification of the Country Where the Drug Was Obtained ");
                                                                                            $xml->endElement();
                                                                                        $xml->endElement();
                                                                                    $xml->endElement();
                                                                                $xml->endElement();
                                                                            $xml->endElement();
                                                                        $xml->endElement();//subjectOf
                                                                    $xml->endElement();//instanceOfKind
                                                                $xml->endElement();//consumable
                                                                $xml->startElement("outboundRelationship2");
                                                                $xml->writeAttribute('typeCode','COMP');
                                                                $xml->writeComment(" G.k.4.r: Dosage and Relevant Information (repeat as necessary) ");
                                                                    $xml->startElement("substanceAdministration");
                                                                    $xml->writeAttribute('classCode','SBADM');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("effectiveTime");
                                                                        $xml->writeAttribute('xsi:type','SXPR_TS');
                                                                            $xml->startElement("comp");
                                                                            $xml->writeAttribute('xsi:type','PIVL_TS');
                                                                                $xml->startElement("period");
                                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,186,1));
                                                                                $xml->writeAttribute('unit',$this->XMLvalue($caseId,187,1));
                                                                                $xml->endElement();
                                                                                $xml->writeComment(" G.k.4.r.2: Number of Units in the Interval ");
                                                                                $xml->writeComment(" G.k.4.r.3: Definition of the Time Interval Unit ");
                                                                            $xml->endElement();//comp
                                                                            $xml->startElement("comp");
                                                                            $xml->writeAttribute('xsi:type','IVL_TS');
                                                                            $xml->writeAttribute('operator','A');
                                                                                $xml->startElement("low");
                                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,199,1));
                                                                                $xml->endElement();
                                                                                $xml->writeComment(" G.k.4.r.4: Date and Time of Start of Drug ");
                                                                                $xml->startElement("high");
                                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,205,1));
                                                                                $xml->endElement();
                                                                                $xml->writeComment(" G.k.4.r.5: Date and Time of Last Administration ");
                                                                            $xml->endElement();//comp
                                                                        $xml->endElement();//effectiveTime
                                                                        $xml->startElement("routeCode");
                                                                        $xml->writeAttribute('code','048');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.14');
                                                                        $xml->writeComment(" G.k.4.r.10.2b: Route of Administration TermID ");
                                                                            $xml->writeElement("originalText",$this->XMLvalue($caseId,183,1));
                                                                        $xml->endElement();//routeCode
                                                                        $xml->startElement("doesQuantity");
                                                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,183,1));
                                                                            $xml->writeAttribute('unit',$this->XMLvalue($caseId,184,1));
                                                                        $xml->endElement();//doesQuantity
                                                                        $xml->writeComment(" G.k.4.r.1a Dose (number) ");
                                                                        $xml->writeComment(" G.k.4.r.1b: Dose (unit) ");
                                                                        $xml->startElement("consumable");
                                                                        $xml->writeAttribute('type','CSM');
                                                                            $xml->startElement("instanceOfKind");
                                                                            $xml->writeAttribute('classCode','INST');
                                                                                $xml->startElement("kindOfProduct");
                                                                                $xml->writeAttribute('classCode','MMAT');
                                                                                $xml->writeAttribute('determinerCode','KIND');
                                                                                    $xml->startElement("formCode");
                                                                                        $xml->writeElement('originalText',$this->XMLvalue($caseId,191,1));
                                                                                        $xml->writeComment(" G.k.4.r.9.1: Pharmaceutical Dose Form (free text) ");
                                                                                    $xml->endElement();//formCode
                                                                                $xml->endElement();//kindOfProduct
                                                                            $xml->endElement();//instanceOfKind
                                                                        $xml->endElement();//consumable
                                                                    $xml->endElement();//substanceAdministration
                                                                $xml->endElement();//outboundRelationship2
                                                                $xml->startElement("outboundRelationship2");
                                                                $xml->writeAttribute('typeCode','PERT');
                                                                    $xml->startElement("observation");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code','31');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                        $xml->endElement();
                                                                        $xml->startElement("value");
                                                                        $xml->writeAttribute('xsi:type','CE');
                                                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,209,1));
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.16');
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" G.k.9.i.4: Did Reaction Recur on Re-administration? ");
                                                                        $xml->startElement("outboundRelationship1");
                                                                        $xml->writeAttribute('typeCode','REFR');
                                                                            $xml->startElement("actReference");
                                                                            $xml->writeAttribute('classCode','OBS');
                                                                            $xml->writeAttribute('moodCode','EVN');
                                                                                $xml->startElement("id");
                                                                                $xml->writeAttribute('root','154eb889-958b-45f2-a02f-42d4d6f4657f');
                                                                                $xml->endElement();
                                                                            $xml->endElement();//actReference
                                                                        $xml->endElement();//outboundRelationship1
                                                                    $xml->endElement();//observation
                                                                $xml->endElement();//outboundRelationship2        
                                                                $xml->startElement("inboundRelationship");
                                                                $xml->writeAttribute('typeCode','RSON');
                                                                    $xml->startElement("observation");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code','19');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                        $xml->endElement();
                                                                        $xml->startElement("value");
                                                                        $xml->writeAttribute('xsi:type','CE');
                                                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,196,1));
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.6.163');
                                                                        $xml->writeAttribute('codeSystemVersion','15.0');
                                                                        $xml->writeComment(" G.k.7.r.2a: MedDRA Version for Indication ");
                                                                        $xml->writeComment(" G.k.7.r.2b: Indication (MedDRA code) ");
                                                                            $xml->writeElement('originalText',$this->XMLvalue($caseId,1047,1));
                                                                            $xml->writeComment(" G.k.7.r.1: Indication as Reported by the Primary Source ");
                                                                        $xml->endElement();
                                                                        $xml->startElement("performer");
                                                                        $xml->writeAttribute('typeCode','PRF');
                                                                            $xml->startElement("assignedEntity");
                                                                            $xml->writeAttribute('classCode','ASSIGNED');
                                                                                $xml->startElement("code");
                                                                                $xml->writeAttribute('code','3');
                                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.21');
                                                                                $xml->endElement();
                                                                            $xml->endElement();
                                                                        $xml->endElement();//performer
                                                                    $xml->endElement();//observation       
                                                                $xml->endElement();//inboundRelationship
                                                                $xml->startElement("inboundRelationship");
                                                                $xml->writeAttribute('typeCode','CAUS');
                                                                    $xml->startElement("act");
                                                                    $xml->writeAttribute('classCode','ACT');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,208,1));
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.15');
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" G.k.8: Action(s) Taken with Drug ");
                                                                    $xml->endElement();//act       
                                                                $xml->endElement();//inboundRelationship
                                                            $xml->endElement();//substanceAdministration
                                                        $xml->endElement();//component
                                                    $xml->endElement();//organizer
                                                $xml->endElement();//subjectOf2
                                            $xml->endElement();//primaryRole
                                        $xml->endElement();//SUBJECT1
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
                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,175,2));
                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.13');
                                                $xml->endElement();
                                                $xml->writeComment(" G.k.1: Characterisation of Drug Role ");
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
                                        $xml->startElement("component");
                                        $xml->writeAttribute('typeCode','COMP');
                                            $xml->startElement("causalityAssessment");
                                            $xml->writeAttribute('classCode','OBS');
                                            $xml->writeAttribute('moodCode','EVN');
                                                $xml->startElement("code");
                                                $xml->writeAttribute('code','39');
                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                $xml->endElement();
                                                $xml->startElement("value");
                                                $xml->writeAttribute('xsi:type','ST');
                                                $xml->text($this->XMLvalue($caseId,216,2));
                                                $xml->endElement();
                                                $xml->writeComment(" G.k.9.i.2.r.3: Result of Assessment ");
                                                $xml->startElement("methodCode");
                                                $xml->writeElement('originalText',$this->XMLvalue($caseId,215,2));
                                                $xml->writeComment(" G.k.9.i.2.r.2: Method of Assessment ");
                                                $xml->endElement();//methodCode
                                                $xml->startElement("author");
                                                    $xml->writeAttribute('typeCode','AUT');
                                                    $xml->startElement("assignedEntity");
                                                    $xml->writeAttribute('classCode','ASSIGNED');
                                                        $xml->startElement("code");
                                                        $xml->writeElement('originalText',$this->XMLvalue($caseId,214,2));
                                                        $xml->writeComment(" G.k.9.i.2.r.1: Source of Assessment ");
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
                                        $xml->startElement("component1");
                                        $xml->writeAttribute('typeCode','COMP');
                                            $xml->startElement("observationEvent");
                                            $xml->writeAttribute('classCode','OBS');
                                            $xml->writeAttribute('moodCode','EVN');
                                                $xml->startElement("code");
                                                $xml->writeAttribute('code','10');
                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                $xml->endElement();
                                                $xml->startElement("value");
                                                $xml->writeAttribute('xsi:type','ED');
                                                $xml->text($this->XMLvalue($caseId,219,2));
                                                $xml->endElement();
                                                $xml->writeComment("  H.2: Reporter's Comments ");
                                                $xml->startElement("author");
                                                    $xml->writeAttribute('typeCode','AUT');
                                                    $xml->startElement("assignedEntity");
                                                    $xml->writeAttribute('classCode','ASSIGNED');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','3');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.21');
                                                        $xml->endElement();//code
                                                    $xml->endElement();//assignedEntity
                                                $xml->endElement();//author
                                            $xml->endElement();//observationEvent
                                        $xml->endElement();//component1
                                        $xml->startElement("component1");
                                        $xml->writeAttribute('typeCode','COMP');
                                            $xml->startElement("observationEvent");
                                            $xml->writeAttribute('classCode','OBS');
                                            $xml->writeAttribute('moodCode','EVN');
                                                $xml->startElement("code");
                                                $xml->writeAttribute('code','10');
                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                $xml->endElement();
                                                $xml->startElement("value");
                                                $xml->writeAttribute('xsi:type','ED');
                                                $xml->text($this->XMLvalue($caseId,222,2));
                                                $xml->endElement();
                                                $xml->writeComment("  H.4: Sender's Comments ");
                                                $xml->startElement("author");
                                                    $xml->writeAttribute('typeCode','AUT');
                                                    $xml->startElement("assignedEntity");
                                                    $xml->writeAttribute('classCode','ASSIGNED');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','1');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.21');
                                                        $xml->endElement();//code
                                                    $xml->endElement();//assignedEntity
                                                $xml->endElement();//author
                                            $xml->endElement();//observationEvent
                                        $xml->endElement();//component1
                                    $xml->endElement();//adverseEventAssessment
                                $xml->endElement();//component
                                $xml->startElement("component");
                                $xml->writeAttribute('typeCode','COMP');
                                    $xml->startElement("observationEvent");
                                    $xml->writeAttribute('classCode','OBS');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','1');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                        $xml->endElement();
                                        $xml->startElement("value");
                                        $xml->writeAttribute('xsi:type','BL');
                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,13,1));
                                        $xml->writeComment(' C.1.6.1: Are Additional Documents Available? ');
                                        $xml->endElement();
                                    $xml->endElement();//observationEvent
                                $xml->endElement();//component
                                $xml->startElement("component");
                                $xml->writeAttribute('typeCode','COMP');
                                    $xml->startElement("observationEvent");
                                    $xml->writeAttribute('classCode','OBS');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','23');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                        $xml->endElement();
                                        $xml->startElement("value");
                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,15,1));
                                        $xml->writeAttribute('xsi:type','BL');
                                        $xml->writeComment(' C.1.7: Does This Case Fulfill the Local Criteria for an Expedited Report? ');
                                        $xml->endElement();
                                    $xml->endElement();//observationEvent
                                $xml->endElement();//component
                                $xml->startElement("component");
                                $xml->writeAttribute('typeCode','COMP');
                                    $xml->startElement("observationEvent");
                                    $xml->writeAttribute('classCode','OBS');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','36');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                        $xml->endElement();
                                        $xml->startElement("value");
                                        $xml->writeAttribute('xsi:type','ED');
                                        $xml->writeAttribute('language',$this->XMLvalue($caseId,1050,1));
                                        $xml->text($this->XMLvalue($caseId,1049,1));
                                        $xml->writeComment(" H.5.r.1a: Case Summary and Reporter's Comments Text ");
                                        $xml->writeComment(" H.5.r.1b: Case Summary and Reporter's Comments Language ");
                                        $xml->endElement();
                                        $xml->startElement("author");
                                        $xml->writeAttribute('typeCode','AUT');
                                            $xml->startElement("assignedEntity");
                                            $xml->writeAttribute('classCode','ASSIGNED');
                                                $xml->startElement("code");
                                                $xml->writeAttribute('code','2');
                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.21');
                                                $xml->endElement();
                                            $xml->endElement();//assignedEntity
                                        $xml->endElement();//author
                                    $xml->endElement();//observationEvent
                                $xml->endElement();//component
                                $xml->startElement("outboundRelationship");
                                $xml->writeAttribute('typeCode','SPRT');
                                    $xml->startElement("relatedInvestigation");
                                    $xml->writeAttribute('classCode','INVSTG');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','1');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.22');
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
                                                        $xml->endElement();//code
                                                        $xml->writeComment(" C.1.8.2: First Sender of This Case ");
                                                    $xml->endElement();//assignedEntity
                                                $xml->endElement();//author
                                            $xml->endElement();//controlActEvent
                                        $xml->endElement();//subjectOf2
                                    $xml->endElement();//observation
                                $xml->endElement();//outboundRelationship
                                $xml->startElement("outboundRelationship");
                                $xml->writeAttribute('typeCode','SPRT');
                                    $xml->startElement("priorityNumber");
                                    $xml->writeAttribute('value',$this->XMLvalue($caseId,227,1));
                                    $xml->endElement();
                                    $xml->writeComment(' C.2.r.5: Primary Source for Regulatory Purposes ');
                                    $xml->startElement("relatedInvestigation");
                                    $xml->writeAttribute('classCode','INVSTG');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','2');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.22');
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
                                                            $xml->writeElement("streetAddressLine",$this->XMLvalue($caseId,31,1));
                                                            $xml->writeComment(" C.2.r.2.3: Reporter's Street #1 ");
                                                            $xml->writeElement("city",$this->XMLvalue($caseId,32,1));
                                                            $xml->writeComment(" C.2.r.2.4: Reporter's City #1 ");
                                                            $xml->writeElement("state",$this->XMLvalue($caseId,33,1));
                                                            $xml->writeComment(" C.2.r.2.5: Reporter's State or Province #1 ");
                                                            $xml->writeElement("postalCode",$this->XMLvalue($caseId,34,1));
                                                            $xml->writeComment(" C.2.r.2.6: Reporter's Postcode #1 ");
                                                        $xml->endElement();//addr
                                                        $xml->startElement("telecom");
                                                        $xml->writeAttribute('vlaue',$this->XMLvalue($caseId,229,1));
                                                        $xml->endElement();
                                                        $xml->writeComment(" C.2.r.2.7: Reporter's Telephone #1 ");
                                                        $xml->startElement("assignedPerson");
                                                        $xml->writeAttribute('classCode','PSN');
                                                        $xml->writeAttribute('determinerCode','INSTANCE');
                                                            $xml->startElement("name");
                                                                $xml->writeElement("prefix",$this->XMLvalue($caseId,25,1));
                                                                $xml->writeComment(" C.2.r.1.1: Reporter's Title #1 ");
                                                                $xml->writeElement("given",$this->XMLvalue($caseId,26,1));
                                                                $xml->writeComment(" C.2.r.1.2: Reporter's Given Name #1 ");
                                                                $xml->writeElement("family",$this->XMLvalue($caseId,28,1));
                                                                $xml->writeComment(" C.2.r.1.4: Reporter's Family Name #1 ");
                                                            $xml->endElement();//name
                                                            $xml->startElement("asQualifiedEntity");
                                                            $xml->writeAttribute('classCode','QUAL');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,36,1));
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.6');
                                                                $xml->endElement();
                                                                $xml->writeComment(" C.2.r.4: Qualification ");
                                                            $xml->endElement();//asQualifiedEntity
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
                                                        $xml->endElement();//assignedPerson
                                                        $xml->startElement("representedOrganization");
                                                        $xml->writeAttribute('classCode','ORG');
                                                        $xml->writeAttribute('determinerCode','INSTANCE');
                                                            $xml->writeElement("name",$this->XMLvalue($caseId,30,1));
                                                            $xml->writeComment(" C.2.r.2.2: Reporter's Department ");
                                                            $xml->startElement("assignedEntity");
                                                            $xml->writeAttribute('classCode','ASSIGNED');
                                                                $xml->startElement("representedOrganization");
                                                                $xml->writeAttribute('classCode','ORG');
                                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                                    $xml->writeElement("name",$this->XMLvalue($caseId,29,1));
                                                                    $xml->writeComment(" C.2.r.2.1: Reporter's Organisation ");
                                                                $xml->endElement();
                                                            $xml->endElement();//assignedEntity
                                                        $xml->endElement();//representedOrganization
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
                                                $xml->startElement("code");
                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,41,1));
                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.7');
                                                $xml->endElement();
                                                $xml->writeComment(" C.3.1: Sender Type ");
                                                $xml->startElement("addr");
                                                    $xml->writeElement("streetAddressLine",$this->XMLvalue($caseId,48,1));
                                                    $xml->writeComment(" C.3.4.1: Sender's Street Address ");
                                                    $xml->writeElement("city",$this->XMLvalue($caseId,49,1));
                                                    $xml->writeComment(" C.3.4.2: Sender's City ");
                                                    $xml->writeElement("state",$this->XMLvalue($caseId,50,1));
                                                    $xml->writeComment(" C.3.4.3: Sender's State or Province ");
                                                    $xml->writeElement("postalCode",$this->XMLvalue($caseId,51,1));
                                                    $xml->writeComment(" C.3.4.4: Sender's Postcode ");
                                                $xml->endElement();//addr
                                                $xml->startElement("telecom");
                                                $xml->writeAttribute('vlaue',$this->XMLvalue($caseId,53,1));
                                                $xml->endElement();
                                                $xml->writeComment(" C.3.4.6: Sender's Telephone ");
                                                $xml->startElement("telecom");
                                                $xml->writeAttribute('vlaue',$this->XMLvalue($caseId,56,1));
                                                $xml->endElement();
                                                $xml->writeComment(" C.3.4.7: Sender's Fax ");
                                                $xml->startElement("telecom");
                                                $xml->writeAttribute('vlaue',$this->XMLvalue($caseId,59,1));
                                                $xml->endElement();
                                                $xml->writeComment(" C.3.4.8: Sender's E-mail Address ");
                                                $xml->startElement("assignedPerson");
                                                $xml->writeAttribute('classCode','PSN');
                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                    $xml->startElement("name");
                                                        $xml->writeElement("prefix",$this->XMLvalue($caseId,44,1));
                                                        $xml->writeComment(" C.3.3.2: Sender's Title ");
                                                        $xml->startElement("given");
                                                        $xml->writeAttribute('nullFlavor','MSK');//$this->XMLvalue($caseId,45,1)
                                                        $xml->endElement();
                                                        $xml->writeComment(" C.3.3.3: Sender's Given Name ");
                                                        $xml->startElement("family");
                                                        $xml->writeAttribute('nullFlavor','MSK');//$this->XMLvalue($caseId,47,1)
                                                        $xml->endElement();
                                                        $xml->writeComment(" C.3.3.5: Sender's Family Name ");
                                                    $xml->endElement();//name
                                                    $xml->startElement("asLocatedEntity");
                                                    $xml->writeAttribute('classCode','LOCE');
                                                        $xml->startElement("location");
                                                        $xml->writeAttribute('classCode','COUNTRY');
                                                        $xml->writeAttribute('determinerCode','INSTANCE');
                                                            $xml->startElement("code");
                                                            $xml->writeAttribute('code',$this->XMLvalue($caseId,52,1));
                                                            $xml->writeAttribute('codeSystem','1.0.3166.1.2.2');
                                                            $xml->endElement();
                                                            $xml->writeComment(" C.3.4.5: Sender's Country Code ");
                                                        $xml->endElement();
                                                    $xml->endElement();//asLocatedEntity
                                                $xml->endElement();//assignedPerson
                                                $xml->startElement("representedOrganization");
                                                $xml->writeAttribute('classCode','ORG');
                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                    $xml->writeElement("name",$this->XMLvalue($caseId,43,1));
                                                    $xml->writeComment(" C.3.3.1: Sender's Department ");
                                                    $xml->startElement("assignedEntity");
                                                    $xml->writeAttribute('classCode','ASSIGNED');
                                                        $xml->startElement("representedOrganization");
                                                        $xml->writeAttribute('classCode','ORG');
                                                        $xml->writeAttribute('determinerCode','INSTANCE');
                                                            $xml->writeElement("name",$this->XMLvalue($caseId,42,1));
                                                            $xml->writeComment(" C.3.2: Sender's Organisation ");
                                                        $xml->endElement();//representedOrganization
                                                    $xml->endElement();//assignedEntity
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
                                        $xml->endElement();
                                        $xml->startElement("value");
                                        $xml->writeAttribute('xsi:type','CE');
                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,6,1));
                                        $xml->writeAttribute('codeSystem','="2.16.840.1.113883.3.989.2.1.1.2');
                                        $xml->endElement();
                                        $xml->writeComment(" C.1.3 Type of Report ");
                                    $xml->endElement();//investigationCharacteristic
                                $xml->endElement();//subjectOf2
                                $xml->startElement("subjectOf2");
                                $xml->writeAttribute('typeCode','SUBJ');
                                    $xml->startElement("investigationCharacteristic");
                                    $xml->writeAttribute('classCode','OBS');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','2');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.23');
                                        $xml->endElement();
                                        $xml->startElement("value");
                                        $xml->writeAttribute('xsi:type','BL');
                                        $xml->writeAttribute('nullFlavor',$this->XMLvalue($caseId,18,1));
                                        $xml->endElement();
                                        $xml->writeComment(" C.1.9.1 Other Case Identifiers in Previous Transmissions ");
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
                                        $xml->endElement();
                                        $xml->startElement("value");
                                        $xml->writeAttribute('xsi:type','CE');
                                            $xml->writeElement('originalText',$this->XMLvalue($caseId,23,1));
                                        $xml->endElement();
                                        $xml->writeComment(" C.1.11.2: Reason for Nullification / Amendment ");
                                    $xml->endElement();//investigationCharacteristic
                                $xml->endElement();//subjectOf2
                            $xml->endElement();//investigationEvent
                        $xml->endElement();//subject
                    $xml->endElement();//controlActProcess
                $xml->endElement();//PORR_IN049016UV
                $xml->writeComment(" C.1.9.1 Other Case Identifiers in Previous Transmissions "); 
                $xml->startElement("receiver");
                $xml->writeAttribute('typeCode','RCV');
                    $xml->startElement("device");
                    $xml->writeAttribute('classCode','DEV');
                    $xml->writeAttribute('determinerCode','INSTANCE');
                        $xml->startElement("id");
                        $xml->writeAttribute('extension',$this->XMLvalue($caseId,1054,1));
                        $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.14');
                        $xml->endElement();
                        $xml->writeComment(" Message #1 ");
                    $xml->endElement();//device
                $xml->endElement();//receiver
                $xml->startElement("sender");
                $xml->writeAttribute('typeCode','SND');
                    $xml->startElement("device");
                    $xml->writeAttribute('classCode','DEV');
                    $xml->writeAttribute('determinerCode','INSTANCE');
                        $xml->startElement("id");
                        $xml->writeAttribute('extension',$this->XMLvalue($caseId,1053,1));
                        $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.13');
                        $xml->endElement();
                        $xml->writeComment(" N.1.3: Batch Sender Identifier ");
                    $xml->endElement();//device
                $xml->endElement();//sender
            $xml->endElement();//MCCI_IN200100UV01
        }
        else if($this->XMLvalue($caseId,1062,1)==1){
            $xml->startElement("MCCI_IN200100UV01");
            $xml->writeAttribute('ITSVersion','XML_1.0');
            $xml->writeAttribute('xsi:schemaLocation','urn:hl7-org:v3 MCCI_IN200100UV01.xsd');
            $xml->writeAttribute('xmlns','urn:hl7-org:v3');
            $xml->writeAttributeNS('xmlns','xsi', null,'http://www.w3.org/2001/XMLSchema-instance');
                $xml->startElement("id");
                    $xml->writeAttribute('extension',$this->XMLvalue($caseId,1052,1));
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
                $xml->endElement();
                $xml->writeComment(" N.1.1: Type of Messages in Batch ");
                $xml->writeComment(" Message #1 ");
                $xml->startElement("PORR_IN049016UV");
                    $xml->startElement("id");
                        $xml->writeAttribute('extension',$this->XMLvalue($caseId,1056,1));
                        $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.1');
                    $xml->endElement();
                    $xml->writeComment(" N.2.r.1: Message Identifier ");
                    $xml->startElement("creationTime");
                        $xml->writeAttribute('value',$this->XMLvalue($caseId,1059,1));
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
                            $xml->writeAttribute('extension',$this->XMLvalue($caseId,1058,1));
                            $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.12');
                            $xml->endElement();
                            $xml->writeComment(" N.2.r.3: Message Receiver Identifier ");
                        $xml->endElement();
                    $xml->endElement();
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
                        $xml->endElement();
                    $xml->endElement();
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
                                $xml->startElement("reference");
                                $xml->writeAttribute('typecode','REFR');
                                    $xml->startElement("document");
                                    $xml->writeAttribute('classCode','DOC');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','2');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.27');
                                        $xml->endElement();
                                        $xml->startElement("text");
                                        $xml->writeAttribute('mediaType',$this->XMLvalue($caseId,1002,1));
                                        $xml->writeAttribute('representation','B64');
                                        $xml->text($this->XMLvalue($caseId,1002,1));
                                        $xml->endElement();
                                    $xml->endElement();//document
                                $xml->endElement();//reference
                                $xml->startElement("component");
                                $xml->writeAttribute('typeCode','COMP');
                                    $xml->startElement("adverseEventAssessment");
                                    $xml->writeAttribute('classCode','INVSTG');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("subject1");
                                        $xml->writeAttribute('typeCode','SBJ');
                                            $xml->startElement("primaryRole");
                                            $xml->writeAttribute('classCode','INVSBJ');
                                                $xml->startElement("player1");
                                                $xml->writeAttribute('classCode','PSN');
                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                    $xml->startElement("name");
                                                    $xml->writeAttribute('nullFlavor','MSK');//$this->XMLvalue($caseId,79,1)
                                                    $xml->endElement();
                                                    $xml->writeComment(" D.1: Patient (name or initials) ");
                                                    $xml->startElement("administrativeGenderCode");
                                                    $xml->writeAttribute('code',$this->XMLvalue($caseId,93,1));
                                                    $xml->writeAttribute('codeSystem','1.0.5218');
                                                    $xml->endElement();
                                                    $xml->writeComment(" D.5 Sex ");
                                                    $xml->startElement("birthTime");
                                                    $xml->writeAttribute('value',$this->XMLvalue($caseId,85,1));
                                                    $xml->endElement();
                                                    $xml->writeComment(" D.2.1: Date of Birth ");
                                                $xml->endElement();//player1
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("observation");
                                                    $xml->writeAttribute('classCode','OBS');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','3');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
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
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("observation");
                                                    $xml->writeAttribute('classCode','OBS');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','7');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                        $xml->endElement();
                                                        $xml->startElement("value");
                                                        $xml->writeAttribute('xsi:type','PQ');
                                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,91,1));
                                                        $xml->writeAttribute('unit','kg');
                                                        $xml->endElement();
                                                        $xml->writeComment(" D.3: Body Weight (kg) ");
                                                    $xml->endElement();//observation
                                                $xml->endElement();//subjectOf2
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("observation");
                                                    $xml->writeAttribute('classCode','OBS');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','17');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                        $xml->endElement();
                                                        $xml->startElement("value");
                                                        $xml->writeAttribute('xsi:type','PQ');
                                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,92,1));
                                                        $xml->writeAttribute('unit','cm');
                                                        $xml->endElement();
                                                        $xml->writeComment(" D.4: Height (cm) ");
                                                    $xml->endElement();//observation
                                                $xml->endElement();//subjectOf2
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("organizer");
                                                    $xml->writeAttribute('classCode','GATEGORY');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','1');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.20');
                                                        $xml->endElement();//code
                                                        $xml->startElement("component");
                                                        $xml->writeAttribute('typeCode','COMP');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,97,1));
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.6.163');
                                                                $xml->writeAttribute('codeSystemVersion','15.0');
                                                                $xml->endElement();
                                                                $xml->writeComment(" D.7.1.r.1a: MedDRA Version for Medical History ");
                                                                $xml->writeComment(" D.7.1.r.1b: Medical History (disease / surgical procedure / etc.) (MedDRA code) ");
                                                                $xml->startElement("effectiveTime");
                                                                $xml->writeAttribute('xsi:type','IVL_TS');
                                                                    $xml->startElement("low");
                                                                    $xml->writeAttribute('value',$this->XMLvalue($caseId,99,1));
                                                                    $xml->endElement();
                                                                    $xml->writeComment(" D.7.1.r.2: Start Date ");
                                                                $xml->endElement();
                                                                $xml->startElement("inboundRelationship");
                                                                $xml->writeAttribute('typeCode','REFR');
                                                                    $xml->startElement("observation");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code','13');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                        $xml->endElement();
                                                                        $xml->startElement("value");
                                                                        $xml->writeAttribute('xsi:type','BL');
                                                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,100,1));
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" D.7.1.r.3: Continuing ");
                                                                    $xml->endElement();//observation
                                                                $xml->endElement();//inboundRelationship
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//component  
                                                    $xml->endElement();//organizer
                                                $xml->endElement();//subjectOf2
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
                                                        $xml->endElement();
                                                        $xml->startElement("effectiveTime");
                                                        $xml->writeAttribute('xsi:type','IVL_TS');
                                                            $xml->startElement("low");
                                                            $xml->writeAttribute('value',$this->XMLvalue($caseId,156,1));
                                                            $xml->endElement();
                                                            $xml->writeComment(" E.i.4: Date of Start of Reaction / Event "); 
                                                        $xml->endElement();//effectiveTime
                                                        $xml->startElement("value");
                                                        $xml->writeAttribute('xsi:type','CE');
                                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,151,1));
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.6.163');
                                                        $xml->writeAttribute('codeSystemVersion','15.0');
                                                        $xml->writeComment(" E.i.2.1a: MedDRA Version for Reaction / Event ");
                                                        $xml->writeComment(" E.i.2.1b: Reaction / Event (MedDRA code) ");
                                                        $xml->endElement();//value
                                                        $xml->startElement("location");
                                                        $xml->writeAttribute('typeCode','LOC');
                                                            $xml->startElement("locatedEntity");
                                                            $xml->writeAttribute('classCode','LOCE');
                                                                $xml->startElement("locatedPlace");
                                                                $xml->writeAttribute('classCode','COUNTRY');
                                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                                    $xml->startElement("code");
                                                                    $xml->writeAttribute('code',$this->XMLvalue($caseId,1026,1));
                                                                    $xml->writeAttribute('codeSystem','1.0.3166.1.2.2');
                                                                    $xml->endElement();
                                                                    $xml->writeComment(" E.i.9: Identification of the Country Where the Reaction / Event Occurred ");
                                                                $xml->endElement();
                                                            $xml->endElement();//locatedEntity
                                                        $xml->endElement();//location
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','PERT');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','30');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','ED');
                                                                $xml->text($this->XMLvalue($caseId,1018,1));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.1.2: Reaction / Event as Reported by the Primary Source for Translation ");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','PERT');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','37');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','CE');
                                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,154,1));
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.10');
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.3.1: Term Highlighted by the Reporter ");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','PERT');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','34');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','BL');
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,1019,1));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.3.2a: Results in Death ");
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
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','BL');
                                                                $xml->writeAttribute('nullFlavor','NI');//$this->XMLvalue($caseId,1019,3)
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.3.2b: Life Threatening ");
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
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','BL');
                                                                $xml->writeAttribute('nullFlavor','NI');//$this->XMLvalue($caseId,1019,3)
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.3.2c: Caused / Prolonged Hospitalisation ");
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
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','BL');
                                                                $xml->writeAttribute('nullFlavor','NI');//$this->XMLvalue($caseId,1019,3)
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.3.2d: Disabling / Incapacitating ");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','REFR');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','12');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','BL');
                                                                $xml->writeAttribute('nullFlavor','NI');//$this->XMLvalue($caseId,1019,3)
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.3.2e: Congenital Anomaly / Birth Defect ");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','REFR');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','26');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','BL');
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,1024,3));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.3.2f: Other Medically Important Condition ");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','REFR');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','27');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','CE');
                                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,165,3));
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.11');
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.7: Outcome of Reaction / Event at the Time of Last Observation ");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                    $xml->endElement();//observation
                                                $xml->endElement();//subjectOf2
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("organizer");
                                                    $xml->writeAttribute('classCode','CATEGORY');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','4');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.20');
                                                        $xml->endElement();
                                                        $xml->writeComment(" G.k Drug(s) Information (repeat as necessary) ");
                                                        $xml->startElement("component");
                                                        $xml->writeAttribute('typeCode','COMP');
                                                            $xml->startElement("substanceAdministration");
                                                            $xml->writeAttribute('classCode','SBADM');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("id");
                                                                $xml->writeAttribute('root','3c91b4d5-e039-4a7a-9c30-67671b0ef9e4');
                                                                $xml->endElement();
                                                                $xml->startElement("consumable");
                                                                $xml->writeAttribute('typeCode','CSM');
                                                                    $xml->startElement("instanceOfKind");
                                                                    $xml->writeAttribute('classCode','INST');
                                                                        $xml->startElement("kindOfProduct");
                                                                        $xml->writeAttribute('classCode','MMAT');
                                                                        $xml->writeAttribute('determinerCode','KIND');
                                                                            $xml->writeElement('name',$this->XMLvalue($caseId,176,1));
                                                                            $xml->writeComment(" G.k.2.2: Medicinal Product Name as Reported by the Primary Source ");
                                                                            $xml->startElement("ingredient");
                                                                            $xml->writeAttribute('classCode','ACTI');
                                                                                $xml->startElement("ingredientSubstance");
                                                                                $xml->writeAttribute('classCode','MMAT');
                                                                                $xml->writeAttribute('determinerCode','KIND');
                                                                                    $xml->writeElement('name',$this->XMLvalue($caseId,177,1));
                                                                                    $xml->writeComment(" G.k.2.3.r.1: Substance / Specified Substance Name ");
                                                                                $xml->endElement();
                                                                            $xml->endElement();
                                                                        $xml->endElement();//kindOfProduct
                                                                        $xml->startElement("subjectOf");
                                                                        $xml->writeAttribute('typeCode','SBJ');
                                                                            $xml->startElement("productEvent");
                                                                            $xml->writeAttribute('classCode','ACT');
                                                                            $xml->writeAttribute('moodCode','EVN');
                                                                                $xml->startElement("code");
                                                                                $xml->writeAttribute('code','1');
                                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.18');
                                                                                $xml->endElement();
                                                                                $xml->startElement("performer");
                                                                                $xml->writeAttribute('typeCode','PRF');
                                                                                    $xml->startElement("assignedEntity");
                                                                                    $xml->writeAttribute('classCode','ASSIGNED');
                                                                                        $xml->startElement("representedOrganization");
                                                                                        $xml->writeAttribute('classCode','ORG');
                                                                                        $xml->writeAttribute('determinerCode','INSTANCE');
                                                                                            $xml->startElement("addr");
                                                                                                $xml->writeElement("country",$this->XMLvalue($caseId,178,1));
                                                                                                $xml->writeComment(" G.k.2.4: Identification of the Country Where the Drug Was Obtained ");
                                                                                            $xml->endElement();
                                                                                        $xml->endElement();
                                                                                    $xml->endElement();
                                                                                $xml->endElement();
                                                                            $xml->endElement();
                                                                        $xml->endElement();//subjectOf
                                                                    $xml->endElement();//instanceOfKind
                                                                $xml->endElement();//consumable
                                                                $xml->startElement("outboundRelationship2");
                                                                $xml->writeAttribute('typeCode','COMP');
                                                                $xml->writeComment(" G.k.4.r: Dosage and Relevant Information (repeat as necessary) ");
                                                                    $xml->startElement("substanceAdministration");
                                                                    $xml->writeAttribute('classCode','SBADM');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("effectiveTime");
                                                                        $xml->writeAttribute('xsi:type','SXPR_TS');
                                                                            $xml->startElement("comp");
                                                                            $xml->writeAttribute('xsi:type','PIVL_TS');
                                                                                $xml->startElement("period");
                                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,186,1));
                                                                                $xml->writeAttribute('unit',$this->XMLvalue($caseId,187,1));
                                                                                $xml->endElement();
                                                                                $xml->writeComment(" G.k.4.r.2: Number of Units in the Interval ");
                                                                                $xml->writeComment(" G.k.4.r.3: Definition of the Time Interval Unit ");
                                                                            $xml->endElement();//comp
                                                                            $xml->startElement("comp");
                                                                            $xml->writeAttribute('xsi:type','IVL_TS');
                                                                            $xml->writeAttribute('operator','A');
                                                                                $xml->startElement("low");
                                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,199,1));
                                                                                $xml->endElement();
                                                                                $xml->writeComment(" G.k.4.r.4: Date and Time of Start of Drug ");
                                                                            $xml->endElement();//comp
                                                                        $xml->endElement();//effectiveTime
                                                                        $xml->startElement("routeCode");
                                                                        $xml->writeAttribute('code','048');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.14');
                                                                        $xml->writeComment(" G.k.4.r.10.2b: Route of Administration TermID ");
                                                                            $xml->writeElement("originalText",$this->XMLvalue($caseId,183,1));
                                                                        $xml->endElement();//routeCode
                                                                        $xml->startElement("doesQuantity");
                                                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,183,1));
                                                                            $xml->writeAttribute('unit',$this->XMLvalue($caseId,184,1));
                                                                        $xml->endElement();//doesQuantity
                                                                        $xml->writeComment(" G.k.4.r.1a Dose (number) ");
                                                                        $xml->writeComment(" G.k.4.r.1b: Dose (unit) ");
                                                                        $xml->startElement("consumable");
                                                                        $xml->writeAttribute('type','CSM');
                                                                            $xml->startElement("instanceOfKind");
                                                                            $xml->writeAttribute('classCode','INST');
                                                                                $xml->startElement("kindOfProduct");
                                                                                $xml->writeAttribute('classCode','MMAT');
                                                                                $xml->writeAttribute('determinerCode','KIND');
                                                                                    $xml->startElement("formCode");
                                                                                        $xml->writeElement('originalText',$this->XMLvalue($caseId,191,1));
                                                                                        $xml->writeComment(" G.k.4.r.9.1: Pharmaceutical Dose Form (free text) ");
                                                                                    $xml->endElement();//formCode
                                                                                $xml->endElement();//kindOfProduct
                                                                            $xml->endElement();//instanceOfKind
                                                                        $xml->endElement();//consumable
                                                                    $xml->endElement();//substanceAdministration
                                                                $xml->endElement();//outboundRelationship2
                                                                $xml->startElement("inboundRelationship");
                                                                $xml->writeAttribute('typeCode','RSON');
                                                                    $xml->startElement("observation");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code','19');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                        $xml->endElement();
                                                                        $xml->startElement("value");
                                                                        $xml->writeAttribute('xsi:type','CE');
                                                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,196,1));
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.6.163');
                                                                        $xml->writeAttribute('codeSystemVersion','15.0');
                                                                        $xml->writeComment(" G.k.7.r.2a: MedDRA Version for Indication ");
                                                                        $xml->writeComment(" G.k.7.r.2b: Indication (MedDRA code) ");
                                                                            $xml->writeElement('originalText',$this->XMLvalue($caseId,1047,1));
                                                                            $xml->writeComment(" G.k.7.r.1: Indication as Reported by the Primary Source ");
                                                                        $xml->endElement();
                                                                        $xml->startElement("performer");
                                                                        $xml->writeAttribute('typeCode','PRF');
                                                                            $xml->startElement("assignedEntity");
                                                                            $xml->writeAttribute('classCode','ASSIGNED');
                                                                                $xml->startElement("code");
                                                                                $xml->writeAttribute('code','3');
                                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.21');
                                                                                $xml->endElement();
                                                                            $xml->endElement();
                                                                        $xml->endElement();//performer
                                                                    $xml->endElement();//observation       
                                                                $xml->endElement();//inboundRelationship
                                                                $xml->startElement("inboundRelationship");
                                                                $xml->writeAttribute('typeCode','CAUS');
                                                                    $xml->startElement("act");
                                                                    $xml->writeAttribute('classCode','ACT');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,208,1));
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.15');
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" G.k.8: Action(s) Taken with Drug ");
                                                                    $xml->endElement();//act       
                                                                $xml->endElement();//inboundRelationship
                                                            $xml->endElement();//substanceAdministration
                                                        $xml->endElement();//component
                                                    $xml->endElement();//organizer
                                                $xml->endElement();//subjectOf2
                                            $xml->endElement();//primaryRole
                                        $xml->endElement();//SUBJECT1
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
                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,175,2));
                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.13');
                                                $xml->endElement();
                                                $xml->writeComment(" G.k.1: Characterisation of Drug Role ");
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
                                    $xml->endElement();//adverseEventAssessment
                                $xml->endElement();//component
                                $xml->startElement("component");
                                $xml->writeAttribute('typeCode','COMP');
                                    $xml->startElement("observationEvent");
                                    $xml->writeAttribute('classCode','OBS');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','1');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                        $xml->endElement();
                                        $xml->startElement("value");
                                        $xml->writeAttribute('xsi:type','BL');
                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,13,1));
                                        $xml->writeComment(' C.1.6.1: Are Additional Documents Available? ');
                                        $xml->endElement();
                                    $xml->endElement();//observationEvent
                                $xml->endElement();//component
                                $xml->startElement("component");
                                $xml->writeAttribute('typeCode','COMP');
                                    $xml->startElement("observationEvent");
                                    $xml->writeAttribute('classCode','OBS');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','23');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                        $xml->endElement();
                                        $xml->startElement("value");
                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,15,1));
                                        $xml->writeAttribute('xsi:type','BL');
                                        $xml->writeComment(' C.1.7: Does This Case Fulfill the Local Criteria for an Expedited Report? ');
                                        $xml->endElement();
                                    $xml->endElement();//observationEvent
                                $xml->endElement();//component
                                $xml->startElement("outboundRelationship");
                                $xml->writeAttribute('typeCode','SPRT');
                                    $xml->startElement("relatedInvestigation");
                                    $xml->writeAttribute('classCode','INVSTG');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','1');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.22');
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
                                                        $xml->endElement();//code
                                                        $xml->writeComment(" C.1.8.2: First Sender of This Case ");
                                                    $xml->endElement();//assignedEntity
                                                $xml->endElement();//author
                                            $xml->endElement();//controlActEvent
                                        $xml->endElement();//subjectOf2
                                    $xml->endElement();//observation
                                $xml->endElement();//outboundRelationship
                                $xml->startElement("outboundRelationship");
                                $xml->writeAttribute('typeCode','SPRT');
                                    $xml->startElement("priorityNumber");
                                    $xml->writeAttribute('value',$this->XMLvalue($caseId,227,1));
                                    $xml->endElement();
                                    $xml->writeComment(' C.2.r.5: Primary Source for Regulatory Purposes ');
                                    $xml->startElement("relatedInvestigation");
                                    $xml->writeAttribute('classCode','INVSTG');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','2');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.22');
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
                                                            $xml->writeElement("streetAddressLine",$this->XMLvalue($caseId,31,1));
                                                            $xml->writeComment(" C.2.r.2.3: Reporter's Street #1 ");
                                                            $xml->writeElement("city",$this->XMLvalue($caseId,32,1));
                                                            $xml->writeComment(" C.2.r.2.4: Reporter's City #1 ");
                                                            $xml->writeElement("state",$this->XMLvalue($caseId,33,1));
                                                            $xml->writeComment(" C.2.r.2.5: Reporter's State or Province #1 ");
                                                            $xml->writeElement("postalCode",$this->XMLvalue($caseId,34,1));
                                                            $xml->writeComment(" C.2.r.2.6: Reporter's Postcode #1 ");
                                                        $xml->endElement();//addr
                                                        $xml->startElement("telecom");
                                                        $xml->writeAttribute('vlaue',$this->XMLvalue($caseId,229,1));
                                                        $xml->endElement();
                                                        $xml->writeComment(" C.2.r.2.7: Reporter's Telephone #1 ");
                                                        $xml->startElement("assignedPerson");
                                                        $xml->writeAttribute('classCode','PSN');
                                                        $xml->writeAttribute('determinerCode','INSTANCE');
                                                            $xml->startElement("name");
                                                                $xml->writeElement("prefix",$this->XMLvalue($caseId,25,1));
                                                                $xml->writeComment(" C.2.r.1.1: Reporter's Title #1 ");
                                                                $xml->writeElement("given",$this->XMLvalue($caseId,26,1));
                                                                $xml->writeComment(" C.2.r.1.2: Reporter's Given Name #1 ");
                                                                $xml->writeElement("family",$this->XMLvalue($caseId,28,1));
                                                                $xml->writeComment(" C.2.r.1.4: Reporter's Family Name #1 ");
                                                            $xml->endElement();//name
                                                            $xml->startElement("asQualifiedEntity");
                                                            $xml->writeAttribute('classCode','QUAL');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,36,1));
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.6');
                                                                $xml->endElement();
                                                                $xml->writeComment(" C.2.r.4: Qualification ");
                                                            $xml->endElement();//asQualifiedEntity
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
                                                        $xml->endElement();//assignedPerson
                                                        $xml->startElement("representedOrganization");
                                                        $xml->writeAttribute('classCode','ORG');
                                                        $xml->writeAttribute('determinerCode','INSTANCE');
                                                            $xml->writeElement("name",$this->XMLvalue($caseId,30,1));
                                                            $xml->writeComment(" C.2.r.2.2: Reporter's Department ");
                                                            $xml->startElement("assignedEntity");
                                                            $xml->writeAttribute('classCode','ASSIGNED');
                                                                $xml->startElement("representedOrganization");
                                                                $xml->writeAttribute('classCode','ORG');
                                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                                    $xml->writeElement("name",$this->XMLvalue($caseId,29,1));
                                                                    $xml->writeComment(" C.2.r.2.1: Reporter's Organisation ");
                                                                $xml->endElement();
                                                            $xml->endElement();//assignedEntity
                                                        $xml->endElement();//representedOrganization
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
                                                $xml->startElement("code");
                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,41,1));
                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.7');
                                                $xml->endElement();
                                                $xml->writeComment(" C.3.1: Sender Type ");
                                                $xml->startElement("addr");
                                                    $xml->writeElement("streetAddressLine",$this->XMLvalue($caseId,48,1));
                                                    $xml->writeComment(" C.3.4.1: Sender's Street Address ");
                                                    $xml->writeElement("city",$this->XMLvalue($caseId,49,1));
                                                    $xml->writeComment(" C.3.4.2: Sender's City ");
                                                    $xml->writeElement("state",$this->XMLvalue($caseId,50,1));
                                                    $xml->writeComment(" C.3.4.3: Sender's State or Province ");
                                                    $xml->writeElement("postalCode",$this->XMLvalue($caseId,51,1));
                                                    $xml->writeComment(" C.3.4.4: Sender's Postcode ");
                                                $xml->endElement();//addr
                                                $xml->startElement("telecom");
                                                $xml->writeAttribute('vlaue',$this->XMLvalue($caseId,53,1));
                                                $xml->endElement();
                                                $xml->writeComment(" C.3.4.6: Sender's Telephone ");
                                                $xml->startElement("telecom");
                                                $xml->writeAttribute('vlaue',$this->XMLvalue($caseId,56,1));
                                                $xml->endElement();
                                                $xml->writeComment(" C.3.4.7: Sender's Fax ");
                                                $xml->startElement("telecom");
                                                $xml->writeAttribute('vlaue',$this->XMLvalue($caseId,59,1));
                                                $xml->endElement();
                                                $xml->writeComment(" C.3.4.8: Sender's E-mail Address ");
                                                $xml->startElement("assignedPerson");
                                                $xml->writeAttribute('classCode','PSN');
                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                    $xml->startElement("name");
                                                        $xml->writeElement("prefix",$this->XMLvalue($caseId,44,1));
                                                        $xml->writeComment(" C.3.3.2: Sender's Title ");
                                                        $xml->startElement("given");
                                                        $xml->writeAttribute('nullFlavor','MSK');//$this->XMLvalue($caseId,45,1)
                                                        $xml->endElement();
                                                        $xml->writeComment(" C.3.3.3: Sender's Given Name ");
                                                        $xml->startElement("family");
                                                        $xml->writeAttribute('nullFlavor','MSK');//$this->XMLvalue($caseId,47,1)
                                                        $xml->endElement();
                                                        $xml->writeComment(" C.3.3.5: Sender's Family Name ");
                                                    $xml->endElement();//name
                                                    $xml->startElement("asLocatedEntity");
                                                    $xml->writeAttribute('classCode','LOCE');
                                                        $xml->startElement("location");
                                                        $xml->writeAttribute('classCode','COUNTRY');
                                                        $xml->writeAttribute('determinerCode','INSTANCE');
                                                            $xml->startElement("code");
                                                            $xml->writeAttribute('code',$this->XMLvalue($caseId,52,1));
                                                            $xml->writeAttribute('codeSystem','1.0.3166.1.2.2');
                                                            $xml->endElement();
                                                            $xml->writeComment(" C.3.4.5: Sender's Country Code ");
                                                        $xml->endElement();
                                                    $xml->endElement();//asLocatedEntity
                                                $xml->endElement();//assignedPerson
                                                $xml->startElement("representedOrganization");
                                                $xml->writeAttribute('classCode','ORG');
                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                    $xml->writeElement("name",$this->XMLvalue($caseId,43,1));
                                                    $xml->writeComment(" C.3.3.1: Sender's Department ");
                                                    $xml->startElement("assignedEntity");
                                                    $xml->writeAttribute('classCode','ASSIGNED');
                                                        $xml->startElement("representedOrganization");
                                                        $xml->writeAttribute('classCode','ORG');
                                                        $xml->writeAttribute('determinerCode','INSTANCE');
                                                            $xml->writeElement("name",$this->XMLvalue($caseId,42,1));
                                                            $xml->writeComment(" C.3.2: Sender's Organisation ");
                                                        $xml->endElement();//representedOrganization
                                                    $xml->endElement();//assignedEntity
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
                                        $xml->endElement();
                                        $xml->startElement("value");
                                        $xml->writeAttribute('xsi:type','CE');
                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,6,1));
                                        $xml->writeAttribute('codeSystem','="2.16.840.1.113883.3.989.2.1.1.2');
                                        $xml->endElement();
                                        $xml->writeComment(" C.1.3 Type of Report ");
                                    $xml->endElement();//investigationCharacteristic
                                $xml->endElement();//subjectOf2
                                $xml->startElement("subjectOf2");
                                $xml->writeAttribute('typeCode','SUBJ');
                                    $xml->startElement("investigationCharacteristic");
                                    $xml->writeAttribute('classCode','OBS');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','2');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.23');
                                        $xml->endElement();
                                        $xml->startElement("value");
                                        $xml->writeAttribute('xsi:type','BL');
                                        $xml->writeAttribute('nullFlavor',$this->XMLvalue($caseId,18,1));
                                        $xml->endElement();
                                        $xml->writeComment(" C.1.9.1 Other Case Identifiers in Previous Transmissions ");
                                    $xml->endElement();//investigationCharacteristic
                                $xml->endElement();//subjectOf2
                            $xml->endElement();//investigationEvent
                        $xml->endElement();//subject
                    $xml->endElement();//controlActProcess
                $xml->endElement();//PORR_IN049016UV
                $xml->writeComment(" C.1.9.1 Other Case Identifiers in Previous Transmissions "); 
                $xml->startElement("receiver");
                $xml->writeAttribute('typeCode','RCV');
                    $xml->startElement("device");
                    $xml->writeAttribute('classCode','DEV');
                    $xml->writeAttribute('determinerCode','INSTANCE');
                        $xml->startElement("id");
                        $xml->writeAttribute('extension',$this->XMLvalue($caseId,1054,1));
                        $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.14');
                        $xml->endElement();
                        $xml->writeComment(" Message #1 ");
                    $xml->endElement();//device
                $xml->endElement();//receiver
                $xml->startElement("sender");
                $xml->writeAttribute('typeCode','SND');
                    $xml->startElement("device");
                    $xml->writeAttribute('classCode','DEV');
                    $xml->writeAttribute('determinerCode','INSTANCE');
                        $xml->startElement("id");
                        $xml->writeAttribute('extension',$this->XMLvalue($caseId,1053,1));
                        $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.13');
                        $xml->endElement();
                        $xml->writeComment(" N.1.3: Batch Sender Identifier ");
                    $xml->endElement();//device
                $xml->endElement();//sender
            $xml->endElement();//MCCI_IN200100UV01
        }
        else if($this->XMLvalue($caseId,1062,1)==2){
            $xml->startElement("MCCI_IN200100UV01");
            $xml->writeAttribute('ITSVersion','XML_1.0');
            $xml->writeAttribute('xsi:schemaLocation','urn:hl7-org:v3 MCCI_IN200100UV01.xsd');
            $xml->writeAttribute('xmlns','urn:hl7-org:v3');
            $xml->writeAttributeNS('xmlns','xsi', null,'http://www.w3.org/2001/XMLSchema-instance');
                $xml->startElement("id");
                    $xml->writeAttribute('extension',$this->XMLvalue($caseId,1052,1));
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
                $xml->endElement();
                $xml->writeComment(" N.1.1: Type of Messages in Batch ");
                $xml->writeComment(" Message #1 ");
                $xml->startElement("PORR_IN049016UV");
                    $xml->startElement("id");
                        $xml->writeAttribute('extension',$this->XMLvalue($caseId,1056,1));
                        $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.1');
                    $xml->endElement();
                    $xml->writeComment(" N.2.r.1: Message Identifier ");
                    $xml->startElement("creationTime");
                        $xml->writeAttribute('value',$this->XMLvalue($caseId,1059,1));
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
                            $xml->writeAttribute('extension',$this->XMLvalue($caseId,1058,1));
                            $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.12');
                            $xml->endElement();
                            $xml->writeComment(" N.2.r.3: Message Receiver Identifier ");
                        $xml->endElement();
                    $xml->endElement();
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
                        $xml->endElement();
                    $xml->endElement();
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
                                $xml->startElement("reference");
                                $xml->writeAttribute('typeCode','REFR');
                                    $xml->startElement("document");
                                    $xml->writeAttribute('classCode','DOC');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.27');
                                        $xml->writeAttribute('code','1');
                                        $xml->endElement();
                                        $xml->writeComment(" documentsHeldBySender ");
                                        $xml->writeElement("title",$this->XMLvalue($caseId,14,1));
                                        $xml->writeComment(" C.1.6.1.r.1: Documents Held by Sender (repeat as necessary) #1 ");
                                    $xml->endElement();//DOCUMENT
                                $xml->endElement();//reference
                                $xml->startElement("component");
                                $xml->writeAttribute('typeCode','COMP');
                                    $xml->startElement("adverseEventAssessment");
                                    $xml->writeAttribute('classCode','INVSTG');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("subject1");
                                        $xml->writeAttribute('typeCode','SBJ');
                                            $xml->startElement("primaryRole");
                                            $xml->writeAttribute('classCode','INVSBJ');
                                                $xml->startElement("player1");
                                                $xml->writeAttribute('classCode','PSN');
                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                    $xml->startElement("name");
                                                    $xml->writeAttribute('nullFlavor','MSK');//$this->XMLvalue($caseId,79,1)
                                                    $xml->endElement();
                                                    $xml->writeComment(" D.1: Patient (name or initials) ");
                                                    $xml->startElement("administrativeGenderCode");
                                                    $xml->writeAttribute('code',$this->XMLvalue($caseId,93,1));
                                                    $xml->writeAttribute('codeSystem','1.0.5218');
                                                    $xml->endElement();
                                                    $xml->writeComment(" D.5 Sex ");
                                                    $xml->startElement("birthTime");
                                                    $xml->writeAttribute('value',$this->XMLvalue($caseId,85,1));   
                                                    $xml->endElement();
                                                    $xml->writeComment(" D.2.1: Date of Birth ");
                                                    $xml->startElement("role");
                                                    $xml->writeAttribute('classCode','PRS');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','PRN');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.5.111');
                                                        $xml->endElement();
                                                        $xml->startElement("associatedPerson");
                                                        $xml->writeAttribute('classCode','PSN');
                                                        $xml->writeAttribute('determinerCode','INSTANCE');
                                                            $xml->writeElement('name',$this->XMLvalue($caseId,121,1));
                                                            $xml->writeComment(" D.10.1: Parent Identification ");
                                                            $xml->startElement("administrativeGenderCode");
                                                            $xml->writeAttribute('code',$this->XMLvalue($caseId,130,1));
                                                            $xml->writeAttribute('codeSystem','1.0.5218');
                                                            $xml->endElement();
                                                            $xml->writeComment(" D.10.6: Sex of Parent ");
                                                        $xml->endElement();//associatedPerson
                                                        $xml->startElement("subjectOf2");
                                                        $xml->writeAttribute('typeCode','SBJ');
                                                            $xml->startElement("organizer");
                                                            $xml->writeAttribute('classCode','CATEGORY');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','1');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.20');
                                                                $xml->endElement();
                                                                $xml->startElement("component");
                                                                $xml->writeAttribute('typeCode','COMP');
                                                                    $xml->startElement("observation");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,132,1));
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.6.163');
                                                                        $xml->writeAttribute('codeSystemVersion','15.0');
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" D.10.7.1.r.1a: MedDRA Version for Medical History ");
                                                                        $xml->writeComment(" D.10.7.1.r.1b: Medical History (disease / surgical procedure/ etc.) (MedDRA code) ");
                                                                        $xml->startElement("inboundRelationship");
                                                                        $xml->writeAttribute('typeCode','REFR');
                                                                            $xml->startElement("observation");
                                                                            $xml->writeAttribute('classCode','OBS');
                                                                            $xml->writeAttribute('moodCode','EVN');
                                                                                $xml->startElement("code");
                                                                                $xml->writeAttribute('code','13');
                                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                                $xml->endElement();
                                                                                $xml->startElement("value");
                                                                                $xml->writeAttribute('xsi:type','BL');
                                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,135,1));
                                                                                $xml->endElement();
                                                                                $xml->writeComment(" D.10.7.1.r.3: Continuing ");
                                                                            $xml->endElement();
                                                                        $xml->endElement();
                                                                    $xml->endElement();//observation
                                                                $xml->endElement();//component
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//subjectOf2
                                                    $xml->endElement();//role
                                                $xml->endElement();//player1
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("observation");
                                                    $xml->writeAttribute('classCode','OBS');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','3');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
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
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("observation");
                                                    $xml->writeAttribute('classCode','OBS');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','7');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                        $xml->endElement();
                                                        $xml->startElement("value");
                                                        $xml->writeAttribute('xsi:type','PQ');
                                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,91,1));
                                                        $xml->writeAttribute('unit','kg');
                                                        $xml->endElement();
                                                        $xml->writeComment(" D.3: Body Weight (kg) ");
                                                    $xml->endElement();//observation
                                                $xml->endElement();//subjectOf2
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("observation");
                                                    $xml->writeAttribute('classCode','OBS');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','17');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                        $xml->endElement();
                                                        $xml->startElement("value");
                                                        $xml->writeAttribute('xsi:type','PQ');
                                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,92,1));
                                                        $xml->writeAttribute('unit','cm');
                                                        $xml->endElement();
                                                        $xml->writeComment(" D.4: Height (cm) ");
                                                    $xml->endElement();//observation
                                                $xml->endElement();//subjectOf2
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("organizer");
                                                    $xml->writeAttribute('classCode','GATEGORY');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','1');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.20');
                                                        $xml->endElement();//code
                                                        $xml->startElement("component");
                                                        $xml->writeAttribute('typeCode','COMP');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,97,1));
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.6.163');
                                                                $xml->writeAttribute('codeSystemVersion','15.0');
                                                                $xml->endElement();
                                                                $xml->writeComment(" D.7.1.r.1a: MedDRA Version for Medical History ");
                                                                $xml->writeComment(" D.7.1.r.1b: Medical History (disease / surgical procedure / etc.) (MedDRA code) ");
                                                                $xml->startElement("effectiveTime");
                                                                $xml->writeAttribute('xsi:type','IVL_TS');
                                                                    $xml->startElement("low");
                                                                    $xml->writeAttribute('value',$this->XMLvalue($caseId,99,1));
                                                                    $xml->endElement();
                                                                    $xml->writeComment(" D.7.1.r.2: Start Date ");
                                                                $xml->endElement();
                                                                $xml->startElement("inboundRelationship");
                                                                $xml->writeAttribute('typeCode','REFR');
                                                                    $xml->startElement("observation");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code','13');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                        $xml->endElement();
                                                                        $xml->startElement("value");
                                                                        $xml->writeAttribute('xsi:type','BL');
                                                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,100,1));
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" D.7.1.r.3: Continuing ");
                                                                    $xml->endElement();//observation
                                                                $xml->endElement();//inboundRelationship
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//component  
                                                    $xml->endElement();//organizer
                                                $xml->endElement();//subjectOf2
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
                                                        $xml->endElement();
                                                        $xml->startElement("effectiveTime");
                                                        $xml->writeAttribute('xsi:type','IVL_TS');
                                                                $xml->startElement("low");
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,156,1));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.4: Date of Start of Reaction / Event ");
                                                        $xml->endElement();//effectiveTime
                                                        $xml->startElement("value");
                                                        $xml->writeAttribute('xsi:type','CE');
                                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,151,1));
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.6.163');
                                                        $xml->writeAttribute('codeSystemVersion','15.0');
                                                        $xml->writeComment(" E.i.2.1a: MedDRA Version for Reaction / Event ");
                                                        $xml->writeComment(" E.i.2.1b: Reaction / Event (MedDRA code) ");
                                                        $xml->endElement();//value
                                                        $xml->startElement("location");
                                                        $xml->writeAttribute('typeCode','LOC');
                                                            $xml->startElement("locatedEntity");
                                                            $xml->writeAttribute('classCode','LOCE');
                                                                $xml->startElement("locatedPlace");
                                                                $xml->writeAttribute('classCode','COUNTRY');
                                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                                    $xml->startElement("code");
                                                                    $xml->writeAttribute('code',$this->XMLvalue($caseId,1026,1));
                                                                    $xml->writeAttribute('codeSystem','1.0.3166.1.2.2');
                                                                    $xml->endElement();
                                                                    $xml->writeComment(" E.i.9: Identification of the Country Where the Reaction / Event Occurred ");
                                                                $xml->endElement();
                                                            $xml->endElement();//locatedEntity
                                                        $xml->endElement();//location
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','PERT');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','30');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','ED');
                                                                $xml->text($this->XMLvalue($caseId,1018,1));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.1.2: Reaction / Event as Reported by the Primary Source for Translation ");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','PERT');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','37');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','CE');
                                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,154,1));
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.11');
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.3.1: Term Highlighted by the Reporter ");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','PERT');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','34');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','BL');
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,1019,1));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.3.2a: Results in Death ");
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
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','BL');
                                                                $xml->writeAttribute('nullFlavor','NI');//$this->XMLvalue($caseId,1019,3)
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.3.2b: Life Threatening ");
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
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','BL');
                                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,1021,2));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.3.2c: Caused / Prolonged Hospitalisation ");
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
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','BL');
                                                                $xml->writeAttribute('nullFlavor','NI');//$this->XMLvalue($caseId,1022,3)
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.3.2d: Disabling / Incapacitating ");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','REFR');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','12');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','BL');
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,1023,3));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.3.2e: Congenital Anomaly / Birth Defect ");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','REFR');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','26');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','BL');
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,1024,3));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.3.2f: Other Medically Important Condition ");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','REFR');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','27');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','CE');
                                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,165,3));
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.11');
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.7: Outcome of Reaction / Event at the Time of Last Observation ");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','REFR');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','24');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','BL');
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,1025,3));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.8: Medical Confirmation by Healthcare Professional ");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                    $xml->endElement();//observation
                                                $xml->endElement();//subjectOf2
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("organizer");
                                                    $xml->writeAttribute('classCode','CATEGORY');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','4');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.20');
                                                        $xml->endElement();
                                                        $xml->writeComment(" G.k Drug(s) Information (repeat as necessary) #1 ");
                                                        $xml->startElement("component");
                                                        $xml->writeAttribute('typeCode','COMP');
                                                            $xml->startElement("substanceAdministration");
                                                            $xml->writeAttribute('classCode','SBADM');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("id");
                                                                $xml->writeAttribute('root','3c91b4d5-e039-4a7a-9c30-67671b0ef9e4');
                                                                $xml->endElement();
                                                                $xml->startElement("consumable");
                                                                $xml->writeAttribute('typeCode','CSM');
                                                                    $xml->startElement("instanceOfKind");
                                                                    $xml->writeAttribute('classCode','INST');
                                                                        $xml->startElement("kindOfProduct");
                                                                        $xml->writeAttribute('classCode','MMAT');
                                                                        $xml->writeAttribute('determinerCode','KIND');
                                                                            $xml->writeElement('name',$this->XMLvalue($caseId,176,1));
                                                                            $xml->writeComment(" G.k.2.2: Medicinal Product Name as Reported by the Primary Source ");
                                                                            $xml->startElement("ingredient");
                                                                            $xml->writeAttribute('classCode','ACTI');
                                                                                $xml->startElement("ingredientSubstance");
                                                                                $xml->writeAttribute('classCode','MMAT');
                                                                                $xml->writeAttribute('determinerCode','KIND');
                                                                                    $xml->writeElement('name',$this->XMLvalue($caseId,177,1));
                                                                                    $xml->writeComment(" G.k.2.3.r.1: Substance / Specified Substance Name ");
                                                                                $xml->endElement();
                                                                            $xml->endElement();
                                                                        $xml->endElement();//kindOfProduct
                                                                        $xml->startElement("subjectOf");
                                                                        $xml->writeAttribute('typeCode','SBJ');
                                                                            $xml->startElement("productEvent");
                                                                            $xml->writeAttribute('classCode','ACT');
                                                                            $xml->writeAttribute('moodCode','EVN');
                                                                                $xml->startElement("code");
                                                                                $xml->writeAttribute('code','1');
                                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.18');
                                                                                $xml->endElement();
                                                                                $xml->startElement("performer");
                                                                                $xml->writeAttribute('typeCode','PRF');
                                                                                    $xml->startElement("assignedEntity");
                                                                                    $xml->writeAttribute('classCode','ASSIGNED');
                                                                                        $xml->startElement("representedOrganization");
                                                                                        $xml->writeAttribute('classCode','ORG');
                                                                                        $xml->writeAttribute('determinerCode','INSTANCE');
                                                                                            $xml->startElement("addr");
                                                                                                $xml->writeElement("country",$this->XMLvalue($caseId,178,1));
                                                                                                $xml->writeComment(" G.k.2.4: Identification of the Country Where the Drug Was Obtained ");
                                                                                            $xml->endElement();
                                                                                        $xml->endElement();
                                                                                    $xml->endElement();
                                                                                $xml->endElement();
                                                                            $xml->endElement();
                                                                        $xml->endElement();//subjectOf
                                                                    $xml->endElement();//instanceOfKind
                                                                $xml->endElement();//consumable
                                                                $xml->startElement("outboundRelationship2");
                                                                $xml->writeAttribute('typeCode','COMP');
                                                                $xml->writeComment(" G.k.4.r: Dosage and Relevant Information (repeat as necessary) ");
                                                                    $xml->startElement("substanceAdministration");
                                                                    $xml->writeAttribute('classCode','SBADM');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("effectiveTime");
                                                                        $xml->writeAttribute('xsi:type','SXPR_TS');
                                                                            $xml->startElement("comp");
                                                                            $xml->writeAttribute('xsi:type','PIVL_TS');
                                                                                $xml->startElement("period");
                                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,186,1));
                                                                                $xml->writeAttribute('unit',$this->XMLvalue($caseId,187,1));
                                                                                $xml->endElement();
                                                                                $xml->writeComment(" G.k.4.r.2: Number of Units in the Interval ");
                                                                                $xml->writeComment(" G.k.4.r.3: Definition of the Time Interval Unit ");
                                                                            $xml->endElement();//comp
                                                                            $xml->startElement("comp");
                                                                            $xml->writeAttribute('xsi:type','IVL_TS');
                                                                            $xml->writeAttribute('operator','A');
                                                                                $xml->startElement("low");
                                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,199,1));
                                                                                $xml->endElement();
                                                                                $xml->writeComment(" G.k.4.r.4: Date and Time of Start of Drug ");
                                                                            $xml->endElement();//comp
                                                                        $xml->endElement();//effectiveTime
                                                                        $xml->startElement("routeCode");
                                                                        $xml->writeAttribute('code', );
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.14');
                                                                        $xml->writeComment(" G.k.4.r.10.2b: Route of Administration TermID ");
                                                                            $xml->writeElement('originalText',$this->XMLvalue($caseId,192,1));
                                                                            $xml->writeComment(" G.k.4.r.10.1: Route of Administration (free text) ");
                                                                        $xml->endElement();//routeCode
                                                                        $xml->startElement("doseQuantity");
                                                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,186,1));
                                                                        $xml->writeAttribute('unit',$this->XMLvalue($caseId,187,1));
                                                                        $xml->endElement();//doseQuantity
                                                                        $xml->writeComment(" G.k.4.r.1a Dose (number) ");
                                                                        $xml->writeComment(" G.k.4.r.1b: Dose (unit) ");
                                                                        $xml->startElement("consumable");
                                                                        $xml->writeAttribute('type','CSM');
                                                                            $xml->startElement("instanceOfKind");
                                                                            $xml->writeAttribute('classCode','INST');
                                                                                $xml->startElement("kindOfProduct");
                                                                                $xml->writeAttribute('classCode','MMAT');
                                                                                $xml->writeAttribute('determinerCode','KIND');
                                                                                    $xml->startElement("formCode");
                                                                                        $xml->writeElement('originalText',$this->XMLvalue($caseId,191,1));
                                                                                        $xml->writeComment(" G.k.4.r.9.1: Pharmaceutical Dose Form (free text) #1-1 ");
                                                                                    $xml->endElement();//formCode
                                                                                $xml->endElement();//kindOfProduct
                                                                            $xml->endElement();//instanceOfKind
                                                                        $xml->endElement();//consumable
                                                                    $xml->endElement();//substanceAdministration
                                                                $xml->endElement();//outboundRelationship2
                                                               
                                                                $xml->startElement("inboundRelationship");
                                                                $xml->writeAttribute('typeCode','RSON');
                                                                    $xml->startElement("observation");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code','19');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                        $xml->endElement();
                                                                        $xml->startElement("value");
                                                                        $xml->writeAttribute('xsi:type','CE');
                                                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,196,1));
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.6.163');
                                                                        $xml->writeAttribute('codeSystemVersion','15.0');
                                                                        $xml->writeComment(" G.k.7.r.2a: MedDRA Version for Indication ");
                                                                        $xml->writeComment(" G.k.7.r.2b: Indication (MedDRA code) ");
                                                                            $xml->writeElement('originalText',$this->XMLvalue($caseId,1047,1));
                                                                            $xml->writeComment(" G.k.7.r.1: Indication as Reported by the Primary Source ");
                                                                        $xml->endElement();
                                                                        $xml->startElement("performer");
                                                                        $xml->writeAttribute('typeCode','PRF');
                                                                            $xml->startElement("assignedEntity");
                                                                            $xml->writeAttribute('classCode','ASSIGNED');
                                                                                $xml->startElement("code");
                                                                                $xml->writeAttribute('code','3');
                                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.21');
                                                                                $xml->endElement();
                                                                            $xml->endElement();
                                                                        $xml->endElement();//performer
                                                                    $xml->endElement();//observation       
                                                                $xml->endElement();//inboundRelationship
                                                                $xml->startElement("inboundRelationship");
                                                                $xml->writeAttribute('typeCode','CAUS');
                                                                    $xml->startElement("act");
                                                                    $xml->writeAttribute('classCode','ACT');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,208,1));
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.15');
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" G.k.8: Action(s) Taken with Drug ");
                                                                    $xml->endElement();//act       
                                                                $xml->endElement();//inboundRelationship
                                                            $xml->endElement();//substanceAdministration
                                                        $xml->endElement();//component
                                                    $xml->endElement();//organizer
                                                $xml->endElement();//subjectOf2
                                            $xml->endElement();//primaryRole
                                        $xml->endElement();//SUBJECT1
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
                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,175,2));
                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.13');
                                                $xml->endElement();
                                                $xml->writeComment(" G.k.1: Characterisation of Drug Role "); 
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
                                        $xml->startElement("component1");
                                        $xml->writeAttribute('typeCode','COMP');
                                            $xml->startElement("observationEvent");
                                            $xml->writeAttribute('classCode','OBS');
                                            $xml->writeAttribute('moodCode','EVN');
                                                $xml->startElement("code");
                                                $xml->writeAttribute('code','15');
                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                $xml->endElement();
                                                $xml->startElement("value");
                                                $xml->writeAttribute('xsi:type','CE');
                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,221,1));
                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.6.163');
                                                $xml->writeAttribute('codeSystemVersion','15.0');
                                                $xml->endElement();
                                                $xml->writeComment(" H.3.r.1a: MedDRA Version for Sender's Diagnosis / Syndrome and / or Reclassification of Reaction / Event ");
                                                $xml->writeComment(" H.3.r.1b: Sender's Diagnosis / Syndrome and / or Reclassification of Reaction / Event  (MedDRA code) ");
                                                $xml->startElement("author");
                                                $xml->writeAttribute('typeCode','AUT');
                                                    $xml->startElement("assignedEntity");
                                                    $xml->writeAttribute('classCode','ASSIGNED');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','1');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.21');
                                                        $xml->endElement();//code
                                                    $xml->endElement();//assignedEntity
                                                $xml->endElement();//author
                                            $xml->endElement();//observationEvent
                                        $xml->endElement();//component1
                                    $xml->endElement();//adverseEventAssessment
                                $xml->endElement();//component
                                $xml->startElement("component");
                                $xml->writeAttribute('typeCode','COMP');
                                    $xml->startElement("observationEvent");
                                    $xml->writeAttribute('classCode','OBS');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','1');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                        $xml->endElement();
                                        $xml->startElement("value");
                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,13,1));
                                        $xml->writeAttribute('xsi:type','BL');
                                        $xml->writeComment(' C.1.6.1: Are Additional Documents Available? ');
                                        $xml->endElement();
                                    $xml->endElement();//observationEvent
                                $xml->endElement();//component
                                $xml->startElement("component");
                                $xml->writeAttribute('typeCode','COMP');
                                    $xml->startElement("observationEvent");
                                    $xml->writeAttribute('classCode','OBS');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','23');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                        $xml->endElement();
                                        $xml->startElement("value");
                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,15,1));
                                        $xml->writeAttribute('xsi:type','BL');
                                        $xml->writeComment(' C.1.7: Does This Case Fulfill the Local Criteria for an Expedited Report? ');
                                        $xml->endElement();
                                    $xml->endElement();//observationEvent
                                $xml->endElement();//component
                                $xml->startElement("outboundRelationship");
                                $xml->writeAttribute('typeCode','SPRT');
                                    $xml->startElement("relatedInvestigation");
                                    $xml->writeAttribute('classCode','INVSTG');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','1');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.22');
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
                                                        $xml->endElement();//code
                                                        $xml->writeComment(" C.1.8.2: First Sender of This Case ");
                                                    $xml->endElement();//assignedEntity
                                                $xml->endElement();//author
                                            $xml->endElement();//controlActEvent
                                        $xml->endElement();//subjectOf2
                                    $xml->endElement();//observation
                                $xml->endElement();//outboundRelationship
                                $xml->startElement("outboundRelationship");
                                $xml->writeAttribute('typeCode','SPRT');
                                    $xml->startElement("relatedInvestigation");
                                    $xml->writeAttribute('classCode','INVSTG');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','2');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.22');
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
                                                            $xml->writeElement("streetAddressLine",$this->XMLvalue($caseId,31,1));
                                                            $xml->writeComment(" C.2.r.2.3: Reporter's Street ");
                                                            $xml->writeElement("city",$this->XMLvalue($caseId,32,1));
                                                            $xml->writeComment(" C.2.r.2.4: Reporter's City ");
                                                            $xml->writeElement("state",$this->XMLvalue($caseId,33,1));
                                                            $xml->writeComment(" C.2.r.2.5: Reporter's State or Province ");
                                                            $xml->writeElement("postalCode",$this->XMLvalue($caseId,34,1));
                                                            $xml->writeComment(" C.2.r.2.6: Reporter's Postcode ");
                                                        $xml->endElement();//addr
                                                        $xml->startElement("telecom");
                                                        $xml->writeAttribute('vlaue',$this->XMLvalue($caseId,229,1));
                                                        $xml->endElement();
                                                        $xml->writeComment(" C.2.r.2.7: Reporter's Telephone ");
                                                        $xml->startElement("assignedPerson");
                                                        $xml->writeAttribute('classCode','PSN');
                                                        $xml->writeAttribute('determinerCode','INSTANCE');
                                                            $xml->startElement("name");
                                                                $xml->writeElement("prefix",$this->XMLvalue($caseId,25,1));
                                                                $xml->writeComment(" C.2.r.1.1: Reporter's Title ");
                                                                $xml->writeElement("given",$this->XMLvalue($caseId,26,1));
                                                                $xml->writeComment(" C.2.r.1.2: Reporter's Given Name ");
                                                                $xml->writeElement("family",$this->XMLvalue($caseId,28,1));
                                                                $xml->writeComment(" C.2.r.1.4: Reporter's Family Name ");
                                                            $xml->endElement();//name
                                                            $xml->startElement("asQualifiedEntity");
                                                            $xml->writeAttribute('classCode','QUAL');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,36,1));
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.6');
                                                                $xml->endElement();
                                                                $xml->writeComment(" C.2.r.4: Qualification ");
                                                            $xml->endElement();//asQualifiedEntity
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
                                                        $xml->endElement();//assignedPerson
                                                        $xml->startElement("representedOrganization");
                                                        $xml->writeAttribute('classCode','ORG');
                                                        $xml->writeAttribute('determinerCode','INSTANCE');
                                                            $xml->writeElement("name",$this->XMLvalue($caseId,30,1));
                                                            $xml->writeComment(" C.2.r.2.2: Reporter's Department ");
                                                            $xml->startElement("assignedEntity");
                                                            $xml->writeAttribute('classCode','ASSIGNED');
                                                                $xml->startElement("representedOrganization");
                                                                $xml->writeAttribute('classCode','ORG');
                                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                                    $xml->writeElement("name",$this->XMLvalue($caseId,29,1));
                                                                    $xml->writeComment(" C.2.r.2.1: Reporter's Organisation ");
                                                                $xml->endElement();
                                                            $xml->endElement();//assignedEntity
                                                        $xml->endElement();//representedOrganization
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
                                                $xml->startElement("code");
                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,41,1));
                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.7');
                                                $xml->endElement();
                                                $xml->writeComment(" C.3.1: Sender Type ");
                                                $xml->startElement("addr");
                                                    $xml->writeElement("streetAddressLine",$this->XMLvalue($caseId,48,1));
                                                    $xml->writeComment(" C.3.4.1: Sender's Street Address ");
                                                    $xml->writeElement("city",$this->XMLvalue($caseId,49,1));
                                                    $xml->writeComment(" C.3.4.2: Sender's City ");
                                                    $xml->writeElement("postalCode",$this->XMLvalue($caseId,50,1));
                                                    $xml->writeComment(" C.3.4.3: Sender's State or Province ");
                                                    $xml->writeElement("postalCode",$this->XMLvalue($caseId,51,1));
                                                    $xml->writeComment(" C.3.4.4: Sender's Postcode ");
                                                $xml->endElement();//addr
                                                $xml->startElement("telecom");
                                                $xml->writeAttribute('vlaue',$this->XMLvalue($caseId,53,1));
                                                $xml->endElement();
                                                $xml->writeComment(" C.3.4.6: Sender's Telephone ");
                                                $xml->startElement("telecom");
                                                $xml->writeAttribute('vlaue',$this->XMLvalue($caseId,56,1));
                                                $xml->endElement();
                                                $xml->writeComment(" C.3.4.7: Sender's Fax ");
                                                $xml->startElement("telecom");
                                                $xml->writeAttribute('vlaue',$this->XMLvalue($caseId,59,1));
                                                $xml->endElement();
                                                $xml->writeComment(" C.3.4.8: Sender's E-mail Address ");
                                                $xml->startElement("assignedPerson");
                                                $xml->writeAttribute('classCode','PSN');
                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                    $xml->startElement("name");
                                                        $xml->writeElement("prefix",$this->XMLvalue($caseId,44,1));
                                                        $xml->writeComment(" C.3.3.2: Sender's Title ");
                                                        $xml->startElement("given");
                                                        $xml->writeAttribute('nullFlavor','MSK');//$this->XMLvalue($caseId,45,1)
                                                        $xml->endElement();
                                                        $xml->writeComment(" C.3.3.3: Sender's Given Name ");
                                                        $xml->startElement("family");
                                                        $xml->writeAttribute('nullFlavor','MSK');//$this->XMLvalue($caseId,47,1)
                                                        $xml->endElement();
                                                        $xml->writeComment(" C.3.3.5: Sender's Family Name ");
                                                    $xml->endElement();//name
                                                    $xml->startElement("asLocatedEntity");
                                                    $xml->writeAttribute('classCode','LOCE');
                                                        $xml->startElement("location");
                                                        $xml->writeAttribute('classCode','COUNTRY');
                                                        $xml->writeAttribute('determinerCode','INSTANCE');
                                                            $xml->startElement("code");
                                                            $xml->writeAttribute('code',$this->XMLvalue($caseId,52,1));
                                                            $xml->writeAttribute('codeSystem','1.0.3166.1.2.2');
                                                            $xml->endElement();
                                                            $xml->writeComment(" C.3.4.5: Sender's Country Code ");
                                                        $xml->endElement();
                                                    $xml->endElement();//asLocatedEntity
                                                $xml->endElement();//assignedPerson
                                                $xml->startElement("representedOrganization");
                                                $xml->writeAttribute('classCode','ORG');
                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                    $xml->writeElement("name",$this->XMLvalue($caseId,43,1));
                                                    $xml->writeComment(" C.3.3.1: Sender's Department ");
                                                    $xml->startElement("assignedEntity");
                                                    $xml->writeAttribute('classCode','ASSIGNED');
                                                        $xml->startElement("representedOrganization");
                                                        $xml->writeAttribute('classCode','ORG');
                                                        $xml->writeAttribute('determinerCode','INSTANCE');
                                                            $xml->writeElement("name",$this->XMLvalue($caseId,42,1));
                                                            $xml->writeComment(" C.3.2: Sender's Organisation ");
                                                        $xml->endElement();//representedOrganization
                                                    $xml->endElement();//assignedEntity
                                                $xml->endElement();//representedOrganization
                                            $xml->endElement();//assignedEntity
                                        $xml->endElement();//author
                                    $xml->endElement();//controlActEvent
                                $xml->endElement();//subjectOf1
                                $xml->startElement("subjectOf1");
                                $xml->writeAttribute('typeCode','SUBJ');
                                    $xml->startElement("controlActEvent");
                                    $xml->writeAttribute('classCode','CACT');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("id");
                                        $xml->writeAttribute('assigningAuthorityName',$this->XMLvalue($caseId,19,1));
                                        $xml->writeAttribute('extension',$this->XMLvalue($caseId,20,1));
                                        $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.3');
                                        $xml->endElement();
                                        $xml->writeComment(" C.1.9.1.r.1: Source(s) of the Case Identifier (repeat as necessary) ");
                                        $xml->writeComment(" C.1.9.1.r.2 Case Identifier(s) ");
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
                                        $xml->endElement();
                                        $xml->startElement("value");
                                        $xml->writeAttribute('xsi:type','CE');
                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,6,1));
                                        $xml->writeAttribute('codeSystem','="2.16.840.1.113883.3.989.2.1.1.2');
                                        $xml->endElement();
                                        $xml->writeComment(" C.1.3 Type of Report ");
                                    $xml->endElement();//investigationCharacteristic
                                $xml->endElement();//subjectOf2
                                $xml->startElement("subjectOf2");
                                $xml->writeAttribute('typeCode','SUBJ');
                                    $xml->startElement("investigationCharacteristic");
                                    $xml->writeAttribute('classCode','OBS');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','2');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.23');
                                        $xml->endElement();
                                        $xml->startElement("value");
                                        $xml->writeAttribute('xsi:type','BL');
                                        $xml->writeAttribute('nullFlavor',$this->XMLvalue($caseId,18,1));
                                        $xml->endElement();
                                        $xml->writeComment(" C.1.9.1 Other Case Identifiers in Previous Transmissions ");
                                    $xml->endElement();//investigationCharacteristic
                                $xml->endElement();//subjectOf2
                            $xml->endElement();//investigationEvent
                        $xml->endElement();//subject
                    $xml->endElement();//controlActProcess
                $xml->endElement();//PORR_IN049016UV 
                $xml->writeComment(" Message #1 ");
                $xml->startElement("receiver");
                $xml->writeAttribute('typeCode','RCV');
                    $xml->startElement("device");
                    $xml->writeAttribute('classCode','DEV');
                    $xml->writeAttribute('determinerCode','INSTANCE');
                        $xml->startElement("id");
                        $xml->writeAttribute('extension',$this->XMLvalue($caseId,1054,1));
                        $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.14');
                        $xml->endElement();
                        $xml->writeComment(" N.1.4: Batch Receiver Identifier ");
                    $xml->endElement();//device
                $xml->endElement();//receiver
                $xml->startElement("sender");
                $xml->writeAttribute('typeCode','SND');
                    $xml->startElement("device");
                    $xml->writeAttribute('classCode','DEV');
                    $xml->writeAttribute('determinerCode','INSTANCE');
                        $xml->startElement("id");
                        $xml->writeAttribute('extension',$this->XMLvalue($caseId,1053,1));
                        $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.13');
                        $xml->endElement();
                        $xml->writeComment(" N.1.3: Batch Sender Identifier ");
                    $xml->endElement();//device
                $xml->endElement();//sender
            $xml->endElement();//MCCI_IN200100UV01
        }
        else if(substr($this->XMLvalue($caseId,416,1),1,1)==1){
            $xml->startElement("MCCI_IN200100UV01");
            $xml->writeAttribute('ITSVersion','XML_1.0');
            $xml->writeAttribute('xsi:schemaLocation','urn:hl7-org:v3 MCCI_IN200100UV01.xsd');
            $xml->writeAttribute('xmlns','urn:hl7-org:v3');
            $xml->writeAttributeNS('xmlns','xsi', null,'http://www.w3.org/2001/XMLSchema-instance');
                $xml->startElement("id");
                    $xml->writeAttribute('extension',$this->XMLvalue($caseId,1052,1));
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
                $xml->endElement();
                $xml->writeComment(" N.1.1: Type of Messages in Batch ");
                $xml->writeComment(" Message #1 ");
                $xml->startElement("PORR_IN049016UV");
                    $xml->startElement("id");
                        $xml->writeAttribute('extension',$this->XMLvalue($caseId,1056,1));
                        $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.1');
                    $xml->endElement();
                    $xml->writeComment(" N.2.r.1: Message Identifier ");
                    $xml->startElement("creationTime");
                        $xml->writeAttribute('value',$this->XMLvalue($caseId,1059,1));
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
                            $xml->writeAttribute('extension',$this->XMLvalue($caseId,1058,1));
                            $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.12');
                            $xml->endElement();
                            $xml->writeComment(" N.2.r.3: Message Receiver Identifier ");
                        $xml->endElement();
                    $xml->endElement();
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
                        $xml->endElement();
                    $xml->endElement();
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
                                $xml->startElement("reference");
                                $xml->writeAttribute('typeCode','REFR');
                                    $xml->startElement("document");
                                    $xml->writeAttribute('classCode','DOC');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.27');
                                        $xml->writeAttribute('code','2');
                                        $xml->endElement();
                                        $xml->startElement("text");
                                        $xml->writeAttribute('mediaType',$this->XMLvalue($caseId,1002,1));
                                        $xml->writeAttribute('representation','B64');
                                        $xml->text($this->XMLvalue($caseId,1002,1));
                                        $xml->endElement();
                                        $xml->writeComment(" C.4.r.2: Included Documents ");
                                        $xml->writeElement('bibliographicDesignationText',$this->XMLvalue($caseId,37,1));
                                        $xml->writeComment(" C.4.r.1: Literature Reference(s)  ");
                                    $xml->endElement();//DOCUMENT
                                $xml->endElement();//reference
                                $xml->startElement("component");
                                $xml->writeAttribute('typeCode','COMP');
                                    $xml->startElement("adverseEventAssessment");
                                    $xml->writeAttribute('classCode','INVSTG');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("subject1");
                                        $xml->writeAttribute('typeCode','SBJ');
                                            $xml->startElement("primaryRole");
                                            $xml->writeAttribute('classCode','INVSBJ');
                                                $xml->startElement("player1");
                                                $xml->writeAttribute('classCode','PSN');
                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                    $xml->startElement("name");
                                                    $xml->writeAttribute('nullFlavor','MSK');//$this->XMLvalue($caseId,79,1)
                                                    $xml->endElement();
                                                    $xml->writeComment(" D.1: Patient (name or initials) ");
                                                    $xml->startElement("administrativeGenderCode");
                                                    $xml->writeAttribute('code',$this->XMLvalue($caseId,93,1));
                                                    $xml->writeAttribute('codeSystem','1.0.5218');
                                                    $xml->endElement();
                                                    $xml->writeComment(" D.5 Sex ");
                                                    $xml->startElement("birthTime");
                                                    $xml->writeAttribute('value',$this->XMLvalue($caseId,85,1));
                                                    $xml->endElement();
                                                    $xml->writeComment(" D.2.1: Date of Birth ");
                                                $xml->endElement();//player1
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("observation");
                                                    $xml->writeAttribute('classCode','OBS');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','3');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
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
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("observation");
                                                    $xml->writeAttribute('classCode','OBS');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','7');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                        $xml->endElement();
                                                        $xml->startElement("value");
                                                        $xml->writeAttribute('xsi:type','PQ');
                                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,91,1));
                                                        $xml->writeAttribute('unit','kg');
                                                        $xml->endElement();
                                                        $xml->writeComment(" D.3: Body Weight (kg) ");
                                                    $xml->endElement();//observation
                                                $xml->endElement();//subjectOf2
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("observation");
                                                    $xml->writeAttribute('classCode','OBS');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','17');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                        $xml->endElement();
                                                        $xml->startElement("value");
                                                        $xml->writeAttribute('xsi:type','PQ');
                                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,92,1));
                                                        $xml->writeAttribute('unit','cm');
                                                        $xml->endElement();
                                                        $xml->writeComment(" D.4: Height (cm) ");
                                                    $xml->endElement();//observation
                                                $xml->endElement();//subjectOf2
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("observation");
                                                        $xml->writeAttribute('classCode','OBS');
                                                        $xml->writeAttribute('moodCode','EVN');
                                                            $xml->startElement("code");
                                                            $xml->writeAttribute('code','22');
                                                            $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                            $xml->endElement();
                                                            $xml->startElement("value");
                                                            $xml->writeAttribute('xsi:type','TS');
                                                            $xml->writeAttribute('value',$this->XMLvalue($caseId,95,1));
                                                            $xml->endElement();
                                                            $xml->writeComment(" D.6: Last Menstrual Period Date ");
                                                        $xml->endElement();//observation
                                                    $xml->endElement();//subjectOf2
                                                    $xml->startElement("subjectOf2");
                                                    $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("organizer");
                                                    $xml->writeAttribute('classCode','GATEGORY');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','1');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.20');
                                                        $xml->endElement();//code
                                                        $xml->startElement("component");
                                                        $xml->writeAttribute('typeCode','COMP');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,97,1));
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.6.163');
                                                                $xml->writeAttribute('codeSystemVersion','15.0');
                                                                $xml->endElement();
                                                                $xml->writeComment(" D.7.1.r.1a: MedDRA Version for Medical History ");
                                                                $xml->writeComment(" D.7.1.r.1b: Medical History (disease / surgical procedure / etc.) (MedDRA code) ");
                                                                $xml->startElement("effectiveTime");
                                                                $xml->writeAttribute('xsi:type','IVL_TS');
                                                                    $xml->startElement("low");
                                                                    $xml->writeAttribute('value',$this->XMLvalue($caseId,99,1));
                                                                    $xml->endElement();
                                                                    $xml->writeComment(" D.7.1.r.2: Start Date ");
                                                                $xml->endElement();
                                                                $xml->startElement("inboundRelationship");
                                                                $xml->writeAttribute('typeCode','REFR');
                                                                    $xml->startElement("observation");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code','13');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                        $xml->endElement();
                                                                        $xml->startElement("value");
                                                                        $xml->writeAttribute('xsi:type','BL');
                                                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,100,1));
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" D.7.1.r.3: Continuing #1 ");
                                                                    $xml->endElement();//observation
                                                                $xml->endElement();//inboundRelationship
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//component  
                                                    $xml->endElement();//organizer
                                                $xml->endElement();//subjectOf2
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
                                                        $xml->endElement();
                                                        $xml->startElement("effectiveTime");
                                                        $xml->writeAttribute('xsi:type','SXPR_TS');
                                                            $xml->startElement("comp");
                                                            $xml->writeAttribute('xsi:type','IVL_TS');
                                                                $xml->startElement("low");
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,156,1));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.4: Date of Start of Reaction / Event ");
                                                                $xml->startElement("high");
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,158,1));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.5: Date of End of Reaction / Event ");
                                                            $xml->endElement();//comp
                                                            $xml->startElement("comp");
                                                            $xml->writeAttribute('xsi:type','IVL_TS');
                                                            $xml->writeAttribute('operator','A');
                                                                $xml->startElement("width");
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,159,1));
                                                                $xml->writeAttribute('unit',$this->XMLvalue($caseId,160,1));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.6a: Duration of Reaction / Event (number) ");
                                                                $xml->writeComment(" E.i.6b: Duration of Reaction / Event (unit) ");
                                                            $xml->endElement();//comp
                                                        $xml->endElement();//effectiveTime
                                                        $xml->startElement("value");
                                                        $xml->writeAttribute('xsi:type','CE');
                                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,151,1));
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.6.163');
                                                        $xml->writeAttribute('codeSystemVersion','15.0');
                                                        $xml->writeComment(" E.i.2.1a: MedDRA Version for Reaction / Event ");
                                                        $xml->writeComment(" E.i.2.1b: Reaction / Event (MedDRA code) ");
                                                            $xml->StartElement("originalText"); 
                                                            $xml->WriteAttribute("language",$this->XMLvalue($caseId,1017,1)); 
                                                            $xml->text($this->XMLvalue($caseId,149,1));
                                                            $xml->EndElement(); 
                                                            $xml->writeComment(" E.i.1.1a: Reaction / Event as Reported by the Primary Source in Native Language ");
                                                            $xml->writeComment(" E.i.1.1b: Reaction / Event as Reported by the Primary Source Language ");
                                                        $xml->endElement();//value
                                                        $xml->startElement("location");
                                                        $xml->writeAttribute('typeCode','LOC');
                                                            $xml->startElement("locatedEntity");
                                                            $xml->writeAttribute('classCode','LOCE');
                                                                $xml->startElement("locatedPlace");
                                                                $xml->writeAttribute('classCode','COUNTRY');
                                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                                    $xml->startElement("code");
                                                                    $xml->writeAttribute('code',$this->XMLvalue($caseId,1026,1));
                                                                    $xml->writeAttribute('codeSystem','1.0.3166.1.2.2');
                                                                    $xml->endElement();
                                                                    $xml->writeComment(" E.i.9: Identification of the Country Where the Reaction / Event Occurred ");
                                                                $xml->endElement();
                                                            $xml->endElement();//locatedEntity
                                                        $xml->endElement();//location
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','PERT');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','30');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','ED');
                                                                $xml->text($this->XMLvalue($caseId,1018,1));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.1.2: Reaction / Event as Reported by the Primary Source for Translation ");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','PERT');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','37');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','CE');
                                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,154,1));
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.10');
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.3.1: Term Highlighted by the Reporter ");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','PERT');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','34');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','BL');
                                                                $xml->writeAttribute('nullFlavor','NI');//$this->XMLvalue($caseId,1019,3)
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.3.2a: Results in Death ");
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
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','BL');
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,1020,2));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.3.2b: Life Threatening ");
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
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','BL');
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,1021,1));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.3.2c: Caused / Prolonged Hospitalisation ");
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
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','BL');
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,1022,1));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.3.2d: Disabling / Incapacitating ");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','REFR');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','12');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','BL');
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,1023,1));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.3.2e: Congenital Anomaly / Birth Defect ");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','REFR');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','26');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','BL');
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,1024,1));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.3.2f: Other Medically Important Condition ");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','REFR');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','27');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->endElement();
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','CE');
                                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,165,1));
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.11');
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.7: Outcome of Reaction / Event at the Time of Last Observation ");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                    $xml->endElement();//observation
                                                $xml->endElement();//subjectOf2
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("organizer");
                                                    $xml->writeAttribute('classCode','CATEGORY');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','3');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.20');
                                                        $xml->endElement();
                                                        $xml->startElement("component");
                                                        $xml->writeAttribute('typeCode','COMP');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,1028,1));
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.6.163');
                                                                $xml->writeAttribute('codeSystemVersion','15.0');
                                                                $xml->writeComment(" F.r.2.2a: MedDRA Version for Test Name #1 ");
                                                                $xml->writeComment(" F.r.2.2b: Test Name (MedDRA code) #1 ");
                                                                    $xml->writeElement('originalText',$this->XMLvalue($caseId,168,1));
                                                                    $xml->writeComment(" F.r.2.1: Test Name (free text) #1 ");
                                                                $xml->endElement();//code
                                                                $xml->startElement("effectiveTime");
                                                                $xml->writeAttribute('value ',$this->XMLvalue($caseId,167,1));
                                                                $xml->endElement();//effectiveTime
                                                                $xml->writeComment(" F.r.1: Test Date #1 ");
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','IVL_PQ');
                                                                    $xml->startElement("center");
                                                                    $xml->writeAttribute('value',$this->XMLvalue($caseId,1030,1));
                                                                    $xml->writeAttribute('unit',$this->XMLvalue($caseId,170,1));
                                                                    $xml->writeComment(" F.r.3.2: Test Result (value / qualifier) #1 ");
                                                                    $xml->writeComment(" F.r.3.3: Test Result (unit) #1 ");
                                                                    $xml->endElement();
                                                                $xml->endElement();
                                                                $xml->startElement("referenceRange");
                                                                $xml->writeAttribute('typeCode','REFV');
                                                                    $xml->startElement("observationRange");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN.CRT');
                                                                        $xml->startElement("value");
                                                                        $xml->writeAttribute('xsi:type','PQ');
                                                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,172,1));
                                                                        $xml->writeAttribute('unit','mg/dl');
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" F.r.5:  Normal High Value #1 ");
                                                                        $xml->startElement("interpretationCode");
                                                                        $xml->writeAttribute('code','H');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.5.83');
                                                                        $xml->endElement();
                                                                    $xml->endElement();//observationRange
                                                                $xml->endElement();//referenceRange
                                                                $xml->startElement("outboundRelationship2");
                                                                $xml->writeAttribute('typeCode','REFR');
                                                                    $xml->startElement("observation");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code','25');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                        $xml->endElement();
                                                                        $xml->startElement("value");
                                                                        $xml->writeAttribute('xsi:type','BL');
                                                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,173,1));
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" F.r.7: More Information Available ");
                                                                    $xml->endElement();//observation
                                                                $xml->endElement();//outboundRelationship2
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//component
                                                        $xml->startElement("component");
                                                        $xml->writeAttribute('typeCode','COMP');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.6.163');
                                                                    $xml->writeElement('originalText',$this->XMLvalue($caseId,168,1));
                                                                    $xml->writeComment(" F.r.2.1: Test Name (free text) ");
                                                                $xml->endElement();
                                                                $xml->startElement("effectiveTime");
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,167,1));
                                                                $xml->endElement();//effectiveTime
                                                                $xml->writeComment(" F.r.1: Test Date #2 ");
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','ED');
                                                                $xml->text($this->XMLvalue($caseId,169,1));
                                                                $xml->endElement();//value
                                                                $xml->writeComment(" F.r.3.4: Result Unstructured Data (free text) ");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//component
                                                    $xml->endElement();//organizer
                                                $xml->endElement();//subjectOf2
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("organizer");
                                                    $xml->writeAttribute('classCode','CATEGORY');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','4');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.20');
                                                        $xml->endElement();
                                                        $xml->writeComment(" G.k Drug(s) Information (repeat as necessary) ");
                                                        $xml->startElement("component");
                                                        $xml->writeAttribute('typeCode','COMP');
                                                            $xml->startElement("substanceAdministration");
                                                            $xml->writeAttribute('classCode','SBADM');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("id");
                                                                $xml->writeAttribute('root','3c91b4d5-e039-4a7a-9c30-67671b0ef9e4');
                                                                $xml->endElement();
                                                                $xml->startElement("consumable");
                                                                $xml->writeAttribute('typeCode','CSM');
                                                                    $xml->startElement("instanceOfKind");
                                                                    $xml->writeAttribute('classCode','INST');
                                                                        $xml->startElement("kindOfProduct");
                                                                        $xml->writeAttribute('classCode','MMAT');
                                                                        $xml->writeAttribute('determinerCode','KIND');
                                                                            $xml->startElement("code");
                                                                            $xml->writeAttribute('code',$this->XMLvalue($caseId,1033,1));
                                                                            $xml->writeAttribute('codeSystem','TBD-MPID');
                                                                            $xml->writeAttribute('codeSystemVersion',$this->XMLvalue($caseId,1032,1));
                                                                            $xml->endElement();
                                                                            $xml->writeComment(" Example MPID and Version ");
                                                                            $xml->writeComment(" G.k.2.1.1a: MPID Version Date / Number ");
                                                                            $xml->writeComment(" G.k.2.1.1b: Medicinal Product Identifier (MPID) ");
                                                                            $xml->writeElement('name',$this->XMLvalue($caseId,176,1));
                                                                            $xml->writeComment(" G.k.2.2: Medicinal Product Name as Reported by the Primary Source ");
                                                                            $xml->startElement("asManufacturedProduct");
                                                                            $xml->writeAttribute('classCode','MANU');
                                                                                $xml->startElement("subjectOf");
                                                                                $xml->writeAttribute('typeCode','SBJ');
                                                                                    $xml->startElement("approval");
                                                                                    $xml->writeAttribute('classCode','CNTRCT');
                                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                                        $xml->startElement("id");
                                                                                        $xml->writeAttribute('extension',$this->XMLvalue($caseId,180,1));
                                                                                        $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.4');
                                                                                        $xml->endElement();
                                                                                        $xml->writeComment(" G.k.3.1: Authorization / Application Number ");
                                                                                        $xml->startElement("holder");
                                                                                        $xml->writeAttribute('typeCode','HLD');
                                                                                            $xml->startElement("role");
                                                                                            $xml->writeAttribute('classCode','HLD');
                                                                                                $xml->startElement("playingOrganization");
                                                                                                $xml->writeAttribute('classCode','ORG');
                                                                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                                                                    $xml->writeElement('name',$this->XMLvalue($caseId,182,1));
                                                                                                    $xml->writeComment(" G.k.3.3: Name of Holder / Applicant ");
                                                                                                $xml->endElement();//playingOrganization
                                                                                            $xml->endElement();//role
                                                                                        $xml->endElement();//holder
                                                                                        $xml->startElement("author");
                                                                                        $xml->writeAttribute('typeCode','AUT');
                                                                                            $xml->startElement("territorialAuthority");
                                                                                            $xml->writeAttribute('classCode','TERR');
                                                                                                $xml->startElement("territory");
                                                                                                $xml->writeAttribute('classCode','NAT');
                                                                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                                                                    $xml->startElement("code");
                                                                                                    $xml->writeAttribute('code',$this->XMLvalue($caseId,181,1));
                                                                                                    $xml->writeAttribute('codeSystem','1.0.3166.1.2.2');
                                                                                                    $xml->endElement();
                                                                                                    $xml->writeComment(" G.k.3.2: Country of Authorization / Application ");
                                                                                                $xml->endElement();//territory
                                                                                            $xml->endElement();//territorialAuthority
                                                                                        $xml->endElement();//author
                                                                                    $xml->endElement();//approval
                                                                                $xml->endElement();//subjectOf
                                                                            $xml->endElement();//asManufacturedProduct
                                                                            $xml->startElement("ingredient");
                                                                            $xml->writeAttribute('classCode','ACTI');
                                                                                $xml->startElement("quantity");
                                                                                    $xml->startElement("numerator");
                                                                                    $xml->writeAttribute('value',$this->XMLvalue($caseId,1038,1)); 
                                                                                    $xml->writeAttribute('unit',$this->XMLvalue($caseId,1039,3));   
                                                                                    $xml->endElement();
                                                                                    $xml->writeComment(" G.k.2.3.r.3a: Strength (number) ");
                                                                                    $xml->writeComment(" G.k.2.3.r.3b: Strength (unit) ");
                                                                                    $xml->startElement("denominator");
                                                                                    $xml->writeAttribute('value','1');   
                                                                                    $xml->endElement();
                                                                                $xml->endElement();
                                                                                $xml->startElement("ingredientSubstance");
                                                                                $xml->writeAttribute('classCode','MMAT');
                                                                                $xml->writeAttribute('determinerCode','KIND');
                                                                                    $xml->startElement("code");
                                                                                    $xml->writeAttribute('code',$this->XMLvalue($caseId,1037,1));
                                                                                    $xml->writeAttribute('codeSystem','TBD-Substance');
                                                                                    $xml->writeAttribute('codeSystemVersion',$this->XMLvalue($caseId,1036,1));
                                                                                    $xml->endElement();
                                                                                    $xml->writeComment(" Example Substance ID and Version ");
                                                                                    $xml->writeComment(" G.k.2.3.r.2a Substance / Specified Substance TermID Version Date/Number ");
                                                                                    $xml->writeComment(" G.k.2.3.r.2b: Substance / Specified Substance TermID ");
                                                                                    $xml->writeElement('name',$this->XMLvalue($caseId,177,1));
                                                                                    $xml->writeComment(" G.k.2.3.r.1: Substance / Specified Substance Name ");
                                                                                $xml->endElement();
                                                                            $xml->endElement();
                                                                        $xml->endElement();//kindOfProduct
                                                                        $xml->startElement("subjectOf");
                                                                        $xml->writeAttribute('typeCode','SBJ');
                                                                            $xml->startElement("productEvent");
                                                                            $xml->writeAttribute('classCode','ACT');
                                                                            $xml->writeAttribute('moodCode','EVN');
                                                                                $xml->startElement("code");
                                                                                $xml->writeAttribute('code','1');
                                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.18');
                                                                                $xml->endElement();
                                                                                $xml->startElement("performer");
                                                                                $xml->writeAttribute('typeCode','PRF');
                                                                                    $xml->startElement("assignedEntity");
                                                                                    $xml->writeAttribute('classCode','ASSIGNED');
                                                                                        $xml->startElement("representedOrganization");
                                                                                        $xml->writeAttribute('classCode','ORG');
                                                                                        $xml->writeAttribute('determinerCode','INSTANCE');
                                                                                            $xml->startElement("addr");
                                                                                                $xml->writeElement("country",$this->XMLvalue($caseId,178,1));
                                                                                                $xml->writeComment(" G.k.2.4: Identification of the Country Where the Drug Was Obtained ");
                                                                                            $xml->endElement();
                                                                                        $xml->endElement();
                                                                                    $xml->endElement();
                                                                                $xml->endElement();
                                                                            $xml->endElement();
                                                                        $xml->endElement();//subjectOf
                                                                    $xml->endElement();//instanceOfKind
                                                                $xml->endElement();//consumable
                                                                $xml->startElement("outboundRelationship2");
                                                                $xml->writeAttribute('typeCode','COMP');
                                                                $xml->writeComment(" G.k.4.r: Dosage and Relevant Information (repeat as necessary) ");
                                                                    $xml->startElement("substanceAdministration");
                                                                    $xml->writeAttribute('classCode','SBADM');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("effectiveTime");
                                                                        $xml->writeAttribute('xsi:type','SXPR_TS');
                                                                            $xml->startElement("comp");
                                                                            $xml->writeAttribute('xsi:type','PIVL_TS');
                                                                                $xml->startElement("period");
                                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,186,1));
                                                                                $xml->writeAttribute('unit',$this->XMLvalue($caseId,187,1));
                                                                                $xml->endElement();
                                                                                $xml->writeComment(" G.k.4.r.2: Number of Units in the Interval ");
                                                                                $xml->writeComment(" G.k.4.r.3: Definition of the Time Interval Unit ");
                                                                            $xml->endElement();//comp
                                                                            $xml->startElement("comp");
                                                                            $xml->writeAttribute('xsi:type','IVL_TS');
                                                                            $xml->writeAttribute('operator','A');
                                                                                $xml->startElement("low");
                                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,199,1));
                                                                                $xml->endElement();
                                                                                $xml->writeComment(" G.k.4.r.4: Date and Time of Start of Drug ");
                                                                                $xml->startElement("high");
                                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,205,1));
                                                                                $xml->endElement();
                                                                                $xml->writeComment(" G.k.4.r.5: Date and Time of Last Administration ");
                                                                            $xml->endElement();//comp
                                                                        $xml->endElement();//effectiveTime
                                                                        $xml->startElement("routeCode");
                                                                        $xml->writeAttribute('code','048');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.14');
                                                                        $xml->writeComment(" G.k.4.r.10.2b: Route of Administration TermID ");
                                                                            $xml->writeElement("originalText",$this->XMLvalue($caseId,183,1));
                                                                        $xml->endElement();//routeCode
                                                                        $xml->startElement("doesQuantity");
                                                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,183,1));
                                                                            $xml->writeAttribute('unit',$this->XMLvalue($caseId,184,1));
                                                                        $xml->endElement();//doesQuantity
                                                                        $xml->writeComment(" G.k.4.r.1a Dose (number) ");
                                                                        $xml->writeComment(" G.k.4.r.1b: Dose (unit) ");
                                                                        $xml->startElement("consumable");
                                                                        $xml->writeAttribute('type','CSM');
                                                                            $xml->startElement("instanceOfKind");
                                                                            $xml->writeAttribute('classCode','INST');
                                                                                $xml->startElement("kindOfProduct");
                                                                                $xml->writeAttribute('classCode','MMAT');
                                                                                $xml->writeAttribute('determinerCode','KIND');
                                                                                    $xml->startElement("formCode");
                                                                                        $xml->writeElement('originalText',$this->XMLvalue($caseId,191,1));
                                                                                        $xml->writeComment(" G.k.4.r.9.1: Pharmaceutical Dose Form (free text) ");
                                                                                    $xml->endElement();//formCode
                                                                                $xml->endElement();//kindOfProduct
                                                                            $xml->endElement();//instanceOfKind
                                                                        $xml->endElement();//consumable
                                                                    $xml->endElement();//substanceAdministration
                                                                $xml->endElement();//outboundRelationship2
                                                                $xml->startElement("outboundRelationship2");
                                                                $xml->writeAttribute('typeCode','PERT');
                                                                    $xml->startElement("observation");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code','31');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                        $xml->endElement();
                                                                        $xml->startElement("value");
                                                                        $xml->writeAttribute('xsi:type','CE');
                                                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,209,"1"));
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.16');
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" G.k.9.i.4: Did Reaction Recur on Re-administration? ");
                                                                        $xml->startElement("outboundRelationship1");
                                                                        $xml->writeAttribute('typeCode','REFR');
                                                                            $xml->startElement("actReference");
                                                                            $xml->writeAttribute('classCode','OBS');
                                                                            $xml->writeAttribute('moodCode','EVN');
                                                                                $xml->startElement("id");
                                                                                $xml->writeAttribute('root','154eb889-958b-45f2-a02f-42d4d6f4657f');
                                                                                $xml->endElement();
                                                                            $xml->endElement();
                                                                        $xml->endElement();
                                                                    $xml->endElement();//observation
                                                                $xml->endElement();//outboundRelationship2
                                                                $xml->startElement("inboundRelationship");
                                                                $xml->writeAttribute('typeCode','RSON');
                                                                    $xml->startElement("observation");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code','19');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                        $xml->endElement();
                                                                        $xml->startElement("value");
                                                                        $xml->writeAttribute('xsi:type','CE');
                                                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,196,1));
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.6.163');
                                                                        $xml->writeAttribute('codeSystemVersion','15.0');
                                                                        $xml->writeComment(" G.k.7.r.2a: MedDRA Version for Indication ");
                                                                        $xml->writeComment(" G.k.7.r.2b: Indication (MedDRA code) ");
                                                                            $xml->writeElement('originalText',$this->XMLvalue($caseId,1047,1));
                                                                            $xml->writeComment(" G.k.7.r.1: Indication as Reported by the Primary Source ");
                                                                        $xml->endElement();
                                                                        $xml->startElement("performer");
                                                                        $xml->writeAttribute('typeCode','PRF');
                                                                            $xml->startElement("assignedEntity");
                                                                            $xml->writeAttribute('classCode','ASSIGNED');
                                                                                $xml->startElement("code");
                                                                                $xml->writeAttribute('code','3');
                                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.21');
                                                                                $xml->endElement();
                                                                            $xml->endElement();
                                                                        $xml->endElement();//performer
                                                                    $xml->endElement();//observation       
                                                                $xml->endElement();//inboundRelationship
                                                                $xml->startElement("inboundRelationship");
                                                                $xml->writeAttribute('typeCode','CAUS');
                                                                    $xml->startElement("act");
                                                                    $xml->writeAttribute('classCode','ACT');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,208,1));
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.15');
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" G.k.8: Action(s) Taken with Drug ");
                                                                    $xml->endElement();//act       
                                                                $xml->endElement();//inboundRelationship
                                                            $xml->endElement();//substanceAdministration
                                                        $xml->endElement();//component
                                                    $xml->endElement();//organizer
                                                $xml->endElement();//subjectOf2
                                            $xml->endElement();//primaryRole
                                        $xml->endElement();//SUBJECT1
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
                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,175,1));
                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.13');
                                                $xml->endElement();
                                                $xml->writeComment(" G.k.1: Characterisation of Drug Role ");
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
                                        $xml->startElement("component");
                                        $xml->writeAttribute('typeCode','COMP');
                                            $xml->startElement("causalityAssessment");
                                            $xml->writeAttribute('classCode','OBS');
                                            $xml->writeAttribute('moodCode','EVN');
                                                $xml->startElement("code");
                                                $xml->writeAttribute('code','39');
                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                $xml->endElement();
                                                $xml->startElement("value");
                                                $xml->writeAttribute('xsi:type','CE');
                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,216,"1"));
                                                $xml->endElement();
                                                $xml->writeComment(" G.k.9.i.2.r.3: Result of Assessment ");
                                                $xml->startElement("methodCode");
                                                    $xml->writeElement("originalText",$this->XMLvalue($caseId,215,"1"));
                                                    $xml->writeComment(" G.k.9.i.2.r.2: Method of Assessment ");
                                                $xml->endElement();//methodCode
                                                $xml->startElement("author");
                                                $xml->writeAttribute('typeCode','AUT');
                                                    $xml->startElement("assignedEntity");
                                                    $xml->writeAttribute('classCode','ASSIGNED');
                                                        $xml->startElement("code");
                                                        $xml->writeElement('originalText',$this->XMLvalue($caseId,214,"1"));
                                                        $xml->writeComment(" G.k.9.i.2.r.1: Source of Assessment ");
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
                                                        $xml->endElement();
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
                                        $xml->startElement("component1");
                                        $xml->writeAttribute('typeCode','COMP');
                                            $xml->startElement("observationEvent");
                                            $xml->writeAttribute('classCode','OBS');
                                            $xml->writeAttribute('moodCode','EVN');
                                                $xml->startElement("code");
                                                $xml->writeAttribute('code','10');
                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
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
                                                        $xml->endElement();//code
                                                    $xml->endElement();//assignedEntity
                                                $xml->endElement();//author
                                            $xml->endElement();//observationEvent
                                        $xml->endElement();//component1
                                        $xml->startElement("component1");
                                        $xml->writeAttribute('typeCode','COMP');
                                            $xml->startElement("observationEvent");
                                            $xml->writeAttribute('classCode','OBS');
                                            $xml->writeAttribute('moodCode','EVN');
                                                $xml->startElement("code");
                                                $xml->writeAttribute('code','10');
                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                $xml->endElement();
                                                $xml->startElement("value");
                                                $xml->writeAttribute('xsi:type','ED');
                                                $xml->text($this->XMLvalue($caseId,222,1));
                                                $xml->endElement();
                                                $xml->writeComment(" H.4: Sender's Comments ");
                                                $xml->startElement("author");
                                                $xml->writeAttribute('typeCode','AUT');
                                                    $xml->startElement("assignedEntity");
                                                    $xml->writeAttribute('classCode','ASSIGNED');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','1');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.21');
                                                        $xml->writeComment(" sender ");
                                                        $xml->endElement();//code
                                                    $xml->endElement();//assignedEntity
                                                $xml->endElement();//author
                                            $xml->endElement();//observationEvent
                                        $xml->endElement();//component1
                                    $xml->endElement();//adverseEventAssessment
                                $xml->endElement();//component
                                $xml->startElement("component");
                                $xml->writeAttribute('typeCode','COMP');
                                    $xml->startElement("observationEvent");
                                    $xml->writeAttribute('classCode','OBS');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','1');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                        $xml->endElement();
                                        $xml->startElement("value");
                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,13,1));
                                        $xml->writeAttribute('xsi:type','BL');
                                        $xml->writeComment(' C.1.6.1: Are Additional Documents Available? ');
                                        $xml->endElement();
                                    $xml->endElement();//observationEvent
                                $xml->endElement();//component
                                $xml->startElement("component");
                                $xml->writeAttribute('typeCode','COMP');
                                    $xml->startElement("observationEvent");
                                    $xml->writeAttribute('classCode','OBS');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','23');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                        $xml->endElement();
                                        $xml->startElement("value");
                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,15,1));
                                        $xml->writeAttribute('xsi:type','BL');
                                        $xml->writeComment(' C.1.7: Does This Case Fulfill the Local Criteria for an Expedited Report? ');
                                        $xml->endElement();
                                    $xml->endElement();//observationEvent
                                $xml->endElement();//component
                                $xml->startElement("component");
                                $xml->writeAttribute('typeCode','COMP');
                                    $xml->startElement("observationEvent");
                                    $xml->writeAttribute('classCode','OBS');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','36');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                        $xml->endElement();
                                        $xml->writeComment(" summaryAndComment ");
                                        $xml->startElement("value");
                                        $xml->writeAttribute('language',$this->XMLvalue($caseId,1050,1));
                                        $xml->writeAttribute('xsi:type','ED');
                                        $xml->text($this->XMLvalue($caseId,1049,1));
                                        $xml->endElement();
                                        $xml->writeComment(" H.5.r.1a: Case Summary and Reporter's Comments Text ");
                                        $xml->writeComment(" H.5.r.1b: Case Summary and Reporter's Comments Language ");
                                        $xml->startElement("author");
                                        $xml->writeAttribute('typeCode','AUT');
                                            $xml->startElement("assignedEntity");
                                            $xml->writeAttribute('classCode','ASSIGNED');
                                                $xml->startElement("code");
                                                $xml->writeAttribute('code','2');
                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.21');
                                                $xml->endElement();//code
                                            $xml->endElement();//assignedEntity
                                        $xml->endElement();//author
                                    $xml->endElement();//observationEvent
                                $xml->endElement();//component
                                $xml->startElement("outboundRelationship");
                                $xml->writeAttribute('typeCode','SPRT');
                                    $xml->startElement("relatedInvestigation");
                                    $xml->writeAttribute('classCode','INVSTG');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','1');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.22');
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
                                                        $xml->endElement();//code
                                                        $xml->writeComment(" C.1.8.2: First Sender of This Case ");
                                                    $xml->endElement();//assignedEntity
                                                $xml->endElement();//author
                                            $xml->endElement();//controlActEvent
                                        $xml->endElement();//subjectOf2
                                    $xml->endElement();//observation
                                $xml->endElement();//outboundRelationship
                                $xml->startElement("outboundRelationship");
                                $xml->writeAttribute('typeCode','SPRT');
                                    $xml->startElement("priorityNumber");
                                    $xml->writeAttribute('value',$this->XMLvalue($caseId,227,1));
                                    $xml->endElement();
                                    $xml->writeComment(' C.2.r.5: Primary Source for Regulatory Purposes ');
                                    $xml->startElement("relatedInvestigation");
                                    $xml->writeAttribute('classCode','INVSTG');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','2');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.22');
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
                                                            $xml->writeElement("streetAddressLine",$this->XMLvalue($caseId,31,1));
                                                            $xml->writeComment(" C.2.r.2.3: Reporter's Street #1 ");
                                                            $xml->writeElement("city",$this->XMLvalue($caseId,32,1));
                                                            $xml->writeComment(" C.2.r.2.4: Reporter's City #1 ");
                                                            $xml->writeElement("state",$this->XMLvalue($caseId,33,1));
                                                            $xml->writeComment(" C.2.r.2.5: Reporter's State or Province #1 ");
                                                            $xml->writeElement("postalCode",$this->XMLvalue($caseId,34,1));
                                                            $xml->writeComment(" C.2.r.2.6: Reporter's Postcode #1 ");
                                                        $xml->endElement();//addr
                                                        $xml->startElement("telecom");
                                                        $xml->writeAttribute('vlaue',$this->XMLvalue($caseId,229,1));
                                                        $xml->endElement();
                                                        $xml->writeComment(" C.2.r.2.7: Reporter's Telephone #1 ");
                                                        $xml->startElement("assignedPerson");
                                                        $xml->writeAttribute('classCode','PSN');
                                                        $xml->writeAttribute('determinerCode','INSTANCE');
                                                            $xml->startElement("name");
                                                                $xml->writeElement("prefix",$this->XMLvalue($caseId,25,1));
                                                                $xml->writeComment(" C.2.r.1.1: Reporter's Title #1 ");
                                                                $xml->writeElement("given",$this->XMLvalue($caseId,26,1));
                                                                $xml->writeComment(" C.2.r.1.2: Reporter's Given Name #1 ");
                                                                $xml->writeElement("family",$this->XMLvalue($caseId,28,1));
                                                                $xml->writeComment(" C.2.r.1.4: Reporter's Family Name #1 ");
                                                            $xml->endElement();//name
                                                            $xml->startElement("asQualifiedEntity");
                                                            $xml->writeAttribute('classCode','QUAL');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,36,1));
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.6');
                                                                $xml->endElement();
                                                                $xml->writeComment(" C.2.r.4: Qualification #1 ");
                                                            $xml->endElement();//asQualifiedEntity
                                                            $xml->startElement("asLocatedEntity");
                                                            $xml->writeAttribute('classCode','LOCE');
                                                                $xml->startElement("location");
                                                                $xml->writeAttribute('classCode','COUNTRY');
                                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                                    $xml->startElement("code");
                                                                    $xml->writeAttribute('code',$this->XMLvalue($caseId,35,1));
                                                                    $xml->writeAttribute('codeSystem','1.0.3166.1.2.2');
                                                                    $xml->endElement();
                                                                    $xml->writeComment(" C.2.r.3: Reporter's Country Code #1 ");
                                                                $xml->endElement();
                                                            $xml->endElement();//asQualifiedEntity
                                                        $xml->endElement();//assignedPerson
                                                        $xml->startElement("representedOrganization");
                                                        $xml->writeAttribute('classCode','ORG');
                                                        $xml->writeAttribute('determinerCode','INSTANCE');
                                                            $xml->writeElement("name",$this->XMLvalue($caseId,30,1));
                                                            $xml->writeComment(" C.2.r.2.2: Reporter's Department #1 ");
                                                            $xml->startElement("assignedEntity");
                                                            $xml->writeAttribute('classCode','ASSIGNED');
                                                                $xml->startElement("representedOrganization");
                                                                $xml->writeAttribute('classCode','ORG');
                                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                                    $xml->writeElement("name",$this->XMLvalue($caseId,29,1));
                                                                    $xml->writeComment(" C.2.r.2.1: Reporter's Organisation #1 ");
                                                                $xml->endElement();
                                                            $xml->endElement();//assignedEntity
                                                        $xml->endElement();//representedOrganization
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
                                                $xml->startElement("code");
                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,41,1));
                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.7');
                                                $xml->endElement();
                                                $xml->writeComment(" C.3.1: Sender Type ");
                                                $xml->startElement("addr");
                                                    $xml->writeElement("streetAddressLine",$this->XMLvalue($caseId,48,1));
                                                    $xml->writeComment(" C.3.4.1: Sender's Street Address ");
                                                    $xml->writeElement("city",$this->XMLvalue($caseId,49,1));
                                                    $xml->writeComment(" C.3.4.2: Sender's City ");
                                                    $xml->writeElement("postalCode",$this->XMLvalue($caseId,51,1));
                                                    $xml->writeComment(" C.3.4.4: Sender's Postcode ");
                                                $xml->endElement();//addr
                                                $xml->startElement("telecom");
                                                $xml->writeAttribute('vlaue',$this->XMLvalue($caseId,53,1));
                                                $xml->endElement();
                                                $xml->writeComment(" C.3.4.6: Sender's Telephone ");
                                                $xml->startElement("telecom");
                                                $xml->writeAttribute('vlaue',$this->XMLvalue($caseId,56,1));
                                                $xml->endElement();
                                                $xml->writeComment(" C.3.4.7: Sender's Fax ");
                                                $xml->startElement("telecom");
                                                $xml->writeAttribute('vlaue',$this->XMLvalue($caseId,59,1));
                                                $xml->endElement();
                                                $xml->writeComment(" C.3.4.8: Sender's E-mail Address ");
                                                $xml->startElement("assignedPerson");
                                                $xml->writeAttribute('classCode','PSN');
                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                    $xml->startElement("name");
                                                        $xml->writeElement("prefix",$this->XMLvalue($caseId,44,1));
                                                        $xml->writeComment(" C.3.3.2: Sender's Title ");
                                                        $xml->startElement("given");
                                                        $xml->writeAttribute('nullFlavor','MSK');//$this->XMLvalue($caseId,45,1)
                                                        $xml->endElement();
                                                        $xml->writeComment(" C.3.3.3: Sender's Given Name ");
                                                        $xml->startElement("family");
                                                        $xml->writeAttribute('nullFlavor','MSK');//$this->XMLvalue($caseId,47,1)
                                                        $xml->endElement();
                                                        $xml->writeComment(" C.3.3.5: Sender's Family Name ");
                                                    $xml->endElement();//name
                                                    $xml->startElement("asLocatedEntity");
                                                    $xml->writeAttribute('classCode','LOCE');
                                                        $xml->startElement("location");
                                                        $xml->writeAttribute('classCode','COUNTRY');
                                                        $xml->writeAttribute('determinerCode','INSTANCE');
                                                            $xml->startElement("code");
                                                            $xml->writeAttribute('code',$this->XMLvalue($caseId,52,1));
                                                            $xml->writeAttribute('codeSystem','1.0.3166.1.2.2');
                                                            $xml->endElement();
                                                            $xml->writeComment(" C.3.4.5: Sender's Country Code ");
                                                        $xml->endElement();
                                                    $xml->endElement();//asLocatedEntity
                                                $xml->endElement();//assignedPerson
                                                $xml->startElement("representedOrganization");
                                                $xml->writeAttribute('classCode','ORG');
                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                    $xml->writeElement("name",$this->XMLvalue($caseId,43,1));
                                                    $xml->writeComment(" C.3.3.1: Sender's Department ");
                                                    $xml->startElement("assignedEntity");
                                                    $xml->writeAttribute('classCode','ASSIGNED');
                                                        $xml->startElement("representedOrganization");
                                                        $xml->writeAttribute('classCode','ORG');
                                                        $xml->writeAttribute('determinerCode','INSTANCE');
                                                            $xml->writeElement("name",$this->XMLvalue($caseId,42,1));
                                                            $xml->writeComment(" C.3.2: Sender's Organisation ");
                                                        $xml->endElement();//representedOrganization
                                                    $xml->endElement();//assignedEntity
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
                                        $xml->endElement();
                                        $xml->writeComment("ichReportType");
                                        $xml->startElement("value");
                                        $xml->writeAttribute('xsi:type','CE');
                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,6,1));
                                        $xml->writeAttribute('codeSystem','="2.16.840.1.113883.3.989.2.1.1.2');
                                        $xml->endElement();
                                        $xml->writeComment(" C.1.3 Type of Report ");
                                    $xml->endElement();//investigationCharacteristic
                                $xml->endElement();//subjectOf2
                                $xml->startElement("subjectOf2");
                                $xml->writeAttribute('typeCode','SUBJ');
                                    $xml->startElement("investigationCharacteristic");
                                    $xml->writeAttribute('classCode','OBS');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','2');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.23');
                                        $xml->endElement();
                                        $xml->writeComment(" otherCaseIds ");
                                        $xml->startElement("value");
                                        $xml->writeAttribute('xsi:type','BL');
                                        $xml->writeAttribute('nullFlavor',$this->XMLvalue($caseId,18,1));
                                        $xml->endElement();
                                        $xml->writeComment(" C.1.9.1 Other Case Identifiers in Previous Transmissions ");
                                    $xml->endElement();//investigationCharacteristic
                                $xml->endElement();//subjectOf2
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
                        $xml->writeAttribute('extension',$this->XMLvalue($caseId,1054,1));
                        $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.14');
                        $xml->endElement();
                        $xml->writeComment(" N.1.4: Batch Receiver Identifier ");
                    $xml->endElement();//device
                $xml->endElement();//receiver
                $xml->startElement("sender");
                $xml->writeAttribute('typeCode','SND');
                    $xml->startElement("device");
                    $xml->writeAttribute('classCode','DEV');
                    $xml->writeAttribute('determinerCode','INSTANCE');
                        $xml->startElement("id");
                        $xml->writeAttribute('extension',$this->XMLvalue($caseId,1053,1));
                        $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.13');
                        $xml->endElement();
                        $xml->writeComment(" N.1.3: Batch Sender Identifier ");
                    $xml->endElement();//device
                $xml->endElement();//sender
            $xml->endElement();//MCCI_IN200100UV01
        }
        else if(substr($this->XMLvalue($caseId,416,1),2,1)==1){
            $xml->startElement("MCCI_IN200100UV01");
            $xml->writeAttribute('ITSVersion','XML_1.0');
            $xml->writeAttribute('xsi:schemaLocation','urn:hl7-org:v3 multicacheschemas/MCCI_IN200100UV01.xsd');
            $xml->writeAttribute('xmlns','urn:hl7-org:v3');
            $xml->writeAttributeNS('xmlns','xsi', null,'http://www.w3.org/2001/XMLSchema-instance');
                $xml->startElement("id");
                    $xml->writeAttribute('extension',$this->XMLvalue($caseId,1052,1));
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
                $xml->endElement();
                $xml->writeComment(" N.1.1: Type of Messages in Batch ");
                $xml->writeComment(" Message #1 ");
                $xml->startElement("PORR_IN049016UV");
                    $xml->startElement("id");
                        $xml->writeAttribute('extension',$this->XMLvalue($caseId,1056,1));
                        $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.1');
                    $xml->endElement();
                    $xml->writeComment(" N.2.r.1: Message Identifier ");
                    $xml->startElement("creationTime");
                        $xml->writeAttribute('value',$this->XMLvalue($caseId,1059,1));
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
                            $xml->writeAttribute('extension',$this->XMLvalue($caseId,1058,1));
                            $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.12');
                            $xml->endElement();
                            $xml->writeComment(" N.2.r.3: Message Receiver Identifier ");
                        $xml->endElement();
                    $xml->endElement();
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
                        $xml->endElement();
                    $xml->endElement();
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
                                $xml->startElement("reference");
                                $xml->writeAttribute('typeCode','REFR');
                                    $xml->startElement("document");
                                    $xml->writeAttribute('classCode','DOC');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.27');
                                        $xml->writeAttribute('code','1');
                                        $xml->endElement();
                                        $xml->writeComment(" documentsHeldBySender ");
                                        $xml->writeElement("title",$this->XMLvalue($caseId,14,1));
                                        $xml->writeComment(" C.1.6.1.r.1: Documents Held by Sender (repeat as necessary) #1 ");
                                    $xml->endElement();//DOCUMENT
                                $xml->endElement();//reference
                                $xml->startElement("component");
                                $xml->writeAttribute('typeCode','COMP');
                                    $xml->startElement("adverseEventAssessment");
                                    $xml->writeAttribute('classCode','INVSTG');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("subject1");
                                        $xml->writeAttribute('typeCode','SBJ');
                                            $xml->startElement("primaryRole");
                                            $xml->writeAttribute('classCode','INVSBJ');
                                                $xml->startElement("player1");
                                                $xml->writeAttribute('classCode','PSN');
                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                    $xml->startElement("name");
                                                    $xml->writeAttribute('nullFlavor','MSK');//$this->XMLvalue($caseId,79,1)
                                                    $xml->endElement();
                                                    $xml->writeComment(" D.1: Patient (name or initials) ");
                                                    $xml->startElement("administrativeGenderCode");
                                                    $xml->writeAttribute('code',$this->XMLvalue($caseId,93,1));
                                                    $xml->writeAttribute('codeSystem','1.0.5218');
                                                    $xml->endElement();
                                                    $xml->writeComment(" D.5 Sex ");
                                                    $xml->startElement("asIdentifiedEntity");
                                                    $xml->writeAttribute('classCode','IDENT');
                                                        $xml->startElement("id");
                                                        $xml->writeAttribute('extension',$this->XMLvalue($caseId,83,1));
                                                        $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.10');
                                                        $xml->endElement();
                                                        $xml->writeComment(" D.1.1.4: Patient Medical Record Number(s) and Source(s) of the Record Number (Investigation Number) ");
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','4');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.4');
                                                        $xml->endElement();
                                                    $xml->endElement();
                                                $xml->endElement();//player1
                                                $xml->startElement("subjectOf1");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("researchStudy");
                                                    $xml->writeAttribute('classCode','CLNTRL');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("id");
                                                        $xml->writeAttribute('extension',$this->XMLvalue($caseId,39,1));
                                                        $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.5');
                                                        $xml->endElement();
                                                        $xml->writeComment(" C.5.3: Sponsor Study Number ");
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,40,1));
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.8');
                                                        $xml->endElement();
                                                        $xml->writeComment(" C.5.4: Study Type Where Reaction(s) / Event(s) Were Observed ");
                                                        $xml->writeElement("title",$this->XMLvalue($caseId,38,1));
                                                        $xml->writeComment(" C.5.2: Study Name ");
                                                        $xml->startElement("authorization");
                                                        $xml->writeAttribute('typeCode','AUTH');
                                                            $xml->startElement("studyRegistration");
                                                            $xml->writeAttribute('classCode','ACT');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("id");
                                                                $xml->writeAttribute('extension',$this->XMLvalue($caseId,1003,1));
                                                                $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.6');
                                                                $xml->endElement();
                                                                $xml->writeComment(" C.5.1.r.1: Study Registration Number #1 ");
                                                                $xml->startElement("author");
                                                                $xml->writeAttribute('typeCode','AUT');
                                                                    $xml->startElement("territorialAuthority");
                                                                    $xml->writeAttribute('classCode','TERR');
                                                                        $xml->startElement("governingPlace");
                                                                        $xml->writeAttribute('classCode','COUNTRY');
                                                                        $xml->writeAttribute('determinerCode','INSTANCE');
                                                                            $xml->startElement("code");
                                                                            $xml->writeAttribute('code',$this->XMLvalue($caseId,1004,1));
                                                                            $xml->writeAttribute('codeSystem','1.0.3166.1.2.2');
                                                                            $xml->endElement();
                                                                            $xml->writeComment(" C.5.1.r.2: Study Registration Country #1 ");
                                                                        $xml->endElement();
                                                                    $xml->endElement();
                                                                $xml->endElement();
                                                            $xml->endElement();
                                                        $xml->endElement();
                                                        $xml->startElement("authorization");
                                                        $xml->writeAttribute('typeCode','AUTH');
                                                            $xml->startElement("studyRegistration");
                                                            $xml->writeAttribute('classCode','ACT');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("id");
                                                                $xml->writeAttribute('extension',$this->XMLvalue($caseId,1003,2));
                                                                $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.6');
                                                                $xml->endElement();
                                                                $xml->writeComment(" C.5.1.r.1: Study Registration Number #2 ");
                                                                $xml->startElement("author");
                                                                $xml->writeAttribute('typeCode','AUT');
                                                                    $xml->startElement("territorialAuthority");
                                                                    $xml->writeAttribute('classCode','TERR');
                                                                        $xml->startElement("governingPlace");
                                                                        $xml->writeAttribute('classCode','COUNTRY');
                                                                        $xml->writeAttribute('determinerCode','INSTANCE');
                                                                            $xml->startElement("code");
                                                                            $xml->writeAttribute('code',$this->XMLvalue($caseId,1004,2));
                                                                            $xml->writeAttribute('codeSystem','1.0.3166.1.2.2');
                                                                            $xml->endElement();
                                                                            $xml->writeComment(" C.5.1.r.2: Study Registration Country #2 ");
                                                                        $xml->endElement();
                                                                    $xml->endElement();
                                                                $xml->endElement();
                                                            $xml->endElement();
                                                        $xml->endElement();
                                                        $xml->startElement("authorization");
                                                        $xml->writeAttribute('typeCode','AUTH');
                                                            $xml->startElement("studyRegistration");
                                                            $xml->writeAttribute('classCode','ACT');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("id");
                                                                $xml->writeAttribute('extension',$this->XMLvalue($caseId,1003,3));
                                                                $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.6');
                                                                $xml->endElement();
                                                                $xml->writeComment(" C.5.1.r.1: Study Registration Number #3 ");
                                                                $xml->startElement("author");
                                                                $xml->writeAttribute('typeCode','AUT');
                                                                    $xml->startElement("territorialAuthority");
                                                                    $xml->writeAttribute('classCode','TERR');
                                                                        $xml->startElement("governingPlace");
                                                                        $xml->writeAttribute('classCode','COUNTRY');
                                                                        $xml->writeAttribute('determinerCode','INSTANCE');
                                                                            $xml->startElement("code");
                                                                            $xml->writeAttribute('code',$this->XMLvalue($caseId,1004,3));
                                                                            $xml->writeAttribute('codeSystem','1.0.3166.1.2.2');
                                                                            $xml->endElement();
                                                                            $xml->writeComment(" C.5.1.r.2: Study Registration Country #3 ");
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
                                                    $xml->writeAttribute('classCode','OBS');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','3');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                        $xml->endElement();
                                                        $xml->writeComment(" age ");
                                                        $xml->startElement("value");
                                                        $xml->writeAttribute('xsi:type','PQ');
                                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,86,1));
                                                        $xml->writeAttribute('unit',$this->XMLvalue($caseId,87,1));
                                                        $xml->endElement();
                                                        $xml->writeComment(" D.2.2a: Age at Time of Onset of Reaction / Event (number) ");
                                                        $xml->writeComment(" D.2.2b: Age at Time of Onset of Reaction / Event (unit) ");
                                                    $xml->endElement();//observation
                                                $xml->endElement();//subjectOf2
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("observation");
                                                    $xml->writeAttribute('classCode','OBS');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','4');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                        $xml->endElement();
                                                        $xml->writeComment(" ageGroup ");
                                                        $xml->startElement("value");
                                                        $xml->writeAttribute('xsi:type','CE');
                                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,90,1));
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                        $xml->endElement();
                                                        $xml->writeComment(" D.2.3: Patient Age Group (as per reporter) ");
                                                    $xml->endElement();//observation
                                                $xml->endElement();//subjectOf2
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("observation");
                                                    $xml->writeAttribute('classCode','OBS');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','7');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                        $xml->endElement();
                                                        $xml->writeComment(" bodyweight ");
                                                        $xml->startElement("value");
                                                        $xml->writeAttribute('xsi:type','PQ');
                                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,91,1));
                                                        $xml->writeAttribute('unit','kg');
                                                        $xml->endElement();
                                                        $xml->writeComment(" D.3: Body Weight (kg) ");
                                                    $xml->endElement();//observation
                                                $xml->endElement();//subjectOf2
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("observation");
                                                    $xml->writeAttribute('classCode','OBS');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','17');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                        $xml->endElement();
                                                        $xml->writeComment(" height ");
                                                        $xml->startElement("value");
                                                        $xml->writeAttribute('xsi:type','PQ');
                                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,92,1));
                                                        $xml->writeAttribute('unit','cm');
                                                        $xml->endElement();
                                                        $xml->writeComment(" D.4: Height (cm) ");
                                                    $xml->endElement();//observation
                                                $xml->endElement();//subjectOf2
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("organizer");
                                                    $xml->writeAttribute('classCode','GATEGORY');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','1');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.20');
                                                        $xml->writeComment(" relevantMedicalHistoryAndConcurrentConditions ");
                                                        $xml->endElement();//code
                                                        $xml->startElement("component");
                                                        $xml->writeAttribute('typeCode','COMP');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,97,1));
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.6.163');
                                                                $xml->writeAttribute('codeSystemVersion','15.0');
                                                                $xml->endElement();
                                                                $xml->writeComment(" D.7.1.r.1a: MedDRA Version for Medical History #1 ");
                                                                $xml->writeComment(" D.7.1.r.1b: Medical History (disease / surgical procedure / etc.) (MedDRA code) #1 ");
                                                                $xml->startElement("effectiveTime");
                                                                $xml->writeAttribute('xsi:type','IVL_TS');
                                                                    $xml->startElement("low");
                                                                    $xml->writeAttribute('value',$this->XMLvalue($caseId,99,1));
                                                                    $xml->endElement();
                                                                    $xml->writeComment(" D.7.1.r.2: Start Date #1 ");
                                                                $xml->endElement();
                                                                $xml->startElement("inboundRelationship");
                                                                $xml->writeAttribute('typeCode','REFR');
                                                                    $xml->startElement("observation");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code','13');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" continuing ");
                                                                        $xml->startElement("value");
                                                                        $xml->writeAttribute('xsi:type','BL');
                                                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,100,1));
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" D.7.1.r.3: Continuing #1 ");
                                                                    $xml->endElement();//observation
                                                                $xml->endElement();//inboundRelationship
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//component  
                                                    $xml->endElement();//organizer
                                                $xml->endElement();//subjectOf2
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("observation");
                                                    $xml->writeAttribute('classCode','OBS');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("id");
                                                        $xml->writeAttribute('root','rid-1');
                                                        $xml->endElement();
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','29');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                        $xml->endElement();
                                                        $xml->writeComment(" reaction ");
                                                        $xml->startElement("effectiveTime");
                                                        $xml->writeAttribute('xsi:type','SXPR_TS');
                                                            $xml->startElement("comp");
                                                            $xml->writeAttribute('xsi:type','IVL_TS');
                                                                $xml->startElement("low");
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,156,1));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.4: Date of Start of Reaction / Event #1 ");
                                                                $xml->startElement("width");
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,159,1));
                                                                $xml->writeAttribute('unit',$this->XMLvalue($caseId,160,1));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.6a: Duration of Reaction / Event (number) #1 ");
                                                                $xml->writeComment(" E.i.6b: Duration of Reaction / Event (unit) #1 ");
                                                            $xml->endElement();//comp
                                                            $xml->startElement("comp");
                                                            $xml->writeAttribute('xsi:type','IVL_TS');
                                                            $xml->writeAttribute('operator','A');
                                                                $xml->startElement("high");
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,158,1));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.5: Date of End of Reaction / Event #1 ");
                                                            $xml->endElement();//comp
                                                        $xml->endElement();//effectiveTime
                                                        $xml->startElement("value");
                                                        $xml->writeAttribute('xsi:type','CE');
                                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,151,1));
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.6.163');
                                                        $xml->writeAttribute('codeSystemVersion','15.0');
                                                        $xml->writeComment(" E.i.2.1a: MedDRA Version for Reaction / Event #1 ");
                                                        $xml->writeComment(" E.i.2.1b: Reaction / Event (MedDRA code) #1 ");
                                                            $xml->StartElement("originalText"); 
                                                            $xml->WriteAttribute("language",$this->XMLvalue($caseId,1017,1)); 
                                                            $xml->text($this->XMLvalue($caseId,149,1));
                                                            $xml->EndElement(); 
                                                            $xml->writeComment(" E.i.1.1a: Reaction / Event as Reported by the Primary Source in Native Language #1 ");
                                                            $xml->writeComment(" E.i.1.1b: Reaction / Event as Reported by the Primary Source Language #1 ");
                                                        $xml->endElement();//value
                                                        $xml->startElement("location");
                                                        $xml->writeAttribute('typeCode','LOC');
                                                            $xml->startElement("locatedEntity");
                                                            $xml->writeAttribute('classCode','LOCE');
                                                                $xml->startElement("locatedPlace");
                                                                $xml->writeAttribute('classCode','COUNTRY');
                                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                                    $xml->startElement("code");
                                                                    $xml->writeAttribute('code',$this->XMLvalue($caseId,1026,1));
                                                                    $xml->writeAttribute('codeSystem','1.0.3166.1.2.2');
                                                                    $xml->endElement();
                                                                    $xml->writeComment(" E.i.9: Identification of the Country Where the Reaction / Event Occurred #1 ");
                                                                $xml->endElement();
                                                            $xml->endElement();//locatedEntity
                                                        $xml->endElement();//location
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','PERT');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','34');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->endElement();
                                                                $xml->writeComment(" resultsInDeath ");
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','BL');
                                                                $xml->writeAttribute('nullFlavor','NI');//$this->XMLvalue($caseId,1019,3)
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.3.2a: Results in Death #1 ");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','PERT');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','27');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->endElement();
                                                                $xml->writeComment(" outcome ");
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','CE');
                                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,165,1));
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.11');
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.7: Outcome of Reaction / Event at the Time of Last Observation #1 ");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','PERT');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','24');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->endElement();
                                                                $xml->writeComment(" medicalConfirmationByHealthProfessional ");
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','BL');
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,1025,1));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.8: Medical Confirmation by Healthcare Professional #1 ");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                    $xml->endElement();//observation
                                                $xml->endElement();//subjectOf2
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("observation");
                                                    $xml->writeAttribute('classCode','OBS');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("id");
                                                        $xml->writeAttribute('root','rid-2');
                                                        $xml->endElement();
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','29');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                        $xml->endElement();
                                                        $xml->writeComment(" reaction ");
                                                        $xml->startElement("effectiveTime");
                                                        $xml->writeAttribute('xsi:type','SXPR_TS');
                                                            $xml->startElement("comp");
                                                            $xml->writeAttribute('xsi:type','IVL_TS');
                                                                $xml->startElement("low");
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,156,2));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.4: Date of Start of Reaction / Event #2 ");
                                                                $xml->startElement("width");
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,159,2));
                                                                $xml->writeAttribute('unit',$this->XMLvalue($caseId,160,2));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.6a: Duration of Reaction / Event (number) #2 ");
                                                                $xml->writeComment(" E.i.6b: Duration of Reaction / Event (unit) #2 ");
                                                            $xml->endElement();//comp
                                                            $xml->startElement("comp");
                                                            $xml->writeAttribute('xsi:type','IVL_TS');
                                                            $xml->writeAttribute('operator','A');
                                                                $xml->startElement("high");
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,158,2));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.5: Date of End of Reaction / Event #2 ");
                                                            $xml->endElement();//comp
                                                        $xml->endElement();//effectiveTime
                                                        $xml->startElement("value");
                                                        $xml->writeAttribute('xsi:type','CE');
                                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,151,2));
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.6.163');
                                                        $xml->writeAttribute('codeSystemVersion','15.0');
                                                        $xml->writeComment(" E.i.2.1a: MedDRA Version for Reaction / Event #2 ");
                                                        $xml->writeComment(" E.i.2.1b: Reaction / Event (MedDRA code) #2 ");
                                                            $xml->StartElement("originalText"); 
                                                            $xml->WriteAttribute("language",$this->XMLvalue($caseId,1017,2)); 
                                                            $xml->text($this->XMLvalue($caseId,149,2));
                                                            $xml->EndElement(); 
                                                            $xml->writeComment(" E.i.1.1a: Reaction / Event as Reported by the Primary Source in Native Language #2 ");
                                                            $xml->writeComment(" E.i.1.1b: Reaction / Event as Reported by the Primary Source Language #2 ");
                                                        $xml->endElement();//value
                                                        $xml->startElement("location");
                                                        $xml->writeAttribute('typeCode','LOC');
                                                            $xml->startElement("locatedEntity");
                                                            $xml->writeAttribute('classCode','LOCE');
                                                                $xml->startElement("locatedPlace");
                                                                $xml->writeAttribute('classCode','COUNTRY');
                                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                                    $xml->startElement("code");
                                                                    $xml->writeAttribute('code',$this->XMLvalue($caseId,1026,2));
                                                                    $xml->writeAttribute('codeSystem','1.0.3166.1.2.2');
                                                                    $xml->endElement();
                                                                    $xml->writeComment(" E.i.9: Identification of the Country Where the Reaction / Event Occurred #2 ");
                                                                $xml->endElement();
                                                            $xml->endElement();//locatedEntity
                                                        $xml->endElement();//location
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','PERT');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','34');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->endElement();
                                                                $xml->writeComment(" resultsInDeath ");
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','BL');
                                                                $xml->writeAttribute('nullFlavor','NI');//$this->XMLvalue($caseId,1019,3)
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.3.2a: Results in Death #2 ");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','PERT');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','27');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->endElement();
                                                                $xml->writeComment(" outcome ");
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','CE');
                                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,165,2));
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.11');
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.7: Outcome of Reaction / Event at the Time of Last Observation #2 ");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','PERT');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','24');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->endElement();
                                                                $xml->writeComment(" medicalConfirmationByHealthProfessional ");
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','BL');
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,1025,2));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.8: Medical Confirmation by Healthcare Professional #2 ");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                    $xml->endElement();//observation
                                                $xml->endElement();//subjectOf2
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("observation");
                                                    $xml->writeAttribute('classCode','OBS');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("id");
                                                        $xml->writeAttribute('root','rid-3');
                                                        $xml->endElement();
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','29');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                        $xml->endElement();
                                                        $xml->writeComment(" reaction ");
                                                        $xml->startElement("effectiveTime");
                                                        $xml->writeAttribute('xsi:type','SXPR_TS');
                                                            $xml->startElement("comp");
                                                            $xml->writeAttribute('xsi:type','IVL_TS');
                                                                $xml->startElement("low");
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,156,3));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.4: Date of Start of Reaction / Event #3 ");
                                                                $xml->startElement("width");
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,159,3));
                                                                $xml->writeAttribute('unit',$this->XMLvalue($caseId,160,3));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.6a: Duration of Reaction / Event (number) #3 ");
                                                                $xml->writeComment(" E.i.6b: Duration of Reaction / Event (unit) #3 ");
                                                            $xml->endElement();//comp
                                                            $xml->startElement("comp");
                                                            $xml->writeAttribute('xsi:type','IVL_TS');
                                                            $xml->writeAttribute('operator','A');
                                                                $xml->startElement("high");
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,158,3));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.5: Date of End of Reaction / Event #3 ");
                                                            $xml->endElement();//comp
                                                        $xml->endElement();//effectiveTime
                                                        $xml->startElement("value");
                                                        $xml->writeAttribute('xsi:type','CE');
                                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,151,3));
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.6.163');
                                                        $xml->writeAttribute('codeSystemVersion','15.0');
                                                        $xml->writeComment(" E.i.2.1a: MedDRA Version for Reaction / Event #3 ");
                                                        $xml->writeComment(" E.i.2.1b: Reaction / Event (MedDRA code) #3 ");
                                                            $xml->startElement("originalText"); 
                                                            $xml->writeAttribute("language",$this->XMLvalue($caseId,1017,3)); 
                                                            $xml->text($this->XMLvalue($caseId,149,3));
                                                            $xml->endElement(); 
                                                            $xml->writeComment(" E.i.1.1a: Reaction / Event as Reported by the Primary Source in Native Language #3 ");
                                                            $xml->writeComment(" E.i.1.1b: Reaction / Event as Reported by the Primary Source Language #3 ");
                                                        $xml->endElement();//value
                                                        $xml->startElement("location");
                                                        $xml->writeAttribute('typeCode','LOC');
                                                            $xml->startElement("locatedEntity");
                                                            $xml->writeAttribute('classCode','LOCE');
                                                                $xml->startElement("locatedPlace");
                                                                $xml->writeAttribute('classCode','COUNTRY');
                                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                                    $xml->startElement("code");
                                                                    $xml->writeAttribute('code',$this->XMLvalue($caseId,1026,3));
                                                                    $xml->writeAttribute('codeSystem','1.0.3166.1.2.2');
                                                                    $xml->endElement();
                                                                    $xml->writeComment(" E.i.9: Identification of the Country Where the Reaction / Event Occurred #3 ");
                                                                $xml->endElement();
                                                            $xml->endElement();//locatedEntity
                                                        $xml->endElement();//location
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','REFR');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','34');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->endElement();
                                                                $xml->writeComment(" resultsInDeath ");
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','BL');
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,1019,3));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.3.2a: Results in Death #3 ");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','REFR');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','33');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->endElement();
                                                                $xml->writeComment(" requiresInpatientHospitalization ");
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','BL');
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,1021,3));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.3.2c: Caused / Prolonged Hospitalisation #3 ");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','REFR');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','27');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->endElement();
                                                                $xml->writeComment(" outcome ");
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','CE');
                                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,165,3));
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.11');
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.7: Outcome of Reaction / Event at the Time of Last Observation #3 ");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                        $xml->startElement("outboundRelationship2");
                                                        $xml->writeAttribute('typeCode','REFR');
                                                            $xml->startElement("observation");
                                                            $xml->writeAttribute('classCode','OBS');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code','24');
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                $xml->endElement();
                                                                $xml->writeComment(" medicalConfirmationByHealthProfessional ");
                                                                $xml->startElement("value");
                                                                $xml->writeAttribute('xsi:type','BL');
                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,1025,3));
                                                                $xml->endElement();
                                                                $xml->writeComment(" E.i.8: Medical Confirmation by Healthcare Professional #3 ");
                                                            $xml->endElement();//observation
                                                        $xml->endElement();//outboundRelationship2
                                                    $xml->endElement();//observation
                                                $xml->endElement();//subjectOf2
                                                $xml->startElement("subjectOf2");
                                                $xml->writeAttribute('typeCode','SBJ');
                                                    $xml->startElement("organizer");
                                                    $xml->writeAttribute('classCode','CATEGORY');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','4');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.20');
                                                        $xml->endElement();
                                                        $xml->writeComment(" drugInformation ");
                                                        $xml->writeComment(" G.k Drug(s) Information (repeat as necessary) #1 ");
                                                        $xml->startElement("component");
                                                        $xml->writeAttribute('typeCode','COMP');
                                                            $xml->startElement("substanceAdministration");
                                                            $xml->writeAttribute('classCode','SBADM');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("id");
                                                                $xml->writeAttribute('root','d-id1');
                                                                $xml->endElement();
                                                                $xml->startElement("consumable");
                                                                $xml->writeAttribute('typeCode','CSM');
                                                                    $xml->startElement("instanceOfKind");
                                                                    $xml->writeAttribute('classCode','INST');
                                                                        $xml->startElement("kindOfProduct");
                                                                        $xml->writeAttribute('classCode','MMAT');
                                                                        $xml->writeAttribute('determinerCode','KIND');
                                                                            $xml->writeElement('name',$this->XMLvalue($caseId,176,1));
                                                                            $xml->writeComment(" G.k.2.2: Medicinal Product Name as Reported by the Primary Source #1 ");
                                                                            $xml->startElement("ingredient");
                                                                            $xml->writeAttribute('classCode','ACTI');
                                                                                $xml->startElement("quantity");
                                                                                    $xml->startElement("numerator");
                                                                                    $xml->writeAttribute('value',$this->XMLvalue($caseId,1038,1)); 
                                                                                    $xml->writeAttribute('unit',$this->XMLvalue($caseId,1039,3));   
                                                                                    $xml->endElement();
                                                                                    $xml->writeComment(" G.k.2.3.r.3a: Strength (number) #1-1 ");
                                                                                    $xml->writeComment(" G.k.2.3.r.3b: Strength (unit) #1-1 ");
                                                                                    $xml->startElement("denominator");
                                                                                    $xml->writeAttribute('value','1');   
                                                                                    $xml->endElement();
                                                                                $xml->endElement();
                                                                                $xml->startElement("ingredientSubstance");
                                                                                $xml->writeAttribute('classCode','MMAT');
                                                                                $xml->writeAttribute('determinerCode','KIND');
                                                                                    $xml->writeElement('name',$this->XMLvalue($caseId,177,1));
                                                                                    $xml->writeComment(" G.k.2.3.r.1: Substance / Specified Substance Name #1-1 ");
                                                                                $xml->endElement();
                                                                            $xml->endElement();
                                                                        $xml->endElement();//kindOfProduct
                                                                        $xml->startElement("subjectOf");
                                                                        $xml->writeAttribute('typeCode','SBJ');
                                                                            $xml->startElement("productEvent");
                                                                            $xml->writeAttribute('classCode','ACT');
                                                                            $xml->writeAttribute('moodCode','EVN');
                                                                                $xml->startElement("code");
                                                                                $xml->writeAttribute('code','1');
                                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.18');
                                                                                $xml->endElement();
                                                                                $xml->writeComment(" retailSupply ");
                                                                                $xml->startElement("performer");
                                                                                $xml->writeAttribute('typeCode','PRF');
                                                                                    $xml->startElement("assignedEntity");
                                                                                    $xml->writeAttribute('classCode','ASSIGNED');
                                                                                        $xml->startElement("representedOrganization");
                                                                                        $xml->writeAttribute('classCode','ORG');
                                                                                        $xml->writeAttribute('determinerCode','INSTANCE');
                                                                                            $xml->startElement("addr");
                                                                                                $xml->writeElement("country",$this->XMLvalue($caseId,178,1));
                                                                                                $xml->writeComment(" G.k.2.4: Identification of the Country Where the Drug Was Obtained #1 ");
                                                                                            $xml->endElement();
                                                                                        $xml->endElement();
                                                                                    $xml->endElement();
                                                                                $xml->endElement();
                                                                            $xml->endElement();
                                                                        $xml->endElement();//subjectOf
                                                                    $xml->endElement();//instanceOfKind
                                                                $xml->endElement();//consumable
                                                                $xml->startElement("outboundRelationship2");
                                                                $xml->writeAttribute('typeCode','COMP');
                                                                $xml->writeComment(" G.k.4: Dosage Information (repeat as necessary) #1-1 ");
                                                                    $xml->startElement("substanceAdministration");
                                                                    $xml->writeAttribute('classCode','SBADM');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("effectiveTime");
                                                                        $xml->writeAttribute('xsi:type','SXPR_TS');
                                                                            $xml->startElement("comp");
                                                                            $xml->writeAttribute('xsi:type','PIVL_TS');
                                                                                $xml->startElement("period");
                                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,186,1));
                                                                                $xml->writeAttribute('unit',$this->XMLvalue($caseId,187,1));
                                                                                $xml->endElement();
                                                                                $xml->writeComment(" G.k.4.r.2: Number of Units in the Interval #1-1 ");
                                                                                $xml->writeComment(" G.k.4.r.3: Definition of the Time Interval Unit #1-1 ");
                                                                            $xml->endElement();//comp
                                                                            $xml->startElement("comp");
                                                                            $xml->writeAttribute('xsi:type','IVL_TS');
                                                                            $xml->writeAttribute('operator','A');
                                                                                $xml->startElement("low");
                                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,199,1));
                                                                                $xml->endElement();
                                                                                $xml->writeComment(" G.k.4.r.4: Date and Time of Start of Drug #1-1 ");
                                                                                $xml->startElement("high");
                                                                                $xml->writeAttribute('value',$this->XMLvalue($caseId,205,1));
                                                                                $xml->endElement();
                                                                                $xml->writeComment(" G.k.4.r.5: Date and Time of Last Administration #1-1 ");
                                                                            $xml->endElement();//comp
                                                                        $xml->endElement();//effectiveTime
                                                                        $xml->startElement("doesQuantity");
                                                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,183,1));
                                                                            $xml->writeAttribute('unit',$this->XMLvalue($caseId,184,1));
                                                                        $xml->endElement();//doesQuantity
                                                                        $xml->writeComment(" G.k.4.r.1a Dose (number) #1-1 ");
                                                                        $xml->writeComment(" G.k.4.r.1b: Dose (unit) #1-1 ");
                                                                        $xml->startElement("consumable");
                                                                        $xml->writeAttribute('type','CSM');
                                                                            $xml->startElement("instanceOfKind");
                                                                            $xml->writeAttribute('classCode','INST');
                                                                                $xml->startElement("kindOfProduct");
                                                                                $xml->writeAttribute('classCode','MMAT');
                                                                                $xml->writeAttribute('determinerCode','KIND');
                                                                                    $xml->startElement("formCode");
                                                                                    $xml->writeAttribute('codeSystem',$this->XMLvalue($caseId,1041,1));
                                                                                    $xml->writeAttribute('codeSystemVersion','124');
                                                                                    $xml->writeComment(" G.k.4.r.9.2a: Pharmaceutical Dose Form TermID Version Date / Number #1-1 ");
                                                                                        $xml->writeElement('originalText',$this->XMLvalue($caseId,191,1));
                                                                                        $xml->writeComment(" G.k.4.r.9.1: Pharmaceutical Dose Form (free text) #1-1 ");
                                                                                    $xml->endElement();//formCode
                                                                                $xml->endElement();//kindOfProduct
                                                                            $xml->endElement();//instanceOfKind
                                                                        $xml->endElement();//consumable
                                                                    $xml->endElement();//substanceAdministration
                                                                $xml->endElement();//outboundRelationship2
                                                                $xml->startElement("outboundRelationship2");
                                                                $xml->writeAttribute('typeCode','PERT');
                                                                    $xml->startElement("observation");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code','31');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" recurranceOfReaction ");
                                                                        $xml->startElement("value");
                                                                        $xml->writeAttribute('xsi:type','CE');
                                                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,209,"1,1"));
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.16');
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" G.k.9.i.4: Did Reaction Recur on Re-administration? Reaction#1 #1 ");
                                                                        $xml->startElement("outboundRelationship1");
                                                                        $xml->writeAttribute('typeCode','REFR');
                                                                            $xml->startElement("actReference");
                                                                            $xml->writeAttribute('classCode','OBS');
                                                                            $xml->writeAttribute('moodCode','EVN');
                                                                                $xml->startElement("id");
                                                                                $xml->writeAttribute('root','r-id1');
                                                                                $xml->endElement();
                                                                            $xml->endElement();
                                                                        $xml->endElement();
                                                                    $xml->endElement();//observation
                                                                $xml->endElement();//outboundRelationship2
                                                                $xml->startElement("outboundRelationship2");
                                                                $xml->writeAttribute('typeCode','PERT');
                                                                    $xml->startElement("observation");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code','31');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" recurranceOfReaction ");
                                                                        $xml->startElement("value");
                                                                        $xml->writeAttribute('xsi:type','CE');
                                                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,209,"1,2"));
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.16');
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" G.k.9.i.4: Did Reaction Recur on Re-administration? Reaction#2 #1 ");
                                                                        $xml->startElement("outboundRelationship1");
                                                                        $xml->writeAttribute('typeCode','REFR');
                                                                            $xml->startElement("actReference");
                                                                            $xml->writeAttribute('classCode','OBS');
                                                                            $xml->writeAttribute('moodCode','EVN');
                                                                                $xml->startElement("id");
                                                                                $xml->writeAttribute('root','r-id2');
                                                                                $xml->endElement();
                                                                            $xml->endElement();
                                                                        $xml->endElement();
                                                                    $xml->endElement();//observation
                                                                $xml->endElement();//outboundRelationship2
                                                                $xml->startElement("inboundRelationship");
                                                                $xml->writeAttribute('typeCode','RSON');
                                                                    $xml->startElement("observation");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code','19');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" indication ");
                                                                        $xml->startElement("value");
                                                                        $xml->writeAttribute('xsi:type','CE');
                                                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,196,1));
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.6.163');
                                                                        $xml->writeAttribute('codeSystemVersion','15.0');
                                                                        $xml->writeComment(" G.k.7.r.2a: MedDRA Version for Indication #1-1 ");
                                                                        $xml->writeComment(" G.k.7.r.2b: Indication (MedDRA code) #1-1 ");
                                                                            $xml->writeElement('originalText',$this->XMLvalue($caseId,1047,1));
                                                                            $xml->writeComment(" G.k.7.r.1: Indication as Reported by the Primary Source #1-1 ");
                                                                        $xml->endElement();
                                                                        $xml->startElement("performer");
                                                                        $xml->writeAttribute('typeCode','PRF');
                                                                            $xml->startElement("assignedEntity");
                                                                            $xml->writeAttribute('classCode','ASSIGNED');
                                                                                $xml->startElement("code");
                                                                                $xml->writeAttribute('code','3');
                                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.21');
                                                                                $xml->endElement();
                                                                                $xml->writeComment(' sourceReporter ');
                                                                            $xml->endElement();
                                                                        $xml->endElement();//performer
                                                                    $xml->endElement();//observation       
                                                                $xml->endElement();//inboundRelationship
                                                                $xml->startElement("inboundRelationship");
                                                                $xml->writeAttribute('typeCode','CAUS');
                                                                    $xml->startElement("act");
                                                                    $xml->writeAttribute('classCode','ACT');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,208,1));
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.15');
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" G.k.8: Action(s) Taken with Drug #1 ");
                                                                    $xml->endElement();//act       
                                                                $xml->endElement();//inboundRelationship
                                                            $xml->endElement();//substanceAdministration
                                                        $xml->endElement();//component
                                                        $xml->writeComment(" G.k Drug(s) Information (repeat as necessary) #2 ");
                                                        $xml->startElement("component");
                                                        $xml->writeAttribute('typeCode','COMP');
                                                            $xml->startElement("substanceAdministration");
                                                            $xml->writeAttribute('classCode','SBADM');
                                                            $xml->writeAttribute('moodCode','EVN');
                                                                $xml->startElement("id");
                                                                $xml->writeAttribute('root','d-id2');
                                                                $xml->endElement();
                                                                $xml->startElement("consumable");
                                                                $xml->writeAttribute('typeCode','CSM');
                                                                    $xml->startElement("instanceOfKind");
                                                                    $xml->writeAttribute('classCode','INST');
                                                                        $xml->startElement("kindOfProduct");
                                                                        $xml->writeAttribute('classCode','MMAT');
                                                                        $xml->writeAttribute('determinerCode','KIND');
                                                                            $xml->writeElement('name',$this->XMLvalue($caseId,176,2));
                                                                            $xml->writeComment(" G.k.2.2: Medicinal Product Name as Reported by the Primary Source #2 ");
                                                                            $xml->startElement("ingredient");
                                                                            $xml->writeAttribute('classCode','ACTI');
                                                                                $xml->startElement("ingredientSubstance");
                                                                                $xml->writeAttribute('classCode','MMAT');
                                                                                $xml->writeAttribute('determinerCode','KIND');
                                                                                    $xml->writeElement('name',$this->XMLvalue($caseId,177,2));
                                                                                    $xml->writeComment(" G.k.2.3.r.1: Substance / Specified Substance Name #2-1 ");
                                                                                $xml->endElement();
                                                                            $xml->endElement();
                                                                        $xml->endElement();//kindOfProduct
                                                                    $xml->endElement();//instanceOfKind
                                                                $xml->endElement();//consumable
                                                                $xml->startElement("outboundRelationship2");
                                                                $xml->writeAttribute('typeCode','PERT');
                                                                    $xml->startElement("observation");
                                                                    $xml->writeAttribute('classCode','OBS');
                                                                    $xml->writeAttribute('moodCode','EVN');
                                                                        $xml->startElement("code");
                                                                        $xml->writeAttribute('code','31');
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" recurranceOfReaction ");
                                                                        $xml->startElement("value");
                                                                        $xml->writeAttribute('xsi:type','CE');
                                                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,209,"2,1"));
                                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.16');
                                                                        $xml->endElement();
                                                                        $xml->writeComment(" G.k.9.i.4: Did Reaction Recur on Re-administration? Reaction#1 #2 ");
                                                                        $xml->startElement("outboundRelationship1");
                                                                        $xml->writeAttribute('typeCode','REFR');
                                                                            $xml->startElement("actReference");
                                                                            $xml->writeAttribute('classCode','OBS');
                                                                            $xml->writeAttribute('moodCode','EVN');
                                                                                $xml->startElement("id");
                                                                                $xml->writeAttribute('root','r-id3');
                                                                                $xml->endElement();
                                                                            $xml->endElement();
                                                                        $xml->endElement();
                                                                    $xml->endElement();//observation
                                                                $xml->endElement();//outboundRelationship2
                                                            $xml->endElement();//substanceAdministration
                                                        $xml->endElement();//component
                                                    $xml->endElement();//organizer
                                                $xml->endElement();//subjectOf2
                                            $xml->endElement();//primaryRole
                                        $xml->endElement();//SUBJECT1
                                        $xml->startElement("component");
                                        $xml->writeAttribute('typeCode','COMP');
                                            $xml->startElement("causalityAssessment");
                                            $xml->writeAttribute('classCode','OBS');
                                            $xml->writeAttribute('moodCode','EVN');
                                                $xml->startElement("code");
                                                $xml->writeAttribute('code','20');
                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                $xml->endElement();
                                                $xml->writeComment(" interventionCharacterization ");
                                                $xml->startElement("value");
                                                $xml->writeAttribute('xsi:type','CE');
                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,175,1));
                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.13');
                                                $xml->endElement();
                                                $xml->writeComment(" G.k.1: Characterisation of Drug Role #1 ");
                                                $xml->startElement("subject2");
                                                $xml->writeAttribute('typeCode','SUBJ');
                                                    $xml->startElement("productUseReference");
                                                    $xml->writeAttribute('classCode','SBADM');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("id");
                                                        $xml->writeAttribute('root','d-id1');
                                                        $xml->endElement();
                                                    $xml->endElement();//productUseReference
                                                $xml->endElement();//subject2
                                            $xml->endElement();//causalityAssessment
                                        $xml->endElement();//component
                                        $xml->startElement("component");
                                        $xml->writeAttribute('typeCode','COMP');
                                            $xml->startElement("causalityAssessment");
                                            $xml->writeAttribute('classCode','OBS');
                                            $xml->writeAttribute('moodCode','EVN');
                                                $xml->startElement("code");
                                                $xml->writeAttribute('code','20');
                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                $xml->endElement();
                                                $xml->writeComment(" interventionCharacterization ");
                                                $xml->startElement("value");
                                                $xml->writeAttribute('xsi:type','CE');
                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,175,2));
                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.13');
                                                $xml->endElement();
                                                $xml->writeComment(" G.k.1: Characterisation of Drug Role #2 ");
                                                $xml->startElement("subject2");
                                                $xml->writeAttribute('typeCode','SUBJ');
                                                    $xml->startElement("productUseReference");
                                                    $xml->writeAttribute('classCode','SBADM');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("id");
                                                        $xml->writeAttribute('root','d-id2');
                                                        $xml->endElement();
                                                    $xml->endElement();//productUseReference
                                                $xml->endElement();//subject2
                                            $xml->endElement();//causalityAssessment
                                        $xml->endElement();//component
                                        $xml->startElement("component");
                                        $xml->writeAttribute('typeCode','COMP');
                                            $xml->startElement("causalityAssessment");
                                            $xml->writeAttribute('classCode','OBS');
                                            $xml->writeAttribute('moodCode','EVN');
                                                $xml->startElement("code");
                                                $xml->writeAttribute('code','39');
                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                $xml->endElement();
                                                $xml->writeComment(" causality ");
                                                $xml->startElement("value");
                                                $xml->writeAttribute('xsi:type','CE');
                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,216,"1,1"));
                                                $xml->endElement();
                                                $xml->writeComment(" G.k.9.i.2.r.3: Result of Assessment #1-1 ");
                                                $xml->startElement("methodCode");
                                                    $xml->writeElement("originalText",$this->XMLvalue($caseId,215,"1,1"));
                                                    $xml->writeComment(" G.k.9.i.2.r.2: Method of Assessment #1-1 ");
                                                $xml->endElement();//methodCode
                                                $xml->startElement("author");
                                                $xml->writeAttribute('typeCode','AUT');
                                                    $xml->startElement("assignedEntity");
                                                    $xml->writeAttribute('classCode','ASSIGNED');
                                                        $xml->startElement("code");
                                                        $xml->writeElement('originalText',$this->XMLvalue($caseId,214,"1,1"));
                                                        $xml->writeComment(" G.k.9.i.2.r.1: Source of Assessment #1-1 ");
                                                        $xml->endElement();//code
                                                    $xml->endElement();//assignedEntity
                                                $xml->endElement();//author
                                                $xml->startElement("subject1");
                                                $xml->writeAttribute('typeCode','SUBJ');
                                                    $xml->startElement("adverseEffectReference");
                                                    $xml->writeAttribute('classCode','OBS');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("id");
                                                        $xml->writeAttribute('root','r-id1');
                                                        $xml->endElement();
                                                    $xml->endElement();//adverseEffectReference
                                                $xml->endElement();//subject1
                                                $xml->startElement("subject2");
                                                $xml->writeAttribute('typeCode','SUBJ');
                                                    $xml->startElement("productUseReference");
                                                    $xml->writeAttribute('classCode','SBADM');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("id");
                                                        $xml->writeAttribute('root','d-id1');
                                                        $xml->endElement();
                                                    $xml->endElement();//productUseReference
                                                $xml->endElement();//subject2
                                            $xml->endElement();//causalityAssessment
                                        $xml->endElement();//component
                                        $xml->startElement("component");
                                        $xml->writeAttribute('typeCode','COMP');
                                            $xml->startElement("causalityAssessment");
                                            $xml->writeAttribute('classCode','OBS');
                                            $xml->writeAttribute('moodCode','EVN');
                                                $xml->startElement("code");
                                                $xml->writeAttribute('code','39');
                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                $xml->endElement();
                                                $xml->writeComment(" causality ");
                                                $xml->startElement("value");
                                                $xml->writeAttribute('xsi:type','CE');
                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,216,"2,1"));
                                                $xml->endElement();
                                                $xml->writeComment(" G.k.9.i.2.r.3: Result of Assessment #1-2 ");
                                                $xml->startElement("methodCode");
                                                    $xml->writeElement("originalText",$this->XMLvalue($caseId,215,"2,1"));
                                                    $xml->writeComment(" G.k.9.i.2.r.2: Method of Assessment #1-2 ");
                                                $xml->endElement();//methodCode
                                                $xml->startElement("author");
                                                $xml->writeAttribute('typeCode','AUT');
                                                    $xml->startElement("assignedEntity");
                                                    $xml->writeAttribute('classCode','ASSIGNED');
                                                        $xml->startElement("code");
                                                        $xml->writeElement('originalText',$this->XMLvalue($caseId,214,"2,1"));
                                                        $xml->writeComment(" G.k.9.i.2.r.1: Source of Assessment #1-2 ");
                                                        $xml->endElement();//code
                                                    $xml->endElement();//assignedEntity
                                                $xml->endElement();//author
                                                $xml->startElement("subject1");
                                                $xml->writeAttribute('typeCode','SUBJ');
                                                    $xml->startElement("adverseEffectReference");
                                                    $xml->writeAttribute('classCode','OBS');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("id");
                                                        $xml->writeAttribute('root','r-id3');
                                                        $xml->endElement();
                                                    $xml->endElement();//adverseEffectReference
                                                $xml->endElement();//subject1
                                                $xml->startElement("subject2");
                                                $xml->writeAttribute('typeCode','SUBJ');
                                                    $xml->startElement("productUseReference");
                                                    $xml->writeAttribute('classCode','SBADM');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("id");
                                                        $xml->writeAttribute('root','d-id1');
                                                        $xml->endElement();
                                                    $xml->endElement();//productUseReference
                                                $xml->endElement();//subject2
                                            $xml->endElement();//causalityAssessment
                                        $xml->endElement();//component
                                        $xml->startElement("component");
                                        $xml->writeAttribute('typeCode','COMP');
                                            $xml->startElement("causalityAssessment");
                                            $xml->writeAttribute('classCode','OBS');
                                            $xml->writeAttribute('moodCode','EVN');
                                                $xml->startElement("code");
                                                $xml->writeAttribute('code','39');
                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                $xml->endElement();
                                                $xml->writeComment(" causality ");
                                                $xml->startElement("value");
                                                $xml->writeAttribute('xsi:type','CE');
                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,216,"1,2"));
                                                $xml->endElement();
                                                $xml->writeComment(" G.k.9.i.2.r.3: Result of Assessment #2-1 ");
                                                $xml->startElement("methodCode");
                                                    $xml->writeElement("originalText",$this->XMLvalue($caseId,215,"1,2"));
                                                    $xml->writeComment(" G.k.9.i.2.r.2: Method of Assessment #2-1 ");
                                                $xml->endElement();//methodCode
                                                $xml->startElement("author");
                                                $xml->writeAttribute('typeCode','AUT');
                                                    $xml->startElement("assignedEntity");
                                                    $xml->writeAttribute('classCode','ASSIGNED');
                                                        $xml->startElement("code");
                                                        $xml->writeElement('originalText',$this->XMLvalue($caseId,214,"1,2"));
                                                        $xml->writeComment(" G.k.9.i.2.r.1: Source of Assessment #2-1 ");
                                                        $xml->endElement();//code
                                                    $xml->endElement();//assignedEntity
                                                $xml->endElement();//author
                                                $xml->startElement("subject1");
                                                $xml->writeAttribute('typeCode','SUBJ');
                                                    $xml->startElement("adverseEffectReference");
                                                    $xml->writeAttribute('classCode','OBS');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("id");
                                                        $xml->writeAttribute('root','r-id3');
                                                        $xml->endElement();
                                                    $xml->endElement();//adverseEffectReference
                                                $xml->endElement();//subject1
                                                $xml->startElement("subject2");
                                                $xml->writeAttribute('typeCode','SUBJ');
                                                    $xml->startElement("productUseReference");
                                                    $xml->writeAttribute('classCode','SBADM');
                                                    $xml->writeAttribute('moodCode','EVN');
                                                        $xml->startElement("id");
                                                        $xml->writeAttribute('root','d-id1');
                                                        $xml->endElement();
                                                    $xml->endElement();//productUseReference
                                                $xml->endElement();//subject2
                                            $xml->endElement();//causalityAssessment
                                        $xml->endElement();//component
                                        $xml->startElement("component1");
                                        $xml->writeAttribute('typeCode','COMP');
                                            $xml->startElement("observationEvent");
                                            $xml->writeAttribute('classCode','OBS');
                                            $xml->writeAttribute('moodCode','EVN');
                                                $xml->startElement("code");
                                                $xml->writeAttribute('code','10');
                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                $xml->endElement();
                                                $xml->writeComment(" comment ");
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
                                                        $xml->endElement();//code
                                                        $xml->writeComment(" sourceReporter ");
                                                    $xml->endElement();//assignedEntity
                                                $xml->endElement();//author
                                            $xml->endElement();//observationEvent
                                        $xml->endElement();//component1
                                        $xml->startElement("component1");
                                        $xml->writeAttribute('typeCode','COMP');
                                            $xml->startElement("observationEvent");
                                            $xml->writeAttribute('classCode','OBS');
                                            $xml->writeAttribute('moodCode','EVN');
                                                $xml->startElement("code");
                                                $xml->writeAttribute('code','10');
                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                                $xml->endElement();
                                                $xml->writeComment(" comment ");
                                                $xml->startElement("value");
                                                $xml->writeAttribute('xsi:type','ED');
                                                $xml->text($this->XMLvalue($caseId,222,1));
                                                $xml->endElement();
                                                $xml->writeComment(" H.4: Sender's Comments ");
                                                $xml->startElement("author");
                                                $xml->writeAttribute('typeCode','AUT');
                                                    $xml->startElement("assignedEntity");
                                                    $xml->writeAttribute('classCode','ASSIGNED');
                                                        $xml->startElement("code");
                                                        $xml->writeAttribute('code','1');
                                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.21');
                                                        $xml->writeComment(" sender ");
                                                        $xml->endElement();//code
                                                    $xml->endElement();//assignedEntity
                                                $xml->endElement();//author
                                            $xml->endElement();//observationEvent
                                        $xml->endElement();//component1
                                    $xml->endElement();//adverseEventAssessment
                                $xml->endElement();//component
                                $xml->startElement("component");
                                $xml->writeAttribute('typeCode','COMP');
                                    $xml->startElement("observationEvent");
                                    $xml->writeAttribute('classCode','OBS');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','1');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                        $xml->endElement();
                                        $xml->writeComment(" additionalDocumentsAvailable ");
                                        $xml->startElement("value");
                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,13,1));
                                        $xml->writeAttribute('xsi:type','BL');
                                        $xml->writeComment(' C.1.6.1: Are Additional Documents Available? ');
                                        $xml->endElement();
                                    $xml->endElement();//observationEvent
                                $xml->endElement();//component
                                $xml->startElement("component");
                                $xml->writeAttribute('typeCode','COMP');
                                    $xml->startElement("observationEvent");
                                    $xml->writeAttribute('classCode','OBS');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','23');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                        $xml->endElement();
                                        $xml->writeComment(" localCriteriaForExpedited ");
                                        $xml->startElement("value");
                                        $xml->writeAttribute('value',$this->XMLvalue($caseId,15,1));
                                        $xml->writeAttribute('xsi:type','BL');
                                        $xml->writeComment(' C.1.7: Does This Case Fulfill the Local Criteria for an Expedited Report? ');
                                        $xml->endElement();
                                    $xml->endElement();//observationEvent
                                $xml->endElement();//component
                                $xml->startElement("component");
                                $xml->writeAttribute('typeCode','COMP');
                                    $xml->startElement("observationEvent");
                                    $xml->writeAttribute('classCode','OBS');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','36');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.19');
                                        $xml->endElement();
                                        $xml->writeComment(" summaryAndComment ");
                                        $xml->startElement("value");
                                        $xml->writeAttribute('language',$this->XMLvalue($caseId,1050,1));
                                        $xml->writeAttribute('xsi:type','ED');
                                        $xml->text($this->XMLvalue($caseId,1049,1));
                                        $xml->endElement();
                                        $xml->writeComment(" H.5.r.1a: Case Summary and Reporter's Comments Text #1 ");
                                        $xml->writeComment(" H.5.r.1b: Case Summary and Reporter's Comments Language #1 ");
                                        $xml->startElement("author");
                                        $xml->writeAttribute('typeCode','AUT');
                                            $xml->startElement("assignedEntity");
                                            $xml->writeAttribute('classCode','ASSIGNED');
                                                $xml->startElement("code");
                                                $xml->writeAttribute('code','2');
                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.21');
                                                $xml->writeComment(" reporter ");
                                                $xml->endElement();//code
                                            $xml->endElement();//assignedEntity
                                        $xml->endElement();//author
                                    $xml->endElement();//observationEvent
                                $xml->endElement();//component
                                $xml->startElement("outboundRelationship");
                                $xml->writeAttribute('typeCode','SPRT');
                                    $xml->startElement("relatedInvestigation");
                                    $xml->writeAttribute('classCode','INVSTG');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','1');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.22');
                                        $xml->endElement();
                                        $xml->writeComment(" initialReport ");
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
                                                        $xml->endElement();//code
                                                        $xml->writeComment(" C.1.8.2: First Sender of This Case ");
                                                    $xml->endElement();//assignedEntity
                                                $xml->endElement();//author
                                            $xml->endElement();//controlActEvent
                                        $xml->endElement();//subjectOf2
                                    $xml->endElement();//observation
                                $xml->endElement();//outboundRelationship
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
                                                $xml->writeAttribute('extension',$this->XMLvalue($caseId,21,1));
                                                $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.2');
                                                $xml->endElement();
                                                $xml->writeComment(" C.1.10.r: Identification Number of the Report Which Is Linked to This Report (repeat as necessary) #1 ");
                                            $xml->endElement();//controlActEvent
                                        $xml->endElement();//subjectOf2
                                    $xml->endElement();//relatedInvestigation
                                $xml->endElement();//outboundRelationship
                                $xml->startElement("outboundRelationship");
                                $xml->writeAttribute('typeCode','SPRT');
                                    $xml->startElement("priorityNumber");
                                    $xml->writeAttribute('value',$this->XMLvalue($caseId,227,1));
                                    $xml->endElement();
                                    $xml->writeComment(' C.2.r.5: Primary Source for Regulatory Purposes #1 ');
                                    $xml->startElement("relatedInvestigation");
                                    $xml->writeAttribute('classCode','INVSTG');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','2');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.22');
                                        $xml->endElement();
                                        $xml->writeComment(' sourceReport ');
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
                                                            $xml->writeElement("streetAddressLine",$this->XMLvalue($caseId,31,1));
                                                            $xml->writeComment(" C.2.r.2.3: Reporter's Street #1 ");
                                                            $xml->writeElement("city",$this->XMLvalue($caseId,32,1));
                                                            $xml->writeComment(" C.2.r.2.4: Reporter's City #1 ");
                                                            $xml->writeElement("state",$this->XMLvalue($caseId,33,1));
                                                            $xml->writeComment(" C.2.r.2.5: Reporter's State or Province #1 ");
                                                            $xml->writeElement("postalCode",$this->XMLvalue($caseId,34,1));
                                                            $xml->writeComment(" C.2.r.2.6: Reporter's Postcode #1 ");
                                                        $xml->endElement();//addr
                                                        $xml->startElement("telecom");
                                                        $xml->writeAttribute('vlaue',$this->XMLvalue($caseId,229,1));
                                                        $xml->endElement();
                                                        $xml->writeComment(" C.2.r.2.7: Reporter's Telephone #1 ");
                                                        $xml->startElement("assignedPerson");
                                                        $xml->writeAttribute('classCode','PSN');
                                                        $xml->writeAttribute('determinerCode','INSTANCE');
                                                            $xml->startElement("name");
                                                                $xml->writeElement("prefix",$this->XMLvalue($caseId,25,1));
                                                                $xml->writeComment(" C.2.r.1.1: Reporter's Title #1 ");
                                                                $xml->writeElement("given",$this->XMLvalue($caseId,26,1));
                                                                $xml->writeComment(" C.2.r.1.2: Reporter's Given Name #1 ");
                                                                $xml->writeElement("family",$this->XMLvalue($caseId,28,1));
                                                                $xml->writeComment(" C.2.r.1.4: Reporter's Family Name #1 ");
                                                            $xml->endElement();//name
                                                            $xml->startElement("asQualifiedEntity");
                                                            $xml->writeAttribute('classCode','QUAL');
                                                                $xml->startElement("code");
                                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,36,1));
                                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.6');
                                                                $xml->endElement();
                                                                $xml->writeComment(" C.2.r.4: Qualification #1 ");
                                                            $xml->endElement();//asQualifiedEntity
                                                            $xml->startElement("asLocatedEntity");
                                                            $xml->writeAttribute('classCode','LOCE');
                                                                $xml->startElement("location");
                                                                $xml->writeAttribute('classCode','COUNTRY');
                                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                                    $xml->startElement("code");
                                                                    $xml->writeAttribute('code',$this->XMLvalue($caseId,35,1));
                                                                    $xml->writeAttribute('codeSystem','1.0.3166.1.2.2');
                                                                    $xml->endElement();
                                                                    $xml->writeComment(" C.2.r.3: Reporter's Country Code #1 ");
                                                                $xml->endElement();
                                                            $xml->endElement();//asQualifiedEntity
                                                        $xml->endElement();//assignedPerson
                                                        $xml->startElement("representedOrganization");
                                                        $xml->writeAttribute('classCode','ORG');
                                                        $xml->writeAttribute('determinerCode','INSTANCE');
                                                            $xml->writeElement("name",$this->XMLvalue($caseId,30,1));
                                                            $xml->writeComment(" C.2.r.2.2: Reporter's Department #1 ");
                                                            $xml->startElement("assignedEntity");
                                                            $xml->writeAttribute('classCode','ASSIGNED');
                                                                $xml->startElement("representedOrganization");
                                                                $xml->writeAttribute('classCode','ORG');
                                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                                    $xml->writeElement("name",$this->XMLvalue($caseId,29,1));
                                                                    $xml->writeComment(" C.2.r.2.1: Reporter's Organisation #1 ");
                                                                $xml->endElement();
                                                            $xml->endElement();//assignedEntity
                                                        $xml->endElement();//representedOrganization
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
                                                $xml->startElement("code");
                                                $xml->writeAttribute('code',$this->XMLvalue($caseId,41,1));
                                                $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.7');
                                                $xml->endElement();
                                                $xml->writeComment(" C.3.1: Sender Type ");
                                                $xml->startElement("addr");
                                                    $xml->writeElement("streetAddressLine",$this->XMLvalue($caseId,48,1));
                                                    $xml->writeComment(" C.3.4.1: Sender's Street Address ");
                                                    $xml->writeElement("city",$this->XMLvalue($caseId,49,1));
                                                    $xml->writeComment(" C.3.4.2: Sender's City ");
                                                    $xml->writeElement("postalCode",$this->XMLvalue($caseId,51,1));
                                                    $xml->writeComment(" C.3.4.4: Sender's Postcode ");
                                                $xml->endElement();//addr
                                                $xml->startElement("telecom");
                                                $xml->writeAttribute('vlaue',$this->XMLvalue($caseId,53,1));
                                                $xml->endElement();
                                                $xml->writeComment(" C.3.4.6: Sender's Telephone ");
                                                $xml->startElement("telecom");
                                                $xml->writeAttribute('vlaue',$this->XMLvalue($caseId,56,1));
                                                $xml->endElement();
                                                $xml->writeComment(" C.3.4.7: Sender's Fax ");
                                                $xml->startElement("telecom");
                                                $xml->writeAttribute('vlaue',$this->XMLvalue($caseId,59,1));
                                                $xml->endElement();
                                                $xml->writeComment(" C.3.4.8: Sender's E-mail Address ");
                                                $xml->startElement("assignedPerson");
                                                $xml->writeAttribute('classCode','PSN');
                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                    $xml->startElement("name");
                                                        $xml->writeElement("prefix",$this->XMLvalue($caseId,44,1));
                                                        $xml->writeComment(" C.3.3.2: Sender's Title ");
                                                        $xml->startElement("given");
                                                        $xml->writeAttribute('nullFlavor','MSK');//$this->XMLvalue($caseId,45,1)
                                                        $xml->endElement();
                                                        $xml->writeComment(" C.3.3.3: Sender's Given Name ");
                                                        $xml->startElement("family");
                                                        $xml->writeAttribute('nullFlavor','MSK');//$this->XMLvalue($caseId,47,1)
                                                        $xml->endElement();
                                                        $xml->writeComment(" C.3.3.5: Sender's Family Name ");
                                                    $xml->endElement();//name
                                                    $xml->startElement("asLocatedEntity");
                                                    $xml->writeAttribute('classCode','LOCE');
                                                        $xml->startElement("location");
                                                        $xml->writeAttribute('classCode','COUNTRY');
                                                        $xml->writeAttribute('determinerCode','INSTANCE');
                                                            $xml->startElement("code");
                                                            $xml->writeAttribute('code',$this->XMLvalue($caseId,52,1));
                                                            $xml->writeAttribute('codeSystem','1.0.3166.1.2.2');
                                                            $xml->endElement();
                                                            $xml->writeComment(" C.3.4.5: Sender's Country Code ");
                                                        $xml->endElement();
                                                    $xml->endElement();//asLocatedEntity
                                                $xml->endElement();//assignedPerson
                                                $xml->startElement("representedOrganization");
                                                $xml->writeAttribute('classCode','ORG');
                                                $xml->writeAttribute('determinerCode','INSTANCE');
                                                    $xml->writeElement("name",$this->XMLvalue($caseId,43,1));
                                                    $xml->writeComment(" C.3.3.1: Sender's Department ");
                                                    $xml->startElement("assignedEntity");
                                                    $xml->writeAttribute('classCode','ASSIGNED');
                                                        $xml->startElement("representedOrganization");
                                                        $xml->writeAttribute('classCode','ORG');
                                                        $xml->writeAttribute('determinerCode','INSTANCE');
                                                            $xml->writeElement("name",$this->XMLvalue($caseId,42,1));
                                                            $xml->writeComment(" C.3.2: Sender's Organisation ");
                                                        $xml->endElement();//representedOrganization
                                                    $xml->endElement();//assignedEntity
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
                                        $xml->endElement();
                                        $xml->writeComment("ichReportType");
                                        $xml->startElement("value");
                                        $xml->writeAttribute('xsi:type','CE');
                                        $xml->writeAttribute('code',$this->XMLvalue($caseId,6,1));
                                        $xml->writeAttribute('codeSystem','="2.16.840.1.113883.3.989.2.1.1.2');
                                        $xml->endElement();
                                        $xml->writeComment(" C.1.3 Type of Report ");
                                    $xml->endElement();//investigationCharacteristic
                                $xml->endElement();//subjectOf2
                                $xml->startElement("subjectOf2");
                                $xml->writeAttribute('typeCode','SUBJ');
                                    $xml->startElement("investigationCharacteristic");
                                    $xml->writeAttribute('classCode','OBS');
                                    $xml->writeAttribute('moodCode','EVN');
                                        $xml->startElement("code");
                                        $xml->writeAttribute('code','2');
                                        $xml->writeAttribute('codeSystem','2.16.840.1.113883.3.989.2.1.1.23');
                                        $xml->endElement();
                                        $xml->writeComment(" otherCaseIds ");
                                        $xml->startElement("value");
                                        $xml->writeAttribute('xsi:type','BL');
                                        $xml->writeAttribute('nullFlavor',$this->XMLvalue($caseId,18,1));
                                        $xml->endElement();
                                        $xml->writeComment(" C.1.9.1 Other Case Identifiers in Previous Transmissions ");
                                    $xml->endElement();//investigationCharacteristic
                                $xml->endElement();//subjectOf2
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
                        $xml->writeAttribute('extension',$this->XMLvalue($caseId,1054,1));
                        $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.14');
                        $xml->endElement();
                        $xml->writeComment(" N.1.4: Batch Receiver Identifier ");
                    $xml->endElement();//device
                $xml->endElement();//receiver
                $xml->startElement("sender");
                $xml->writeAttribute('typeCode','SND');
                    $xml->startElement("device");
                    $xml->writeAttribute('classCode','DEV');
                    $xml->writeAttribute('determinerCode','INSTANCE');
                        $xml->startElement("id");
                        $xml->writeAttribute('extension',$this->XMLvalue($caseId,1053,1));
                        $xml->writeAttribute('root','2.16.840.1.113883.3.989.2.1.3.13');
                        $xml->endElement();
                        $xml->writeComment(" N.1.3: Batch Sender Identifier ");
                    $xml->endElement();//device
                $xml->endElement();//sender
            $xml->endElement();//MCCI_IN200100UV01
        }
        $xml->endDocument();
    echo $xml->outputMemory();
    }

    
}
