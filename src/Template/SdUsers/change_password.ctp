<title>Change Password</title>

<div class="card text-center w-50 my-3 mx-auto">
  <div class="card-header">
    <h3><?php echo __("Change Password")?></h3>
  </div>
  <div class="card-body">
    <?= $this->Form->create() ?>
    <div class="form-row justify-content-center">
        <div class="form-group col-md-4 mx-auto">
            <label><?php echo __("Current Password")?></label>
            <input type="password" class="form-control text-center" name="curPw" required>
        </div>
    </div>
    <div class="form-row justify-content-center">
        <div class="form-group col-md-4 mx-auto">
            <label><?php echo __("New Password")?></label>
            <input type="password" class="form-control text-center" id='newPw' required>
            <div class="invalid-feedback" style='display:none' >
              Please Provide A New Password.
            </div>
        </div>
    </div>
    <div class="form-row justify-content-center">
        <div class="form-group col-md-4 mx-auto">
            <label><?php echo __("Confirm New Password")?></label>
            <input type="password" class="form-control text-center" name="newPw" id='reNewPw' required>
            <div class="invalid-feedback" style='display:none'>
              Please Confirm Your New Password.
            </div>
        </div>
    </div>

    <?= $this->Form->button(__('Submit'),['class'=>'btn btn-info w-25 mx-1', 'id'=>'changePWbtn']) ?>
    <?= $this->Form->end() ?>

  </div>
</div>

<script>
// password change feature
$("#changePWbtn").click(function(event){
    if($('#newPw').val() !== $('#reNewPw').val() ) {
        event.preventDefault();
        event.stopPropagation();
        swal("Error", "New password not matched! Please try again", "error");
        $('.invalid-feedback').show();
        $('#newPw').val('');
        $('#reNewPw').val('');
    }
});
</script>