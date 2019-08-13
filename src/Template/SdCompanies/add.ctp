<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SdCompany $sdCompany
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Sd Companies'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Sd User Types'), ['controller' => 'SdUserTypes', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Sd User Type'), ['controller' => 'SdUserTypes', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Sd Users'), ['controller' => 'SdUsers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Sd User'), ['controller' => 'SdUsers', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="sdCompanies form mx-auto my-3 formContainer text-center" style="width: 80%;">
    <?= $this->Form->create($sdCompany) ?>
    <fieldset>
        <p class="pageTitle">
            <?php echo __("Add Company");?>
        </p>
        <div class="form-row">
            <div class="form-group col-md-3">
                <?php
                    echo "<div class=\"input select\"><label for=\"sd-user-type-id\">Sd User Type</label><select name=\"sd_user_type_id\" class=\"form-control\" id=\"sd-user-type-id\"><option value=\"1\">iSafetyDB Administrator</option><option value=\"2\">Sponsor</option><option value=\"3\">CRO</option><option value=\"4\">PI User</option><option value=\"5\">Call Center</option></select></div>";
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php 
                    echo "<div class=\"input text\"><label for=\"company-name\">Company Name</label><input class=\"form-control\" type=\"text\" name=\"company_name\" maxlength=\"255\" id=\"company-name\"/></div>";
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php
                echo '<div class="input text"><label for="company-email">Company Email</label><input class="form-control" type="text" name="company_email" maxlength="100" id="company-email"/></div>';
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php
                echo '<div class="input text"><label for="website">Website</label><input type="text" class="form-control" name="website" maxlength="255" id="website"/></div>';
                ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <?php
                    echo '<div class="input text"><label for="address-line1">Address Line1</label><input class="form-control" type="text" name="address_line1" maxlength="255" id="address-line1"/></div>';
                ?>
            </div>
            <div class="form-group col-md-6">
                <?php 
                    echo '<div class="input text"><label for="address-line2">Address Line2</label><input class="form-control" type="text" name="address_line2" maxlength="255" id="address-line2"/></div>';
                ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <?php
                    echo '<div class="input text"><label for="zipcode">Zipcode</label><input class="form-control" type="text" name="zipcode" maxlength="20" id="zipcode"/></div>';
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php 
                    echo '<div class="input text"><label for="city">City</label><input class="form-control" type="text" name="city" maxlength="50" id="city"/></div>';
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php
                echo '<div class="input text"><label for="state">State</label><input class="form-control" type="text" name="state" maxlength="50" id="state"/></div>';
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php
                echo '<div class="input text"><label for="country">Country</label><input class="form-control" type="text" name="country" maxlength="50" id="country"/></div>';
                ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <?php
                    echo '<div class="input text"><label for="cell-country-code">Cell Country Code</label><input class="form-control" type="text" name="cell_country_code" maxlength="5" id="cell-country-code"/></div>';
                ?>
            </div>
            <div class="form-group col-md-6">
                <?php 
                    echo '<div class="input text"><label for="cell-phone-no">Cell Phone No</label><input class="form-control" type="text" name="cell_phone_no" maxlength="20" id="cell-phone-no"/></div>';
                ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <?php
                    echo '<div class="input text"><label for="phone1-country-code">Phone1 Country Code</label><input class="form-control" type="text" name="phone1_country_code" maxlength="5" id="phone1-country-code"/></div>';
                ?>
            </div>
            <div class="form-group col-md-4">
                <?php 
                    echo '<div class="input text"><label for="phone1">Phone1</label><input class="form-control" type="text" name="phone1" maxlength="20" id="phone1"/></div>';
                ?>
            </div>
            <div class="form-group col-md-4">
                <?php 
                    echo '<div class="input text"><label for="extention1">Extention1</label><input class="form-control" type="text" name="extention1" maxlength="5" id="extention1"/></div>';
                ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <?php
                    echo '<div class="input text"><label for="phone2-country-code">Phone2 Country Code</label><input class="form-control" type="text" name="phone2_country_code" maxlength="5" id="phone2-country-code"/></div>';
                ?>
            </div>
            <div class="form-group col-md-4">
                <?php 
                    echo '<div class="input text"><label for="phone2">Phone2</label><input class="form-control" type="text" name="phone2" maxlength="20" id="phone2"/></div>';
                ?>
            </div>
            <div class="form-group col-md-4">
                <?php 
                    echo '<div class="input text"><label for="extention2">Extention2</label><input class="form-control" type="text" name="extention2" maxlength="5" id="extention2"/></div>';
                ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <?php
                    echo '<div class="input text"><label for="fax1-country-code">Fax1 Country Code</label><input class="form-control" type="text" name="fax1_country_code" maxlength="5" id="fax1-country-code"/></div>';
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php 
                    echo '<div class="input text"><label for="fax1">Fax1</label><input class="form-control" type="text" name="fax1" maxlength="20" id="fax1"/></div>';
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php
                echo '<div class="input text"><label for="fax2-country-code">Fax2 Country Code</label><input class="form-control" type="text" name="fax2_country_code" maxlength="5" id="fax2-country-code"/></div>';
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php
                echo '<div class="input text"><label for="fax2">Fax2</label><input class="form-control" type="text" name="fax2" maxlength="20" id="fax2"/></div>';
                ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <?php
                    echo '<div class="input number"><label for="transaction-currency">Transaction Currency</label><input class="form-control" type="number" name="transaction_currency" id="transaction-currency"/></div>';
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php 
                    echo '<div class="input number"><label for="no-of-billing-cycle">No Of Billing Cycle</label><input class="form-control" type="number" name="no_of_billing_cycle" id="no-of-billing-cycle"/></div>';
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php
                echo '<div class="input number"><label for="current-billing-cycle">Current Billing Cycle</label><input class="form-control" type="number" name="current_billing_cycle" id="current-billing-cycle"/></div>';
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php
                echo '<div class="input number"><label for="no-of-whodra">No Of Whodra</label><input class="form-control" type="number" name="no_of_whodra" id="no-of-whodra" value="0"/></div>';
                ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <?php
                    echo '<div class="input number required"><label for="status">Status</label><input class="form-control" type="number" name="status" required="required" id="status" value="1"/></div>';
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php 
                    echo '<div class="input number required"><label for="un-paid">Un Paid</label><input class="form-control" type="number" name="un_paid" required="required" id="un-paid" value="0"/></div>';
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php
                echo '<div class="input number"><label for="is-medra">Is Medra</label><input class="form-control" type="number" name="is_medra" id="is-medra"/></div>';
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php
                echo '<div class="input number"><label for="is-whodra">Is Whodra</label><input class="form-control" type="number" name="is_whodra" id="is-whodra"/></div>';
                ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <?php
                    echo '<div class="input number"><label for="create-by">Create By</label><input class="form-control" type="number" name="create_by" id="create-by"/></div>';
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php 
                    echo $this->Form->control('created_dt',['class'=>'form-control']);
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php
                echo '<div class="input number"><label for="modify-by">Modify By</label><input class="form-control" type="number" name="modify_by" id="modify-by"/></div>';
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
