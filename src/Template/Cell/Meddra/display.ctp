<!-- Meddra button -->
<div class="position-relative">
    <button type="button" onclick="meddrabrowser(<?php echo $meddraFieldId;?>)" class="btn btn-info btn-sm d-block" id="meddraBtn_<?php echo $meddraFieldId;?>" data-toggle="modal" data-target=".bd-example-modal-sm"><i class="fas fa-search"></i> <?php echo __("MedDRA Browser")?></button>
    <div class="col-md-4 my-2 position-absolute lltQuickSearch">
        <label><?php echo __("LLT Term Encode");?></label>
        <input class="form-control" type="text" id="llt-searchbar_<?php echo $meddraFieldId;?>" >
        <div id="meddraQuickSearch_<?php echo $meddraFieldId;?>"></div>
        <input class="form-control" type="hidden" id="descriptor_<?php echo $meddraFieldId;?>" value="<?php echo $descriptor;?>">
    </div>
</div>

<!-- Meddra Modal -->
<div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document" style="max-width: 1175px !important;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id=""><?php echo __("MedDRA Browser")?> (<?php echo __("Version")?>: MedDRA 24.1)</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <!-- Search field -->
            <div class="container">
                <div class="form-row m-2">
                     <!-- <div class="form-group col-md-4">
                        <label>Search LLT Term</label>
                        <input type="text" class="form-control" id="llt_term"  placeholder="Search LLT Term">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Search PT Term</label>
                        <input type="text" class="form-control" id="pt_term"  placeholder="Search PT Term">
                    </div>
                    <div class="form-group col-md-4">
                        <label><?php echo __("SMQ")?>:</label>
                        <input type="text" class="form-control" id="wildcard_search"  placeholder="Search by SMQ">
                    </div> -->
                </div>
                <div class="form-row justify-content-between mx-3">
                    <!-- <div class="form-group col-sm-3">
                        <div id="meddrasea" onclick="searchMedDra()" class="form-control btn btn-primary w-100"><i class="fas fa-search"></i> Search</div>
                    </div> -->
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="meddra-full_text">
                            <label class="form-check-label" for="meddra-full_text"><?php echo __("Full Text Search")?></label>
                        </div>
                        <button class="clearsearch btn btn-sm btn-outline-danger" type="button"><i class="fas fa-eraser"></i> <?php echo __("Clear")?></button>

                </div>
            </div>
            <div class="container mb-5">
                <div class="form-row justify-content-around">
                    <div class="form-group col-md-2">
                        <label for=""><?php echo __("SOC Name")?></label>
                        <input type="text" class="form-control" id="typed-soc-name">
                    </div>
                    <div class="form-group col-md-2">
                        <label for=""><?php echo __("HLGT Name")?></label>
                        <input type="text" class="form-control" id="typed-hlgt-name">
                    </div>
                    <div class="form-group col-md-2">
                        <label for=""><?php echo __("HLT Name")?></label>
                        <input type="text" class="form-control" id="typed-hlt-name">
                    </div>
                    <div class="form-group col-md-2">
                        <label for=""><?php echo __("PT Name")?></label>
                        <input type="text" class="form-control" id="typed-pt-name">
                    </div>
                    <div class="form-group col-md-2">
                        <label for=""><?php echo __("LLT Name")?></label>
                        <input type="text" class="form-control" id="typed-llt-name">
                    </div>
                </div>

                <!-- Loading Animation -->
                <div class='loadingSpinner'>
                    <div class="spinner-border text-primary m-5 " role="status">
                        <span class="visually-hidden"></span>
                    </div>
                </div>
                <div id='noMDmatch'></div>
                <div style="height: 300px;" class="form-row justify-content-around pendingShow">
                    <div class="form-group border h-100 col-md-2 overflow-auto" id="field-soc_name"></div>
                    <div class="form-group border h-100 col-md-2 overflow-auto" id="field-hlgt_name"></div>
                    <div class="form-group border h-100 col-md-2 overflow-auto" id="field-hlt_name"></div>
                    <div class="form-group border h-100 col-md-2 overflow-auto" id="field-pt_name"></div>
                    <div class="form-group border h-100 col-md-2 overflow-auto" id="field-llt_name"></div>
                </div>
            </div>
            <!-- Table field (Should be hidden before search) -->
            <div class='pendingShow'>
                <h4 class="text-center"><?php echo __("MedDra Details")?></h4> <hr>
                <div class="container">
                    <fieldset disabled>
                        <div class="form-row justify-content-center">
                            <div class="form-group col-md-4">
                                <label for=""><?php echo __("LLT Name")?></label>
                                <input type="text" class="form-control" id="select-llt-n">
                            </div>
                            <div class="form-group col-md-3 offset-md-1">
                                <label for=""><?php echo __("LLT Code")?></label>
                                <input type="text" class="form-control" id="select-llt-c">
                            </div>
                        </div>
                        <div class="form-row justify-content-center">
                            <div class="form-group col-md-4">
                                <label for=""><?php echo __("PT Name")?></label>
                                <input type="text" class="form-control" id="select-pt-n">
                            </div>
                            <div class="form-group col-md-3 offset-md-1">
                                <label for=""><?php echo __("PT Code")?></label>
                                <input type="text" class="form-control" id="select-pt-c">
                            </div>
                        </div>
                        <div class="form-row justify-content-center">
                            <div class="form-group col-md-4">
                                <label for=""><?php echo __("HLT Name")?></label>
                                <input type="text" class="form-control" id="select-hlt-n">
                            </div>
                            <div class="form-group col-md-3 offset-md-1">
                                <label for=""><?php echo __("HLT Code")?></label>
                                <input type="text" class="form-control" id="select-hlt-c">
                            </div>
                        </div>
                        <div class="form-row justify-content-center">
                            <div class="form-group col-md-4">
                                <label for=""><?php echo __("HLGT Name")?></label>
                                <input type="text" class="form-control" id="select-hlgt-n">
                            </div>
                            <div class="form-group col-md-3 offset-md-1">
                                <label for=""><?php echo __("HLGT Code")?></label>
                                <input type="text" class="form-control" id="select-hlgt-c">
                            </div>
                        </div>
                        <div class="form-row justify-content-center">
                            <div class="form-group col-md-4">
                                <label for=""><?php echo __("SOC Name")?></label>
                                <input type="text" class="form-control" id="select-soc-n">
                            </div>
                            <div class="form-group col-md-3 offset-md-1">
                                <label for=""><?php echo __("SOC Code")?></label>
                                <input type="text" class="form-control" id="select-soc-c">
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="meddraSelectBtn" class="btn btn-success w-25 mx-auto"  onclick="selectMeddraButton()" data-dismiss="modal"><?php echo __("Select")?></button>
      </div>
    </div>
  </div>
</div>