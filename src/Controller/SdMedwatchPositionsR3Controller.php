<?php
namespace App\Controller;
use Cake\ORM\TableRegistry;

use App\Controller\AppController;

/**
 * SdMedwatchPositionsR3 Controller
 *
 * @property \App\Model\Table\SdMedwatchPositionsR3Table $SdMedwatchPositionsR3
 *
 * @method \App\Model\Entity\SdMedwatchPositionsR3[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SdMedwatchPositionsR3Controller extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['SdFields']
        ];
        $sdMedwatchPositionsR3 = $this->paginate($this->SdMedwatchPositionsR3);

        $this->set(compact('sdMedwatchPositionsR3'));
    }

    /**
     * View method
     *
     * @param string|null $id Sd Medwatch Positions R3 id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $sdMedwatchPositionsR3 = $this->SdMedwatchPositionsR3->get($id, [
            'contain' => ['SdFields']
        ]);

        $this->set('sdMedwatchPositionsR3', $sdMedwatchPositionsR3);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $sdMedwatchPositionsR3 = $this->SdMedwatchPositionsR3->newEntity();
        if ($this->request->is('post')) {
            $sdMedwatchPositionsR3 = $this->SdMedwatchPositionsR3->patchEntity($sdMedwatchPositionsR3, $this->request->getData());
            if ($this->SdMedwatchPositionsR3->save($sdMedwatchPositionsR3)) {
                $this->Flash->success(__('The sd medwatch positions r3 has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sd medwatch positions r3 could not be saved. Please, try again.'));
        }
        $sdFields = $this->SdMedwatchPositionsR3->SdFields->find('list', ['limit' => 200]);
        $this->set(compact('sdMedwatchPositionsR3', 'sdFields'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Sd Medwatch Positions R3 id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sdMedwatchPositionsR3 = $this->SdMedwatchPositionsR3->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $sdMedwatchPositionsR3 = $this->SdMedwatchPositionsR3->patchEntity($sdMedwatchPositionsR3, $this->request->getData());
            if ($this->SdMedwatchPositionsR3->save($sdMedwatchPositionsR3)) {
                $this->Flash->success(__('The sd medwatch positions r3 has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sd medwatch positions r3 could not be saved. Please, try again.'));
        }
        $sdFields = $this->SdMedwatchPositionsR3->SdFields->find('list', ['limit' => 200]);
        $this->set(compact('sdMedwatchPositionsR3', 'sdFields'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Sd Medwatch Positions R3 id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sdMedwatchPositionsR3 = $this->SdMedwatchPositionsR3->get($id);
        if ($this->SdMedwatchPositionsR3->delete($sdMedwatchPositionsR3)) {
            $this->Flash->success(__('The sd medwatch positions r3 has been deleted.'));
        } else {
            $this->Flash->error(__('The sd medwatch positions r3 could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    

    /**
    *  Generate PDF files
    *
    */
    //get direct value from sd_field_values table
    public function getDirectValue($caseId,$field_id,$set_num=null){
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

    //get caption value by join table sd_field_value_look_ups and sd_field_values together
    public function getLookupValue($caseId,$field_id,$set_num=null){
        $more_conditions = "";
        if (!is_null($set_num))
        {
            $more_conditions = "set_number=".substr($set_num,0,1);
        }
        $sdFieldValues = TableRegistry::get('sdFieldValues');
        $lookup= $sdFieldValues ->find()
            ->select(['look.caption'])
             ->join([
                    'look' =>[
                        'table' =>'sd_field_value_look_ups',
                        'type'=>'INNER',
                        'conditions'=>['sd_case_id='.$caseId,'look.sd_field_id = sdFieldValues.sd_field_id','status=1',
                                     'sdFieldValues.sd_field_id='.$field_id,'sdFieldValues.field_value=look.value',$more_conditions]
                        ]
                    ])->first();
        $lookupValue=$lookup['look']['caption'];
        return $lookupValue;
    }

    // convert month into MMM format
    public function getMonthValue($informalDate){
        switch(substr($informalDate,2,2)){
            case '00':
                $monthFormat="MMM";
                break;
            case '01':
                $monthFormat="JAN";
                break;
            case '02':
                $monthFormat="FEB";
                break;
            case '03':
                $monthFormat="Mar";
                break;
            case '04':
                $monthFormat="APR";
                break;
            case '05':
                $monthFormat="MAY";
                break;
            case '06':
                $monthFormat="JUN";
                break;
            case '07':
                $monthFormat="JUL";
                break;
            case '08':
                $monthFormat="AUG";
                break;
            case '09':
                $monthFormat="SEP";
                break;
            case '10':
                $monthFormat="OCT";
                break;
            case '11':
                $monthFormat="NOV";
                break;
            case '12':
                $monthFormat="DEC";
                break;
            default: 
                $monthFormat="";
            }
        return $monthFormat;
    }

    //convert date into dd-mmm-yyy format
    public function getDateValue($caseId,$field_id,$set_num=null){
        $informalDate=$this->getDirectValue($caseId,$field_id,$set_num);
        $dayFormat=substr($informalDate,0,2);
        //debug($dayFormat);die();
        if(($dayFormat=='00')){
            $dateFormat="DD";
        }       
        else{
            $dayFormat=$dayFormat."-";
        }
        $yearFormat=substr($informalDate,4,4);
        if($yearFormat=='0000'){
            $yearFormat="YYYY";
        }       
        else{
            $yearFormat="-".$yearFormat;
        }
        return $dayFormat.$this->getMonthValue($informalDate).$yearFormat;
    } 

    //get suspect drugs' set number
    public function SuspectRole($caseId){
        $sdFieldValues = TableRegistry::get('sdFieldValues');
            $suspect= $sdFieldValues->find()
                ->select(['set_number'])
                ->where(['sd_case_id='.$caseId,'sd_field_id=175','status=1','field_value=1']);
            $suspectSet=array();
            foreach($suspect as $suspect_details){
            $suspectSet[]=$suspect_details['set_number'];
            }
        return $suspectSet;
    }
    //get concomitant drugs' set number
    public function ConcomitantRole($caseId){
        $sdFieldValues = TableRegistry::get('sdFieldValues');
            $concomitant= $sdFieldValues->find()
                ->select(['set_number'])
                ->where(['sd_case_id='.$caseId,'sd_field_id=175','status=1','field_value=2']);
            $concomitantSet=array();
            foreach($concomitant as $concomitant_details){
            $concomitantSet[]=$concomitant_details['set_number'];
            }
        return $concomitantSet;
    }
    //function include all type of fields
    public function getMedValue($caseId,$field_id,$value_type,$set_num=null){
        switch($value_type){
            case '1'://direct value+single checkbox+complex checkbox
                $result=$this->getDirectValue($caseId,$field_id,$set_num);
                return $result;
                break;
            case '2'://look up value
                $result=$this->getLookupValue($caseId,$field_id,$set_num);
                return $result;
                break;
            case '3'://date value
                $result=$this->getDateValue($caseId,$field_id,$set_num);
                return $result;
                break;
            case '4'://day value
                $date=$this->getDirectValue($caseId,$field_id,$set_num);
                $result=substr($date,0,2);
                return $result;
                break;
            case '5'://month value
                $date=$this->getDirectValue($caseId,$field_id,$set_num);
                $result=$this->getMonthValue($date);
                return $result;
                break;
            case '6'://Year value
                $date=$this->getDirectValue($caseId,$field_id,$set_num);
                $result=substr($date,4,4);
                return $result;
                break;
            case '7'://c9 dechallenge and c10 rechallenge
                $sdFieldValues = TableRegistry::get('sdFieldValues');
                $challenge =$sdFieldValues->find()
                    ->select(['field_value'])
                    ->where(['sd_case_id='.$caseId,'sd_field_id='.$field_id,'status=1','substr(set_number,-1)='.$set_num])->toList();
                    foreach($challenge as $challenge_detail) {
                        if ($challenge_detail['field_value'] == 1) {
                            $result = 1;
                            break;
                        }else if($challenge_detail['field_value'] == 2){
                            $result = 2;
                        }else if($challenge_detail['field_value'] == 3){
                            $result = 3;
                        }else{
                            $result = 4;
                        }
                    }
                return $result;
            default;

        }
    }
    //call function getMedValue() and find position out
    public function getMedPosition($caseId,$field_id,$value_type,$position_type,$set_num=null){
        $more_conditions = "";
        if (!is_null($set_num))
        {  if($set_num==1){
                $more_conditions = "set_number=".$set_num;
            }if($set_num>1){
                $more_conditions = "set_number=2";
            }
        }
        $MedValue=$this->getMedValue($caseId,$field_id,$value_type,$set_num);
        $sdMedwatchPositions = TableRegistry::get('sdMedwatchPositionsR3');
        switch($position_type){
            case '1': //single position
                $positions=$sdMedwatchPositions->find()
                ->select(['position_top','position_left','position_width','position_height'])
                ->where(['sd_field_id='.$field_id,$more_conditions])
                ->first();
                $text = $text." <style> p {position: absolute;}  </style>";
                $text=$text.'<p style="top: '.$positions['position_top'].'px; left: '.$positions['position_left']
                            .'px; width: '.$positions['position_width'].'px;  height: '.$positions['position_height'].'px; color:black;">'.$MedValue.'</p>';
                break;
            case '2': //checkbox position
                $option=substr($MedValue,-1);
                if((!empty($MedValue))&&($MedValue!='null')){
                $positions=$sdMedwatchPositions->find()
                ->select(['position_top','position_left','position_width','position_height'])
                ->where(['sd_field_id='.$field_id,$more_conditions,'substr(sdMedwatchPositionsR3.field_name,-1)='.$option])
                ->first();
                $text = $text." <style> p {position: absolute;}  </style>";
                $text=$text.'<p style="top: '.$positions['position_top'].'px; left: '.$positions['position_left']
                            .'px; width: '.$positions['position_width'].'px;  height: '.$positions['position_height'].'px; color:black;">'.'X'.'</p>';
                }
                break;
            case '3': //date filled
                $positions=$sdMedwatchPositions->find()
                ->select(['position_top','position_left','position_width','position_height'])
                ->where(['sd_field_id='.$field_id,$more_conditions])
                ->first();
                $text = $text." <style> p {position: absolute;}  </style>";
                $text=$text.'<p style="top: '.$positions['position_top'].'px; left: '.$positions['position_left']
                            .'px; width: '.$positions['position_width'].'px;  height: '.$positions['position_height'].'px; color:black;">'.$MedValue.'</p>';
                break;
            case '4': //day filled
                $positions=$sdMedwatchPositions->find()
                ->select(['position_top','position_left','position_width','position_height'])
                ->where(['sd_field_id='.$field_id,$more_conditions,'substr(sdMedwatchPositionsR3.field_name,-1)="y"'])
                ->first();
                $text = $text." <style> p {position: absolute;}  </style>";
                $text=$text.'<p style="top: '.$positions['position_top'].'px; left: '.$positions['position_left']
                            .'px; width: '.$positions['position_width'].'px;  height: '.$positions['position_height'].'px; color:black;">'.$MedValue.'</p>';
                break;
            case '5'://month filled
                $positions=$sdMedwatchPositions->find()
                ->select(['position_top','position_left','position_width','position_height'])
                ->where(['sd_field_id='.$field_id,$more_conditions,'substr(sdMedwatchPositionsR3.field_name,-1)="h"'])
                ->first();
                $text = $text." <style> p {position: absolute;}  </style>";
                $text=$text.'<p style="top: '.$positions['position_top'].'px; left: '.$positions['position_left']
                            .'px; width: '.$positions['position_width'].'px;  height: '.$positions['position_height'].'px; color:black;">'.$MedValue.'</p>';
                break;
            case '6'://year filled
                $positions=$sdMedwatchPositions->find()
                ->select(['position_top','position_left','position_width','position_height'])
                ->where(['sd_field_id='.$field_id,$more_conditions,'substr(sdMedwatchPositionsR3.field_name,-1)="r"'])
                ->first();
                $text = $text." <style> p {position: absolute;}  </style>";
                $text=$text.'<p style="top: '.$positions['position_top'].'px; left: '.$positions['position_left']
                            .'px; width: '.$positions['position_width'].'px;  height: '.$positions['position_height'].'px; color:black;">'.$MedValue.'</p>';
                break;
            case '7'://multiple checkbox
                $checked=array();
                for($i=0;$i<strlen($MedValue);$i++){
                    $checked[]=substr($MedValue,$i,1);
                }
                
                foreach($checked as $key=>$value){
                    if($value=='1'){
                        $option=$key+1;
                        $positions=$sdMedwatchPositions->find()
                        ->select(['position_top','position_left','position_width','position_height'])
                        ->where(['sd_field_id='.$field_id,$more_conditions,'substr(sdMedwatchPositionsR3.field_name,-1)='.$option])
                        ->first();
                        $text = $text." <style> p {position: absolute;}  </style>";
                        $text=$text.'<p style="top: '.$positions['position_top'].'px; left: '.$positions['position_left']
                                    .'px; width: '.$positions['position_width'].'px;  height: '.$positions['position_height'].'px; color:black;">'.'X'.'</p>';
                    }
                }
                break;
            case '8'://c6 compounded?
            $checked=array();
            for($i=0;$i<strlen($MedValue);$i++){
                $checked[]=substr($MedValue,$i,1);
            }
            if((!empty($checked))&&($checked!='null')){
                $positions=$sdMedwatchPositions->find()
                    ->select(['position_top','position_left','position_width','position_height'])
                    ->where(['sd_field_id='.$field_id,$more_conditions,'substr(sdMedwatchPositionsR3.field_name,-1)='.$checked[1],'value_type=6'])
                    ->first();
                    $text = $text." <style> p {position: absolute;}  </style>";
                    $text=$text.'<p style="top: '.$positions['position_top'].'px; left: '.$positions['position_left']
                                .'px; width: '.$positions['position_width'].'px;  height: '.$positions['position_height'].'px; color:black;">'.'X'.'</p>';
            }else{break;}
            break;
            case '9'://c7 OTC?
            $checked=array();
            for($i=0;$i<strlen($MedValue);$i++){
                $checked[]=substr($MedValue,$i,1);
            }
            if((!empty($checked))&&($checked!='null')){
                $positions=$sdMedwatchPositions->find()
                    ->select(['position_top','position_left','position_width','position_height'])
                    ->where(['sd_field_id='.$field_id,$more_conditions,'substr(sdMedwatchPositionsR3.field_name,-1)='.$checked[0],'value_type=7'])
                    ->first();
                    $text = $text." <style> p {position: absolute;}  </style>";
                    $text=$text.'<p style="top: '.$positions['position_top'].'px; left: '.$positions['position_left']
                                .'px; width: '.$positions['position_width'].'px;  height: '.$positions['position_height'].'px; color:black;">'.'X'.'</p>';
            }else{break;}
            break;
            default;
        }
        return $text;

    }
    //find current date
    public function CurrentTime(){
        $reportDate=date('d-M-Y');
        $sdMedwatchPositions = TableRegistry::get('sdMedwatchPositionsR3');
        $query1=$sdMedwatchPositions->find()
            ->select(['position_top','position_left','position_width','position_height'])
            ->where(['id=68'])
            ->first();
        $query2=$sdMedwatchPositions->find()
            ->select(['position_top','position_left','position_width','position_height'])
            ->where(['id=69'])
            ->first();
        $query3=$sdMedwatchPositions->find()
            ->select(['position_top','position_left','position_width','position_height'])
            ->where(['id=70'])
            ->first();
        $text = $text."<style> p {position: absolute;font-size:10px;font-family: courier;}  </style>";
        $text =$text.'<p style="top: '.$query1['position_top'].'px; left: '.$query1['position_left']
            .'px; width: '.$query1['position_width'].'px;  height: '.$query1['position_height'].'px; color:black;">'.substr($reportDate,0,2).'</p>';
        $text =$text.'<p style="top: '.$query2['position_top'].'px; left: '.$query2['position_left']
        .'px; width: '.$query2['position_width'].'px;  height: '.$query2['position_height'].'px; color:black;">'.strtoupper(substr($reportDate,3,3)).'</p>';
        $text =$text.'<p style="top: '.$query3['position_top'].'px; left: '.$query3['position_left']
            .'px; width: '.$query3['position_width'].'px;  height: '.$query3['position_height'].'px; color:black;">'.substr($reportDate,-4).'</p>';
        return $text;
    }
    //get position with already value
    public function getPosition($caseId,$field_id,$set_num=null,$ready_value=null){
        $more_conditions = "";
        if (!is_null($set_num))
        {
            $more_conditions = "set_number=".$set_num;
        }
        $sdMedwatchPositions = TableRegistry::get('sdMedwatchPositionsR3');
        $positions=$sdMedwatchPositions->find()
        ->select(['position_top','position_left','position_width','position_height'])
        ->where(['sd_field_id='.$field_id,$more_conditions])
        ->first();
        $text = $text." <style> p {position: absolute;}  </style>";
        $text=$text.'<p style="top: '.$positions['position_top'].'px; left: '.$positions['position_left']
                .'px; width: '.$positions['position_width'].'px;  height: '.$positions['position_height'].'px; color:black;">'.$ready_value.'</p>';
        return $text;
    }
    //b5 describe Event
    public function getDescribeEventValue($caseId,$term,$llt,$pt,$narrative,$repoCom,$remark,$additionCom){
        $sdFieldValues = TableRegistry::get('sdFieldValues');
        $eventQuantity= $sdFieldValues->find()
        ->select(['set_number'])
        ->where(['sd_case_id='.$caseId,'sd_field_id='.$term,'status=1'])->toArray(); 
        $length=count($eventQuantity);
        for($i=0;$i<$length;$i++){
            $set_num=$eventQuantity[$i]['set_number'];
            $primarySourceReaction= $this->getDirectValue($caseId,$term,$set_num);
                if($primarySourceReaction=="null"){
                    $primarySourceReaction=" ";
                }
                else{
                    $primarySourceReaction="Report term:".$primarySourceReaction;
                }
            $LLT=$this->getDirectValue($caseId,$llt,$set_num);
                if($LLT=="null"){
                    $LLT=" ";
                }
                else{
                    $LLT="/LLT:".$LLT;
                }
            $PT=$this->getDirectValue($caseId,$pt,$set_num);
                if($PT=="null"){
                    $PT=" ";
                }
                else{
                    $PT="/PT:".$PT;
                }
            $j=$i+1;
            $describe=$primarySourceReaction.$LLT.$PT;
            $description.="#".$j.")  ".$describe."<br>";
        }
        $narrativeIncludeClinical= $this->getDirectValue($caseId,$narrative,1);
            if ($narrativeIncludeClinical=="null"||$narrativeIncludeClinical==null){
                $narrativeIncludeClinical=" ";
            }else{
                $narrativeIncludeClinical="Case Description:".$narrativeIncludeClinical;
            }
        $reporterComment= $this->getDirectValue($caseId,$repoCom,1);
        if ($reporterComment=="null"||$reporterComment==null){
            $reporterComment=" ";
        }else{
            $reporterComment="Reporter Comments:".$reporterComment;
        }
       
        $compamyRemarksFlag=$this->getDirectValue($caseId,560,1);
        if($compamyRemarksFlag==1){
        $compamyRemarks=$this->getDirectValue($caseId,$remark,1);
            if ($compamyRemarks=="null"||$compamyRemarks==null){
                $compamyRemarks=" ";
            }else{
                $compamyRemarks="Company Remarks:".$compamyRemarks;
            }
        }else{$compamyRemarks=" ";}
        $additionalCommentFlag=$this->getDirectValue($caseId,561,1);
        if($additionalCommentFlag==1){
        $additionalComment=$this->getDirectValue($caseId,$additionCom,1);
            if ($additionalComment=="null"||$additionalComment==null){
                $additionalComment=" ";
            }else{
                $additionalComment="Additioal Comments:".$additionalComment;
            }
        }else{$additionalComment=" ";}
        $result=$description."<br>".$narrativeIncludeClinical."<br>".$reporterComment."<br>".$compamyRemarks."<br>".$additionalComment;
        return $result;

    }
    //b6 relevant tests/laboratory data in page one
    public function labDataOne($caseId){
        $sdFieldValues = TableRegistry::get('sdFieldValues');
        $lab= $sdFieldValues->find()
            ->select(['set_number'])
            ->where(['sd_case_id='.$caseId,'sd_field_id=1111','status=1']);
        $labSet=array();
        foreach($lab as $lab_details){
            $labSet[]=$lab_details['set_number'];
        }
        $length=count($labSet);
        $testdata="";
        for($i=0;$i<$length;$i++){
            $setNumber=$labSet[$i];
            $query1=$this->getDirectValue($caseId,1111,$setNumber);
            if($query1!=null){
                $query1="Test Name:".$query1."/";
            }
            $query2=$this->getDateValue($caseId,167,$setNumber);
            if($query2!=null){
                $query2="Test Date:".$query2."/";
            }
            $query3=$this->getDirectValue($caseId,1112,$setNumber);
            if($query3!=null){
                $query3="Test Result:".$query3." ";
            }
            
            $query4=$this->getLookupValue($caseId,1113,$setNumber);
            if($query4!=null){
                $query4=$query4."/";
            }
            $query5=$this->getDirectValue($caseId,171,$setNumber);
            if($query5!=null){
                $query5="Normal value low:".$query5."/";
            }
            $query6=$this->getDirectValue($caseId,172,$setNumber);
            if($query6!=null){
                $query6="Normal value high:".$query6."/";
            }
            $query7=$this->getDirectValue($caseId,361,$setNumber);
            if($query7!=null){
                $query7="Comments:".$query7;
            }
            $description=$query1.$query2.$query3.$query4.$query5.$query6.$query7; 
            $j=$i+1;
            $testdata.= "#".$j.")  ".$description."<br>";  
        }
        $labDataOne=substr($testdata,0,300);
        $labPositionOne=$this->getPosition($caseId,174,1,$labDataOne);
        return $labPositionOne;
    }
    //b6 in page three
    public function labDataThree($caseId){
        $sdFieldValues = TableRegistry::get('sdFieldValues');
        $lab= $sdFieldValues->find()
            ->select(['set_number'])
            ->where(['sd_case_id='.$caseId,'sd_field_id=1111','status=1']);
        $labSet=array();
        foreach($lab as $lab_details){
            $labSet[]=$lab_details['set_number'];
        }
        $length=count($labSet);
        $testdata="";
        for($i=0;$i<$length;$i++){
            $setNumber=$labSet[$i];
            $query1=$this->getDirectValue($caseId,1111,$setNumber);
            if($query1!=null){
                $query1="Test Name:".$query1."/";
            }
            $query2=$this->getDateValue($caseId,167,$setNumber);
            if($query2!=null){
                $query2="Test Date:".$query2."/";
            }
            $query3=$this->getDirectValue($caseId,1112,$setNumber);
            if($query3!=null){
                $query3="Test Result:".$query3." ";
            }
            
            $query4=$this->getLookupValue($caseId,1113,$setNumber);
            if($query4!=null){
                $query4=$query4."/";
            }
            $query5=$this->getDirectValue($caseId,171,$setNumber);
            if($query5!=null){
                $query5="Normal value low:".$query5."/";
            }
            $query6=$this->getDirectValue($caseId,172,$setNumber);
            if($query6!=null){
                $query6="Normal value high:".$query6."/";
            }
            $query7=$this->getDirectValue($caseId,361,$setNumber);
            if($query7!=null){
                $query7="Comments:".$query7;
            }
            $description=$query1.$query2.$query3.$query4.$query5.$query6.$query7; 
            $j=$i+1;
            $testdata.= "#".$j.")  ".$description."<br>";  
        }
        $labDataThree=substr($testdata,300);
        $labPositionThree=$this->getPosition($caseId,174,2,$labDataThree);
        return $labPositionThree;
    }
    public function getCiomsConcomitantValue($caseId,$product,$substanceName,$botainedCountry,$start,$end,$dosageNum,$dosageUnit,$indication){
        $concomitant=$this->ConcomitantRole($caseId);
        $sdFieldValues = TableRegistry::get('sdFieldValues');
        $length=count($concomitant);
            for($i=0;$i<$length;$i++){
                $setNumber=$concomitant[$i];
                $query1=$this->getDirectValue($caseId,$product,$setNumber);        
                $query2=$this->getDirectValue($caseId,$substanceName,$setNumber);
                    if($query2!=null){
                        $substance="(".$query2.")";
                    }
                $query3=$this->getLookupValue($caseId,$botainedCountry,$setNumber);
                    if($query3!=null){
                        $countryObtained="(".$query3.")";
                    }
                $query4=$this->getDateValue($caseId,$start,$setNumber);
                    if($query4==null){
                        $query4="unknown";
                    }
                    else{
                        $query4=$query4." / ";
                    }
                $query5=$this->getDateValue($caseId,$end,$setNumber);
                    
                    if($query5==null){
                        $query5="unknown";
                    }
                $query6=$this->getDirectValue($caseId,$dosageNum,$setNumber);
                    if($query6==null){
                        $query6="unknown";
                    }
                $query7=$this->getLookupValue($caseId,$dosageUnit,$setNumber);
                $query8=$this->getDirectValue($caseId,$indication,$setNumber);
                    if($query8==null){
                        $query8="unknown";
                    }
                $description=$query1.$substance.$countryObtained." | ".$query4.$query5." | ".$query6." ".$query7." | ".$query8; 
                $j=$i+1;
                $concomitantProducts .= "#".$j.")  ".$description."<br>";
                
            }
        return $concomitantProducts;
    }
    //c2 concomitanat medical products and therapy dates in page one
    public function concomitantTherapyOne($caseId){
        $concomitantProducts=$this->getCiomsConcomitantValue($caseId,1114,1115,178,199,205,183,1116,1126);
        $concomitantProducts='Drug    |    Therapy Start and Stop Date    |    Dose    |    Indication<br>'.$concomitantProducts;
        $sdMedwatchPositions = TableRegistry::get('sdMedwatchPositions');
        $positions= $sdMedwatchPositions ->find()
            ->select(['id','sd_field_id','position_top','position_left','position_width','position_height'])
            ->where(['medwatch_no="c2"'])
            ->first(); 
        $text = $text."<style> p {position: absolute;font-size:10px;font-family: courier;}  </style>";
        $pageone=substr($concomitantProducts,0,300);
        
        $text =$text.'<p style="top: '.$positions['position_top'].'px; left: '.$positions['position_left']
                .'px; width: '.$positions['position_width'].'px;  height: '.$positions['position_height'].'px; color:black;">'.$pageone.'</p>';
        return $text;
    }
    //c2 concomitanat medical products and therapy dates in page three
    public function concomitantTherapyThree($caseId){
        $concomitantProducts=$this->getCiomsConcomitantValue($caseId,1114,1115,178,199,205,183,1116,1126);
        $concomitantProducts='Drug    |    Therapy Start and Stop Date    |    Dose    |    Indication<br>'.$concomitantProducts;
        $sdMedwatchPositions = TableRegistry::get('sdMedwatchPositions');
        $positions= $sdMedwatchPositions ->find()
            ->select(['id','sd_field_id','position_top','position_left','position_width','position_height'])
            ->where(['medwatch_no="c2+"'])
            ->first(); 
        $text = $text."<style> p {position: absolute;font-size:10px;font-family: courier;}  </style>";
        $pageThree=substr($concomitantProducts,300);
        
        $text =$text.'<p style="top: '.$positions['position_top'].'px; left: '.$positions['position_left']
                .'px; width: '.$positions['position_width'].'px;  height: '.$positions['position_height'].'px; color:black;">'.$pageThree.'</p>';
        return $text;
    }
    

    public function genPdfThree($caseId)
    {   
        
        //a1 patientID field
        $result=$this->getMedPosition($caseId,1080,1,1);
        //a2 age field  
        $result=$result.$this->getMedPosition($caseId,86,1,1);
        //a2 age unit 
        $result=$result.$this->getMedPosition($caseId,87,1,2);
        //a2 date of birth 
        $result=$result.$this->getMedPosition($caseId,85,4,4);//day
        $result=$result.$this->getMedPosition($caseId,85,5,5);//month
        $result=$result.$this->getMedPosition($caseId,85,6,6);//year
        //a3 sex 
        $result=$result.$this->getMedPosition($caseId,93,1,2);
        //a4 weight
        $result=$result.$this->getMedPosition($caseId,91,1,1);
        $result=$result.'<p style="top: 205px; left: 368px; width: 18px;  height: 18px; color:black;">'.'X'.'</p>';
        //a5a ethnicity
        $result=$result.$this->getMedPosition($caseId,1061,1,2);
        //a5b race
        $result=$result.$this->getMedPosition($caseId,1060,1,2);
        //b1 adverse event
        $result=$result.$this->getMedPosition($caseId,224,1,2);
        // b2 serious TODO
        $result=$result.$this->getMedPosition($caseId,1019,1,2);
        $result=$result.$this->getMedPosition($caseId,1020,1,2);
        $result=$result.$this->getMedPosition($caseId,1021,1,2);
        $result=$result.$this->getMedPosition($caseId,1022,1,2);
        $result=$result.$this->getMedPosition($caseId,1023,1,2);
        $result=$result.$this->getMedPosition($caseId,1024,1,2);
        //b2 death date
        $result=$result.$this->getMedPosition($caseId,115,4,4);//day
        $result=$result.$this->getMedPosition($caseId,115,5,5);//month
        $result=$result.$this->getMedPosition($caseId,115,6,6);//year
        // b3 date of event
        $result=$result.$this->getMedPosition($caseId,1108,4,4);//day
        $result=$result.$this->getMedPosition($caseId,1108,5,5);//month
        $result=$result.$this->getMedPosition($caseId,1108,6,6);//year
        //b4
        $result=$result.$this->CurrentTime();
        // b5 describe event or problem
        $describeOne=substr($this->getDescribeEventValue($caseId,1105,392,394,1134,1135,1138,310),0,600);
        $result=$result.$this->getPosition($caseId,1134,1,$describeOne);   
        // b6 relevant tests/laboratory data
        $result=$result.$this->labDataOne($caseId);   
        // b7 other relevant history
        $historyOne=substr($this->getMedValue($caseId,104,1),0,300);
        $result=$result.$this->getPosition($caseId,104,1,$historyOne);   
        //c2 concomitant medical products and therapy dates
        $result=$result.$this->concomitantTherapyOne($caseId);
        $suspect=$this->SuspectRole($caseId);
        if(!is_null($suspect[0])){
            // c1#1 name and strength
            $result=$result.$this->getMedPosition($caseId,1114,1,1,$suspect[0]);
            // c1#1 NDC or unique ID
            $result=$result.$this->getMedPosition($caseId,345,1,1,$suspect[0]);
            // c1#1 Manufacturer/compounder
            $result=$result.$this->getMedPosition($caseId,284,1,1,$suspect[0]);
            // c1#1 Lot number
            $result=$result.$this->getMedPosition($caseId,179,1,1,$suspect[0]);
            //c3#1 dose
            $dosageOne=$this->getMedValue($caseId,183,1,$suspect[0]).$this->getMedValue($caseId,184,2,$suspect[0]);
            $result=$result.$this->getPosition($caseId,183,1,$dosageOne);
            //c3#1 frequency
            $frequencyOne=$this->getMedValue($caseId,185,1,$suspect[0]);
            if($frequencyOne!=null){$frequencyOne=$frequencyOne."time(s)".$this->getMedValue($caseId,186,1,$suspect[0]).$this->getMedValue($caseId,187,2,$suspect[0]);}
            $result=$result.$this->getPosition($caseId,185,1,$frequencyOne);
            //c3#1 route used
            $result=$result.$this->getMedPosition($caseId,1122,2,1,$suspect[0]);
            //c4#1 start day
            $result=$result.$this->getMedPosition($caseId,199,3,3,$suspect[0]);
            //c4#1 stop day
            $result=$result.$this->getMedPosition($caseId,205,3,3,$suspect[0]);
            //c5#1 diagnosis for use
            $result=$result.$this->getMedPosition($caseId,1126,1,1,$suspect[0]);
            //c6#1 is the product compounded?
            $result=$result.$this->getMedPosition($caseId,425,1,8,$suspect[0]);
            //c7#1 Is the product over-the-counter?
            $result=$result.$this->getMedPosition($caseId,425,1,9,$suspect[0]);
            // c8#1 expiration date
            $result=$result.$this->getMedPosition($caseId,298,4,4,$suspect[0]);//day
            $result=$result.$this->getMedPosition($caseId,298,5,5,$suspect[0]);//month
            $result=$result.$this->getMedPosition($caseId,298,6,6,$suspect[0]);//year
            //c9#1 dechallenge?
            $result=$result.$this->getMedPosition($caseId,381,7,2,$suspect[0]);
            //c10#1 rechallenge?
            $result=$result.$this->getMedPosition($caseId,1147,7,2,$suspect[0]);
        }
        
        if(!is_null($suspect[1])){
        // c1#2 name and strength
            $result=$result.$this->getMedPosition($caseId,1114,1,1,$suspect[1]);
            // c1#2 NDC or unique ID
            $result=$result.$this->getMedPosition($caseId,345,1,1,$suspect[1]);
            // c1#2 Manufacturer/compounder
            $result=$result.$this->getMedPosition($caseId,284,1,1,$suspect[1]);
            // c1#2 Lot number
            $result=$result.$this->getMedPosition($caseId,179,1,1,$suspect[1]);
            //c3#2 dose
            $dosageTwo=$this->getMedValue($caseId,183,1,$suspect[1]).$this->getMedValue($caseId,184,2,$suspect[1]);
            $result=$result.$this->getPosition($caseId,183,2,$dosageTwo);
            //c3#2 frequency
            $frequencyTwo=$this->getMedValue($caseId,185,1,$suspect[1]);
            if($frequencyTwo!=null){$frequencyTwo=$frequencyTwo."time(s)".$this->getMedValue($caseId,186,1,$suspect[1]).$this->getMedValue($caseId,187,2,$suspect[1]);}
            $result=$result.$this->getPosition($caseId,185,2,$frequencyTwo);
            //c3#2 route used
            $result=$result.$this->getMedPosition($caseId,1122,2,1,$suspect[1]);
            //c4#2 start day
            $result=$result.$this->getMedPosition($caseId,199,3,3,$suspect[1]);
            //c4#2 stop day
            $result=$result.$this->getMedPosition($caseId,205,3,3,$suspect[1]);
            //c5#2 diagnosis for use
            $result=$result.$this->getMedPosition($caseId,1126,1,1,$suspect[1]);
            //c6#2 is the product compounded?
            $result=$result.$this->getMedPosition($caseId,425,1,8,$suspect[1]);
            //c7#2 Is the product over-the-counter?
            $result=$result.$this->getMedPosition($caseId,425,1,9,$suspect[1]);
            // C8#2 expiration date
            $result=$result.$this->getMedPosition($caseId,298,4,4,$suspect[1]);//day
            $result=$result.$this->getMedPosition($caseId,298,5,5,$suspect[1]);//month
            $result=$result.$this->getMedPosition($caseId,298,6,6,$suspect[1]);//year 
            //c9#2 dechallenge?
            $result=$result.$this->getMedPosition($caseId,381,7,2,$suspect[1]);
            //c10#2 rechallenge?
            $result=$result.$this->getMedPosition($caseId,1147,7,2,$suspect[1]);
        }
        // e1 last name
        $result=$result.$this->getMedPosition($caseId,1070,1,1);
        //e1 first name
        $result=$result.$this->getMedPosition($caseId,1068,1,1); 
        //e1 address
        $result=$result.$this->getMedPosition($caseId,31,1,1); 
        //e1 city
        $result=$result.$this->getMedPosition($caseId,32,1,1); 
        //e1 state
        $result=$result.$this->getMedPosition($caseId,33,1,1); 
        //e1 country
        $result=$result.$this->getMedPosition($caseId,35,2,1); 
        // e1 zip
        $result=$result.$this->getMedPosition($caseId,34,1,1);
        //e1 phone 
        $result=$result.$this->getMedPosition($caseId,229,1,1);
        //e1 email
        $result=$result.$this->getMedPosition($caseId,232,1,1);
        //e2 health professional?
        $result=$result.$this->getMedPosition($caseId,342,1,2);
        //e3 occuption
        $result=$result.$this->getMedPosition($caseId,36,2,1);
        //e4 Initial reporter also sent report to FDA?
        $result=$result.$this->getMedPosition($caseId,432,1,2);
        // Require composer autoload
        //require_once __DIR__ . '../vendor/autoload.php';
        // Require composer autoload
        //require_once __DIR__ . '../vendor/autoload.php';

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->CSSselectMedia='mpdf';
        $mpdf->SetTitle('FDA-MEDWATCH'); 
        //$medwatchdata = $this->SdTabs->find();
        $mpdf->use_kwt = true;
        $mpdf->SetImportUse();
        $mpdf->SetDocTemplate('export_template/MEDWATCH.pdf',true);

        // If needs to link external css file, uncomment the next 2 lines
        // $stylesheet = file_get_contents('css/genpdf.css');
        // $mpdf->WriteHTML($stylesheet,1);
        $mpdf->WriteHTML($result);
        $mpdf->AddPage();
        //$test2 = '<img src="img/pdficon.png" />';
        //$mpdf->WriteHTML("second page");
        $mpdf->AddPage();
        $describeThree=substr($this->getDescribeEventValue($caseId,1105,392,394,1134,1135,1138,310),600);//b5 describe event continue
        $continue=$this->getPosition($caseId,1134,2,$describeThree);
        // b6 relevant tests continue
        $continue=$continue.$this->labDataThree($caseId);
        $historyThree=substr($this->getMedValue($caseId,104,1),300);//b7 other relevant history continue
        $continue=$continue.$this->getPosition($caseId,104,2,$historyThree);   
        $continue=$continue.$this->concomitantTherapyThree($caseId); //c2 concomitant medical continue
        $mpdf->WriteHTML($continue);
        $mpdf->Output();
        // Download a PDF file directly to LOCAL, uncomment while in real useage
        //$mpdf->Output('TEST.pdf', \Mpdf\Output\Destination::DOWNLOAD);
        $this->set(compact('positions'));
    }



    public function genPdfThreeDRAFT($caseId)
    {   
        
        //a1 patientID field
        $result=$this->getMedPosition($caseId,1080,1,1);
        //a2 age field  
        $result=$result.$this->getMedPosition($caseId,86,1,1);
        //a2 age unit 
        $result=$result.$this->getMedPosition($caseId,87,1,2);
        //a2 date of birth 
        $result=$result.$this->getMedPosition($caseId,85,4,4);//day
        $result=$result.$this->getMedPosition($caseId,85,5,5);//month
        $result=$result.$this->getMedPosition($caseId,85,6,6);//year
        //a3 sex 
        $result=$result.$this->getMedPosition($caseId,93,1,2);
        //a4 weight
        $result=$result.$this->getMedPosition($caseId,91,1,1);
        $result=$result.'<p style="top: 205px; left: 368px; width: 18px;  height: 18px; color:black;">'.'X'.'</p>';
        //a5a ethnicity
        $result=$result.$this->getMedPosition($caseId,1061,1,2);
        //a5b race
        $result=$result.$this->getMedPosition($caseId,1060,1,2);
        //b1 adverse event
        $result=$result.$this->getMedPosition($caseId,224,1,2);
        // b2 serious TODO
        $result=$result.$this->getMedPosition($caseId,1019,1,2);
        $result=$result.$this->getMedPosition($caseId,1020,1,2);
        $result=$result.$this->getMedPosition($caseId,1021,1,2);
        $result=$result.$this->getMedPosition($caseId,1022,1,2);
        $result=$result.$this->getMedPosition($caseId,1023,1,2);
        $result=$result.$this->getMedPosition($caseId,1024,1,2);
        //b2 death date
        $result=$result.$this->getMedPosition($caseId,115,4,4);//day
        $result=$result.$this->getMedPosition($caseId,115,5,5);//month
        $result=$result.$this->getMedPosition($caseId,115,6,6);//year
        // b3 date of event
        $result=$result.$this->getMedPosition($caseId,1108,4,4);//day
        $result=$result.$this->getMedPosition($caseId,1108,5,5);//month
        $result=$result.$this->getMedPosition($caseId,1108,6,6);//year
        //b4
        $result=$result.$this->CurrentTime();
        // b5 describe event or problem
        $describeOne=substr($this->getDescribeEventValue($caseId,1105,392,394,1134,1135,1138,310),0,600);
        $result=$result.$this->getPosition($caseId,1134,1,$describeOne);   
        // b6 relevant tests/laboratory data
        $result=$result.$this->labDataOne($caseId);   
        // b7 other relevant history
        $historyOne=substr($this->getMedValue($caseId,104,1),0,300);
        $result=$result.$this->getPosition($caseId,104,1,$historyOne);   
        //c2 concomitant medical products and therapy dates
        $result=$result.$this->concomitantTherapyOne($caseId);
        $suspect=$this->SuspectRole($caseId);
        if(!is_null($suspect[0])){
            // c1#1 name and strength
            $result=$result.$this->getMedPosition($caseId,1114,1,1,$suspect[0]);
            // c1#1 NDC or unique ID
            $result=$result.$this->getMedPosition($caseId,345,1,1,$suspect[0]);
            // c1#1 Manufacturer/compounder
            $result=$result.$this->getMedPosition($caseId,284,1,1,$suspect[0]);
            // c1#1 Lot number
            $result=$result.$this->getMedPosition($caseId,179,1,1,$suspect[0]);
            //c3#1 dose
            $dosageOne=$this->getMedValue($caseId,183,1,$suspect[0]).$this->getMedValue($caseId,184,2,$suspect[0]);
            $result=$result.$this->getPosition($caseId,183,1,$dosageOne);
            //c3#1 frequency
            $frequencyOne=$this->getMedValue($caseId,185,1,$suspect[0]);
            if($frequencyOne!=null){$frequencyOne=$frequencyOne."time(s)".$this->getMedValue($caseId,186,1,$suspect[0]).$this->getMedValue($caseId,187,2,$suspect[0]);}
            $result=$result.$this->getPosition($caseId,185,1,$frequencyOne);
            //c3#1 route used
            $result=$result.$this->getMedPosition($caseId,1122,2,1,$suspect[0]);
            //c4#1 start day
            $result=$result.$this->getMedPosition($caseId,199,3,3,$suspect[0]);
            //c4#1 stop day
            $result=$result.$this->getMedPosition($caseId,205,3,3,$suspect[0]);
            //c5#1 diagnosis for use
            $result=$result.$this->getMedPosition($caseId,1126,1,1,$suspect[0]);
            //c6#1 is the product compounded?
            $result=$result.$this->getMedPosition($caseId,425,1,8,$suspect[0]);
            //c7#1 Is the product over-the-counter?
            $result=$result.$this->getMedPosition($caseId,425,1,9,$suspect[0]);
            // c8#1 expiration date
            $result=$result.$this->getMedPosition($caseId,298,4,4,$suspect[0]);//day
            $result=$result.$this->getMedPosition($caseId,298,5,5,$suspect[0]);//month
            $result=$result.$this->getMedPosition($caseId,298,6,6,$suspect[0]);//year
            //c9#1 dechallenge?
            $result=$result.$this->getMedPosition($caseId,381,7,2,$suspect[0]);
            //c10#1 rechallenge?
            $result=$result.$this->getMedPosition($caseId,1147,7,2,$suspect[0]);
        }
        
        if(!is_null($suspect[1])){
        // c1#2 name and strength
            $result=$result.$this->getMedPosition($caseId,1114,1,1,$suspect[1]);
            // c1#2 NDC or unique ID
            $result=$result.$this->getMedPosition($caseId,345,1,1,$suspect[1]);
            // c1#2 Manufacturer/compounder
            $result=$result.$this->getMedPosition($caseId,284,1,1,$suspect[1]);
            // c1#2 Lot number
            $result=$result.$this->getMedPosition($caseId,179,1,1,$suspect[1]);
            //c3#2 dose
            $dosageTwo=$this->getMedValue($caseId,183,1,$suspect[1]).$this->getMedValue($caseId,184,2,$suspect[1]);
            $result=$result.$this->getPosition($caseId,183,2,$dosageTwo);
            //c3#2 frequency
            $frequencyTwo=$this->getMedValue($caseId,185,1,$suspect[1]);
            if($frequencyTwo!=null){$frequencyTwo=$frequencyTwo."time(s)".$this->getMedValue($caseId,186,1,$suspect[1]).$this->getMedValue($caseId,187,2,$suspect[1]);}
            $result=$result.$this->getPosition($caseId,185,2,$frequencyTwo);
            //c3#2 route used
            $result=$result.$this->getMedPosition($caseId,1122,2,1,$suspect[1]);
            //c4#2 start day
            $result=$result.$this->getMedPosition($caseId,199,3,3,$suspect[1]);
            //c4#2 stop day
            $result=$result.$this->getMedPosition($caseId,205,3,3,$suspect[1]);
            //c5#2 diagnosis for use
            $result=$result.$this->getMedPosition($caseId,1126,1,1,$suspect[1]);
            //c6#2 is the product compounded?
            $result=$result.$this->getMedPosition($caseId,425,1,8,$suspect[1]);
            //c7#2 Is the product over-the-counter?
            $result=$result.$this->getMedPosition($caseId,425,1,9,$suspect[1]);
            // C8#2 expiration date
            $result=$result.$this->getMedPosition($caseId,298,4,4,$suspect[1]);//day
            $result=$result.$this->getMedPosition($caseId,298,5,5,$suspect[1]);//month
            $result=$result.$this->getMedPosition($caseId,298,6,6,$suspect[1]);//year 
            //c9#2 dechallenge?
            $result=$result.$this->getMedPosition($caseId,381,7,2,$suspect[1]);
            //c10#2 rechallenge?
            $result=$result.$this->getMedPosition($caseId,1147,7,2,$suspect[1]);
        }
        // e1 last name
        $result=$result.$this->getMedPosition($caseId,1070,1,1);
        //e1 first name
        $result=$result.$this->getMedPosition($caseId,1068,1,1); 
        //e1 address
        $result=$result.$this->getMedPosition($caseId,31,1,1); 
        //e1 city
        $result=$result.$this->getMedPosition($caseId,32,1,1); 
        //e1 state
        $result=$result.$this->getMedPosition($caseId,33,1,1); 
        //e1 country
        $result=$result.$this->getMedPosition($caseId,35,2,1); 
        // e1 zip
        $result=$result.$this->getMedPosition($caseId,34,1,1);
        //e1 phone 
        $result=$result.$this->getMedPosition($caseId,229,1,1);
        //e1 email
        $result=$result.$this->getMedPosition($caseId,232,1,1);
        //e2 health professional?
        $result=$result.$this->getMedPosition($caseId,342,1,2);
        //e3 occuption
        $result=$result.$this->getMedPosition($caseId,36,2,1);
        //e4 Initial reporter also sent report to FDA?
        $result=$result.$this->getMedPosition($caseId,432,1,2);
        // Require composer autoload
        //require_once __DIR__ . '../vendor/autoload.php';
        // Require composer autoload
        //require_once __DIR__ . '../vendor/autoload.php';

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->CSSselectMedia='mpdf';
        $mpdf->SetTitle('FDA-MEDWATCH');
        $mpdf->SetWatermarkImage('../img/draft-watermark.jpg');
        $mpdf->showWatermarkImage = true;   
        //$medwatchdata = $this->SdTabs->find();
        $mpdf->use_kwt = true;
        $mpdf->SetImportUse();
        $mpdf->SetDocTemplate('export_template/MEDWATCH.pdf',true);

        // If needs to link external css file, uncomment the next 2 lines
        // $stylesheet = file_get_contents('css/genpdf.css');
        // $mpdf->WriteHTML($stylesheet,1);
        $mpdf->WriteHTML($result);
        $mpdf->AddPage();
        //$test2 = '<img src="img/pdficon.png" />';
        //$mpdf->WriteHTML("second page");
        $mpdf->AddPage();
        $describeThree=substr($this->getDescribeEventValue($caseId,1105,392,394,1134,1135,1138,310),600);//b5 describe event continue
        $continue=$this->getPosition($caseId,1134,2,$describeThree);
        // b6 relevant tests continue
        $continue=$continue.$this->labDataThree($caseId);
        $historyThree=substr($this->getMedValue($caseId,104,1),300);//b7 other relevant history continue
        $continue=$continue.$this->getPosition($caseId,104,2,$historyThree);   
        $continue=$continue.$this->concomitantTherapyThree($caseId); //c2 concomitant medical continue
        $mpdf->WriteHTML($continue);
        $mpdf->Output();
        // Download a PDF file directly to LOCAL, uncomment while in real useage
        //$mpdf->Output('TEST.pdf', \Mpdf\Output\Destination::DOWNLOAD);
        $this->set(compact('positions'));

    }
}
