<title>Case Registration</title>
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
                    <h3>Case Registration / Duplicate Detection</h3>
                </div>
                <?= $this->Form->create($productInfo,['id'=>'caseRegistrationForm']);?>
                <div class="card-body">
                    <div class="text-center">
                        <!-- Add Product -->
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label>Product Name: <i class="fas fa-asterisk reqField"></i></label>
                                <select type="text" class="form-control" id="product_id">
                                    <option value="null">Select Project No</option>
                                    <?php
                                    foreach($productInfo as $k => $productDetail){
                                        echo "<option value=".$productDetail->id.">".$productDetail->product_name."</option>";
                                    };?>
                                    <!-- html->form(project_no) -->
                                </select>
                                <input name="product_id" type="hidden" id="input_product_id">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Country: <i class="fas fa-asterisk reqField"></i></label>
                                <select type="text" class="form-control" id="sd_product_workflow_id">
                                    <option value="null">Select Country:</option>
                                    <!-- html->form(project_no) -->
                                </select>
                                <input name="sd_product_workflow_id" id="input_product_workflow_id" type="hidden">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Event Report Term:</label>
                                <input type="text" class="form-control" name="field_value[149]" id="event_report_term">
                             </div>
                             <div class="form-group col-md-3">
                                <label>Reaction Onset Date (B.2.i.4b):</label>
                                <input type="text" class="form-control" name="field_value[156]" id="event_onset_date">
                             </div>
                        </div>
                        <div class="form-row">
                             <div class="form-group col-md-3">
                                <label>Patient Gender:</label>
                                <select type="text" class="form-control" name="field_value[93]" id="patient_gender">
                                    <option value="">Select Patient Gender</option>
                                    <option value="1">Male</option>
                                    <option value="2">Female</option>
                                    <option value="3">Unknown</option>
                                    <option value="4">Not Specified</option>
                                </select>
                             </div>
                            <div class="form-group col-md-5">
                                <label>Age at Time of Onset of Reaction/event (B.1.2.2a)</label>
                                <a tabindex="0" role="button" data-toggle="popover" data-trigger="focus" title="Field Helper" data-content="Age at time of onset of reaction or event"><i class="qco fas fa-info-circle"></i></a>
                                <input type="text" class="form-control" name="field_value[86]" id="patient_age">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Age Unit:</label>
                                <select class="form-control" name="field_value[87]" id="patient_age_unit">
                                    <option value="null">Select Unit</option>
                                    <option value="800">Decade</option>
                                    <option value="801">Year</option>
                                    <option value="802">Month</option>
                                    <option value="803">Week</option>
                                    <option value="804">Day</option>
                                    <option value="805">Hour</option>
                                </select>
                            </div>
                        </div>

                        <!-- Advance Search -->
                        <div id="caseRegAdvFields" style="display:none;">
                            <hr class="my-3">
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label>Reporter First Name:</label>
                                    <input type="text" class="form-control" name="field_value[26]" id="reporter_firstname">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Reporter Last name:</label>
                                    <input type="text" class="form-control" name="field_value[28]" id="reporter_lastname">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Subject No.:</label>
                                    <input type="text" class="form-control" name="" id="">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Patient Ethnic origin:</label>
                                    <select class="form-control" id="patient_ethnic_origin" name="field_value[235]">
                                        <option value="null"></option>
                                        <option  value="1">American Indian or Alaskan Native</option>
                                        <option  value="2">Asian</option>
                                        <option  value="3">Black or African American</option>
                                        <option  value="4">Hispanic/Latino</option>
                                        <option  value="5">Native Hawaiian or Other Pacific Islander</option>
                                        <option  value="6">Not Hispanic/Latino</option>
                                        <option  value="7">Other</option>
                                        <option  value="8">Unknown</option>
                                        <option  value="9">White</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label>Patient Initial:</label>
                                    <input type="text" class="form-control" name="field_value[79]" id="patient_initial">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Patient Date of Birth:</label>
                                    <div class="form-row">
                                        <div class="col-sm-4">
                                            <select id="dobDay" class="custom-select js-example-basic-single daySelect" placeholder="Day">
                                                <option value="00">Day</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <select id=dobMonth class="custom-select js-example-basic-single monthSelect" placeholder="Month">
                                                <option value="00">Month</option>
                                                <option value="01">Jan-1</option>
                                                <option value="02">Feb-2</option>
                                                <option value="03">Mar-3</option>
                                                <option value="04">Apr-4</option>
                                                <option value="05">May-5</option>
                                                <option value="06">Jun-6</option>
                                                <option value="07">Jul-7</option>
                                                <option value="08">Aug-8</option>
                                                <option value="09">Sep-9</option>
                                                <option value="10">Oct-10</option>
                                                <option value="11">Nov-11</option>
                                                <option value="12">Dec-12</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <select id="dobYear" class="custom-select js-example-basic-single yearSelect" placeholder="Year">
                                                <option value="0000">Year</option>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="hidden" class="form-control" name="field_value[85]" id="patient_dob">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Patient Age group:</label>
                                    <select class="form-control" name="field_value[90]" id="patient_age_group">
                                        <option value="null"></option>
                                        <option value="1">Neonate</option>
                                        <option value="2">Infant</option>
                                        <option value="3">Child</option>
                                        <option value="4">Adolescent</option>
                                        <option value="5">Adult</option>
                                        <option value="6">Elderly</option>
                                    </select>
                                </div>
                            </div>
                            <div class="p-3">
                                <h6 class="text-left">Meddra Browser</h6>
                                <div class="form-row">
                                    <div class="form-group col-md-8">
                                        <!-- <label>Meddra Browser:</label> -->
                                        <?php
                                        $meddraCell = $this->cell('Meddra');
                                        echo $meddraCell;?>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label>PT Term:</label>
                                        <input type="text" class="form-control" name="field_value[394]" id="meddraptname">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>LLT Term:</label>
                                        <input type="text" class="form-control" name="field_value[392]" id="meddralltname">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>HLT Term:</label>
                                        <input type="text" class="form-control" name="field_value[395]" id="meddrahltname">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?= $this->Form->end()?>
                    <div id="checkbutton" class="d-flex justify-content-center">
                        <div id="caseRegAdvBtn" class="btn btn-outline-info w-25 mx-1"><i class="fas fa-keyboard"></i> Advanced Search</div>
                        <input class="btn btn-success mx-1 w-25" onclick="checkDuplicate()" id="checkbtn" type="button" value="Seach Duplicate">
                        <input onclick="clearResult()" id="clear" class="btn btn-outline-warning mx-2 w-25" style="display:none;" type="button" value="Search Again">
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
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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