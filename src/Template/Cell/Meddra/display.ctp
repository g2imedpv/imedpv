<div class="position-relative">
    <button type="button" onclick="meddrabrowser(<?php echo $meddraFieldId;?>)" class="btn btn-info btn-sm d-block" id="meddraBtn_<?php echo $meddraFieldId;?>" data-toggle="modal" data-target=".bd-example-modal-sm"><i class="fas fa-search"></i> <?php echo __("MedDRA Browser")?></button>
    <div class="col-md-4 my-3 position-absolute lltQuickSearch">
        <label><?php echo __("LLT Term Encode");?></label>
        <input class="form-control" type="text" id="llt-searchbar_<?php echo $meddraFieldId;?>" >
        <div id="meddraQuickSearch_<?php echo $meddraFieldId;?>"></div>
        <input class="form-control" type="hidden" id="descriptor_<?php echo $meddraFieldId;?>" value="<?php echo $descriptor;?>">
    </div>
</div>