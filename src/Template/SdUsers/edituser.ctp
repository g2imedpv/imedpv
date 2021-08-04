<title>Edit User</title>

<div class="card text-center w-50 my-3 mx-auto">
    <div class="card-header">
        <h3><?= h($sdUser->firstname) ?>&nbsp;<?= h($sdUser->lastname) ?></h3>
    </div>
    <div class="card-body">
        <?= $this->Form->create($sdUser) ?>
        <div class="form-row justify-content-center">
            <div class="form-group col-md-6 mx-auto">
                <label><?php echo __("Email Address")?></label>
                <input type="text" class="form-control text-center" name='email' value="<?php print $sdUser->email ?>">
            </div>
            <div class="form-group col-md-3">
                <label><?php echo __("First Name")?></label>
                <input type="text" class="form-control text-center" name="firstname" value="<?php print $sdUser->firstname ?>">
            </div>
            <div class="form-group col-md-3">
                <label><?php echo __("Last Name")?></label>
                <input type="text" class="form-control text-center" name='lastname' value="<?php print $sdUser->lastname ?>">
            </div>
        </div>
        <?= $this->Form->button(__('Submit'),['class'=>'btn btn-info w-50 mx-2']) ?>
        <?= $this->Form->end() ?>
    </div>
</div>
