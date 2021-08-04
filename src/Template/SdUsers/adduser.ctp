<title>Add User</title>

<div class="card text-center w-50 my-3 mx-auto">
    <div class="card-header">
        <h3<?= __('>Add User') ?></h3>
    </div>
    <div class="card-body">
        <?= $this->Form->create($sdUser) ?>
        <div class="form-row justify-content-center">
            <div class="form-group col-md-6 mx-auto">
                <?php echo $this->Form->control('email',[
                    'class'=>'form-control',
                    'label' => 'E-mail',
                    'required' => true
                ]); ?>
            </div>
            <div class="form-group col-md-3">
                <?php echo $this->Form->control('firstname',[
                    'class'=>'form-control',
                    'label' => 'First Name',
                    'required' => true
                ]); ?>
            </div>
            <div class="form-group col-md-3">
                <?php echo $this->Form->control('lastname',[
                    'class'=>'form-control',
                    'label' => 'Last Name',
                    'required' => true
                ]); ?>
            </div>
        </div>
        <div class="form-row justify-content-center">
            <div class="form-group col-md-6 mx-auto">
                <?php echo $this->Form->control('sd_role_id',[
                    'options' => $sdRolesOptions,
                    'class'=>'form-control',
                    'label' => 'Roles',
                    'empty' => 'Choose Role',
                    'required' => true
                ]); ?>
            </div>
            <div class="form-group col-md-6">
                <?php echo $this->Form->control('sd_company_id',[
                    'options' => $sdCompaniesOptions,
                    'class'=>'form-control',
                    'label' => 'Companies',
                    'empty' => 'Choose Company',
                    'required' => true
                ]); ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label>Password</label>
                <?php echo $this->Form->password('pw',[
                    'class'=>'form-control',
                    'required' => true
                ]); ?>
            </div>
        </div>

        <!-- Created Date - Normally hidden -->
        <div class="form-row d-none">
            <div class="form-group col-md-3">
                <?php echo $this->Form->control('status',[
                    'class'=>'form-control',
                    'label' => 'Status',
                    'default' => '1'
                ]); ?>
            </div>
            <div class="form-group col-md-3">
                <?php
                     echo $this->Form->control('created_dt',[
                        'class'=>'form-control',
                        'label' => 'Created Time',
                    ]);
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php
                   echo $this->Form->control('modified_dt',['class'=>'form-control']);
                ?>
            </div>
        </div>

        <?= $this->Form->button(__('Submit'),['class'=>'btn btn-info w-50 mx-2']) ?>
        <?= $this->Form->end() ?>
    </div>
</div>
