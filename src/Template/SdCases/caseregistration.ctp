<title><?php echo __("Case Registration")?></title>
<head>
    <?= $this->Html->script('cases/duplicate_detection.js') ?>
    <!-- For datepicker in caselist page-->
    <?= $this->Html->css('datepicker/jquery-ui.css') ?>
    <?= $this->Html->script('datepicker/jquery-1.10.2.js') ?>
    <?= $this->Html->script('datepicker/jquery-ui-1.10.4.js') ?>
    <!-- For local CSS link -->
    <?= $this->Html->css('mainlayout.css') ?>
    <?= $this->Html->script('meddra.js') ?>
    <!-- For local Select2 (External library for quick selecting) CSS/JS link -->
    <?= $this->Html->css('select2/select2.min.css') ?>
    <?= $this->Html->script('select2/select2.min.js') ?>
    <!-- For local DataTable CSS/JS link -->
    <?= $this->Html->css('datatable/dataTables.bootstrap4.min.css') ?>
    <?= $this->Html->script('datatable/DataTables/js/jquery.dataTables.min.js') ?>
    <?= $this->Html->script('datatable/DataTables/js/dataTables.bootstrap4.min.js') ?>
<head>

<div class="card mx-auto my-3 w-75">
    <div class="card-header pageTitle text-center">
        <?php echo __("Case Registration / Duplicate Detection");?>
    </div>
    <div class="card-body">
        <?= $this->Form->create($productInfo,['id'=>'caseRegistrationForm']);?>
            <!-- Add Product -->
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label>
                        <?php echo __("Product Name")?> <i class="fas fa-asterisk reqField"></i>
                        <span class='ml-2 badge badge-info caseRegV' style='display:none;'>E2B <span id='e2bV'></span> </span>
                    </label>
                    <select type="text" class="form-control" id="product_id">
                        <option value=""><?php echo __("Select Project No.")?></option>
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
                        <option value=""><?php echo __("Select Country")?></option>
                        <!-- html->form(project_no) -->
                    </select>
                    <input name="sd_product_workflow_id" id="input_product_workflow_id" type="hidden">
                </div>
                <div class="form-group col-md-3">
                    <label><?php echo __("Event Report Term")?></label>
                    <input type="text" class="form-control" name="field_value[149]" id="event_report_term">
                </div>
                <div class="form-group col-md-3">
                    <label><?php echo __("Reaction Onset Date")?></label>
                    <a tabindex="0" role="button" data-toggle="popover" data-trigger="focus" title="<?php echo __("Field Helper")?>" data-content="Date of Start of Reaction / Event (B.2.i.4b/E.i.4)"><i class="qco fas fa-info-circle"></i></a>
                    <input type="hidden" class="form-control" name="field_value[156]" id="event_onset_date">
                    <input type="text" class="form-control"  id="event_onset_date_plugin" placeholder="<?php echo __("yyyy/mm/dd")?>">
                </div>

                <!-- Second Line -->
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
                <div class="form-group col-md-4">
                    <label><?php echo __("Age at Time of Onset of Reaction/event")?></label>
                    <a tabindex="0" role="button" data-toggle="popover" data-trigger="focus" title="<?php echo __("Field Helper")?>" data-content="Age at time of onset of reaction or event (B.1.2.2a/D.2.2a)"><i class="qco fas fa-info-circle"></i></a>
                    <input type="text" class="form-control" name="field_value[86]" id="patient_age">
                </div>
                <div class="form-group col-md-3">
                    <label><?php echo __("Age Unit")?></label>
                    <select class="form-control" name="field_value[87]" id="patient_age_unit">
                        <option value=""><?php echo __("Select Unit")?></option>
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
                <hr class="my-3 w-75">
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
                            <option value=""></option>
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
                    <div class="form-group col-md-3">
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
                                echo "<option value=\"00\">".__("Month")."</option>";
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
                            <option value=""></option>
                            <option value="1"><?php echo __("Neonate")?></option>
                            <option value="2"><?php echo __("Infant")?></option>
                            <option value="3"><?php echo __("Child")?></option>
                            <option value="4"><?php echo __("Adolescent")?></option>
                            <option value="5"><?php echo __("Adult")?></option>
                            <option value="6"><?php echo __("Elderly")?></option>
                        </select>
                    </div>
                </div>

                <div class="my-3">
                    <div class="form-row justify-content-center">
                        <div class="form-group col-md-12">
                            <!-- <label><?php echo __("Meddra Browser")?></label> -->
                            <?php
                            $meddraCell = $this->cell('Meddra',['llt-c:392,llt-n:457,pt-c:394,pt-n:458,ver:150,ver:443','496']);
                            echo $meddraCell;?>
                            <input type="hidden" id="meddraResult-496" name="field_value[496]" value="">
                        </div>
                    </div>
                    <div class="form-row justify-content-center">
                        <div class="form-group col-md-3">
                            <label><?php echo __("LLT Code")?></label>
                            <input type="text" class="form-control" name="field_value[392]" id="meddrashow-392">
                        </div>
                        <div class="form-group col-md-3">
                            <label><?php echo __("LLT Name")?></label>
                            <input type="text" class="form-control" name="field_value[457]" id="meddrashow-457">
                        </div>
                        <div class="form-group col-md-3">
                            <label><?php echo __("PT Code")?></label>
                            <input type="text" class="form-control" name="field_value[394]" id="meddrashow-394">
                        </div>
                        <div class="form-group col-md-3">
                            <label><?php echo __("PT Name")?></label>
                            <input type="text" class="form-control" name="field_value[458]" id="meddrashow-458">
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <?= $this->Form->end()?>

        <div id="checkbutton" class="text-center mb-4">
            <button class="btn btn-sm btn-outline-info w-25 mx-1" id="caseRegAdvBtn"><i class="fas fa-search"></i> <?php echo __("Advanced Search")?></button>
            <button class="btn btn-sm btn-success mx-1 w-25" onclick="checkDuplicate()" id="checkbtn"><i class="far fa-copy"></i> <?php echo __("Search Duplicate")?></button>
        </div>

        <div class='text-center'>
            <button class="btn btn-sm btn-outline-primary w-25"  onclick="clearResult()" id="clear" style="display:none;"><i class="fab fa-searchengin"></i> <?php echo __("Search Again")?></button>
        </div>

        <!-- Search Results -->
        <div id="caseTable" class='text-center'>
        </div>

        <!-- CaseDetail Modal -->
        <div class="modal fade CaseDetail" tabindex="-1" role="dialog" aria-labelledby="CaseDetail" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content modalStyle">
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
    </div>
    <!-- Loading Animation -->
    <div class="text-center w-75 mx-auto loadingSpinner">
        <div class="spinner-border text-primary m-5" role="status">
            <span class="visually-hidden"></span>
        </div>
    </div>
</div>

<script type="text/javascript">
    var userId = <?= $this->request->getSession()->read('Auth.User.id')?>;
    var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
    var productInfo = <?php $productInfo = $productInfo->toList();
    echo json_encode($productInfo);?>;
</script>