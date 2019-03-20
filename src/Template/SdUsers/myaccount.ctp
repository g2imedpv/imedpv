<title>My Account</title>

<div class="card text-center w-50 my-3 mx-auto">
  <div class="card-header">
    <h3>My Account</h3>
  </div>
  <div class="card-body">
    <!-- <h5 class="card-title">Special title treatment</h5> -->
    <div class="form-row">
        <div class="form-group col-md-6 mx-auto">
            <label>Email Address</label>
            <input type="text" class="form-control text-center" value="<?php print $this->request->getSession()->read('Auth.User.email'); ?>" disabled>
        </div>
    </div>
    <div class="form-row justify-content-center">
        <div class="form-group col-md-3">
            <label>First Name</label>
            <input type="text" class="form-control text-center" value="<?php print $this->request->getSession()->read('Auth.User.firstname'); ?>">
        </div>
        <div class="form-group col-md-3">
            <label>Last Name</label>
            <input type="text" class="form-control text-center" value="<?php print $this->request->getSession()->read('Auth.User.lastname'); ?>">
        </div>
    </div>


    <a href="/sd-users/edit/<?php print $this->request->getSession()->read('Auth.User.id'); ?>" class="btn btn-info w-25 mx-1">Change Password</a>
    <a href="#" class="btn btn-primary w-25 mx-1">Save</a>

    <!-- <?php
    echo $userID;
    ?> -->

  </div>
</div>