<title>Line Listing</title>
<head>

    <!-- For local DataTable CSS/JS link -->
    <?= $this->Html->css('datatable/dataTables.bootstrap4.min.css') ?>
    <?= $this->Html->script('datatable/DataTables/js/jquery.dataTables.min.js') ?>
    <?= $this->Html->script('datatable/DataTables/js/dataTables.bootstrap4.min.js') ?>

    <!-- For local DataTable File Export CSS/JS link -->
    <?= $this->Html->script('datatable/DataTables/js/dataTables.buttons.min.js') ?>
    <?= $this->Html->script('datatable/DataTables/js/jszip.min.js') ?>
    <?= $this->Html->script('datatable/DataTables/js/pdfmake.min.js') ?>
    <?= $this->Html->script('datatable/DataTables/js/vfs_fonts.js') ?>
    <?= $this->Html->script('datatable/DataTables/js/buttons.html5.min.js') ?>
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
    <table class="table table-bordered table-hover display" id="lineListTable">
            <thead>
                <tr>
                    <th scope="row"><?php echo __("Case No");?></th>
                    <!-- <th scope="row"><?php echo __("Case Version");?></th> -->
                    <th scope="row"><?php echo __("Country");?></th>
                    <th scope="row"><?php echo __("Suspect Drug");?></th>
                    <th scope="row"><?php echo __("Source Of Report");?></th>
                    <th scope="row"><?php echo __("Age");?></th>
                    <th scope="row"><?php echo __("Sex");?></th>
                    <th scope="row"><?php echo __("Dose");?></th>
                    <th scope="row"><?php echo __("Event");?></th>
                    <th scope="row"><?php echo __("Event (MedDra PT)");?></th>
                    <th scope="row"><?php echo __("Time To Onset");?></th>
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
                    $seriousness = array("Result in death", "Life Threatening", "Caused/Prolonged Hospitalisation", "Disabling / Incapacitating", "Congenital Anomaly/Birth Defect", "Other Medically Important Condition");
                    foreach($caseFields as $caseDetails){
                        echo "<tr id=\'caseId-".$caseDetails['id']."\'>";
                        echo"<td>".$caseDetails['caseNO']."</td>";
                        // echo"<td>".$caseDetails['version_no']."</td>";
                        echo"<td>";
                        if($caseDetails['country'] != null) echo $caseDetails['country'];
                        else echo $caseDetails['country_r3'];
                        echo"</td>";
                        echo"<td>".$caseDetails['suspectDrug']."</td>";
                        echo"<td>".$caseDetails['reportTypeValue']."</td>";
                        echo"<td>".$caseDetails['ageCount']." ".$caseDetails['ageUnitValue']."</td>";
                        echo"<td>".$caseDetails['sexValue']."</td>";
                        echo"<td>".$caseDetails['dose']."</td>";

                        // Event
                        echo"<td>".$caseDetails['reaction']."</td>";

                        // Event (MedDra PT)
                        if($caseDetails['meddra_llt'] != null){
                            list($lltTerm, $lltCode, $ptTerm, $ptCode, $hltTerm, $hltCode, $hlgtTerm, $hlgtCode, $socTerm, $socCode) = explode(",", $caseDetails['meddra_llt']);
                            // Shows LLT, PT, SOC
                            // echo"<td>".$lltTerm." / ".$ptTerm." / ".$socTerm."</td>";
                            // Shows PT ONLY
                            echo"<td>".$ptTerm."</td>";
                        }else echo "<td></td>";

                        // Time Onset
                        echo"<td>";
                        if ($caseDetails['durationStart'] != null)
                            echo substr($caseDetails['durationStart'], 0, 2). " / ".substr($caseDetails['durationStart'], 2, 2)." / ".substr($caseDetails['durationStart'], 4, 4);
                        echo " ~ ";
                        if ($caseDetails['durationReaction'] != null)
                            echo substr($caseDetails['durationReaction'], 0, 2). " / ".substr($caseDetails['durationReaction'], 2, 2)." / ".substr($caseDetails['durationReaction'], 4, 4);
                        echo "</td>";
                        echo"<td>".$caseDetails['outcomeValue']."</td>";
                        echo"<td>".$caseDetails['serverityValue']."</td>";
                        echo"<td>";
                        $textcauseOfDeath = "";
                        if ($caseDetails['causeOfDeath'] != null) {
                            for($i = 0; $i < 5; $i++){
                                if(substr($caseDetails['causeOfDeath'], $i, 1) == 1){
                                    $textcauseOfDeath = $textcauseOfDeath.$seriousness[$i];
                                    $textcauseOfDeath = $textcauseOfDeath." | ";
                                }
                            }
                        }else {
                            for($i = 19; $i < 24; $i++){
                                if ($caseDetails['causeOfDeath10'.$i] == 1){
                                    $textcauseOfDeath = $textcauseOfDeath.$seriousness[$i - 19];
                                    $textcauseOfDeath = $textcauseOfDeath." | ";
                                }
                            }
                        }
                        if (substr($textcauseOfDeath, -3) == " | "){
                            $textcauseOfDeath = substr($textcauseOfDeath, 0, strlen($textcauseOfDeath) - 3);
                        }
                        echo $textcauseOfDeath;
                        echo"</td>";
                        echo"<td>".$caseDetails['labeledValue']."</td>";
                        echo"<td>".$caseDetails['causalityValue']."</td>";
                        echo"<td >".$caseDetails['history']."</td>";
                        echo"<td>";
                        if($caseDetails['concomitant'] == 2) echo $caseDetails['concomitantDrug'];
                        echo "</td>";
                        echo"</tr>";
                    }
                ?>
            </tbody>
        </table>
  </div>