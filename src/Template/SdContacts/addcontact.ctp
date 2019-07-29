<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SdContact $sdContact
 */
use Cake\ORM\TableRegistry;
?>
<title><?php echo __("Add Contact");?></title>
<head>
<?= $this->Html->script('contact/contact.js') ?>
<!-- For local DataTable CSS/JS link -->
<?= $this->Html->css('datatable/dataTables.bootstrap4.min.css') ?>
<?= $this->Html->script('datatable/DataTables/js/jquery.dataTables.min.js') ?>
<?= $this->Html->script('datatable/DataTables/js/dataTables.bootstrap4.min.js') ?>
<head>
<body>
    <div class="mx-auto my-3 formContainer text-center">
        <form class="container" method="post" accept-charset="utf-8" action="/sd-contacts/addcontact" >
            <?php echo $this->Form->create($sdContact);?>
                <fieldset>
                    <p class="pageTitle">
                        <?php echo __("Add Contact");?>
                    </p>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <div class="input number required ">
                                <label for="contact-type"><?php echo __("Contact Type");?></label>
                                <select class="form-control" name="contact_type" required="required" id="contact-type"  >
                                <option value=""></option>
                                <option value="1"><?php echo __("Pharmaceutical Company");?></option>
                                <option value="2"><?php echo __("Regulatory Authority");?></option>
                                <option value="3"><?php echo __("Health professional");?></option>
                                <option value="4"><?php echo __("Regional Pharmacovigilance Center");?></option>
                                <option value="5"><?php echo __("WHO Collaborating Center for International Drug Monitoring");?></option>
                                <option value="6"><?php echo __("Other");?></option>
                                <option value="7"><?php echo __("CRO");?></option>
                                <option value="8"><?php echo __("Call Center");?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="input number required">
                                <label for="authority"><?php echo __("Authority");?></label>
                                <input type="number" name="authority" required="required" id="authority" class="form-control" >
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6" >
                            <div class="input number required">
                                <label for="blinded-report"><?php echo __("Blinded Report");?></label>
                                <select class="form-control" name="blinded_report" required="required" id="blinded-report" >
                                <option value=""></option>
                                <option value="1"><?php echo __("Blinded");?></option>
                                <option value="2"><?php echo __("Unblinded");?></option>
                                <option value="3"><?php echo __("Both");?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6 position-relative" >
                            <div class="form-check input checkbox position-absolute" style="top:25px;">
                                <input type="hidden" name="data_privacy" value="0">
                                <label for="data-privacy">
                                    <input type="checkbox" name="data_privacy" value="1" id="data-privacy" ><?php echo __("Data Privacy");?>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <div class="input text required">
                                <label for="contactid"><?php echo __("Contact ID");?> </label>
                                <input type="text" name="contactId" required="required" maxlength="11" id="contactid"  class="form-control" >
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <div class="input number required">
                                <label for="preferred-route"><?php echo __("Preferred Route");?> </label>
                                <select class="form-control" name="preferred_route" required="required" id="preferred-route" >
                                <option value=""></option>
                                <option value="1"><?php echo __("Email");?> </option>
                                <option value="2"><?php echo __("ESTRI Gateway");?> </option>
                                <option value="3"><?php echo __("Manual");?> </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <div class="input number required">
                                <label for="format-type"><?php echo __("Format Type");?></label>
                                <select class="form-control" name="format_type" required="required" id="format-type" >
                                <option value=""></option>
                                <option value="1">E2B</option>
                                <option value="2">CIOMS</option>
                                <option value="3">MedWatch</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <div class="input text required">
                                <label for="title"><?php echo __("Title");?></label>
                                <input type="text" name="title" required="required" maxlength="10" id="title" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <div class="input text required">
                                <label for="given-name"><?php echo __("Given Name");?></label>
                                <input type="text" name="given_name" required="required" maxlength="35" id="given-name" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <div class="input text required">
                                <label for="family-name"><?php echo __("Family Name");?></label>
                                <input type="text" name="family_name" required="required" maxlength="35" id="family-name" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <div class="input text required">
                                <label for="middle-name"><?php echo __("Middle Name");?></label>
                                <input type="text" name="middle_name"  maxlength="15" id="middle-name" class="form-control" >
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <div class="input text required">
                                <label for="address"><?php echo __("Address");?></label>
                                <input type="text" name="address" required="required" maxlength="100" id="address" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="input text required">
                                <label for="address-extension"><?php echo __("Address Extension");?></label>
                                <input type="text" name="address_extension"  maxlength="100" id="address-extension" class="form-control" >
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <div class="input text required">
                                <label for="city"><?php echo __("City");?></label>
                                <input type="text" name="city" required="required" maxlength="35" id="city" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <div class="input text required">
                                <label for="state-province"><?php echo __("State/Province");?></label>
                                <input type="text" name="state_province" required="required" maxlength="40" id="state-province" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <div class="input text required">
                                <label for="zipcode"><?php echo __("Zip Code");?></label>
                                <input type="text" name="zipcode" required="required" maxlength="15" id="zipcode" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <div class="input number required">
                                <label for="country"><?php echo __("Country");?></label>
                                <select name="country" required="required" id="country" class="form-control">
                                <option value=""></option>
                                <?php 
                                $sdLookups = TableRegistry::get('sd_field_value_look_ups');
                                $coutry = $sdLookups
                                         ->find('all')
                                         ->where(['sd_field_id=178'])
                                         ->order(['id' => 'DESC']);
                                foreach($coutry as $coutries){
                                echo "<option value=\"$coutries->value\">".$coutries->caption."</option>"; }
                                ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <div class="input tel required">
                                <label for="phone"><?php echo __("Phone");?></label>
                                <input type="tel" name="phone" required="required" maxlength="10" id="phone" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <div class="input text required">
                                <label for="phone-extension"><?php echo __("Phone Extension");?></label>
                                <input type="text" name="phone_extension"  maxlength="10" id="phone-extension" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <div class="input text required">
                                <label for="fax"><?php echo __("Fax");?></label>
                                <input type="text" name="fax" required="required" maxlength="10" id="fax"  class="form-control">
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <div class="input text required">
                                <label for="fax-extension"><?php echo __("Fax Extension");?></label>
                                <input type="text" name="fax_extension"  maxlength="10" id="fax-extension"  class="form-control" >
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <div class="input text required">
                                <label for="email-address"><?php echo __("Email Address");?></label>
                                <input type="text" name="email_address" required="required" maxlength="100" id="email-address" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="input text required">
                                <label for="website"><?php echo __("Website");?></label>
                                <input type="text" name="website"  maxlength="100" id="website"  class="form-control" >
                            </div>
                        </div>
                    </div>
                    <div class="form-row text-center">
                    <button type="submit" class="btn btn-success w-25 mt-3 mx-auto"><?php echo __("Submit");?></button>
                    </div>
                </fieldset>
                <?= $this->Form->end() ?>
        </form>

        <hr class="my-4">

        <p class="pageTitle">
            <?php echo __("Contact List");?>
        </p>
        <table class="table table-bordered table-hover mb-5" id="contact_list">
            <thead>
                <tr>
                    <th scope="row"><?php echo __("Contact ID");?></th>
                    <th scope="row"><?php echo __("Contact Type");?></th>
                    <th scope="row"><?php echo __("Contact Route");?></th>
                    <th scope="row"><?php echo __("Contact Format");?></th>
                    <th scope="row"><?php echo __("Phone");?></th>
                    <th scope="row"><?php echo __("Email");?></th>
                    <th scope="row"><?php echo __("Address");?></th>
                    <th scope="row"><?php echo __("City");?></th>
                    <th scope="row"><?php echo __("State/Province");?></th>
                    <th scope="row"><?php echo __("Country");?></th>
                    <th scope="row"><?php echo __("Website");?></th>
                    <th scope="row"><?php echo __("Action");?></th>
                </tr>
            </thead>
            <tbody>
                <?php $sdContacts = TableRegistry::get('sd_contacts');
                    $query = $sdContacts
                            ->find('all')
                            ->order(['id' => 'DESC']);
                    $study_type=["","E2B", "CIOMS","MedWatch"];
                    $comtact_type=["","Pharmaceutical Company","Regulatory Authority","Health professional","Regional Pharmacovigilance Center","WHO Collaborating Center for International Drug Monitoring","Other","CRO","Call Center"];
                    $comtact_route=["","Email","ESTRI Gateway","Manual"];
                    $sdLookups = TableRegistry::get('sd_field_value_look_ups');
                    $country = $sdLookups
                                ->find()
                                ->select(['value','caption'])
                                ->where(['sd_field_id=178'])
                                ->order(['id' => 'ASC']);
                    foreach($country as $key=>$countries){
                        $countryarray[$countries['value']]=$countries['caption'];
                    }
                    
                    foreach($query as $contacters){
                        echo "<tr id='contacter$contacters->id' onclick='imedpv/sd-contacts/edit/$contacters->id'>";
                        echo"<td>".$contacters->contactId."</td>";
                        echo"<td>".$comtact_type[$contacters->contact_type]."</td>";
                        echo"<td>".$comtact_route[$contacters->preferred_route]."</td>";
                        echo"<td>".$study_type[$contacters->format_type]."</td>";
                        echo"<td>".$contacters->phone."</td>";
                        echo"<td>".$contacters->email_address."</td>";
                        echo"<td>".$contacters->address."</td>";
                        echo"<td>".$contacters->city."</td>";
                        echo"<td>".$contacters->state_province."</td>";
                        echo"<td>".$countryarray[$contacters->country]."</td>";
                        echo"<td>".$contacters->website."</td>";
                        echo"<td><a class=\"btn btn-outline-info btn-sm\"  role=\"button\" href=\"/sd-contacts/edit/$contacters->id\"><i class=\"fas fa-edit\"></i></a></td>";
                        echo"</tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>
</body>
