<title><?php echo __("Dashboard")?></title>
<head>
<?= $this->Html->script('dashboard/index.js') ?>
<head>
<script>
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
var userId = <?= $this->request->getSession()->read('Auth.User.id')?>;
</script>

<div class="mx-auto my-3 formContainer text-center">
    <p class="pageTitle">
        <?php echo __("Dashboard");?>
    </p>
    <hr class="my-4">
    <h4 class="display-8 text-center"><?php echo __("Quick Look");?></h4>
    <div class="form-row justify-content-center">
        <?php
        foreach($preferrence_list as $preferrence_detail){
            if ($preferrence_detail['id']==7) {
                echo "<div class=\"form-group col-lg-4\" onclick=\"onQueryClicked(".$preferrence_detail['id'].")\"><button class=\"form-control btn btn-danger w-100\">";
                echo __($preferrence_detail['preferrence_name'])." ";
                echo "<span class=\"badge badge-light\">".$preferrence_detail['count']."</span>";
                echo "</button></div>";
            }
            else {
                echo "<div class=\"form-group col-lg-2\" onclick=\"onQueryClicked(".$preferrence_detail['id'].")\"><button class=\"form-control btn btn-outline-primary w-100\">";
                echo __($preferrence_detail['preferrence_name'])." ";
                echo "<span class=\"badge badge-danger\">".$preferrence_detail['count']."</span>";
                echo "</button></div>";
            }
        }
        ?>
    </div>
    <hr class="my-4">
    <h4 class="display-8 text-center"><?php echo __("Search")?></h4>
    <div class="form-row  justify-content-center" id="basicSearch">
        <div class="form-group col-lg-3">
            <input type="text" class="form-control" id="searchProductNameMin"placeholder="<?php echo __("Search by Product Name")?>">
        </div>
        <div class="form-group col-lg-2">
            <button id="searchBtn" onclick="onQueryClicked()" class="form-control btn btn-sm btn-primary"><i class="fas fa-search"></i> <?php echo __("Search")?></button>
        </div>
        <div class="form-group col-lg-2">
            <button id="fullSearchBtn" class="form-control btn btn-sm btn-outline-info"><i class="fas fa-keyboard"></i> <?php echo __("Advanced Search")?></button>
        </div>
    </div>
    <div id="fullSearch" style="display:none;">
        <div class="form-row">
            <div class="form-group col-lg-4">
                <label><?php echo __("Product Name")?></label>
                <input type="text" class="form-control" id="searchProductName" placeholder="<?php echo  __("Search by Product Name")?>">
            </div>
            <div class="form-group col-lg-4">
                <label><?php echo __("Case No.")?></label>
                <input type="text" class="form-control"  id="searchName" placeholder="<?php echo __("Search by Case No.")?>">
            </div>
            <div class="form-group col-lg-4">
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
            <div class="form-group col-lg-4">
                <label><?php echo __("Patient ID");?></label>
                <input type="text" class="form-control" id="patient_id" placeholder="<?php echo  __("Search Patient ID")?>">
            </div>
            <div class="form-group col-lg-4">
                <label><?php echo __("Date of Birth");?></label>
                <input type="date" class="form-control" id="patient_dob" placeholder="<?php echo  __("Search Birthday")?>">
            </div>
            <div class="form-group col-lg-4">
                <label><?php echo __("Gender");?></label>
                <select id="patient_gender" class="form-control">
                    <option value=""><?php echo __("Search Gender");?></option>
                    <option value="1"><?php echo __("Male");?></option>
                    <option value="2"><?php echo __("Female");?></option>
                    <option value="3"><?php echo __("Unknown");?></option>
                </select>
            </div>
        </div>
        <div class="form-row justify-content-start">
            <div class="form-group col-lg-3">
                <label class="col-form-label"><?php echo __("Activity Due Date")?>:</label>
                <div class="col-sm-12 row justify-content-start">
                    <div class="col-sm-5">
                        <input type="text"  class="form-control" id="activity_due_date_start" placeholder="<?php echo __("mm/dd/yyyy")?>">
                    </div>
                    <div class="col-sm-2 arrow">
                        <i class="far fa-window-minimize"></i>
                    </div>
                    <div class="col-sm-5">
                        <input type="text"  class="form-control" id="activity_due_date_end" placeholder="<?php echo __("mm/dd/yyyy")?>">
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-3">
                <label class="col-form-label"><?php echo __("Submission Due Date")?>:</label>
                <div class="col-sm-12 row justify-content-start">
                    <div class="col-sm-5">
                        <input type="text"  class="form-control" id="submission_due_date_start" placeholder="<?php echo __("mm/dd/yyyy")?>">
                    </div>
                    <div class="col-sm-2 arrow">
                        <i class="far fa-window-minimize"></i>
                    </div>
                    <div class="col-sm-5">
                        <input type="text"  class="form-control" id="submission_due_date_end" placeholder="<?php echo __("mm/dd/yyyy")?>">
                    </div>
                </div>
            </div>
        </div>

        <div class="form-row justify-content-center">
            <div class="form-group col-lg-3">
                <button id="searchBtn" onclick="onQueryClicked()" class="form-control btn btn-sm btn-primary w-100"><i class="fas fa-search"></i> <?php echo __("Search");?></button>
            </div>
            <div class="form-group col-lg-1">
                <button class="clearsearch form-control btn btn-sm btn-outline-danger w-100"><i class="fas fa-eraser"></i> <?php echo __("Clear");?></button>
            </div>
        </div>
    </div>
    <hr class="my-3">
    <div id="textHint" class="text-center align-middle"></div>
</div>
