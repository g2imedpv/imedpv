<button type="button" class="btn btn-info btn-sm mx-3 d-block" id="meddraBtn_<?php echo $meddraFieldId;?>" data-toggle="modal" data-target=".bd-example-modal-sm"><i class="fas fa-search"></i> MedDRA Browser</button>
<div class="lltQuickSearch form-group col-md-6">
    <label>LLT Term Encode</label>
    <input class="form-control" type="text" id="llt-searchbar_<?php echo $meddraFieldId;?>" >
    <div id="meddraQuickSearch_<?php echo $meddraFieldId;?>"></div>
    <input class="form-control" type="hidden" id="descriptor_<?php echo $meddraFieldId;?>" value="<?php echo $descriptor;?>">
</div>

<div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document" style="max-width: 1175px !important;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">MedDRA Browser (Version: MedDRA 18.1)</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <!-- Search field -->
            <div class="container">
                <div class="form-row mx-2">
                    <!-- <div class="form-group col-md-4">
                        <label>Search LLT Term</label>
                        <input type="text" class="form-control" id="llt_term"  placeholder="Search LLT Term">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Search PT Term</label>
                        <input type="text" class="form-control" id="pt_term"  placeholder="Search PT Term">
                    </div> -->
                    <div class="form-group col-md-4">
                        <label>SMQ:</label>
                        <input type="text" class="form-control" id="wildcard_search"  placeholder="Search by SMQ">
                    </div>
                    <div class="form-group">
                        <label for="meddra-full_text">Full Search</label>
                        <input type="checkbox" class="form-control" id="meddra-full_text">
                    </div>
                </div>
                <!-- <div class="form-row justify-content-center">
                    <div class="form-group col-sm-3">
                        <div id="meddrasea" onclick="searchMedDra()" class="form-control btn btn-primary w-100"><i class="fas fa-search"></i> Search</div>
                    </div>
                    <div class="form-group col-sm-1">
                        <div class="clearsearch form-control btn btn-outline-danger w-100"><i class="fas fa-eraser"></i> Clear</div>
                    </div>
                </div> -->
            </div>
            <div class="container mb-5">
                    <div class="form-row justify-content-around">
                        <div class="form-group col-md-2">
                            <label for="">SOC Name</label>
                            <input type="text" class="form-control" id="typed-soc-name">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="">HLGT Name</label>
                            <input type="text" class="form-control" id="typed-hlgt-name">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="">HLT Name</label>
                            <input type="text" class="form-control" id="typed-hlt-name">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="">PT Name</label>
                            <input type="text" class="form-control" id="typed-pt-name">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="">LLT Name</label>
                            <input type="text" class="form-control" id="typed-llt-name">
                        </div>
                    </div>
                    <div style="height: 300px;" class="form-row justify-content-around">
                        <div class="form-group border h-100 col-md-2 overflow-auto" id="field-soc_name"></div>
                        <div class="form-group border h-100 col-md-2 overflow-auto" id="field-hlgt_name"></div>
                        <div class="form-group border h-100 col-md-2 overflow-auto" id="field-hlt_name"></div>
                        <div class="form-group border h-100 col-md-2 overflow-auto" id="field-pt_name"></div>
                        <div class="form-group border h-100 col-md-2 overflow-auto" id="field-llt_name"></div>
                    </div>
            </div>
            <!-- Table field (Should be hidden before search) -->
            <h4 class="text-center">MedDra Details</h4> <hr>
            <div class="container">
                <fieldset disabled>
                    <div class="form-row justify-content-center">
                        <div class="form-group col-md-4">
                            <label for="">LLT Name</label>
                            <input type="text" class="form-control" id="select-llt-n">
                        </div>
                        <div class="form-group col-md-3 offset-md-1">
                            <label for="">LLT Code</label>
                            <input type="text" class="form-control" id="select-llt-c">
                        </div>
                    </div>
                    <div class="form-row justify-content-center">
                        <div class="form-group col-md-4">
                            <label for="">PT Name</label>
                            <input type="text" class="form-control" id="select-pt-n">
                        </div>
                        <div class="form-group col-md-3 offset-md-1">
                            <label for="">PT Code</label>
                            <input type="text" class="form-control" id="select-pt-c">
                        </div>
                    </div>
                    <div class="form-row justify-content-center">
                        <div class="form-group col-md-4">
                            <label for="">HLT Name</label>
                            <input type="text" class="form-control" id="select-hlt-n">
                        </div>
                        <div class="form-group col-md-3 offset-md-1">
                            <label for="">HLT Code</label>
                            <input type="text" class="form-control" id="select-hlt-c">
                        </div>
                    </div>
                    <div class="form-row justify-content-center">
                        <div class="form-group col-md-4">
                            <label for="">HLGT Name</label>
                            <input type="text" class="form-control" id="select-hlgt-n">
                        </div>
                        <div class="form-group col-md-3 offset-md-1">
                            <label for="">HLGT Code</label>
                            <input type="text" class="form-control" id="select-hlgt-c">
                        </div>
                    </div>
                    <div class="form-row justify-content-center">
                        <div class="form-group col-md-4">
                            <label for="">SOC Name</label>
                            <input type="text" class="form-control" id="select-soc-n">
                        </div>
                        <div class="form-group col-md-3 offset-md-1">
                            <label for="">SOC Code</label>
                            <input type="text" class="form-control" id="select-soc-c">
                        </div>
                    </div>
                </fieldset>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success w-25 mx-auto"  onclick="selectMeddraButton(<?php echo $meddraFieldId?>)" data-dismiss="modal">Select</button>
      </div>
    </div>
  </div>
</div>