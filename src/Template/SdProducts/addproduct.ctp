<?php
//debug($sdProductTypes);
?>
<title>Add Product</title>
<head>
<?= $this->Html->script('product/addproduct.js') ?>
<head>
<script type="text/javascript">
    var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
</script>
<div class="container">

    <div class="row my-3">
        <div class="col">
            <div class="card">
                <div class="card-header text-center">
                    <h3>Add Product</h3>
                </div>
                <div class="prodiff card-body">
                    <div class="text-center">
                        <!-- Add Product -->
                        <span id="errorMsg" class="alert alert-danger" role="alert" style="display:none"></span>
                        <?= $this->Form->create();?>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label>Product Name (B.4.k.2.1)</label>
                                <a tabindex="0" role="button" data-toggle="popover" title="" data-original-title="Proprietary medicinal product name (B.4.k.2.1)" data-content="<div>The name should be that used by the reporter. It is recognized that a single product may have different proprietary names in different countries, even when produced by a single manufacturer.</div>" ><i class="qco fas fa-info-circle"></i></a>
                                <input type="text" class="form-control" id="product_name" name="product[product_name]" placeholder="Proprietary Medicinal Product Name" required oninvalid="this.setCustomValidity('Product Name is REQUIRED')" oninput="this.setCustomValidity('')">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Product Type:</label>
                                <div class="option_group">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="drug_type" value="1" name="case[product_type]" class="custom-control-input">
                                        <label for="drug_type" class="custom-control-label">Drug<label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="vaccine_type" value="2" name="case[product_type]" class="custom-control-input">
                                        <label for="vaccine_type" class="custom-control-label">Vaccine<label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="device_type" value="3" name="case[product_type]" class="custom-control-input">
                                        <label for="device_type" class="custom-control-label">Device<label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="combination_type" value="4" name="case[product_type]" class="custom-control-input">
                                        <label for="combination_type" class="custom-control-label">Combination<label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Mfr. name</label>
                                <input type="text" class="form-control" id="mfr_name" name="product[mfr_name]" placeholder="Mfr. name">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Drug Role / Product flag (B.4.k.1)</label>
                                <a tabindex="0" role="button" data-toggle="popover" title="" data-original-title="Characterization of drug role (B.4.k.1)" data-content="<div>Characterization of the drug as provided by primary reporter. All spontaneous reports should have at least one suspect drug. If the reporter indicates a suspected interaction, interacting should be selected. All interacting drugs are considered to be suspect drugs.</div>" ><i class="qco fas fa-info-circle"></i></a>
                                <select class="form-control" id="sd_product_flag" name="product[sd_product_flag]" required oninvalid="this.setCustomValidity('Product flag is REQUIRED')" oninput="this.setCustomValidity('')">
                                    <option value="">Select Characterization of Drug Role</option>
                                    <option value="1">Suspect</option>
                                    <option value="2">Concomitant</option>
                                    <option value="3">Interacting</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Product Status</label>
                                <select class="form-control" id="status" name="product[status]" required oninvalid="this.setCustomValidity('Status is REQUIRED')" oninput="this.setCustomValidity('')">
                                    <option value="">Select Product Status</option>
                                    <option value="1">Active</option>
                                    <option value="2">Close</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Blinding Method</label>
                                <select class="form-control" id="blinding_tech" name="product[blinding_tech]">
                                    <option value="">Select Blinding Method</option>
                                    <option value="1">Single blind</option>
                                    <option value="2">Double blind</option>
                                    <option value="3">Open-label</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label>WHO-DD Browser</label>
                                <div style="margin-left: 47px;">
                                    <?php
                                    $whodraCell = $this->cell('Whodd');
                                    echo $whodraCell;?>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label>WHO-DD Code</label>
                                <input type="text" readonly="readonly" class="form-control" id="whodracode" name="product[WHODD_code]" placeholder="WHO-DD Code">
                            </div>
                            <div class="form-group col-md-3">
                                <label>WHO-DD Name</label>
                                <input type="text" readonly="readonly" class="form-control" id="whodraname" name="product[WHODD_name]" placeholder="WHO-DD Name">
                            </div>
                            <div class="form-group col-md-3">
                                <label>WHO-DD Preferred Name</label>
                                <input type="text" class="form-control" id="WHODD_decode" name="product[WHODD_decode]" placeholder="WHO-DD Preferred Name">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label>Study Name (A.2.3.1)</label>
                                <input type="text" class="form-control" id="study_name" name="product[study_name]" placeholder="Study Name">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Sponsor Study Number (A.2.3.2)</label>
                                <a tabindex="0" role="button" data-toggle="popover" title="" data-original-title="Sponsor Study Number (A.2.3.2)" data-content="<div>This section would be completed only if the sender is the study sponsor or has been informed of the study number by the sponsor.</div>" ><i class="qco fas fa-info-circle"></i></a>
                                <input type="text" class="form-control" id="study_no" name="product[study_no]" placeholder="Study Number">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Study Type (A.2.3.3)</label>
                                <a tabindex="0" role="button" data-toggle="popover" title="" data-original-title="Study type in which the reactions or events were observed (A.2.3.3)" data-content="<div><ol>Clinical trials</ol><ol>Individual patient use; (e.g., compassionate use or named patient basis)</ol><ol>Other studies (e.g., pharmacoepidemiology, pharmacoeconomics, intensive monitoring, PMS)</ol></div>" ><i class="qco fas fa-info-circle"></i></a>
                                <select class="form-control" id="sd_study_type_id" name="product[study_type]" required oninvalid="this.setCustomValidity('Study Type is REQUIRED')" oninput="this.setCustomValidity('')">
                                    <option value="">Select Study Type</option>
                                    <option value="1">Clinical trials</option>
                                    <option value="2">Individual patient use</option>
                                    <option value="3">Other studies</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Study Start Date</label>
                                <input type="text" class="form-control" name="product[start_date]" id="start_date">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Study End Date</label>
                                <input type="text" class="form-control" name="product[end_date]" id="end_date">
                            </div>
                        </div>


                        <!-- Workflow List and Add New -->
                        <button id="addNew-accessment-WL" type="button" class="btn btn-outline-info float-right">Add New Workflow <i class="far fa-plus-square"></i></button>

                        <!-- Hide this when triggered "Add New" -->
                        <div id="accessment-workflowlist" class="mt-3">
                            <h3>Workflow List</h3>

                            <table class="table table-hover mb-3" id="workflow_list">
                                <thead>
                                    <tr>
                                        <th scope="col">Workflow Name</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Call Center</th>
                                        <th scope="col">Country</th>
                                        <th scope="col">Company</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="workflow_table"></tbody>
                            </table>

                            <!-- View Workflow List Detail Modal -->
                            <div class="modal fade WFlistView" tabindex="-1" role="dialog" aria-labelledby="WFlistView" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-body m-3">
                                            <h4>Workflow Details</h4>
                                            <table class="table table-hover" id="ifram_view">
                                                <thead>
                                                    <tr>
                                                        <th scope="row" class="w-25">Workflow Name</th>
                                                        <td id="viewWFname"></td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <th scope="row" class="w-25">Call Center</th>
                                                        <td id="viewCC"></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row" class="w-25">Country</th>
                                                        <td id="viewCountry"></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row" class="w-25">Description</th>
                                                        <td id="viewDesc"></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row" class="w-25">Workflow Manager</th>
                                                        <td id="viewMan"></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row" class="w-25">Team Resources</th>
                                                        <td id="viewRes"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <div>
                                                <h4>Workflow Steps</h4>
                                                <div id="view_activities"></div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <!-- <button type="button" class="btn btn-primary">Send message</button> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="distribution_input" style="display:none;"></div>
                            <div id="no_workflow_notice"><h3>There is no workflow linked to this product, please add workflow first;</h3></div>
                            <input type="submit" class="btn btn-success w-25 mt-3 mx-auto">
                            <?= $this->Form->end() ?>

                        </div>

                        <!-- Show this when triggered "Add New" -->
                        <!-- Choose Workflow -->
                        <div id="cho-accessment-workflow" class="prodiff text-center mt-1" style="display:none;">
                        <!-- Title for "Add New" -->
                            <div class="jumbotron jumbotron-fluid bg-warning">
                                <div class="container">
                                    <h1 class="display-4">Add New Workflow</h1>
                                    <p class="lead">You can edit the specific info in the following steps</p>
                                </div>
                            </div>
                            <!-- Choose Country  id="choosecon"-->
                            <div class="prodiff text-center mt-1">
                                <h3>Choose Country and Call Center</h3>
                                <hr>
                                <div class="form-row justify-content-md-center">

                                    <div class="form-group col-md-3">
                                        <label for="">Select Country</label>
                                        <select class="form-control" id="select-accessment-country" name="product_accessment_workflow[0][country]">
                                        <option value="">Select Country</option>
                                        <?php
                                        $country_list=[
                                            'USA'=>'Unitied States',
                                            'JPN'=>'Japan',
                                            'CHN'=>'China'
                                        ];
                                        foreach($accessment_workflow_structure as $workflow_structure_detail){
                                            echo "<option value=".$workflow_structure_detail->country.">".$country_list[$workflow_structure_detail->country]."</option>";
                                        }
                                        ?>
                                        </select>
                                        <div id="select-accessment-country-validate" class="alert alert-danger mt-2" role="alert" style="display:none;">
                                            Country is REQUIRED
                                        </div>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="">Select Call Center</label>
                                        <select class="form-control" id="callCenter" name="product_workflow[0][callCenter]">
                                        <?php 
                                        foreach($call_ctr_companies as $k => $call_ctr_company){
                                            echo "<option value=\"".$k."\">".$call_ctr_company."</option>";
                                        }
                                        ?>
                                        </select>
                                        <div id="callCenter-validate" class="alert alert-danger mt-2" role="alert" style="display:none;">
                                            Call Center is REQUIRED
                                        </div>
                                    </div>
                                </div>
                                <button id="exit_accessment_workflow" type="button" class="btn btn-outline-warning">Exit</button>
                                <div id="submit_accessment_country" class="btn btn-primary w-25">Continue</div>
                            </div>
                            <div id="choose_accessment_wf">
                                <div class="row" style="min-height: 740px;">
                                    <!-- Default Workflow -->
                                    <div class="col" id="default_accessment_workflow_div">
                                        <button type="button" id="default_accessment_btn" class="btn btn-success btn-sm workflow"><span>Default Workflow</span></button>
                                        <h3 id="default_accessment_T" style="display:none;">Default Workflow</h3>
                                        <hr class="wfhr">
                                        <ol class="defworkflow" id="default_accessment_workflow">
                                        </ol>
                                        <input type="hidden" id="default_accessment_workflow_name"/>
                                        <input type="hidden" id="default_accessment_workflow_id"/>
                                        <input type="hidden" id="default_accessment_workflow_description"/>
                                    </div>

                                    <!-- Customize Workflow -->
                                    <div class="col" id="customize_accessment_workflow_div">
                                        <button type="button" id="cust_accessment_btn" class="btn btn-success btn-sm workflow"><span>Customize Your Workflow</span></button>
                                        <h3 id="customize_accessment_T" style="display:none;">Customize Workflow</h3>
                                        <hr class="wfhr">
                                        <div class="cust-accessment-workflow" id="customize_accessment_workflow">
                                            <div class="form-row">
                                                <div class="form-group col-md-12">
                                                    <h4>Workflow Name: </h4 >
                                                    <input class="w-75 text-center" type="text" id="custom_accessment_workflow_name" value=""/>
                                                    <div id="custom_accessment_workflow_name-validate" class="alert alert-danger mt-2" role="alert" style="display:none;">
                                                        Name is REQUIRED
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-12">
                                                    <h5>Workflow Description </h5 >
                                                    <input class="w-75 text-center" type="text" id="custom_accessment_workflow_description" value=""/>
                                                    <div id="custom_accessment_workflow_description-validate" class="alert alert-danger mt-2" role="alert" style="display:none;">
                                                        Description is REQUIRED
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="errAccessmentWorkflow" class="invalid-feedback" style="display:none;">Workflow name is required!</div>

                                            <p>You can edit the workflow here and please drag the yellow box to anywhere in the workflow for customization</p>
                                            <ul>
                                                <li id="draggable" class="custworkflowstep">
                                                    <div class="card w-100 h-25 my-2">
                                                        <div class="card-body p-3">
                                                            <h5 class="card-title"><input type="text" id="new_accessment_activity_name" placeholder="Type step name here FIRST" class="font-weight-bold" /> </h5>
                                                            <p class="card-text"><textarea type="text"  id="new_accessment_activity_description" class="form-control" placeholder="Type your step description here" aria-label="With textarea"></textarea></p>
                                                        </div>
                                                        <button id="confirm_new_accessment_activity" class="btn btn-primary w-25 mx-auto my-2" onclick="confirm_cust_activity()">Confirm</button>
                                                    </div>
                                                </li>
                                            </ul>
                                            <ol id="accessment-sortable" class="cust">
                                            </ol>
                                        </div>
                                    </div>
                                </div>

                                <!-- Select Permission  -->
                                <div class="modal fade bd-example-modal-lg" id="selectPermission" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Permission Assignment</h5>
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
                                    <button id="undocho-accessment-con" type="button" class="btn btn-outline-warning" style="display:none;">Go back to last step</button>
                                    <button id="confirm_accessment_activities" class="btn btn-primary w-25" style="display:none;">Continue</button>
                                    <button id="undo_accessment_activities" type="button" class="btn btn-outline-warning" style="display:none;">Go back to last step</button>
                                    <button id="submit_accessment_workflow" class="btn btn-primary w-25" style="display:none;">Continue</button>
                                </div>
                            </div>
                        </div>

                        <!-- Add CROs -->
                        <div id="choose-accessment-company" class="prodiff text-center" style="display:none">
                            <h3 class="mt-2">Team Members</h3>
                            <hr>
                            <p class="card-text">Add the Resources here and assign personnels</p>
                            <button type="button" class="btn btn-outline-info w-25 mx-auto mb-3" data-toggle="modal" data-target="#accessment-addcromodal">Add Resources</button>
                            <div class="modal fade" id="accessment-addcromodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Add Companies</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <label for="">Add Resources</label>
                                        <select class="custom-select" id="accessment-croname">
                                        <?php 
                                            foreach($cro_companies as $k => $cro_company){
                                                echo "<option value=\"".$k."\">".$cro_company."</option>";
                                            }
                                        ?>
                                        </select>
                                    </div>
                                    <div class="modal-footer">
                                        <button id="accessment-croadd"  class="btn btn-primary"  data-dismiss="modal">ADD</button>
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
                                            <h5 class="modal-title">Assign Personnels</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Assign people as manager or team members.</p>
                                            <!-- Choose Personnels -->
                                            <div class="card bg-light mb-3 float-left personnelarea">
                                                <div class="card-header">Candidates of Team Resources</div>
                                                <div id="personnelDraggable" class="card-body p-2">
                                                </div>
                                            </div>

                                            <!-- Droppable Area -->
                                            <div id="dropZone" class="card bg-light mx-3 mb-3 float-right">
                                                <div class="card-header">Drag Candidates Here for Assignment</div>
                                                <div class="stack border-success">
                                                    <div class="stackHdr">Assign as workflow manager</div>
                                                    <div class="stackDrop1" id="workflow_manager-add"></div>
                                                </div>
                                                <div class="stack border-info">
                                                    <div class="stackHdr">Assign as team resources</div>
                                                    <div class="stackDrop2" id="team_resources-add"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button id="conass-accessment" class="btn btn-outline-success" type="submit" data-dismiss="modal">Confirm Assignment</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-block mt-3">
                                <!-- CROs Resources List -->
                                <h3 class="mt-3">CROs Resources List</h3>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">CRO Company</th>
                                            <th scope="col">Workflow Manager</th>
                                            <th scope="col">Team Resources</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="accessment-crotable">
                                    </tbody>
                                </table>
                                <button id="undocho-accessment-WF" type="button" class="btn btn-outline-warning mt-3">Reselect Workflow</button>
                                <button id="confirm-accessment-WFlist" type="button" class="btn btn-primary w-25 mt-3 mx-auto">Continue</button>
                            </div>
                        <!-- Choose Distribution -->
                        <div id="distribution-workflowlist" class="prodiff text-center" style="display:none;">
                            <h3>Choose Distribution</h3>
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
                            <button id="addNew-distribution-WL" type="button" class="btn btn-sm btn-outline-primary float-left"><i class="fas fa-plus"></i> Add New</button>
                            <button id="backDistri" class="btn btn-outline-warning w-25">Last Step</button>
                            <button id="submitDistri" class="btn btn-primary w-25">Continue</button>
                        </div>
                        <div id="cho-distribution-workflow" class="prodiff text-center mt-1" style="display:none;">
                        <!-- Title for "Add New" -->
                            <div class="jumbotron jumbotron-fluid bg-warning">
                                <div class="container">
                                    <h1 class="display-4">Add New Distribution Workflow</h1>
                                </div>
                            </div>
                            <!-- Choose Country  id="choosecon"-->
                            <div class="prodiff text-center mt-1">
                                <h3>Choose Country and Call Center</h3>
                                <hr>
                                <div class="form-row justify-content-md-center">

                                    <div class="form-group col-md-3">
                                        <label for="">Select Country</label>
                                        <select class="form-control" id="select-distribution-country" name="product_distribution_workflow[0][country]">
                                        <option value="">Select Country</option>
                                        <?php
                                        foreach($distribution_workflow_structure as $workflow_structure_detail){
                                            echo "<option value=".$workflow_structure_detail->country.">".$country_list[$workflow_structure_detail->country]."</option>";
                                        }
                                        ?>
                                        </select>
                                        <div id="select-distribution-country-validate" class="alert alert-danger mt-2" role="alert" style="display:none;">
                                            Country is REQUIRED
                                        </div>
                                    </div>
                                </div>
                                <button id="exit_distribution_workflow" type="button" class="btn btn-outline-warning">Exit</button>
                                <div id="submit_distribution_country" class="btn btn-primary w-25">Continue</div>
                            </div>
                            <div id="choose_distribution_wf">
                                <div class="row" style="min-height: 740px;">
                                    <!-- Default Workflow -->
                                    <div class="col" id="default_distribution_workflow_div">
                                        <button type="button" id="default_distribution_btn" class="btn btn-success btn-sm workflow"><span>Default Distribution Workflow</span></button>
                                        <h3 id="default_distribution_T" style="display:none;">Default Workflow</h3>
                                        <hr class="wfhr">
                                        <ol id="default_distribution_workflow" class="defworkflow">
                                        </ol>
                                        <input type="hidden" id="default_distribution_workflow_name"/>
                                        <input type="hidden" id="default_distribution_workflow_id"/>
                                        <input type="hidden" id="default_distribution_workflow_description"/>
                                    </div>

                                    <!-- Customize Workflow -->
                                    <div class="col" id="customize_distribution_workflow_div">
                                        <button type="button" id="cust_distribution_btn" class="btn btn-success btn-sm workflow"><span>Customize Your Workflow</span></button>
                                        <h3 id="customize_distribution_T" style="display:none;">Customize Workflow</h3>
                                        <hr class="wfhr">
                                        <div class="custworkflow" id="customize_distribution_workflow">
                                            <div class="form-row">
                                                <div class="form-group col-md-12">
                                                    <h4>Workflow Name: </h4 >
                                                    <input class="w-75 text-center" type="text" id="custom_distribution_workflow_name" value=""/>
                                                    <div id="custom_distribution_workflow_name-validate" class="alert alert-danger mt-2" role="alert" style="display:none;">
                                                        Name is REQUIRED
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-12">
                                                    <h5>Workflow Description </h5 >
                                                    <input class="w-75 text-center" type="text" id="custom_distribution_workflow_description" value=""/>
                                                    <div id="custom_distribution_workflow_description-validate" class="alert alert-danger mt-2" role="alert" style="display:none;">
                                                        Description is REQUIRED
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="errAccessmentWorkflow" class="invalid-feedback" style="display:none;">Workflow name is required!</div>

                                            <p>You can edit the workflow here and please drag the yellow box to anywhere in the workflow for customization</p>
                                            <ul>
                                                <li id="draggable" class="custworkflowstep">
                                                    <div class="card w-100 h-25 my-2">
                                                        <div class="card-body p-3">
                                                            <h5 class="card-title"><input type="text" id="new_distribution_activity_name" placeholder="Type step name here FIRST" class="font-weight-bold" /> </h5>
                                                            <p class="card-text"><textarea type="text"  id="new_distribution_activity_description" class="form-control" placeholder="Type your step description here" aria-label="With textarea"></textarea></p>
                                                        </div>
                                                        <button id="confirm_new_distribution_activity" class="btn btn-primary w-25 mx-auto my-2" onclick="confirm_cust_activity(1)">Confirm</button>
                                                    </div>
                                                </li>
                                            </ul>
                                            <ol id="distribution-sortable" class="cust">
                                            </ol>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-block mt-3">
                                    <button id="undocho-distribution-con" type="button" class="btn btn-outline-warning" style="display:none;">Go back to last step</button>
                                    <button id="confirm_distribution_activities" class="btn btn-primary w-25" style="display:none;">Continue</button>
                                    <button id="undo_distribution_activities" type="button" class="btn btn-outline-warning" style="display:none;">Go back to last step</button>
                                    <button id="submit_distribution_workflow" class="btn btn-primary w-25" style="display:none;">Continue</button>
                                </div>
                            </div>
                        </div>

                        <!-- Add CROs -->
                        <div id="choose-distribution-company" class="prodiff text-center" style="display:none">
                            <h3 class="mt-2">Team Members</h3>
                            <hr>
                            <p class="card-text">Add the Resources here and assign personnels</p>
                            <button type="button" class="btn btn-outline-info w-25 mx-auto mb-3" data-toggle="modal" data-target="#distribution-addcromodal">Add Resources</button>
                            <div class="modal fade" id="distribution-addcromodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Add Companies</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <label for="">Add Resources</label>
                                        <select class="custom-select" id="distribution-croname">
                                        <?php 
                                            foreach($cro_companies as $k => $cro_company){
                                                echo "<option value=\"".$k."\">".$cro_company."</option>";
                                            }
                                        ?>
                                        </select>
                                    </div>
                                    <div class="modal-footer">
                                        <button id="distribution-croadd"  class="btn btn-primary"  data-dismiss="modal">ADD</button>
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
                                            <h5 class="modal-title">Assign Personnels</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Assign people as manager or team members.</p>
                                            <!-- Choose Personnels -->
                                            <div class="card bg-light mb-3 float-left personnelarea">
                                                <div class="card-header">Candidates of Team Resources</div>
                                                <div id="personnelDraggable" class="card-body p-2">
                                                </div>
                                            </div>

                                            <!-- Droppable Area -->
                                            <div id="dropZone" class="card bg-light mx-3 mb-3 float-right">
                                                <div class="card-header">Drag Candidates Here for Assignment</div>
                                                <div class="stack border-success">
                                                    <div class="stackHdr">Assign as workflow manager</div>
                                                    <div class="stackDrop1" id="workflow_manager-add"></div>
                                                </div>
                                                <div class="stack border-info">
                                                    <div class="stackHdr">Assign as team resources</div>
                                                    <div class="stackDrop2" id="team_resources-add"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button id="conass-distribution" class="btn btn-outline-success" type="submit" data-dismiss="modal">Confirm Assignment</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-block mt-3">
                                <!-- CROs Resources List -->
                                <h3 class="mt-3">CROs Resources List</h3>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">CRO Company</th>
                                            <th scope="col">Workflow Manager</th>
                                            <th scope="col">Team Resources</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="distribution-crotable">
                                    </tbody>
                                </table>
                                <button id="undocho-distribution-WF" type="button" class="btn btn-outline-warning mt-3">Reselect Workflow</button>
                                <button id="confirm-distribution-WFlist" type="button" class="btn btn-primary w-25 mt-3 mx-auto">Continue</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<script type="text/javascript">
var accessment_workflow_structure = <?php echo json_encode($accessment_workflow_structure);?>;
var distribution_workflow_structure = <?php echo json_encode($distribution_workflow_structure);?>;
var loadTabs = <?php echo json_encode($loadTabs);?>;
var cro_companies = <?php echo json_encode($cro_companies);?>
</script>
<?php function renderTabs($sections, $exsitList, $sdsections,$tabkey){
    if(!array_key_exists($sections['id'], $exsitList)||$exsitList[$sections['id']]==="")
        return null;
    $sectionKey = array_search($sections['id'],$exsitList);
    echo "<div class=\"row text-left panel-heading\" id=\"l2section-".$sections['id']."\"><div class=\"col-md-12 \">";
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