<title>Case List</title>
<head>
    <?= $this->Html->script('cases/duplicate_detection.js') ?>
    <!-- For datepicker in caselist page-->
    <?= $this->Html->css('datepicker/jquery-ui.css') ?>
    <?= $this->Html->script('datepicker/jquery-1.10.2.js') ?>
    <?= $this->Html->script('datepicker/jquery-ui-1.10.4.js') ?>
    <!-- For local DataTable CSS/JS link -->
    <?= $this->Html->css('datatable/dataTables.bootstrap4.min.css') ?>
    <?= $this->Html->script('datatable/DataTables/js/jquery.dataTables.min.js') ?>
    <?= $this->Html->script('datatable/DataTables/js/dataTables.bootstrap4.min.js') ?>
    <script>
    var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
    var userId = <?= $this->request->getSession()->read('Auth.User.id')?>;
    </script>
</head>

<div class="my-3 mx-auto formContainer">
    <div class="text-center">
        <p class="pageTitle">
            <?php echo __("Case List")?>
        </p>
        <div class="form-row justify-content-center">
            <div class="form-group col-lg-2">
                <label class="col-form-label"><?php echo __("Search Product Name")?></label>
                <input type="text" class="form-control" id="searchProductName" name="searchProductName" placeholder="<?php echo __("Search Product Name")?>">
            </div>
            <div class="form-group col-lg-2">
                <label class="col-form-label"><?php echo __("Search Case No.")?></label>
                <input type="text" class="form-control"  id="searchName" name="searchName" placeholder="<?php echo __("Search Case No.")?>">
            </div>
            <div class="form-group col-lg-2">
                <label class="col-form-label"><?php echo __("Search Case Status")?></label>
                <input type="text" class="form-control" id="case_status" placeholder="<?php echo __("Search Case Status")?>">
            </div>
        </div>

        <div class="form-row justify-content-center">
            <div class="form-group col-lg-3">
                <label class="col-form-label"><?php echo __("Activity Due Date")?>:</label>
                <div class="col-sm-12 row justify-content-center">
                    <div class="col-sm-5">
                        <input type="text"  class="form-control" id="datepicker1" placeholder="<?php echo __("yyyy/mm/dd")?>">
                    </div>
                    <div class="col-sm-2 arrow">
                        <i class="far fa-window-minimize"></i>
                    </div>
                    <div class="col-sm-5">
                        <input type="text"  class="form-control" id="datepicker2" placeholder="<?php echo __("yyyy/mm/dd")?>">
                    </div>
                </div>
            </div>
            <div class="form-group col-lg-3">
                <label class="col-form-label"><?php echo __("Submission Due Date")?>:</label>
                <div class="col-sm-12 row justify-content-center">
                    <div class="col-sm-5">
                        <input type="text"  class="form-control" id="datepicker3" placeholder="<?php echo __("yyyy/mm/dd")?>">
                    </div>
                    <div class="col-sm-2 arrow">
                        <i class="far fa-window-minimize"></i>
                    </div>
                    <div class="col-sm-5">
                        <input type="text"  class="form-control" id="datepicker4" placeholder="<?php echo __("yyyy/mm/dd")?>">
                    </div>
                </div>
            </div>
        </div>

        <div class="form-row justify-content-center">
            <div class="form-group col-lg-2">
                <label class="col-form-label"><?php echo __("Search Patient ID")?></label>
                <input type="text" class="form-control" id="patient_id" placeholder="<?php echo __("Search Patient ID")?>">
            </div>
            <div class="form-group col-lg-2">
                <label class="col-form-label"><?php echo __("Select Date of Birth")?></label>
                <input type="text" class="form-control" id="datepicker5" placeholder="<?php echo __("Select Date of Birth")?>">
            </div>
            <div class="form-group col-lg-2">
                <label class="col-form-label"><?php echo __("Select Gender")?></label>
                <select id="inputState" class="form-control">
                    <option selected><?php echo __("Select Gender")?></option>
                    <option><?php echo __("Male")?></option>
                    <option><?php echo __("Female")?></option>
                    <option><?php echo __("Unknown")?></option>
                </select>
            </div>
        </div>
        <div class="text-center">
            <button onclick="onQueryClicked()" class="btn btn-primary w-25" type="button"><i class="fas fa-search"></i> <?php echo __("Search")?></button>
            <!-- <button id="advsearch" class="btn btn-outline-info"><i class="fas fa-keyboard"></i> Advanced Search</button> -->
            <button class="clearsearch btn btn-outline-danger" type="button"><i class="fas fa-eraser"></i> <?php echo __("Clear")?></button>
        </div>

        <div id="textHint" class="d-block w-100 text-center"></div>
    </div>
  </div>