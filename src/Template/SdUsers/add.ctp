<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SdUser $sdUser
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <!-- <li class="heading"><?= __('Actions') ?></li> -->
        <li><?= $this->Html->link(__('User List'), ['action' => 'index']) ?></li>
        <!-- <li><?= $this->Html->link(__('List Sd Roles'), ['controller' => 'SdRoles', 'action' => 'index']) ?></li> -->
        <!-- <li><?= $this->Html->link(__('New Sd Role'), ['controller' => 'SdRoles', 'action' => 'add']) ?></li> -->
        <li><?= $this->Html->link(__('Company List'), ['controller' => 'SdCompanies', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'SdCompanies', 'action' => 'add']) ?></li>
        <!-- <li><?= $this->Html->link(__('List Sd Activity Logs'), ['controller' => 'SdActivityLogs', 'action' => 'index']) ?></li> -->
        <!-- <li><?= $this->Html->link(__('New Sd Activity Log'), ['controller' => 'SdActivityLogs', 'action' => 'add']) ?></li> -->
        <!-- <li><?= $this->Html->link(__('List Sd Cases'), ['controller' => 'SdCases', 'action' => 'index']) ?></li> -->
        <!-- <li><?= $this->Html->link(__('New Sd Case'), ['controller' => 'SdCases', 'action' => 'add']) ?></li> -->
        <!-- <li><?= $this->Html->link(__('List Sd Product Workflows'), ['controller' => 'SdProductWorkflows', 'action' => 'index']) ?></li> -->
        <!-- <li><?= $this->Html->link(__('New Sd Product Workflow'), ['controller' => 'SdProductWorkflows', 'action' => 'add']) ?></li> -->
        <!-- <li><?= $this->Html->link(__('List Sd User Assignments'), ['controller' => 'SdUserAssignments', 'action' => 'index']) ?></li> -->
        <!-- <li><?= $this->Html->link(__('New Sd User Assignment'), ['controller' => 'SdUserAssignments', 'action' => 'add']) ?></li> -->
    </ul>
</nav>
<div class="sdUsers form mx-auto my-3 formContainer text-center" style="width: 80%;">
    <?= $this->Form->create($sdUser) ?>
    <fieldset>
        <p class="pageTitle">
            <?php echo __("Add User");?>
        </p>
        <div class="form-row">
            <div class="form-group col-md-6">
                <?php
                    echo $this->Form->control('sd_role_id', ['options' => $sdRoles,'class'=>'form-control']);
                ?>
            </div>
            <div class="form-group col-md-6">
                <?php 
                    echo $this->Form->control('sd_company_id', ['options' => $sdCompanies, 'class'=>'form-control','empty' => true]);
                ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <?php
                    echo $this->Form->control('firstname',['class'=>'form-control']);
                ?>
            </div>
            <div class="form-group col-md-4">
                <?php 
                     echo $this->Form->control('lastname',['class'=>'form-control']);
                ?>
            </div>
            <div class="form-group col-md-4">
                <?php 
                   echo $this->Form->control('username',['class'=>'form-control']);
                ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <?php
                    echo $this->Form->control('email',['class'=>'form-control']);
                ?>
            </div>
            <div class="form-group col-md-6">
                <?php 
                    echo $this->Form->control('password',['class'=>'form-control']);
                ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <?php
                    echo $this->Form->control('thumbnail',['class'=>'form-control']);
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php 
                     echo $this->Form->control('site_number',['class'=>'form-control']);
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php 
                   echo $this->Form->control('site_name',['class'=>'form-control']);
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php 
                   echo $this->Form->control('title',['class'=>'form-control']);
                ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <?php
                    echo $this->Form->control('phone_country_code',['class'=>'form-control']);
                ?>
            </div>
            <div class="form-group col-md-4">
                <?php 
                     echo $this->Form->control('phone',['class'=>'form-control']);
                ?>
            </div>
            <div class="form-group col-md-4">
                <?php 
                   echo $this->Form->control('extention',['class'=>'form-control']);
                ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <?php
                    echo $this->Form->control('cell_country_code',['class'=>'form-control']);
                ?>
            </div>
            <div class="form-group col-md-4">
                <?php 
                     echo $this->Form->control('cell_phone_no',['class'=>'form-control']);
                ?>
            </div>
            <div class="form-group col-md-4">
                <?php 
                   echo $this->Form->control('verification',['class'=>'form-control']);
                ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <?php
                    echo $this->Form->control('phone_alert',['class'=>'form-control']);
                ?>
            </div>
            <div class="form-group col-md-4">
                <?php 
                     echo $this->Form->control('email_alert',['class'=>'form-control']);
                ?>
            </div>
            <div class="form-group col-md-4">
                <?php 
                   echo $this->Form->control('is_never',['class'=>'form-control']);
                ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <?php
                    echo $this->Form->control('account_expire_date',['empty' => true]);
                ?>
            </div>
            <div class="form-group col-md-6">
                <?php 
                     echo $this->Form->control('is_email_verified');
                ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <?php 
                   echo $this->Form->control('reset_password_expire_time',['empty' => true]);
                ?>
            </div>
            <div class="form-group col-md-6">
                <?php 
                   echo $this->Form->control('is_import_user');
                ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <?php
                    echo $this->Form->control('is_medra',['class'=>'form-control']);
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php 
                     echo $this->Form->control('is_whodra',['class'=>'form-control']);
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php 
                   echo $this->Form->control('job_title',['class'=>'form-control']);
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php 
                   echo $this->Form->control('assign_protocol',['class'=>'form-control']);
                ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <?php
                    echo $this->Form->control('status',['class'=>'form-control']);
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php 
                     echo $this->Form->control('default_language',['class'=>'form-control']);
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php 
                   echo $this->Form->control('is_imedsae_tracking',['class'=>'form-control']);
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php 
                   echo $this->Form->control('is_imed_safety_database',['class'=>'form-control']);
                ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <?php
                    echo $this->Form->control('created_by',['class'=>'form-control']);
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php 
                     echo $this->Form->control('created_dt',['class'=>'form-control']);
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php 
                   echo $this->Form->control('modified_by',['class'=>'form-control']);
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php 
                   echo $this->Form->control('modified_dt',['class'=>'form-control']);
                ?>
            </div>
        </div>
    </fieldset>
    <button type="submit" class="btn btn-success w-25 mt-3 mx-auto"><?php echo __("Submit");?></button>
    <?= $this->Form->end() ?>
</div>
