<title><?php echo __("Search Contact");?></title>
<head>
<script type="text/javascript">
    var caseId = <?= json_encode($caseId) ?>;
    var userId = <?= $this->request->getSession()->read('Auth.User.id')?>;
    var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
</script>
<?= $this->Html->script('inputHistory/search.js') ?>
<!-- For local DataTable CSS/JS link -->
<?= $this->Html->css('datatable/dataTables.bootstrap4.min.css') ?>
<?= $this->Html->script('datatable/DataTables/js/jquery.dataTables.min.js') ?>
<?= $this->Html->script('datatable/DataTables/js/dataTables.bootstrap4.min.js') ?>
<head>


<body>
    <div class="mx-auto my-3 formContainer text-center">
        <p class="pageTitle">
            <?php echo __("Input Histories")?>
            <!-- <?php echo __("Case No.").$caseNo;?> -->
        </p>
        <!-- Search Product -->
        <span id="errorMsg" class="alert alert-danger" role="alert" style="display:none"></span>
        <div id="addpro" class="form-row">
            <div class="form-group col-md-3">
                <label><?php echo __("Tab Name");?></label>
                <input type="text" class="form-control" id="tab_name" name="tab_name" placeholder="<?php echo __("Tab Name");?>">
            </div>
            <div class="form-group col-md-3">
                <label><?php echo __("Section Name");?></label>
                <input type="text" class="form-control" id="section_name" name="section_name" placeholder="<?php echo __("Section Name");?>">
            </div>
            <div class="form-group col-md-3">
                <label><?php echo __("User Name");?></label>
                <input type="text" class="form-control" id="user_name" name="user_name" placeholder="<?php echo __("User_name");?>">
            </div>
            <div class="form-group col-lg-3">
                <label class="col-form-label"><?php echo __("Modified Date")?>:</label>
                <div class="col-sm-12 row justify-content-start">
                    <div class="col-sm-5">
                        <input type="text"  class="form-control" id="modified_date_start" placeholder="<?php echo __("yyyy/mm/dd")?>">
                    </div>
                    <div class="col-sm-2 arrow">
                        <i class="far fa-window-minimize"></i>
                    </div>
                    <div class="col-sm-5">
                        <input type="text"  class="form-control" id="modified_date_end" placeholder="<?php echo __("yyyy/mm/dd")?>">
                    </div>
                </div>
            </div>
        </div>
        <button  class="btn btn-primary w-25" onclick="searchContact()"><i class="fas fa-search"></i> <?php echo __("Search");?> </button>
        <!-- <button id="advsearch" class="btn btn-outline-info"><i class="fas fa-keyboard"></i> Advanced Search</button> -->
        <button class="clearsearch btn btn-outline-danger"><i class="fas fa-eraser"></i> <?php echo __("Clear");?> </button>

        <hr class="my-4">
        <p class="pageTitle">
            <?php echo __("Input Histories List");?>
        </p>
        <div id="searchInputHistoryList"></div>
    </div>
</body>