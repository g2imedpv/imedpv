<title>Triage</title>
<head>
<?= $this->Html->script('cases/triage.js') ?>
<head>
<script>
    var userId = <?= $this->request->getSession()->read('Auth.User.id')?>;
    var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
    var caseNo = "<?= $caseNo ?>";
    var versionNo = <?= $versionNo?>;
    var dayZero = "<?= $field_value_set['225']['field_value']?>";
</script>
<div class="card text-center w-75 mx-auto my-3">
  <div class="card-header text-center"><h3>Create New Case</h3></div>
  <div class="card-body">
    <div class="alert alert-primary w-50 mx-auto" role="alert"><h4>New Case Number: <?= $caseNo ?></h4></div>
    <hr class="my-3">
    <?= $this->Form->create($caseNo,['id'=>"triageForm"]) ?>
    <!-- Basic Info Fields Set -->

    <?php if($versionNo>1){
        echo "<div id=\"basicInfo\" class=\"form-group\"><h4 class=\"text-left\">Version Info</h4>";
        echo "<div class=\"form-row\">";
        echo "<div class=\"form-group col-md-3\">";
        echo "<label>Reason For Version Up</label>";
        // if($field_value_set['79']['id']!=null)
        //     echo "<input type=\"hidden\" id=\"id_patientField_id_id\" name=\"field_value[79][id]\" value=\"".$field_value_set['79']['id']."\">";
        // echo "<input type=\"text\" class=\"form-control\" id=\"patientField_id\" name=\"field_value[79][value]\" value=\"".$field_value_set['79']['field_value']."\"></div>";
        // echo "</div>";
        echo "<select class=\"custom-select\">";
        echo "<option>Follow Up</option>";
        echo "<option>Data Correction</option>";
        echo "</select></div>";
        echo "<div class=\"form-group col-md-3\">";
        echo "<label>Versioning Comment</label>";
        echo "<textarea class=\"form-control\"></textarea>";
        echo "</div>";
        echo "</div></div>";
    }
    ?>
    </div>
    <div id="basicInfo" class="form-group mx-3">
        <h4 class="text-left">Product</h4>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label>Product Name (B.4.k.2.1)<i class="fas fa-asterisk reqField"></i></label>
                <p><b><?= $field_value_set['176']['field_value'] ?></b><p>
            </div>
        </div>
        <h4 class="text-left mt-3">Patient</h4>
        <div id="patientInfo" class="form-row bg-light">
            <div class="form-group col-md-3">
                <label>Patient ID (B.1.1)</label>
                <?php if($field_value_set['79']['id']!=null)
                echo "<input type=\"hidden\" id=\"id_patientField_id_id\" name=\"field_value[79][id]\" value=\"".$field_value_set['79']['id']."\">";?>
                <input type="text" class="form-control" id="patientField_id" name="field_value[79][value]" value="<?= $field_value_set['79']['field_value']?>">
            </div>
            <div class="form-group col-md-3">
                <label>Sex (B.1.5)</label>
                <?php if($field_value_set['93']['id']!=null)
                echo "<input type=\"hidden\" id=\"id_patientField_sex_id\" name=\"field_value[93][id]\" value=\"".$field_value_set['93']['id']."\">";?>
                <select class="custom-select" id="patientField_sex" name="field_value[93][value]">
                    <option value="">Select Sex</option>
                    <option value="1" <?php echo ($field_value_set['93']['field_value']=='1')?"selected":null?>>Male</option>
                    <option value="2" <?php echo ($field_value_set['93']['field_value']=='2')?"selected":null?>>Female</option>
                    <option value="3" <?php echo ($field_value_set['93']['field_value']=='3')?"selected":null?>>Unknown</option>
                    <option value="4" <?php echo ($field_value_set['93']['field_value']=='4')?"selected":null?>>Not Specified</option>
                </select>
            </div>
        </div>
        <div id="patientInfo" class="form-row bg-light">
            <div class="form-group col-md-3">
                <label>Date of birth (B.1.2.1b)</label>
                <?php if($field_value_set['85']['id']!=null)
                echo "<input type=\"hidden\" id=\"id_patientField_dob_id\" name=\"field_value[85][id]\" value=\"".$field_value_set['85']['id']."\">";?>
                <div class="form-row">
                    <div class="col-sm-4">
                        <select class="custom-select js-example-basic-single" placeholder="Day" id="patientField_dob_day" name="field_value[85][value]">
                        <?php
                        echo "<option value=\"00\">Day</option>";
                        for($i=1;$i<32;$i++){
                            echo "<option value=\"".sprintf("%02d",$i)."\"";
                            if (array_key_exists('85',$field_value_set)&&(substr($field_value_set['85']['field_value'],0,2)==sprintf("%02d",$i))) echo "selected";
                            echo ">".$i."</option>";
                        }
                        ?>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <select class="custom-select js-example-basic-single"  name="field_value[85][value]" placeholder="Month" id="patientField_dob_month">
                        <?php
                        $month_str = ['Jan-1','Feb-2','Mar-3','Apr-4','May-5','Jun-6','Jul-7','Aug-8','Sep-9','Oct-10','Nov-11','Dec-12'];
                        echo "<option value=\"00\">Month</option>";
                        foreach($month_str as $i => $month){
                            echo "<option value=\"".sprintf("%02d",$i+1)."\"";
                            if (array_key_exists('85',$field_value_set)&&(substr($field_value_set['85']['field_value'],2,2)==sprintf("%02d",$i+1))) echo "selected";
                            echo ">".$month."</option>";
                        }
                        ?>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <select class="custom-select js-example-basic-single yearSelect" placeholder="Year" id="patientField_dob_year" name="field_value[85][value]">
                        <?php
                        echo "<option value=\"00\">Day</option>";
                        for($i=1900;$i<=2050;$i++){
                            echo "<option value=\"".sprintf("%04d",$i)."\"";
                            if (array_key_exists('85',$field_value_set)&&(substr($field_value_set['85']['field_value'],4,4)==sprintf("%02d",$i))) echo "selected";
                            echo ">".$i."</option>";
                        }
                        ?>
                            <option value="0000">Year</option>
                        </select>
                    </div>
                    <?php
                    echo "<input id=\"patientField_dob\" name=\"field_value[85][value]\"";
                    if($field_value_set['85']['field_value']!=null) echo "value=\"".$field_value_set['85']['field_value']."\"";
                    echo "type=\"hidden\">";
                    ?>
                </div>
            </div>
            <div class="form-group col-md-3">
                <label>Age at time of onset reaction (B.1.2.2a)</label>
                <?php if($field_value_set['86']['id']!=null)
                echo "<input type=\"hidden\" id=\"id_patientField_age_id\" name=\"field_value[86][id]\" value=\"".$field_value_set['86']['id']."\">";?>
                <a tabindex="0" role="button" data-toggle="popover" data-trigger="focus" title="Field Helper" data-content="Age at time of onset of reaction or event"><i class="qco fas fa-info-circle"></i></a>
                <input type="text" class="form-control" id="patientField_age" name="field_value[86][value]" value="<?= $field_value_set['86']['field_value']?>">
            </div>
            <!-- <div class="form-group col-md-3">
                <label>Age at the time of event (B.1.2.2a)</label>
                <input type="text" class="form-control" id="">
            </div> -->
            <div class="form-group col-md-3">
                <label>Age Unit (B.1.2.2b)</label>
                <?php if($field_value_set['87']['id']!=null)
                echo "<input type=\"hidden\" id=\"id_patientField_sex_id\" name=\"field_value[87][id]\" value=\"".$field_value_set['87']['id']."\">";?>
                <select class="custom-select" name="field_value[87][value]" id="patientField_ageunit">
                    <option value="">Select Age Unit</option>
                    <option value="800" <?php echo ($field_value_set['87']['field_value']=='800')?"selected":null?> >Decade</option>
                    <option value="801" <?php echo ($field_value_set['87']['field_value']=='801')?"selected":null?>>Year</option>
                    <option value="802" <?php echo ($field_value_set['87']['field_value']=='802')?"selected":null?>>Month</option>
                    <option value="803" <?php echo ($field_value_set['87']['field_value']=='803')?"selected":null?>>Week</option>
                    <option value="804" <?php echo ($field_value_set['87']['field_value']=='804')?"selected":null?>>Day</option>
                    <option value="805" <?php echo ($field_value_set['87']['field_value']=='805')?"selected":null?>>Hour</option>
                </select>
            </div>
        </div>
        <h4 class="text-left mt-3">Reporter</h4>
        <div id="reporterInfo" class="form-row">
            <div class="form-group col-md-3">
                <label>First Name (A.2.1.1b)</label>
                <?php if($field_value_set['26']['id']!=null)
                echo "<input type=\"hidden\" id=\"id_reporterField_firstname_id\" name=\"field_value[26][id]\" value=\"".$field_value_set['26']['id']."\">";?>
                <input type="text" class="form-control" name="field_value[26][value]" id="reporterField_firstname" value="<?= $field_value_set['26']['field_value']?>">
            </div>
            <div class="form-group col-md-3">
                <label>Last Name (A.2.1.1d)</label>
                <?php if($field_value_set['28']['id']!=null)
                echo "<input type=\"hidden\" id=\"id_reporterField_lastname_id\" name=\"field_value[28][id]\" value=\"".$field_value_set['28']['id']."\">";?>
                <input type="text" class="form-control" name="field_value[28][value]" id="reporterField_lastname" value="<?= $field_value_set['28']['field_value']?>">
            </div>
        </div>
        <h4 class="text-left mt-3">Event</h4>
        <div class="form-row bg-light">
            <div class="form-group col-md-4">
                <?php if($field_value_set['149']['id']!=null)
                echo "<input type=\"hidden\" id=\"id_eventField_term_id\" name=\"field_value[149][id]\" value=\"".$field_value_set['149']['id']."\">";?>
                <label>Reported term (B.2.i.0) <i class="fas fa-asterisk reqField"></i></label>
                <input type="text" class="form-control" name="field_value[149][value]" id="eventField_term" value="<?= $field_value_set['149']['field_value'] ?>">
            </div>
            <div class="form-group col-md-2">
                <label>MedDra Browser</label>
                <div>
                    <?php
                    $meddraCell = $this->cell('Meddra');
                    echo $meddraCell;?>
                </div>
            </div>
        </div>
        <div class="form-row bg-light">
            <div class="form-group col-md-3">
                <label>LLT Name</label>
                <?php if($field_value_set['394']['id']!=null)
                echo "<input type=\"hidden\" id=\"id_eventField_meddralltname_id\" name=\"field_value[394][id]\" value=\"".$field_value_set['394']['id']."\">";?>
                <input type="text" class="form-control" name="field_value[394][value]" id="eventField_meddralltname" value="<?= $field_value_set['394']['field_value'] ?>">
            </div>
            <div class="form-group col-md-3">
                <label>PT Name</label>
                <?php if($field_value_set['392']['id']!=null)
                echo "<input type=\"hidden\" id=\"id_eventField_meddraptname_id\" name=\"field_value[392][id]\" value=\"".$field_value_set['393']['id']."\">";?>
                <input type="text" class="form-control" name="field_value[392][value]" id="eventField_meddraptname" value="<?= $field_value_set['392']['field_value'] ?>">
            </div>
            <div class="form-group col-md-3">
                <label>HLT Name</label>
                <?php if($field_value_set['395']['id']!=null)
                echo "<input type=\"hidden\" id=\"id_eventField_meddrahltname_id\" name=\"field_value[395][id]\" value=\"".$field_value_set['395']['id']."\">";?>
                <input type="text" class="form-control" name="field_value[395][value]" id="eventField_meddrahltname" value="<?= $field_value_set['395']['field_value'] ?>">
            </div>
        </div>

        <!-- Attachment -->
        <h4 class="text-left mt-3">Attach Documents </h4>
        <div class="form-row">
            <div class="form-group col-md-4">
                <!-- <input type="file" class="form-control-file" id=""> -->
            </div>
        </div>
        <?php if($field_value_set['223']['id']!=null)
                echo "<input type=\"hidden\" id=\"id_validcase\" name=\"field_value[223][id]\" value=\"".$field_value_set['223']['id']."\">";?>
        <?php echo "<input type=\"hidden\" id=\"validcase\" name=\"field_value[223][value]\" value=\"".$field_value_set['223']['field_value']."\">";?>
        <button type="button" id="confirmElements" class="btn btn-primary m-auto w-25">Countinue</button>
        <button type="button" onclick="savenexit()" id="savenexitbtn" class="btn btn-outline-info m-auto w-25">Save And Exit</button>
    </div>

    <hr class="my-2">

    <!-- If invalid then choose YES, Select Reasons -->
    <div id="selRea" class="card w-50 mx-auto my-3" style="display:none;">
        <div class="card-header text-center"><h5>Please Select Reasons For Continuing</h5></div>
        <div class="card-body" id="selectReasonContent">
        <?php if($field_value_set['417']['id']!=null)
                echo "<input type=\"hidden\" id=\"reason_id\" name=\"field_value[417][id]\" disabled value=\"".$field_value_set['417']['id']."\">";?>
        <?php echo "<input type=\"hidden\" id=\"reason_value\" name=\"field_value[417][value]\" disabled value=\"".$field_value_set['417']['field_value']."\">";?>
            <div class="mx-auto w-50">
                <div class="form-check my-2 text-left">
                    <input class="form-check-input" type="checkbox" value="1" id="reason-1" disabled="true" <?php if(substr($field_value_set['417']['field_value'],0,1)==1) echo "checked"; ?>>
                    <label class="form-check-label" for="reason-1">Reporter is Reliable </label>
                </div>
                <div class="form-check my-2 text-left">
                    <input class="form-check-input" type="checkbox" value="1" id="reason-2" disabled="true"<?php if(substr($field_value_set['417']['field_value'],1,1)==1) echo "checked"; ?> >
                    <label class="form-check-label" for="reason-2">Important Event </label>
                </div>
                <div class="form-check my-2 text-left">
                    <input class="form-check-input" type="checkbox" value="1" id="reason-3" disabled="true" <?php if(substr($field_value_set['417']['field_value'],2,1)==1)  echo "checked"; ?> >
                    <label class="form-check-label" for="reason-3">Others </label>
                    <?php if($field_value_set['420']['id']!=null)
                    echo "<input type=\"hidden\" id=\"id_otherReason_id\" disabled name=\"field_value[420][id]\" value=\"".$field_value_set['420']['id']."\">";?>
                    <textarea class="form-control" id="otherReason" rows="3" <?php if(substr($field_value_set['417']['field_value'],-1)!=1) echo "style=\"display:none;\" disabled"; ?> name="field_value[420][value]">
                    <?= $field_value_set['420']['field_value'] ?></textarea>
                </div>
            </div>
            <button type="button" id="selReaBack" class="btn btn-outline-warning my-2 mx-2 w-25">Back</button>
            <button type="button" id="confirmRea" class="btn btn-primary my-2 mx-2 w-25">Confirm</button>
        </div>
    </div>

    <!-- If Valid then choose NO, Prioritize -->
    <div id="prioritize" class="card mx-auto my-3 w-50" style="display:none;">
        <div class="card-header text-center"><h5>Prioritize</h5></div>
        <div class="card-body" id="prioritizeContent">
            <div class="row my-2">
                <legend class="col-form-label col-sm-4 pt-0 text-right">Seriousness</legend>
                <?php if($field_value_set['421']['id']!=null)
                    echo "<input type=\"hidden\" id=\"id_seriousness_id\" disabled name=\"field_value[421][id]\" value=\"".$field_value_set['421']['id']."\">";?>
                <div class="col-sm-8 text-left">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="prioritize-seriousness-1" value="1" name="field_value[421][value]" <?php if($field_value_set['421']['field_value']==1) echo "checked"; ?> disabled>
                        <label class="form-check-label" for="prioritize-seriousness-1">Fatal / Life Threatening</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="prioritize-seriousness-2" value="2" name="field_value[421][value]" <?php if($field_value_set['421']['field_value']==2) echo "checked"; ?> disabled>
                        <label class="form-check-label" for="prioritize-seriousness-2">Other Serious</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="prioritize-seriousness-3" value="3" name="field_value[421][value]" <?php if($field_value_set['421']['field_value']==3) echo "checked"; ?> disabled>
                        <label class="form-check-label" for="prioritize-seriousness-3">Serious / Spontaneous</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="prioritize-seriousness-4" value="4" name="field_value[421][value]" <?php if($field_value_set['421']['field_value']==4) echo "checked"; ?> disabled>
                        <label class="form-check-label" for="prioritize-seriousness-4">Non Serious</label>
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <legend class="col-form-label col-sm-4 pt-0 text-right">Related</legend>
                <?php if($field_value_set['422']['id']!=null)
                    echo "<input type=\"hidden\" id=\"id_related_id\" disabled name=\"field_value[422][id]\" value=\"".$field_value_set['422']['id']."\">";?>
                <div class="col-sm-8 text-left">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="prioritize-related-1" value="1" name="field_value[422][value]" <?php if($field_value_set['422']['field_value']==1) echo "checked"; ?> disabled>
                        <label class="form-check-label" for="prioritize-related-1">Yes</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="prioritize-related-2" value="2" name="field_value[422][value]" <?php if($field_value_set['422']['field_value']==2) echo "checked"; ?> disabled>
                        <label class="form-check-label" for="prioritize-related-2">No</label>
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <legend class="col-form-label col-sm-4 pt-0 text-right">Unlabelled</legend>
                <?php if($field_value_set['423']['id']!=null)
                    echo "<input type=\"hidden\" disabled id=\"id_unlabelled_id\" name=\"field_value[423][id]\" value=\"".$field_value_set['423']['id']."\">";?>
                <div class="col-sm-8 text-left">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="prioritize-unlabelled-1" value="1" name="field_value[423][value]" <?php if($field_value_set['423']['field_value']==1) echo "checked"; ?> disabled>
                        <label class="form-check-label" for="prioritize-unlabelled-1">Yes</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="prioritize-unlabelled-2" value="2" name="field_value[423][value]" <?php if($field_value_set['423']['field_value']==2) echo "checked"; ?> disabled>
                        <label class="form-check-label" for="prioritize-unlabelled-2">No</label>
                    </div>
                </div>
            </div>
            <?php if($field_value_set['415']['id']!=null)
                echo "<input type=\"hidden\" id=\"submissionDate_id\" name=\"field_value[415][id]\" disabled value=\"".$field_value_set['415']['id']."\">";?>
            <?php echo "<input type=\"hidden\" id=\"submissionDate_value\" name=\"field_value[415][value]\" disabled value=\"".$field_value_set['415']['field_value']."\">";?>
            <?= $this->Form->end();?>
            <div id="prioritizeType"></div>
            <button type="button" id="prioritizeBack" class="btn btn-outline-warning my-2 mx-2 w-25">Back</button>
            <a class="btn btn-light text-success mx-1" title="Sign Off" role="button" data-toggle="modal" data-target=".signOff" onclick="endTriage()"><i class="fas fa-share-square"></i>End Triage</a>
        </div>
    </div>
    <div class="modal fade signOff" tabindex="-1" role="dialog" aria-labelledby="signOff" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div id="action-text-hint"></div>
            </div>
        </div>
    </div>
  </div>
</div>
