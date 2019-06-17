<title>My Account</title>

<div class="card text-center w-50 my-3 mx-auto">
  <div class="card-header">
    <h3><?php echo __("My Account")?></h3>
  </div>
  <div class="card-body">
    <!-- <h5 class="card-title">Special title treatment</h5> -->
    <div class="form-row">
        <div class="form-group col-md-6 mx-auto">
            <label><?php echo __("Email Address")?></label>
            <input type="text" class="form-control text-center" value="<?php print $this->request->getSession()->read('Auth.User.email'); ?>" disabled>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6 mx-auto">
            <label><?php echo __("User Role")?></label>
            <input type="text" class="form-control text-center" value="<?php echo $this->request->getSession()->read('Auth.User.role_name'); ?>" disabled>
        </div>
    </div>
    <div class="form-row justify-content-center">
        <div class="form-group col-md-3">
            <label><?php echo __("First Name")?></label>
            <input type="text" class="form-control text-center" value="<?php print $this->request->getSession()->read('Auth.User.firstname'); ?>">
        </div>
        <div class="form-group col-md-3">
            <label><?php echo __("Last Name")?></label>
            <input type="text" class="form-control text-center" value="<?php print $this->request->getSession()->read('Auth.User.lastname'); ?>">
        </div>
    </div>


    <a href="/sd-users/edit/<?php print $this->request->getSession()->read('Auth.User.id'); ?>" class="btn btn-info w-25 mx-1"><?php echo __("Change Password")?></a>
    <a href="#" class="btn btn-primary w-25 mx-1"><?php echo __("Save")?></a>
    <a role="button" class="btn btn-warning d-block mx-auto my-3 w-25" href="/sd-users/logout"><?php echo __("Log Out")?></a>

    <!-- <?php
    echo $userID;
    ?> -->

  </div>
</div>