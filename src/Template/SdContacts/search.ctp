<?php
//debug($sdContacts);
use Cake\ORM\TableRegistry;
?>
<title><?php echo __("Search Contact");?></title>
<head>
<?= $this->Html->script('contact/contact.js') ?>
<!-- For local DataTable CSS/JS link -->
<?= $this->Html->css('datatable/dataTables.bootstrap4.min.css') ?>
<?= $this->Html->script('datatable/DataTables/js/jquery.dataTables.min.js') ?>
<?= $this->Html->script('datatable/DataTables/js/dataTables.bootstrap4.min.js') ?>
<head>
<script type="text/javascript">
    var userId = <?= $this->request->getSession()->read('Auth.User.id')?>;
    var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
</script>

<body>
    <div class="mx-auto my-3 formContainer text-center">
        <p class="pageTitle">
            <?php echo __("Search Contact");?>
        </p>
        <!-- Search Product -->
        <span id="errorMsg" class="alert alert-danger" role="alert" style="display:none"></span>
        <div id="addpro" class="form-row">
            <div class="form-group col-md-3">
                <label><?php echo __("Key Word");?></label>
                <input type="text" class="form-control" id="key_word" name="key_word" placeholder="<?php echo __("Search Key Word");?>">
            </div>
            <div class="form-group col-md-3">
                <label><?php echo __("Contact ID");?></label>
                <input type="text" class="form-control" id="Contact_ID" name="Contact_ID" placeholder="<?php echo __("Search Contact ID");?>">
            </div>
            <div class="form-group col-md-3">
                <label><?php echo __("Contact Route");?></label>
                <input type="text" class="form-control" id="Contact_Route" name="Contact_Route" placeholder="<?php echo __("Search Contact Route");?>">
            </div>
            <div class="form-group col-md-3">
                <label><?php echo __("Contact Type");?></label>
                <input type="text" class="form-control" id="Contact_Type" name="Contact_Type" placeholder="<?php echo __("Search Contact Type");?>">
            </div>
        </div>
        <button  class="btn btn-primary w-25" onclick="searchContact()"><i class="fas fa-search"></i> <?php echo __("Search");?> </button>
        <!-- <button id="advsearch" class="btn btn-outline-info"><i class="fas fa-keyboard"></i> Advanced Search</button> -->
        <button class="clearsearch btn btn-outline-danger"><i class="fas fa-eraser"></i> <?php echo __("Clear");?> </button>

        <hr class="my-4">

        <p class="pageTitle">
            <?php echo __("Contact List");?>
        </p>
        <table class="table table-bordered table-hover " id="contact_list">
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
                </tr>
            </thead>
            <tbody>
                <?php $sdContacts = TableRegistry::get('sd_contacts');
                    $query = $sdContacts->find('all');
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
                        echo "<tr id='contacter$contacters->id'>";
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
                        echo"</tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>
</body>