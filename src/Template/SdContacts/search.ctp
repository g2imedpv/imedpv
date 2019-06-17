<?php
//debug($sdContacts);
use Cake\ORM\TableRegistry;
?>
<title><?php echo __("Search Contact");?></title>
<head>
    <?= $this->Html->script('dataentry/fieldLogic.js') ?>
<head>

<body>
    <div class="container ">
        <div class="col">
            <div class="card mt-3">
                <div class="card-header text-center">
                    <h3><?php echo __("Search Contact");?></h3>
                </div>
                <div class="card-body">
                    <div class="text-center">
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
                                <label><?php echo __("Contact Person");?></label>
                                <input type="text" class="form-control" id="Contact_person" name="Contact_person" placeholder="<?php echo __("Search Contact Person");?>">
                            </div>
                            <div class="form-group col-md-3">
                                <label><?php echo __("Contact Type");?></label>
                                <input type="text" class="form-control" id="Contact_Type" name="Contact_Type" placeholder="<?php echo __("Search Contact Type");?>">
                            </div>
                        </div>
                        <button  class="btn btn-primary w-25"><i class="fas fa-search"></i> <?php echo __("Search");?> </button>
                        <!-- <button id="advsearch" class="btn btn-outline-info"><i class="fas fa-keyboard"></i> Advanced Search</button> -->
                        <button class="clearsearch btn btn-outline-danger"><i class="fas fa-eraser"></i> <?php echo __("Clear");?> </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
                   
    <div class="mx-auto text-center w-75 mt-3 ">
        <h3><?php echo __("Contact List");?></h3>
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
                    foreach($query as $contacters){
                        echo "<tr id='contacter$contacters->id'>";
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