<title>Create New Case</title>
<head>
<?= $this->Html->script('cases/duplicate_detection.js') ?>
<head>

<div class="card text-center w-75 mx-auto my-3">
  <div class="card-header text-center"><h3>Create New Case</h3></div>
  <div class="card-body">
    <div class="alert alert-primary w-50 mx-auto" role="alert"><h4>New Case Number: 43523452345234</h4></div>
    <hr class="my-3">
    <!-- Basic Info Fields Set -->
    <div id="basicInfo" class="form-group">
        <h4 class="text-left">Product</h4>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label>Product Name <i class="fas fa-asterisk reqField"></i></label>
                <input type="text" class="form-control" id="test" placeholder="">
            </div>
        </div>
        <h4 class="text-left mt-3">Patient</h4>
        <div id="patientInfo" class="form-row bg-light">
            <div class="form-group col-md-3">
                <label>Patient ID (B.1.1)</label>
                <input type="text" class="form-control" id="patient" placeholder="">
            </div>
            <div class="form-group col-md-3">
                <label>Sex (B.1.5)</label>
                <input type="text" class="form-control" id="sex" placeholder="">
            </div>
        </div>
        <div id="patientInfo" class="form-row bg-light">
            <div class="form-group col-md-3">
                <label>Date of birth (B.1.2.1b)</label>
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
                <input type="hidden" class="form-control" name="field_value[85]" id="caseReg_patient_dob" value="00000000">
            </div>
            <div class="form-group col-md-5">
                <label>Age at time of onset reaction (B.1.2.2a)</label>
                <a tabindex="0" role="button" data-toggle="popover" data-trigger="focus" title="Field Helper" data-content="Age at time of onset of reaction or event"><i class="qco fas fa-info-circle"></i></a>
                <input type="text" class="form-control" id="age" placeholder="">
            </div>
            <!-- <div class="form-group col-md-3">
                <label>Age at the time of event (B.1.2.2a)</label>
                <input type="text" class="form-control" id="" placeholder="">
            </div> -->
            <div class="form-group col-md-3">
                <label>Age Unit (B.1.2.2b)</label>
                <select class="custom-select">
                    <option value="">Select Age Unit</option>
                    <option value="800">Decade</option>
                    <option value="801">Year</option>
                    <option value="802">Month</option>
                    <option value="803">Week</option>
                    <option value="804">Day</option>
                    <option value="805">Hour</option>
                </select>
            </div>
        </div>
        <h4 class="text-left mt-3">Reporter</h4>
        <div id="reporterInfo" class="form-row">
            <div class="form-group col-md-3">
                <label>First Name (A.2.1.1b)</label>
                <input type="text" class="form-control" id="fname" placeholder="">
            </div>
            <div class="form-group col-md-3">
                <label>Last Name (A.2.1.1d)</label>
                <input type="text" class="form-control" id="lname" placeholder="">
            </div>
        </div>
        <h4 class="text-left mt-3">Event</h4>
        <div class="form-row bg-light">
            <div class="form-group col-md-4">
                <label>Reported term (B.2.i.0) <i class="fas fa-asterisk reqField"></i></label>
                <input type="text" class="form-control" id="report_term" placeholder="">
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
                <input type="text" class="form-control" id="" placeholder="">
            </div>
            <div class="form-group col-md-3">
                <label>PT Name</label>
                <input type="text" class="form-control" id="" placeholder="">
            </div>
            <div class="form-group col-md-3">
                <label>HLT Name</label>
                <input type="text" class="form-control" id="" placeholder="">
            </div>
        </div>

        <!-- Attachment -->
        <h4 class="text-left mt-3">Attach Documents </h4>
        <div class="form-row">
            <div class="form-group col-md-4">
                <input type="file" class="form-control-file" id="">
            </div>
        </div>

        <button type="button" id="confirmElements" class="btn btn-primary m-auto w-25">Countinue</button>
    </div>

    <hr class="my-2">

    <!-- If invalid then choose YES, Select Reasons -->
    <div id="selRea" class="card w-50 mx-auto my-3" style="display:none;">
        <div class="card-header text-center"><h5>Please Select Reasons</h5></div>
        <div class="card-body">
            <div class="mx-auto w-50">
                <div class="form-check my-2 text-left">
                    <input class="form-check-input" type="checkbox" value="" id="1">
                    <label class="form-check-label" for="1">Reporter is Reliable </label>
                </div>
                <div class="form-check my-2 text-left">
                    <input class="form-check-input" type="checkbox" value="" id="2">
                    <label class="form-check-label" for="2">Important Event </label>
                </div>
                <div class="form-check my-2 text-left">
                    <input class="form-check-input" type="checkbox" value="" id="otherCheck">
                    <label class="form-check-label" for="otherCheck">Others </label>
                    <textarea class="form-control" id="othersInput" rows="3" style="display:none;"></textarea>
                </div>
            </div>
            <button type="button" id="selReaBack" class="btn btn-outline-warning my-2 mx-2 w-25">Back</button>
            <button type="button" id="confirmRea" class="btn btn-primary my-2 mx-2 w-50">Confirm</button>
        </div>
    </div>

    <!-- If Valid then choose NO, Prioritize -->
    <div id="prioritize" class="card mx-auto my-3 w-50" style="display:none;">
        <div class="card-header text-center"><h5>Prioritize</h5></div>
        <div class="card-body">
            <div class="row my-2">
                <legend class="col-form-label col-sm-4 pt-0 text-right">Seriousness</legend>
                <div class="col-sm-8 text-left">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios1" value="option1">
                        <label class="form-check-label" for="gridRadios1">Fatal / Life Threatening</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios2" value="option2">
                        <label class="form-check-label" for="gridRadios2">Other Serious</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gridRadios" id="qq" value="option1">
                        <label class="form-check-label" for="qq">Serious / Spontaneous</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gridRadios" id="ww" value="option2">
                        <label class="form-check-label" for="ww">Non Serious</label>
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <legend class="col-form-label col-sm-4 pt-0 text-right">Related</legend>
                <div class="col-sm-8 text-left">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="qwe" id="rr" value="option1">
                        <label class="form-check-label" for="rr">Yes</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="qwe" id="ff" value="option2">
                        <label class="form-check-label" for="ff">No</label>
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <legend class="col-form-label col-sm-4 pt-0 text-right">Unlabelled</legend>
                <div class="col-sm-8 text-left">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="ert" id="5" value="option1">
                        <label class="form-check-label" for="5">Yes</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="ert" id="6" value="option2">
                        <label class="form-check-label" for="6">No</label>
                    </div>
                </div>
            </div>
            <button type="button" id="prioritizeBack" class="btn btn-outline-warning my-2 mx-2 w-25">Back</button>
            <button type="button" id="confirmPrioritize" class="btn btn-primary my-2 mx-2 w-50">Confirm</button>
        </div>
    </div>


  </div>
</div>
