<?php
//debug($sdContacts);
use Cake\ORM\TableRegistry;
?>
<title>Search Contact</title>
<head>
<?= $this->Html->script('') ?>
<head>

<body>
    <div class="container ">
        <div class="col">
            <div class="card mt-3">
                <div class="card-header text-center">
                    <h3>Search Contact</h3>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <!-- Search Product -->
                        <span id="errorMsg" class="alert alert-danger" role="alert" style="display:none"></span>
                        <div id="addpro" class="form-row">
                            <div class="form-group col-md-3">
                                <label>Key Word</label>
                                <input type="text" class="form-control" id="key_word" name="key_word" placeholder="Search Key Word">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Contact ID</label>
                                <input type="text" class="form-control" id="Contact_ID" name="Contact_ID" placeholder="Search Contact ID">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Contact Person</label>
                                <input type="text" class="form-control" id="Contact_person" name="Contact_person" placeholder="Search Contact person">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Contact Type</label>
                                <input type="text" class="form-control" id="Contact_Type" name="Contact_Type" placeholder="Search Contact Type">
                            </div>
                        </div>
                        <button  class="btn btn-primary w-25"><i class="fas fa-search"></i> Search</button>
                        <!-- <button id="advsearch" class="btn btn-outline-info"><i class="fas fa-keyboard"></i> Advanced Search</button> -->
                        <button class="clearsearch btn btn-outline-danger"><i class="fas fa-eraser"></i> Clear Search</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
  <?php  $sdContacts = TableRegistry::get('sdContacts');
                $row1 =$sdContacts->find()
                    ->select(['contactId'])
                    ->where(['id=1']);
                    ?> 
                   
    <div class="mx-auto text-center w-75 mt-3 ">
        <h3>Contact List</h3>
        <table class="table table-bordered table-hover" id="contact_list">
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
                <tr>
                    <td><?echo $direct?></td>
                    <td>CRO</td>
                    <td>Email</td>
                    <td>2018888888</td>
                    <td>g2biopharmaservices@gmail.com</td>
                    <td>3637 Brunswick Pike Princeton,New Jersey,USA</td>
                    <td>www.g2-biopharma-services.com</td>
                </tr>
                <tr>
                    <td>G2 Services Inc.</td>
                    <td>Pharmaceutical Company</td>
                    <td>ESTRI Gateway</td>
                    <td>2015555555</td>
                    <td>G2ServicesInc@gmail.com</td>
                    <td>3637 Brunswick Pike Princeton,New Jersey,USA</td>
                    <td>www.g2-biopharma-services.com</td>
                </tr>
                <tr>
                    <td>Lucy</td>
                    <td>Health professional</td>
                    <td>Manual</td>
                    <td>2017777777</td>
                    <td>Lucy@gmail.com</td>
                    <td>3637 Brunswick Pike Princeton,New Jersey,USA</td>
                    <td>www.g2-biopharma-services.com</td>
                </tr>
                <tr>
                    <td>Services Inc.</td>
                    <td>WHO Collaborating Center for International Drug Monitoring</td>
                    <td>ESTRI Gateway</td>
                    <td>2016666666</td>
                    <td>Services Inc.@gmail.com</td>
                    <td>3637 Brunswick Pike Princeton,New Jersey,USA</td>
                    <td>www.g2-biopharma-services.com</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>