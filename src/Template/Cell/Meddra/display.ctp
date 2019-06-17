<button type="button" onclick="meddrabrowser(<?php echo $meddraFieldId;?>)" class="btn btn-info btn-sm mx-3 d-block" id="meddraBtn_<?php echo $meddraFieldId;?>" data-toggle="modal" data-target=".bd-example-modal-sm"><i class="fas fa-search"></i><?php echo __("MedDRA Browser")?></button>
<div class="lltQuickSearch form-group col-md-6">
    <label><?php echo __("LLT Term Encode");?></label>
    <input class="form-control" type="text" id="llt-searchbar_<?php echo $meddraFieldId;?>" >
    <div id="meddraQuickSearch_<?php echo $meddraFieldId;?>"></div>
    <input class="form-control" type="hidden" id="descriptor_<?php echo $meddraFieldId;?>" value="<?php echo $descriptor;?>">
</div>