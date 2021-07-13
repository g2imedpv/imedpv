<title>My Account</title>

<div class="card text-center w-50 my-3 mx-auto">
  <div class="card-header">
    <h3><?php echo __("My Account")?></h3>
  </div>
  <div class="card-body">
    <?= $this->Form->create() ?>
    <div class="form-row justify-content-center">
        <div class="form-group col-md-4 mx-auto">
            <label><?php echo __("User Role")?></label>
            <input type="text" class="form-control text-center" value="<?php echo $this->request->getSession()->read('Auth.User.role_name'); ?>" disabled>
        </div>
        <div class="form-group col-md-8 mx-auto">
            <label><?php echo __("Current Signin Company")?></label>
            <input type="text" class="form-control text-center" value="<?= h($company) ?>" disabled>
        </div>
    </div>
    <div class="form-row justify-content-center">
        <div class="form-group col-md-4 mx-auto">
            <label><?php echo __("Email Address")?></label>
            <input type="text" class="form-control text-center" name='email' value="<?php print $this->request->getSession()->read('Auth.User.email'); ?>">
        </div>
        <div class="form-group col-md-4">
            <label><?php echo __("First Name")?></label>
            <input type="text" class="form-control text-center" name="fName" value="<?php print $this->request->getSession()->read('Auth.User.firstname'); ?>">
        </div>
        <div class="form-group col-md-4">
            <label><?php echo __("Last Name")?></label>
            <input type="text" class="form-control text-center" name='lName' value="<?php print $this->request->getSession()->read('Auth.User.lastname'); ?>">
        </div>
    </div>

    <div class='d-flex justify-content-center'>
        <?= $this->Form->button(__('Submit'),['class'=>'btn btn-info w-50 mx-2']) ?>
        <a class="btn btn-warning mx-2" href="/sd-users/change_password" role="button"><?php echo __("Change Password")?></a>
        <a class="btn btn-danger mx-2" href="/sd-users/logout" role="button"><?php echo __("Log Out")?></a>
    </div>
    <?= $this->Form->end() ?>


  </div>
</div>