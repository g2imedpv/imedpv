<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SdContact $sdContact
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Edit') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $sdContact->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $sdContact->id)]
            )
        ?></li>
        <!-- <li><?= $this->Html->link(__('Contacts List'), ['action' => 'index']) ?></li> -->
    </ul>
</nav>
<div class="sdContacts form mx-auto my-3 formContainer text-center" style="width: 60%;">
    <?= $this->Form->create($sdContact) ?>
    <fieldset>
        <p class="pageTitle">
            <?php echo __("Edit Contact");?>
        </p>
        <div class="form-row">
            <div class="form-group col-md-3">
                <?php
                    echo '<div class="input  text required"><label for="contact-type">Contact Type</label><input class="form-control" type="text" name="contact_type" required="required" maxlength="40" id="contact-type" value='.$sdContact["contact_type"].'></div>';
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php 
                    echo '<div class="input  text required"><label for="authority">Authority</label><input class="form-control" type="text" name="authority" required="required" maxlength="40" id="authority" value='.$sdContact['authority'].'></div>';
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php
                echo '<div class="input text required"><label for="blinded-report">Blinded Report</label><input class="form-control" type="text" name="blinded_report" required="required" maxlength="40" id="blinded-report" value='.$sdContact['blinded_report'].'></div>';
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php
                echo '<div class="input  checkbox"><input class="form-control" type="hidden" name="data_privacy" value="0"/><label for="data-privacy"><input type="checkbox" name="data_privacy" value='.$sdContact['data_privacy'].' id="data-privacy">Data Privacy</label></div>';
                ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <?php
                 echo '<div class="input text required"><label for="contactid">Contact Id</label><input class="form-control" type="text" name="contactId" required="required" maxlength="11" id="contactid" value='.$sdContact['contactId'].'></div>';
                ?>
            </div>
            <div class="form-group col-md-4">
                <?php
                echo '<div class="input text required"><label for="preferred-route">Preferred Route</label><input class="form-control" type="text" name="preferred_route" required="required" maxlength="40" id="preferred-route" value='.$sdContact['preferred_route'].'></div>';
                ?>
            </div>
            <div class="form-group col-md-4">
                <?php
                 echo '<div class="input text required"><label for="format-type">Format Type</label><input class="form-control" type="text" name="format_type" required="required" maxlength="40" id="format-type" value='.$sdContact['format_type'].'></div>';
                ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <?php 
                echo '<div class="input text required"><label for="title">Title</label><input class="form-control" type="text" name="title" required="required" maxlength="10" id="title" value='.$sdContact['title'].'></div>';
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php
                echo '<div class="input text required"><label for="given-name">Given Name</label><input class="form-control" type="text" name="given_name" required="required" maxlength="35" id="given-name" value='.$sdContact['given_name'].'></div>';
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php
                echo '<div class="input text required"><label for="family-name">Family Name</label><input class="form-control" type="text" name="family_name" required="required" maxlength="35" id="family-name" value='.$sdContact['family_name'].'></div>';
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php
                 echo '<div class="input text required"><label for="middle-name">Middle Name</label><input class="form-control" type="text" name="middle_name" required="required" maxlength="15" id="middle-name" value='.$sdContact['middle_name'].'></div>';
                ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <?php
                 echo "<div class=\"input text required\"><label for=\"address\">Address</label><input class=\"form-control\" type=\"text\" name=\"address\" required=\"required\" maxlength=\"100\" id=\"address\" value=\"$sdContact->address\"></div>";
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php
                echo "<div class=\"input text required\"><label for=\"address-extension\">Address Extension</label><input class=\"form-control\" type=\"text\" name=\"address_extension\" required=\"required\" maxlength=\"100\" id=\"address-extension\" value=\"$sdContact->address_extension\"></div>";
                ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <?php
                 echo '<div class="input text required"><label for="city">City</label><input class="form-control" type="text" name="city" required="required" maxlength="35" id="city" value='.$sdContact['city'].'></div>';
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php
                 echo '<div class="input text required"><label for="state-province">State Province</label><input class="form-control" type="text" name="state_province" required="required" maxlength="40" id="state-province" value='.$sdContact['state_province'].'></div>';
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php
                 echo '<div class="input text required"><label for="zipcode">Zipcode</label><input class="form-control" type="text" name="zipcode" required="required" maxlength="15" id="zipcode" value='.$sdContact['zipcode'].'></div>';
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php
                 echo '<div class="input text required"><label for="country">Country</label><input class="form-control" type="text" name="country" required="required" maxlength="30" id="country" value='.$sdContact['country'].'></div>';
                ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <?php
                echo '<div class="input tel required"><label for="phone">Phone</label><input class="form-control" type="tel" name="phone" required="required" maxlength="10" id="phone" value='.$sdContact['phone'].'></div>';
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php
                 echo '<div class="input text required"><label for="phone-extension">Phone Extension</label><input class="form-control" type="text" name="phone_extension" required="required" maxlength="10" id="phone-extension" value='.$sdContact['phone_extension'].'></div>';
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php
                 echo '<div class="input text required"><label for="fax">Fax</label><input class="form-control" type="text" name="fax" required="required" maxlength="10" id="fax" value='.$sdContact['fax'].'></div>';
                ?>
            </div>
            <div class="form-group col-md-3">
                <?php
                 echo '<div class="input text required"><label for="fax-extension">Fax Extension</label><input class="form-control" type="text" name="fax_extension" required="required" maxlength="10" id="fax-extension" value='.$sdContact['fax_extension'].'></div>';
                ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <?php
                 echo '<div class="input text required"><label for="email-address">Email Address</label><input class="form-control" type="text" name="email_address" required="required" maxlength="100" id="email-address" value='.$sdContact['email_address'].'></div>';
                ?>
            </div>
            <div class="form-group col-md-6">
                <?php
                 echo '<div class="input text required"><label for="website">Website</label><input class="form-control" type="text" name="website" required="required" maxlength="100" id="website" value='.$sdContact['website'].'></div>';
                ?>
            </div>
        </div>
    </fieldset>
    <button type="submit" class="btn btn-success w-25 mt-3 mx-auto"><?php echo __("Submit");?></button>
    <?= $this->Form->end() ?>
</div>
