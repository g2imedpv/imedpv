<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SdContact $sdContact
 */
use Cake\ORM\TableRegistry;
?>
<title>Add Contact</title>
<head>
<?= $this->Html->script('dataentry/fieldLogic.js') ?>
<head>
<body>
    <form class="container" method="post" accept-charset="utf-8" action="/sd-contacts/addcontact" >
        <div class="card mt-3">
            <div class="card-header text-center">
                <h3>Add Contact</h3>
            </div>
            <div class="card-body prodiff">
            <?php echo $this->Form->create($sdContact);?>
                <fieldset>
                    <div class="form-row">      
                        <div class="form-group col-md-6">
                            <div class="input number required ">
                                <label for="contact-type">Contact Type</label>
                                <select class="form-control" name="contact_type" required="required" id="contact-type"  >
                                <option value=""></option>
                                <option value="1">Pharmaceutical Company</option>
                                <option value="2">Regulatory Authority</option>
                                <option value="3">Health professional</option>
                                <option value="4">Regional Pharmacovigilance Center</option>
                                <option value="5">WHO Collaborating Center for International Drug Monitoring</option>
                                <option value="6">Other</option>
                                <option value="7">CRO</option>
                                <option value="8">Call Center</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="input number required">
                                <label for="authority">Authority</label>
                                <input type="number" name="authority" required="required" id="authority" class="form-control" >
                            </div>
                        </div>
                    </div>
                    <div class="form-row">      
                        <div class="form-group col-md-6" >
                            <div class="input number required">
                                <label for="blinded-report">Blinded Report</label>
                                <select class="form-control" name="blinded_report" required="required" id="blinded-report" >
                                <option value=""></option>
                                <option value="1">Blinded</option>
                                <option value="2">Unblinded</option>
                                <option value="3">Both</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-6" >
                            <div class="input checkbox" style="margin-top:14px; text-align:justify; line-height:28px;">
                                <input type="hidden" name="data_privacy" value="0">
                                <label for="data-privacy">
                                <input type="checkbox" name="data_privacy" value="1" id="data-privacy" >Data Privacy</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <div class="input text required">
                                <label for="contactid">Contact Id</label>
                                <input type="text" name="contactId" required="required" maxlength="11" id="contactid"  class="form-control" >
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <div class="input number required">
                                <label for="preferred-route">Preferred Route</label>
                                <select class="form-control" name="preferred_route" required="required" id="preferred-route" >
                                <option value=""></option>
                                <option value="1">Email</option>
                                <option value="2">ESTRI Gateway</option>
                                <option value="3">Manual</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <div class="input number required">
                                <label for="format-type">Format Type</label>
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
                                <label for="title">Title</label>
                                <input type="text" name="title" required="required" maxlength="10" id="title" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <div class="input text required">
                                <label for="given-name">Given Name</label>
                                <input type="text" name="given_name" required="required" maxlength="35" id="given-name" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <div class="input text required">
                                <label for="family-name">Family Name</label>
                                <input type="text" name="family_name" required="required" maxlength="35" id="family-name" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <div class="input text required">
                                <label for="middle-name">Middle Name</label>
                                <input type="text" name="middle_name"  maxlength="15" id="middle-name" class="form-control" >
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <div class="input text required">
                                <label for="address">Address</label>
                                <input type="text" name="address" required="required" maxlength="100" id="address" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="input text required">
                                <label for="address-extension">Address Extension</label>
                                <input type="text" name="address_extension"  maxlength="100" id="address-extension" class="form-control" >
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <div class="input text required">
                                <label for="city">City</label>
                                <input type="text" name="city" required="required" maxlength="35" id="city" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <div class="input text required">
                                <label for="state-province">State Province</label>
                                <input type="text" name="state_province" required="required" maxlength="40" id="state-province" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <div class="input text required">
                                <label for="zipcode">Zipcode</label>
                                <input type="text" name="zipcode" required="required" maxlength="15" id="zipcode" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <div class="input number required">
                                <label for="country">Country</label>
                                <input type="number" name="country" required="required" id="country" class="form-control" >
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <div class="input tel required">
                                <label for="phone">Phone</label>
                                <input type="tel" name="phone" required="required" maxlength="10" id="phone" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <div class="input text required">
                                <label for="phone-extension">Phone Extension</label>
                                <input type="text" name="phone_extension"  maxlength="10" id="phone-extension" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <div class="input text required">
                                <label for="fax">Fax</label>
                                <input type="text" name="fax" required="required" maxlength="10" id="fax"  class="form-control">
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <div class="input text required">
                                <label for="fax-extension">Fax Extension</label>
                                <input type="text" name="fax_extension"  maxlength="10" id="fax-extension"  class="form-control" >
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <div class="input text required">
                                <label for="email-address">Email Address</label>
                                <input type="text" name="email_address" required="required" maxlength="100" id="email-address" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="input text required">
                                <label for="website">Website</label>
                                <input type="text" name="website"  maxlength="100" id="website"  class="form-control" >
                            </div>
                        </div>
                    </div>
                    <div class="form-row text-center">
                    <input type="submit" class="btn btn-success w-25 mt-3 mx-auto">
                    </div>
                </fieldset>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </form>
    <div class="mx-auto text-center w-75 mt-3 ">
        <h3>Contact List</h3>
        <table class="table table-bordered table-hover " id="contact_list">
            <thead>
                <tr>
                    <th scope="row">Contact ID</th>
                    <th scope="row">Contact Type</th>
                    <th scope="row">Contact Route</th>
                    <th scope="row">Contact Format</th>
                    <th scope="row" >Phone</th>
                    <th scope="row">Email</th>
                    <th scope="row">Address</th>
                    <th scope="row">City</th>
                    <th scope="row">State/Province</th>
                    <th scope="row">Country</th>
                    <th scope="row">website</th>
                </tr>
            </thead>
            <tbody>
                <?php $sdContacts = TableRegistry::get('sd_contacts');
                    $query = $sdContacts
                            ->find('all')
                            ->order(['id' => 'DESC']);
                    foreach($query as $contacters){
                        echo "<tr id='contacter$contacters->id' onclick='imedpv/sd-contacts/edit/$contacters->id'>";
                        echo"<td>".$contacters->contactId."</td>";
                        echo"<td>".$contacters->contact_type."</td>";
                        echo"<td>".$contacters->preferred_route."</td>";
                        echo"<td>".$contacters->format_type."</td>";
                        echo"<td>".$contacters->phone."</td>";
                        echo"<td>".$contacters->email_address."</td>";
                        echo"<td>".$contacters->address."</td>";
                        echo"<td>".$contacters->city."</td>";
                        echo"<td>".$contacters->state_province."</td>";
                        echo"<td>".$contacters->country."</td>";
                        echo"<td>".$contacters->website."</td>";
                        echo"</tr>";
                    }
                ?> 
            </tbody>
        </table>
    </div>
</body>
