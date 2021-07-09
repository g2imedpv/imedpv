<title><?php echo __("Dashboard")?></title>
<head>
<?= $this->Html->script('dashboard/index.js') ?>
<!-- For datepicker in dashboard advanced search page-->
<?= $this->Html->css('datepicker/jquery-ui.css') ?>
<?= $this->Html->script('datepicker/jquery-1.10.2.js') ?>
<?= $this->Html->script('datepicker/jquery-ui-1.10.4.js') ?>
<!-- For local DataTable CSS/JS link -->
<?= $this->Html->css('datatable/dataTables.bootstrap4.min.css') ?>
<?= $this->Html->script('datatable/DataTables/js/jquery.dataTables.min.js') ?>
<?= $this->Html->script('datatable/DataTables/js/dataTables.bootstrap4.min.js') ?>
<head>
<script>
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
var userId = <?= $this->request->getSession()->read('Auth.User.id')?>;
</script>

<div class="card text-center formContainer mx-auto my-3">
  <div class="card-header pageTitle"><?php echo __("Dashboard");?></div>
  <div class="card-body row justify-content-around">
    <div class='col-md-5 border border-light rounded'>
        <h3 class="card-title p-2"><?php echo __("Quick Look");?></h3>
        <div class='row justify-content-center'>
            <?php
            foreach($preferrence_list as $preferrence_detail){
                if ($preferrence_detail['id']==7) {
                    echo "<button type=\"button\" class=\"btn btn-outline-danger col-md-10 mx-3 my-1 btnShadow\" onclick=\"onQueryClicked(".$preferrence_detail['id'].")\">";
                    echo "<div class=\"row justify-content-around d-flex h-100\">";
                    echo "<div class=\"font-weight-bold QL-size\">";
                    echo "<span>".__($preferrence_detail['preferrence_name'])."</span>" ;
                    echo "<span class=\"badge badge-warning ml-3\">".$preferrence_detail['count']."</span></div>";
                    echo "<div class=\"badge badge-danger\"><i class=\"".$preferrence_detail['icon']." fa-3x\"></i></div>";
                    echo "</div></button>";
                }
                else {
                    echo "<button type=\"button\" class=\"btn btn-outline-primary col-md-5 m-1 btnShadow\" onclick=\"onQueryClicked(".$preferrence_detail['id'].")\">";
                    echo "<div class=\"row justify-content-around d-flex h-100\">";
                    echo "<div class=\"font-weight-bold QL-size\" >";
                    echo "<span>".__($preferrence_detail['preferrence_name'])."</span>" ;
                    echo "<span class=\"badge badge-warning ml-3\">".$preferrence_detail['count']."</span></div>";
                    echo"<div class=\"badge badge-primary\"><i class=\"".$preferrence_detail['icon']." QL-icon fa-3x\"></i></div>";
                    echo "</div></button>";
                }
            }
            ?>
        </div>

    </div>

    <!-- Search -->
    <div class='col-md-7 border border-light rounded'>
        <h3 class="card-title p-2"><?php echo __("Search")?></h3>

        <div class="form-row">
            <div class="form-group col-lg-5">
                <label><?php echo __("Product Name")?></label>
                <input type="text" class="form-control" id="searchProductName" placeholder="<?php echo  __("Search by Product Name")?>">
            </div>
            <div class="form-group col-lg-4">
                <label><?php echo __("Case No.")?></label>
                <input type="text" class="form-control"  id="caseNo" placeholder="<?php echo __("Search by Case No.")?>">
            </div>
            <div class="form-group col-lg-3">
                <label><?php echo __("Case Status")?></label>
                <select class="form-control" id="caseStatus" >
                    <option value=""><?php echo __("Search by Case Status");?></option>
                    <option value="1"><?php echo __("Active");?></option>
                    <option value="2"><?php echo __("Inactive");?></option>
                    <option value="3"><?php echo __("All");?></option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-lg-5">
                <label><?php echo __("Patient ID");?></label>
                <input type="text" class="form-control" id="patient_id" placeholder="<?php echo  __("Search Patient ID")?>">
            </div>
            <div class="form-group col-lg-4">
                <label><?php echo __("Date of Birth");?></label>
                <input type="text" class="form-control" id="patient_dob" placeholder="<?php echo  __("Search Birthday")?>">
            </div>
            <div class="form-group col-lg-3">
                <label><?php echo __("Gender");?></label>
                <select id="patient_gender" class="form-control">
                    <option value=""><?php echo __("Search Gender");?></option>
                    <option value="1"><?php echo __("Male");?></option>
                    <option value="2"><?php echo __("Female");?></option>
                    <option value="3"><?php echo __("Unknown");?></option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-lg-6">
                <label class="col-form-label"><?php echo __("Activity Due Date")?>:</label>
                <div class="col-sm-12 row justify-content-start">
                    <div class="col-sm-5">
                        <input type="text"  class="form-control" id="activity_due_date_start" placeholder="<?php echo __("dd/mm/yyyy")?>">
                    </div>
                    <div class="col-sm-2 arrow">
                        <i class="far fa-window-minimize"></i>
                    </div>
                    <div class="col-sm-5">
                        <input type="text"  class="form-control" id="activity_due_date_end" placeholder="<?php echo __("dd/mm/yyyy")?>">
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-6">
                <label class="col-form-label"><?php echo __("Submission Due Date")?>:</label>
                <div class="col-sm-12 row justify-content-start">
                    <div class="col-sm-5">
                        <input type="text"  class="form-control" id="submission_due_date_start" placeholder="<?php echo __("dd/mm/yyyy")?>">
                    </div>
                    <div class="col-sm-2 arrow">
                        <i class="far fa-window-minimize"></i>
                    </div>
                    <div class="col-sm-5">
                        <input type="text"  class="form-control" id="submission_due_date_end" placeholder="<?php echo __("dd/mm/yyyy")?>">
                    </div>
                </div>
            </div>

        </div>

        <div class='form-row justify-content-between'>
            <div class="form-group col-lg-4">
                <label><?php echo __("SMQ Query");?></label>
                <select class="form-control" id="meddra_smq">
                <option value=""><?php echo __("Select SMQ Query")?></option>
                <?php
                    foreach($smq_list as $smq_code => $smq_name)
                        echo "<option value=\"".$smq_code."\" >".$smq_name."</option>";
                ?>
                </select>
                <!-- <input type="text" class="form-control" id="searchSMQ" placeholder="<?php echo __("Type SMQ Query")?>"> -->
                <div id="SMQoptions"  style="display:none;"></div>
                <input type="hidden" id="meddra_smq">
            </div>
            <div class="form-group col-lg-4">
                <label><?php echo __("SMQ Query");?></label>
                <select class="form-control" id="meddra_smq_scope">
                    <option value="1"><?php echo __("Broad Search")?></option>
                    <option value="2"><?php echo __("Narrow Search")?></option>
                </select>
            </div>
            <div class='form-group col-lg-4 d-flex align-items-end'>
                <button id="searchBtn" onclick="onQueryClicked()" class="form-control btn btn-primary"><i class="fas fa-search"></i> <?php echo __("Search");?></button>
            </div>
        </div>
    </div>
  </div>
    <hr class="my-3">
    <div id="textHint" class="formContainer mx-auto my-3 text-center align-middle"></div>
</div>
