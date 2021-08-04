<title>Line Listing</title>
<head>
    <!-- For local DataTable CSS/JS link -->
    <?= $this->Html->css('datatable/dataTables.bootstrap4.min.css') ?>
    <?= $this->Html->css('datatable/buttons.dataTables.min.css') ?>
    <?= $this->Html->css('datatable/jquery.dataTables.min.css') ?>
    <?= $this->Html->script('datatable/DataTables/js/jquery.dataTables.min.js') ?>
    <?= $this->Html->script('datatable/DataTables/js/dataTables.bootstrap4.min.js') ?>
    <?= $this->Html->script('datatable/DataTables/js/dataTables.buttons.min.js') ?>
    <?= $this->Html->script('datatable/DataTables/js/buttons.colVis.min.js') ?>
    <?= $this->Html->script('cases/checkfieldsdetail.js') ?>
    <script>
    var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
    var userId = <?= $this->request->getSession()->read('Auth.User.id')?>;
    </script>
</head>

<div class="my-3 mx-auto formContainer">
    <div class="text-center">
        <p class="pageTitle">
            <?php echo __("Line Listing")?>
        </p>
    </div>
    <!-- <div>CIMOS fields</div>
    <?php
        foreach($cimosFields as $fieldId => $label) {
            ?> 
                <input name="filter[<?php echo $fieldId?>]" type="radio" value="1">
                <label><?php echo __($label)?></label> 
            <?php
        }
    ?>
    <div>Extra fields</div>
    <?php
        foreach($extraFields as $fieldId => $label) {
            ?> 
                <input name="filter[<?php echo $fieldId?>]" type="radio" value="1">
                <label><?php echo __($label)?></label> 
            <?php
        }
    ?> -->
    <button onclick="exportTable()">Export Table</button>
    <table class="table table-bordered table-hover display" id="lineListTable">
            <thead>
                <tr>
                    <th scope="row"><?php echo __("Case No");?></th>
                    <th scope="row"><?php echo __("Case Version");?></th>
                    <th scope="row"><?php echo __("Country of report origin");?></th>
                    <th scope="row"><?php echo __("Suspect Drug");?></th>
                    <th scope="row"><?php echo __("Source of Report");?></th>
                    <th scope="row"><?php echo __("Age of patient");?></th>
                    <th scope="row"><?php echo __("Sex of patient");?></th>
                    <th scope="row"><?php echo __("Dose of drug");?></th>
                    <th scope="row"><?php echo __("Duration of Treatment");?></th>
                    <th scope="row"><?php echo __("Description of reaction");?></th>
                    <th scope="row"><?php echo __("Event Meddra codding ( LLT / PT / SOC )");?></th>
                    <th scope="row"><?php echo __("Outcome");?></th>
                    <th scope="row"><?php echo __("Serverity");?></th>
                    <th scope="row"><?php echo __("Seriousness");?></th>
                    <th scope="row"><?php echo __("Labeling");?></th>
                    <th scope="row"><?php echo __("Causality");?></th>
                    <th scope="row"><?php echo __("Medical History");?></th>
                    <th scope="row"><?php echo __("Concomitant Medication");?></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    foreach($caseFields as $caseDetails){
                        echo "<tr id=\'caseId-".$caseDetails['id']."\'>";
                        echo"<td>".$caseDetails['caseNO']."</td>";
                        echo"<td>".$caseDetails['version_no']."</td>";
                        echo"<td>".$caseDetails['country']."</td>";
                        echo"<td>".$caseDetails['suspectDrug']."</td>";
                        echo"<td>".$caseDetails['reportTypeValue']."</td>";
                        echo"<td>".$caseDetails['ageCount']." ".$caseDetails['ageUnitValue']."</td>";
                        echo"<td>".$caseDetails['sexValue']."</td>";
                        echo"<td>".$caseDetails['dose']."</td>";
                        echo"<td>".$caseDetails['durationStart']." - ".$caseDetails['durationReaction']."</td>";
                        echo"<td>".$caseDetails['reaction']."</td>";
                        if($caseDetails['meddra_llt'] != null){
                            list($lltTerm, $lltCode, $ptTerm, $ptCode, $hltTerm, $hltCode, $hlgtTerm, $hlgtCode, $socTerm, $socCode) = explode(",", $caseDetails['meddra_llt']);
                            echo"<td>".$lltTerm." / ".$ptTerm." / ".$socTerm."</td>";
                        }else echo "<td></td>";
                            
                        echo"<td>".$caseDetails['outcomeValue']."</td>";
                        echo"<td>".$caseDetails['serverityValue']."</td>";
                        echo"<td>"."</td>";
                        echo"<td>".$caseDetails['labeled']."</td>";
                        echo"<td>".$caseDetails['causalityValue']."</td>";
                        echo"<td>".$caseDetails['history']."</td>";
                        echo"<td>".$caseDetails['concomitant']."  ".$caseDetails['concomitantDrug']."</td>";
                        echo"</tr>";
                    }
                ?>
            </tbody>
        </table>
  </div>