<?php
//debug($sdProductTypes);
?>
<title><?php echo __("Search Product")?></title>
<head>
<?= $this->Html->script('product/search.js') ?>
<head>
<script type="text/javascript">
    var userId = <?= $this->request->getSession()->read('Auth.User.id')?>;
    var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
</script>

<div class="my-3 mx-auto formContainer">
    <div class="text-center">
        <p class="pageTitle">
            <?php echo __("Search Product")?>
        </p>
        <!-- Search Product -->
        <span id="errorMsg" class="alert alert-danger" role="alert" style="display:none"></span>
        <div id="addpro" class="form-row">
            <div class="form-group col-md-3">
                <label><?php echo __("Key Word");?></label>
                <input type="text" class="form-control" id="key_word" name="key_word" placeholder="<?php echo __("Search Key Word");?>">
            </div>
            <div class="form-group col-md-3">
                <label><?php echo __("Product Name");?></label>
                <input type="text" class="form-control" id="product_name" name="product_name" placeholder="<?php echo __("Search Product Name");?>">
            </div>
            <div class="form-group col-md-3">
                <label><?php echo __("Sponsor Study Number (A.2.3.2)");?></label>
                <input type="text" class="form-control" id="study_no" name="study_no" placeholder="<?php echo __("Search Study Number");?>">
            </div>
            <div class="form-group col-md-3">
                <label><?php echo __("Status");?></label>
                <input type="text" class="form-control" id="status" name="status" placeholder="<?php echo __("Search Status");?>">
            </div>
        </div>
        <div id="advsearchfield" style="display:none;">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label><?php echo __("Study Type (A.2.3.3)");?></label>
                    <input type="text" class="form-control" id="product_name" name="product_name" placeholder="<?php echo __("Search Study Type");?>">
                </div>
                <div class="form-group col-md-3">
                    <label><?php echo __("Product Flag (B.4.k.1)");?></label>
                    <input type="text" class="form-control" id="product_name" name="product_name" placeholder="<?php echo __("Search Product Flag");?>">
                </div>
                <div class="form-group col-md-3">
                    <label><?php echo __("Product Type");?></label>
                    <select class="form-control" name="sd_product_type_id" id="sd_product_type_id">
                    <?php
                        echo "<option value=''>".__("Select Product Type")."</option>";
                        foreach ($sdProductTypes as $eachType)
                        {
                            echo "<option value=\"".$eachType['id']."\">".$eachType['type_name']."</option>";
                        }
                    ?>
                    </select>
                    <input type="hidden" id="status" name="status" value="1">
                </div>
                <div class="form-group col-md-3">
                    <label><?php echo __("Study Name (A.2.3.1)");?></label>
                    <input type="text" class="form-control" id="product_name" name="product_name" placeholder="<?php echo __("Search Study Name")?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label><?php echo __("Sponsor Company");?></label>
                    <input type="text" class="form-control" id="product_name" name="product_name" placeholder="<?php echo __("Search Sponsor Company")?>">
                </div>
                <div class="form-group col-md-3">
                    <label><?php echo __("CRO");?></label>
                    <input type="text" class="form-control" id="product_name" name="product_name" placeholder="<?php echo __("Search CRO")?>">
                </div>
                <div class="form-group col-md-3">
                    <label><?php echo __("Call Center");?></label>
                    <input type="text" class="form-control" id="product_name" name="product_name" placeholder="<?php echo __("Search Call Center")?>">
                </div>
            </div>
        </div>
        <button onclick="searchProd()" class="btn btn-primary w-25"><i class="fas fa-search"></i> <?php echo __("Search")?></button>
        <button id="advsearch" class="btn btn-outline-info"><i class="fas fa-keyboard"></i> <?php echo __("Advanced Search")?></button>
        <button class="clearsearch btn btn-outline-danger"><i class="fas fa-eraser"></i> <?php echo __("Clear")?></button>
        <!-- <div class="form-row justify-content-center">
            <div class="form-group col-lg-3">
                <div onclick="searchProd()" class="btn btn-primary w-100"><i class="fas fa-search"></i> Search</div>
            </div>
            <div class="form-group col-lg-2">
                <div id="advsearch" class="btn btn-outline-info w-100"><i class="fas fa-keyboard"></i> Advanced Search</div>
            </div>
            <div class="form-group col-lg-1">
                <div class="clearsearch form-control btn btn-outline-danger w-100"><i class="fas fa-eraser"></i> Clear</div>
            </div>
        </div> -->


        <div class="modal fade WFlistView" tabindex="-1" role="dialog" aria-labelledby="WFlistView" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-center"><?php echo __("Workflow Details")?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body m-3">
                            <table class="table table-hover" id="ifram_view">
                                <thead>
                                    <tr>
                                        <th scope="row" class="w-25"><?php echo __("Workflow Name")?></th>
                                        <td id="viewWFname"></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row" class="w-25"><?php echo __("Call Center")?></th>
                                        <td id="viewCC"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="w-25"><?php echo __("Country")?></th>
                                        <td id="viewCountry"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="w-25"><?php echo __("Description")?></th>
                                        <td id="viewDesc"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="w-25"><?php echo __("Workflow Manager")?></th>
                                        <td id="viewMan"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="w-25"><?php echo __("Team Resources")?></th>
                                        <td id="viewRes"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <div>
                                <h4><?php echo __("Workflow Steps")?></h4>
                                <div id="view_activities"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a class="btn btn-primary" id="allocate_workflow" href="#"><?php echo __("Allocate This Workflow")?></a>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo __("Close")?></button>
                            <!-- <button type="button" class="btn btn-primary">Send message</button> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade product_detail" tabindex="-1" role="dialog" aria-labelledby="product_detail" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-center"><?php echo __("Product Detail")?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body m-3">
                            <div id="addpro" class="form-row">
                                <div class="form-group col-md-3">
                                    <label><?php echo __("Product Name");?></label>
                                    <input class="form-control" id="detail_product_name" readonly="readonly">
                                </div>
                                <div class="form-group col-md-3">
                                    <label><?php echo __("Sponsor Company");?></label>
                                    <input type="text"  class="form-control" id="detail_sponsor_company" readonly="readonly">
                                </div>
                                <div class="form-group col-md-3">
                                    <label><?php echo __("Product Flag (B.4.k.1)");?></label>
                                    <input type="text" class="form-control" id="detail_sd_product_flag" readonly="readonly">
                                </div>
                                <div class="form-group col-md-3">
                                    <label><?php echo __("Blinding Technique");?></label>
                                    <input type="text" class="form-control" id="detail_blinding_tech" readonly="readonly">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label><?php echo __("Study Name");?></label>
                                    <input type="text" class="form-control" id="detail_study_name" readonly="readonly">
                                </div>
                                <div class="form-group col-md-3">
                                    <label><?php echo __("Study Number");?></label>
                                    <input type="text" class="form-control" id="detail_study_no" readonly="readonly">
                                </div>
                                <div class="form-group col-md-3">
                                    <label><?php echo __("Mfr. Name");?></label>
                                    <input type="text" class="form-control" id="detail_mfr_name" readonly="readonly">
                                </div>
                                <div class="form-group col-md-3">
                                    <label><?php echo __("Study Type (A.2.3.3)");?></label>
                                    <input class="form-control" id="detail_study_type" readonly="readonly">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label><?php echo __("WHO-DD Code");?></label>
                                    <input type="text" readonly="readonly" class="form-control" id="detail_whodracode">
                                </div>
                                <div class="form-group col-md-3">
                                    <label><?php echo __("WHO-DD Name");?></label>
                                    <input type="text" readonly="readonly" class="form-control" id="detail_whodraname">
                                </div>
                                <div class="form-group col-md-6">
                                    <label><?php echo __("Preferred WHO DD Decode");?></label>
                                    <input type="text" readonly="readonly" class="form-control" id="detail_WHODD_decode">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label><?php echo __("Start Date");?></label>
                                    <input type="text" readonly="readonly" class="form-control" id="detail_start_date">
                                </div>
                                <div class="form-group col-md-3">
                                    <label><?php echo __("End Date");?></label>
                                    <input type="text" readonly="readonly" class="form-control" id="detail_end_date">
                                </div>
                                <div class="form-group col-md-3">
                                    <label><?php echo __("Status");?></label>
                                    <input type="text" class="form-control" id="detail_status" readonly="readonly">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label><?php echo __("Short Description");?></label>
                                    <input type="text" readonly="readonly" class="form-control" id="detail_short_desc">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label><?php echo __("Proprietary Medicinal Product Name");?> (B.4.k.2.1)</label>
                                    <input type="text" readonly="readonly" class="form-control" id="detail_product_desc">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo __("Close")?></button>
                            <!-- <button type="button" class="btn btn-primary">Send message</button> -->
                        </div>
                    </div>
                </div>
            </div>
        <div id="searchProductlist" class="my-3"></div>
    </div>
</div>