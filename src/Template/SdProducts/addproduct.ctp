<?php
//debug($sdProductTypes);
?>
<title><?php echo __("Add Product")?></title>
<head>
<?= $this->Html->script('product/addproduct.js') ?>
<!-- For datepicker in caselist page-->
<?= $this->Html->css('datepicker/jquery-ui.css') ?>
<?= $this->Html->script('datepicker/jquery-1.10.2.js') ?>
<?= $this->Html->script('datepicker/jquery-ui-1.10.4.js') ?>
<!-- For local Select2 (External library for quick selecting) CSS/JS link -->
<?= $this->Html->css('select2/select2.min.css') ?>
<?= $this->Html->script('select2/select2.min.js') ?>
<head>
<script type="text/javascript">
    var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
</script>

<div class="card mx-auto my-3 w-75">
    <div class="card-header pageTitle text-center">
        <?php echo __("Add Product")?>
    </div>
    <!-- Add Product -->
    <div class="card-body">
        <span id="errorMsg" class="alert alert-danger" role="alert" style="display:none"></span>
        <?= $this->Form->create('addproduct',
            [
                'class'=>'needs-validation',
                'novalidate' => true
            ]
        );?>

        <h5 class="">Product Info</h5>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label><?php echo __("Product Name")?> (B.4.k.2.1)</label>
                <a tabindex="0" role="button" data-toggle="popover" title="" data-original-title="<?php echo __("Proprietary Medicinal Product Name")?> (B.4.k.2.1)" data-content="<div><?php echo __("The name should be that used by the reporter. It is recognized that a single product may have different proprietary names in different countries, even when produced by a single manufacturer.");?></div>" ><i class="qco fas fa-info-circle"></i></a>
                <input type="text" class="form-control" id="product_name" name="product[product_name]" placeholder="<?php echo __("Proprietary Medicinal Product Name");?>" required>
                <div class="invalid-feedback">This field is REQUIRED</div>
            </div>
            <div class="form-group col-md-6">
                <label><?php echo __("Short Description")?> </label>
                <input type="text" class="form-control" id="short_desc" name="product[short_desc]" placeholder="<?php echo __("Proprietary Medicinal Product Name");?>" required>
                <div class="invalid-feedback">This field is REQUIRED</div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label><?php echo __("Product Abbreviation")?> </label>
                <a tabindex="0" role="button" data-toggle="popover" title="" data-original-title="<?php echo __("Product Abbreviation")?>" data-content="<div><?php echo __("Product Abbreviation is required, max length is 5.")?></div>" ><i class="qco fas fa-info-circle"></i></a>
                <input type="text" class="form-control" id="short_desc" name="product[product_abbreviation]" maxlength=5 placeholder="<?php echo __("Product Abbreviation");?>" required>
                <div class="invalid-feedback">
                    Product Abbreviation is required, max length is 5.
                </div>
            </div>
            <div class="form-group col-md-6">
                <label><?php echo __("Product Description")?></label>
                <input type="text" class="form-control" id="product_desc" name="product[product_desc]" placeholder="<?php echo __("Proprietary Medicinal Product Name");?>" required>
                <div class="invalid-feedback">This field is REQUIRED</div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label><?php echo __("Product Type")?></label>
                <div class="option_group">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="drug_type" value="1" name="case[product_type]" class="custom-control-input">
                        <label for="drug_type" class="custom-control-label"><?php echo __("Drug")?><label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="vaccine_type" value="2" name="case[product_type]" class="custom-control-input">
                        <label for="vaccine_type" class="custom-control-label"><?php echo __("Vaccine")?><label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="device_type" value="3" name="case[product_type]" class="custom-control-input">
                        <label for="device_type" class="custom-control-label"><?php echo __("Device")?><label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="combination_type" value="4" name="case[product_type]" class="custom-control-input">
                        <label for="combination_type" class="custom-control-label"><?php echo __("Combination")?><label>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-3">
                <label><?php echo __("Mfr. Name")?></label>
                <input type="text" class="form-control" id="mfr_name" name="product[mfr_name]" placeholder="<?php echo __("Mfr. Name");?>" required>
                <div class="invalid-feedback">This field is REQUIRED</div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-4">
                <label><?php echo __("Drug Role / Product Flag")?> (B.4.k.1)</label>
                <a tabindex="0" role="button" data-toggle="popover" title="" data-original-title="<?php echo __("Characterization of Drug Role")?> (B.4.k.1)" data-content="<div><?php echo __("Characterization of the drug as provided by primary reporter. All spontaneous reports should have at least one suspect drug. If the reporter indicates a suspected interaction, interacting should be selected. All interacting drugs are considered to be suspect drugs.")?></div>" ><i class="qco fas fa-info-circle"></i></a>
                <select class="form-control" id="sd_product_flag" name="product[sd_product_flag]" required>
                    <option value=""><?php echo __("Select Characterization of Drug Role")?></option>
                    <option value="1"><?php echo __("Suspect")?></option>
                    <option value="2"><?php echo __("Concomitant")?></option>
                    <option value="3"><?php echo __("Interacting")?></option>
                </select>
                <div class="invalid-feedback">This field is REQUIRED</div>
            </div>
            <div class="form-group col-md-4">
                <label><?php echo __("Product Status")?></label>
                <select class="form-control" id="status" name="product[status]" required>
                    <option value=""><?php echo __("Select Product Status")?></option>
                    <option value="1"><?php echo __("Active")?></option>
                    <option value="2"><?php echo __("Close")?></option>
                </select>
                <div class="invalid-feedback">This field is REQUIRED</div>
            </div>
            <div class="form-group col-md-4">
                <label><?php echo __("Blinding Method")?></label>
                <select class="form-control" id="blinding_tech" name="product[blinding_tech]" required>
                    <option value=""><?php echo __("Select Blinding Method")?></option>
                    <option value="1"><?php echo __("Single blind")?></option>
                    <option value="2"><?php echo __("Double blind")?></option>
                    <option value="3"><?php echo __("Open-label")?></option>
                </select>
                <div class="invalid-feedback">This field is REQUIRED</div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-3">
                <label><?php echo __("WHO-DD Browser")?></label>
                <div class="">
                    <?php
                    $whodraCell = $this->cell('Whodd');
                    echo $whodraCell;?>
                </div>
            </div>
            <div class="form-group col-md-3">
                <label><?php echo __("WHO-DD Code")?></label>
                <input type="text" readonly="readonly" class="form-control" id="whodracode" name="product[WHODD_code]" placeholder="<?php echo __("WHO-DD Code");?>">
            </div>
            <div class="form-group col-md-3">
                <label><?php echo __("WHO-DD Name")?></label>
                <input type="text" readonly="readonly" class="form-control" id="whodraname" name="product[WHODD_name]" placeholder="<?php echo __("WHO-DD Name");?>">
            </div>
            <div class="form-group col-md-3">
                <label><?php echo __("WHO-DD Preferred Name")?></label>
                <input type="text" class="form-control" id="WHODD_decode" name="product[WHODD_decode]" placeholder="<?php echo __("WHO-DD Preferred Name");?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label><?php echo __("Study Name")?> (A.2.3.1)</label>
                <input type="text" class="form-control" id="study_name" name="product[study_name]" placeholder="<?php echo __("Study Name");?>" required>
                <div class="invalid-feedback">This field is REQUIRED</div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-3">
                <label><?php echo __("Sponsor Study Number (A.2.3.2)");?></label>
                <a tabindex="0" role="button" data-toggle="popover" title="" data-original-title="<?php echo __("Sponsor Study Number (A.2.3.2)");?>" data-content="<div><?php echo __("This section would be completed only if the sender is the study sponsor or has been informed of the study number by the sponsor.");?></div>" ><i class="qco fas fa-info-circle"></i></a>
                <input type="text" class="form-control" id="study_no" name="product[study_no]" placeholder="<?php echo __("Study Number");?>" required>
                <div class="invalid-feedback">This field is REQUIRED</div>
            </div>
            <div class="form-group col-md-3">
                <label><?php echo __("Study Type");?> (A.2.3.3)</label>
                <a tabindex="0" role="button" data-toggle="popover" title="" data-original-title="<?php echo __("Study type in which the reaction(s)/event(s) were observed");?> (A.2.3.3)" data-content="<div><li><?php echo __("Clinical trials");?></li><li><?php echo __("Individual patient use");?> <?php echo __("(e.g., compassionate use or named patient basis)");?></li><li><?php echo __("Other studies");?> <?php echo __("(e.g., pharmacoepidemiology, pharmacoeconomics, intensive monitoring, PMS)");?></li></div>" ><i class="qco fas fa-info-circle"></i></a>
                <select class="form-control" id="sd_study_type_id" name="product[study_type]" required>
                    <option value=""><?php echo __("Select Study Type");?></option>
                    <option value="1"><?php echo __("Clinical trials");?></option>
                    <option value="2"><?php echo __("Individual patient use");?></option>
                    <option value="3"><?php echo __("Other studies");?></option>
                </select>
                <div class="invalid-feedback">This field is REQUIRED</div>
            </div>
            <div class="form-group col-md-3">
                <label><?php echo __("E2B Version");?></label>
                <select class="form-control" id="sd_e2b-version_id" name="product[e2b_version]" required>
                    <option value=""><?php echo __("Select E2B Version");?></option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
                <div class="invalid-feedback">This field is REQUIRED</div>
            </div>

        </div>

        <div class="form-row">
            <div class="form-group col-md-3">
                <label><?php echo __("Study Start Date");?></label>
                <input type="text" class="form-control" name="product[start_date]" id="start_date" placeholder="<?php echo __("yyyy/mm/dd")?>" required>
                <div class="invalid-feedback">This field is REQUIRED</div>
            </div>
            <div class="form-group col-md-3">
                <label><?php echo __("Study End Date");?></label>
                <input type="text" class="form-control" name="product[end_date]" id="end_date" placeholder="<?php echo __("yyyy/mm/dd")?>" required>
                <div class="invalid-feedback">This field is REQUIRED</div>
            </div>
        </div>

        <!-- Customize Case Number -->
        <hr class="my-2 w-100">
            <h5 class="">Customize Case Number</h5>
            <div class="form-row">
                <div class="form-group col-md-2">
                    <label><?php echo __("Select Element")?></label>
                    <select class="form-control" id="casenumber1" required>
                        <option value=""><?php echo __("Select Element")?></option>
                        <option value="company"><?php echo __("Company Number")?></option>
                        <option value="product"><?php echo __("Product Number")?></option>
                        <option value="date"><?php echo __("Date")?></option>
                        <option value="random"><?php echo __("Random")?></option>
                        <option value="sequential"><?php echo __("Sequential")?></option>
                    </select>
                    <div class="invalid-feedback">This field is REQUIRED</div>
                </div>
                <div class="form-group col-md-2">
                    <label><?php echo __("Select Element")?></label>
                    <select class="form-control" id="casenumber2" required>
                        <option value=""><?php echo __("Select Element")?></option>
                        <option value="company"><?php echo __("Company Number")?></option>
                        <option value="product"><?php echo __("Product Number")?></option>
                        <option value="date"><?php echo __("Date")?></option>
                        <option value="random"><?php echo __("Random")?></option>
                        <option value="sequential"><?php echo __("Sequential")?></option>
                    </select>
                    <div class="invalid-feedback">This field is REQUIRED</div>
                </div>
                <div class="form-group col-md-2">
                    <label><?php echo __("Select Element")?></label>
                    <select class="form-control" id="casenumber3" required>
                        <option value=""><?php echo __("Select Element")?></option>
                        <option value="company"><?php echo __("Company Number")?></option>
                        <option value="product"><?php echo __("Product Number")?></option>
                        <option value="date"><?php echo __("Date")?></option>
                        <option value="random"><?php echo __("Random")?></option>
                        <option value="sequential"><?php echo __("Sequential")?></option>
                    </select>
                    <div class="invalid-feedback">This field is REQUIRED</div>
                </div>
                <div class="form-group col-md-2">
                    <label><?php echo __("Select Element")?></label>
                    <select class="form-control" id="casenumber4" required>
                        <option value=""><?php echo __("Select Element")?></option>
                        <option value="company"><?php echo __("Company Number")?></option>
                        <option value="product"><?php echo __("Product Number")?></option>
                        <option value="date"><?php echo __("Date")?></option>
                        <option value="random"><?php echo __("Random")?></option>
                        <option value="sequential"><?php echo __("Sequential")?></option>
                    </select>
                    <div class="invalid-feedback">This field is REQUIRED</div>
                </div>
                <div class="form-group col-md-2">
                    <label><?php echo __("Select Element")?></label>
                    <select class="form-control" id="casenumber5" required>
                        <option value=""><?php echo __("Select Element")?></option>
                        <option value="company"><?php echo __("Company Number")?></option>
                        <option value="product"><?php echo __("Product Number")?></option>
                        <option value="date"><?php echo __("Date")?></option>
                        <option value="random"><?php echo __("Random")?></option>
                        <option value="sequential"><?php echo __("Sequential")?></option>
                    </select>
                    <div class="invalid-feedback">This field is REQUIRED</div>
                </div>
                <input name="product[caseNo_convention]" id="caseNo_convention" value="" style="display:none">
            </div>
            <!-- <button type="button" id="genCaseNum" class="btn btn-sm btn-info">Generate</button>

            <div class="form-row justify-content-center mt-3">
                <div class="form-group col-md-6">
                    <h6><?php echo __("Case Number Format")?></h6>
                    <input type="text" class="form-control w-75 mx-auto" id="newCaseFormat" name="">
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-primary px-5">Confirm</button> -->
        <hr class="my-2 w-100">

        <!-- Hide this when triggered "Add New" -->
        <div id="assessment-workflowlist" class="mt-3">
            <div class="position-relative">
                <p class="pageTitle text-center">
                    <?php echo __("Workflow List");?>
                </p>
                <!-- Workflow List and Add New -->
                <button id="addNew-assessment-WL" type="button" class="btn btn-sm btn-outline-info position-absolute" style="right: 5px;top: 15px;"><?php echo __("Add New Workflow");?> <i class="far fa-plus-square"></i></button>
            </div>

            <table class="table table-hover mb-3" id="workflow_list">
                <thead>
                    <tr>
                        <th scope="col"><?php echo __("Workflow Name");?></th>
                        <th scope="col"><?php echo __("Description");?></th>
                        <th scope="col"><?php echo __("Call Center");?></th>
                        <th scope="col"><?php echo __("Country");?></th>
                        <th scope="col"><?php echo __("Company");?></th>
                        <th scope="col"><?php echo __("Action");?></th>
                    </tr>
                </thead>
                <tbody id="workflow_table"></tbody>
            </table>

            <!-- View Workflow List Detail Modal -->
            <div class="modal fade WFlistView" tabindex="-1" role="dialog" aria-labelledby="WFlistView" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-body m-3">
                            <h4><?php echo __("Workflow Details");?></h4>
                            <table class="table table-hover" id="ifram_view">
                                <thead>
                                    <tr>
                                        <th scope="row" class="w-25"><?php echo __("Workflow Name");?></th>
                                        <td id="viewWFname"></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row" class="w-25"><?php echo __("Call Center");?></th>
                                        <td id="viewCC"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="w-25"><?php echo __("Country");?></th>
                                        <td id="viewCountry"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="w-25"><?php echo __("Description");?></th>
                                        <td id="viewDesc"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="w-25"><?php echo __("Workflow Manager");?></th>
                                        <td id="viewMan"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="w-25"><?php echo __("Team Resources");?></th>
                                        <td id="viewRes"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <div>
                                <h4><?php echo __("Workflow Steps");?></h4>
                                <div id="view_activities"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo __("Close");?></button>
                            <!-- <button type="button" class="btn btn-primary">Send message</button> -->
                        </div>
                    </div>
                </div>
            </div>
            <div id="distribution_input" style="display:none;"></div>
            <div id="no_workflow_notice" class="alert alert-warning text-center" role="alert"><?php echo __("There is no workflow linked to this product, please add workflow first");?></div>
            <button type="submit" class="btn btn-success w-25 mt-3 mx-auto d-block"><?php echo __("Submit");?></button>
            <?= $this->Form->end() ?>

        </div>

        <!-- Show this when triggered "Add New" -->
        <!-- Choose Workflow -->
        <div id="cho-assessment-workflow" class="prodiff text-center mt-1" style="display:none;">
        <!-- Title for "Add New" -->
            <div class="jumbotron jumbotron-fluid bg-warning">
                <div class="container">
                    <h1 class="display-4"><?php echo __("Add New Workflow");?></h1>
                    <p class="lead"><?php echo __("Customize the workflow by editing the information in the section below");?></p>
                </div>
            </div>
            <!-- Choose Country  id="choosecon"-->
            <div class="prodiff text-center mt-1">
                <h3><?php echo __("Choose Country and Call Center");?></h3>
                <hr>
                <div class="form-row justify-content-md-center">

                    <div class="form-group col-md-3">
                        <label for=""><?php echo __("Select Country");?></label>
                        <select class="form-control" id="select-assessment-country" name="product_assessment_workflow[0][country]">
                        <option value=""><?php echo __("Select Country");?></option>
                        <?php
                        $country_list=[
                            'USA'=>'Unitied States',
                            'JPN'=>'Japan',
                            'CN'=>'China',
                        ];
                        foreach($assessment_workflow_structure as $workflow_structure_detail){
                            echo "<option value=".$workflow_structure_detail->country.">".$country_list[$workflow_structure_detail->country]."</option>";
                        }
                        ?>
                        </select>
                        <div id="select-assessment-country-validate" class="alert alert-danger mt-2" role="alert" style="display:none;">
                        <?php echo __("Country is REQUIRED");?>
                        </div>
                    </div>

                    <div class="form-group col-md-3">
                        <label for=""><?php echo __("Select Call Center");?></label>
                        <select class="form-control" id="callCenter" name="product_workflow[0][callCenter]">
                        <?php
                        foreach($call_ctr_companies as $k => $call_ctr_company){
                            echo "<option value=\"".$k."\">".$call_ctr_company."</option>";
                        }
                        ?>
                        </select>
                        <div id="callCenter-validate" class="alert alert-danger mt-2" role="alert" style="display:none;">
                        <?php echo __("Call Center is REQUIRED");?>
                        </div>
                    </div>
                </div>
                <button id="exit_assessment_workflow" type="button" class="btn btn-outline-warning"><?php echo __("Exit");?></button>
                <div id="submit_assessment_country" class="btn btn-primary w-25"><?php echo __("Continue");?></div>
            </div>
            <div id="choose_assessment_wf">
                <div class="row" style="min-height: 740px;">
                    <!-- Default Workflow -->
                    <div class="col" id="default_assessment_workflow_div">
                        <button type="button" id="default_assessment_btn" class="btn btn-success btn-sm workflow"><span><?php echo __("Default Workflow");?></span></button>
                        <h3 id="default_assessment_T" style="display:none;"><?php echo __("Default Workflow");?></h3>
                        <hr class="wfhr">
                        <ol class="defworkflow" id="default_assessment_workflow">
                        </ol>
                        <input type="hidden" id="default_assessment_workflow_name"/>
                        <input type="hidden" id="default_assessment_workflow_id"/>
                        <input type="hidden" id="default_assessment_workflow_description"/>
                    </div>

                    <!-- Customize Workflow -->
                    <div class="col" id="customize_assessment_workflow_div">
                        <button type="button" id="cust_assessment_btn" class="btn btn-success btn-sm workflow"><span><?php echo __("Customize Your Workflow");?></span></button>
                        <h3 id="customize_assessment_T" style="display:none;"><?php echo __("Customize Workflow");?></h3>
                        <hr class="wfhr">
                        <div class="cust-assessment-workflow" id="customize_assessment_workflow">
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <h4><?php echo __("Workflow Name");?>: </h4 >
                                    <input class="w-75 text-center" type="text" id="custom_assessment_workflow_name" value=""/>
                                    <div id="custom_assessment_workflow_name-validate" class="alert alert-danger mt-2" role="alert" style="display:none;">
                                    <?php echo __("Name is REQUIRED");?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <h5><?php echo __("Workflow Description");?></h5 >
                                    <input class="w-75 text-center" type="text" id="custom_assessment_workflow_description" value=""/>
                                    <div id="custom_assessment_workflow_description-validate" class="alert alert-danger mt-2" role="alert" style="display:none;">
                                    <?php echo __("Description is REQUIRED");?>
                                    </div>
                                </div>
                            </div>

                            <div id="errAssessmentWorkflow" class="invalid-feedback" style="display:none;"><?php echo __("Workflow name is required");?>!</div>

                            <p><?php echo __("You can customize the workflow by editing the yellow box and dragging it to anywhere in the workflow");?></p>
                            <ul>
                                <li id="draggable" class="custworkflowstep">
                                    <div class="card w-100 h-25 my-2">
                                        <div class="card-body p-3">
                                            <h5 class="card-title"><input type="text" id="new_assessment_activity_name" placeholder="Type step name here FIRST" class="font-weight-bold" /> </h5>
                                            <p class="card-text"><textarea type="text"  id="new_assessment_activity_description" class="form-control" placeholder="Type your step description here" aria-label="With textarea"></textarea></p>
                                        </div>
                                        <button id="confirm_new_assessment_activity" class="btn btn-primary w-25 mx-auto my-2" onclick="confirm_cust_activity(1)"><?php echo __("Confirm");?></button>
                                    </div>
                                </li>
                            </ul>
                            <ol id="assessment-sortable" class="cust">
                            </ol>
                        </div>
                    </div>
                </div>

                <!-- Select Permission  -->
                <div class="modal fade bd-example-modal-lg" id="selectPermission" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><?php echo __("Permission Assignment");?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="permissionSec my-4">
                            <!-- TODO LOAD STRUCTURE OF SECTIONS -->
                            <?php
                            foreach($loadTabs as $tabkey => $tab){
                                // echo "<div class=\"row\"><div class=\"col-md-12\"><h5 class=\"text-center\">".$tab['tab_name']."</h5></div></div>";
                                $exsitSectionNo = [];
                                foreach($tab['sd_sections'] as $key => $sdSection){
                                    $exsitSectionNo[$sdSection['id']] = $key;
                                }
                                renderTabs($tab['sd_sections'][0],$exsitSectionNo,$tab['sd_sections'],$tabkey);
                            }
                            ?>
                                <!-- <div class="row">
                                    <div class="col-md-12"><h5 class="text-center">General</h5></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="mx-3"><input type="checkbox" class="read" value=""> READ</label>
                                        <label class="mx-3"><input type="checkbox" class="checkAll" value=""> Check ALL</label>
                                        <hr class="my-2">
                                        <div class="checkboxContent">
                                            <label class="mx-1"><input type="checkbox" class="checkItem" value="">Admin</label>
                                            <label class="mx-1"><input type="checkbox" class="checkItem" value="">Admin</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="mx-3"><input type="checkbox" class="read" value=""> WRITE</label>
                                        <label class="mx-3"><input type="checkbox" class="checkAll" value=""> Check ALL</label>
                                        <hr class="my-2">
                                        <div class="checkboxContent">
                                            <label class="mx-1"><input type="checkbox" class="checkItem" value="">Admin</label>
                                            <label class="mx-1"><input type="checkbox" class="checkItem" value="">Admin</label>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" id="permissionFooter">
                    </div>
                    </div>
                </div>
                </div>

                <div class="d-block mt-3">
                    <button id="undocho-assessment-con" type="button" class="btn btn-outline-warning" style="display:none;"><?php echo __("Go back to last step");?></button>
                    <button id="confirm_assessment_activities" class="btn btn-primary w-25" style="display:none;"><?php echo __("Continue");?></button>
                    <button id="undo_assessment_activities" type="button" class="btn btn-outline-warning" style="display:none;"><?php echo __("Go back to last step");?></button>
                    <button id="submit_assessment_workflow" class="btn btn-primary w-25" style="display:none;"><?php echo __("Continue");?></button>
                </div>
            </div>
        </div>

        <!-- Add CROs -->
        <div id="choose-assessment-company" class="prodiff text-center" style="display:none">
            <h3 class="mt-2"></h3>
            <hr>
            <p class="card-text"><?php echo __("Add the Resources here and assign personnels");?></p>
            <button type="button" class="btn btn-outline-info w-25 mx-auto mb-3" data-toggle="modal" data-target="#assessment-addcromodal"><?php echo __("Add Resources");?></button>
            <div class="modal fade" id="assessment-addcromodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><?php echo __("Add Companies");?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label for=""><?php echo __("Add Resources");?></label>
                        <select class="custom-select" id="assessment-croname">
                        <?php
                            foreach($cro_companies as $k => $cro_company){
                                echo "<option value=\"".$k."\">".$cro_company."</option>";
                            }
                        ?>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button id="assessment-croadd"  class="btn btn-primary"  data-dismiss="modal"><?php echo __("Add");?></button>
                    </div>
                    </div>
                </div>
            </div>

            <!-- <div id="addcroarea" class="btn-group-vertical w-25">
                <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target=".bd-example-modal-lg">A CRO</button>
                <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target=".bd-example-modal-lg">B CRO</button>
                <button type="button" class="btn btn-outline-primary" id ="0" data-toggle="modal" data-target=".bd-example-modal-lg">C CRO</button>
            </div> -->

            <div class="modal fade bd-example-modal-lg" id="addper" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><?php echo __("Assign Personnels");?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p><?php echo __("Assign people as manager or team members");?>.</p>
                            <!-- Choose Personnels -->
                            <div class="card bg-light mb-3 float-left personnelarea">
                                <div class="card-header"><?php echo __("Candidates of Team Resources");?></div>
                                <div id="personnelDraggable" class="card-body p-2">
                                </div>
                            </div>

                            <!-- Droppable Area -->
                            <div id="dropZone" class="card bg-light mx-3 mb-3 float-right">
                                <div class="card-header"><?php echo __("Drag Candidates Here for Assignment");?></div>
                                <div class="stack border-success">
                                    <div class="stackHdr"><?php echo __("Assign as workflow manager");?></div>
                                    <div class="stackDrop1" id="workflow_manager-add"></div>
                                </div>
                                <div class="stack border-info">
                                    <div class="stackHdr"><?php echo __("Assign as team resources");?></div>
                                    <div class="stackDrop2" id="team_resources-add"></div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button id="conass-assessment" class="btn btn-outline-success" type="submit" data-dismiss="modal"><?php echo __("Confirm Assignment");?></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-block mt-3">
                <!-- CROs Resources List -->
                <h3 class="mt-3"><?php echo __("CROs Resources List");?></h3>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col"><?php echo __("CRO Company");?></th>
                            <th scope="col"><?php echo __("Workflow Manager");?></th>
                            <th scope="col"><?php echo __("Team Resources");?></th>
                            <th scope="col"><?php echo __("Actions");?></th>
                        </tr>
                    </thead>
                    <tbody id="assessment-crotable">
                    </tbody>
                </table>
                <button id="undocho-assessment-WF" type="button" class="btn btn-outline-warning mt-3"><?php echo __("Reselect Workflow");?></button>
                <button id="confirm-assessment-WFlist" type="button" class="btn btn-primary w-25 mt-3 mx-auto"><?php echo __("Continue");?></button>
            </div>
        <!-- Choose Distribution -->
        <div id="distribution-workflowlist" class="prodiff text-center" style="display:none;">
            <h3><?php echo __("Choose Distribution");?></h3>
            <hr>
            <div id="distriList"></div>
            <!-- <div id="newDistri-0">
                <div class="form-group col-md-3 d-inline-block">
                    <label for="">Select Country</label>
                    <select class="form-control" id="" name="">
                        <option value="">Select Country</option>
                        <option value="USA">Unitied States</option>
                        <option value="JPN">Japan</option>
                        <option value="EU">Europe</option>
                    </select>
                </div>
                <div id="defDistri" class="my-2">
                    <button type="button" id="defDistriBtn-0" class="btn btn-success workflow w-25 defDistriBtn"><span>Default Distribution</span></button>
                    <div class="defDistriContent" style="display:none;">
                        <div class="d-flex justify-content-center">
                            <div class="card m-2" style="width: 18rem;">
                                <div class="card-body">
                                    <h5 class="card-title">Generate Report</h5>
                                    <p class="card-text">Output a report from system</p>
                                </div>
                            </div>
                            <div class="card m-2" style="width: 18rem;">
                                <div class="card-body">
                                    <h5 class="card-title">Submission</h5>
                                    <p class="card-text">Submit report to regulator</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="custDistri" class="my-2">
                    <button type="button" id="custDistriBtn-0" class="btn btn-success workflow w-25 custDistriBtn"><span>Customize Distribution</span></button>
                    <div class="custDistriContent" class="my-3" style="display:none;">
                        <div class="addnNewDistriContent">
                            <div class="d-flex justify-content-center">
                                <div class="card m-2" style="width: 18rem;">
                                    <div class="card-body">
                                        <h5 class="card-title">Generate Report</h5>
                                        <p class="card-text">Output a report from system</p>
                                    </div>
                                </div>
                                <div class="card m-2" style="width: 18rem;">
                                    <div class="card-body">
                                        <h5 class="card-title">Submission</h5>
                                        <p class="card-text">Submit report to regulator</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
            </div> -->
            <div class="newDistrictArea"></div>
            <button id="addNew-distribution-WL" type="button" class="btn btn-sm btn-outline-primary float-left"><i class="fas fa-plus"></i><?php echo __("Add New");?></button>
            <button id="backDistri" class="btn btn-outline-warning w-25"><?php echo __("Last Step");?></button>
            <button id="submitDistri" class="btn btn-primary w-25"><?php echo __("Continue");?></button>
        </div>
        <div id="cho-distribution-workflow" class="prodiff text-center mt-1" style="display:none;">
        <!-- Title for "Add New" -->
            <div class="jumbotron jumbotron-fluid bg-warning">
                <div class="container">
                    <h1 class="display-4"><?php echo __("Add New Distribution Workflow");?></h1>
                </div>
            </div>
            <!-- Choose Country  id="choosecon"-->
            <div class="prodiff text-center mt-1">
                <h3><?php echo __("Choose Country and Call Center");?></h3>
                <hr>
                <div class="form-row justify-content-md-center">

                    <div class="form-group col-md-3">
                        <label for=""><?php echo __("Select Country");?></label>
                        <select class="form-control" id="select-distribution-country" name="product_distribution_workflow[0][country]">
                        <option value=""><?php echo __("Select Country");?></option>
                        <?php
                        foreach($distribution_workflow_structure as $workflow_structure_detail){
                            echo "<option value=".$workflow_structure_detail->country.">".$country_list[$workflow_structure_detail->country]."</option>";
                        }
                        ?>
                        </select>
                        <div id="select-distribution-country-validate" class="alert alert-danger mt-2" role="alert" style="display:none;">
                        <?php echo __("Country is REQUIRED");?>
                        </div>
                    </div>
                </div>
                <button id="exit_distribution_workflow" type="button" class="btn btn-outline-warning"><?php echo __("Exit");?></button>
                <div id="submit_distribution_country" class="btn btn-primary w-25"><?php echo __("Continue");?></div>
            </div>
            <div id="choose_distribution_wf">
                <div class="row" style="min-height: 740px;">
                    <!-- Default Workflow -->
                    <div class="col" id="default_distribution_workflow_div">
                        <button type="button" id="default_distribution_btn" class="btn btn-success btn-sm workflow"><span><?php echo __("Default Distribution Workflow");?></span></button>
                        <h3 id="default_distribution_T" style="display:none;"><?php echo __("Default Workflow");?></h3>
                        <hr class="wfhr">
                        <ol id="default_distribution_workflow" class="defworkflow">
                        </ol>
                        <input type="hidden" id="default_distribution_workflow_name"/>
                        <input type="hidden" id="default_distribution_workflow_id"/>
                        <input type="hidden" id="default_distribution_workflow_description"/>
                    </div>

                    <!-- Customize Workflow -->
                    <div class="col" id="customize_distribution_workflow_div">
                        <button type="button" id="cust_distribution_btn" class="btn btn-success btn-sm workflow"><span><?php echo __("Customize Your Workflow");?></span></button>
                        <h3 id="customize_distribution_T" style="display:none;"><?php echo __("Customize Workflow");?></h3>
                        <hr class="wfhr">
                        <div class="custworkflow" id="customize_distribution_workflow">
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <h4><?php echo __("Workflow Name");?>: </h4 >
                                    <input class="w-75 text-center" type="text" id="custom_distribution_workflow_name" value=""/>
                                    <div id="custom_distribution_workflow_name-validate" class="alert alert-danger mt-2" role="alert" style="display:none;">
                                    <?php echo __("Name is REQUIRED");?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <h5><?php echo __("Workflow Description");?> </h5 >
                                    <input class="w-75 text-center" type="text" id="custom_distribution_workflow_description" value=""/>
                                    <div id="custom_distribution_workflow_description-validate" class="alert alert-danger mt-2" role="alert" style="display:none;">
                                    <?php echo __("Description is REQUIRED");?>
                                    </div>
                                </div>
                            </div>

                            <div id="errDistributionWorkflow" class="invalid-feedback" style="display:none;"><?php echo __("Workflow name is required!");?></div>

                            <p><?php echo __("You can customize the workflow by editing the yellow box and dragging it to anywhere in the workflow");?></p>
                            <ul>
                                <li id="draggable" class="custworkflowstep">
                                    <div class="card w-100 h-25 my-2">
                                        <div class="card-body p-3">
                                            <h5 class="card-title"><input type="text" id="new_distribution_activity_name" placeholder="Type step name here FIRST" class="font-weight-bold" /> </h5>
                                            <p class="card-text"><textarea type="text"  id="new_distribution_activity_description" class="form-control" placeholder="Type your step description here" aria-label="With textarea"></textarea></p>
                                        </div>
                                        <button id="confirm_new_distribution_activity" class="btn btn-primary w-25 mx-auto my-2" onclick="confirm_cust_activity(0)"><?php echo __("Confirm");?></button>
                                    </div>
                                </li>
                            </ul>
                            <ol id="distribution-sortable" class="cust">
                            </ol>
                        </div>
                    </div>
                </div>

                <div class="d-block mt-3">
                    <button id="undocho-distribution-con" type="button" class="btn btn-outline-warning" style="display:none;"><?php echo __("Go back to last step");?></button>
                    <button id="confirm_distribution_activities" class="btn btn-primary w-25" style="display:none;"><?php echo __("Continue");?></button>
                    <button id="undo_distribution_activities" type="button" class="btn btn-outline-warning" style="display:none;"><?php echo __("Go back to last step");?></button>
                    <button id="submit_distribution_workflow" class="btn btn-primary w-25" style="display:none;"><?php echo __("Continue");?></button>
                </div>
            </div>
        </div>

        <!-- Add CROs -->
        <div id="choose-distribution-company" class="prodiff text-center" style="display:none">
            <h3 class="mt-2"><?php echo __("Team Members");?></h3>
            <hr>
            <p class="card-text"><?php echo __("Add the Resources here and assign personnels");?></p>
            <button type="button" class="btn btn-outline-info w-25 mx-auto mb-3" data-toggle="modal" data-target="#distribution-addcromodal"><?php echo __("Add Resources");?></button>
            <div class="modal fade" id="distribution-addcromodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><?php echo __("Add Companies");?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label for=""><?php echo __("Add Resources");?></label>
                        <select class="custom-select" id="distribution-croname">
                        <?php
                            foreach($cro_companies as $k => $cro_company){
                                echo "<option value=\"".$k."\">".$cro_company."</option>";
                            }
                        ?>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button id="distribution-croadd"  class="btn btn-primary"  data-dismiss="modal"><?php echo __("Add");?></button>
                    </div>
                    </div>
                </div>
            </div>

            <!-- <div id="addcroarea" class="btn-group-vertical w-25">
                <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target=".bd-example-modal-lg">A CRO</button>
                <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target=".bd-example-modal-lg">B CRO</button>
                <button type="button" class="btn btn-outline-primary" id ="0" data-toggle="modal" data-target=".bd-example-modal-lg">C CRO</button>
            </div> -->

            <div class="modal fade bd-example-modal-lg" id="addper" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><?php echo __("Assign Personnels");?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p><?php echo __("Assign people as manager or team members");?>.</p>
                            <!-- Choose Personnels -->
                            <div class="card bg-light mb-3 float-left personnelarea">
                                <div class="card-header"><?php echo __("Candidates of Team Resources");?></div>
                                <div id="personnelDraggable" class="card-body p-2">
                                </div>
                            </div>

                            <!-- Droppable Area -->
                            <div id="dropZone" class="card bg-light mx-3 mb-3 float-right">
                                <div class="card-header"><?php echo __("ContiDrag Candidates Here for Assignmentnue");?></div>
                                <div class="stack border-success">
                                    <div class="stackHdr"><?php echo __("Assign as workflow manager");?></div>
                                    <div class="stackDrop1" id="workflow_manager-add"></div>
                                </div>
                                <div class="stack border-info">
                                    <div class="stackHdr"><?php echo __("Assign as team resources");?></div>
                                    <div class="stackDrop2" id="team_resources-add"></div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button id="conass-distribution" class="btn btn-outline-success" type="submit" data-dismiss="modal"><?php echo __("Confirm Assignment");?></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-block mt-3">
                <!-- CROs Resources List -->
                <h3 class="mt-3"><?php echo __("CROs Resources List");?></h3>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col"><?php echo __("CRO Company");?></th>
                            <th scope="col"><?php echo __("Workflow Manager");?></th>
                            <th scope="col"><?php echo __("Team Resources");?></th>
                            <th scope="col"><?php echo __("Actions");?></th>
                        </tr>
                    </thead>
                    <tbody id="distribution-crotable">
                    </tbody>
                </table>
                <button id="undocho-distribution-WF" type="button" class="btn btn-outline-warning mt-3"><?php echo __("Reselect Workflow");?></button>
                <button id="confirm-distribution-WFlist" type="button" class="btn btn-primary w-25 mt-3 mx-auto"><?php echo __("Continue");?></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
var assessment_workflow_structure = <?php echo json_encode($assessment_workflow_structure);?>;
var distribution_workflow_structure = <?php echo json_encode($distribution_workflow_structure);?>;
var loadTabs = <?php echo json_encode($loadTabs);?>;
var cro_companies = <?php echo json_encode($cro_companies);?>;
var call_center_list = <?php echo json_encode($call_ctr_companies);?>;

// Example starter JavaScript for disabling form submissions if there are invalid fields
(function() {
  'use strict';
  window.addEventListener('load', function() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();
</script>
<?php function renderTabs($sections, $exsitList, $sdsections,$tabkey){
    if(!array_key_exists($sections['id'], $exsitList)||$exsitList[$sections['id']]==="")
        return null;
    $sectionKey = $sections['id'];
    echo "<div class=\"row text-left panel-heading\" id=\"section-".$sections['id']."\"><div class=\"col-md-12 \">";
    echo "<span class=\"panel-title\">";
    echo "<a data-toggle=\"collapse\" class=\"collapsed\" href=\"#collapse-".$sections['id']."\">".$sections['section_name'];
    echo "</a></span>";
    echo "<label class=\"mx-1\" for=\"write-".$tabkey."-".$sectionKey."\"><input type=\"checkbox\" id=\"write-".$tabkey."-".$sectionKey."\" class=\"checkItem\" value=\"1\">Write</label>";
    echo "<label class=\"mx-1\" for=\"read-".$tabkey."-".$sectionKey."\"><input type=\"checkbox\" id=\"read-".$tabkey."-".$sectionKey."\" class=\"checkItem\" value=\"2\">Read</label>";
    echo "</div>";
    echo "<div class=\"panel-collapse collapse in \" style='margin-left:40px;' id=\"collapse-".$sections['id']."\";>";
    $exsitList[$sections['id']]='';
    if($sections['child_section']!=''){
        $child_sections = explode(',', $sections['child_section']);
        foreach($child_sections as $child_section => $child_section_id){
            if($sdsections[$exsitList[$child_section_id]]!="undefined")
                renderTabs($sdsections[$exsitList[$child_section_id]], $exsitList, $sdsections, $tabkey);
        }
    }
    echo "</div>";
    echo "</div>";
}
?>