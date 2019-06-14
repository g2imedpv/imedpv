<title>Case List</title>
<script>
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
var userId = <?= $this->request->getSession()->read('Auth.User.id')?>;
</script>
<div class="card my-3 w-75 mx-auto">
    <div class="card-header text-center">
        <h3> Case List</h3>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-lg-4">
                <!-- <label for="recipient-name" class="col-form-label">Recipient:</label> -->
                <input type="text" class="form-control" id="searchProductName" name="searchProductName" placeholder="Search by Product Name">
            </div>
            <div class="form-group col-lg-4">
                <input type="text" class="form-control"  id="searchName" name="searchName" placeholder="Select Case No.">
            </div>
            <div class="form-group col-lg-4">
                <input type="text" class="form-control" id="case_status" placeholder="Select Case Status">
            </div>
        </div>
        <div class="form-row">
            <div class="duedate form-group col-2"><?php echo __("Activity Due Date")?>:</div>
            <div class="form-group col-1">
                <input type="text" class="form-control" id="datepicker1" placeholder="<?php echo __("[mm/dd/yyyy]")?>">
            </div>
            <div class="arrow">
                <i class="far fa-window-minimize"></i>
            </div>
            <div class="form-group col-1">
                <input type="text" class="form-control" id="datepicker2" placeholder="<?php echo __("[mm/dd/yyyy]")?>">
            </div>

            <div class="duedate form-group col-2 float-right"><?php echo __("Submission Due Date")?>:</div>
            <div class="form-group col-1">
                <input type="text" class="form-control" id="datepicker3" placeholder="<?php echo __("[mm/dd/yyyy]")?>">
            </div>
            <div class="arrow">
                <i class="far fa-window-minimize"></i>
            </div>
            <div class="form-group col-1">
                <input type="text" class="form-control" id="datepicker4" placeholder="<?php echo __("[mm/dd/yyyy]")?>">
            </div>
        </div>
        <div class="form-row" id="advsearchfield">
            <div class="form-group col-lg-2">
                <input type="text" class="form-control" id="patient_id" placeholder="<?php echo __("Search by Patient ID")?>">
            </div>
            <div class="form-group col-lg-2">
                <input type="text" class="form-control" id="datepicker5" placeholder="<?php echo __("Choose Date of Birth")?>">
            </div>
            <div class="form-group col-lg-2">
                <select id="inputState" class="form-control">
                    <option selected><?php echo __("Select Gender")?></option>
                    <option><?php echo __("Male")?></option>
                    <option><?php echo __("Female")?></option>
                    <option><?php echo __("Unknown")?></option>
                </select>
            </div>
        </div>
        <div class="text-center">
            <button onclick="onQueryClicked()" class="btn btn-primary w-25"><i class="fas fa-search"></i> <?php echo __("Search")?></button>
            <!-- <button id="advsearch" class="btn btn-outline-info"><i class="fas fa-keyboard"></i> Advanced Search</button> -->
            <button class="clearsearch btn btn-outline-danger"><i class="fas fa-eraser"></i> <?php echo __("Clear")?></button>
        </div>

        <div id="textHint" class="d-block w-100 text-center"></div>
    </div>
  </div>