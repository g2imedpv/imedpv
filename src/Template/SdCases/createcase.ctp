<title>Create New Case</title>
<head>
<?= $this->Html->script('cases/duplicate_detection.js') ?>
<head>

<div class="card text-center w-75 mx-auto my-3">
  <div class="card-header text-center"><h3><?php echo __("Create New Case")?></h3></div>
  <div class="card-body">
    <div class="alert alert-primary w-50 mx-auto" role="alert"><h4>New Case Number: 43523452345234</h4></div>
    <hr class="my-3">
    <!-- Basic Info Fields Set -->
    <div id="basicInfo" class="form-group">
        <h4 class="text-left"><?php echo __("Product")?></h4>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label><?php echo __("Product Name")?> <i class="fas fa-asterisk reqField"></i></label>
                <input type="text" class="form-control" id="test" placeholder="">
            </div>
        </div>
        <h4 class="text-left mt-3"><?php echo __("Patient")?></h4>
        <div id="patientInfo" class="form-row bg-light">
            <div class="form-group col-md-3">
                <label><?php echo __("Patient ID")?> (B.1.1)</label>
                <input type="text" class="form-control" id="patient" placeholder="">
            </div>
            <div class="form-group col-md-3">
                <label><?php echo __("Sex")?> (B.1.5)</label>
                <input type="text" class="form-control" id="sex" placeholder="">
            </div>
        </div>
        <div id="patientInfo" class="form-row bg-light">
            <div class="form-group col-md-3">
                <label><?php echo __("Date of birth")?> (B.1.2.1b)</label>
                <div class="form-row">
                    <div class="col-sm-4">
                        <select id="dobDay" class="custom-select js-example-basic-single daySelect" placeholder="<?php echo __("Day")?>">
                            <option value="00"><?php echo __("Day")?></option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <select id=dobMonth class="custom-select js-example-basic-single monthSelect" placeholder="<?php echo __("Month")?>">
                            <option value="00"><?php echo __("Month")?></option>
                            <option value="01"><?php echo __("Jan-1")?></option>
                            <option value="02"><?php echo __("Feb-2")?></option>
                            <option value="03"><?php echo __("Mar-3")?></option>
                            <option value="04"><?php echo __("Apr-4")?></option>
                            <option value="05"><?php echo __("May-5")?></option>
                            <option value="06"><?php echo __("Jun-6")?></option>
                            <option value="07"><?php echo __("Jul-7")?></option>
                            <option value="08"><?php echo __("Aug-8")?></option>
                            <option value="09"><?php echo __("Sep-9")?></option>
                            <option value="10"><?php echo __("Oct-10")?></option>
                            <option value="11"><?php echo __("Nov-11")?></option>
                            <option value="12"><?php echo __("Dec-12")?></option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <select id="dobYear" class="custom-select js-example-basic-single yearSelect" placeholder="<?php echo __("Year")?>">
                            <option value="0000"><?php echo __("Year")?></option>
                        </select>
                    </div>
                </div>
                <input type="hidden" class="form-control" name="field_value[85]" id="caseReg_patient_dob" value="00000000">
            </div>
            <div class="form-group col-md-5">
                <label><?php echo __("Age at time of onset reaction")?> (B.1.2.2a)</label>
                <a tabindex="0" role="button" data-toggle="popover" data-trigger="focus" title="<?php echo __("Field Helper")?>" data-content="Age at time of onset of reaction or event"><i class="qco fas fa-info-circle"></i></a>
                <input type="text" class="form-control" id="age" placeholder="">
            </div>
            <!-- <div class="form-group col-md-3">
                <label>Age at the time of event (B.1.2.2a)</label>
                <input type="text" class="form-control" id="" placeholder="">
            </div> -->
            <div class="form-group col-md-3">
                <label><?php echo __("Age Unit")?> (B.1.2.2b)</label>
                <select class="custom-select">
                    <option value=""><?php echo __("Select Age Unit")?></option>
                    <option value="800"><?php echo __("Decade")?></option>
                    <option value="801"><?php echo __("Year")?></option>
                    <option value="802"><?php echo __("Month")?></option>
                    <option value="803"><?php echo __("Week")?></option>
                    <option value="804"><?php echo __("Day")?></option>
                    <option value="805"><?php echo __("Hour")?></option>
                </select>
            </div>
        </div>
        <h4 class="text-left mt-3"><?php echo __("Reporter")?></h4>
        <div id="reporterInfo" class="form-row">
            <div class="form-group col-md-3">
                <label><?php echo __("First Name")?> (A.2.1.1b)</label>
                <input type="text" class="form-control" id="fname" placeholder="">
            </div>
            <div class="form-group col-md-3">
                <label><?php echo __("Last Name")?> (A.2.1.1d)</label>
                <input type="text" class="form-control" id="lname" placeholder="">
            </div>
        </div>
        <h4 class="text-left mt-3"><?php echo __("Event")?></h4>
        <div class="form-row bg-light">
            <div class="form-group col-md-4">
                <label><?php echo __("Reported term")?> (B.2.i.0) <i class="fas fa-asterisk reqField"></i></label>
                <input type="text" class="form-control" id="report_term" placeholder="">
            </div>
            <div class="form-group col-md-2">
                <label><?php echo __("MedDra Browser")?></label>
                <div>
                    <?php
                    $meddraCell = $this->cell('Meddra');
                    echo $meddraCell;?>
                </div>
            </div>
        </div>
        <div class="form-row bg-light">
            <div class="form-group col-md-3">
                <label><?php echo __("LLT Name")?></label>
                <input type="text" class="form-control" id="" placeholder="">
            </div>
            <div class="form-group col-md-3">
                <label><?php echo __("PT Name")?></label>
                <input type="text" class="form-control" id="" placeholder="">
            </div>
            <div class="form-group col-md-3">
                <label><?php echo __("HLT Name")?></label>
                <input type="text" class="form-control" id="" placeholder="">
            </div>
        </div>

        <!-- Attachment -->
        <h4 class="text-left mt-3"><?php echo __("Attach Documents")?> </h4>
        <div class="form-row">
            <div class="form-group col-md-4">
                <input type="file" class="form-control-file" id="">
            </div>
        </div>

        <button type="button" id="confirmElements" class="btn btn-primary m-auto w-25"><?php echo __("Continue")?></button>
    </div>

    <hr class="my-2">

    <!-- If invalid then choose YES, Select Reasons -->
    <div id="selRea" class="card w-50 mx-auto my-3" style="display:none;">
        <div class="card-header text-center"><h5><?php echo __("Please Select Reasons")?></h5></div>
        <div class="card-body">
            <div class="mx-auto w-50">
                <div class="form-check my-2 text-left">
                    <input class="form-check-input" type="checkbox" value="" id="1">
                    <label class="form-check-label" for="1"><?php echo __("Reporter is Reliable")?> </label>
                </div>
                <div class="form-check my-2 text-left">
                    <input class="form-check-input" type="checkbox" value="" id="2">
                    <label class="form-check-label" for="2"><?php echo __("Important Event")?> </label>
                </div>
                <div class="form-check my-2 text-left">
                    <input class="form-check-input" type="checkbox" value="" id="otherCheck">
                    <label class="form-check-label" for="otherCheck"><?php echo __("Others")?> </label>
                    <textarea class="form-control" id="othersInput" rows="3" style="display:none;"></textarea>
                </div>
            </div>
            <button type="button" id="selReaBack" class="btn btn-outline-warning my-2 mx-2 w-25"></button>
            <button type="button" id="confirmRea" class="btn btn-primary my-2 mx-2 w-50"><?php echo __("Confirm")?></button>
        </div>
    </div>

    <!-- If Valid then choose NO, Prioritize -->
    <div id="prioritize" class="card mx-auto my-3 w-50" style="display:none;">
        <div class="card-header text-center"><h5><?php echo __("Prioritize")?></h5></div>
        <div class="card-body">
            <div class="row my-2">
                <legend class="col-form-label col-sm-4 pt-0 text-right"><?php echo __("Seriousness")?></legend>
                <div class="col-sm-8 text-left">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios1" value="option1">
                        <label class="form-check-label" for="gridRadios1"><?php echo __("Fatal / Life Threatening")?></label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios2" value="option2">
                        <label class="form-check-label" for="gridRadios2"><?php echo __("Other Serious")?></label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gridRadios" id="qq" value="option1">
                        <label class="form-check-label" for="qq"><?php echo __("Serious / Spontaneous")?></label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gridRadios" id="ww" value="option2">
                        <label class="form-check-label" for="ww"><?php echo __("Non Serious")?></label>
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <legend class="col-form-label col-sm-4 pt-0 text-right"><?php echo __("Related")?></legend>
                <div class="col-sm-8 text-left">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="qwe" id="rr" value="option1">
                        <label class="form-check-label" for="rr"><?php echo __("Yes")?></label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="qwe" id="ff" value="option2">
                        <label class="form-check-label" for="ff"><?php echo __("No")?></label>
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <legend class="col-form-label col-sm-4 pt-0 text-right"><?php echo __("Unlabelled")?></legend>
                <div class="col-sm-8 text-left">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="ert" id="5" value="option1">
                        <label class="form-check-label" for="5"><?php echo __("Yes")?></label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="ert" id="6" value="option2">
                        <label class="form-check-label" for="6"><?php echo __("No")?></label>
                    </div>
                </div>
            </div>
            <button type="button" id="prioritizeBack" class="btn btn-outline-warning my-2 mx-2 w-25"><?php echo __("Back")?></button>
            <button type="button" id="confirmPrioritize" class="btn btn-primary my-2 mx-2 w-50"><?php echo __("Confirm")?></button>
        </div>
    </div>


  </div>
</div>
