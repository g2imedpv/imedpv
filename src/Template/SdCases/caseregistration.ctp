<title><?php echo __("Case Registration")?></title>
<head>
<?= $this->Html->script('cases/duplicate_detection.js') ?>
<head>
<?php
// debug($productInfo);
// foreach ($productInfo as $k){  debug($k->id);  }
?>


<div class="container">

    <div class="row my-3">
        <div class="col">
            <div class="card">
                <div class="card-header text-center">
                    <h3><?php echo __("Case Registration / Duplicate Detection")?></h3>
                </div>
                <?= $this->Form->create($productInfo,['id'=>'caseRegistrationForm']);?>
                <div class="card-body">
                    <div class="text-center">
                        <!-- Add Product -->
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label><?php echo __("Product Name")?> <i class="fas fa-asterisk reqField"></i>"</label>
                                <select type="text" class="form-control" id="product_id">
                                    <option value="null"><?php echo __("Select Project No")?></option>
                                    <?php
                                    foreach($productInfo as $k => $productDetail){
                                        echo "<option value=".$productDetail->id.">".$productDetail->product_name."</option>";
                                    };?>
                                    <!-- html->form(project_no) -->
                                </select>
                                <input name="product_id" type="hidden" id="input_product_id">
                            </div>
                            <div class="form-group col-md-3">
                                <label><?php echo __("Country")?> <i class="fas fa-asterisk reqField"></i></label>
                                <select type="text" class="form-control" id="sd_product_workflow_id">
                                    <option value="null"><?php echo __("Select Country")?></option>
                                    <!-- html->form(project_no) -->
                                </select>
                                <input name="sd_product_workflow_id" id="input_product_workflow_id" type="hidden">
                            </div>
                            <div class="form-group col-md-3">
                                <label><?php echo __("Event Report Term")?></label>
                                <input type="text" class="form-control" name="field_value[149]" id="event_report_term">
                             </div>
                             <div class="form-group col-md-3">
                                <label><?php echo __("Reaction Onset Date (B.2.i.4b)")?></label>
                                <input type="hidden" class="form-control" name="field_value[156]" id="event_onset_date">
                                <input type="date" class="form-control"  id="event_onset_date_plugin">
                             </div>
                        </div>
                        <div class="form-row">
                             <div class="form-group col-md-3">
                                <label><?php echo __("Patient Gender")?></label>
                                <select type="text" class="form-control" name="field_value[93]" id="patient_gender">
                                    <option value=""><?php echo __("Select Patient Gender")?></option>
                                    <option value="1"><?php echo __("Male")?></option>
                                    <option value="2"><?php echo __("Female")?></option>
                                    <option value="3"><?php echo __("Unknown")?></option>
                                    <option value="4"><?php echo __("Not Specified")?></option>
                                </select>
                             </div>
                            <div class="form-group col-md-5">
                                <label><?php echo __("Age at Time of Onset of Reaction/event (B.1.2.2a)")?></label>
                                <a tabindex="0" role="button" data-toggle="popover" data-trigger="focus" title="<?php echo __("Field Helper")?>" data-content="Age at time of onset of reaction or event"><i class="qco fas fa-info-circle"></i></a>
                                <input type="text" class="form-control" name="field_value[86]" id="patient_age">
                            </div>
                            <div class="form-group col-md-3">
                                <label><?php echo __("Age Unit")?></label>
                                <select class="form-control" name="field_value[87]" id="patient_age_unit">
                                    <option value="null"><?php echo __("Select Unit")?></option>
                                    <option value="800"><?php echo __("Decade")?></option>
                                    <option value="801"><?php echo __("Year")?></option>
                                    <option value="802"><?php echo __("Month")?></option>
                                    <option value="803"><?php echo __("Week")?></option>
                                    <option value="804"><?php echo __("Day")?></option>
                                    <option value="805"><?php echo __("Hour")?></option>
                                </select>
                            </div>
                        </div>

                        <!-- Advance Search -->
                        <div id="caseRegAdvFields" style="display:none;">
                            <hr class="my-3">
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label><?php echo __("Reporter First Name")?></label>
                                    <input type="text" class="form-control" name="field_value[26]" id="reporter_firstname">
                                </div>
                                <div class="form-group col-md-3">
                                    <label><?php echo __("Reporter Last name")?></label>
                                    <input type="text" class="form-control" name="field_value[28]" id="reporter_lastname">
                                </div>
                                <div class="form-group col-md-3">
                                    <label><?php echo __("Subject No.")?></label>
                                    <input type="text" class="form-control" name="" id="">
                                </div>
                                <div class="form-group col-md-3">
                                    <label><?php echo __("Patient Ethnic origin")?></label>
                                    <select class="form-control" id="patient_ethnic_origin" name="field_value[235]">
                                        <option value="null"></option>
                                        <option  value="1"><?php echo __("American Indian or Alaskan Native")?></option>
                                        <option  value="2"><?php echo __("Asian")?></option>
                                        <option  value="3"><?php echo __("Black or African American")?></option>
                                        <option  value="4"><?php echo __("Hispanic/Latino")?></option>
                                        <option  value="5"><?php echo __("Native Hawaiian or Other Pacific Islander")?></option>
                                        <option  value="6"><?php echo __("Not Hispanic/Latino")?></option>
                                        <option  value="7"><?php echo __("Other")?></option>
                                        <option  value="8"><?php echo __("Unknown")?></option>
                                        <option  value="9"><?php echo __("White")?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label><?php echo __("Patient Initial")?></label>
                                    <input type="text" class="form-control" name="field_value[79]" id="patient_initial">
                                </div>
                                <div class="form-group col-md-4">
                                    <label><?php echo __("Patient Date of Birth")?></label>
                                    <div class="form-row">
                                        <div class="col-sm-4">
                                            <select class="custom-select js-example-basic-single" placeholder="<?php echo __("Day")?>" id="patientField_dob_day">
                                            <option value="00"><?php echo __("Day")?></option>
                                            <?php
                                            for($i=1;$i<32;$i++){
                                                echo "<option value=\"".sprintf("%02d",$i)."\">".$i."</option>"; 
                                            }
                                            ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <select class="custom-select js-example-basic-single" placeholder="<?php echo __("Month")?>" id="patientField_dob_month">
                                            <?php
                                            $month_str = ['Jan-1','Feb-2','Mar-3','Apr-4','May-5','Jun-6','Jul-7','Aug-8','Sep-9','Oct-10','Nov-11','Dec-12'];
                                            echo "<option value=\"00\">Month</option>";
                                            foreach($month_str as $i => $month){
                                                echo "<option value=\"".sprintf("%02d",$i+1)."\">".__($month)."</option>";
                                            }
                                            ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <select class="custom-select js-example-basic-single yearSelect" placeholder="<?php echo __("Year")?>" id="patientField_dob_year" name="field_value[85][value]">
                                            <option value="0000"><?php echo __("Year")?></option>
                                            <?php
                                            for($i=1900;$i<=2050;$i++){
                                                echo "<option value=\"".sprintf("%04d",$i)."\">".$i."</option>";
                                            }
                                            ?>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="hidden" class="form-control" name="field_value[85]" id="patient_dob">
                                </div>
                                <div class="form-group col-md-3">
                                    <label><?php echo __("Patient Age Group")?></label>
                                    <select class="form-control" name="field_value[90]" id="patient_age_group">
                                        <option value="null"></option>
                                        <option value="1"><?php echo __("Neonate")?></option>
                                        <option value="2"><?php echo __("Infant")?></option>
                                        <option value="3"><?php echo __("Child")?></option>
                                        <option value="4"><?php echo __("Adolescent")?></option>
                                        <option value="5"><?php echo __("Adult")?></option>
                                        <option value="6"><?php echo __("Elderly")?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="p-3">
                                <h6 class="text-left"><?php echo __("Meddra Browser")?></h6>
                                <div class="form-row">
                                    <div class="form-group col-md-8">
                                        <!-- <label><?php echo __("Meddra Browser")?></label> -->
                                        <?php
                                        $meddraCell = $this->cell('Meddra');
                                        echo $meddraCell;?>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label><?php echo __("LLT Code")?></label>
                                        <input type="text" class="form-control" name="field_value[392]" id="meddraptname">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label><?php echo __("LLT Name")?></label>
                                        <input type="text" class="form-control" name="field_value[391]" id="meddralltname">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label><?php echo __("PT Code")?></label>
                                        <input type="text" class="form-control" name="field_value[394]" id="meddrahltname">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label><?php echo __("PT Name")?></label>
                                        <input type="text" class="form-control" name="field_value[393]" id="meddrahltname">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?= $this->Form->end()?>
                    <div id="checkbutton" class="d-flex justify-content-center">
                        <div id="caseRegAdvBtn" class="btn btn-outline-info w-25 mx-1"><i class="fas fa-keyboard"></i><?php echo __("Advanced Search")?></div>
                        <input class="btn btn-success mx-1 w-25" onclick="checkDuplicate()" id="checkbtn" type="button" value="<?php echo __("Search Duplicate")?>" >
                        <input onclick="clearResult()" id="clear" class="btn btn-outline-warning mx-2 w-25" style="display:none;" type="button" value="<?php echo __("Search Again")?>" >
                        <!-- <a role="button" onclick="checkDuplicate()" id="checkbtn" class="completeBtn btn btn-success d-block m-auto w-25">Seach Duplicate</a> -->
                    </div>

                    <div class="modal fade CaseDetail" tabindex="-1" role="dialog" aria-labelledby="CaseDetail" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content" style="width:1250px;left: -220px;">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="caseLabel"></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body m-3">
                                    <div class="embed-responsive embed-responsive-4by3">
                                        <iframe id="iframeDiv" class="embed-responsive-item" src=""></iframe>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo __("Close")?></button>
                                </div>
                                <!-- <iframe id="iframeDiv" src="" width="700" height="730"></iframe> -->
                            </div>
                        </div>
                    </div>
                    <div id="caseTable"></div>
                </div>
            </div>
        </div>
    </div>

</div>
<script type="text/javascript">
    var userId = <?= $this->request->getSession()->read('Auth.User.id')?>;
    var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
var productInfo = <?php $productInfo =$productInfo->toList();
echo json_encode($productInfo);?>;
</script>