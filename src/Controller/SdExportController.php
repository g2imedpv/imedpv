<?php
namespace App\Controller;
use Cake\ORM\TableRegistry;


class SdExportController extends AppController
    {
        //function for CIOMS and MEDWATCH:
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

        //get caption value by join table 'sd_field_value_look_ups' and 'sd_field_values' together
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

        public function getLookupValueThree($caseId,$field_id,$set_num=null){
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
                            'table' =>'sd_field_value_look_ups_r3',
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

        // //convert date into dd-mmm-yyy format
        public function getDateValue($caseId,$field_id,$set_num=null){
            $informalDate=$this->getDirectValue($caseId,$field_id,$set_num);
            if($informalDate!=null){
                $dayFormat=substr($informalDate,0,2);
                //debug($dayFormat);die();
                if(($dayFormat=='00')){
                    $dayFormat="DD-";
                }
                else{
                    $dayFormat=$dayFormat."-";
                }
                $yearFormat=substr($informalDate,4,4);
                if($yearFormat=='0000'){
                    $yearFormat="-YYYY";
                }
                else{
                    $yearFormat="-".$yearFormat;
                }
                return $dayFormat.$this->getMonthValue($informalDate).$yearFormat;
            }
            else{
                return "";
            }
        }
        //Grab the first/last $num word from the $words string
        // public function subwords($words,$num,$order){
        //     $total_num = str_word_count($words); // get words count
        //     if($total_num <= $num){
        //         return $words;
        //     }else{
        //         $pattern = '/([\S]+?[ ]+){'.$num.'}/';
        //         preg_match($pattern, $words, $out);
        //         return $out[$order];
        //     }
        // }

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

        // /**
        // *  Generate CIOMS files
        // *
        // */

        //function of no.7+13
        public function getDescribeValue($caseId,$term,$llt,$pt,$outcome,$action,$narrative,$repoCom,$test,$remark,$additionCom,$unstructuredData){
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
                $reactionOutcome= $this->getLookupValue($caseId,$outcome,$set_num);
                    if ($reactionOutcome=="null"||$reactionOutcome==null){
                        $reactionOutcome=" ";
                    }
                    else{
                        $reactionOutcome="/Reporter Assessment Outcome:".$reactionOutcome;
                    }
                $actionDrug= $this->getLookupValue($caseId,$action,$set_num);
                    if ($actionDrug=="null"||$actionDrug==null){
                        $actionDrug=" ";
                    }
                    else{
                        $actionDrug="/Actions Taken With Drug:".$actionDrug;
                    }
                $j=$i+1;
                $describe=$primarySourceReaction.$LLT.$PT."     ".$reactionOutcome."     ".$actionDrug;
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

            if($term=="1105"){
                $resultsTestsProcedures="";
            }else{
                $resultsTestsProcedures=$this->getDirectValue($caseId,$test,1);
                if ($resultsTestsProcedures=="null"||$resultsTestsProcedures==null){
                    $resultsTestsProcedures=" ";
                }else{
                    $resultsTestsProcedures="Relevant Tests/Laboratory Data:".$resultsTestsProcedures;
                }
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

            $resultUnstructuredData=$this->getDirectValue($caseId,169,1);
            $result=$description."<br>".$narrativeIncludeClinical."<br>".$reporterComment."<br>".$resultsTestsProcedures."<br>".$compamyRemarks."<br>".$additionalComment."<br>".$resultUnstructuredData;
            return $result;

        }
        public function getDescribeValueThree($caseId,$term,$llt,$pt,$outcome,$action,$narrative,$repoCom,$test,$remark,$additionCom,$unstructuredData){
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
                $reactionOutcome= $this->getLookupValueThree($caseId,$outcome,$set_num);
                    if ($reactionOutcome=="null"||$reactionOutcome==null){
                        $reactionOutcome=" ";
                    }
                    else{
                        $reactionOutcome="/Reporter Assessment Outcome:".$reactionOutcome;
                    }
                $actionDrug= $this->getLookupValueThree($caseId,$action,$set_num);
                    if ($actionDrug=="null"||$actionDrug==null){
                        $actionDrug=" ";
                    }
                    else{
                        $actionDrug="/Actions Taken With Drug:".$actionDrug;
                    }
                $j=$i+1;
                $describe=$primarySourceReaction.$LLT.$PT."     ".$reactionOutcome."     ".$actionDrug;
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

            if($term=="1105"){
                $resultsTestsProcedures="";
            }else{
                $resultsTestsProcedures=$this->getDirectValue($caseId,$test,1);
                if ($resultsTestsProcedures=="null"||$resultsTestsProcedures==null){
                    $resultsTestsProcedures=" ";
                }else{
                    $resultsTestsProcedures="Relevant Tests/Laboratory Data:".$resultsTestsProcedures;
                }
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

            $resultUnstructuredData=$this->getDirectValue($caseId,169,1);
            $result=$description."<br>".$narrativeIncludeClinical."<br>".$reporterComment."<br>".$resultsTestsProcedures."<br>".$compamyRemarks."<br>".$additionalComment."<br>".$resultUnstructuredData;
            return $result;

        }
        public function getDescribeValueCN($caseId,$term,$llt,$pt,$outcome,$action,$narrative,$repoCom,$test,$remark,$additionCom,$unstructuredData){
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
                        $primarySourceReaction="反应/事件:".$primarySourceReaction;
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
                $reactionOutcome= $this->getLookupValueThree($caseId,$outcome,$set_num);
                    if ($reactionOutcome=="null"||$reactionOutcome==null){
                        $reactionOutcome=" ";
                    }
                    else{
                        $reactionOutcome="/报告者结果评估:".$reactionOutcome;
                    }
                $actionDrug= $this->getLookupValueThree($caseId,$action,$set_num);
                    if ($actionDrug=="null"||$actionDrug==null){
                        $actionDrug=" ";
                    }
                    else{
                        $actionDrug="/针对药物采取的措施:".$actionDrug;
                    }
                $j=$i+1;
                $describe=$primarySourceReaction.$LLT.$PT."     ".$reactionOutcome."     ".$actionDrug;
                $description.="#".$j.")  ".$describe."<br>";
            }
            $narrativeIncludeClinical= $this->getDirectValue($caseId,$narrative,1);
                if ($narrativeIncludeClinical=="null"||$narrativeIncludeClinical==null){
                    $narrativeIncludeClinical=" ";
                }else{
                    $narrativeIncludeClinical="病例描述:".$narrativeIncludeClinical;
                }
            $reporterComment= $this->getDirectValue($caseId,$repoCom,1);
            if ($reporterComment=="null"||$reporterComment==null){
                $reporterComment=" ";
            }else{
                $reporterComment="Reporter Comments:".$reporterComment;
            }

            if($term=="1105"){
                $resultsTestsProcedures="";
            }else{
                $resultsTestsProcedures=$this->getDirectValue($caseId,$test,1);
                if ($resultsTestsProcedures=="null"||$resultsTestsProcedures==null){
                    $resultsTestsProcedures=" ";
                }else{
                    $resultsTestsProcedures="相关实验数据:".$resultsTestsProcedures;
                }
            }
            $compamyRemarksFlag=$this->getDirectValue($caseId,560,1);
            if($compamyRemarksFlag==1){
            $compamyRemarks=$this->getDirectValue($caseId,$remark,1);
                if ($compamyRemarks=="null"||$compamyRemarks==null){
                    $compamyRemarks=" ";
                }else{
                    $compamyRemarks="公司评论:".$compamyRemarks;
                }
            }else{$compamyRemarks=" ";}
            $additionalCommentFlag=$this->getDirectValue($caseId,561,1);
            if($additionalCommentFlag==1){
            $additionalComment=$this->getDirectValue($caseId,$additionCom,1);
                if ($additionalComment=="null"||$additionalComment==null){
                    $additionalComment=" ";
                }else{
                    $additionalComment="附加评论:".$additionalComment;
                }
            }else{$additionalComment=" ";}

            $resultUnstructuredData=$this->getDirectValue($caseId,169,1);
            $result=$description."<br>".$narrativeIncludeClinical."<br>".$reporterComment."<br>".$resultsTestsProcedures."<br>".$compamyRemarks."<br>".$additionalComment."<br>".$resultUnstructuredData;
            return $result;

        }

        //function of no.8-12 checkbox
        public function getCiomsSeriousValue($caseId,$field_id,$set_num=null){
            $choice=$this->getDirectValue($caseId,$field_id,$set_num);
            if(substr($choice,0,1)==1){
                $this->set('patientDied','checked');
            };
            if(substr($choice,1,1)==1){
                $this->set('lifeThreatening','checked');
            };
            if(substr($choice,2,1)==1){
                $this->set('disability','checked');
            };
            if(substr($choice,3,1)==1){
                $this->set('hospitalization','checked');
            };
            if(substr($choice,4,1)==1){
                $this->set('congenital','checked');
            };
            if(substr($choice,5,1)==1){
                $this->set('otherSerious','checked');
            };
        }
        public function getCiomsSeriousValueThree($caseId,$set_num=null){
            if($this->getDirectValue($caseId,1019,$set_num)=="TRUE"){
                $this->set('patientDied','checked');
            };
            if($this->getDirectValue($caseId,1020,$set_num)=="TRUE"){
                $this->set('lifeThreatening','checked');
            };
            if($this->getDirectValue($caseId,1021,$set_num)=="TRUE"){
                $this->set('disability','checked');
            };
            if($this->getDirectValue($caseId,1022,$set_num)=="TRUE"){
                $this->set('hospitalization','checked');
            };
            if($this->getDirectValue($caseId,1023,$set_num)=="TRUE"){
                $this->set('congenital','checked');
            };
            if($this->getDirectValue($caseId,1024,$set_num)=="TRUE"){
                $this->set('otherSerious','checked');
            };
        }

        //function of no.14
        public function getCiomsSuspectValue($caseId,$product,$substanceName,$botainedCountry,$batchNo){
            $suspect=$this->SuspectRole($caseId);
            $sdFieldValues = TableRegistry::get('sdFieldValues');
            $length=count($suspect);
                for($i=0;$i<$length;$i++){
                    $setNumber=$suspect[$i];
                    $query1=$this->getDirectValue($caseId,$product,$setNumber);
                    $query2=$this->getDirectValue($caseId,$substanceName,$setNumber);
                    if($query2!=null){
                        $substance="(".$query2.")";
                    }
                    $query3=$this->getLookupValue($caseId,$botainedCountry,$setNumber);
                    if($query3!=null){
                        $countryObtained="(".$query3.")";
                    }
                    else{
                        $countryObtained=" ";
                    }
                    $query4=$this->getDirectValue($caseId,$batchNo,$setNumber);
                    if($query4!=null){
                        $lotNumber="   | Batch/Lot Number:".$query4;
                    }
                    else{
                        $lotNumber=" ";
                    }
                    $description=$query1.$substance.$countryObtained.$lotNumber;
                    $j=$i+1;
                    $suspectProducts .= "#".$j.") ".$description."<br>";
                }
            return $suspectProducts;
        }

        //function of no.15
        public function getCiomsDailyDoseValue($caseId,$dosageNum,$dosageUnit,$separated,$unit,$defination){
            $suspect=$this->SuspectRole($caseId);
            $sdFieldValues = TableRegistry::get('sdFieldValues');
            $length=count($suspect);
                for($i=0;$i<$length;$i++){
                    $setNumber=$suspect[$i];
                    $query1=$this->getDirectValue($caseId,$dosageNum,$setNumber);
                    $query2=$this->getLookupValue($caseId,$dosageUnit,$setNumber);
                    $query3=$this->getDirectValue($caseId,$separated,$setNumber);
                    if($query3!=null&&$query3!="null"){
                        $dosage="|  dosage(s) = ".$query3;
                    }
                    else{
                        $dosage=" ";
                    }
                    $query4=$this->getDirectValue($caseId,$unit,$setNumber);
                    if($query4!=null&&$query4!="null"){
                        $interval="|  Interval = ".$query4;
                    }
                    else{
                        $interval=" ";
                    }
                    $query5=$this->getLookupValue($caseId,$defination,$setNumber);
                    $description=$query1.$query2."     ".$dosage."     ".$interval." ".$query5;
                    $j=$i+1;
                    $dailyDose .= "#".$j.")  ".$description."<br>";
                }
            return $dailyDose;
        }

        public function getCiomsDailyDoseValueThree($caseId,$dosageNum,$dosageUnit,$separated,$unit,$defination,$doseText){
            $suspect=$this->SuspectRole($caseId);
            $sdFieldValues = TableRegistry::get('sdFieldValues');
            $length=count($suspect);
                for($i=0;$i<$length;$i++){
                    $setNumber=$suspect[$i];
                    $query1=$this->getDirectValue($caseId,$dosageNum,$setNumber);
                    $query2=$this->getLookupValueThree($caseId,$dosageUnit,$setNumber);
                    $query3=$this->getDirectValue($caseId,$separated,$setNumber);
                    if($query3!=null&&$query3!="null"){
                        $dosage="|  dosage(s) = ".$query3;
                    }
                    else{
                        $dosage=" ";
                    }
                    $query4=$this->getDirectValue($caseId,$unit,$setNumber);
                    if($query4!=null&&$query4!="null"){
                        $interval="|  Interval = ".$query4;
                    }
                    else{
                        $interval=" ";
                    }
                    $query5=$this->getLookupValueThree($caseId,$defination,$setNumber);
                    $query6=$this->getDirectValue($caseId,$doseText,$setNumber);
                    $description=$query1.$query2."     ".$dosage."     ".$interval." ".$query5."     ".$query6;
                    $j=$i+1;
                    $dailyDose .= "#".$j.")  ".$description."<br>";
                }
            return $dailyDose;
        }


        //function of no.16
        public function getCiomsRouteValue($caseId,$route){
            $suspect=$this->SuspectRole($caseId);
            $sdFieldValues = TableRegistry::get('sdFieldValues');
            $length=count($suspect);
                for($i=0;$i<$length;$i++){
                    $setNumber=$suspect[$i];
                    $description=$this->getLookupValue($caseId,$route,$setNumber);
                    $j=$i+1;
                    $Route .= "#".$j.")  ".$description."<br>";
                }
            return $Route;
        }
        public function getCiomsRouteValueThree($caseId,$route){
            $suspect=$this->SuspectRole($caseId);
            $sdFieldValues = TableRegistry::get('sdFieldValues');
            $length=count($suspect);
                for($i=0;$i<$length;$i++){
                    $setNumber=$suspect[$i];
                    $description=$this->getLookupValueThree($caseId,$route,$setNumber);
                    $j=$i+1;
                    $Route .= "#".$j.")  ".$description."<br>";
                }
            return $Route;
        }
        //function of no.17
        public function getCiomsIndicationValue($caseId,$indication,$indicationTerm){
            $suspect=$this->SuspectRole($caseId);
            $length=count($suspect);
                for($i=0;$i<$length;$i++){
                    $setNumber=$suspect[$i];
                    $query1=$this->getDirectValue($caseId,$indication,$setNumber);
                    if($query1!=null&&$query1!="null"){
                        $query1="(".$query1.")";
                    }
                    $query2=$this->getDirectValue($caseId,$indicationTerm,$setNumber);
                    if($query2!=null&&$query2!="null"){
                        $query2="(".$query2.")";
                    }
                    $j=$i+1;
                    $Indication .= "#".$j.")  ".$query1.$query2."<br>";
                }
            return $Indication;
        }
        //function of no.18
        public function getCiomsTherapyValue($caseId,$start,$end){
            $suspect=$this->SuspectRole($caseId);
            $sdFieldValues = TableRegistry::get('sdFieldValues');
            $length=count($suspect);
                for($i=0;$i<$length;$i++){
                    $setNumber=$suspect[$i];
                    $query1=$this->getDateValue($caseId,$start,$setNumber);
                    $query2=$this->getDateValue($caseId,$end,$setNumber);
                    $j=$i+1;
                    $therapy .= "#".$j.")  ".$query1."/".$query2."<br>";
                }
            return $therapy;
        }

        //function of no.19
        public function getCiomsDurationValue($caseId,$duration,$durationUnit){
            $suspect=$this->SuspectRole($caseId);
            $sdFieldValues = TableRegistry::get('sdFieldValues');
            $length=count($suspect);
                for($i=0;$i<$length;$i++){
                    $setNumber=$suspect[$i];
                    $query1=$this->getDirectValue($caseId,$duration,$setNumber);
                    $query2=$this->getLookupValue($caseId,$durationUnit,$setNumber);
                    $j=$i+1;
                    $Duration .= "#".$j.")  ".$query1."  ".$query2."<br>";
                }
            return $Duration;
        }
        //function of no.20 checkbox
        public function getCiomsDechallengeValue($caseId,$field_id,$set_num=null){
            $choice=$this->getDirectValue($caseId,$field_id,$set_num);
            switch($choice){
                case '1':
                    $this->set('DeYes','checked');
                    break;
                case '2':
                    $this->set('DeNo','checked');
                    break;
                case '4':
                    $this->set('DeUnkown','checked');
                    break;
                    default;
                }
        }

        //function of no.21 checkbox
        public function getCiomsRechallengeValue($caseId,$field_id,$set_num=null){
            $choice=$this->getDirectValue($caseId,$field_id,$set_num);
            switch($choice){
                case '1':
                    $this->set('ReYes','checked');
                    break;
                case '2':
                    $this->set('ReNo','checked');
                    break;
                case '4':
                    $this->set('ReUnkown','checked');
                    break;
                    default;
            }
        }

        //function of no.22
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

        public function getCiomsConcomitantValueThree($caseId,$product,$substanceName,$botainedCountry,$start,$end,$dosageNum,$dosageUnit,$indication){
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
                    $query3=$this->getLookupValueThree($caseId,$botainedCountry,$setNumber);
                        if($query3!=null){
                            $countryObtained="(".$query3.")";
                        }
                    $query4=$this->getDateValue($caseId,$start,$setNumber);
                        if($query4==null){
                            $query4=" ";
                        }
                        else{
                            $query4=" | ".$query4." / ";
                        }
                    $query5=$this->getDateValue($caseId,$end,$setNumber);
                        if($query5==null){
                            $query5=" ";
                        }
                    $query6=$this->getDirectValue($caseId,$dosageNum,$setNumber);
                        if($query6==null){
                            $query6=" ";
                        }
                        else{
                            $query6=" | ".$query6;
                        }
                    $query7=$this->getLookupValueThree($caseId,$dosageUnit,$setNumber);
                    $query8=$this->getDirectValue($caseId,$indication,$setNumber);
                        if($query8==null){
                            $query8=" ";
                        }
                        else{
                            $query8=" | ".$query8;
                        }
                    $description=$query1.$substance.$countryObtained.$query4.$query5.$query6.$query7.$query8;
                    $j=$i+1;
                    $concomitantProducts .= "#".$j.")  ".$description."<br>";

                }
            return $concomitantProducts;
        }

        //function of no.23
        public function getCiomsRelevantValue($caseId,$disease,$medStart,$continue,$medEnd,$comment){
            $sdFieldValues = TableRegistry::get('sdFieldValues');
            $Disease= $sdFieldValues->find()
                ->select(['set_number'])
                ->where(['sd_case_id='.$caseId,'sd_field_id='.$disease,'status=1'])->toArray();
            $length=count($Disease,0);
                 for($i=1;$i<=$length;$i++){
                    $query1=$this->getDirectValue($caseId,$disease,$i);
                    $query2=$this->getDateValue($caseId,$medStart,$i);
                    if($query2==null){
                        $query2="unknown";
                    }
                    $query3=$this->getLookupValue($caseId,$continue,$i);
                    if($query3!=null){
                        $query3="continuing:".$query3;
                    }
                    $query4=$this->getDateValue($caseId,$medEnd,$i);
                    if($query4==null){
                        $query4="unknown";
                    }
                    $query=$this->getDirectValue($caseId,$comment,$i);
                    $relevant .= "#".$i.")  ".$query1."  |  ".$query2."  |  ".$query3."   |   ".$query4."  |  ".$query5."<br>";
                }
            return $relevant;
        }

        public function getCiomsRelevantValueThree($caseId,$disease,$medStart,$continue,$medEnd,$comment,$text){
            $sdFieldValues = TableRegistry::get('sdFieldValues');
            $Disease= $sdFieldValues->find()
                ->select(['set_number'])
                ->where(['sd_case_id='.$caseId,'sd_field_id='.$disease,'status=1'])->toArray();
            $length=count($Disease,0);
                 for($i=1;$i<=$length;$i++){
                    $query1=$this->getDirectValue($caseId,$disease,$i);
                    $query2=$this->getDateValue($caseId,$medStart,$i);
                    if($query2==null){
                        $query2="unknown";
                    }
                    $query3=$this->getLookupValueThree($caseId,$continue,$i);
                    if($query3!=null){
                        $query3="continuing:".$query3;
                    }
                    $query4=$this->getDateValue($caseId,$medEnd,$i);
                    if($query4==null){
                        $query4="unknown";
                    }
                    $query5=$this->getDirectValue($caseId,$comment,$i);
                    $query6=$this->getDirectValue($caseId,$text,$i);
                    $relevant .= "#".$i.")  ".$query1."  |  ".$query2."  |  ".$query3."   |   ".$query4."  |  ".$query5."   |    ".$query6."<br>";
                }
            return $relevant;
        }


        //function of no.24d checkbox
        public function getCiomsReportSourceValue($caseId,$set_num=null){
            $choiceOne=$this->getDirectValue($caseId,416,$set_num);
            $choiceTwo=$this->getDirectValue($caseId,342,$set_num);
            $choiceThree=$this->getDirectValue($caseId,416,$set_num);
            if(substr($choiceOne,1,1)==1){
                $this->set('study','checked');
            };
            if($choiceTwo==1){
                $this->set('healthProfessional','checked');
            };
            if(substr($choiceThree,2,1)==1){
                $this->set('literature','checked');
            };
        }

        //function of no.25a checkbox
        public function getCiomsReportTypeValue($caseId,$field_id,$set_num=null){
            $choice=$this->getDirectValue($caseId,$field_id,$set_num);
            switch($choice){
                case '1':
                    $this->set('initial','checked');
                    break;
                case '2':
                    $this->set('followup','checked');
                    break;
                }
        }

        // Call the previous function by $caseId,$field_id and $set_num
        public function genCIOMS($caseId) {
            $this->viewBuilder()->layout('CIOMS');
            //MFR number
            $sdCases = TableRegistry::get('sdCases');
            $name=$sdCases->find()
                    ->select(['caseNo'])
                    ->where(['id='.$caseId,'status=1'])->first();
            $fileName=$name['caseNo'];
            $this->set('fileName', $fileName);
            //1.
            $this->set('patientInitial', $this->getDirectValue($caseId,79));//B.1.1  patientinitial
            //1a.
            $this->set('country', $this->getLookupValue($caseId,3));// A.1.2 occurcountry
            //2.
            $this->set('birth', $this->getDirectValue($caseId,85));// A.1.2.1b patientbirthdate
            $this->set('birthMonth', $this->getMonthValue($this->getDirectValue($caseId,85)));// A.1.2.1b patientbirthdate
            //2a.
            $this->set('age', $this->getDirectValue($caseId,86));//B.1.2.2a patientonsetage
            $this->set('ageUnit',$this->getLookupValue($caseId,87));//B.1.2.2b  patientonsetageunit
            //3
            $this->set('sex',$this->getLookupValue($caseId,93));//B.1.5  sex
            //4-6
            $this->set('reaction', $this->getDirectValue($caseId,156));//B.2.i.4b  reactionstartdate
            $this->set('reactionMonth', $this->getMonthValue($this->getDirectValue($caseId,156)));//B.2.i.4b  reactionstartdate
            //7+13
            $this->set('describe', $this->getDescribeValue($caseId,149,392,394,165,208,218,219,174,222,310,169));//B.2.i.0  primarysourcereaction
            //8-12
            $this->getCiomsSeriousValue($caseId,354);
            //14
            $this->set('suspecttitle','     Drug     |     Batch/Lot Number<br>');
            $this->set('suspectProducts', $this->getCiomsSuspectValue($caseId,176,177,178,179));//B.4.K.2.1 Proprietary Medicinal Product Name
            //15  dailyDose
            $this->set('dailyDose', $this->getCiomsDailyDoseValue($caseId,183,184,185,186,187));//B.4.k.5.1Dose(number)+Dose(unit) (B.4.k.5.2)+Number Of Separate Dosages (B.4.k.5.3)+Interval (B.4.k.5.4)+/Interval Unit (B.4.k.5.5)+ Dosage Text  (B.4.k.6)
            //16
            $this->set('route', $this->getCiomsRouteValue($caseId,192));//B.4.k.8    drugadministrationroute
            //17
            $this->set('indication', $this->getCiomsIndicationValue($caseId,197,299));//B.4.k.11b   drugindication
            //18
            $this->set('therapy', $this->getCiomsTherapyValue($caseId,199,205));//B.4.k.12b   drugstartdate
            //19
            $this->set('duration', $this->getCiomsDurationValue($caseId,206,207));//B.4.k.15a  drugtreatmentduration
            //20.
            $this->getCiomsDechallengeValue($caseId,381,'1,1');//dechallenge
            //21.
            $this->getCiomsRechallengeValue($caseId,209,'1,1');//Rechallenge
            //22. concomitant drugs and dates of administration
            $this->set('concomitanttitle','Drug    |    Therapy Start and Stop Date    |    Dose    |    Indication<br>');
            $this->set('concomitantProducts', $this->getCiomsConcomitantValue($caseId,176,177,178,199,205,183,184,197));//B.4.k.2+B.4.k.12+B.4.k.14+dose(unit)+indication
            //23.other relevant history
            $this->set('relevanttitle','Disease    |    Start date    |   Continuing   |    End date    |    Comments<br>');
            $this->set('relevant', $this->getCiomsRelevantValue($caseId,239,99,100,102,103));
            //24a
            $this->set('caseSource', $this->getDirectValue($caseId,19));//A.1.11.1  Source of the case identifier
            //24b
            $this->set('otherCaseIndentifier', $this->getDirectValue($caseId,20));//A.1.11 Other case identifiers in previous transmissions
            //24c
            $this->set('receiptDate', $this->getDateValue($caseId,12));//A.1.7b  Latest received date
            //24d
            $this->getCiomsReportSourceValue($caseId);
            //25a.
            $this->getCiomsReportTypeValue($caseId,558);//report type

        }

        // Call the previous function by $caseId,$field_id and $set_num
        public function genCIOMSDRAFT($caseId) {
            $this->viewBuilder()->layout('CIOMS_DRAFT');
            //MFR number
            $sdCases = TableRegistry::get('sdCases');
            $name=$sdCases->find()
                    ->select(['caseNo'])
                    ->where(['id='.$caseId,'status=1'])->first();
            $fileName=$name['caseNo'];
            $this->set('fileName', $fileName);
            //1.
            $this->set('patientInitial', $this->getDirectValue($caseId,79));//B.1.1  patientinitial
            //1a.
            $this->set('country', $this->getLookupValue($caseId,3));// A.1.2 occurcountry
            //2.
            $this->set('birth', $this->getDirectValue($caseId,85));// A.1.2.1b patientbirthdate
            $this->set('birthMonth', $this->getMonthValue($this->getDirectValue($caseId,85)));// A.1.2.1b patientbirthdate
            //2a.
            $this->set('age', $this->getDirectValue($caseId,86));//B.1.2.2a patientonsetage
            $this->set('ageUnit',$this->getLookupValue($caseId,87));//B.1.2.2b  patientonsetageunit
            //3
            $this->set('sex',$this->getLookupValue($caseId,93));//B.1.5  sex
            //4-6
            $this->set('reaction', $this->getDirectValue($caseId,156));//B.2.i.4b  reactionstartdate
            $this->set('reactionMonth', $this->getMonthValue($this->getDirectValue($caseId,156)));//B.2.i.4b  reactionstartdate
            //7+13
            $this->set('describe', $this->getDescribeValue($caseId,149,392,394,165,208,218,219,174,222,310,169));//B.2.i.0  primarysourcereaction
            //8-12
            $this->getCiomsSeriousValue($caseId,354);
            //14
            $this->set('suspecttitle','     Drug     |     Batch/Lot Number<br>');
            $this->set('suspectProducts', $this->getCiomsSuspectValue($caseId,176,177,178,179));//B.4.K.2.1 Proprietary Medicinal Product Name
            //15  dailyDose
            $this->set('dailyDose', $this->getCiomsDailyDoseValue($caseId,183,184,185,186,187));//B.4.k.5.1Dose(number)+Dose(unit) (B.4.k.5.2)+Number Of Separate Dosages (B.4.k.5.3)+Interval (B.4.k.5.4)+/Interval Unit (B.4.k.5.5)+ Dosage Text  (B.4.k.6)
            //16
            $this->set('route', $this->getCiomsRouteValue($caseId,192));//B.4.k.8    drugadministrationroute
            //17
            $this->set('indication', $this->getCiomsIndicationValue($caseId,197,299));//B.4.k.11b   drugindication
            //18
            $this->set('therapy', $this->getCiomsTherapyValue($caseId,199,205));//B.4.k.12b   drugstartdate
            //19
            $this->set('duration', $this->getCiomsDurationValue($caseId,206,207));//B.4.k.15a  drugtreatmentduration
            //20.
            $this->getCiomsDechallengeValue($caseId,381,'1,1');//dechallenge
            //21.
            $this->getCiomsRechallengeValue($caseId,209,'1,1');//Rechallenge
            //22. concomitant drugs and dates of administration
            $this->set('concomitanttitle','Drug    |    Therapy Start and Stop Date    |    Dose    |    Indication<br>');
            $this->set('concomitantProducts', $this->getCiomsConcomitantValue($caseId,176,177,178,199,205,183,184,197));//B.4.k.2+B.4.k.12+B.4.k.14+dose(unit)+indication
            //23.other relevant history
            $this->set('relevanttitle','Disease    |    Start date    |   Continuing   |    End date    |    Comments<br>');
            $this->set('relevant', $this->getCiomsRelevantValue($caseId,239,99,100,102,103));
            //24a
            $this->set('caseSource', $this->getDirectValue($caseId,19));//A.1.11.1  Source of the case identifier
            //24b
            $this->set('otherCaseIndentifier', $this->getDirectValue($caseId,20));//A.1.11 Other case identifiers in previous transmissions
            //24c
            $this->set('receiptDate', $this->getDateValue($caseId,12));//A.1.7b  Latest received date
            //24d
            $this->getCiomsReportSourceValue($caseId);
            //25a.
            $this->getCiomsReportTypeValue($caseId,558);//report type

        }

        // Call the previous function by $caseId,$field_id and $set_num
        public function genCIOMSCN($caseId) {
            $this->viewBuilder()->layout('CIOMS_CN');
            //MFR number
            $sdCases = TableRegistry::get('sdCases');
            $name=$sdCases->find()
                    ->select(['caseNo'])
                    ->where(['id='.$caseId,'status=1'])->first();
            $fileName=$name['caseNo'];
            $this->set('fileName', $fileName);
          //1.
          $this->set('patientInitial', $this->getDirectValue($caseId,79));//D.1  patientinitial
          //1a.
          $this->set('country', $this->getLookupValueThree($caseId,1026));// E.i.9 occurcountry
          //2.
          $this->set('birth', $this->getDirectValue($caseId,85));// D.2.1 Date of Birth
          $this->set('birthMonth', $this->getMonthValue($this->getDirectValue($caseId,85)));// D.2.1 Date of Birth
          //2a.
          $this->set('age', $this->getDirectValue($caseId,86));//D.2.2a Age at Time of Onset of Reaction / Event
          $this->set('ageUnit',$this->getLookupValueThree($caseId,87));//D.2.2b Age at Time of Onset of Reaction / Event Unit
          //3
          $this->set('sex',$this->getLookupValueThree($caseId,93));//D.5 Sex
          //4-6
          $this->set('reaction', $this->getDirectValue($caseId,156));//E.i.4  Date of Start of Reaction / Event
          $this->set('reactionMonth', $this->getMonthValue($this->getDirectValue($caseId,156)));//E.i.4  Date of Start of Reaction / Event
          //7+13
          $this->set('describe', $this->getDescribeValueCN($caseId,149,392,394,165,208,218,219,174,222,310,169));//E.i.1Reaction / Event+E.i.7 Outcome of Reaction / Event+G.k.8 Action(s) Taken with Drug + Result Unstructured Data (F.r.3.4)
          //8-12  E.i.3.2Seriousness Criteria at Event Level
          $this->getCiomsSeriousValueThree($caseId);
          //14
          $this->set('suspectProducts', $this->getCiomsSuspectValue($caseId,176,177,178,179));//G.k.2Drug Identification+G.k.4.r.7 Batch / Lot Number
          //15  dailyDose
          $this->set('dailyDose', $this->getCiomsDailyDoseValueThree($caseId,183,184,185,186,187,190));//G.k.4.r.8
          //16
          $this->set('route', $this->getCiomsRouteValueThree($caseId,192));//G.k.4.r.10 Route of Administration
          //17
          $this->set('indication', $this->getCiomsIndicationValue($caseId,197,299));//G.k.7 Indication for Use in Case
          //18
          $this->set('therapy', $this->getCiomsTherapyValue($caseId,199,205));//G.k.4.r.4 Date and Time of Start of Drug
          //19
          $this->set('duration', $this->getCiomsDurationValue($caseId,206,207));//G.k.4.r.6 Duration of Drug Administration
          //20.
          $this->getCiomsDechallengeValue($caseId,209,'1,1');//dechallenge
          //21.
          $this->getCiomsRechallengeValue($caseId,381,'1,1');//Rechallenge
          //22. concomitant drugs and dates of administration
          $this->set('concomitanttitle','用药    |    开始日期/停止日期    |    剂量     |    适应症<br>');
          $this->set('concomitantProducts', $this->getCiomsConcomitantValueThree($caseId,176,177,178,199,205,183,184,197));//G.k.2 +G.k.4.r.4+G.k.4.r.5+dose(unit)+indication
          //23.other relevant history
          $this->set('relevanttitle','D病史    |    开始日期    |   是否持续   |    停止日期    |   评论       |       相关病史及并发疾病的文本说明<br>');
          $this->set('relevant', $this->getCiomsRelevantValueThree($caseId,239,99,100,102,103,104));//D.7.2
          //24a
          $this->set('caseSource', $this->getDirectValue($caseId,19));//C.1.9.1.r.1
          //24b
          $this->set('otherCaseIndentifier', $this->getDirectValue($caseId,20));//C.1.9 Other Case Identifiers
          //24c
          $this->set('receiptDate', $this->getDateValue($caseId,12));//C.1.5Date of Most Recent Information for This Report
          //24d
          $this->getCiomsReportSourceValue($caseId);//C.1.3Type of Report+C.4.r.1Literature Reference(s)+ C.2.r.4 Qualification
          //25a.
          $this->getCiomsReportTypeValue($caseId,1062);//report type

        }

        public function genCIOMSThree($caseId) {
            $this->viewBuilder()->layout('CIOMS_R3');
            //MFR number
            $sdCases = TableRegistry::get('sdCases');
            $name=$sdCases->find()
                    ->select(['caseNo'])
                    ->where(['id='.$caseId,'status=1'])->first();
            $fileName=$name['caseNo'];
            $this->set('fileName', $fileName);
            //1.
            $this->set('patientInitial', $this->getDirectValue($caseId,79));//D.1  patientinitial
            //1a.
            $this->set('country', $this->getLookupValueThree($caseId,1026));// E.i.9 occurcountry
            //2.
            $this->set('birth', $this->getDirectValue($caseId,85));// D.2.1 Date of Birth
            $this->set('birthMonth', $this->getMonthValue($this->getDirectValue($caseId,85)));// D.2.1 Date of Birth
            //2a.
            $this->set('age', $this->getDirectValue($caseId,86));//D.2.2a Age at Time of Onset of Reaction / Event
            $this->set('ageUnit',$this->getLookupValueThree($caseId,87));//D.2.2b Age at Time of Onset of Reaction / Event Unit
            //3
            $this->set('sex',$this->getLookupValueThree($caseId,93));//D.5 Sex
            //4-6
            $this->set('reaction', $this->getDirectValue($caseId,156));//E.i.4  Date of Start of Reaction / Event
            $this->set('reactionMonth', $this->getMonthValue($this->getDirectValue($caseId,156)));//E.i.4  Date of Start of Reaction / Event
            //7+13
            $this->set('describe', $this->getDescribeValueThree($caseId,149,392,394,165,208,218,219,174,222,310,169));//E.i.1Reaction / Event+E.i.7 Outcome of Reaction / Event+G.k.8 Action(s) Taken with Drug + Result Unstructured Data (F.r.3.4)
            //8-12  E.i.3.2Seriousness Criteria at Event Level
            $this->getCiomsSeriousValueThree($caseId);
            //14
            $this->set('suspecttitle','     Drug     |     Batch/Lot Number<br>');
            $this->set('suspectProducts', $this->getCiomsSuspectValue($caseId,176,177,178,179));//G.k.2Drug Identification+G.k.4.r.7 Batch / Lot Number
            //15  dailyDose
            $this->set('dailyDose', $this->getCiomsDailyDoseValueThree($caseId,183,184,185,186,187,190));//G.k.4.r.8
            //16
            $this->set('route', $this->getCiomsRouteValueThree($caseId,192));//G.k.4.r.10 Route of Administration
            //17
            $this->set('indication', $this->getCiomsIndicationValue($caseId,197,299));//G.k.7 Indication for Use in Case
            //18
            $this->set('therapy', $this->getCiomsTherapyValue($caseId,199,205));//G.k.4.r.4 Date and Time of Start of Drug
            //19
            $this->set('duration', $this->getCiomsDurationValue($caseId,206,207));//G.k.4.r.6 Duration of Drug Administration
            //20.
            $this->getCiomsDechallengeValue($caseId,209,'1,1');//dechallenge
            //21.
            $this->getCiomsRechallengeValue($caseId,381,'1,1');//Rechallenge
            //22. concomitant drugs and dates of administration
            $this->set('concomitanttitle','Drug    |    Therapy Start and Stop Date    |    Dose    |    Indication<br>');
            $this->set('concomitantProducts', $this->getCiomsConcomitantValueThree($caseId,176,177,178,199,205,183,184,197));//G.k.2 +G.k.4.r.4+G.k.4.r.5+dose(unit)+indication
            //23.other relevant history
            $this->set('relevanttitle','Disease    |    Start date    |   Continuing   |    End date    |    Comments       |       Text for Relevant Medical History and Concurrent Conditions<br>');
            $this->set('relevant', $this->getCiomsRelevantValueThree($caseId,239,99,100,102,103,104));//D.7.2
            //24a
            $this->set('caseSource', $this->getDirectValue($caseId,19));//C.1.9.1.r.1
            //24b
            $this->set('otherCaseIndentifier', $this->getDirectValue($caseId,20));//C.1.9 Other Case Identifiers
            //24c
            $this->set('receiptDate', $this->getDateValue($caseId,12));//C.1.5Date of Most Recent Information for This Report
            //24d
            $this->getCiomsReportSourceValue($caseId);//C.1.3Type of Report+C.4.r.1Literature Reference(s)+ C.2.r.4 Qualification
            //25a.
            $this->getCiomsReportTypeValue($caseId,1062);//report type
        }

        public function genCIOMSThreeDRAFT($caseId) {
            $this->viewBuilder()->layout('CIOMS_R3_DRAFT');
            //MFR number
            $sdCases = TableRegistry::get('sdCases');
            $name=$sdCases->find()
                    ->select(['caseNo'])
                    ->where(['id='.$caseId,'status=1'])->first();
            $fileName=$name['caseNo'];
            $this->set('fileName', $fileName);
            //1.
            $this->set('patientInitial', $this->getDirectValue($caseId,79));//D.1  patientinitial
            //1a.
            $this->set('country', $this->getLookupValueThree($caseId,1026));// E.i.9 occurcountry
            //2.
            $this->set('birth', $this->getDirectValue($caseId,85));// D.2.1 Date of Birth
            $this->set('birthMonth', $this->getMonthValue($this->getDirectValue($caseId,85)));// D.2.1 Date of Birth
            //2a.
            $this->set('age', $this->getDirectValue($caseId,86));//D.2.2a Age at Time of Onset of Reaction / Event
            $this->set('ageUnit',$this->getLookupValueThree($caseId,87));//D.2.2b Age at Time of Onset of Reaction / Event Unit
            //3
            $this->set('sex',$this->getLookupValueThree($caseId,93));//D.5 Sex
            //4-6
            $this->set('reaction', $this->getDirectValue($caseId,156));//E.i.4  Date of Start of Reaction / Event
            $this->set('reactionMonth', $this->getMonthValue($this->getDirectValue($caseId,156)));//E.i.4  Date of Start of Reaction / Event
            //7+13
            $this->set('describe', $this->getDescribeValueThree($caseId,149,392,394,165,208,218,219,174,222,310,169));//E.i.1Reaction / Event+E.i.7 Outcome of Reaction / Event+G.k.8 Action(s) Taken with Drug + Result Unstructured Data (F.r.3.4)
            //8-12  E.i.3.2Seriousness Criteria at Event Level
            $this->getCiomsSeriousValueThree($caseId);
            //14
            $this->set('suspecttitle','     Drug     |     Batch/Lot Number<br>');
            $this->set('suspectProducts', $this->getCiomsSuspectValue($caseId,176,177,178,179));//G.k.2Drug Identification+G.k.4.r.7 Batch / Lot Number
            //15  dailyDose
            $this->set('dailyDose', $this->getCiomsDailyDoseValueThree($caseId,183,184,185,186,187,190));//G.k.4.r.8
            //16
            $this->set('route', $this->getCiomsRouteValueThree($caseId,192));//G.k.4.r.10 Route of Administration
            //17
            $this->set('indication', $this->getCiomsIndicationValue($caseId,197,299));//G.k.7 Indication for Use in Case
            //18
            $this->set('therapy', $this->getCiomsTherapyValue($caseId,199,205));//G.k.4.r.4 Date and Time of Start of Drug
            //19
            $this->set('duration', $this->getCiomsDurationValue($caseId,206,207));//G.k.4.r.6 Duration of Drug Administration
            //20.
            $this->getCiomsDechallengeValue($caseId,209,'1,1');//dechallenge
            //21.
            $this->getCiomsRechallengeValue($caseId,381,'1,1');//Rechallenge
            //22. concomitant drugs and dates of administration
            $this->set('concomitanttitle','Drug    |    Therapy Start and Stop Date    |    Dose    |    Indication<br>');
            $this->set('concomitantProducts', $this->getCiomsConcomitantValueThree($caseId,176,177,178,199,205,183,184,197));//G.k.2 +G.k.4.r.4+G.k.4.r.5+dose(unit)+indication
            //23.other relevant history
            $this->set('relevanttitle','Disease    |    Start date    |   Continuing   |    End date    |    Comments       |       Text for Relevant Medical History and Concurrent Conditions<br>');
            $this->set('relevant', $this->getCiomsRelevantValueThree($caseId,239,99,100,102,103,104));//D.7.2
            //24a
            $this->set('caseSource', $this->getDirectValue($caseId,19));//C.1.9.1.r.1
            //24b
            $this->set('otherCaseIndentifier', $this->getDirectValue($caseId,20));//C.1.9 Other Case Identifiers
            //24c
            $this->set('receiptDate', $this->getDateValue($caseId,12));//C.1.5Date of Most Recent Information for This Report
            //24d
            $this->getCiomsReportSourceValue($caseId);//C.1.3Type of Report+C.4.r.1Literature Reference(s)+ C.2.r.4 Qualification
            //25a.
            $this->getCiomsReportTypeValue($caseId,1062);//report type
        }

            /**
            *  Generate PDF files
            *
            */
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
                $sdMedwatchPositions = TableRegistry::get('sdMedwatchPositions');
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
                        ->where(['sd_field_id='.$field_id,$more_conditions,'substr(sdMedwatchPositions.field_name,-1)='.$option])
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
                        ->where(['sd_field_id='.$field_id,$more_conditions,'substr(sdMedwatchPositions.field_name,-1)="y"'])
                        ->first();
                        $text = $text." <style> p {position: absolute;}  </style>";
                        $text=$text.'<p style="top: '.$positions['position_top'].'px; left: '.$positions['position_left']
                                    .'px; width: '.$positions['position_width'].'px;  height: '.$positions['position_height'].'px; color:black;">'.$MedValue.'</p>';
                        break;
                    case '5'://month filled
                        $positions=$sdMedwatchPositions->find()
                        ->select(['position_top','position_left','position_width','position_height'])
                        ->where(['sd_field_id='.$field_id,$more_conditions,'substr(sdMedwatchPositions.field_name,-1)="h"'])
                        ->first();
                        $text = $text." <style> p {position: absolute;}  </style>";
                        $text=$text.'<p style="top: '.$positions['position_top'].'px; left: '.$positions['position_left']
                                    .'px; width: '.$positions['position_width'].'px;  height: '.$positions['position_height'].'px; color:black;">'.$MedValue.'</p>';
                        break;
                    case '6'://year filled
                        $positions=$sdMedwatchPositions->find()
                        ->select(['position_top','position_left','position_width','position_height'])
                        ->where(['sd_field_id='.$field_id,$more_conditions,'substr(sdMedwatchPositions.field_name,-1)="r"'])
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
                                ->where(['sd_field_id='.$field_id,$more_conditions,'substr(sdMedwatchPositions.field_name,-1)='.$option])
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
                            ->where(['sd_field_id='.$field_id,$more_conditions,'substr(sdMedwatchPositions.field_name,-1)='.$checked[1],'value_type=6'])
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
                            ->where(['sd_field_id='.$field_id,$more_conditions,'substr(sdMedwatchPositions.field_name,-1)='.$checked[0],'value_type=7'])
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
                $sdMedwatchPositions = TableRegistry::get('sdMedwatchPositions');
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
                $sdMedwatchPositions = TableRegistry::get('sdMedwatchPositions');
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
                    ->where(['sd_case_id='.$caseId,'sd_field_id=168','status=1']);
                $labSet=array();
                foreach($lab as $lab_details){
                    $labSet[]=$lab_details['set_number'];
                }
                $length=count($labSet);
                $testdata="";
                for($i=0;$i<$length;$i++){
                    $setNumber=$labSet[$i];
                    $query1=$this->getDirectValue($caseId,168,$setNumber);
                    if($query1!=null){
                        $query1="Test Name:".$query1."/";
                    }
                    $query2=$this->getDateValue($caseId,167,$setNumber);
                    if($query2!=null){
                        $query2="Test Date:".$query2."/";
                    }
                    $query3=$this->getDirectValue($caseId,169,$setNumber);
                    if($query3!=null){
                        $query3="Test Result:".$query3." ";
                    }

                    $query4=$this->getLookupValue($caseId,170,$setNumber);
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
                $labdata =$this->getDirectValue($caseId,174,1)."<br>".$testdata;
                $labDataOne=substr($labdata,0,300);
                $labPositionOne=$this->getPosition($caseId,174,1,$labDataOne);
                return $labPositionOne;
            }
            //b6 in page three
            public function labDataThree($caseId){
                $sdFieldValues = TableRegistry::get('sdFieldValues');
                $lab= $sdFieldValues->find()
                    ->select(['set_number'])
                    ->where(['sd_case_id='.$caseId,'sd_field_id=168','status=1']);
                $labSet=array();
                foreach($lab as $lab_details){
                    $labSet[]=$lab_details['set_number'];
                }
                $length=count($labSet);
                $testdata="";
                for($i=0;$i<$length;$i++){
                    $setNumber=$labSet[$i];
                    $query1=$this->getDirectValue($caseId,168,$setNumber);
                    if($query1!=null){
                        $query1="Test Name:".$query1."/";
                    }
                    $query2=$this->getDateValue($caseId,167,$setNumber);
                    if($query2!=null){
                        $query2="Test Date:".$query2."/";
                    }
                    $query3=$this->getDirectValue($caseId,169,$setNumber);
                    if($query3!=null){
                        $query3="Test Result:".$query3." ";
                    }

                    $query4=$this->getLookupValue($caseId,170,$setNumber);
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
                $labdata =$this->getDirectValue($caseId,174,1)."<br>".$testdata;
                $labDataThree=substr($labdata,300);
                $labPositionThree=$this->getPosition($caseId,174,2,$labDataThree);
                return $labPositionThree;
            }

            //c2 concomitanat medical products and therapy dates in page one
            public function concomitantTherapyOne($caseId){
                $concomitantProducts=$this->getCiomsConcomitantValue($caseId,176,177,178,199,205,183,184,197);
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
                $concomitantProducts=$this->getCiomsConcomitantValue($caseId,176,177,178,199,205,183,184,197);
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



            public function genFDApdf($caseId)
            {

                //a1 patientID field
               $result=$this->getMedPosition($caseId,79,1,1);
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
                $result=$result.$this->getMedPosition($caseId,235,1,2);
                //a5b race
                $result=$result.$this->getMedPosition($caseId,549,1,2);
                //b1 adverse event
                $result=$result.$this->getMedPosition($caseId,224,1,2);
                // b2 serious
                $result=$result.$this->getMedPosition($caseId,354,1,7);
                //b2 death date
                $result=$result.$this->getMedPosition($caseId,115,4,4);//day
                $result=$result.$this->getMedPosition($caseId,115,5,5);//month
                $result=$result.$this->getMedPosition($caseId,115,6,6);//year
                // b3 date of event
                $result=$result.$this->getMedPosition($caseId,156,4,4);//day
                $result=$result.$this->getMedPosition($caseId,156,5,5);//month
                $result=$result.$this->getMedPosition($caseId,156,6,6);//year
                //b4
                $result=$result.$this->CurrentTime();
                // b5 describe event or problem
                $describeOne=substr($this->getDescribeEventValue($caseId,149,392,394,218,219,222,310),0,600);
                $result=$result.$this->getPosition($caseId,218,1,$describeOne);
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
                    $result=$result.$this->getMedPosition($caseId,176,1,1,$suspect[0]);
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
                    $result=$result.$this->getMedPosition($caseId,192,2,1,$suspect[0]);
                    //c4#1 start day
                    $result=$result.$this->getMedPosition($caseId,199,3,3,$suspect[0]);
                    //c4#1 stop day
                    $result=$result.$this->getMedPosition($caseId,205,3,3,$suspect[0]);
                    //c5#1 diagnosis for use
                    $result=$result.$this->getMedPosition($caseId,197,1,1,$suspect[0]);
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
                    $result=$result.$this->getMedPosition($caseId,209,7,2,$suspect[0]);
                }

                if(!is_null($suspect[1])){
                // c1#2 name and strength
                    $result=$result.$this->getMedPosition($caseId,176,1,1,$suspect[1]);
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
                    $result=$result.$this->getMedPosition($caseId,192,2,1,$suspect[1]);
                    //c4#2 start day
                    $result=$result.$this->getMedPosition($caseId,199,3,3,$suspect[1]);
                    //c4#2 stop day
                    $result=$result.$this->getMedPosition($caseId,205,3,3,$suspect[1]);
                    //c5#2 diagnosis for use
                    $result=$result.$this->getMedPosition($caseId,197,1,1,$suspect[1]);
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
                    $result=$result.$this->getMedPosition($caseId,209,7,2,$suspect[1]);
                }
                // e1 last name
                $result=$result.$this->getMedPosition($caseId,28,1,1);
                //e1 first name
                $result=$result.$this->getMedPosition($caseId,26,1,1);
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
                $describeThree=substr($this->getDescribeEventValue($caseId,149,392,394,218,219,222,310),600);//b5 describe event continue
                $continue=$this->getPosition($caseId,218,2,$describeThree);
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


            public function genFDApdfDRAFT($caseId)
            {

                //a1 patientID field
               $result=$this->getMedPosition($caseId,79,1,1);
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
                $result=$result.$this->getMedPosition($caseId,235,1,2);
                //a5b race
                $result=$result.$this->getMedPosition($caseId,549,1,2);
                //b1 adverse event
                $result=$result.$this->getMedPosition($caseId,224,1,2);
                // b2 serious
                $result=$result.$this->getMedPosition($caseId,354,1,7);
                //b2 death date
                $result=$result.$this->getMedPosition($caseId,115,4,4);//day
                $result=$result.$this->getMedPosition($caseId,115,5,5);//month
                $result=$result.$this->getMedPosition($caseId,115,6,6);//year
                // b3 date of event
                $result=$result.$this->getMedPosition($caseId,156,4,4);//day
                $result=$result.$this->getMedPosition($caseId,156,5,5);//month
                $result=$result.$this->getMedPosition($caseId,156,6,6);//year
                //b4
                $result=$result.$this->CurrentTime();
                // b5 describe event or problem
                $describeOne=substr($this->getDescribeEventValue($caseId,149,392,394,218,219,222,310),0,600);
                $result=$result.$this->getPosition($caseId,218,1,$describeOne);
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
                    $result=$result.$this->getMedPosition($caseId,176,1,1,$suspect[0]);
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
                    $result=$result.$this->getMedPosition($caseId,192,2,1,$suspect[0]);
                    //c4#1 start day
                    $result=$result.$this->getMedPosition($caseId,199,3,3,$suspect[0]);
                    //c4#1 stop day
                    $result=$result.$this->getMedPosition($caseId,205,3,3,$suspect[0]);
                    //c5#1 diagnosis for use
                    $result=$result.$this->getMedPosition($caseId,197,1,1,$suspect[0]);
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
                    $result=$result.$this->getMedPosition($caseId,209,7,2,$suspect[0]);
                }

                if(!is_null($suspect[1])){
                // c1#2 name and strength
                    $result=$result.$this->getMedPosition($caseId,176,1,1,$suspect[1]);
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
                    $result=$result.$this->getMedPosition($caseId,192,2,1,$suspect[1]);
                    //c4#2 start day
                    $result=$result.$this->getMedPosition($caseId,199,3,3,$suspect[1]);
                    //c4#2 stop day
                    $result=$result.$this->getMedPosition($caseId,205,3,3,$suspect[1]);
                    //c5#2 diagnosis for use
                    $result=$result.$this->getMedPosition($caseId,197,1,1,$suspect[1]);
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
                    $result=$result.$this->getMedPosition($caseId,209,7,2,$suspect[1]);
                }
                // e1 last name
                $result=$result.$this->getMedPosition($caseId,28,1,1);
                //e1 first name
                $result=$result.$this->getMedPosition($caseId,26,1,1);
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
                $describeThree=substr($this->getDescribeEventValue($caseId,149,392,394,218,219,222,310),600);//b5 describe event continue
                $continue=$this->getPosition($caseId,218,2,$describeThree);
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
?>
